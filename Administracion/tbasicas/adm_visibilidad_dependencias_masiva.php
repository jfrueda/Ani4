<?php
session_start();
$ruta_raiz = "../..";

if (!$_SESSION['dependencia']) {
    header("Location: $ruta_raiz/cerrar_session.php");
    exit;
}

foreach ($_GET as $key => $valor) {
    ${$key} = $valor;
}
foreach ($_POST as $key => $valor) {
    ${$key} = $valor;
}

$krd = $_SESSION["krd"];
$dependencia = (int)$_SESSION["dependencia"];
$isAdmin = (int)$_SESSION["usua_admin_sistema"] >= 1;

include_once "$ruta_raiz/include/db/ConnectionHandler.php";
$db = new ConnectionHandler("$ruta_raiz");
$db->conn->SetFetchMode(ADODB_FETCH_ASSOC);

$mensaje = '';
$tipoMensaje = 'info';
$ignoradasInput = isset($_POST['dependencias_ignorar']) ? trim($_POST['dependencias_ignorar']) : '';
$visiblesInput = isset($_POST['dependencias_visibles']) ? trim($_POST['dependencias_visibles']) : '';
$previewTotal = 0;
$previewActivas = 0;
$previewActivasObjetivo = 0;
$previewIgnoradas = array();
$previewVisibles = array();

function parseDependenciasIgnorar($input)
{
    if ($input === '') {
        return array();
    }

    $tokens = preg_split('/[\s,;]+/', $input);
    $codigos = array();

    foreach ($tokens as $token) {
        if ($token === '') {
            continue;
        }
        if (preg_match('/^\d+$/', $token)) {
            $codigos[(int)$token] = (int)$token;
        }
    }

    return array_values($codigos);
}

if (!$isAdmin) {
    $mensaje = 'No tiene permisos para ejecutar esta administración.';
    $tipoMensaje = 'danger';
} else {
    $codigosIgnorar = parseDependenciasIgnorar($ignoradasInput);
    $codigosVisibles = parseDependenciasIgnorar($visiblesInput);
    $sqlActivas = "SELECT DEPE_CODI FROM DEPENDENCIA WHERE DEPE_ESTADO = 1 ORDER BY DEPE_CODI";
    $rsActivas = $db->conn->Execute($sqlActivas);
    $dependenciasActivas = array();
    $dependenciasActivasObjetivo = array();

    if ($rsActivas) {
        while (!$rsActivas->EOF) {
            $dependenciasActivas[] = (int)$rsActivas->fields['DEPE_CODI'];
            $rsActivas->MoveNext();
        }
        $rsActivas->Close();
    }

    $previewActivas = count($dependenciasActivas);
    $previewIgnoradas = $codigosIgnorar;
    $previewVisibles = $codigosVisibles;
    $dependenciasObservadoras = array();
    foreach ($dependenciasActivas as $depActiva) {
        if (!in_array($depActiva, $codigosIgnorar)) {
            $dependenciasObservadoras[] = $depActiva;
        }
    }

    // Si no se especifica lista de visibles, mantiene comportamiento anterior.
    if (empty($codigosVisibles)) {
        $codigosVisibles = $dependenciasActivas;
    }

    $visiblesValidas = array();
    foreach ($codigosVisibles as $depVisible) {
        if (in_array($depVisible, $dependenciasActivas) && !in_array($depVisible, $codigosIgnorar)) {
            $visiblesValidas[] = $depVisible;
        }
    }
    $dependenciasActivasObjetivo = array_values(array_unique($visiblesValidas));
    $previewActivasObjetivo = count($dependenciasActivasObjetivo);

    foreach ($dependenciasObservadoras as $depObserva) {
        foreach ($dependenciasActivasObjetivo as $depVisible) {
            if ($depObserva != $depVisible) {
                $previewTotal++;
            }
        }
    }

    if (isset($_POST['btn_aplicar'])) {
        if ($previewActivas < 1 || $previewActivasObjetivo < 1) {
            $mensaje = 'No hay dependencias activas para procesar con los filtros indicados.';
            $tipoMensaje = 'warning';
        } else {
            $inSetObservadoras = implode(',', $dependenciasObservadoras);
            $inSetVisiblesObjetivo = implode(',', $dependenciasActivasObjetivo);
            $db->conn->BeginTrans();

            // Modo aditivo:
            // No borra visibilidades actuales. Solo agrega relaciones faltantes.
            $conteoAntes = (int)$db->conn->GetOne(
                "SELECT COUNT(*) FROM DEPENDENCIA_VISIBILIDAD
                 WHERE DEPENDENCIA_OBSERVA IN ($inSetObservadoras)
                 AND DEPENDENCIA_VISIBLE IN ($inSetVisiblesObjetivo)"
            );

            $okInsert = true;
            foreach ($dependenciasObservadoras as $depObserva) {
                foreach ($dependenciasActivasObjetivo as $depVisible) {
                    if ($depObserva == $depVisible) {
                        continue;
                    }
                    $okInsert = $db->conn->Execute(
                        "INSERT INTO DEPENDENCIA_VISIBILIDAD (DEPENDENCIA_OBSERVA, DEPENDENCIA_VISIBLE)
                         SELECT $depObserva, $depVisible
                         WHERE NOT EXISTS (
                            SELECT 1
                            FROM DEPENDENCIA_VISIBILIDAD dv
                            WHERE dv.DEPENDENCIA_OBSERVA = $depObserva
                              AND dv.DEPENDENCIA_VISIBLE = $depVisible
                         )"
                    );
                    if (!$okInsert) {
                        break 2;
                    }
                }
            }

            if ($okInsert) {
                $conteoDespues = (int)$db->conn->GetOne(
                    "SELECT COUNT(*) FROM DEPENDENCIA_VISIBILIDAD
                     WHERE DEPENDENCIA_OBSERVA IN ($inSetObservadoras)
                     AND DEPENDENCIA_VISIBLE IN ($inSetVisiblesObjetivo)"
                );
                $relacionesNuevas = max(0, $conteoDespues - $conteoAntes);
                $db->conn->CommitTrans();
                $mensaje = "Proceso completado. Activas observadoras: $previewActivas. Activas visibles objetivo: $previewActivasObjetivo. Relaciones nuevas agregadas: $relacionesNuevas.";
                $tipoMensaje = 'success';
            } else {
                $db->conn->RollbackTrans();
                $mensaje = 'Ocurrió un error aplicando la visibilidad masiva.';
                $tipoMensaje = 'danger';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Visibilidad de Dependencias Masiva</title>
    <link rel="stylesheet" type="text/css" media="screen" href="<?= $ruta_raiz; ?>/estilos/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" media="screen" href="<?= $ruta_raiz; ?>/estilos/smartadmin-production.css">
    <link rel="stylesheet" type="text/css" media="screen" href="<?= $ruta_raiz; ?>/estilos/smartadmin-skins.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" media="screen" href="<?= $ruta_raiz ?>/estilos/custom.css">
</head>

<body>
    <div class="container-fluid mt-3">
        <section id="widget-grid">
            <div class="row">
                <article class="col-12">
                    <div class="card shadow-sm border-secondary">
                        <div class="card-header bg-orfeo text-white">
                            <h5 class="mb-0">
                                <i class="fa fa-eye me-2"></i>Visibilidad de Dependencias Masiva
                            </h5>
                        </div>
                        <div class="card-body bg-light">
                            <?php if ($mensaje !== '') { ?>
                                <div class="alert alert-<?= $tipoMensaje ?> mb-3"><?= htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8') ?></div>
                            <?php } ?>

                            <form method="post" action="<?= $_SERVER['PHP_SELF'] ?>">
                                <input type="hidden" name="<?= session_name() ?>" value="<?= session_id() ?>">

                                <div class="mb-3">
                                    <label for="dependencias_visibles" class="form-label fw-bold">Dependencias visibles para todas (separadas por coma)</label>
                                    <textarea name="dependencias_visibles" id="dependencias_visibles" rows="3" class="form-control" placeholder="Ejemplo: 12003,12005"><?= htmlspecialchars($visiblesInput, ENT_QUOTES, 'UTF-8') ?></textarea>
                                    <small class="text-muted">Estas dependencias serán visibles para todas las observadoras, excepto las que estén en ignoradas.</small>
                                </div>

                                <div class="mb-3">
                                    <label for="dependencias_ignorar" class="form-label fw-bold">Dependencias a ignorar (separadas por coma)</label>
                                    <textarea name="dependencias_ignorar" id="dependencias_ignorar" rows="3" class="form-control" placeholder="Ejemplo: 100,101,102"><?= htmlspecialchars($ignoradasInput, ENT_QUOTES, 'UTF-8') ?></textarea>
                                    <small class="text-muted">Los códigos listados no quedarán visibles para ninguna dependencia.</small>
                                </div>

                                <div class="mb-3 p-3 border rounded bg-white">
                                    <div><strong>Dependencias activas observadoras:</strong> <?= (int)$previewActivas ?></div>
                                    <div><strong>Dependencias visibles objetivo (activas y sin ignoradas):</strong> <?= (int)$previewActivasObjetivo ?></div>
                                    <div><strong>Relaciones a crear (observa-visible):</strong> <?= (int)$previewTotal ?></div>
                                    <div><strong>Visibles ingresadas:</strong> <?= empty($previewVisibles) ? 'Todas (comportamiento anterior)' : implode(', ', $previewVisibles) ?></div>
                                    <div><strong>Ignoradas:</strong> <?= empty($previewIgnoradas) ? 'Ninguna' : implode(', ', $previewIgnoradas) ?></div>
                                </div>

                                <button type="submit" name="btn_aplicar" value="1" class="btn btn-primary">
                                    <i class="fa fa-cogs me-1"></i>Aplicar visibilidad masiva
                                </button>
                            </form>
                        </div>
                    </div>
                </article>
            </div>
        </section>
    </div>
</body>

</html>
