<?php
session_start();
error_reporting(7);
foreach ($_GET as $key => $valor) {
    ${$key} = $valor;
}
foreach ($_POST as $key => $valor) {
    ${$key} = $valor;
}
$ruta_raiz = "..";
include "$ruta_raiz/include/tx/sanitize.php";
$krd = $_SESSION["krd"];
$dependencia = $_SESSION["dependencia"];
$usua_doc = $_SESSION["usua_doc"];
$codusuario = $_SESSION["codusuario"];
$ruta_raiz = "..";
header('Content-Type: text/html; charset=UTF-8');
if (!$fecha_busq) {
    $fecha_busq = date("Y-m-d");
}
if (!$fecha_busq2) {
    $fecha_busq2 = date("Y-m-d");
}
include '../processConfig.php';
include_once "$ruta_raiz/include/tx/Anulacion.php";
include_once "$ruta_raiz/include/tx/Historico.php";
include_once "$ruta_raiz/include/db/ConnectionHandler.php";

$db = new ConnectionHandler("$ruta_raiz");

if ($cancelarAnular) {
    $aceptarAnular = "";
    $actaNo = "";
}
//$db->conn->debug = true;
$depe_codi_territorial = $_SESSION['depe_codi_territorial'];

if ($generar_informe || $aceptarAnular) {
    if ($depeBuscada && $depeBuscada != 0 && $depeBuscada != 9999) {
        $whereDependencia = " b.DEPE_CODI=$depeBuscada AND";
    }
    include_once "../include/query/busqueda/busquedaPiloto1.php";
    include "$ruta_raiz/include/query/anulacion/queryanularRadicados.php";

    $fecha_ini = $fecha_busq;
    $fecha_fin = $fecha_busq2;
    $fecha_ini = mktime(00, 00, 00, substr($fecha_ini, 5, 2), substr($fecha_ini, 8, 2), substr($fecha_ini, 0, 4));
    $fecha_fin = mktime(23, 59, 59, substr($fecha_fin, 5, 2), substr($fecha_fin, 8, 2), substr($fecha_fin, 0, 4));

    $query = "select $radi_nume_radi as radi_nume_radi, r.radi_fech_radi, r.ra_asun, r.radi_usua_actu,
        r.radi_depe_actu, r.radi_usu_ante, c.depe_nomb, b.sgd_anu_sol_fech, b.sgd_anu_desc as sgd_anu_desc  , c.depe_codi  
        from radicado r, sgd_anu_anulados b, dependencia c";
    $fecha_mes = substr($fecha_ini, 0, 7);

    // Si la variable $generar_listado_existente viene entonces este if genera la planilla existente
    $where_isql = " WHERE $whereDependencia	b.sgd_anu_sol_fecH BETWEEN " .
        $db->conn->DBTimeStamp($fecha_ini) . " and " . $db->conn->DBTimeStamp($fecha_fin) .
        " and SGD_EANU_CODI = 1 $whereTipoRadi and r.radi_nume_radi=b.radi_nume_radi and b.depe_codi = c.depe_codi";
    $order_isql = " ORDER BY  b.depe_codi, b.SGD_ANU_SOL_FECH";
    $query_t = $query . $where_isql . $order_isql;

    $anio = date('Y');
    // Verifica el ultimo numero de acta del tipo de radicado
    $queryk = "Select usua_anu_acta
        from sgd_anu_anulados
        where 
          to_char(SGD_ANU_SOL_FECH,'YYYY') = '$anio' 
          --and depe_codi_anu in (select depe_codi from dependencia where depe_codi_territorial='$depe_codi_territorial') 
          and usua_anu_acta is not null
        order by cast(usua_anu_acta as integer) desc";
    // -- sgd_eanu_codi=2 and sgd_trad_codigo = $tipoRadicado and 
    //$db->conn->debug = true;

    $c = $db->conn->Execute($queryk);

    $rsk = $db->query($queryk);

    $actaNo = (int)$rsk->fields["USUA_ANU_ACTA"];

    //echo "<<<".$actaNo.">>>>";
    $actaNo++;
}

?>
<HTML>

<HEAD>
    <?php include_once "$ruta_raiz/htmlheader.inc.php"; ?>
</HEAD>

<BODY>
    <P>

    <div class="col-sm-12">
        <!-- widget grid -->
        <h2></h2>
        <section id="widget-grid">
            <!-- row -->
            <div class="row">
                <!-- NEW WIDGET START -->
                <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <!-- Widget ID (each widget will need unique ID)-->
                    <div class="jarviswidget jarviswidget-color-darken" id="wid-id-1" data-widget-editbutton="false">

                        <header>
                            <h2>
                                Anular Radicados<br>
                            </h2>
                        </header>
                        <!-- widget div-->
                        <div>
                            <!-- widget content -->
                            <div class="widget-body no-padding">
                                <div class="table-responsive">
                                    <TABLE width="100%" class='table table-bordered' cellspacing="5">
                                        <TR>
                                            <TD height="30" valign="middle" class='titulos5' align="center">Anulacion de Radicados por Dependencia
                                            </td>
                                        </tr>
                                    </table>
                                    <form name="new_product" class="smart-form" action='anularRadicados.php?<?= session_name() . "=" . session_id() .  "&fecha_h=$fechah" ?>' method=post>
                                        <center>

                                            <table class="table table-bordered table-striped">
                                                <!--DWLayoutTable-->
                                                <TR>
                                                    <TD width="125" height="21"> Fecha desde<br>
                                                        <?
                                                        echo "($fecha_busq)";
                                                        ?>
                                                    </TD>
                                                    <TD width="500" align="right" valign="top">
                                                        <label class="input"> <i class="icon-append fa fa-calendar"></i>
                                                            <input type="text" id="fecha_busq" name="fecha_busq" placeholder="Fecha de inicial" value="<?= $fecha_busq ?>">
                                                        </label>
                                                    </TD>
                                                </TR>
                                                <TR>
                                                    <TD width="125" height="21"> Fecha Hasta<br>
                                                        <?
                                                        echo "($fecha_busq2)";
                                                        ?>
                                                    </TD>
                                                    <TD width="500" align="right" valign="top">
                                                        <label class="input"> <i class="icon-append fa fa-calendar"></i>
                                                            <input type="text" id="fecha_busq2" name="fecha_busq2" placeholder="Fecha de inicial" value="<?= $fecha_busq2 ?>">
                                                        </label>
                                                    </TD>
                                                </TR>
                                                <tr>
                                                    <TD height="26">Tipo Radicacion</TD>
                                                    <TD valign="top" align="left">
                                                        <label class="select">
                                                            <?
                                                            $sqlTR = "select upper(sgd_trad_descr),sgd_trad_codigo from sgd_trad_tiporad
                                                                    where sgd_trad_codigo != 2
                                                                    order by sgd_trad_codigo";
                                                            $rsTR = $db->conn->Execute($sqlTR);
                                                            print $rsTR->GetMenu2("tipoRadicado", "$tipoRadicado", false, false, 0, " class='select'>");
                                                            ?>
                                                        </label>
                                                    </TD>
                                                </tr>
                                                <tr>
                                                    <TD height="26">Dependencia</TD>
                                                    <TD valign="top" align="left">
                                                        <label class="select">
                                                            <?
                                                            $sqlD = "select depe_nomb,depe_codi from dependencia
                                                                    where depe_codi_territorial = $depe_codi_territorial
                                                                                order by depe_codi";
                                                            $rsD = $db->conn->Execute($sqlD);

                                                            if (!$depeBuscada) {
                                                                $depeBuscada = $dependencia;
                                                            }
                                                            print $rsD->GetMenu2("depeBuscada", "$depeBuscada", false, false, 0, " class='select'> <option value=9999>--- TODAS LAS DEPENDENCIAS --- </OPTION ");
                                                            //if(!$depeBuscada) $depeBuscada=$dependencia;
                                                            ?>
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td height="26" colspan="2" valign="top">
                                                        <center>
                                                            <INPUT TYPE=submit name=generar_informe Value='Ver Documentos En Solicitud' class='btn btn-sm  btn-success '>
                                                        </center>
                                                    </td>
                                                </tr>
                                            </TABLE>

                                            <HR>
                                            <?php
                                            if (!$fecha_busq) {
                                                $fecha_busq = date("Y-m-d");
                                            }
                                            if ($aceptar && !$actaNo && !$cancelarAnular) {
                                                die("<font color=red><span class=etextomenu>Debe colocal el Numero de acta para poder anular los radicados</span></font>");
                                            }
                                            if (($generar_informe || $aceptarAnular) && !$cancelarAnular) {
                                                require "../anulacion/class_control_anu.php";
                                                $db->conn->SetFetchMode(ADODB_FETCH_NUM);
                                                $btt = new CONTROL_ORFEO($db);
                                                $campos_align = array("C", "L", "L", "L", "L", "L", "L", "L", "L", "L", "L", "L");
                                                $campos_tabla = array("depe_nomb", "radi_nume_radi", "sgd_anu_sol_fech", "sgd_anu_desc");
                                                $campos_vista = array("Dependencia", "Radicado", "Fecha de Solicitud", "Observacion Solicitante");
                                                $campos_width = array(200, 100, 280, 300);
                                                $btt->campos_align = $campos_align;
                                                $btt->campos_tabla = $campos_tabla;
                                                $btt->campos_vista = $campos_vista;
                                                $btt->campos_width = $campos_width;
                                            ?>
                                        </center>

                                        <table width="100%" cellspacing="3" class="table table-bordered table-striped">
                                            <TR>
                                                <TD height="30" valign="middle" class='titulos5' align="center" colspan="2">Documentos con solicitud de
                                                    Anulacion
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="16%" class='titulos5'>Fecha Inicial</td>
                                                <td width="84%" class='listado5'><?= $fecha_busq ?> </td>
                                            </tr>
                                            <tr>
                                                <td class='titulos5'>Fecha Final</td>
                                                <td class='listado5'><?= $fecha_busq2 ?>
                                            </tr>
                                            <tr>
                                                <td class='titulos5'>Fecha Generado</td>
                                                <td class='listado5'><? echo date("Ymd - H:i:s"); ?></td>
                                            </tr>
                                        </table>
                                    <?
                                                $btt->tabla_sql($query_t);
                                                $html = $btt->tabla_html;

                                                $radAnular = $btt->radicadosEnv;
                                                $radObserva = $btt->radicadosObserva;

                                                //Se asigna el No. de la ultima acta + 1

                                            }
                                            if ($generar_informe) {
                                    ?>
                                        <span class="listado2">
                                            <br>Si esta seguro de Anular estos documentos por favor presione aceptar.<br>

                                            <table class="table table-bordered table-striped" align="center">
                                                <tr>
                                                    <td>
                                                        <input type="submit" name="aceptarAnular" value="Aceptar" class="btn btn-sm btn-default">
                                                    </td>
                                                    <td>
                                                        <input type="submit" name="cancelarAnular" value="Cancelar" class="btn btn-sm btn-default">
                                                    </td>
                                                </tr>
                                            </table>
                                        </span>
                                        <?
                                            }

                                            //Se le asigna a actaNo el No. de acta que debe seguir
                                            if ($aceptarAnular && $actaNo) {
                                                include_once "$ruta_raiz/include/db/ConnectionHandler.php";
                                                $db = new ConnectionHandler("$ruta_raiz");
                                                //*Inclusion territorial

                                                if ($depeBuscada == 0) {

                                                    $sqlD = "select depe_nomb,depe_codi from dependencia
                                                            where depe_codi_territorial = $depe_codi_territorial
                                                            order by depe_codi";
                                                    $db->conn->SetFetchMode(ADODB_FETCH_ASSOC);
                                                    $rsD = $db->conn->Execute($sqlD);
                                                    while (!$rsD->EOF) {
                                                        $depcod = $rsD->fields["DEPE_CODI"];
                                                        $lista_depcod .= " $depcod,";
                                                        $rsD->MoveNext();
                                                    }
                                                    $lista_depcod .= "0";
                                                } else {
                                                    $lista_depcod = $depeBuscada;
                                                }
                                                $where_depe = " and (depe_codi) in ($lista_depcod )";
                                                //*fin inclusion
                                                /*
                                                    * Variables que manejan el tipo de Radicacion
                                                    */
                                                $isqlTR = 'select sgd_trad_descr,sgd_trad_codigo from sgd_trad_tiporad
                                                            where sgd_trad_codigo = ' . $tipoRadicado . '
                                                            ';
                                                $rsTR = $db->conn->Execute($isqlTR);
                                                if ($rsTR) {
                                                    $TituloActam = $rsTR->fields["SGD_TRAD_DESCR"];
                                                } else {
                                                    $TituloActam = "sin titulo ";
                                                }

                                                $dbSel = new ConnectionHandler("$ruta_raiz");
                                                $dbSel->conn->SetFetchMode(ADODB_FETCH_ASSOC);
                                                $rsSel = $dbSel->conn->Execute($query_t);
                                                $i = 0;
                                                while (!$rsSel->EOF) {
                                                    $radAnularE[$i] = $rsSel->fields['RADI_NUME_RADI'];
                                                    $radObservaE[$i] = $rsSel->fields['SGD_ANU_DESC'];
                                                    $radFechaE[$i] = $rsSel->fields['RADI_FECH_RADI'];
                                                    $radDepeNombE[$i] = substr($rsSel->fields['DEPE_NOMB'], 0, 45);
                                                    $radDepeCodiE[$i] = $rsSel->fields['DEPE_CODI'];
                                                    $i++;
                                                    $rsSel->MoveNext();
                                                }

                                                if (!$radAnularE) {
                                                    die("<P><spn class=etextomenu><CENTER><FONT COLOR=RED>NO HAY RADICADOS PARA ANULAR</FONT></CENTER><span>");
                                                } else {
                                                    $where_TipoRadicado = " and sgd_trad_codigo = " . $tipoRadicado;
                                                    $Anulacion = new Anulacion($db);
                                                    $observa = "Radicado Anulado. (Acta No $actaNo)";
                                                    $var = md5(date("YMDHis"));
                                                    $noArchivo = "/pdfs/planillas/ActaAnul_$dependencia" . "_" . "$tipoRadicado" . "_" . "$actaNo" . "_" . $var . "_.pdf";
                                                    $radicados = $Anulacion->genAnulacion(
                                                        $radAnularE,
                                                        $dependencia,
                                                        $usua_doc,
                                                        "'" . $observaE . "'",
                                                        $codusuario,
                                                        $actaNo,
                                                        $noArchivo,
                                                        $where_depe,
                                                        $where_TipoRadicado,
                                                        $tipoRadicado,
                                                        $rsk->fields["0"]
                                                    );

                                                    $Historico = new Historico($db);
                                                    $radicados = $Historico->insertarHistorico($radAnularE, $dependencia, $codusuario, 'NULL', 0, $observa, 26);

                                                    define('FPDF_FONTPATH', '../fpdf/font/');
                                                    $radAnulados = join(",", $radAnularE);

                                                    foreach ($radAnularE as $id => $noRadicado) {
                                                        $norad = $radAnularE[$id];
                                                        $txrad = $radObservaE[$id];
                                                        $depeNombAnu = substr($radDepeNombE[$id], 0, 40);
                                                        $depeCodiAnu = $radDepeCodiE[$id];
                                                        $radicadosPdf .= "<br><br><tr><td width='350'><b>Radicado No. $norad ($depeCodiAnu - $depeNombAnu)</b></td></tr><span bgcolor='#D0D0'>$txrad</span>";
                                                    }

                                                    $anoActual = date("Y");

                                                    $ruta_raiz = "..";
                                                    include "$ruta_raiz/fpdf/fpdf.php";
                                                    require "$ruta_raiz/fpdf/html_table.php";

                                                    $fecha = date("d-m-Y");
                                                    $fecha_hoy_corto = date("d-m-Y");
                                                    include "$ruta_raiz/class_control/class_gen.php";
                                                    include_once "$ruta_raiz/include/class/JefeArea.class.php";
                                                    $date = date("m/d/Y");
                                                    $b = new CLASS_GEN();
                                                    $fecha_hoy = $b->traducefecha($date);

                                                    // Firma fija del jefe de la dependencia 11001.
                                                    $jefeFirmaNombre = "PENDIENTE CONFIGURAR JEFE DEP 11001";
                                                    $jefeFirmaCargo = "Jefe División de Gestión Documental";
                                                    $jefeFirmaEntidad = "Universidad Militar Nueva Granada";
                                                    $jefeFirmaLogin = "";
                                                    $firmaMecanicaActaPath = "";
                                                    $firmaMecanicaTmp = "";
                                                    $jefeInfo = JefeArea::getInfoCompletaJefe($db, 11001);
                                                    if (is_array($jefeInfo) && !empty($jefeInfo['usua_nomb'])) {
                                                        $jefeFirmaNombre = trim($jefeInfo['usua_nomb']);
                                                    }
                                                    $sqlJefeLogin = "SELECT u.usua_login
                                                        FROM usuario u
                                                        INNER JOIN autm_membresias a ON u.id = a.autu_id
                                                        WHERE a.autg_id = 2
                                                          AND u.depe_codi = 11001
                                                        ORDER BY u.id DESC
                                                        LIMIT 1";
                                                    $rsJefeLogin = $db->conn->Execute($sqlJefeLogin);
                                                    if ($rsJefeLogin && !$rsJefeLogin->EOF) {
                                                        $jefeFirmaLogin = strtolower(trim((string)$rsJefeLogin->fields['USUA_LOGIN']));
                                                    }
                                                    if ($jefeFirmaLogin !== '') {
                                                        $firmaMecanicaActaPath = $ruta_raiz . "/bodega/firmas/grafo/" . $jefeFirmaLogin . ".png";
                                                    }

                                                    // Obtiene el cuerpo del acta desde la tabla parametrizada tomando la mayor fecha de vigencia no futura
                                                    $contenidoActaDefault = <<<'EOC'
                                                                        En cumplimiento a lo establecido en el Acuerdo No. 060 del 30 de octubre de 2001, expedido por el<br>
                                                                        Archivo General de la Nación, en el cual se establecen pautas para la administración de las<br>
                                                                        comunicaciones oficiales en las entidades públicas y privadas que cumplen funciones públicas,<br>
                                                                        y con base especialmente en el parágrafo del Artículo Quinto, el cual establece que:<br><br>
                                                                        "Cuando existan errores en la radicación y se anulen los números, se debe dejar constancia por<br>
                                                                        escrito, con la respectiva justificación y firma del Jefe de la Unidad de Correspondencia." <br><br>
                                                                        EOC;

                                                    $contenidoActa = $contenidoActaDefault;
                                                    $sqlContenidoActa = "SELECT contenido FROM sgd_acta_anu_contenido WHERE estado = 1 AND fecha_vigencia <= CURRENT_DATE ORDER BY fecha_vigencia DESC LIMIT 1";
                                                    $rsContenidoActa = $db->conn->Execute($sqlContenidoActa);
                                                    if ($rsContenidoActa && !$rsContenidoActa->EOF) {
                                                        $contenidoDb = $rsContenidoActa->fields['CONTENIDO'];
                                                        if (is_string($contenidoDb) && trim($contenidoDb) !== '') {
                                                            $contenidoActa = $contenidoDb;
                                                        }
                                                    }


                                                    //$preName=(file_exists("../img/$entidad.banerPDF.png"))?$entidad.".":"";
                                                    //  $html = '                                                        <img src="../img/'.$preName.'banerPDF.png" width="180" height="100" >' . $html;
                                                    $ruta_raiz = "..";

                                                    $pdf = new PDF();
                                                    $pdf->SetTitle("$entidad - Acta de Anulacion de Radicados No $actaNo");
                                                    $pdf->SetSubject("Anulacion radicados");
                                                    $pdf->SetFont('Arial', '', 11);
                                                    $pdf->SetMargins(15, 5, 15, 5);
                                                    $pdf->AddPage();
                                                    if (ini_get('magic_quotes_gpc') == '1') {
                                                        $html = stripslashes($html);
                                                    }
                                                    //df->WriteHTML(iconv('UTF-8', 'ISO-8859-1', $html));
                                                    // Inserta logo desde base64 para evitar dependencias de rutas locales.
                                                    $logoTmp = null;
                                                    $logoBase64Path = __DIR__ . "/umng_escudo_base64.php";
                                                    if (is_readable($logoBase64Path)) {
                                                        $logoBase64 = include $logoBase64Path;
                                                        if (is_string($logoBase64) && trim($logoBase64) !== '') {
                                                            $logoBin = base64_decode($logoBase64, true);
                                                            if ($logoBin !== false) {
                                                                $logoTmp = tempnam(sys_get_temp_dir(), 'umng_logo_');
                                                                if ($logoTmp) {
                                                                    file_put_contents($logoTmp, $logoBin);
                                                                    // Se inserta más abajo junto con el bloque del encabezado.
                                                                }
                                                            }
                                                        }
                                                    }
                                                    // Plantilla de encabezado: logo centrado arriba + tabla centrada.
                                                    $pageW = isset($pdf->w) ? $pdf->w : 210;
                                                    if (isset($logoTmp) && $logoTmp && file_exists($logoTmp)) {
                                                        $logoW = 34;
                                                        $logoX = ($pageW - $logoW) / 2;
                                                        $pdf->Image($logoTmp, $logoX, 8, $logoW, 0, 'JPG');
                                                    }

                                                    $wLeft = 95;
                                                    $wMid = 40;
                                                    $wRight = 35;
                                                    $rowH = 10;
                                                    $headerW = $wLeft + $wMid + $wRight;
                                                    $x0 = ($pageW - $headerW) / 2;
                                                    $y0 = 56;

                                                    // Celda izquierda combinada (2 filas)
                                                    $pdf->Rect($x0, $y0, $wLeft, $rowH * 2);
                                                    $pdf->SetFont('Arial', 'B', 9);
                                                    $pdf->SetXY($x0 + 2, $y0 + 6);
                                                    $pdf->Cell($wLeft - 4, 8, utf8_decode("ACTA DE ANULACIÓN DE RADICADOS"), 0, 0, 'C');

                                                    // Bloque derecho fila superior
                                                    $pdf->SetFont('Arial', 'B', 10);
                                                    $pdf->SetXY($x0 + $wLeft, $y0);
                                                    $pdf->MultiCell($wMid, 5, utf8_decode("Fecha Emisión:\n" . date("Y/m/d")), 1, 'C');
                                                    $pdf->SetXY($x0 + $wLeft + $wMid, $y0);
                                                    $pdf->Cell($wRight, $rowH, "GD-GD-F-27", 1, 0, 'C');

                                                    // Bloque derecho fila inferior
                                                    $pdf->SetXY($x0 + $wLeft, $y0 + $rowH);
                                                    $pdf->MultiCell($wMid, 5, utf8_decode("Revisión No.:\n4"), 1, 'C');
                                                    $pdf->SetXY($x0 + $wLeft + $wMid, $y0 + $rowH);
                                                    $pdf->Cell($wRight, $rowH, utf8_decode("Página 1 de 1"), 1, 0, 'C');

                                                    $pdf->SetY($y0 + ($rowH * 2) + 8);

                                                    $pdf->SetFont('Arial', 'B', 12);
                                                    $pdf->Cell(0, 8, utf8_decode("ACTA DE ANULACIÓN No. $actaNo"), 0, 1, 'C');
                                                    $pdf->SetFont('Arial', 'B', 11);
                                                    $pdf->Cell(0, 8, utf8_decode("FECHA: " . $fecha_hoy), 0, 1, 'L');
                                                    $pdf->Ln(2);

                                                    $pdf->SetFont('Arial', '', 11);
                                                    $pdf->MultiCell(0, 6, utf8_decode($contenidoActa), 0, 'J');
                                                    $pdf->Ln(5);
                                                    $pdf->SetFont('Arial', 'B', 11);
                                                    $pdf->Cell(0, 6, utf8_decode("EN CONSECUENCIA"), 0, 1, 'C');
                                                    $pdf->Ln(2);
                                                    $pdf->SetFont('Arial', '', 11);
                                                    $pdf->MultiCell(0, 6, utf8_decode("El responsable de la División de Gestión Documental de la Universidad Militar Nueva Granada, procede a anular los siguientes números de radicados, que no fueron tramitados por las Unidades Académico Administrativas radicadoras:"), 0, 'J');
                                                    $pdf->Ln(5);
                                                    $pdf->MultiCell(0, 6, utf8_decode("1. Radicados a anular:"), 0, 'J');
                                                    $pdf->Ln(3);

                                                    // Helper: sanitize and convert text for FPDF (ISO-8859-1)
                                                    function _safe_for_pdf($text)
                                                    {
                                                        if ($text === null) {
                                                            return '';
                                                        }
                                                        // Trim and remove NULL/control chars that can break the PDF library
                                                        $text = trim($text);
                                                        $text = preg_replace('/[\x00-\x1F\x7F]/u', '', $text);

                                                        // Try to convert UTF-8 -> ISO-8859-1 with transliteration
                                                        $converted = @iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $text);
                                                        if ($converted === false) {
                                                            // Fallback: ignore characters that can't be represented
                                                            $converted = @iconv('UTF-8', 'ISO-8859-1//IGNORE', $text);
                                                        }
                                                        if ($converted === false) {
                                                            // Last resort: use utf8_decode which maps common characters
                                                            $converted = @utf8_decode($text);
                                                        }
                                                        // Ensure we always return a string
                                                        return $converted === false ? '' : $converted;
                                                    }

                                                    // Envuelve texto para calcular alto dinámico en filas de tabla.
                                                    function _wrap_for_table($text, $maxChars = 78)
                                                    {
                                                        $text = trim((string)$text);
                                                        if ($text === '') {
                                                            return '';
                                                        }
                                                        return wordwrap($text, $maxChars, "\n", true);
                                                    }

                                                    // Recorta el texto envuelto a un número máximo de líneas para conservar altura uniforme.
                                                    function _clamp_wrapped_lines($wrappedText, $maxLines = 2)
                                                    {
                                                        $wrappedText = (string)$wrappedText;
                                                        if ($wrappedText === '') {
                                                            return '';
                                                        }
                                                        $lines = explode("\n", $wrappedText);
                                                        if (count($lines) <= $maxLines) {
                                                            return $wrappedText;
                                                        }
                                                        $lines = array_slice($lines, 0, $maxLines);
                                                        $lastIdx = $maxLines - 1;
                                                        $lines[$lastIdx] = rtrim($lines[$lastIdx], " .") . "...";
                                                        return implode("\n", $lines);
                                                    }

                                                    // Ajusta texto a un ancho real de celda usando métricas de FPDF y limita líneas.
                                                    function _fit_text_for_multicell($pdf, $text, $cellWidth, $maxLines = 2)
                                                    {
                                                        $text = trim((string)$text);
                                                        if ($text === '') {
                                                            return '';
                                                        }
                                                        $words = preg_split('/\s+/', $text);
                                                        $lines = array();
                                                        $current = '';
                                                        foreach ($words as $word) {
                                                            $probe = ($current === '') ? $word : ($current . ' ' . $word);
                                                            if ($pdf->GetStringWidth($probe) <= ($cellWidth - 2)) {
                                                                $current = $probe;
                                                                continue;
                                                            }
                                                            if ($current === '') {
                                                                $lines[] = $word;
                                                            } else {
                                                                $lines[] = $current;
                                                                $current = $word;
                                                            }
                                                            if (count($lines) >= $maxLines) {
                                                                break;
                                                            }
                                                        }
                                                        if (count($lines) < $maxLines && $current !== '') {
                                                            $lines[] = $current;
                                                        }
                                                        $lines = array_slice($lines, 0, $maxLines);
                                                        $joined = implode("\n", $lines);
                                                        // Si hubo truncamiento, agrega puntos suspensivos a la última línea.
                                                        if (count($words) > 0 && $joined !== $text) {
                                                            $last = count($lines) - 1;
                                                            if ($last >= 0) {
                                                                $base = rtrim($lines[$last], " .");
                                                                while ($base !== '' && $pdf->GetStringWidth($base . '...') > ($cellWidth - 2)) {
                                                                    $base = rtrim(substr($base, 0, -1));
                                                                }
                                                                $lines[$last] = ($base === '') ? '...' : ($base . '...');
                                                            }
                                                        }
                                                        return implode("\n", $lines);
                                                    }

                                                    // Convierte PNG de firma mecánica a JPG para evitar incompatibilidades de alpha en FPDF 1.52.
                                                    function _firma_png_to_jpg($pngPath)
                                                    {
                                                        if (!is_readable($pngPath)) {
                                                            return '';
                                                        }
                                                        if (!function_exists('imagecreatefrompng') || !function_exists('imagejpeg')) {
                                                            return '';
                                                        }
                                                        $im = @imagecreatefrompng($pngPath);
                                                        if ($im === false) {
                                                            return '';
                                                        }
                                                        $w = imagesx($im);
                                                        $h = imagesy($im);
                                                        $bg = imagecreatetruecolor($w, $h);
                                                        $white = imagecolorallocate($bg, 255, 255, 255);
                                                        imagefill($bg, 0, 0, $white);
                                                        imagecopy($bg, $im, 0, 0, 0, 0, $w, $h);
                                                        $tmpBase = tempnam(sys_get_temp_dir(), 'firma_jpg_');
                                                        if ($tmpBase === false) {
                                                            imagedestroy($im);
                                                            imagedestroy($bg);
                                                            return '';
                                                        }
                                                        $tmpJpg = $tmpBase . ".jpg";
                                                        @rename($tmpBase, $tmpJpg);
                                                        $ok = @imagejpeg($bg, $tmpJpg, 92);
                                                        imagedestroy($im);
                                                        imagedestroy($bg);
                                                        return $ok ? $tmpJpg : '';
                                                    }

                                                    // Tabla de radicados anulados
                                                    $pdf->SetFont('Arial', 'B', 10);
                                                    $pdf->Cell(50, 8, utf8_decode("No. Radicado"), 1, 0, 'C');
                                                    $pdf->Cell(35, 8, "Fecha", 1, 0, 'C');
                                                    $pdf->Cell(105, 8, utf8_decode("Motivo Anulación"), 1, 1, 'C');

                                                    $pdf->SetFont('Arial', '', 10);
                                                    // Altura uniforme y texto recortado para mantener tabla alineada visualmente.
                                                    $altoLinea = 5;
                                                    $maxLineasVisuales = 4;
                                                    $altoFilaFija = $maxLineasVisuales * $altoLinea;
                                                    $anchoRad = 50;
                                                    $anchoFecha = 35;
                                                    $anchoMotivo = 105;

                                                    foreach ($radAnularE as $id => $noRadicado) {
                                                        $norad = $radAnularE[$id];
                                                        $txrad = $radObservaE[$id];
                                                        $fechaRad = '';
                                                        if (!empty($radFechaE[$id])) {
                                                            $fechaRad = substr($radFechaE[$id], 0, 10);
                                                        }

                                                        $motivoWrap = _fit_text_for_multicell($pdf, _safe_for_pdf($txrad), $anchoMotivo, $maxLineasVisuales);
                                                        $altoFila = $altoFilaFija;

                                                        // Si no cabe la fila completa, abrir nueva página y repetir encabezado de tabla.
                                                        $espacioDisponible = ($pdf->h - $pdf->bMargin) - $pdf->GetY();
                                                        if ($altoFila > $espacioDisponible) {
                                                            $pdf->AddPage();
                                                            $pdf->SetFont('Arial', 'B', 10);
                                                            $pdf->Cell($anchoRad, 8, utf8_decode("No. Radicado"), 1, 0, 'C');
                                                            $pdf->Cell($anchoFecha, 8, "Fecha", 1, 0, 'C');
                                                            $pdf->Cell($anchoMotivo, 8, utf8_decode("Motivo Anulación"), 1, 1, 'C');
                                                            $pdf->SetFont('Arial', '', 10);
                                                        }

                                                        $x = $pdf->GetX();
                                                        $y = $pdf->GetY();
                                                        $pdf->Cell($anchoRad, $altoFila, _safe_for_pdf($norad), 1, 0, 'L');
                                                        $pdf->Cell($anchoFecha, $altoFila, _safe_for_pdf($fechaRad), 1, 0, 'C');
                                                        // Borde completo para mantener la grilla alineada.
                                                        $xComentario = $x + $anchoRad + $anchoFecha;
                                                        $pdf->Rect($xComentario, $y, $anchoMotivo, $altoFila);
                                                        $pdf->MultiCell($anchoMotivo, $altoLinea, $motivoWrap, 0, 'J');
                                                        $pdf->SetXY($x, $y + $altoFila);
                                                    }

                                                    // El bloque de cierre (punto 2 + firma) va en página adicional para evitar cortes.
                                                    $pdf->AddPage();
                                                    $pdf->Ln(6);
                                                    $pdf->MultiCell(0, 6, utf8_decode("2. La presente acta reposa en el Sistema de Gestión de Documento Electrónico de Archivo - SGDEA, como constancia y en cumplimiento de las directrices de la Universidad en materia archivística."), 0, 'J');
                                                    $pdf->Ln(8);

                                                    // Firma mecánica del jefe (misma lógica de extracción del flujo de combinar).
                                                    if ($firmaMecanicaActaPath && file_exists($firmaMecanicaActaPath)) {
                                                        $firmaMecanicaTmp = _firma_png_to_jpg($firmaMecanicaActaPath);
                                                        if ($firmaMecanicaTmp && file_exists($firmaMecanicaTmp)) {
                                                            // Firma con ancho fijo y alto automático (proporcional).
                                                            $firmaW = 62;
                                                            $firmaH = 0;
                                                            $firmaX = (isset($pdf->w) ? ($pdf->w - $firmaW) / 2 : 75);
                                                            $firmaY = $pdf->GetY();
                                                            $pdf->Image($firmaMecanicaTmp, $firmaX, $firmaY, $firmaW, $firmaH, 'JPG');

                                                            // Salto dinámico según alto real renderizado.
                                                            $firmaInfo = @getimagesize($firmaMecanicaTmp);
                                                            $altoRender = 20;
                                                            if ($firmaInfo && !empty($firmaInfo[0]) && !empty($firmaInfo[1])) {
                                                                $altoRender = ($firmaW * $firmaInfo[1]) / $firmaInfo[0];
                                                            }
                                                            $pdf->SetY($firmaY + $altoRender + 2);
                                                        }
                                                    }

                                                    $pdf->Ln(4);
                                                    $pdf->SetFont('Arial', 'B', 11);
                                                    $pdf->Cell(0, 6, utf8_decode("Firmado electrónicamente por:"), 0, 1, 'C');
                                                    $pdf->Cell(0, 6, _safe_for_pdf(strtoupper($jefeFirmaNombre)), 0, 1, 'C');
                                                    $pdf->SetFont('Arial', '', 11);
                                                    $pdf->Cell(0, 6, utf8_decode($jefeFirmaCargo), 0, 1, 'C');
                                                    $pdf->Cell(0, 6, utf8_decode($jefeFirmaEntidad), 0, 1, 'C');

                                                    $noArchivo = "../bodega" . $noArchivo;
                                                    $directorioSalida = dirname($noArchivo);
                                                    if (!is_dir($directorioSalida)) {
                                                        @mkdir($directorioSalida, 0775, true);
                                                    }
                                                    $pdf->Output($noArchivo);
                                                    if ($logoTmp && file_exists($logoTmp)) {
                                                        @unlink($logoTmp);
                                                    }
                                                    if ($firmaMecanicaTmp && file_exists($firmaMecanicaTmp)) {
                                                        @unlink($firmaMecanicaTmp);
                                                    }
                                        ?>
                                            Ver Acta <a class="titulo2" href='<?= $noArchivo ?>'>Acta No <?= $actaNo ?> </a><?
                                                                                                                            exit;
                                                                                                                        }
                                                                                                                    }
                                                                                                                            ?>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </article>
            </div>
        </section>
    </div>

</BODY>
<script>
    //Datepicker muestra fecha
    $('#fecha_busq').datepicker({
        dateFormat: 'yy-mm-dd',
        onSelect: function(selectedDate) {
            $('#date').datepicker('option', 'maxDate', selectedDate);
        }
    });

    //Datepicker muestra fecha
    $('#fecha_busq2').datepicker({
        dateFormat: 'yy-mm-dd',
        onSelect: function(selectedDate) {
            $('#date').datepicker('option', 'maxDate', selectedDate);
        }
    });
</script>

</HTML>
