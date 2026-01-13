<form name="formaTRD" action="upload2PorExcel.php?<?= $paramsTRD ?>" method="post">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-12">
        <div class="card shadow-sm ">
          <div class="card-header bg-orfeo text-white">
            <h4 class="fw-bold pb-2">
              Aplicación de la TRD
            </h4>
          </div>

          <div class="card shadow-sm border-0">
            <div class="card-body">
              <?
              $paramsTRD = $phpsession . "&krd=$krd&codiTRD=$codiTRD&tsub=$tsub&codserie=$codserie&tipo=$tipo&dependencia=$dependencia&depe_codi_territorial=$depe_codi_territorial&usua_nomb=$usua_nomb&"
                . "depe_nomb=$depe_nomb&usua_doc=$usua_doc&codusuario=$codusuario";
              ?>

              <!-- TABLA PRINCIPAL -->
              <div class="table-responsive">
                <table class="table table-bordered align-middle mb-4">
                  <tbody>
                    <!-- SERIE -->
                    <tr>
                      <th class="bg-light text-center w-25">
                        SERIE
                      </th>
                      <td>
                        <div class="form-floating">
                          <?
                          $coddepe = $_SESSION['dependencia'];

                          if ($codserie != 0 and $tipo != 0 and $tsub != 0) {
                            $queryTRD = "select SGD_MRD_CODIGO AS CLASETRD from sgd_mrd_matrird m
                                        where
                                        (cast(m.depe_codi as varchar(100)) = '$coddepe' or cast(m.depe_codi_aplica as varchar(100)) like '%$coddepe%') 
                                        and m.sgd_srd_codigo = '$codserie' and m.sgd_sbrd_codigo = '$tsub' and m.sgd_tpr_codigo = '$tipo'";

                            $rsTRD = $db->conn->query($queryTRD);
                            if ($rsTRD) {
                              $codiTRD = $rsTRD->fields['CLASETRD'];
                            }
                          }

                          if ($coddepe != 0 && $tipo != 0 && $tsub != 0)
                            if (!$tipo) $tipo = 0;
                          if (!$codserie) $codserie = 0;
                          if (!$tsub) $tsub = 0;

                          $fechah = date("dmy") . " " . time("h_m_s");
                          $fecha_hoy = Date("Y-m-d");
                          $sqlFechaHoy = $db->conn->DBDate($fecha_hoy);
                          $num_car = 4;
                          $nomb_varc = "s.sgd_srd_codigo";
                          $nomb_varde = "s.sgd_srd_descrip";
                          include "$ruta_raiz/include/query/trd/queryCodiDetalle.php";

                          $querySerie = "select
                                            distinct ($sqlConcat) as detalle
                                            , s.sgd_srd_codigo
                                        from
                                            sgd_mrd_matrird m
                                            , sgd_srd_seriesrd s
                                        where
                                            (cast(m.depe_codi as varchar(100)) = '$coddepe' or cast(m.depe_codi_aplica as varchar(100)) like '%$coddepe%') 
                                            and s.sgd_srd_codigo = m.sgd_srd_codigo
                                            and " . $sqlFechaHoy . " between s.sgd_srd_fechini and s.sgd_srd_fechfin
                                        order by detalle";

                          $rsD           = $db->conn->query($querySerie);
                          $comentarioDev = "Muestra las Series Docuementales";

                          include "$ruta_raiz/include/tx/ComentarioTx.php";

                          print $rsD->GetMenu2(
                            "codserie",
                            $codserie,
                            "0:-- Seleccione --",
                            false,
                            "",
                            "onChange='submit()' class='form-select'"
                          );
                          ?>
                          <label>Seleccione la serie documental</label>
                        </div>
                      </td>
                    </tr>

                    <!-- SUBSERIE -->
                    <tr>
                      <th class="bg-light text-center">
                        SUBSERIE
                      </th>
                      <td>
                        <div class="form-floating">
                          <?php

                          $nomb_varc  = "su.sgd_sbrd_codigo";
                          $nomb_varde = "su.sgd_sbrd_descrip";

                          include("$ruta_raiz/include/query/trd/queryCodiDetalle.php");

                          $querySub = "select distinct ($sqlConcat) as detalle, su.sgd_sbrd_codigo
                                      from sgd_mrd_matrird m, sgd_sbrd_subserierd su
                                      where (cast(m.depe_codi as varchar(100)) = '$coddepe' or cast(m.depe_codi_aplica as varchar(100)) like '%$coddepe%') 
                                          and m.sgd_srd_codigo = '$codserie'
                                          and su.sgd_srd_codigo = '$codserie'
                                          and su.sgd_sbrd_codigo = m.sgd_sbrd_codigo
                                          and " . $sqlFechaHoy . " between su.sgd_sbrd_fechini and su.sgd_sbrd_fechfin
                                      order by detalle";

                          $rsSub = $db->conn->query($querySub);

                          while (!$rsSub->EOF) {
                            $nombre = utf8_decode($rsSub->fields['DETALLE']);
                            $codigo = $rsSub->fields['SGD_SBRD_CODIGO'];
                            if ($codigo  == $tsub) {
                              $options .= "<option value='$codigo' selected >$nombre</option>";
                            } else {
                              $options .= "<option value='$codigo'>$nombre</option>";
                            }
                            $rsSub->MoveNext();
                          }

                          echo "<select name='tsub' onChange='submit()' class='form-select'>
                                    <option value='0'>-- Seleccione --</option>
                                    $options
                                </select>";
                          ?>
                          <label>Seleccione la subserie</label>
                        </div>
                      </td>
                    </tr>

                    <!-- TIPO DOCUMENTO -->
                    <tr>
                      <th class="bg-light text-center">
                        TIPO DE DOCUMENTO
                      </th>
                      <td>
                        <div class="form-floating">
                          <?php

                          $ent = 1;
                          $nomb_varc = "t.sgd_tpr_codigo";
                          $nomb_varde = "t.sgd_tpr_descrip";

                          include "$ruta_raiz/include/query/trd/queryCodiDetalle.php";

                          $queryTip = "select distinct ($sqlConcat) as detalle, t.sgd_tpr_codigo
                                          from sgd_mrd_matrird m, sgd_tpr_tpdcumento t
                                          where (cast(m.depe_codi as varchar(100)) = '$coddepe' or cast(m.depe_codi_aplica as varchar(100)) like '%$coddepe%') 
                                              and m.sgd_srd_codigo  = '$codserie'
                                              and m.sgd_sbrd_codigo = '$tsub'
                                              and t.sgd_tpr_codigo = m.sgd_tpr_codigo
                                              and t.sgd_tpr_estado = 1
                                              and t.sgd_tpr_tp$ent='1'
                                          order by detalle";

                          $rsTip = $db->conn->query($queryTip);
                          include "$ruta_raiz/include/tx/ComentarioTx.php";

                          print $rsTip->GetMenu2(
                            "tipo",
                            $tipo,
                            "0:-- Seleccione --",
                            false,
                            "",
                            "onChange='submit()' class='form-select'"
                          );
                          ?>
                          <label>Tipo de documento</label>
                        </div>
                      </td>
                    </tr>

                    <!-- TIPO RADICACIÓN -->
                    <tr>
                      <th class="bg-light text-center">
                        TIPO DE RADICACIÓN
                      </th>
                      <td>
                        <div class="form-floating">
                          <?php

                          $sql = "SELECT SGD_TRAD_CODIGO,SGD_TRAD_DESCR FROM SGD_TRAD_TIPORAD WHERE SGD_TRAD_CODIGO <> 2";

                          $rtiprad = $db->conn->query($sql);

                          while (!$rtiprad->EOF) {
                            $ercodigo  = utf8_decode($rtiprad->fields['SGD_TRAD_CODIGO']);
                            $nombre    = $rtiprad->fields['SGD_TRAD_DESCR'];

                            if ($ercodigo  == $tipoRad && $ercodigo < 2) {
                              $options2 .= "<option value='$ercodigo' selected >$nombre</option>";
                            } else {
                              if ($ercodigo < 2)
                                $options2 .= "<option value='$ercodigo'>$nombre</option>";
                            }
                            $rtiprad->MoveNext();
                          }

                          echo "<select name='tipoRad' id='Slc_Trd' onChange='submit()' class='form-select'>
                                    <option value='0'>-- Seleccione --</option>
                                    $options2
                                </select>";
                          ?>
                          <label>Tipo de radicación</label>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>

              <?php

              $queryProc = "select SGD_PEXP_DESCRIP,SGD_PEXP_CODIGO
                              from SGD_PEXP_PROCEXPEDIENTES
                              WHERE SGD_SRD_CODIGO=$codserie
                              AND SGD_SBRD_CODIGO=$tsub";

              $rs = $db->conn->query($queryProc);
              $codTmpProc = $rs->fields["SGD_PEXP_CODIGO"];
              ?>

              <!-- PROCESOS -->
              <?php if ($codTmpProc) { ?>
                <div class="card border mt-4">
                  <div class="card-header bg-orfeo text-white fw-semibold">
                    Vincular a Proceso
                  </div>
                  <div class="card-body">
                    <div class="row g-3">
                      <div class="col-md-6">
                        <div class="form-floating">
                          <?php

                          echo "<label class='select'>";

                          print $rs->GetMenu2(
                            "codProceso",
                            $codProceso,
                            "0:-- Ningún Proceso --",
                            false,
                            "",
                            "class='form-select' onchange='submit();'"
                          );

                          echo "</label>";
                          ?>
                          <label>Proceso</label>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-floating">
                          <?php

                          include("$ruta_raiz/include/tx/Flujo.php");
                          $objFlujo = new Flujo($db, $codProceso, $usua_doc);

                          echo $objFlujo->getMenuProximaArista(
                            $tipo,
                            $codProceso,
                            $codserie,
                            $tsub,
                            $tipoRad,
                            'pNodo',
                            $pNodo,
                            "class='form-select' onChange='submit();'"
                          );
                          ?>
                          <label>Flujo siguiente</label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              <?php } ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</form>