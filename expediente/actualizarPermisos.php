<?php
session_start();
$ruta_raiz = __DIR__ . "/..";
include_once "$ruta_raiz/include/db/ConnectionHandler.php";
include_once "$ruta_raiz/expediente/expediente.class.php";

$db = new ConnectionHandler($ruta_raiz);
$expClass = new Expediente($ruta_raiz);

if (php_sapi_name() === 'cli') {
    // Ejemplo: php procesar_seguridad.php accion=procesar year=2024
    parse_str(implode('&', array_slice($argv, 1)), $_GET);
} else {
    // Si es navegador, exige sesión
    if (!$_SESSION['dependencia']) die("Sesión no válida");
}

$year = $_GET['year'] ?? date('Y');
$accion = $_GET['accion'] ?? '';

$niveles = [
    'Publica' => 0,
    'Reservada' => 1,
    'Clasificada' => 2
];

if (!$accion && php_sapi_name() !== 'cli') {
    $total = $db->conn->getOne("
        SELECT COUNT(*) FROM sgd_sexp_secexpedientes WHERE sgd_sexp_ano = ? AND seguridad IS NULL
    ", [$year]);

    echo "<h2>Procesamiento de nivel de seguridad ($year)</h2>";
    echo "<p><b>Total de expedientes:</b> " . number_format($total) . "</p>";
    echo "<form method='get'>
            <input type='hidden' name='year' value='$year'>
            <button type='submit' name='accion' value='csv'>Generar CSV de verificación</button>
            <button type='submit' name='accion' value='procesar' onclick=\"return confirm('¿Seguro que deseas aplicar los cambios?');\">Ejecutar proceso real</button>
          </form>";
    exit;
}

if ($accion === 'csv') {
    $filename = "resumen_seguridad_{$year}.csv";

    if (php_sapi_name() === 'cli') {
        $output = fopen($filename, 'w');
        echo "Generando CSV: $filename\n";
    } else {
        header('Content-Type: text/csv; charset=utf-8');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        $output = fopen('php://output', 'w');
    }

    fputcsv($output, ['Expediente', 'Permisos', 'Seguridad antes']);

    $batchSize = 5000;
    $offset = 0;

    while (true) {
        $expedientes = $db->conn->getAll("
            SELECT 
                e.sgd_exp_numero,
                COALESCE(
                    (SELECT COUNT(*) FROM sgd_aexp_aclexp a WHERE a.num_expediente = e.sgd_exp_numero),
                    0
                ) AS permisos
            FROM sgd_sexp_secexpedientes e
            WHERE e.sgd_sexp_ano = ? AND
                e.seguridad IS NULL
            ORDER BY e.sgd_exp_numero
            LIMIT ? OFFSET ?
        ", [$year, $batchSize, $offset]);

        if (empty($expedientes)) break;

        foreach ($expedientes as $exp) {
            switch ($exp['PERMISOS']) {
                case 0: $seguridad = "Publica"; break;
                case 2: $seguridad = "Reservada"; break;
                case 4: $seguridad = "Clasificada"; break;
                default: $seguridad = "Publica"; break;
            }

            fputcsv($output, [
                $exp['SGD_EXP_NUMERO'],
                $exp['PERMISOS'],
                $seguridad
            ]);
        }

        $offset += $batchSize;
    }

    fclose($output);

    if (php_sapi_name() === 'cli') {
        echo "Archivo generado exitosamente.\n";
    }
    exit;
}

if ($accion === 'procesar') {
    set_time_limit(0);
    ini_set('memory_limit', '1024M');

    $total = $db->conn->getOne("
        SELECT COUNT(*) FROM sgd_sexp_secexpedientes WHERE sgd_sexp_ano = ? AND seguridad IS NULL
    ", [$year]);

    $batchSize = 2000;
    $offset = 0;
    $procesados = 0;

    $isCli = php_sapi_name() === 'cli';
    $inicio = microtime(true);

    if ($isCli) {
        echo "Iniciando actualización de niveles de seguridad ($year)...\n";
        echo "Total de expedientes: $total\n";
    } else {
        echo "<h3>Iniciando actualización de niveles de seguridad ($year)...</h3>";
        ob_flush(); flush();
    }

    while (true) {
        $expedientes = $db->conn->getAll("
            SELECT 
                e.sgd_exp_numero,
                COALESCE(
                    (SELECT COUNT(*) FROM sgd_aexp_aclexp a WHERE a.num_expediente = e.sgd_exp_numero),
                    0
                ) AS permisos
            FROM sgd_sexp_secexpedientes e
            WHERE e.sgd_sexp_ano = ? AND
                e.seguridad IS NULL
            ORDER BY e.sgd_exp_numero
            LIMIT ? OFFSET ?
        ", [$year, $batchSize, $offset]);

        if (empty($expedientes)) break;

        foreach ($expedientes as $exp) {
            switch ($exp['PERMISOS']) {
                case 0: $seguridad = "Publica"; break;
                case 2: $seguridad = "Reservada"; break;
                case 4: $seguridad = "Clasificada"; break;
                default: $seguridad = "Publica"; break;
            }

            $nuevoNivel = $niveles[$seguridad];
            $db->conn->execute("
                UPDATE sgd_sexp_secexpedientes
                SET seguridad = ?
                WHERE sgd_exp_numero = ?
            ", [$nuevoNivel, $exp['SGD_EXP_NUMERO']]);

            $procesados++;
        }

        if ($isCli) {
            echo "Procesados: $procesados / $total\n";
        } else {
            echo "Procesados: $procesados / $total<br>";
            ob_flush(); flush();
        }

        $offset += $batchSize;
    }

    $duracion = round((microtime(true) - $inicio) / 60, 2);

    if ($isCli) {
        echo "Proceso finalizado.\n";
        echo "Total procesados: $procesados\n";
        echo "Duración: {$duracion} minutos.\n";
    } else {
        echo "<p><b>Proceso finalizado.</b></p>";
        echo "<p>Total procesados: $procesados</p>";
        echo "<p>Duración: {$duracion} minutos.</p>";
    }
}