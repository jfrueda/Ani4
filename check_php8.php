<?php
// Directorio a escanear (el punto indica la carpeta actual)
$directory = new RecursiveDirectoryIterator('.');
$iterator = new RecursiveIteratorIterator($directory);
$files = new RegexIterator($iterator, '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH);

// Funciones eliminadas o problemáticas en PHP 8.x
$obsolete_functions = [
    'get_magic_quotes_gpc' => 'Eliminada en PHP 8.0',
    'each'                 => 'Eliminada en PHP 8.0 (Usar foreach)',
    'utf8_encode'          => 'Obsoleta en 8.2 / Eliminada en 8.4',
    'utf8_decode'          => 'Obsoleta en 8.2 / Eliminada en 8.4',
    'create_function'      => 'Eliminada en PHP 8.0',
    'money_format'         => 'Eliminada en PHP 8.0',
    'restore_include_path' => 'Eliminada en PHP 8.0',
    'split'                => 'Eliminada hace mucho (Usar explode o preg_split)'
];

echo "--- Iniciando escaneo de compatibilidad PHP 7.4 -> 8.4 ---\n";

foreach ($files as $file) {
    $filename = $file[0];
    $content = file_get_contents($filename);

    foreach ($obsolete_functions as $func => $reason) {
        if (preg_match("/\b$func\s*\(/i", $content)) {
            echo "[ALERTA] Archivo: $filename \n";
            echo "         Función detectada: $func ($reason)\n";
            echo "--------------------------------------------------\n";
        }
    }
}

echo "--- Escaneo finalizado ---\n";
