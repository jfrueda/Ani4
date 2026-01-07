<?php

/**
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
$ruta_raiz = "..";
if (!$_SESSION['dependencia'])
  header("Location: $ruta_raiz/cerrar_session.php");

$krd         = $_SESSION["krd"];
$dependencia = $_SESSION["dependencia"];
$usua_doc    = $_SESSION["usua_doc"];
$codusuario  = $_SESSION["codusuario"];
$entidad     = $_SESSION["entidad"];
$datos_enviar = session_name() . "=" . session_id();

include_once "$ruta_raiz/processConfig.php";
include_once "$ruta_raiz/include/db/ConnectionHandler.php";
$db = new ConnectionHandler("$ruta_raiz");

$sql_rol = "SELECT autg_id FROM autm_membresias WHERE autg_id=6 AND autu_id=" . $_SESSION['usua_id'];
$rs_rol = $db->conn->Execute($sql_rol);
if ($_SESSION['envios_dependencia'] >= 1) {
  header('Location:../envios/electronicos/index.php');
}
?>
<html>

<head>
  <title>Procesos</title>
  <?php include_once "$ruta_raiz/htmlheader.inc.php"; ?>
</head>

<body>
  <div class="container-fluid py-4">
    <h2 class="mb-4">
      Envío de Correspondencia <br>
      <!-- <small class="text-muted"><?= $tituloCrear ?></small> -->
    </h2>

    <div id="app">
      <Transition name="slide-fade">
        <div v-if="showForm" class="row g-4">
          <!-- === SECCIÓN: Envíos === -->
          <div class="col-12 col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm">
              <div class="card-header bg-orfeo text-white d-flex align-items-center">
                <i class="fa fa-envelope fa-2x me-2"></i>
                <h5 class="mb-0">Envíos</h5>
              </div>

              <div class="list-group list-group-flush">
                <a href='../envios/cuerpoEnvioNormal.php?<?= $datos_enviar ?>&estado_sal=3&estado_sal_max=3&krd=<?= $krd ?>&nomcarpeta=Radicados Para Envio'
                  class="list-group-item list-group-item-action">
                  <i class="fa fa-paperclip me-2"></i> Envíos Físicos
                </a>

                <a href="../envios/electronicos/index.php"
                  class="list-group-item list-group-item-action">
                  <i class="fa fa-inbox me-2"></i> Envíos Electrónicos
                </a>

                <a href='../envios/cuerpoModifEnvio.php?<?= $datos_enviar ?>&estado_sal=4&estado_sal_max=4&devolucion=3&krd=<?= $krd ?>'
                  class="list-group-item list-group-item-action">
                  <i class="fa fa-edit me-2"></i> Modificación Registro de Envío
                </a>

                <a href='../radsalida/cuerpo_masiva.php?<?= $datos_enviar ?>&krd=<?= $krd ?>&estado_sal=3&estado_sal_max=3'
                  class="list-group-item list-group-item-action">
                  <i class="fa fa-files-o me-2"></i> Envío Masivo
                </a>

                <a href='../radsalida/generar_envio.php?<?= $datos_enviar ?>&krd=<?= $krd ?>'
                  class="list-group-item list-group-item-action">
                  <i class="fa fa-list-alt me-2"></i> Generación de Planillas y Guías
                </a>

                <a href='../envios/uploadPlanos.php?<?= $datos_enviar ?>&krd=<?= $krd ?>'
                  class="list-group-item list-group-item-action">
                  <i class="fa fa-upload me-2"></i> Cargue Resultado Envío
                </a>
              </div>
            </div>
          </div>

          <!-- === SECCIÓN: Devoluciones === -->
          <div class="col-12 col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm">
              <div class="card-header bg-warning text-dark d-flex align-items-center">
                <i class="fa fa-undo fa-2x me-2"></i>
                <h5 class="mb-0">Devoluciones</h5>
              </div>

              <div class="list-group list-group-flush">
                <a href='../devolucion/dev_corresp.php?<?= $datos_enviar ?>&estado_sal=4&estado_sal_max=4&krd=<?= $krd ?>'
                  class="list-group-item list-group-item-action">
                  <i class="fa fa-clock-o me-2"></i> Por exceder tiempo de espera
                </a>

                <a href='../devolucion/cuerpoDevGestion.php?<?= $datos_enviar ?>&estado_sal=3&estado_sal_max=4&devolucion=1&krd=<?= $krd ?>'
                  class="list-group-item list-group-item-action">
                  <i class="fa fa-tasks me-2"></i> Devoluciones para gestión
                </a>
              </div>
            </div>
          </div>

          <!-- === SECCIÓN: Anulaciones === -->
          <div class="col-12 col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm">
              <div class="card-header bg-danger text-white d-flex align-items-center">
                <i class="fa fa-ban fa-2x me-2"></i>
                <h5 class="mb-0">Anulaciones</h5>
              </div>

              <div class="list-group list-group-flush">
                <a href='../anulacion/anularRadicados.php?<?= $datos_enviar ?>&estado_sal=4&tpAnulacion=2&krd=<?= $krd ?>'
                  class="list-group-item list-group-item-action">
                  <i class="fa fa-trash-o me-2"></i> Anular Radicados
                </a>
              </div>
            </div>
          </div>

          <!-- === SECCIÓN: Reportes === -->
          <div class="col-12 col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm">
              <div class="card-header bg-info text-white d-flex align-items-center">
                <i class="fa fa-bar-chart-o fa-2x me-2"></i>
                <h5 class="mb-0">Reportes</h5>
              </div>

              <div class="list-group list-group-flush">
                <a href='../reportes/generar_estadisticas_envio.php?<?= $datos_enviar ?>&estado_sal=4&estado_sal_max=4&krd=<?= $krd ?>'
                  class="list-group-item list-group-item-action">
                  <i class="fa fa-envelope me-2"></i> Envío de Correo
                </a>

                <a href='../reportes/generar_estadisticas.php?<?= $datos_enviar ?>&estado_sal=4&estado_sal_max=4&krd=<?= $krd ?>'
                  class="list-group-item list-group-item-action">
                  <i class="fa fa-reply me-2"></i> Devoluciones
                </a>

                <a href='../anulacion/cuerpo_RepAnula.php?<?= $datos_enviar ?>&estado_sal=4&tpAnulacion=2&krd=<?= $krd ?>'
                  class="list-group-item list-group-item-action">
                  <i class="fa fa-ban me-2"></i> Anulaciones
                </a>

                <?php if (strtoupper($entidad) == 'correlibre') { ?>
                  <a href='../reportes/generar_listado_entrega.php?<?= $datos_enviar ?>&estado_sal=4&krd=<?= $krd ?>'
                    class="list-group-item list-group-item-action">
                    <i class="fa fa-list me-2"></i> Listado Entrega
                  </a>
                <?php } ?>
              </div>
            </div>
          </div>
        </div>
      </Transition>
    </div>
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