<?php
session_start();

define('ADODB_ASSOC_CASE', 1);

$ruta_raiz = "..";
$ADODB_COUNTRECS = false;

include_once("$ruta_raiz/processConfig.php");
include_once("$ruta_raiz/include/db/ConnectionHandler.php");

$db = new ConnectionHandler($ruta_raiz);
$db->conn->SetFetchMode(ADODB_FETCH_ASSOC);

$resultado = null;
$error = null;

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numeroRadicado = isset($_POST['numeroRadicado']) ? trim($_POST['numeroRadicado']) : '';
    $codigoVerificacion = isset($_POST['codigoVerificacion']) ? trim($_POST['codigoVerificacion']) : '';
    
    if(empty($numeroRadicado)) {
        $error = "Por favor ingrese un número de radicado";
    } elseif(empty($codigoVerificacion)) {
        $error = "Por favor ingrese el código de verificación";
    } else {
        // Limpiar número de radicado
        $numeroRadicado = str_replace([' ', '.', ',', '_'], '', $numeroRadicado);
        // Limpiar y normalizar código de verificación (convertir a minúsculas)
        $codigoVerificacion = strtolower(trim($codigoVerificacion));
        
        // Consultar radicado
        $sql = "SELECT 
                    r.radi_nume_radi,
                    r.radi_fech_radi,
                    r.ra_asun,
                    r.sgd_rad_codigoverificacion,
                    r.radi_usua_actu,
                    r.radi_depe_actu,
                    u.usua_nomb,
                    d.depe_nomb,
                    t.sgd_tpr_descrip as tipo_doc,
                    CASE 
                        WHEN r.radi_leido = 1 THEN 'Leído'
                        ELSE 'No Leído'
                    END as estado_lectura
                FROM radicado r
                LEFT JOIN usuario u ON r.radi_usua_actu = u.usua_codi AND r.radi_depe_actu = u.depe_codi
                LEFT JOIN dependencia d ON r.radi_depe_actu = d.depe_codi
                LEFT JOIN sgd_tpr_tpdcumento t ON r.tdoc_codi = t.sgd_tpr_codigo
                WHERE r.radi_nume_radi = " . $db->conn->qstr($numeroRadicado);
        
        $rsRad = $db->conn->Execute($sql);
        
        if($rsRad && !$rsRad->EOF) {
            $radicado = $rsRad->fields;
            
            // Validar código de verificación (case-insensitive)
            // Intentar con diferentes casos de columna
            $codigoVerifBD = '';
            if(isset($radicado['sgd_rad_codigoverificacion'])) {
                $codigoVerifBD = strtolower(trim($radicado['sgd_rad_codigoverificacion'] ?? ''));
            } elseif(isset($radicado['SGD_RAD_CODIGOVERIFICACION'])) {
                $codigoVerifBD = strtolower(trim($radicado['SGD_RAD_CODIGOVERIFICACION'] ?? ''));
            }
            
            if($codigoVerifBD === $codigoVerificacion) {
                // Consultar anexos
                $sqlAnexos = "SELECT 
                                anex_numero,
                                anex_descripcion,
                                anex_tipo,
                                anex_nomb_archivo,
                                anex_fech_crea,
                                anex_size,
                                anex_carpeta
                            FROM anexos
                            WHERE anex_radi_nume = " . $db->conn->qstr($numeroRadicado) . "
                            AND anex_borrado = 'N'
                            ORDER BY anex_numero ASC";
                
                $rsAnexos = $db->conn->Execute($sqlAnexos);
                $anexos = array();
                
                if($rsAnexos && is_object($rsAnexos)) {
                    while(!$rsAnexos->EOF) {
                        $anexos[] = $rsAnexos->fields;
                        $rsAnexos->MoveNext();
                    }
                }
                
                $resultado = array(
                    'radicado' => $radicado,
                    'anexos' => $anexos
                );
            } else {
                $error = "Código de verificación incorrecto";
            }
        } else {
            $error = "Radicado no encontrado";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Consulta de Estado de Radicados - UMNG</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            padding-top: 2rem;
            padding-bottom: 2rem;
        }
        
        header {
            margin-bottom: 2rem;
        }
        
        .header-card {
            background: white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .logo-container {
            text-align: center;
            margin-bottom: 1.5rem;
        }
        
        .logo-container img {
            max-height: 120px;
            width: auto;
        }
        
        .main-card {
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;
            border: none !important;
            margin-bottom: 2rem;
        }
        
        .form-section {
            background: white;
        }
        
        .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);
            border: none;
            padding: 0.7rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #0b5ed7 0%, #0a58ca 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.4);
        }
        
        .success-alert {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            border: 1px solid #b1dfbb;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            color: #155724;
        }
        
        .error-alert {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            border: 1px solid #f1b0b7;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            color: #721c24;
        }
        
        .table {
            border-radius: 8px;
            overflow: hidden;
        }
        
        .table thead {
            background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);
            color: white;
        }
        
        .table thead th {
            border: none;
            font-weight: 600;
            padding: 1rem;
        }
        
        .table tbody td {
            padding: 0.8rem 1rem;
            vertical-align: middle;
        }
        
        .table tbody tr:nth-child(odd) {
            background-color: #f8f9fa;
        }
        
        .table tbody tr:hover {
            background-color: #e7f1ff;
            transition: background-color 0.2s ease;
        }
        
        .status-badge {
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
            display: inline-block;
        }
        
        .status-leido {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
        }
        
        .status-no-leido {
            background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
            color: #333;
        }
        
        .btn-consultar {
            width: 100%;
            padding: 0.8rem;
            font-size: 1.1rem;
            border-radius: 8px;
        }
        
        .btn-nueva-consulta {
            background: #6c757d;
            color: white;
            border: none;
            padding: 0.6rem 1.5rem;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-block;
            margin-top: 2rem;
        }
        
        .btn-nueva-consulta:hover {
            background: #5a6268;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(108, 117, 125, 0.4);
            text-decoration: none;
            color: white;
        }
        
        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
        }
        
        .form-text {
            color: #6c757d;
            font-size: 0.9rem;
        }
        
        .info-radicado {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 8px;
            margin-bottom: 2rem;
        }
        
        .info-radicado-title {
            color: #0d6efd;
            font-weight: 600;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #0d6efd;
        }
        
        .table-title {
            color: #0d6efd;
            font-weight: 600;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 3px solid #0d6efd;
        }
        
        a {
            color: #0d6efd;
            transition: color 0.2s ease;
        }
        
        a:hover {
            color: #0b5ed7;
        }
        
        footer {
            text-align: center;
            padding: 2rem;
            color: #6c757d;
            margin-top: 3rem;
        }
    </style>
</head>
<body class="bg-light">
    <header>
        <div class="container" style="max-width: 900px;">
            <div class="card header-card border-0">
                <div class="card-body p-4 p-md-5">
                    <div class="logo-container">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/c/c4/LOGO_UMNG.png" alt="Logo Universidad Militar Nueva Granada">
                    </div>
                    <h1 class="text-center fs-4 mb-3 text-primary">
                        <i class="bi bi-search me-2"></i>CONSULTA DE ESTADO DE RADICADOS
                    </h1>
                    <p class="text-center text-muted mb-0">
                        Sistema de Gestión Documental - Universidad Militar Nueva Granada
                    </p>
                </div>
            </div>
        </div>
    </header>

    <main>
        <div class="container" style="max-width: 900px;">
            <div class="card main-card">
                <div class="card-body p-4 p-md-5">
                    <form method="POST" action="" novalidate>
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="numeroRadicado" class="form-label">
                                    <i class="bi bi-file-earmark me-2"></i>Número de Radicado
                                </label>
                                <input type="text" class="form-control form-control-lg" id="numeroRadicado" name="numeroRadicado" 
                                       placeholder="Ej: 2026009000000182" 
                                       value="<?= isset($_POST['numeroRadicado']) ? htmlspecialchars($_POST['numeroRadicado']) : '' ?>" 
                                       required>
                                <small class="form-text">Número único de 19 dígitos</small>
                            </div>
                            
                            <div class="col-12">
                                <label for="codigoVerificacion" class="form-label">
                                    <i class="bi bi-shield-lock me-2"></i>Código de Verificación
                                </label>
                                <input type="text" class="form-control form-control-lg" id="codigoVerificacion" name="codigoVerificacion" 
                                       placeholder="Ingrese su código de verificación" 
                                       value="<?= isset($_POST['codigoVerificacion']) ? htmlspecialchars($_POST['codigoVerificacion']) : '' ?>" 
                                       required>
                                <small class="form-text">Código enviado al momento de radicar</small>
                            </div>
                            
                            <div class="col-12 pt-2">
                                <button type="submit" class="btn btn-primary btn-consultar">
                                    <i class="bi bi-search me-2"></i>Consultar Estado
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <?php if($error): ?>
                <div class="error-alert" role="alert">
                    <strong><i class="bi bi-exclamation-triangle me-2"></i>Error:</strong> <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <?php if($resultado): ?>
                <div class="success-alert" role="alert">
                    <strong><i class="bi bi-check-circle me-2"></i>Éxito:</strong> Radicado encontrado correctamente
                </div>
                
                <div class="card main-card">
                    <div class="card-body p-4 p-md-5">
                        <div class="info-radicado">
                            <h3 class="info-radicado-title">
                                <i class="bi bi-info-circle me-2"></i>Información del Radicado
                            </h3>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <strong class="d-block mb-1 text-secondary">Número de Radicado</strong>
                                        <span class="fs-6 text-primary fw-bold"><?= htmlspecialchars($resultado['radicado']['RADI_NUME_RADI']) ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <strong class="d-block mb-1 text-secondary">Fecha de Radicación</strong>
                                        <span class="fs-6"><?= isset($resultado['radicado']['RADI_FECH_RADI']) ? date('d/m/Y H:i:s', strtotime($resultado['radicado']['RADI_FECH_RADI'])) : 'N/A' ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <strong class="d-block mb-1 text-secondary">Asunto</strong>
                                        <span class="fs-6"><?= htmlspecialchars($resultado['radicado']['RA_ASUN'] ?? 'N/A') ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <strong class="d-block mb-1 text-secondary">Tipo de Documento</strong>
                                        <span class="fs-6"><?= htmlspecialchars($resultado['radicado']['TIPO_DOC'] ?? 'N/A') ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <strong class="d-block mb-1 text-secondary">Dependencia Actual</strong>
                                        <span class="fs-6"><?= htmlspecialchars($resultado['radicado']['DEPE_NOMB'] ?? 'N/A') ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <strong class="d-block mb-1 text-secondary">Usuario Actual</strong>
                                        <span class="fs-6"><?= htmlspecialchars($resultado['radicado']['USUA_NOMB'] ?? 'N/A') ?></span>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-0">
                                        <strong class="d-block mb-2 text-secondary">Estado</strong>
                                        <?php 
                                        $clase = strpos($resultado['radicado']['ESTADO_LECTURA'], 'Leído') !== false ? 'status-leido' : 'status-no-leido';
                                        ?>
                                        <span class="status-badge <?= $clase ?>">
                                            <i class="bi bi-circle-fill me-1"></i><?= htmlspecialchars($resultado['radicado']['ESTADO_LECTURA']) ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card main-card">
                    <div class="card-body p-4 p-md-5">
                        <h3 class="table-title">
                            <i class="bi bi-file-text me-2"></i>Anexos (<?= count($resultado['anexos']) ?> archivo<?= count($resultado['anexos']) !== 1 ? 's' : '' ?>)
                        </h3>
                        <?php if(count($resultado['anexos']) > 0): ?>
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th><i class="bi bi-hash me-1"></i>Nº</th>
                                            <th><i class="bi bi-file-pdf me-1"></i>Archivo</th>
                                            <th><i class="bi bi-tag me-1"></i>Tipo</th>
                                            <th><i class="bi bi-file-earmark-text me-1"></i>Tamaño</th>
                                            <th><i class="bi bi-calendar me-1"></i>Fecha</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($resultado['anexos'] as $anexo): ?>
                                            <tr>
                                                <td class="fw-bold text-primary"><?= htmlspecialchars($anexo['ANEX_NUMERO'] ?? 'N/A') ?></td>
                                                <td>
                                                    <?php 
                                                    $nombreArchivo = htmlspecialchars($anexo['ANEX_NOMB_ARCHIVO'] ?? 'N/A');
                                                    $carpeta = htmlspecialchars($anexo['ANEX_CARPETA'] ?? '');
                                                    if($carpeta && $carpeta !== 'N/A') {
                                                        $rutaArchivo = '/bodega/' . trim($carpeta, '/') . '/' . basename($anexo['ANEX_NOMB_ARCHIVO'] ?? '');
                                                        echo '<a href="' . htmlspecialchars($rutaArchivo) . '" target="_blank" class="text-decoration-none" title="Descargar ' . $nombreArchivo . '">
                                                                <i class="bi bi-download me-1"></i>' . $nombreArchivo . '
                                                              </a>';
                                                    } else {
                                                        echo '<i class="bi bi-file me-1"></i>' . $nombreArchivo;
                                                    }
                                                    ?>
                                                </td>
                                                <td><?= htmlspecialchars($anexo['ANEX_TIPO'] ?? 'N/A') ?></td>
                                                <td>
                                                    <?php 
                                                    if(isset($anexo['ANEX_SIZE']) && is_numeric($anexo['ANEX_SIZE'])) {
                                                        $size = $anexo['ANEX_SIZE'];
                                                        if($size >= 1024*1024) {
                                                            echo round($size/(1024*1024), 2) . ' MB';
                                                        } elseif($size >= 1024) {
                                                            echo round($size/1024, 2) . ' KB';
                                                        } else {
                                                            echo $size . ' bytes';
                                                        }
                                                    } else {
                                                        echo 'N/A';
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php 
                                                    if(isset($anexo['ANEX_FECH_CREA'])) {
                                                        echo date('d/m/Y H:i:s', strtotime($anexo['ANEX_FECH_CREA']));
                                                    } else {
                                                        echo 'N/A';
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-info mb-0" role="alert">
                                <i class="bi bi-info-circle me-2"></i>No hay anexos registrados para este radicado
                            </div>
                        <?php endif; ?>
                        
                        <a href="consulta_simple.php" class="btn-nueva-consulta">
                            <i class="bi bi-arrow-counterclockwise me-1"></i>Nueva Consulta
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <footer>
        <p>&copy; 2026 Universidad Militar Nueva Granada | Sistema de Gestión Documental</p>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>
