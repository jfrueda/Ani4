<?php

session_start();

$ruta_raiz = "../..";

if (!isset($_SESSION['dependencia']) || !$_SESSION['dependencia']) {
    header("Location: $ruta_raiz/cerrar_session.php");
    exit;
}

if (!isset($_SESSION["usua_admin_sistema"]) || !$_SESSION["usua_admin_sistema"]) {
    header("Location: $ruta_raiz/cerrar_session.php");
    exit;
}

include_once "$ruta_raiz/include/db/ConnectionHandler.php";
include_once "$ruta_raiz/processConfig.php";

$db = new ConnectionHandler($ruta_raiz);
$db->conn->SetFetchMode(ADODB_FETCH_ASSOC);

$termino = '';
if (isset($_GET['q'])) {
    $termino = trim($_GET['q']);
}

$resultados = array();
$errorConsulta = '';

if ($termino !== '') {
    $like = '%' . $termino . '%';
    $sql = "SELECT
                u.id,
                u.usua_nomb AS nombres,
                u.usua_login AS login,
                u.usua_doc AS documento,
                u.usua_email AS correo,
                u.depe_codi AS dependencia_codigo,
                d.depe_nomb AS dependencia_nombre,
                u.usua_esta AS estado,
                u.usua_codi AS codigo_usuario,
                u.codi_nivel AS nivel,
                u.usua_login_ldap AS ldap_login,
                u.usua_fech_sesion AS ultima_sesion,
                COALESCE(string_agg(DISTINCT ag.nombre, ', ' ORDER BY ag.nombre), 'Sin rol asignado') AS roles
            FROM usuario u
            INNER JOIN dependencia d ON d.depe_codi = u.depe_codi
            LEFT JOIN autm_membresias am ON am.autu_id = u.id
            LEFT JOIN autg_grupos ag ON ag.id = am.autg_id
            WHERE (
                u.usua_nomb ILIKE ?
                OR u.usua_email ILIKE ?
                OR u.usua_login ILIKE ?
                OR CAST(u.usua_doc AS VARCHAR) ILIKE ?
            )
            GROUP BY
                u.id,
                u.usua_nomb,
                u.usua_login,
                u.usua_doc,
                u.usua_email,
                u.depe_codi,
                d.depe_nomb,
                u.usua_esta,
                u.usua_codi,
                u.codi_nivel,
                u.usua_login_ldap,
                u.usua_fech_sesion
            ORDER BY u.usua_nomb ASC
            LIMIT 200";

    $resultados = $db->conn->GetAll($sql, array($like, $like, $like, $like));
    if ($resultados === false) {
        $errorConsulta = $db->conn->ErrorMsg();
        $resultados = array();
    }
}

function e($texto)
{
    return htmlspecialchars((string)$texto, ENT_QUOTES, 'UTF-8');
}

function rowValue($fila, $campo)
{
    if (isset($fila[$campo])) {
        return $fila[$campo];
    }
    $campoMin = strtolower($campo);
    if (isset($fila[$campoMin])) {
        return $fila[$campoMin];
    }
    $campoMay = strtoupper($campo);
    if (isset($fila[$campoMay])) {
        return $fila[$campoMay];
    }

    return '';
}
?>
<!DOCTYPE html>
<html>
<head>
    <?php include_once "$ruta_raiz/htmlheader-old.inc.php"; ?>
    <style>
        body {
            margin: 0;
            padding: 16px;
        }
        .panel {
            background: #fff;
            border: 1px solid #d7d7d7;
            border-radius: 6px;
            padding: 16px;
        }
        .panel h3 {
            margin-top: 0;
            margin-bottom: 12px;
            color: #0b4f9c;
        }
        .panel p {
            margin-top: 0;
            margin-bottom: 16px;
            color: #4c4c4c;
        }
        .filtro {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-bottom: 14px;
        }
        .filtro input[type="text"] {
            min-width: 320px;
            width: 55%;
            padding: 8px 10px;
            border: 1px solid #b5b5b5;
            border-radius: 4px;
        }
        .filtro button {
            padding: 8px 14px;
            border: 0;
            border-radius: 4px;
            background: #0b4f9c;
            color: #fff;
            cursor: pointer;
        }
        .filtro a {
            display: inline-block;
            padding: 8px 14px;
            border-radius: 4px;
            background: #efefef;
            color: #333;
            text-decoration: none;
        }
        .mensaje {
            margin: 10px 0;
            padding: 10px;
            border-radius: 4px;
            font-size: 13px;
        }
        .mensaje.error {
            background: #ffe5e5;
            border: 1px solid #ffb8b8;
            color: #8b1d1d;
        }
        .mensaje.info {
            background: #edf6ff;
            border: 1px solid #bdddfd;
            color: #1f4b75;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }
        th, td {
            border: 1px solid #d8d8d8;
            padding: 6px 8px;
            vertical-align: top;
            text-align: left;
        }
        th {
            background: #f3f6fa;
            color: #203045;
        }
        .estado-activo {
            color: #1f7a34;
            font-weight: 600;
        }
        .estado-inactivo {
            color: #8f2727;
            font-weight: 600;
        }
        .desktop-table {
            display: block;
        }
        .mobile-list {
            display: none;
        }
        .card-usuario {
            border: 1px solid #d8d8d8;
            border-radius: 6px;
            padding: 10px;
            margin-bottom: 10px;
            background: #fff;
        }
        .card-usuario h4 {
            margin: 0 0 8px 0;
            font-size: 14px;
            color: #203045;
        }
        .card-usuario .linea {
            margin: 3px 0;
            font-size: 12px;
            color: #2f2f2f;
            word-break: break-word;
        }
        .card-usuario .etiqueta {
            font-weight: 600;
            color: #4a4a4a;
        }
        @media (max-width: 768px) {
            .filtro input[type="text"] {
                min-width: 100%;
                width: 100%;
            }
            .desktop-table {
                display: none;
            }
            .mobile-list {
                display: block;
            }
        }
    </style>
</head>
<body>
<div class="panel">
    <h3>Consultar usuario</h3>
    <p>Busqueda por nombre, email, login o documento. Se muestra dependencia e informacion general del usuario.</p>

    <form method="get" class="filtro">
        <input
            type="text"
            name="q"
            value="<?php echo e($termino); ?>"
            placeholder="Ejemplo: juan, juan@correo.com, jgomez o 1030..."
        />
        <button type="submit">Buscar</button>
        <a href="consulta_usuario.php">Limpiar</a>
    </form>

    <?php if ($errorConsulta !== '') { ?>
        <div class="mensaje error">Error en la consulta: <?php echo e($errorConsulta); ?></div>
    <?php } ?>

    <?php if ($termino !== '' && $errorConsulta === '') { ?>
        <div class="mensaje info">Resultados: <?php echo count($resultados); ?> registro(s).</div>

        <?php if (empty($resultados)) { ?>
            <div class="mensaje info">No se encontraron usuarios para el criterio ingresado.</div>
        <?php } else { ?>
            <div class="desktop-table">
                <table>
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Login</th>
                        <th>Email</th>
                        <th>Documento</th>
                        <th>Dependencia</th>
                        <th>Rol(es)</th>
                        <th>Estado</th>
                        <th>Cod. usuario</th>
                        <th>Nivel</th>
                        <th>Login LDAP</th>
                        <th>Ultima sesion</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($resultados as $fila) { ?>
                        <?php
                        $estado = rowValue($fila, 'estado');
                        $esActivo = ((string)$estado === '1');
                        ?>
                        <tr>
                            <td><?php echo e(rowValue($fila, 'id')); ?></td>
                            <td><?php echo e(rowValue($fila, 'nombres')); ?></td>
                            <td><?php echo e(rowValue($fila, 'login')); ?></td>
                            <td><?php echo e(rowValue($fila, 'correo')); ?></td>
                            <td><?php echo e(rowValue($fila, 'documento')); ?></td>
                            <td><?php echo e(rowValue($fila, 'dependencia_codigo') . ' - ' . rowValue($fila, 'dependencia_nombre')); ?></td>
                            <td><?php echo e(rowValue($fila, 'roles')); ?></td>
                            <td class="<?php echo $esActivo ? 'estado-activo' : 'estado-inactivo'; ?>">
                                <?php echo $esActivo ? 'Activo' : 'Inactivo'; ?>
                            </td>
                            <td><?php echo e(rowValue($fila, 'codigo_usuario')); ?></td>
                            <td><?php echo e(rowValue($fila, 'nivel')); ?></td>
                            <td><?php echo e(rowValue($fila, 'ldap_login')); ?></td>
                            <td><?php echo e(rowValue($fila, 'ultima_sesion')); ?></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>

            <div class="mobile-list">
                <?php foreach ($resultados as $fila) { ?>
                    <?php
                    $estado = rowValue($fila, 'estado');
                    $esActivo = ((string)$estado === '1');
                    ?>
                    <div class="card-usuario">
                        <h4><?php echo e(rowValue($fila, 'nombres')); ?></h4>
                        <div class="linea"><span class="etiqueta">Estado:</span> <span class="<?php echo $esActivo ? 'estado-activo' : 'estado-inactivo'; ?>"><?php echo $esActivo ? 'Activo' : 'Inactivo'; ?></span></div>
                        <div class="linea"><span class="etiqueta">Login:</span> <?php echo e(rowValue($fila, 'login')); ?></div>
                        <div class="linea"><span class="etiqueta">Email:</span> <?php echo e(rowValue($fila, 'correo')); ?></div>
                        <div class="linea"><span class="etiqueta">Documento:</span> <?php echo e(rowValue($fila, 'documento')); ?></div>
                        <div class="linea"><span class="etiqueta">Dependencia:</span> <?php echo e(rowValue($fila, 'dependencia_codigo') . ' - ' . rowValue($fila, 'dependencia_nombre')); ?></div>
                        <div class="linea"><span class="etiqueta">Rol(es):</span> <?php echo e(rowValue($fila, 'roles')); ?></div>
                        <div class="linea"><span class="etiqueta">Cod. usuario:</span> <?php echo e(rowValue($fila, 'codigo_usuario')); ?></div>
                        <div class="linea"><span class="etiqueta">Nivel:</span> <?php echo e(rowValue($fila, 'nivel')); ?></div>
                        <div class="linea"><span class="etiqueta">Login LDAP:</span> <?php echo e(rowValue($fila, 'ldap_login')); ?></div>
                        <div class="linea"><span class="etiqueta">Ultima sesion:</span> <?php echo e(rowValue($fila, 'ultima_sesion')); ?></div>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>
    <?php } ?>
</div>
</body>
</html>
