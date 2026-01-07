<?php
session_start();
require 'vendor/autoload.php';

use AltchaOrg\Altcha\ChallengeOptions;
use AltchaOrg\Altcha\Altcha;

define('ADODB_ASSOC_CASE', 1);
$ruta_raiz = __DIR__;
$ADODB_COUNTRECS = false;

include_once("$ruta_raiz/processConfig.php");

$hmacKey = $altcha_hmac;

$options = new ChallengeOptions([
    'hmacKey'   => $hmacKey,
    'maxNumber' => 250000,
]);

header('Content-Type: application/json; charset=utf-8');

try {
    $challenge = Altcha::createChallenge($options);
    echo json_encode($challenge);
} catch (Exception $e) {
    echo json_encode(['error' => 'Failed to create challenge: ' . $e->getMessage()]);
}