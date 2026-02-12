<?php
session_start();
/**
 * @module index_frame
 *
 * @author Jairo Losada   <jlosada@gmail.com>
 * @author Cesar Gonzalez <aurigadl@gmail.com>
 * @license  GNU AFFERO GENERAL PUBLIC LICENSE
 * @copyright
 * SIIM2 Models are the data definition of SIIM2 Information System
 * Copyright (C) 2013 Infometrika Ltda.
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 * 
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

// //ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

/**
 * Funcion para validar el navegador para no permitir el vegador de Microsft y/o Edge 
 */
function get_the_browser()
{
  if (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)
    return false;
  elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Trident') !== false)
    return false;
  elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Edg') !== false)
    return false;
  else
    return true;
}

$isValidBrowser = get_the_browser();

$drd = false;
$krd = false;

if (isset($_POST["krd"])) {
  $krd = $_POST["krd"];
}

if (isset($_POST["drd"])) {
  $drd = $_POST["drd"];
}

$year = date('Y');

if (isset($_POST["autenticaPorLDAP"])) {
  $autenticaPorLDAP = $_POST["autenticaPorLDAP"];
}

$fechah        = date("dmy") . "_" . time();
$ruta_raiz     = ".";
$usua_nuevo    = 3;
$ValidacionKrd  = "";

include("dbconfig.php");
include("processConfig.php");
$serv = str_replace(".", ".", $_SERVER['REMOTE_ADDR']);

if ($krd) {
  //session_orfeo retorna mensaje de error
  include "$ruta_raiz/session_orfeo.php";
  require_once("$ruta_raiz/class_control/Mensaje.php");

  if ($usua_nuevo == 0 &&  !$autenticaPorLDAP && false) {
    include($ruta_raiz . "/contraxx.php");
    $ValidacionKrd = "NOOOO";
    if ($j = 1)
      die("<center> -- </center>");
  }
}

include_once("include/utils/Utils.php");

$krd = strtoupper($krd);

if ($ValidacionKrd == "Si") {
  header("Location: $ruta_raiz/index_frames.php");
  exit();
}

$ico = "$ruta_raiz/bodega/$favicon";
$bac = "$ruta_raiz/bodega/$background";
//$header = "$ruta_raiz/bodega/$headerRtaPdf";
$imgLogin = "$ruta_raiz/bodega/sys_img/imgLogin.png";
$imgPie = "$ruta_raiz/bodega/sys_img/pie_login.png";
$leftSection = "$ruta_raiz/bodega/sys_img/login.png";

if ($logoEntidad) {
  $log = "$ruta_raiz/bodega/$logoEntidad";
} else {
  $log = "$ruta_raiz/img/orfeo.png";
}
?>

<!DOCTYPE html>
<!--[if IE 8]> <html class="no-js lt-ie9" lang="en" > <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js" lang="en"><!--<![endif]-->

<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="SIIM2">
  <meta name="keywords" content="">
  <link rel="shortcut icon" href="<?= (file_exists("$ico")) ? $ico : "" ?>" onClick="this.reload();">
  <title>..:: <?= $entidad ?> Orfeo ::..</title>
  <!-- Bootstrap core CSS -->
  <!-- <link href="./estilos/bootstrap.min.css" rel="stylesheet"> -->
  <!-- <link href="./estilos/bootstrap.css" rel="stylesheet"> -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">

  <!-- Custom styles for this template -->
  <link href="./estilos/<?= (file_exists("./estilos/$entidad.login.css")) ? $entidad . "." : "" ?>login.css" rel="stylesheet">

  <style>
    .right-section {
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
      flex: 1;
      background: #ecf0f1;
    }
  </style>

</head>

<body>
  <!-- start Login box -->
  <?php
  $err_response = array(
    '400' => 'La petición realizada es inválida.',
    '401' => 'Acceso a recurso no atuorizado.',
    '403' => 'No tiene permisos para acceder a este recurso.',
    '404' => 'La página solicitada no fué encontrada.',
    '500' => 'Lo sentimos, ha ocurrido un error inesperado.'
  );
  ?>
  <?php if (isset($_GET['code']) &&  array_key_exists($_GET['code'], $err_response)): ?>
    <h4 style="color: #fff; ">ERROR: <?= $err_response[$_GET['code']]; ?></h4>
  <?php endif; ?>

  <div class="left-section">
    <img src="<?= $leftSection ?>" alt="Logo" class="logo">
  </div>

  <div class="right-section" id="app">
    <Transition name="bounce">
      <div v-if="showLogin" class="container" id="login-block">
        <div class="row justify-content-center">
          <div class="col-md-12">
            <div class="login-box show">
              <div class="login-form text-center">
                <img src="<?= $leftSection ?>" width="100" alt="Logo Orfeo">
                <p class="login-alt-text text-center">Sistema de gestión documental</p>
                <?= @$msgindex ?>
                <form action="./login.php??fecha=250314_1395788602&amp;PHPSESSID=&amp;krd=&amp;swLog=1" method="post">
                  <input type="hidden" name="csrf_token" value="<?php echo Utils::get_token() ?>">
                  <div class="mb-3">
                    <label for="usr" class="form-label">Usuario</label>
                    <input type="text" name="krd" required class="form-control" id="usr" placeholder="Usuario">
                  </div>
                  <div class="mb-3">
                    <label for="pass" class="form-label">Contraseña</label>
                    <input type="password" name="drd" required class="form-control" id="pass" placeholder="Contraseña">
                  </div>
                  <div class="mb-3" style="display:none">
                    <label for="code" class="form-label">Código</label>
                    <input type="text" name="code" class="form-control" id="code" placeholder="Código" autocomplete="off">
                  </div>
                  <?php if ($isValidBrowser): ?>
                    <button type="submit" class="btn btn-primary w-100">INGRESAR</button>
                  <?php else: ?>
                    <div class="alert alert-warning" role="alert">
                      <h4>Información!</h4>
                      Para un mejor desempeño, usa un navegador diferente a <u>Internet Explorer</u> y/o <u>Microsoft Edge</u>.
                    </div>
                  <?php endif; ?>
                </form>
                <div class="text-error">
                  <?php if (!empty($mensajeError)) { ?>
                    <?= $mensajeError ?>
                  <?php } ?>
                </div>
              </div>
              <div>
                <span id="signinButton">
                  <span
                    class="g-signin"
                    data-callback="signinCallback"
                    data-clientid="CLIENT_ID"
                    data-cookiepolicy="single_host_origin"
                    data-requestvisibleactions="http://schemas.google.com/AddActivity"
                    data-scope="https://www.googleapis.com/auth/plus.login">
                  </span>
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </Transition>
    <footer class="container text-center mt-4">
      <p id="footer-text"><small>Copyleft <?= $year ?>, basado en OrfeoGPL</small></p>
    </footer>
  </div>
  <!-- End Login box -->

  <script>
    if (window.self !== window.top) top.location.reload();
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
  <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
  <script>
    const {
      createApp
    } = Vue;

    createApp({
      data() {
        return {
          showLogin: false
        };
      },
      mounted() {
        this.showLogin = true
      }
    }).mount('#app');
  </script>
</body>

</html>