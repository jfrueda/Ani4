<?php

/**
 * @module index_frame
 *
 * @author Cesar Gonzalez <aurigadl@gmail.com>
 * @license  GNU AFFERO GENERAL PUBLIC LICENSE
 * @copyright

 SIIM2 Models are the data definition of SIIM2 Information System
 Copyright (C) 2013 Infometrika Ltda.

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

function validarSiSalida($array_radicados)
{
    $pattern = "1";
    $allEndWithPattern = true;

    foreach ($array_radicados as $item) {
        if (substr($item, -strlen($pattern)) !== $pattern) {
            $allEndWithPattern = false;
            break;
        }
    }

    return $allEndWithPattern;
}

session_start();

$ruta_raiz   = "..";
if (!$_SESSION['dependencia'])
    header("Location: $ruta_raiz/cerrar_session.php");
/*
    if (!$_SESSION["usua_admin_sistema"])
        header ("Location: $ruta_raiz/index.php");
    */
$krd         = $_SESSION["krd"];
$dependencia = $_SESSION["dependencia"];
$usua_doc    = $_SESSION["usua_doc"];
$codusuario  = $_SESSION["codusuario"];

$nurad   = isset($_POST['nurad']) ? $_POST['nurad'] : null;

preg_match_all('/\d{12,}/', $nurad, $output);
$output = $output[0];
$radToFind = implode(", ", $output);

if (
    isset($radToFind) &&
    !empty($radToFind) &&
    validarSiSalida($output)
) {


    include_once "$ruta_raiz/include/db/ConnectionHandler.php";
    $db = new ConnectionHandler("$ruta_raiz");

    if ($_GET['pruebas'] == 'true') {
        ini_set('display_errors', true);
        $db->conn->debug = true;
    }

    $isql = "select
                        a.id as id_anexo,
                        d.id as id_direccion,
                        a.anex_codigo as ANEX_CODIGO,
                        a.radi_nume_salida as RADICADO
                     from  anexos a,
                           radicado c,
                           sgd_dir_drecciones d
                    where
                        a.anex_borrado= 'N'
                    and a.radi_nume_salida = c.radi_nume_radi
                    and a.sgd_dir_tipo != 7
                    and d.radi_nume_radi = c.radi_nume_radi
                    and
                    ((a.SGD_DEVE_CODIGO >=0 and a.SGD_DEVE_CODIGO <=99)
                    OR a.SGD_DEVE_CODIGO IS NULL)
                    AND
                    ((c.SGD_EANU_CODIGO != 2
                    AND c.SGD_EANU_CODIGO != 1)
                    or c.SGD_EANU_CODIGO IS NULL)
                    AND a.radi_nume_salida in (%s) ";

    $sql21 = vsprintf($isql, $radToFind);
    $rstl = $db->conn->query($sql21);
    $contador = 1;
    $addRad = array();

    while (!$rstl->EOF && $rstl != false) {

        $dataMasiva   = '';
        $id_direccion = $rstl->fields['ID_DIRECCION'];
        $id_anexo     = $rstl->fields['ID_ANEXO'];
        $codigo       = $rstl->fields['ANEX_CODIGO'];
        $radicaMasiva = $rstl->fields['RADICADO'];
        $output = array_diff($output, array($radicaMasiva));

        //Si el radicado actula ya fue enviado entonces entonces no realizamos
        //la acción nuevamente.
        if (in_array($radicaMasiva, $addRad)) {
            $rstl->MoveNext();
            continue;
        }

        $addRad[] = $radicaMasiva;

        //Si se agrego el radicado a este listado entonces automaticamente
        //cambiamos el estado a marcado por enviar de la tabla de anexos
        $isqlUp100 = "update anexos set anex_estado = 4 where  anex_codigo = '$codigo'";
        $db->conn->Execute($isqlUp100);

        //Validamos que este creado el registro de envio en la tabla sgd_radi_Envio
        //que se creo para hacer la discriminacion entre envio fisico y correo electronico
        //Si no existe lo creamos para poder continuar con el envio en el script
        //responseEnvioE-mail.php
        $isqlSgdRE = "SELECT ID FROM sgd_rad_envios WHERE id_direccion = $id_direccion";
        $res_isqlSgdRE = $db->conn->Execute($isqlSgdRE);
        $envio = $res_isqlSgdRE->fields['ID'];

        if (!$envio) {
            $sql100 = "select count(id) + 1 as NUMB from sgd_rad_envios";
            $res_sql100 = $db->conn->query($sql100);
            $envio = $res_sql100->fields['NUMB'];

            $isqlInsRE = "  INSERT INTO
                                        sgd_rad_envios
                                    VALUES (
                                        $envio,
                                        $id_anexo,
                                        $id_direccion,
                                        'E-mail',
                                        2,
                                        NULL)";
            $res_InsRE = $db->conn->query($isqlInsRE);
        }

        $message .= '<tr>';
        $message .= "<td>
                                   $contador ) $radicaMasiva
                             </td>";

        include("$ruta_raiz/envios/responseEnvioE-mail.php");
        $message .= "<td>
                                <ul id='$radicaMasiva' class='list-group collapse'>
                                    $dataMasiva
                                </ul>
                                <button data-toggle='collapse' data-target='#$radicaMasiva'>
                                    $button
                                </button>
                             </td>";

        $message .= '</tr>';
        $contador++;
        $rstl->MoveNext();
    }

    if ($output) {
        $rad = implode(", ", $output);
        $success .= "<div class='alert alert-danger'>
                                No se procesaron los siguientes radicados <br>
                                $rad <br>
                                Comprobar que el radicado tenga dirección,
                                no este borrado o anulado.
                            </div>";
    } else {
        $timed =  date("Y-m-d h:i:sa");
        $success = "<div class='alert alert-success'>Acción realizada $timed</div>";
    }
} else {
    if (isset($output) && is_array($output) && !validarSiSalida($output)) {
        $rad = implode(", ", $output);
        $success .= "<div class='alert alert-danger'>
                                No se procesaron los siguientes radicados <br>
                                $rad <br>
                                solo es posible enviar salidas.
                            </div>";
    }
}
?>
<html>

<head>
    <title>Envio masivo de documentos por Correo Electronico</title>
    <?php include_once "$ruta_raiz/htmlheader.inc.php"; ?>

    <style>
        /*Cargar en envios/adminMasivaSend*/
        #cover-spin {
            position: fixed;
            width: 100%;
            left: 0;
            right: 0;
            top: 0;
            bottom: 0;
            background-color: rgba(255, 255, 255, 0.7);
            z-index: 9999;
            display: none;
        }

        @-webkit-keyframes spin {
            from {
                -webkit-transform: rotate(0deg);
            }

            to {
                -webkit-transform: rotate(360deg);
            }
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        #cover-spin::after {
            content: '';
            display: block;
            position: absolute;
            left: 48%;
            top: 40%;
            width: 40px;
            height: 40px;
            border-style: solid;
            border-color: black;
            border-top-color: transparent;
            border-width: 4px;
            border-radius: 50%;
            -webkit-animation: spin .8s linear infinite;
            animation: spin .8s linear infinite;
        }
    </style>

</head>

<body>
    <div id="app">
        <Transition name="slide-fade">
            <form v-if="showForm" action="" name="FrmBuscar" method="POST" class="container-fluid">
                <div class="row justify-content-center my-4">
                    <div class="col-lg-12">
                        <div class="card shadow border-0">
                            <div class="card-header bg-orfeo text-white py-3">
                                <h4 class="mb-0">Envío de documentos por Correo Electrónico</h4>
                            </div>

                            <div class="card-body">
                                <!-- Nota -->
                                <div class="alert alert-info text-center">
                                    <strong>NOTA:</strong> Puede enviar radicados de forma masiva a los
                                    correos electrónicos registrados.
                                </div>

                                <!-- Ingreso de radicados -->
                                <div class="mb-4">
                                    <label for="nurad" class="form-label fw-semibold">Números de radicados</label>
                                    <textarea
                                        rows="6"
                                        type="text"
                                        name="nurad"
                                        id="nurad"
                                        class="form-control"
                                        placeholder="Ingrese los radicados separados por comas o saltos de línea"><?= $radToFind ?></textarea>
                                </div>

                                <!-- Botón enviar -->
                                <div class="d-flex justify-content-end">
                                    <button
                                        type="submit"
                                        name="Enviar"
                                        class="btn btn-success px-4"
                                        onclick="$('#cover-spin').show(0)">
                                        Enviar
                                    </button>
                                </div>

                                <!-- Resultado -->
                                <?php if ($success) { ?>
                                    <div class="mt-4">
                                        <?= $success ?>

                                        <div class="table-responsive mt-3">
                                            <table class="table table-bordered">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Radicado</th>
                                                        <th>Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?= $message ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </Transition>
    </div>

    <!-- Loading Spinner -->
    <div id="cover-spin"></div>

    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
    <script>
        const {
            createApp
        } = Vue;

        createApp({
            data() {
                return {
                    showForm: false
                };
            },
            mounted() {
                this.showForm = true
            }
        }).mount('#app');
    </script>
</body>

</html>