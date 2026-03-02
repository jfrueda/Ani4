<?php
declare(strict_types=1);

// Endpoint ACS basico para recibir respuesta SAML 2.0 (Google IdP).

header('Content-Type: application/json; charset=UTF-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'ok' => false,
        'message' => 'Metodo no permitido. Use POST para SAMLResponse.',
    ]);
    exit;
}

$samlResponse = isset($_POST['SAMLResponse']) ? trim((string)$_POST['SAMLResponse']) : '';
$relayState = isset($_POST['RelayState']) ? trim((string)$_POST['RelayState']) : null;

if ($samlResponse === '') {
    http_response_code(400);
    echo json_encode([
        'ok' => false,
        'message' => 'No se recibio SAMLResponse.',
    ]);
    exit;
}

$decodedXml = base64_decode($samlResponse, true);
if ($decodedXml === false || $decodedXml === '') {
    http_response_code(400);
    echo json_encode([
        'ok' => false,
        'message' => 'SAMLResponse no es Base64 valido.',
    ]);
    exit;
}

libxml_use_internal_errors(true);
$xml = new DOMDocument();
$loaded = $xml->loadXML($decodedXml, LIBXML_NONET);

if (!$loaded) {
    http_response_code(400);
    echo json_encode([
        'ok' => false,
        'message' => 'XML SAML invalido.',
    ]);
    exit;
}

$xpath = new DOMXPath($xml);
$xpath->registerNamespace('samlp', 'urn:oasis:names:tc:SAML:2.0:protocol');
$xpath->registerNamespace('saml', 'urn:oasis:names:tc:SAML:2.0:assertion');

$issuerNode = $xpath->query('/samlp:Response/saml:Issuer')->item(0);
$nameIdNode = $xpath->query('//saml:Subject/saml:NameID')->item(0);
$sessionIndexNode = $xpath->query('//saml:AuthnStatement/@SessionIndex')->item(0);

$issuer = $issuerNode ? trim($issuerNode->textContent) : null;
$nameId = $nameIdNode ? trim($nameIdNode->textContent) : null;
$sessionIndex = $sessionIndexNode ? trim($sessionIndexNode->nodeValue) : null;

// Nota: este endpoint valida estructura minima.
// La validacion criptografica de firma y condiciones SAML debe hacerse
// antes de habilitar autenticacion en produccion.
echo json_encode([
    'ok' => true,
    'message' => 'SAMLResponse recibida en ACS.',
    'acs' => basename(__FILE__),
    'issuer' => $issuer,
    'name_id' => $nameId,
    'session_index' => $sessionIndex,
    'relay_state' => $relayState,
    'received_at' => gmdate('c'),
]);
