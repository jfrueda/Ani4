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
if (!$_SESSION['dependencia'])
  header("Location: $ruta_raiz/cerrar_session.php");

$lkGenerico = "&usuario=$krd&nsesion=" . trim(session_id()) . "&nro=$verradicado" . "$datos_envio";

$sqlremitente = "select SGD_DIR_NOMBRE, SGD_DIR_NOMREMDES from SGD_DIR_DRECCIONES t where t.radi_nume_radi = '$numrad'";
$rsRemitente = $db->conn->Execute($sqlremitente);
$SGD_DIR_NOMBRE = $rsRemitente->fields['SGD_DIR_NOMREMDES'];
$isqlDepR = "SELECT RADI_DEPE_ACTU,RADI_USUA_ACTU, RADI_DATO_001, RADI_DATO_002 from radicado	WHERE RADI_NUME_RADI = '$numrad'";
$rsDepR = $db->conn->Execute($isqlDepR);
$coddepe = $rsDepR->fields['RADI_DEPE_ACTU'];
$codusua = $rsDepR->fields['RADI_USUA_ACTU'];
$radi_dato_001 = $rsDepR->fields['RADI_DATO_001'];
$radi_dato_002 = $rsDepR->fields['RADI_DATO_002'];
$ind_ProcAnex = "N";

$sqlFirmador = "SELECT usua_nomb FROM usuario
where usua_codi= (select radi_usua_firma from radicado where radi_nume_radi = $numrad)
  and depe_codi = (select radi_depe_firma from radicado where radi_nume_radi = $numrad)";
$rsFirmador = $db->conn->Execute($sqlFirmador);

if ($rsFirmador && !$rsFirmador->EOF) {
  $usuaFirmador = $rsFirmador->fields['USUA_NOMB'];
} else {
  $usuaFirmador = "No definidio";
}

?>

<script>
  function regresar() { //window.history.go(0);
    window.location.reload();
    //window.location.href='<a href="#" ></a>'
  }

  function CambiarE(est, numeroExpediente) {
    window.open("<?= $ruta_raiz ?>/archivo/cambiar.php?<?= session_name() ?>=<?= session_id() ?>&numRad=<?= $verrad ?>&expediente=" + numeroExpediente + "&est=" + est + "&", "Cambio Estado Expediente", "height=100,width=100,scrollbars=yes");
  }

  function modFlujo(numeroExpediente, texp, codigoFldExp) {
    window.open("<?= $ruta_raiz ?>/flujo/modFlujoExp.php?<?= session_name() ?>=<?= session_id() ?>&codigoFldExp=" + codigoFldExp + "&numRad=<?= $verrad ?>&texp=" + texp + "&ind_ProcAnex=<?= $ind_ProcAnex ?>&codusua=<?= $codusua ?>", "TexpE<?= $fechaH ?>", "height=250,width=750,scrollbars=yes");
  }

  function verVinculoDocto() {
    window.open("./vinculacion/mod_vinculacion.php?verrad=<?= $verrad ?>&codusuario=<?= $codusuario ?>&dependencia=<?= $dependencia ?>", "Vinculacion_Documento", "height=500,width=750,scrollbars=yes");
  }

  function update_cExp() {
    $.post("<?= $ruta_raiz ?>/include/tx/comiteExpertos.php", {
      numRad: <?= $numrad ?>
    });
  }

  function cambiarFechaVencimiento() {
    window.open("<?= $ruta_raiz ?>/tx/cambiarFechaVencimiento.php?<?= session_name() ?>=<?= session_id() ?>&radi_fech_vcmto=<?= $radi_fech_vcmto ?>&numRad=<?= $verrad ?>&codusua=<?= $codusua ?>", "TexpE<?= $fechaH ?>", "height=250,width=750,scrollbars=yes");
  }

  function insertarHistorico(radicado, comentario, tx) {
    $.ajax({
      type: 'POST',
      url: '<?= $ruta_raiz ?>/tx/insertarHistorico.php',
      data: {
        numrad: radicado,
        tx_comentario: comentario,
        tx_codigo: tx
      },
      success: function(data, status) {
        console.log(data);
      },
      error: function(xhr, textStatus, errorThrown) {
        console.log('Error: ' + errorThrown);
      }
    });
  }

  $(document).ready(function() {
    $('.abrirVisorg').off('click').on('click', function() {
      $("#visor").dialog();
      try {
        var rad = '<?= $verrad ?>';
        insertarHistorico(rad, `Ver imagen principal`, 110);
      } catch (error) {
        console.log('Error: ' + error.message);
      }
    });

    $('.cerrarVisorg').off('click').on('click', function() {
      $("#visor").dialog('destroy');
    });
  });
</script>

<body>

  <div class="card shadow border-0 mb-4">
    <div class="card-header bg-primary text-white py-3">
      <h6 class="fw-bold mb-0">Información del Radicado</h6>
    </div>
    
    <div class="card-body p-3">
      <div class="table-responsive">
        <table class="table table-sm align-middle">

          <!-- ========================== FILA 1 =========================== -->
          <tr class="border-bottom">
            <td class="fw-semibold text-secondary"><small>Asunto</small></td>
            <td><small><?= $ra_asun ?></small></td>

            <td class="fw-semibold text-secondary"><small>Fecha</small></td>
            <td><small><?= $radi_fech_radi ?></small></td>

            <td class="fw-semibold text-secondary"><small>Fecha Vencimiento</small></td>
            <td>
              <small><?= $radi_fech_vcmto ?></small>
              <?php if (isset($_SESSION["fecha_vencimiento"]) && $_SESSION["fecha_vencimiento"] == 2): ?>
                <input type="button" name="CambiarFechaV"
                  value="..." title="Cambiar fecha vencimiento"
                  class="btn btn-primary btn-xs ms-1"
                  onclick="cambiarFechaVencimiento();">
              <?php endif; ?>
            </td>
          </tr>

          <!-- ========================== FILA 2 =========================== -->
          <tr class="border-bottom">
            <td class="fw-semibold text-secondary"><small>Folios</small></td>
            <td><small><?= $radi_nume_folio ?>/<?= $radi_nume_hoja ?></small></td>

            <td class="fw-semibold text-secondary"><small>Anexos</small></td>
            <td><small><?= $radi_nume_anexo ?></small></td>

            <td></td>
            <td></td>
          </tr>

          <!-- ========================== FILA 3 =========================== -->
          <tr class="border-bottom">
            <td class="fw-semibold text-secondary"><small>Descripción Anexos</small></td>
            <td><small><?= $radi_desc_anex ?></small></td>

            <td class="fw-semibold text-secondary"><small>Anexo / Asociado</small></td>
            <td>
              <small>
                <?php
                if ($radi_tipo_deri != 1 and $radi_nume_deri) {
                  echo $radi_nume_deri;
                  $resulVali = $verLinkArchivo->valPermisoRadi($radi_nume_deri);
                  $verImg = $resulVali['verImg'];

                  if ($verImg == "SI") {
                    echo "<br>(<a class='vinculos' 
                                            href='$ruta_raiz/verradicado.php?verrad=$radi_nume_deri &session_name()=session_id()' 
                                            target='VERRAD$radi_nume_deri_" . date("Ymdhi") . "'>Ver Datos</a>)";
                  } else {
                    echo "<br>(<a class='vinculos' href='javascript:noPermiso()'>Ver Datos</a>)";
                  }
                }

                if (($verradPermisos == "Full" and $coddepe != '999') or $datoVer == "985"):
                ?>
                  <input type="button" name="mostrar_anexo" value="..."
                    title="Mostrar anexo"
                    class="btn btn-primary btn-xs ms-1"
                    onclick="verVinculoDocto();">
                <?php endif; ?>
              </small>
            </td>

            <td class="fw-semibold text-secondary"><small>Referencia / Oficio</small></td>
            <td><small><?= $cuentai ?></small></td>
          </tr>

          <!-- ========================== FACTURACIÓN SUI =========================== -->
          <?php
          $muniCodiFac = "";
          $dptoCodiFac = "";

          if ($sector_grb == 6 and $cuentai and $espcodi) {
            if ($muni_us2 && $codep_us2) {
              $muniCodiFac = $muni_us2;
              $dptoCodiFac = $codep_us2;
            } elseif ($muni_us1 && $codep_us1) {
              $muniCodiFac = $muni_us1;
              $dptoCodiFac = $codep_us1;
            }

            echo "<tr class='border-bottom'>
                            <td colspan='6'>
                                <a class='vinculos' 
                                target='FacSUI$cuentai'
                                href='./consultaSUI/facturacionSUI.php?cuentai=$cuentai&
                                        muniCodi=$muniCodiFac&deptoCodi=$dptoCodiFac&espCodi=$espcodi'>
                                Ver Facturación
                                </a>
                            </td>
                        </tr>";
          }

          //Reescribir $imagenv para que el pdf abra en el visor modal
          if (!empty($radi_path)) {
            $extension = explode('.', $radi_path);
            if ($extension[1] == 'pdf') {
              if (strpos($radi_path, "/") != 0) {
                $radi_path = "/" . $radi_path;
              }
              $linkImagen = "$ruta_raiz/bodega" . $radi_path;
              $imagenv = "<a 'vinculos' href='javascript:void(0)' class='abrirVisorg'>Ver Imagen</a>";
            }
          }
          ?>

          <!-- ========================== FILA 4 =========================== -->
          <tr class="border-bottom">
            <td class="fw-semibold text-secondary"><small>Imagen</small></td>
            <td><small><?= $imagenv ?></small></td>

            <td class="fw-semibold text-secondary"><small>Flujos</small></td>
            <td>
              <small><?= $descFldExp ?></small>
              <?php if ($verradPermisos == "Full" or $datoVer == "985"): ?>
                <input type="button" value="..."
                  class="btn btn-primary btn-xs ms-2"
                  onclick="modFlujo('<?= $numExpediente ?>', <?= $texp ?>, <?= $codigoFldExp ?>)">
              <?php endif; ?>
            </td>

            <td class="fw-semibold text-secondary"><small>Nivel de Seguridad</small></td>
            <td>
              <small>
                <?php
                if ($nivelRad == 0) echo "Público";
                elseif ($nivelRad == 1) echo "Reservado: Solo la dependencia";
                elseif ($nivelRad == 2) echo "Clasificado: Usuario proyectó, Jefe y actual.";

                if (!($dependencias_clasificadas_trigger == 'true' &&
                  in_array($coddepe, explode(',', $dependencias_clasificadas)))):

                  if (($verradPermisos == "Full" && $coddepe != '999')):
                    $varEnvio = "&numRad=$verrad&nivelRad=$nivelRad";
                ?>
                    <input type="button" value="..."
                      title="Cambiar nivel"
                      class="btn btn-primary btn-xs ms-2"
                      onclick="window.open('<?= $ruta_raiz ?>/seguridad/radicado.php?<?= $varEnvio ?>',
                              'NivelSeguridad', 'height=270,width=600,left=350,top=300');">
                <?php endif;
                endif; ?>
              </small>
            </td>
          </tr>

          <!-- ========================== FILA 5 - TRD =========================== -->
          <tr class="border-bottom">
            <td class="fw-semibold text-secondary"><small>Clasificación Documental</small></td>
            <td>
              <?php
              if (!$codserie) $codserie = "0";
              if (!$tsub) $tsub = "0";
              if (trim($val_tpdoc_grbTRD) == "///") $val_tpdoc_grbTRD = "";
              ?>

              <div class="p-2 rounded bg-light border">
                <small>
                  <?= $serie_nombre ?><br>
                  <?= $subserie_nombre ?><br>
                  <?= $tpdoc_nombreTRD ?>
                </small>
              </div>

              <?php if (($verradPermisos == "Full" && $coddepe != '999') or $datoVer == "985" or $dependencia == '999'): ?>
                <input type="button" value="..."
                  title="Asignar TRD"
                  class="btn btn-primary btn-xs mt-2"
                  onclick="ver_tipodocuTRD(<?= $codserie ?>, <?= $tsub ?>);">
              <?php endif; ?>
            </td>

            <?php
            $termino = $db->conn->Execute(
              "select SGD_TPR_TERMINO 
                        from sgd_tpr_tpdcumento tp, radicado r 
                        where tp.SGD_TPR_CODIGO=r.TDOC_CODI 
                        and r.radi_nume_radi=$verrad"
            )->fields["SGD_TPR_TERMINO"];
            ?>

            <td class="fw-semibold text-secondary"><small>Término</small></td>
            <td><small><?= $termino ?></small></td>

            <?php if (!empty($esNotificacion)): ?>
              <td></td>
              <td></td>
            <?php else: ?>
              <td class="fw-semibold text-secondary"><small>Medio Envío</small></td>
              <td><small><?= $medio_recepcion ?></small></td>
            <?php endif; ?>
          </tr>

          <!-- ========================== NOTIFICACIÓN (si aplica) =========================== -->
          <?php if (!empty($esNotificacion)): ?>
            <?=
            $opacidad_citacion = 0.4;
            $opacidad_notificacion = 0.4;
            $opacidad_comunicacion = 0.4;
            $opacidad_publicacion = 0.4;
            if ($esNotificacionCircular) {
              $opacidad_publicacion = 1;
            } else {
              foreach ($ordenesNotificacion as $dir_codigo => $orden_codigo) {
                foreach ($orden_codigo as $orden) {
                  switch ($orden) {
                    case "1":
                      $opacidad_citacion = 1;
                      break;
                    case "2":
                      $opacidad_notificacion = 1;
                      break;
                    case "3":
                      $opacidad_comunicacion = 1;
                      break;
                    case "4":
                      $opacidad_publicacion = 1;
                      break;
                  }
                }
                if (
                  $opacidad_citacion == 1 && $opacidad_notificacion == 1 &&
                  $opacidad_comunicacion == 1 && $opacidad_publicacion == 1
                ) {
                  break;
                }
              }
            }
            ?>
            <tr>
              <td class="tdprincipal"><small><b>Medio de Publicaci&oacute;n</b></small></td>
              <td><small><?= $medio_pub_desc ?></small></td>

              <? if ($esNotificacionCircular) { ?>
                <td class="tdprincipal" rowspan="4"><small><b></b></small></td>
                <td style="opacity:<?= $opacidad_citacion ?>"><small><b></b></small></td>
              <? } else { ?>
                <td class="tdprincipal" rowspan="4"><small><b>Orden Acto Administrativo</b></small></td>
                <td style="opacity:<?= $opacidad_citacion ?>"><small><b>Cita</b></small></td>
              <? } ?>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td class="tdprincipal"><small><b>Caracter Administrativo</b></small></td>
              <td><small><?= $caracter_adtvo_desc  ?></small></td>
              <!--td class="tdprincipal"></td-->
              <? if ($esNotificacionCircular) { ?>
                <td style="opacity:<?= $opacidad_notificacion ?>"><small><b></b></small></td>
              <? } else { ?>
                <td style="opacity:<?= $opacidad_notificacion ?>"><small><b>Notifica</b></small></td>
              <? } ?>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td class="tdprincipal"><small><b>SIAD</b></small></td>
              <td><small><?= $siad_preestablecido ?></small></td>
              <!--td class="tdprincipal"></td-->
              <? if ($esNotificacionCircular) { ?>
                <td style="opacity:<?= $opacidad_comunicacion ?>"><small><b></b></small></td>
              <? } else { ?>
                <td style="opacity:<?= $opacidad_comunicacion ?>"><small><b>Comunica</b></small></td>
              <? } ?>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td class="tdprincipal"><small><b>Prioridad </b></small></td>
              <td><small><?= $prioridad_prestablecido ?></small></td>
              <!--td class="tdprincipal"></td-->
              <? if ($esNotificacionCircular) { ?>
                <td style="opacity:<?= $opacidad_publicacion ?>"><small><b></b></small></td>
              <? } else { ?>
                <td style="opacity:<?= $opacidad_publicacion ?>"><small><b>Publica</b></small></td>
              <? } ?>
              <td></td>
              <td></td>
            </tr>

          <?php endif; ?>

          <?php if ($esNotificacion): ?>
            <tr>
              <td class="fw-semibold text-secondary"><small>Funcionario designado para firma</small></td>
              <td><small><?= $usuaFirmador ?></small></td>
              <td></td>
              <td colspan="3"></td>
            </tr>
          <?php endif; ?>

        </table>
      </div>
    </div>
  </div>

  <div class="container-fluid my-4">
    <div class="card shadow-sm border-0">
      <div class="card-header bg-primary text-white py-3">
        <h5 class="mb-0">Direcciones Asociadas</h5>
      </div>

      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th>Nombre</th>
                <th>Persona</th>
                <th>Dirección</th>
                <th>Ciudad / Departamento</th>
                <th>E-mail</th>
                <th>Teléfono</th>

                <?php if ($ent == RESOLUCION || $ent == AUTO) { ?>
                  <th class="text-center">Cita</th>
                  <th class="text-center">Notifica</th>
                  <th class="text-center">Comunica</th>
                  <th class="text-center">Publica</th>
                  <th class="text-center">Medio de Envío</th>
                <?php } ?>
              </tr>
            </thead>
            <tbody>

              <?php
              $isql = "select a.* from sgd_dir_drecciones a where a.RADI_NUME_RADI=$verrad";
              $rs = $db->query($isql);

              include_once "$ruta_raiz/jh_class/funciones_sgd.php";

              while (!$rs->EOF) {

                $nombres       = $rs->fields["SGD_DIR_NOMREMDES"];
                $nombre        = $rs->fields["SGD_DIR_NOMBRE"];
                $dirD          = $rs->fields["SGD_DIR_DIRECCION"];
                $dirMail       = $rs->fields["SGD_DIR_MAIL"];
                $dirTelefono   = $rs->fields["SGD_DIR_TELEFONO"];
                $dptoCodigo    = $rs->fields["DPTO_CODI"];
                $muniCodigo    = $rs->fields["MUNI_CODI"];
                $idPais        = $rs->fields["ID_PAIS"];

                $a = new LOCALIZACION($idPais . "-" . $dptoCodigo, $muniCodigo, $db);

                $dpto_nombre  = $a->departamento;
                $muni_nombre  = $a->municipio;

                // Lógica notificaciones
                if ($ent == RESOLUCION || $ent == AUTO) {
                  include_once("$ruta_raiz/include/tx/notificacion.php");
                  $notificacion = new Notificacion($db);

                  $dir_codigo = $rs->fields["SGD_DIR_CODIGO"];
                  $medio_envio_codi = $rs->fields["MEDIO_ENVIO"];
                  $medio_envio_desc = $notificacion->obtenerMedioEnvio($medio_envio_codi);

                  $disable_citacion     = "disabled";
                  $disable_notificacion = "disabled";
                  $disable_comunicacion = "disabled";
                  $disable_publicacion  = "disabled";

                  foreach ($ordenesNotificacion[$dir_codigo] as $orden_codigo) {
                    switch ($orden_codigo) {
                      case "1":
                        $disable_citacion = "";
                        break;
                      case "2":
                        $disable_notificacion = "";
                        break;
                      case "3":
                        $disable_comunicacion = "";
                        break;
                      case "4":
                        $disable_publicacion = "";
                        break;
                    }
                  }
                }
              ?>

                <tr>
                  <td><?= $nombre ?></td>
                  <td><?= $nombres ?></td>
                  <td><?= $dirD ?></td>
                  <td><?= $muni_nombre ?>/<?= $dpto_nombre ?></td>
                  <td><?= $dirMail ?></td>
                  <td><?= $dirTelefono ?></td>

                  <?php if ($ent == RESOLUCION || $ent == AUTO) { ?>
                    <td class="text-center"><input type="checkbox" <?= $disable_citacion ?>></td>
                    <td class="text-center"><input type="checkbox" <?= $disable_notificacion ?>></td>
                    <td class="text-center"><input type="checkbox" <?= $disable_comunicacion ?>></td>
                    <td class="text-center"><input type="checkbox" <?= $disable_publicacion ?>></td>
                    <td class="text-center"><?= $medio_envio_desc ?></td>
                  <?php } ?>
                </tr>

              <?php
                $rs->MoveNext();
              }
              ?>

              <!-- Última fila -->
              <tr class="table-light">
                <td><?= $nombret_us3 ?> — <?= $cc_documento_us3 ?></td>
                <td><?= $direccion_us3 ?></td>
                <td><?= $dpto_nombre_us3 ?>/<?= $muni_nombre_us3 ?></td>
                <td><?= $email["x3"] ?></td>
                <td><?= $telefono["x3"] ?></td>
                <td></td>

                <?php if ($ent == RESOLUCION || $ent == AUTO) { ?>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                <?php } ?>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <table width="150" class="table table-bordered ">
    <?php
    if (trim($radi_dato_001)) {
    ?>
      <tr>
        <th class='alert-info'>Apoderado </th>
        <td><?= $radi_dato_001 ?></td>
      </tr>
    <?php
    }
    if (trim($radi_dato_002)) {
    ?>
      <tr>
        <th class='alert-info'>Demandante</th>
        <td><?= $radi_dato_002 ?></td>
      </tr>
    <?php
    }
    ?>
  </table>

  <div id='visor' style='display:none; 
                          position:fixed;
                          padding:26px 30px 30px;
                          top:0;
                          left:0;
                          right:0;
                          bottom:0;
                          z-index:2'>
    <button class='cerrarVisorg' type='button' style='float:right; background-color:red;'><b>x</b></button>
    <iframe style='width:100%; height:100%; z-index:-2;' src=<?= $linkImagen ?>></iframe>
  </div>