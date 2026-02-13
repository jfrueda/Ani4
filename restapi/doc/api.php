<?php

require("../vendor/autoload.php");

$openapi = \OpenApi\Generator::scan([$_SERVER['DOCUMENT_ROOT'].'/2/restapi/App']);
//echo json_encode($_SERVER['DOCUMENT_ROOT']);

header('Content-Type: application/json');
echo $openapi->toJSON();
