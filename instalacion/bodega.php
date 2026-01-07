<?php
if (PHP_SAPI != 'cli') exit;

$root = realpath(__DIR__.'/..');
require "$root/dbconfig.php";
$bodega = "$root/bodega";
$year = date('Y');
$yearBorrador = $year + 1000;
$dirs = ['tmp','sys_img','fax','masiva','pdfs/guias','pdfs/planillas/dev',
    'pdfs/planillas/envios','plantillas/genericas','tmp/workDir/cacheODT',
    'tmp/radimail/imgs',"$year/formFiles",'firmas/grafo'
];

is_writable($bodega) or die("No existe o no tiene permisos bodega\n");

$host = explode(':', $servidor);
$port = $host[1] ?? 5432;
$dbconn = pg_connect("host={$host[0]} port={$port} dbname=$servicio user=$usuario password=$contrasena")
    or die('Could not connect: ' . pg_last_error());

$query = 'SELECT depe_codi FROM dependencia';
$result = pg_query($query) or die('Query failed: ' . pg_last_error());

while ($row = pg_fetch_assoc($result)) {
    $dirs[] = "$year/{$row['depe_codi']}/docs";
    $dirs[] = "$year/{$row['depe_codi']}/exp";
    $dirs[] = "$yearBorrador/{$row['depe_codi']}/docs";
    $dirs[] = "$yearBorrador/{$row['depe_codi']}/exp";
}

foreach ($dirs as $dir) {
    @mkdir("$bodega/$dir",0755,true);
}

$opts = getopt('s');
if (array_key_exists('s', $opts)) {
    $query = "select setval(sequencename::text, 1, false) from pg_sequences where sequencename ~ '^(secr_tp|borrador).*' and last_value > 0";
    $result = pg_query($query) or die('Query failed: ' . pg_last_error());
}

pg_free_result($result);
pg_close($dbconn);
