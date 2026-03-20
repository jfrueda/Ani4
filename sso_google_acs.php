<?php
declare(strict_types=1);

$ruta_raiz = '.';

function failSso(string $message, int $status = 400): void
{
    http_response_code($status);
    header('Content-Type: text/html; charset=UTF-8');
    echo '<h3>Error de autenticacion SSO</h3>';
    echo '<p>' . htmlspecialchars($message, ENT_QUOTES, 'UTF-8') . '</p>';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    failSso('Metodo no permitido. Use POST para SAMLResponse.', 405);
}

$samlResponse = isset($_POST['SAMLResponse']) ? trim((string)$_POST['SAMLResponse']) : '';
$relayState = isset($_POST['RelayState']) ? trim((string)$_POST['RelayState']) : '';

if ($samlResponse === '') {
    failSso('No se recibio SAMLResponse.');
}

$decodedXml = base64_decode($samlResponse, true);
if ($decodedXml === false || $decodedXml === '') {
    failSso('SAMLResponse no es Base64 valido.');
}

libxml_use_internal_errors(true);
$xml = new DOMDocument();
if (!$xml->loadXML($decodedXml, LIBXML_NONET)) {
    failSso('XML SAML invalido.');
}

$xpath = new DOMXPath($xml);
$xpath->registerNamespace('samlp', 'urn:oasis:names:tc:SAML:2.0:protocol');
$xpath->registerNamespace('saml', 'urn:oasis:names:tc:SAML:2.0:assertion');

$issuerNode = $xpath->query('/samlp:Response/saml:Issuer')->item(0);
$nameIdNode = $xpath->query('//saml:Subject/saml:NameID')->item(0);

$issuer = $issuerNode ? trim($issuerNode->textContent) : '';
$nameId = $nameIdNode ? trim($nameIdNode->textContent) : '';

$allowedIssuer = 'https://accounts.google.com/o/saml2?idpid=C01gxnbnc';
if ($issuer === '' || $issuer !== $allowedIssuer) {
    failSso('Issuer SAML no autorizado.');
}

if ($nameId === '' || strpos($nameId, '@') === false) {
    failSso('NameID no valido en la respuesta SAML.');
}

include_once $ruta_raiz . '/include/db/ConnectionHandler.php';
$db = new ConnectionHandler($ruta_raiz);
$email = strtolower($nameId);

$sql = "SELECT USUA_LOGIN
        FROM USUARIO
        WHERE usua_esta = '1'
          AND (
              LOWER(COALESCE(USUA_EMAIL, '')) = ?
              OR LOWER(COALESCE(USUA_EMAIL_1, '')) = ?
              OR LOWER(COALESCE(USUA_EMAIL_2, '')) = ?
          )
        ORDER BY USUA_CODI";

$usuarioLogin = (string)$db->conn->GetOne($sql, [$email, $email, $email]);
if ($usuarioLogin === '') {
    failSso('No existe un usuario activo en Orfeo asociado al correo autenticado.');
}

$krd = strtoupper($usuarioLogin);
$drd = '';
$autenticaExterna = true;

include $ruta_raiz . '/session_orfeo.php';

if (!isset($ValidacionKrd) || $ValidacionKrd !== 'Si') {
    failSso('No fue posible iniciar sesion en Orfeo con el usuario autenticado.');
}

$scriptDir = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');
if ($scriptDir === '') {
    $scriptDir = '/';
}

$defaultRedirect = ($scriptDir === '/' ? '' : $scriptDir) . '/index_frames.php';
$target = $defaultRedirect;

if ($relayState !== '') {
    $relayUrl = parse_url($relayState);
    $currentHost = $_SERVER['HTTP_HOST'] ?? '';

    if ($relayUrl !== false && isset($relayUrl['host']) && strcasecmp($relayUrl['host'], $currentHost) === 0 && isset($relayUrl['path'])) {
        $relayPath = $relayUrl['path'];
        $relayBasename = strtolower((string)basename($relayPath));

        if ($relayBasename !== 'login.php' && $relayBasename !== 'index.php') {
            $target = $relayPath;
        }

        if (isset($relayUrl['query'])) {
            $target .= '?' . $relayUrl['query'];
        }
    }
}

header('Location: ' . $target);
exit;
