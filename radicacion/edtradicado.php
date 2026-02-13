<?php

/**
 * @module index_frame
 *
 * @author Jairo Losada   <jlosada@gmail.com>
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

foreach ($_GET as $key => $valor)   ${$key} = $valor;
foreach ($_POST as $key => $valor)   ${$key} = $valor;


$krd         = $_SESSION["krd"];
$dependencia = $_SESSION["dependencia"];
$usua_doc    = $_SESSION["usua_doc"];
$codusuario  = $_SESSION["codusuario"];
$tpNumRad    = $_SESSION["tpNumRad"];
$tpPerRad    = $_SESSION["tpPerRad"];
$tpDescRad   = $_SESSION["tpDescRad"];
$tip3Nombre  = $_SESSION["tip3Nombre"];
$tip3img     = $_SESSION["tip3img"];
$tpDepeRad   = $_SESSION["tpDepeRad"];

?>
<html>

<head>
  <title>Buscar Radicado</title>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
  <?php include_once "$ruta_raiz/htmlheader.inc.php"; ?>
  <script>
    $(function() {
      var button_value = "Buscar Radicado";

      $('#boton_buscar').on('click', function(e) {
        var nrad = $('#nurad').val();

        $('#boton_buscar').val('Verificando...');
        var request = $.post('ajax_buscarRadicado.php', {
            servicio: 'modificar_valido',
            radicado: nrad,
            dependencia: "<?= $dependencia ?>",
            codUsua: "<?= $codusuario ?>"
          },
          'json'
        );

        request.done(function(data) {
          $('#boton_buscar').val(button_value);

          if (data.total == 'finalizado') {
            Swal.fire({
              icon: 'warning',
              title: 'Modificación de Radicados',
              text: 'El radicado que está consultando está archivado y/o anulado, por lo cual no se puede realizar ninguna modificación.',
              confirmButtonText: 'Aceptar'
            }).then((result) => {
              if (result.isConfirmed) {
                let limpCajaRad = document.getElementById('nurad');
                limpCajaRad.focus();
                limpCajaRad.value = '';
              }
            });
          } else {

            if (data.total == 0) {
              alert('Por favor verifique el número de radicado ya que no se registra ninguna coindencia.');
            } else if (data.total > 1) {
              alert('Por favor verifique el número de radicado ya que se registra más de una coindencia.');
            } else if (data.total != 1) {
              alert('Por favor verifique el número de radicado.');
            } else if (data.total == 1) {
              $('#form_buscar').submit();
            }
          }

        });
      });
    });
  </script>
</head>

<body onload='document.getElementById("nurad").focus();'>
  <form id="form_buscar"
    action='NEW.php?<?= session_name() . "=" . session_id() . "&krd=$krd" ?>&Submit3=ModificarDocumentos'
    name="FrmBuscar"
    method="POST"
    class="container-fluid py-4">

    <div class="row justify-content-center">
      <div class="col-lg-10">

        <!-- Card principal -->
        <div class="card shadow-lg border-0">
          <div class="card-header bg-primary text-white py-3">
            <h4 class="mb-0">
              <i class="fa fa-pencil-square-o me-2"></i>
              Modificación de Radicados
            </h4>
            <small class="text-white-50 ms-1"><?= $tituloCrear ?></small>
          </div>

          <div class="card-body">

            <!-- Nota informativa -->
            <div class="alert alert-info d-flex align-items-center" role="alert">
              <i class="fa fa-info-circle fa-lg me-2"></i>
              Ingrese el número de radicado que desea modificar.
            </div>

            <div class="mb-4">
              <label for="nurad" class="form-label fw-semibold">Número de Radicado</label>

              <div class="input-group">
                <span class="input-group-text bg-light">
                  <i class="fa fa-hashtag" aria-hidden="true"></i>
                </span>

                <input
                  type="text"
                  name="nurad"
                  id="nurad"
                  class="form-control"
                  placeholder="Ejemplo: 2024000123"
                  onkeypress="return event.keyCode!=13" />

                <!-- Botón buscar -->
                <button
                  id="boton_buscar"
                  type="button"
                  class="btn btn-primary">
                  <i class="fa fa-search me-2"></i> Buscar
                </button>
              </div>

              <!-- Inputs ocultos -->
              <input type="hidden" name="modificarRad" value="ModificarR" id="modificarRad">
              <input type="hidden" name="Buscar" value="Buscar Radicado">
            </div>

          </div>
        </div>

      </div>
    </div>
  </form>
</body>

</html>