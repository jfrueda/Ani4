<?php
if (!$ruta_raiz) {
    $ruta_raiz = "..";
}

session_start();

if (!$_SESSION['dependencia'] || !$_SESSION["usua_admin_sistema"] >= 1) {
    header("Location: $ruta_raiz/cerrar_session.php");
    exit;
}

function path_join($base, $rel)
{
    return rtrim($base, '/') . '/' . ltrim($rel, '/');
}

function normalize_rel_path($value)
{
    $value = trim((string) $value);
    if ($value === '') {
        return '';
    }

    if (strpos($value, '/docs/') !== false) {
        return '/' . ltrim($value, '/');
    }

    return $value;
}

function file_exists_rel($contentPath, $relPath)
{
    if ($relPath === '') {
        return false;
    }

    $full = path_join($contentPath, $relPath);
    return file_exists($full) ? $full : false;
}

if (isset($_POST['radicado'])) {
    header('Content-Type: application/json');

    $radicado = trim((string) $_POST['radicado']);
    if ($radicado === '' || !ctype_digit($radicado)) {
        echo json_encode(['error' => 'Número de radicado inválido']);
        exit;
    }

    require_once $ruta_raiz . "/include/db/ConnectionHandler.php";
    require_once $ruta_raiz . "/processConfig.php";
    require_once "$ruta_raiz/include/tx/Historico.php";

    $db = new ConnectionHandler($ruta_raiz);
    $hist = new Historico($db);

    $sqlRad = "SELECT radi_nume_radi, radi_path, radi_depe_actu FROM radicado WHERE radi_nume_radi = $radicado";
    $rsRad = $db->conn->Execute($sqlRad);

    if (!$rsRad || $rsRad->EOF) {
        echo json_encode(['error' => 'No existe el radicado']);
        exit;
    }

    $radiPath = trim((string) $rsRad->fields['RADI_PATH']);
    $depeActu = trim((string) $rsRad->fields['RADI_DEPE_ACTU']);
    $contentBase = isset($CONTENT_PATH) ? rtrim($CONTENT_PATH, '/') : (rtrim($ABSOL_PATH, '/') . '/bodega');

    $sourceRel = '';
    $sourceDocx = '';

    if ($radiPath !== '' && preg_match('/\.docx$/i', $radiPath)) {
        $candidateRel = normalize_rel_path($radiPath);
        $candidateFull = file_exists_rel($contentBase, $candidateRel);
        if ($candidateFull !== false) {
            $sourceRel = $candidateRel;
            $sourceDocx = $candidateFull;
        }
    }

    if ($sourceDocx === '') {
        $sqlAnexo = "SELECT id, anex_nomb_archivo
                     FROM anexos
                     WHERE anex_radi_nume = $radicado
                       AND anex_nomb_archivo IS NOT NULL
                       AND anex_borrado = 'N'
                     ORDER BY id DESC";
        $rsAnexo = $db->conn->Execute($sqlAnexo);

        while ($rsAnexo && !$rsAnexo->EOF) {
            $rawName = trim((string) $rsAnexo->fields['ANEX_NOMB_ARCHIVO']);
            if ($rawName === '' || !preg_match('/\.docx$/i', $rawName)) {
                $rsAnexo->MoveNext();
                continue;
            }

            $normalized = normalize_rel_path($rawName);
            if ($normalized !== '' && strpos($normalized, '/docs/') !== false) {
                $candidateFull = file_exists_rel($contentBase, $normalized);
                if ($candidateFull !== false) {
                    $sourceRel = $normalized;
                    $sourceDocx = $candidateFull;
                    break;
                }
            }

            $baseDirRel = '';
            if ($radiPath !== '' && strpos($radiPath, '/docs/') !== false) {
                $baseDirRel = dirname('/' . ltrim($radiPath, '/'));
            } else {
                $anio = substr($radicado, 0, 4);
                $digDep = isset($_SESSION['digitosDependencia']) ? intval($_SESSION['digitosDependencia']) : 3;
                $depePath = substr($radicado, 4, $digDep);
                if ($depePath === '' && $depeActu !== '') {
                    $depePath = $depeActu;
                }
                $baseDirRel = '/' . $anio . '/' . $depePath . '/docs';
            }

            $candidateRel = rtrim($baseDirRel, '/') . '/' . basename($rawName);
            $candidateFull = file_exists_rel($contentBase, $candidateRel);
            if ($candidateFull !== false) {
                $sourceRel = $candidateRel;
                $sourceDocx = $candidateFull;
                break;
            }

            $rsAnexo->MoveNext();
        }
    }

    if ($sourceDocx === '') {
        echo json_encode(['error' => 'No se encontró DOCX combinado para ese radicado']);
        exit;
    }

    $tmpSf = '/tmp/' . str_replace('.', '', microtime(true));
    $cmdToPdf = "soffice --headless -env:UserInstallation=file://$tmpSf --convert-to pdf "
        . escapeshellarg($sourceDocx)
        . " --outdir "
        . escapeshellarg(dirname($sourceDocx))
        . " 2>&1";

    $outToPdf = [];
    $retToPdf = 0;
    exec($cmdToPdf, $outToPdf, $retToPdf);
    exec('rm -rf ' . escapeshellarg($tmpSf));

    $convertedPdf = preg_replace('/\.docx$/i', '.pdf', $sourceDocx);
    if ($retToPdf !== 0 || !file_exists($convertedPdf)) {
        $outStr = implode("\n", $outToPdf);
        error_log(date(DATE_ATOM) . " " . basename(__FILE__) . " (soffice $retToPdf) $radicado: $outStr\n", 3, "$ABSOL_PATH/bodega/jsignpdf.log");
        echo json_encode(['error' => 'Falló la conversión a PDF']);
        exit;
    }

    $firmasDir = $ABSOL_PATH . '/bodega/firmas/';
    $P12_FILE = $firmasDir . 'server.p12';

    if (!file_exists($P12_FILE)) {
        $P12_FILE = $firmasDir . $_SESSION['usua_doc'] . '.p12';
    }

    if (!file_exists($P12_FILE)) {
        echo json_encode(['error' => 'No se encontró certificado de firma (.p12)']);
        exit;
    }

    $clave = isset($_POST['clave']) && trim((string) $_POST['clave']) !== ''
        ? trim((string) $_POST['clave'])
        : (isset($P12_PASS) ? $P12_PASS : '');

    if ($clave === '') {
        echo json_encode(['error' => 'No hay clave de firma disponible']);
        exit;
    }

    $jar = $ABSOL_PATH . '/include/jsignpdf/JSignPdf.jar';
    $cmdFirmado = 'java -jar ' . escapeshellarg($jar)
        . ' ' . escapeshellarg($convertedPdf)
        . ' -kst PKCS12'
        . ' -ksf ' . escapeshellarg($P12_FILE)
        . ' -ksp ' . escapeshellarg($clave)
        . ' --font-size 7'
        . ' -r ' . escapeshellarg('Firmado al Radicar en CADET')
        . ' -V -llx 0 -lly 0 -urx 550 -ury 27'
        . ' -d ' . escapeshellarg(dirname($convertedPdf));

    if (!empty($tsUrlTimeStamp)) {
        $cmdFirmadoTS = $cmdFirmado
            . ' -ta PASSWORD'
            . ' -ts ' . escapeshellarg($tsUrlTimeStamp)
            . ' -tsu ' . escapeshellarg($tsuUserTimeStamp)
            . ' -tsp ' . escapeshellarg($tspPasswordTimeStamp)
            . ' 2>&1';
    }

    $cmdFirmado .= ' 2>&1';

    $outFirmado = [];
    $retFirmado = 0;
    $cmdEjecutar = isset($cmdFirmadoTS) ? $cmdFirmadoTS : $cmdFirmado;
    exec($cmdEjecutar, $outFirmado, $retFirmado);

    if ($retFirmado !== 0 && isset($cmdFirmadoTS)) {
        $outFirmado = [];
        $retFirmado = 0;
        exec($cmdFirmado, $outFirmado, $retFirmado);
    }

    if ($retFirmado !== 0) {
        $outStr = implode("\n", $outFirmado);
        error_log(date(DATE_ATOM) . " " . basename(__FILE__) . " ($retFirmado) $radicado: $outStr\n", 3, "$ABSOL_PATH/bodega/jsignpdf.log");
        echo json_encode(['error' => 'Falló el proceso de firma']);
        exit;
    }

    $signedTmp = preg_replace('/\.pdf$/i', '_signed.pdf', $convertedPdf);
    if (!file_exists($signedTmp)) {
        echo json_encode(['error' => 'No se generó el archivo firmado']);
        exit;
    }

    $destRel = '';
    if ($radiPath !== '') {
        $destRel = '/' . ltrim(preg_replace('/\.[^.]+$/', '.pdf', $radiPath), '/');
    } else {
        $destRel = preg_replace('/\.docx$/i', '.pdf', $sourceRel);
        if ($destRel === '' || $destRel === $sourceRel) {
            $destRel = '/' . substr($radicado, 0, 4) . '/' . substr($radicado, 4, 3) . '/docs/' . $radicado . '.pdf';
        }
    }

    $destFull = path_join($contentBase, $destRel);
    $destDir = dirname($destFull);
    if (!is_dir($destDir) && !mkdir($destDir, 0775, true)) {
        echo json_encode(['error' => 'No se pudo crear directorio de destino']);
        exit;
    }

    if (!rename($signedTmp, $destFull)) {
        echo json_encode(['error' => 'No se pudo mover el PDF firmado a la ruta productiva']);
        exit;
    }

    if (file_exists($convertedPdf)) {
        @unlink($convertedPdf);
    }

    $destRelDb = '/' . ltrim($destRel, '/');

    $db->conn->StartTrans();
    $updateRad = "UPDATE radicado
                  SET radi_path = '" . $destRelDb . "',
                      radi_firma = '1'
                  WHERE radi_nume_radi = $radicado";
    $okUpdate = $db->conn->Execute($updateRad);

    if ($okUpdate) {
        $hist->insertarHistorico(
            [$radicado],
            $_SESSION['dependencia'],
            $_SESSION['codusuario'],
            $_SESSION['dependencia'],
            $_SESSION['codusuario'],
            'Firmado digital desde Administracion y publicado en ruta productiva',
            40
        );
    }

    $db->conn->CompleteTrans();

    if (!$okUpdate) {
        echo json_encode(['error' => 'No se pudo actualizar RADI_PATH en base de datos']);
        exit;
    }

    echo json_encode([
        'ok' => true,
        'radicado' => $radicado,
        'ruta' => $destRelDb
    ]);
    exit;
}
?>
<html>

<head>
    <?php include_once "$ruta_raiz/htmlheader.inc.php"; ?>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
</head>

<body>
    <div class="container-fluid mb-4">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card shadow border-0 overflow-hidden">
                    <div class="card-header bg-orfeo bg-gradient text-white py-3">
                        <h5 class="mb-0 fw-bold d-flex align-items-center">
                            <i class="fa fa-pencil-square-o me-2"></i> Firma de radicado
                        </h5>
                    </div>

                    <div class="card-body bg-light p-0">
                        <form id="fmFirma">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="p-3 bg-white rounded-3 shadow-sm border border-light-subtle h-100">
                                        <label for="radicado" class="form-label fw-semibold text-secondary small mb-2">Número de radicado*</label>
                                        <input type="text" maxlength="20" class="form-control shadow-none" id="radicado" name="radicado" placeholder="Ej: 2026..." required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 bg-white rounded-3 shadow-sm border border-light-subtle h-100">
                                        <label for="clave" class="form-label fw-semibold text-secondary small mb-2">Clave de firma (opcional)</label>
                                        <input type="password" maxlength="255" class="form-control shadow-none" id="clave" name="clave" placeholder="Si la deja vacía usa la configurada en sistema">
                                    </div>
                                </div>
                            </div>

                            <div class="my-4 border-top border-light-subtle"></div>

                            <div class="d-flex justify-content-center gap-3">
                                <button type="button" class="btn btn-outline-secondary px-4 fw-medium border-2" id="btBorrarForm">
                                    <i class="fa fa-eraser me-1"></i> Borrar formulario
                                </button>
                                <button type="button" class="btn btn-primary px-5 fw-bold shadow" id="btFirmar">
                                    <i class="fa fa-certificate me-1"></i> Firmar y publicar
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer bg-white py-2">
                        <small class="text-muted italic px-2">
                            <i class="fa fa-info-circle me-1 text-primary"></i> El sistema toma el DOCX más reciente del radicado y publica el PDF firmado en la ruta productiva.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="alert d-none" role="alert" id="dvForm"></div>

    <script type="text/javascript">
        function alerta(mensaje, tipo = 'info') {
            const $alerta = $('#dvForm');
            $alerta.attr('class', `alert alert-${tipo}`);
            $alerta.text(mensaje);
            if ($alerta.is(':visible')) {
                $alerta.fadeOut(200, function() {
                    $(this).fadeIn(200);
                });
            } else {
                $alerta.show();
            }
        }

        $('#btFirmar').click(function() {
            const radicado = $('#radicado').val().trim();
            if (!/^\d+$/.test(radicado)) {
                alerta('Digite un número de radicado válido', 'warning');
                return;
            }

            $.ajax({
                type: 'POST',
                dataType: 'json',
                data: $('#fmFirma').serialize(),
                success: function(result) {
                    if (result.error) {
                        alerta('Error: ' + result.error, 'danger');
                    } else {
                        alerta('Radicado ' + result.radicado + ' firmado y publicado en ' + result.ruta, 'success');
                    }
                },
                error: function() {
                    alerta('Ocurrió un error en el proceso de firmado', 'danger');
                }
            });
        });

        $('#btBorrarForm').click(function() {
            $('#fmFirma')[0].reset();
            $('#dvForm').hide();
        });
    </script>
</body>

</html>
