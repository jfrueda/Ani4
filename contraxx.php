<?php

/**
 * @author Cesar Gonzalez <aurigadl@gmail.com>
 * @author Jairo Losada   <jlosada@gmail.com>
 * @license  GNU AFFERO GENERAL PUBLIC LICENSE
 * @copyright

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU Affero General Public License as published by
the Free Software Foundation, either version 3 of the License, or
any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Affero General Public License for more details.

You should have received a copy of the GNU Affero General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

session_start();

$ruta_raiz = ".";
if (!$_SESSION['dependencia']) {
    header("Location: $ruta_raiz/cerrar_session.php");
}

foreach ($_GET as $key => $valor) {
    ${$key} = $valor;
}
foreach ($_POST as $key => $valor) {
    ${$key} = $valor;
}

$krd         = $_SESSION["krd"];
$dependencia = $_SESSION["dependencia"];
$usua_doc    = $_SESSION["usua_doc"];
$codusuario  = $_SESSION["codusuario"];
$tip3Nombre  = $_SESSION["tip3Nombre"];
$tip3desc    = $_SESSION["tip3desc"];
$tip3img     = $_SESSION["tip3img"];

include_once "$ruta_raiz/include/db/ConnectionHandler.php";
$db = new ConnectionHandler($ruta_raiz);

$numeroa =
    $numero  =
    $numeros =
    $numerot =
    $numerop =
    $numeroh = 0;

$isql = "select
              a.*
              ,b.depe_nomb
         from
              usuario a
              ,dependencia b
          where
             a.depe_codi=b.depe_codi
             and a.USUA_CODI = $codusuario
             and b.DEPE_CODI = $dependencia";

$rs  = $db->query($isql);

$dependencianomb = $rs->fields["DEPE_NOMB"];
$usua_login      = $rs->fields["USUA_LOGIN"];

?>
<html>

<head>
    <title>Cambio de Contrase&ntilde;as</title>
    <?php include_once "$ruta_raiz/htmlheader.inc.php"; ?>
    <style>
        .error {
            color: red;
            margin-left: 5px;
        }

        label.error {
            display: inline;
        }
    </style>
</head>

<body>
    <form action='usuarionuevo.php' method="post" id="loginform" class="needs-validation" role="form">
        <input type='hidden' name='<?= session_name() ?>' value='<?= session_id() ?>'>

        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-12 col-md-6 col-lg-5">
                    <div class="card shadow-lg border-0">
                        <div class="card-header bg-dark bg-gradient text-white py-3">
                            <h5 class="mb-0 fw-bold">
                                <i class="fa fa-key me-2 text-warning"></i> Cambio de contraseña
                            </h5>
                            <?php if (!empty($tituloCrear)): ?>
                                <small class="text-white-50"><?= $tituloCrear ?></small>
                            <?php endif; ?>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <label for="contradrd" class="form-label fw-semibold">Contraseña Nueva:</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fa fa-lock"></i></span>
                                    <input
                                        type="password"
                                        name="contradrd"
                                        id="contradrd"
                                        class="form-control"
                                        placeholder="Ingrese su nueva clave"
                                        pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{7,}"
                                        required>
                                </div>
                                <div id="passwordHelp" class="form-text mt-2 small lh-sm">
                                    <i class="fa fa-info-circle me-1"></i> Requisitos: Mínimo 7 caracteres, incluyendo números, letras minúsculas y mayúsculas.
                                </div>
                            </div>
                            <div class="mb-4">
                                <label for="contraver" class="form-label fw-semibold">Confirmar Contraseña:</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fa fa-check-circle"></i></span>
                                    <input
                                        type="password"
                                        name="contraver"
                                        id="contraver"
                                        class="form-control"
                                        placeholder="Repita la clave"
                                        pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}"
                                        required>
                                </div>
                            </div>

                            <div class="d-grid gap-2 mt-2">
                                <button type="submit" class="btn btn-success btn-lg fw-bold shadow-sm">
                                    <i class="fa fa-save me-2"></i>Aceptar y Cambiar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script>
        jQuery.validator.addMethod('passwordMatch', function(value, element) {
            // The two password inputs
            var password = $("#contradrd").val();
            var confirmPassword = $("#contraver").val();

            // Check for equality with the password inputs
            if (password != confirmPassword) {
                return false;
            } else {
                return true;
            }

        }, "No son iguales las constraseñas");

        $("#loginform").validate({

            submitHandler: function(form) {
                form.submit();
            },

            rules: {
                contradrd: {
                    required: true,
                    minlength: 7,
                },

                contraver: {
                    equalTo: "#contradrd",
                    minlength: 7,
                    passwordMatch: true
                }
            },

            // messages
            messages: {
                contradrd: {
                    required: "El campo contraseña es requerido",
                    minlength: "El campo contraseña requiere mas de 7 caracteres",
                },
                contraver: {
                    required: "El campo confimar contraseña es requerido",
                    minlength: "El campo contraseña requiere mas de 7 caracteres",
                    passwordMatch: "Las constraseñas no son iguales", // custom message for mismatched passwords
                }
            }
        });
    </script>
</body>

</html>