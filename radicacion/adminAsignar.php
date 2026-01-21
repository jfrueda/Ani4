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

session_start();

$ruta_raiz   = "..";
if (!$_SESSION['dependencia'])
  header("Location: $ruta_raiz/cerrar_session.php");

if (!$_SESSION["usua_perm_adminasig"])
  header("Location: $ruta_raiz/index.php");

$krd         = $_SESSION["krd"];
$dependencia = $_SESSION["dependencia"];
$usua_doc    = $_SESSION["usua_doc"];
$codusuario  = $_SESSION["codusuario"];

$operea = isset($_POST['operea']) ? $_POST['operea'] : null;
$coddepe = isset($_POST['coddepe']) ? $_POST['coddepe'] : null;
$codusua = isset($_POST['codusua']) ? $_POST['codusua'] : null;
$nurad   = isset($_POST['nurad']) ? $_POST['nurad'] : null;
$desc   = isset($_POST['desc']) ? $_POST['desc'] : null;

include("$ruta_raiz/include/db/ConnectionHandler.php");
include "$ruta_raiz/include/tx/Tx.php";

$db = new ConnectionHandler($ruta_raiz);
$tx = new Tx($db);

$esadmin = $_SESSION['usua_admin_sistema'];
$whereDep = '';
if ($esadmin) {
  $whereDep = 'or d.depe_codi in (900,999)';
}
$query  = "
    SELECT d.DEPE_CODI || ' - ' || d.DEPE_NOMB,
        d.DEPE_CODI
    FROM
    DEPENDENCIA d
    where
        d.depe_estado = '1' and ( $whereDep)
    ORDER BY d.DEPE_CODI, d.DEPE_NOMB";

$rs1 = $db->conn->Execute($query);

$depselect = $rs1->GetMenu2(
  "coddepe",
  $coddepe,
  "0:-- Seleccione una Dependencia --",
  false,
  "",
  "onChange='submit()'  class='form-control'"
);

if ($coddepe) {
  $query  = "SELECT
            d.USUA_NOMB || '-' || d.USUA_LOGIN,
            d.USUA_CODI
        FROM
          usuario d
        where
          d.usua_esta = '1'
        and d.depe_codi = {$_POST['coddepe']}
        ORDER BY d.USUA_NOMB";

  $rs = $db->conn->Execute($query);

  $ususelect = $rs->GetMenu2(
    "codusua",
    $codusua,
    "0:-- Seleccione un Usuario --",
    false,
    "",
    "onChange='submit()'  class='form-control'"
  );
}

$arryRad = array_filter(explode(",", trim($nurad)));

if (
  isset($operea) && $operea == 2 &&
  isset($coddepe) &&
  isset($codusua) &&
  isset($arryRad) &&
  isset($desc) &&
  !empty($arryRad)
) {

  if (!$tx->validarReasignar($arryRad, 2)) {
    $success = "<div class='alert alert-warning'>Si usa la opción  Reasignar ningún radicado listado puede estar archivado.</div>";
  } else {
    $observa = "Reasignado por $krd " . (isset($desc) ? " Motivo: $desc" : '');
    $usCodDestino = $tx->reasignar(
      $arryRad,
      $krd,
      $coddepe,
      $dependencia,
      $codusua,
      $codusuario,
      1,
      $observa,
      9,
      0,
      false
    );

    $success = "<div class='alert alert-success'>Acción realizada</div>";
  }
}

if (
  isset($operea) && $operea == 1 &&
  isset($arryRad) &&
  !empty($arryRad)
) {

  if (!$tx->validarBorrador($arryRad)) {
    $success = "<div class='alert alert-warning'>Si usa la opción  Desarchivar no pueden estar en el listado radicados tipo borrador.</div>";
  } elseif (!$tx->validarReasignar($arryRad, 1)) {
    $success = "<div class='alert alert-warning'>Si usa la opción  Desarchivar todos los radicados listados deben estar archivados.</div>";
  } else {
    $tx->desarchivar(
      $arryRad,
      $krd,
      $dependencia,
      $codusuario,
      1,
      9,
      0,
      false
    );
    $success = "<div class='alert alert-success'>Acción realizada</div>";
  }
}


?>
<html>

<head>
  <title>Reasignar documentos</title>
  <?php include_once "$ruta_raiz/htmlheader.inc.php"; ?>
</head>

<body>
  <div id="app">
    <Transition name="slide-fade">
      <form v-if="showForm" action="" name="FrmBuscar" class="container-fluid" method="POST">
        <div class="row justify-content-center my-4">
          <div class="col-lg-12">

            <div class="card shadow border-0">
              <div class="card-header bg-orfeo text-white py-3">
                <h4 class="mb-0">Traslado de documentos</h4>
              </div>

              <div class="card-body">
                <div class="alert alert-info text-center">
                  <strong>NOTA:</strong> Puede cambiar radicados de un usuario a otro y desarchivar documentos.
                </div>

                <!-- Operación -->
                <div class="mb-3">
                  <label for="operea" class="form-label fw-semibold">Operación a realizar</label>
                  <select name="operea" id="operea" onchange="submit()" class="form-select">
                    <option value="0">-- Seleccione una operación --</option>

                    <?php if ($esadmin) { ?>
                      <option value="1" <?= $operea == 1 ? 'selected' : '' ?>>Desarchivar</option>
                    <?php } ?>

                    <option value="2" <?= $operea == 2 ? 'selected' : '' ?>>Reasignar</option>
                  </select>
                </div>

                <!-- Dependencia destino -->
                <?php if ($operea && $operea == 2) { ?>
                  <div class="mb-3">
                    <label class="form-label fw-semibold">Dependencia destino</label>
                    <?= $depselect ?>
                  </div>
                <?php } ?>

                <!-- Usuario destino -->
                <?php if ($coddepe && $operea == 2) { ?>
                  <div class="mb-3">
                    <label class="form-label fw-semibold">Usuario destino</label>
                    <?= $ususelect ?>
                  </div>
                <?php } ?>

                <!-- Número de radicados -->
                <?php if (($codusua && $operea == 2) || $operea == 1) { ?>
                  <div class="mb-3">
                    <label for="nurad" class="form-label fw-semibold">Número de radicados (separados por coma)</label>
                    <input type="text" name="nurad" id="nurad" class="form-control" placeholder="Ej: 20240001, 20240002">
                  </div>
                <?php } ?>

                <!-- Motivo -->
                <?php if ($codusua && $operea == 2) { ?>
                  <div class="mb-3">
                    <label for="desc" class="form-label fw-semibold">Motivo</label>
                    <input type="text" name="desc" id="desc" class="form-control" placeholder="Ej: Reasignación por carga laboral">
                  </div>
                <?php } ?>

                <!-- Botón -->
                <?php if (($codusua && $operea == 2) || $operea == 1) { ?>
                  <div class="d-flex justify-content-end">
                    <button type="submit" name="Trasladar" class="btn btn-success px-4">
                      Trasladar
                    </button>
                  </div>
                <?php } ?>

                <!-- Mensaje success -->
                <?php if (!empty($success)) { ?>
                  <div class="mt-3">
                    <?= $success ?>
                  </div>
                <?php } ?>

              </div>
            </div>
          </div>
        </div>
      </form>
    </Transition>
  </div>

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