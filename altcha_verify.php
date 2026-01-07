<?php
session_start();
require 'vendor/autoload.php';

use AltchaOrg\Altcha\ChallengeOptions;
use AltchaOrg\Altcha\Altcha;

define('ADODB_ASSOC_CASE', 1);
$ruta_raiz = __DIR__;
$ADODB_COUNTRECS = false;
include_once("$ruta_raiz/processConfig.php");
include_once("$ruta_raiz/include/db/ConnectionHandler.php");

$db   = new ConnectionHandler($ruta_raiz);
$db->conn->SetFetchMode(ADODB_FETCH_ASSOC);

function verify_captcha($payload) {
    global $altcha_hmac;
    global $db;

    $hmacKey = $altcha_hmac;
    $decodedPayload = base64_decode($payload);
    $payload = json_decode($decodedPayload, true);
    $verified = Altcha::verifySolution($payload, $hmacKey, true);
    $challenge_count = $db->conn->getRow('SELECT COUNT(id) as total FROM altcha WHERE challenge = ?', [$payload['challenge']]);
    if ($challenge_count['TOTAL'] > 0 || $payload['challenge'] == null) {
        $verified = false;
    } else {
        $db->conn->execute('INSERT INTO altcha (challenge, created_at) VALUES (?, ?)', [$payload['challenge'], date('Y-m-d H:i:s')]);
    }
    
    return $verified;
}