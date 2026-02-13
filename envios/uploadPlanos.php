<?php

/**
 * @author Cesar Gonzalez <aurigadl@gmail.com>
 * @author Jairo Losada   <jlosada@gmail.com>
 * @author Correlibre.org
 * @license  GNU AFFERO GENERAL PUBLIC LICENSE
 * @copyright
 *
 * OrfeoGpl Models are the data definition of OrfeoGpl Information System
 * Copyright (C) 2013 Infometrika Ltda.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

session_start();

foreach ($_GET  as $key => $val) {
    ${$key} = $val;
}
foreach ($_POST as $key => $val) {
    ${$key} = $val;
}

$ruta_raiz = "..";

if (!$_SESSION['dependencia'])
    header("Location: $ruta_raiz/cerrar_session.php");

include_once "$ruta_raiz/include/db/ConnectionHandler.php";
include_once("$ruta_raiz/include/combos.php");

if (!$db)   $db = new ConnectionHandler($ruta_raiz);
$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;

/**
 * Retorna la cantidad de bytes de una expresion como 7M, 4G u 8K.
 *
 * @param char $var
 * @return numeric
 */
function return_bytes($val)
{
    $val = trim($val);
    $ultimo = strtolower($val[strlen($val) - 1]);
    switch ($ultimo) {   // El modificador 'G' se encuentra disponible desde PHP 5.1.0
        case 'g':
            $val *= 1024;
        case 'm':
            $val *= 1024;
        case 'k':
            $val *= 1024;
    }
    return $val;
}

if (!$tipo) $tipo = 3;

$paramsTRD = $phpsession . "&krd=$krd&codiEst=$codiEst&dependencia=$dependencia&usua_nomb=$usua_nomb&"
    . "depe_nomb=$depe_nomb&usua_doc=$usua_doc&codusuario=$codusuario";

$params = "dependencia=$dependencia&codiEst=$codiEst&usua_nomb=$usua_nomb&depe_nomb=$depe_nomb&usua_doc=$usua_doc&tipo=$tipo&codusuario=$codusuario";

$coddepe = $_SESSION['dependencia'];

if ($codEmp != 0) {
    $queryTRD = "select distinct sgd_tidm_codi AS CODIESTR from sgd_cob_campobliga
        where sgd_tidm_codi = '$codEmp'";
    $rsTRD = $db->conn->query($queryTRD);
    if ($rsTRD) {
        $codiEst = $rsTRD->fields['CODIESTR'];
    }
}

$num_car = 4;

$comentarioDev = "Despliega las Posibles Estructuras";
include "$ruta_raiz/include/tx/ComentarioTx.php";
?>

<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <?php include_once "$ruta_raiz/htmlheader.inc.php"; ?>
    <script>
        /**
         * Valida que el formulario desplegado se encuentre adecuadamente diligenciado
         * 
         */
        function validar() {
            archDocto = document.formAdjuntarArchivos.archivoPlantilla.value;
            if ((archDocto.substring(archDocto.length - 1 - 3, archDocto.length)).indexOf(".xls") == -1) {
                alert("El archivo de datos debe ser .xls");
                return false;
            }

            if (document.formAdjuntarArchivos.archivoPlantilla.value.length < 1) {
                alert("Debe ingresar el archivo XLS con los datos");
                return false;
            }

            return true;
        }

        function enviar() {
            if (!validar())
                return;

            document.formAdjuntarArchivos.accion.value = "PRUEBA";
            document.formAdjuntarArchivos.submit();
        }
    </script>
</head>

<body>
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-orfeo text-white py-3">
                        <h4 class="mb-0">
                            Cargue de resultado de envíos
                            <br>
                            <small class="text-light"><?= $tituloCrear ?></small>
                        </h4>
                    </div>

                    <div class="card-body">

                        <!-- Primer formulario oculto -->
                        <form name="formaTRD" action="uploadPlanos.php?<?= $paramsTRD ?>" method="post">
                            <table class="table d-none">
                                <tr class="text-center">
                                    <td colspan="2" class="fw-bold">Cargar acuse de recibo</td>
                                </tr>
                                <tr class="text-center">
                                    <td class="fw-semibold">EMPRESA</td>
                                    <td>
                                        <select name="codEmp" onchange="submit()" class="form-select w-auto mx-auto">
                                            <option value="0">-- Seleccione --</option>
                                            <option value="2" <?php if ($codEmp == 2) echo "selected"; ?>>Genérica</option>
                                            <option value="3" <?php if ($codEmp == 3) echo "selected"; ?>>4-72</option>
                                        </select>
                                    </td>
                                </tr>
                            </table>
                        </form>

                        <!-- Segundo formulario -->
                        <form action="adjuntar_PlanoEnvio.php?<?= $params ?>" method="post" enctype="multipart/form-data" name="formAdjuntarArchivos">

                            <input type="hidden" name="<?= session_name() ?>" value="<?= session_id() ?>">
                            <input type="hidden" name="codiEst" value="<?= $codiEst ?>">

                            <div class="table-responsive">
                                <table class="table align-middle">

                                    <tr class="text-center bg-light">
                                        <td colspan="2" class="fw-bold py-3">
                                            Seleccionar archivo plano
                                            <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo return_bytes(ini_get('upload_max_filesize')); ?>">
                                            <input type="hidden" name="accion" id="accion">
                                        </td>
                                    </tr>

                                    <tr class="text-center">
                                        <td class="fw-semibold" width="20%">Plantilla carga</td>
                                        <td>
                                            <a href="../bodega/plantillas/Formato_masivo_planillas_guias.xls"
                                                target="_blank"
                                                class="btn btn-sm btn-outline-primary">
                                                Descargar plantilla base
                                            </a>
                                        </td>
                                    </tr>

                                    <tr class="text-center">
                                        <td class="fw-semibold">Archivo</td>
                                        <td>
                                            <input name="archivoPlantilla"
                                                type="file"
                                                id="archivoPlantilla"
                                                value="<?= $archivoPlantilla ?>"
                                                class="form-control w-75 mx-auto"
                                                accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                                        </td>
                                    </tr>

                                    <tr class="text-center bg-light">
                                        <td colspan="2" class="py-3">
                                            <button type="button"
                                                name="enviaPrueba"
                                                id="envia22"
                                                onclick="enviar();"
                                                class="btn btn-primary px-4">
                                                Cargar
                                            </button>
                                        </td>
                                    </tr>

                                    <tr class="text-center">
                                        <td colspan="2" class="text-muted py-3">
                                            Esta operación permite registrar la información suministrada por la empresa de correo.
                                        </td>
                                    </tr>

                                </table>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>