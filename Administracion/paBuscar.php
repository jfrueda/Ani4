<?php

/** * @module paBuscar
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
?>
<div class="container-fluid mt-3">
  <section id="widget-grid">
    <div class="row">
      <article class="col-12">
        <div class="card shadow-sm border-secondary">
          <div class="card-header bg-orfeo text-white">
            <h5 class="mb-0">
              <i class="fa fa-search me-2"></i>Buscar
              <br>
              <small class="text-light fw-light"><?= $tituloCrear ?></small>
            </h5>
          </div>

          <div class="card-body bg-light">
            <form name="form_busq_rad" action='<?= $pagina_actual ?>?<?= session_name() . "=" . session_id() . "&krd=$krd" ?>&estado_sal=<?= $estado_sal ?>&tpAnulacion=<?= $tpAnulacion ?>&estado_sal_max=<?= $estado_sal_max ?>&pagina_sig=<?= $pagina_sig ?>&dep_sel=<?= $dep_sel ?>&nomcarpeta=<?= $nomcarpeta ?>' method="post">

              <div class="row align-items-end">
                <div class="col-md-10 mb-3 mb-md-0">
                  <label for="busqRadicados" class="form-label fw-bold text-secondary">
                    Buscar por nombres de usuario y/o login <span class="badge bg-info text-dark">Separados por coma</span>
                  </label>

                  <div class="input-group">
                    <span class="input-group-text bg-white">
                      <i class="fa fa-user text-muted"></i>
                    </span>
                    <input name="busqRadicados" id="busqRadicados" type="text" class="form-control form-control-lg" placeholder="Ej: jsmith, Juan Perez..." value="<?= $busqRadicados ?>">
                    <input type="submit" value='Buscar' name="Buscar" valign='middle' class='btn btn-primary px-4'>
                  </div>
                </div>
              </div>

              <?php
              if ($busqRadicados) {
                $busqRadicados = trim($busqRadicados);
                $textElements = explode(",", $busqRadicados);
                $newText = "";
                $i = 0;
                $busq_radicados_tmp = ""; // Inicialización por seguridad

                foreach ($textElements as $item) {
                  $item = trim($item);
                  if ($item) {
                    if ($i != 0) $busq_and = " or ";
                    else $busq_and = "  ";

                    $busq_radicados_tmp .= " $busq_and upper($varBuscada) like upper('%$item%') ";
                    if ($varBuscada2) {
                      $busq_radicados_tmp .= " or upper($varBuscada2) like upper('%$item%') ";
                    }
                    $i++;
                  }
                }
                $dependencia_busq2 .= " and ($busq_radicados_tmp) ";
              }
              ?>
            </form>
          </div>
        </div>
      </article>
    </div>
  </section>
</div>