<?php

/**
 * @module crearUsuario
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

$ruta_raiz = "../..";
if (!$_SESSION['dependencia'])
  header("Location: $ruta_raiz/cerrar_session.php");
include_once "$ruta_raiz/include/db/ConnectionHandler.php";

$db = new ConnectionHandler($ruta_raiz);
$db->conn->SetFetchMode(ADODB_FETCH_ASSOC);
$nomcarpetaOLD = $nomcarpeta;

if (!$carpeta) {
  $carpeta = "0";
  $nomcarpeta = "Entrada";
}
?>
<div class="container-fluid my-3">
  <section id="widget-grid">
    <div class="row">
      <article class="col-12">
        <div class="card shadow-sm border-secondary">
          <div class="card-header bg-orfeo text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
              Administración de usuarios y perfiles<br>
              <small class="text-light fw-light"><?= $tituloCrear ?></small>
            </h5>
          </div>
          <div class="card-body p-0">
            <div class="row g-0 border-bottom">
              <div class="col-md-4 border-end p-3">
                <label class="text-muted fw-bold small text-uppercase d-block">Listado de:</label>
                <span class="text-primary fw-semibold"><?= $nomcarpeta ?></span>
              </div>

              <div class="col-md-4 border-end p-3">
                <label class="text-muted fw-bold small text-uppercase d-block">Usuario</label>
                <span class="text-dark fw-semibold"><?= $usua_nomb ?></span>
              </div>

              <div class="col-md-4 p-3">
                <label class="text-muted fw-bold small text-uppercase d-block">Dependencia</label>
                <?php if (!$swBusqDep) { ?>
                  <span class="text-dark fw-semibold"><?= $depe_nomb ?></span>
                <?php } else { ?>
                  <form name="formboton" action='<?= $pagina_actual ?>?<?= session_name() . "=" . session_id() . "&krd=$krd" ?>&estado_sal=<?= $estado_sal ?>&estado_sal_max=<?= $estado_sal_max ?>&pagina_sig=<?= $pagina_sig ?>&dep_sel=<?= $dep_sel ?>&nomcarpeta=<?= $nomcarpeta ?>' method="get">
                    <input type='hidden' name='<?= session_name() ?>' value='<?= session_id() ?>'>
                    <div class="d-grid gap-2">
                      <?php
                      include_once "$ruta_raiz/include/query/envios/queryPaencabeza.php";
                      $sqlConcat = $db->conn->Concat($conversion, "'-'", $db->conn->substr . "(depe_nomb,1,30) ");
                      $sql = "select $sqlConcat ,depe_codi from dependencia where depe_estado=1 order by depe_codi";
                      $rsDep = $db->conn->Execute($sql);
                      if (!$depeBuscada) $depeBuscada = $dependencia;

                      // Renderizado del select con clases de BS5
                      print $rsDep->GetMenu2("dep_sel", "$dep_sel", false, false, 0, " onChange='submit();' class='form-select form-select-sm'");

                      if ($perm_trd) {
                        $sql_series = "select distinct concat(s.sgd_srd_codigo,' - ',s.sgd_srd_descrip),s.sgd_srd_codigo  from sgd_mrd_matrird m left join sgd_srd_seriesrd s on (m.sgd_srd_codigo=s.sgd_srd_codigo) where  m.sgd_mrd_esta='1'";
                        $rs_series = $db->conn->Execute($sql_series);
                        print $rs_series->GetMenu2("srd_sel", "$srd_sel", false, false, 0, " onChange='submit();' class='form-select form-select-sm mt-2'");
                      }
                      ?>
                    </div>
                  </form>
                <?php } ?>
              </div>
            </div>
          </div>
        </div>
      </article>
    </div>
  </section>
</div>