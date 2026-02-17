<?php

session_start();
$ruta_raiz = __DIR__.'/../../';


include_once "$ruta_raiz/processConfig.php";
include_once "$ruta_raiz/util.php";
include_once "$ruta_raiz/include/db/ConnectionHandler.php";
include_once "$ruta_raiz/clasesComunes/datosDest.php";
include_once "$ruta_raiz/jh_class/funciones_sgd.php";
include_once "$ruta_raiz/include/PHPMailer_v5.1/class.phpmailer.php";
include_once "$ruta_raiz/include/crypt/Crypt.php";
include_once "$ruta_raiz/envios/electronicos/certificadores.php";

$db = new ConnectionHandler("$ruta_raiz");
$ids = is_array($_REQUEST['id']) ? $_REQUEST['id'] : [$_REQUEST['id']];
$respuesta = [];
$proveedor_correo_certificado = 'certicamara';
$proveedor_generico = 'generico';

foreach($ids as $id) {
    $rad_envio = $db->conn->getRow('SELECT * FROM sgd_rad_envios WHERE id = ?', [$id]);
    $anexo = $db->conn->getRow('SELECT * FROM anexos WHERE id = ?', [$rad_envio['ID_ANEXO']]);
    $sgd_dir_direccion = $db->conn->getRow('SELECT * FROM sgd_dir_drecciones WHERE id = ?', [$rad_envio['ID_DIRECCION']]);
    if(empty($sgd_dir_direccion))
    {
        $db->conn->Execute('DELETE FROM sgd_rad_envios WHERE id = ?', [$id]);
        continue;
    }
    $registro = json_decode($rad_envio['REGISTRO'] ?? '[]', true);
    $correos = explode(';', $sgd_dir_direccion['SGD_DIR_MAIL']);
    $radicado = $db->conn->getRow('SELECT * FROM radicado WHERE radi_nume_radi = ?', [$anexo['RADI_NUME_SALIDA']]);
    $certificador = $rad_envio['CERTIFICADO'] == 't' ? $proveedor_correo_certificado : $proveedor_generico;

    $registro = array_filter($registro, function($entry) {
        return $entry['status'] !== 'error';
    });

    if ($radicado['RADI_PATH']) 
    {
        $linkImagenes .= "<a href='".$servidorOrfeoBodega.$radicado['RADI_PATH']."'>Imagen Radicado ".$radicado['RADI_NUME_RADI']." </a><br>";
    } else {
        $linkImagenes .= "Radicado ".$radicado['RADI_NUME_RADI']." sin documetno Asociado<br>";
    }

    $correos_validos = [];

    foreach ($correos as $correo) {
        $correo = trim(strtolower($correo));
        if (array_search($correo, array_column($registro, 'correo')) !== false)
            continue;
        
        try {
            esEmailValido($correo);
            $correos_validos[] = $correo;
        } catch (Exception $e) {
            $registro[] = [
                'correo' => $correo, 
                'status' => 'error', 
                'message' => $e->getMessage(),
                'regenvio' => 0,
                'timestamp' => date('Y-m-d H:i:s'),
                'certificador' => $certificador
            ];
        }
    }

    try {
        $subject = "$entidad: radicado ".$radicado['RADI_NUME_RADI'];
        $msgHtml = file_get_contents($ruta_raiz."/conf/envioDigital.html");
        $reemplazos = [
            "*RAD_S*" => $radicado['RADI_NUME_RADI'],
            "*USUARIO*" => $_SESSION["krd"],
            "*LINK_ANEXOS*" => getBaseUrl().'/2/lista_anexos_consulta.php?radiNume='.encrypt_decrypt('encrypt', $radicado['RADI_NUME_RADI'], '97SUP3RC0R33UDKA7128409EJA'),
            "*IMAGEN*" => str_replace("*SERVIDOR_IMAGEN*", $servidorOrfeoBodega, $linkImagenes),
            "*ASUNTO*" => htmlentities($subject, ENT_QUOTES | ENT_IGNORE, "UTF-8"),
            "*ENTIDAD_LARGO*" => $_SESSION["entida_largo"],
            "*DEPENDENCIA_NOMBRE*" => $_SESSION["depe_nomb"],
            "*RADICADO_PADRE*" => $radicado['RADI_NUME_RADI']
        ];

        $msgHtml = str_replace(array_keys($reemplazos), array_values($reemplazos), $msgHtml);
        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->SMTPAuth = true;
        $mail->Host = trim($servidorSmtp);
        $mail->Port = trim($puertoSmtp);
        $mail->Username = trim($correoSaliente);
        $mail->Password = trim($passwordCorreoSaliente);
        $mail->SMTPSecure = trim($SMTPSecure);
        $mail->From = trim($correoSaliente);
        $mail->FromName = 'Supersalud';
        foreach($correos_validos as $correo) {
            $mail = aplicarCertificador($mail, $correo, $certificador);
        }

        $mail->Subject = $subject;
        $mail->Body = $msgHtml;
        $mail->IsHTML(true);

        if (!$mail->Send()) {
            error_log("Error al enviar a correo: {$mail->ErrorInfo}");
            throw new Exception($mail->ErrorInfo);
        } else {
            foreach($correos_validos as $correo) {
                $registro[] = [
                    'correo' => $correo,
                    'status' => 'success',
                    'message' => 'Email enviado con éxito.',
                    'regenvio' => 0,
                    'timestamp' => date('Y-m-d H:i:s'),
                    'certificador' => $certificador
                ];
            }
        }
    } catch(Exception $e) {
        foreach($correos_validos as $correo) {
            $registro[] = [
                'correo' => $correo,
                'status' => 'error',
                'message' => $e->getMessage(),
                'regenvio' => 0,
                'timestamp' => date('Y-m-d H:i:s'),
                'certificador' => $certificador
            ];
        }
    }

    $envio_completo = true;

    foreach($registro as $key => $r) {
        if ($r['status'] === 'error') {
            $envio_completo = false;
        }

        if ($r['status'] === 'success' && $r['regenvio'] === 0)
        {
            $dat = new DATOSDEST(
                $db,
                $anexo['RADI_NUME_SALIDA'],
                null,
                $anexo['SGD_DIR_TIPO'],
                $anexo['SGD_DIR_TIPO'],
            );

            $pCodDep = $dat->codep_us;
            $pCodMun = $dat->muni_us;
            $pNombre = $dat->nombre_us;
            $pPriApe = $dat->prim_apel_us;
            $pSegApe = $dat->seg_apel_us;
            $nombre_us = substr($pNombre." ".$pPriApe." ".$pSegApe, 0, 33);
            $direccion_us = $dat->direccion_us;
            $dir_codigo = $dat->documento_us;

            $localizacion = new LOCALIZACION($pCodDep, $pCodMun, $db);
            $departamento_us = $localizacion->departamento;
            $destino = $localizacion->municipio;
            $pais_us = $localizacion->GET_NOMBRE_PAIS($dat->idpais,$db);

            $regenvio['USUA_DOC'] = $_SESSION["usua_doc"];
            $regenvio["SGD_RENV_CODIGO"] = intval($db->conn->getOne("SELECT MAX(SGD_RENV_CODIGO) + 1 FROM SGD_RENV_REGENVIO"));
            $regenvio["SGD_FENV_CODIGO"] = 106;
            $regenvio["SGD_RENV_FECH"] = "now()";
            $regenvio["RADI_NUME_SAL"] = $radicado['RADI_NUME_RADI'];
            $regenvio["SGD_RENV_DESTINO"] = $r['correo'];
            $regenvio["SGD_RENV_MAIL"] = $r['correo'];
            $regenvio["SGD_RENV_CERTIFICADO"] = 0;
            $regenvio["SGD_RENV_ESTADO"] = 1;
            $regenvio["SGD_RENV_NOMBRE"] = $nombre_us;
            $regenvio["SGD_DIR_CODIGO"] = $dir_codigo;
            $regenvio["DEPE_CODI"] = $_SESSION["dependencia"];
            $regenvio["SGD_DIR_TIPO"] = $anexo['SGD_DIR_TIPO'];
            $regenvio["RADI_NUME_GRUPO"] = $radicado['RADI_NUME_RADI'];
            $regenvio["SGD_RENV_PLANILLA"] = 0;
            $regenvio["SGD_RENV_DIR"] = "email: ".$r['correo'];
            $regenvio["SGD_RENV_DEPTO"] = $departamento_us;
            $regenvio["SGD_RENV_MPIO"] = $destino;
            $regenvio["SGD_RENV_PAIS"] = $pais_us;
            $regenvio["SGD_RENV_OBSERVA"] = "";
            $regenvio["SGD_RENV_CANTIDAD"] = 1;

            $keys = array_keys($regenvio);
            $keys = implode(',',$keys);

            $db->conn->Execute('
                INSERT INTO sgd_renv_regenvio ('.$keys.') 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', 
                $regenvio);

            $registro[$key]['regenvio'] = 1;
        }
    }

    $db->conn->Execute("UPDATE sgd_rad_envios SET registro = ?, devuelto = ?, fecha = NOW() WHERE id = ?", [json_encode($registro, true), 'f', $id]);

    if ($envio_completo) 
    {
        $db->conn->Execute("UPDATE sgd_rad_envios SET estado = 2, fecha = NOW() WHERE id = ?", [$id]);
    }

    $envios_pedientes = intval($db->conn->getOne('SELECT count(id) FROM sgd_rad_envios WHERE estado <> 2 AND id_anexo = ?', [$anexo['ID']]));

    if ($envios_pedientes == 0)
    {
        $db->conn->Execute("UPDATE anexos SET anex_estado = 4 WHERE id = ?", [$anexo['ID']]);
    }

    $respuesta[$id] = $registro;
}

header('Content-Type: application/json');
echo json_encode([
    'message' => 'Correos enviados y registro actualizado.',
    'registro' => $respuesta
], true);
