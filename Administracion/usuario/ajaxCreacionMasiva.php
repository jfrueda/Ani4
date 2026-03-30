<?php
session_start();

$ruta_raiz = '../../';

header('Content-Type: application/json; charset=utf-8');

if (!$_SESSION['dependencia'] || !$_SESSION['usua_admin_sistema']) {
    echo json_encode([
        'estado' => 0,
        'mensaje' => 'No autorizado.'
    ]);
    exit;
}

include_once "$ruta_raiz/include/db/ConnectionHandler.php";
include_once "$ruta_raiz/include/tx/roles.php";

$db = new ConnectionHandler($ruta_raiz);
$roles = new Roles($db);
$db->conn->SetFetchMode(ADODB_FETCH_ASSOC);

$rowsJson = isset($_POST['rows']) ? $_POST['rows'] : '[]';
$rows = json_decode($rowsJson, true);

if (!is_array($rows) || !count($rows)) {
    echo json_encode([
        'estado' => 0,
        'mensaje' => 'No se recibieron filas para procesar.'
    ]);
    exit;
}

$contratista = $db->conn->getOne('SELECT id FROM autg_grupos WHERE nombre = ?', ['Contratista']);
$profesional = $db->conn->getOne('SELECT id FROM autg_grupos WHERE nombre = ?', ['Profesional']);
$gruposPermitidos = array_filter([(int)$contratista, (int)$profesional]);

$totalOk = 0;
$totalError = 0;
$errores = [];

foreach ($rows as $idx => $row) {
    $linea = $idx + 1;

    $usuario = strtoupper(trim(isset($row['usuarios']) ? $row['usuarios'] : ''));
    $nuevo = trim(isset($row['nuevo']) ? $row['nuevo'] : '');
    $nombres = trim(isset($row['nombres']) ? $row['nombres'] : '');
    $documento = trim(isset($row['documento']) ? $row['documento'] : '');
    $dependencia = trim(isset($row['dependencia']) ? $row['dependencia'] : '');
    $correo = trim(isset($row['correo']) ? $row['correo'] : '');
    $estado = trim(isset($row['estado']) ? $row['estado'] : '');
    $nivel = trim(isset($row['nivel']) ? $row['nivel'] : '');
    $ldap = isset($row['ldap_login']) ? trim($row['ldap_login']) : 'null';
    $grupo = trim(isset($row['grupo']) ? $row['grupo'] : '');

    if ($usuario === '' || $nuevo === '' || $nombres === '' || $documento === '' || $dependencia === '' || $correo === '' || $estado === '' || $nivel === '' || $grupo === '') {
        $totalError++;
        $errores[] = "Fila {$linea}: faltan campos obligatorios.";
        continue;
    }

    if (!in_array((int)$grupo, $gruposPermitidos, true)) {
        $totalError++;
        $errores[] = "Fila {$linea}: el grupo {$grupo} no es permitido. Use ID de Contratista o Profesional.";
        continue;
    }

    if (!in_array((int)$estado, [0, 1], true)) {
        $totalError++;
        $errores[] = "Fila {$linea}: estado inválido ({$estado}).";
        continue;
    }

    if (!in_array((int)$nivel, [1, 2, 3, 4, 5], true)) {
        $totalError++;
        $errores[] = "Fila {$linea}: nivel inválido ({$nivel}).";
        continue;
    }

    if ($ldap !== 'null' && !in_array((int)$ldap, [0, 1], true)) {
        $totalError++;
        $errores[] = "Fila {$linea}: ldap_login inválido ({$ldap}).";
        continue;
    }

    try {
        $okCrear = $roles->creaEditaUsuario($usuario, $nombres, (int)$nuevo, $correo, (int)$estado, (int)$dependencia, null, $documento, (int)$nivel, $ldap);

        if (!$okCrear) {
            $totalError++;
            $mensaje = $roles->error ? $roles->error : 'No se pudo crear el usuario.';
            $errores[] = "Fila {$linea} ({$usuario}): {$mensaje}";
            continue;
        }

        $usuarioId = $db->conn->getOne('SELECT id FROM usuario WHERE usua_login = ?', [$usuario]);

        if (!$usuarioId) {
            $totalError++;
            $errores[] = "Fila {$linea} ({$usuario}): no se pudo localizar el usuario recién creado.";
            continue;
        }

        // Ajusta estado/nuevo para mantener el comportamiento esperado de la vista original.
        $roles->creaEditaUsuario($usuario, $nombres, (int)$nuevo, $correo, (int)$estado, (int)$dependencia, (int)$usuarioId, $documento, (int)$nivel, $ldap);

        // Asegura que solo uno de los dos perfiles quede activo como perfil por defecto.
        if ($contratista) {
            $roles->modificarMembresia((int)$contratista, (int)$usuarioId, false);
        }
        if ($profesional) {
            $roles->modificarMembresia((int)$profesional, (int)$usuarioId, false);
        }
        $roles->modificarMembresia((int)$grupo, (int)$usuarioId, true);

        $totalOk++;
    } catch (Exception $e) {
        $totalError++;
        $errores[] = "Fila {$linea} ({$usuario}): " . $e->getMessage();
    }
}

echo json_encode([
    'estado' => 1,
    'total_ok' => $totalOk,
    'total_error' => $totalError,
    'errores' => $errores
]);
