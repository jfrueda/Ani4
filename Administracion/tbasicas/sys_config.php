<?php

/**
 * @module config_system
 n*
 * @author Cesar Gonzalez <aurigadl@gmail.com>
 * @license  GNU AFFERO GENERAL PUBLIC LICENSE
 * @copyright 2020
 *
 * realizaro en tiempo de coronavirus

 This program is free software: you can redistribute it and/or modify
 it under the terms of the GNU Affero General Public License as published by
 the Free Software Foundation, either version 3 of the License, or
 (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU Affero General Public License for more details.

 You should have received a copy of the GNU Affero General Public License
 along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

session_start();
$ruta_raiz = "../..";

if (!$_SESSION['dependencia']) {
    header("Location: $ruta_raiz/cerrar_session.php");
}

include_once "$ruta_raiz/include/db/ConnectionHandler.php";
include_once "$ruta_raiz/processConfig.php";
include_once "$ruta_raiz/include/tx/roles.php";

$db    = new ConnectionHandler("$ruta_raiz");
$roles = new Roles($db);
$drd   = $_POST['pass'];
$krd   = $_SESSION["krd"];
$error = '';
$mess  = False;
$rest  = False;

if (strtoupper($krd) == 'ADMON' && $drd && $roles->traerPermisos($krd, $drd)) {
    $_SESSION["sys_config"] = True;
}

if ($_SESSION["sys_config"] && $_POST['form_config'] == 'Guardar') {
    foreach ($_FILES as $key => $val) {

        if ($val["size"] == 0) {
            continue;
        }

        $target_dir = $CONTENT_PATH;
        $namefile = '/sys_img/' . basename($val["name"]);
        $target_file = $target_dir . $namefile;
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $check = getimagesize($val["tmp_name"]);

        if ($check !== false) {
            $uploadOk = 1;
        } else {
            $uploadOk = 0;
        }

        if ($val["size"] > 5000000) {
            $uploadOk = 0;
            $error .= 'Tamaño superior a 5M';
        }

        if (
            $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif" && $imageFileType != "ico"
        ) {
            $uploadOk = 0;
        }

        if ($uploadOk == 0) {
            $error .= empty($error) ? $key : ', ' . $key;
            $error .= ' parametros incorrectos';
        } else {
            if (move_uploaded_file($val["tmp_name"], $target_file)) {
                $upd = "UPDATE
                            SGD_CONFIG
                        SET CONF_VALOR = '$namefile'
                        WHERE CONF_NOMBRE = '$key'";
                $rest = $db->conn->query($upd);
            } else {
                $error .= empty($error) ? $key : ', ' . $key;
                $error .= ' en directorio destino';
            }
        }
    }

    foreach ($_POST as $key => $val) {
        $upd = "UPDATE
                    SGD_CONFIG
                SET CONF_VALOR = '$val'
                WHERE CONF_NOMBRE = '$key'";
        $rest = $db->conn->query($upd);
        if (!$rest) {
            $error .= empty($error) ? $key : ', ' . $key;
        }
    }

    if (!$error) {
        $mess = time();
    }
}

if ($_SESSION["sys_config"]) {
    $data  = array();
    $data2 = array();
    $query = "SELECT * FROM SGD_CONFIG";
    $rs    = $db->conn->Execute($query);

    while (!$rs->EOF) {
        $desc = $rs->fields["CONF_DESCRIPCION"];
        $name = $rs->fields["CONF_NOMBRE"];
        $valu = $rs->fields["CONF_VALOR"];
        $imag = $rs->fields["CONF_IMAGEN"];
        $cons = $rs->fields["CONF_CONSTANTE"];

        if (!$imag) {
            $showDat = array(
                'DES' => $desc,
                'NAM' => $name,
                'VAL' => $valu,
                'SIM' => ''
            );

            if (!$cons) {
                $showDat['SIM'] = '$';
            }

            $data[] = $showDat;
        } else {

            $data2[] = array(
                'DES' => $desc,
                'NAM' => $name,
                'VAL' => $valu
            );
        }

        $rs->MoveNext();
    }
}

?>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="sys_config">
    <title>Orfeo- Admon de Dependencias.</title>
    <?php include_once "$ruta_raiz/htmlheader.inc.php"; ?>
    <style>
        .form-signin {
            max-width: 330px;
            padding: 15px;
        }

        .form-signin .form-signin-heading,
        .form-signin .checkbox {
            margin-bottom: 10px;
        }

        .form-signin .checkbox {
            font-weight: normal;
        }

        .form-signin .form-control {
            position: relative;
            font-size: 16px;
            height: auto;
            padding: 10px;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
        }

        .form-signin .form-control:focus {
            z-index: 2;
        }

        .form-signin input[type="text"] {
            margin-bottom: -1px;
            border-bottom-left-radius: 0;
            border-bottom-right-radius: 0;
        }

        .form-signin input[type="password"] {
            margin-bottom: 10px;
            border-top-left-radius: 0;
            border-top-right-radius: 0;
        }
    </style>
</head>

<body>
    <?php if (!$_SESSION["sys_config"]) { ?>
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-12 col-sm-8 col-md-6 col-lg-4">
                    <div class="card shadow border-0 py-4 px-3">
                        <div class="card-body">
                            <div class="text-center mb-3">
                                <i class="fa fa-lock fa-3x text-primary"></i>
                            </div>
                            <h4 class="text-center fw-bold mb-4">Administrador</h4>
                            <form class='form-signin' role="form" name="admin" id="admin" method="post" action="<?= $_SERVER['PHP_SELF'] ?>">
                                <p class="text-muted text-center small mb-4">
                                    Digite su contraseña de administrador para continuar
                                </p>
                                <div class="mb-3">
                                    <label class="form-label d-none">Contraseña</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="fa fa-key text-muted"></i>
                                        </span>
                                        <input name='pass' type="password" class="form-control border-start-0 ps-0 shadow-none" placeholder="Contraseña" required>
                                    </div>
                                </div>
                                <div class="d-grid gap-2 mt-4">
                                    <button class="btn btn-primary btn-lg fw-bold shadow-sm" type="submit">
                                        Entrar <i class="fa fa-sign-in ms-2"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <p class="mt-3 text-center text-muted small">
                        &copy; <?= date("Y") ?> Sistema Orfeo - Acceso Restringido
                    </p>

                </div>
            </div>
        </div>
    <?php }

    if ($mess) { ?>
        <div class="alert alert-success d-flex align-items-center shadow-sm border-start border-4 border-success" role="alert">
            <i class="fa-fw fa fa-check-circle me-2 fs-4"></i>
            <div>
                <strong id="alert-success">¡Éxito!</strong> Guardado <?= $mess ?>
            </div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php }

    if ($error) { ?>
        <div class="alert alert-danger d-flex align-items-center shadow-sm border-start border-4 border-danger" role="alert">
            <i class="fa-fw fa fa-exclamation-triangle me-2 fs-4"></i>
            <div>
                <strong id="alert-error">Error:</strong> No se actualizó el registro de: <?= $error ?>
            </div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php }

    if ($_SESSION["sys_config"]) { ?>
        <form role="form" name="form" id="config" method="post" enctype="multipart/form-data" action="<?= $_SERVER['PHP_SELF'] ?>">
            <div class="container-fluid margin-botton-table">
                <section id="widget-grid">
                    <div class="row justify-content-center">
                        <article class="col-12 ">
                            <div class="card shadow border-secondary">
                                <div class="card-header bg-orfeo text-white p-3">
                                    <h5 class="mb-0">
                                        <i class="fa fa-wrench me-2"></i>Variables de Configuración
                                    </h5>
                                </div>

                                <div class="card-body bg-light p-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle mb-0" id="datatable">
                                            <thead class="table-secondary">
                                                <tr>
                                                    <th scope="col" class="ps-4" width="40%">Descripción de Variable</th>
                                                    <th scope="col" class="pe-4">Configuración del Valor</th>
                                                </tr>
                                            </thead>
                                            <tbody id="contentable">
                                                <? foreach ($data2 as $key => $val) { ?>
                                                    <tr>
                                                        <td class="ps-4 py-3">
                                                            <h6 class="text-primary mb-1">
                                                                <span class="badge bg-secondary me-1"><?= $key ?></span>
                                                                $<?= $val['NAM'] ?>
                                                            </h6>
                                                            <small class="text-muted d-block lh-sm"><?= $val['DES'] ?></small>
                                                        </td>
                                                        <td class="pe-4 py-3">
                                                            <div class="mb-1">
                                                                <span class="badge bg-info text-dark">Valor Actual:</span>
                                                                <code class="ms-1 text-break"><?= htmlspecialchars($val['VAL']) ?></code>
                                                            </div>
                                                            <input type="file" class="form-control form-control-sm" name="<?= $val['NAM'] ?>" id="file_<?= $key ?>">
                                                        </td>
                                                    </tr>
                                                <? } ?>

                                                <? foreach ($data as $key => $val) { ?>
                                                    <tr>
                                                        <td class="ps-4 py-3 border-top">
                                                            <h6 class="text-primary mb-1">
                                                                <span class="badge bg-secondary me-1"><?= $key ?></span>
                                                                <?= $val['SIM'] ?><?= $val['NAM'] ?>
                                                            </h6>
                                                            <small class="text-muted d-block lh-sm"><?= $val['DES'] ?></small>
                                                        </td>
                                                        <td class="pe-4 py-3 border-top">
                                                            <div class="input-group input-group-sm shadow-sm">
                                                                <span class="input-group-text bg-white"><i class="fa fa-edit text-muted"></i></span>
                                                                <input
                                                                    class="form-control"
                                                                    type="text"
                                                                    name="<?= $val['NAM'] ?>"
                                                                    value="<?= htmlspecialchars($val['VAL']) ?>"
                                                                    placeholder="Ingrese valor..." />
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <? } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="card-footer bg-white text-end py-3 px-4">
                                    <input class="btn btn-primary px-5 fw-bold shadow-sm" name='form_config' value="Guardar" type="submit" />
                                </div>
                            </div>
                        </article>
                    </div>
                </section>
            </div>
        </form>
    <?php } ?>
</body>

</html>