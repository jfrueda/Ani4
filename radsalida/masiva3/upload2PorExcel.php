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
$ruta_raiz = "../..";
include_once $ruta_raiz . "/include/tx/sanitize.php";

foreach ($_GET  as $key => $val) {
  ${$key} = $val;
}
foreach ($_POST as $key => $val) {
  ${$key} = $val;
}

if (!isset($_SESSION['dependencia']))  include "$ruta_raiz/rec_session.php";

$krd                = $_SESSION["krd"];
$dependencia        = $_SESSION["dependencia"];
$usua_doc           = $_SESSION["usua_doc"];
$codusuario         = $_SESSION["codusuario"];

include_once "$ruta_raiz/include/db/ConnectionHandler.php";
include_once("$ruta_raiz/include/combos.php");

if (!$db) $db = new ConnectionHandler($ruta_raiz);
//if (!defined('ADODB_FETCH_ASSOC'))  define('ADODB_FETCH_ASSOC',2);
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
  switch ($ultimo) {
    // El modificador 'G' se encuentra disponible desde PHP 5.1.0
    case 'g':
      $val *= 1024;
      break;
    case 'm':
      $val *= 1024;
      break;
    case 'k':
      $val *= 1024;
      break;
  }
  return $val;
}

//Start::seleccion de plantillas
$sql21       = "SELECT
                ID,
                PLAN_PLANTILLA,
                PLAN_NOMBRE,
                PLAN_FECHA,
                DEPE_CODI,
                USUA_CODI,
                PLAN_TIPO
              FROM
                SGD_PLAN_PLANTILLAS
              where 
               ES_MASIVA = 1";

$plant = $db->conn->Execute($sql21);
$arrayplantillas = [];

while (!$plant->EOF) {
  $arrayplantillas[] = $plant->fields;
  $plant->MoveNext();
}

//END::seleccion de plantillas

//Start::divipola departamentos
$sql22       = "SELECT
                DPTO_NOMB
              FROM
                DEPARTAMENTO";

$plant = $db->conn->Execute($sql22);
$arraydepartamentos = [];

while (!$plant->EOF) {
  $arraydepartamentos[] = $plant->fields['DPTO_NOMB'];
  $plant->MoveNext();
}
//END::seleccion de plantillas

//Start::divipola municipios
$sql23       = "SELECT
                MUNI_NOMB
              FROM
                MUNICIPIO";

$plant = $db->conn->Execute($sql23);
$arraymunicipios = [];
while (!$plant->EOF) {
  $arraymunicipios[] = $plant->fields['MUNI_NOMB'];
  $plant->MoveNext();
}
//END::seleccion de plantillas
?>
<html>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <script src="https://use.fontawesome.com/65fc9a6f3f.js"></script>
  <?php include_once "$ruta_raiz/htmlheader.inc.php"; ?>
  <script src="../../include/ckeditor/ckeditor.js"></script>
  <script src="../../include/xlsx/jszip.js"></script>
  <script src="../../include/xlsx/xlsx.js"></script>
  <script src="../../jhrtf/js/validator.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" integrity="sha512-mJ/Iujog+Xougyg1X5zSfMy5M/EOv5PLxQKifvUoGfVJ+uTqPwv8O9p7X/kxH3CebXcJjV4M5JEuPiSx1+W8Tg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" integrity="sha512-E95FBlR1LS5cq4bgu7Bn5+8FX5Y7qJdU/V6+7U5bMqqT7iSm9bGPq47Vg/d+jlzuVWxmRsy1A6pB1j/MlKk15Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style type="text/css">
    /* [FULL SCREEN SPINNER] */
    #spinner-back,
    #spinner-front {
      position: fixed;
      width: 100vw;
      transition: all 1s;
      visibility: hidden;
      opacity: 0;
    }

    #spinner-back {
      z-index: 998;
      height: 100vh;
      background: rgba(0, 0, 0, 0.7);
    }

    #spinner-front {
      z-index: 999;
      color: #fff;
      text-align: center;
      margin-top: 50vh;
      transform: translateY(-50%);
    }

    #spinner-back.show,
    #spinner-front.show {
      visibility: visible;
      opacity: 1;
    }
  </style>
  <script>
    function validar() {
      archDocto = document.formAdjuntarArchivos.archivoPlantilla.value;
      codserie = document.getElementsByName("codserie")[0].value;
      codsubser = document.getElementsByName("tsub")[0].value;
      codtipo = document.getElementsByName("tipo")[0].value;
      codtipora = document.getElementsByName("tipoRad")[0].value;

      if (codserie == 0 | codsubser == 0 | codtipo == 0 | codtipora == 0) {
        alert("Falta seleccionar uno de los campos");
        return false;
      }

      if ((archDocto.substring(archDocto.length - 1 - 3, archDocto.length)).indexOf(".xls") == -1) {
        alert("El archivo de datos debe ser .xls");
        return false;
      }

      if (document.formAdjuntarArchivos.archivoPlantilla.value.length < 1) {
        alert("Debe ingresar el archivo CSV con los datos");
        return false;
      }

      if (confirm("Tenga cuidado con esta opci\u00F3n ya que se realizar\u00E1n\n" +
          "cambios irreversibles en el sistema.")) {
        return true;
      } else {
        return false;
      }
      return true;
    }

    function cargando() {
      document.getElementById("spinner-back").classList.add("show");
      document.getElementById("spinner-front").classList.add("show");
    }

    function enviar() {
      if (!validar())
        return;
      cargando();
      document.formAdjuntarArchivos.accion.value = "PRUEBA";
      document.formAdjuntarArchivos.submit();
    }
  </script>
</head>

<body>
  <div id="spinner-back"></div>
  <div id="spinner-front">
    <img src="https://www.v4software.com/Admin/Tpl/V4admin/Public/image/ajax-loaders/ajax-loader-no-color.gif" /><br>
    Cargando...
  </div>

  <?
  include "tipificar_masivaExcel.php";
  $params = "dependencia=$dependencia&codiTRD=$codiTRD&tipoRad=$tipoRad&depe_codi_territorial=$depe_codi_territorial&usua_nomb=$usua_nomb&depe_nomb=$depe_nomb&usua_doc=$usua_doc&tipo=$tipo&codusuario=$codusuario";
  ?>

  <div class="container-fluid">
    <form action="adjuntar_masivaExcel.php?<?= $params ?>" method="post" enctype="multipart/form-data" name="formAdjuntarArchivos">

      <!-- HIDDEN INPUTS (sin cambios) -->
      <input type="hidden" name="<?= session_name() ?>" value="<?= session_id() ?>">
      <input type="hidden" name="pNodo" value="<?= $pNodo ?>">
      <input type="hidden" name="codProceso" value="<?= $codProceso ?>">
      <input type="hidden" name="tipoRad" value="<?= $tipoRad ?>">
      <input type="hidden" name="MAX_FILE_SIZE" value="<?= return_bytes(ini_get('upload_max_filesize')) ?>">
      <input type="hidden" name="accion" id="accion">

      <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-orfeo text-white fw-semibold">
          <h4 class="fw-bold pb-2">
            Adjuntar archivo con combinación
          </h4>
        </div>

        <div class="card-body">
          <div class="row g-4 mb-4">
            <div class="col-12 col-lg-6">
              <div class="alert alert-info h-100">
                <h6 class="fw-semibold">Para evitar el problema de caracteres especiales en el texto plano se deben reemplazar si existen los siguientes caracteres:</h6>
                <ul class="mb-0 small">
                  <li>‵ ´ “ ” → "</li>
                  <li>° → o</li>
                  <li>ª → a</li>
                  <li>– → -</li>
                  <li># → No</li>
                  <li>┃ │ → l</li>
                  <li>ü, Ü → U</li>
                </ul>
              </div>
            </div>

            <div class="col-12 col-lg-6">
              <div class="alert alert-info h-100">
                <h6 class="fw-semibold">Para salidas el medio de envío es requerido. Evitar dejar vacío, las opciones validas son:</h6>
                <ul class="mb-0 small">
                  <li>FISICO</li>
                  <li>EMAIL</li>
                  <li>AMBOS</li>
                  <li><strong>EMAILNC</strong> (Envio de email sin certificado electrónico del envío)</li>
                </ul>
              </div>
            </div>
          </div>

          <div class="row g-4 mb-4">
            <div class="col-12 col-md-6">
              <label class="form-label fw-semibold">Archivo plantilla (Excel)</label>
              <input name="archivoPlantilla" type="file"
                class="form-control"
                value='<?= $archivoPlantilla ?>'
                id="archivoPlantilla"
                accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">

              <a id="borrarFichero" class="btn btn-outline-danger">
                <i class="fa fa-trash me-1"></i>
                Borrar plantilla para cargar nuevamente
              </a>
            </div>

            <div class="col-12 col-md-6">
              <label class="form-label fw-semibold">Archivo de anexos (ZIP)</label>
              <input name="archivoAnexos" type="file"
                class="form-control"
                value='<?= $archivoAnexos ?>'
                id="archivoAnexos"
                accept="zip,application/octet-stream,application/zip,application/x-zip,application/x-zip-compressed">
            </div>
          </div>

          <div class="mb-4">
            <label class="form-label fw-semibold">Plantilla disponible</label>
            <select id="select-plantillas" class="form-select">
              <option>Seleccione una plantilla...</option>
              <?php
              foreach ($arrayplantillas as $index => $value) {
                echo "<option value='$index'>{$value['PLAN_NOMBRE']}</option>";
              }
              ?>
            </select>
          </div>

          <div class="mb-4">
            <label class="form-label fw-semibold">Contenido del documento</label>
            <textarea id="texrich" name="respuesta" class="form-control" rows="14">
              <table border="0" cellpadding="1" cellspacing="1" style="width:100%">
                  <tbody>
                      <tr>
                        <td style="width:44%">&nbsp;</td>
                        <td style="width:56%">
                            <table border="1" cellspacing="0" class="MsoTableGrid" style="border-collapse:collapse; border:1pt solid windowtext; height:117px; width:95%">
                              <tbody>
                                  <tr>
                                    <td colspan="2">
                                        <p style="text-align:center">
                                        <span style="font-size:11px">
                                            <strong>SUPERINTENDENCIA NACIONAL DE SALUD </strong>
                                        </span>
                                        </p>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td colspan="2">
                                        <p style="text-align:center">
                                          <span style="font-size:11px">
                                              <strong>Para responder este documento favor citar este n&uacute;mero:</strong>
                                          </span>
                                        </p>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td><strong><span style="font-size:11px">Rad No: </span></strong></td>
                                    <td><strong><span style="font-size:11px">RAD_S</span> </strong></td>
                                  </tr>
                                  <tr>
                                  <td><span style="font-size:11px">Fecha: </span></td>
                                  <td><span style="font-size:11px">F_RAD_S</span></td>
                                  </tr>
                                  <tr>
                                  <td><span style="font-size:11px">Expediente</span></td>
                                  <td>*NUM_EXPEDIENTE*</td>
                                  </tr>
                              </tbody>
                            </table>
                        </td>
                      </tr>
                  </tbody>
              </table>

              <p>Bogot&aacute;,</p>

              <p>
                <strong>
                    <span style="font-size:12px">
                      <span style="font-family:Arial,Helvetica,sans-serif">
                          <strong>Se&ntilde;or(a)</strong>
                      </span>
                    </span>
                </strong>
              </p>

              <p>
                  <strong>
                    *NOMBRE*&nbsp;*APELLIDO*<br />
                    *DIGNATARIO*<br />
                    *CARGO*<br />
                    *DIR*<br />
                    *EMAIL*<br />
                    *MUNI_NOMBRE* *DEPTO_NOMBRE* 
                  </strong>
              </p>

              <p style="text-align:justify;"><strong>Asunto: </strong>*ASUNTO*</p>
              <p>&nbsp;</p>
              <p style="text-align:justify;">*CONTENIDO*</p>
              <p>&nbsp;</p>
              <p><br />
              <br />
              <br />
              <strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </strong></p>

              <p style="text-align:justify">Cordialmente,</p>

              <p>${FIRMA}</p>

              <p>&nbsp;</p>
              <p>
                  <span style="font-family:Arial,Helvetica,sans-serif;font-size:10px;">
                      Anexos Electrónicos: *ANEXOS*</br>
                      *DESC_ANEXOS*<br />
                  </span>
                  <br>
                  &nbsp;
              </p>
              <td style="width:7%">&nbsp;</td>
            </textarea>
          </div>

          <div class="alert alert-info d-flex align-items-center mb-4">
            <i class="fa fa-info-circle me-2"></i>
            Para habilitar el botón <strong> Radicar</strong>, la previsualización no debe contener errores.
          </div>

          <div class="d-flex gap-2 mb-4">
            <input type="button" id="previsualizar" class="btn btn-outline-secondary" value="Previsualizar">
            <input type="button" name="enviaPrueba" class="btn btn-primary"
              id="envia22" onClick="enviar();" value="Radicar" disabled>
          </div>

          <div class="alert alert-danger">
            <strong>Cuidado:</strong>
            Esta operación generará un radicado por cada registro del archivo de origen.
            Por favor tenga cuidado con esta opción ya que
            se realizará; cambios irreversibles en el sistema.
          </div>

          <div class="alert alert-warning small">
            <strong>Nota!</strong><br>
            <small>
              Campo para la combinación : (Pueden usarse otros adicionales)<br>
              <b>*PAIS_NOMBRE*</b> : Nombre del pais. <br>
              <b>*ASUNTO*</b> : Asunto que tendra el radicado Generado. <br>
              <b>*FOLIOS*</b> : Opcional, Numero de Fólios del radicado. <br>
              <b>*ANEXOS*</b> : Opcional, Numero de Anexos.<br>
              <b>*DESC_ANEXOS*</b> : Opcional, Descripcion de los anexos del radicado.<br>
              <b>*NUM_EXPEDIENTE*</b> : Opcional, Numero de expediente al cual se asocia el radicado generado. <br>
              <b>*EXP_DE_RADICADO*</b> : Opcional, Asocia el radicado generado a el expediente de un radicado indicado en este campo, es de anotar que si el campo *NUM_EXPEDIENTE*,
              contiene ya un n&uacute;mero de Expediente, este campo no se tendrá en cuenta. Adicionalmente si el radicado (*EXP_DE_RADICADO*) indicado se encuentra dos Expedientes el sistema no asocia ninguno, este proceso deberá ser
              realizado mas adelante de manera manual, el sistema indicara los expedientes que contiene el radicado indicado.
            </small>
          </div>
        </div>
      </div>
    </form>
  </div>

  <div id="modal" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content shadow-lg">
        <div class="modal-header bg-primary text-white d-flex justify-content-between align-items-center">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title fw-bold mb-0" id="myModalLabel">Verifica los datos que se van a cargar</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
              <div id="preview"></div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <span id="info"></span>
          <button type="button" class="btn btn-default" id="anterior">
            << /button>
              <button type="button" class="btn btn-default" id="siguiente">></button>
              <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>

  <script>
    var plantillas = <?php echo json_encode($arrayplantillas) ?>;
    var departamentos = <?php echo json_encode($arraydepartamentos) ?>;
    var municipios = <?php echo json_encode($arraymunicipios) ?>;

    CKEDITOR.config.height = '400';
    CKEDITOR.replace('texrich');

    document.addEventListener('DOMContentLoaded', function() {
      var indice = 0;
      var title = [];
      var dataset = [];

      // $('#select-plantillas').on('change', function() {
      //   CKEDITOR.instances.texrich.setData(plantillas[$(this).val()]['PLAN_PLANTILLA']);
      // })
      const selectPlantillasId = document.getElementById('select-plantillas');

      selectPlantillasId?.addEventListener('change', function() {
        const value = this.value;

        if (
          CKEDITOR.instances.texrich &&
          plantillas[value] &&
          plantillas[value]['PLAN_PLANTILLA']
        ) {
          CKEDITOR.instances.texrich.setData(
            plantillas[value]['PLAN_PLANTILLA']
          );
        }
      });
      //excel

      var ExcelToJSON = function() {
        this.parseExcel = function(file) {
          var reader = new FileReader();

          reader.onload = function(e) {
            var data = e.target.result;
            var workbook = XLSX.read(data, {
              type: 'binary'
            });
            let = 0;
            workbook.SheetNames.forEach(function(sheetName) {
              // Here is your object

              var XL_row_object = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[sheetName]);
              var json_object = JSON.stringify(XL_row_object);
              console.log(JSON.parse(json_object));
              jQuery('#xlx_json').val(json_object);
              if (let == 0)
                dataset = JSON.parse(json_object);
              let ++
            })
          };

          reader.onerror = function(ex) {
            console.log(ex);
          };

          reader.readAsBinaryString(file);
        };
      };

      document.getElementById('archivoPlantilla').addEventListener('change', handleFileSelect, false);

      function handleFileSelect(evt) {
        var files = evt.target.files; // FileList object
        var xl2json = new ExcelToJSON();
        xl2json.parseExcel(files[0]);
      }

      // Lista de dominios válidos comunes
      const dominiosValidos = [
        'hotmail.com',
        'gmail.com',
        'supersalud.gov.co',
        'yahoo.es',
        'outlook.com',
        'yahoo.com',
        'nuevaeps.com.co',
        'hotmail.es',
        'americasbps.com',
        'epscomfenalcovalle.com.co',
        'positiva.gov.co',
        'prueba.gov.co',
        'cajacopi.com',
        'casoauditores.com',
        'live.com',
        'fiscalia.gov.co'
      ];

      // Función de distancia Levenshtein
      function levenshtein(a, b) {
        const matrix = Array.from({
          length: a.length + 1
        }, () => Array(b.length + 1).fill(0));
        for (let i = 0; i <= a.length; i++) matrix[i][0] = i;
        for (let j = 0; j <= b.length; j++) matrix[0][j] = j;

        for (let i = 1; i <= a.length; i++) {
          for (let j = 1; j <= b.length; j++) {
            matrix[i][j] = a[i - 1] === b[j - 1] ?
              matrix[i - 1][j - 1] :
              1 + Math.min(
                matrix[i - 1][j], // eliminación
                matrix[i][j - 1], // inserción
                matrix[i - 1][j - 1] // sustitución
              );
          }
        }
        return matrix[a.length][b.length];
      }

      // Validación de correos con sugerencia de dominio
      function validarEmails(val) {
        let valido = true;
        let errores = "";

        let emails = val.split(';').map(e => e.trim());

        emails.forEach(function(email) {
          if (email.length === 0) return;

          if (typeof validator !== 'undefined' && !validator.isEmail(email)) {
            valido = false;
            errores += `El campo *EMAIL* contiene un correo inválido: "${email}"\n`;
            return;
          }

          const partes = email.split('@');
          if (partes.length !== 2) {
            valido = false;
            errores += `El campo *EMAIL* tiene un formato incorrecto: "${email}"\n`;
            return;
          }

          const dominio = partes[1].toLowerCase();

          if (!dominiosValidos.includes(dominio)) {
            const sugerido = dominiosValidos.find(d => levenshtein(dominio, d) <= 1);
            if (sugerido) {
              valido = false; // Ahora es error, no solo advertencia
              errores += `Error: el dominio del correo "${email}" podría estar mal escrito. ¿Quisiste decir "<b>${partes[0]}@${sugerido}</b>"?\n`;
            } else {
              //errores += `Advertencia: el dominio "${dominio}" no está en la lista de dominios comunes.\n`;
            }
          }
        });

        return {
          valido,
          errores
        };
      }

      function validar() {
        if (dataset.length > 0) {
          let errores = '';
          valido = true;

          dataset.forEach(function(v) {

            // $('select[name=tipoRad]').val()
            const tipoRadSelect = document.querySelector('select[name="tipoRad"]');
            const tiporadSel = tipoRadSelect ? tipoRadSelect.value : null;

            const envios_tipo = ['FISICO', 'EMAIL', 'AMBOS', 'EMAILNC'];

            if (tiporadSel == 1 && v['*MEDIOENVIO*'] === undefined) {
              valido = false;
              errores += `una salida debe tener medio de envio EMAIL o EMAILNC o FISICO o AMBOS valor actual: vacio \n`;
            }

            Object.entries(v).forEach(function([i, val]) {

              if (tiporadSel == 1 && i === '*MEDIOENVIO*' && !envios_tipo.includes(val)) {
                valido = false;
                errores += `una salida debe tener medio de envio EMAIL o EMAILNC o FISICO o AMBOS valor actual: ${val} \n`;
              }

              // Validación EMAIL (regex básica)
              if (i === '*EMAIL*' && val !== '') {
                const emails = val.split(';').map(e => e.trim());
                const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

                emails.forEach(function(email) {
                  if (email.length > 0 && (!emailRegex.test(email) || email.includes(' '))) {
                    valido = false;
                    errores += `El campo *EMAIL* contiene un correo inválido o con espacios: "${email}"\n`;
                  }
                });
              }

              // Validación EMAIL avanzada (validator.js + fallback)
              if (i === '*EMAIL*' && val !== '') {

                const resultadoDominio = validarEmails(val);
                if (resultadoDominio.errores) {
                  errores += resultadoDominio.errores;
                }
                if (!resultadoDominio.valido) {
                  valido = false;
                }

                const emails = val.split(';').map(e => e.trim());

                if (typeof validator !== 'undefined') {
                  emails.forEach(function(email) {
                    if (email.length > 0 && !validator.isEmail(email)) {
                      valido = false;
                      errores += `El campo *EMAIL* contiene un correo inválido: "${email}"\n`;
                    }
                  });
                } else {
                  console.error('La librería validator.js no se ha cargado correctamente');

                  const emailRegex = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

                  emails.forEach(function(email) {
                    if (email.length > 0 && !emailRegex.test(email)) {
                      valido = false;
                      errores += `El campo *EMAIL* contiene un correo inválido: "${email}"\n`;
                    }
                  });
                }
              }

              if (i === '*ANEXOS*' && val !== '' && !Number.isInteger(parseInt(val))) {
                valido = false;
                errores += `la columna anexos debe ser un número sin espacios ni caracteres, valor actual: ${val} \n`;
              }

              if (i === '*MUNI_NOMBRE*' && val !== '' && !municipios.includes(val)) {
                valido = false;
                errores += `verifica la divipola municipio con error ${val} \n`;
              }

              if (i === '*DEPTO_NOMBRE*' && val !== '' && !departamentos.includes(val)) {
                valido = false;
                errores += `verifica la divipola departamento con error ${val} \n`;
              }

              const letters = /[´“”°ª–#'┃│]/g;
              if (typeof val === 'string' && letters.test(val)) {
                valido = false;
                errores += `El excel contiene los siguientes caracteres no validos: ${val} \n`;
              }
            });
          });

          const btnEnviar = document.getElementById('envia22');
          const inputArchivo = document.getElementById('archivoPlantilla');

          if (valido === false) {
            // $('#envia22').prop('disabled', true)
            if (btnEnviar) {
              btnEnviar.disabled = true;
            }

            Swal.fire({
              icon: 'error',
              title: 'Errores en la plantilla',
              html: errores.replace(/\n/g, '<br>') +
                '<br><b>Debes ajustar y cargar la plantilla nuevamente.</b>'
            });

            // Limpia el input file
            if (inputArchivo) {
              inputArchivo.value = '';
            }

            // Detiene la ejecución (equivalente al original)
            throw new Error('error');
          } else {
            // $('#envia22').prop('disabled', false)
            if (btnEnviar) {
              btnEnviar.disabled = false;
            }
          }
        }
      }

      // function validar() {
      //   if (dataset.length > 0) {
      //     let errores = '';
      //     valido = true
      //     $.each(dataset, function(k, v) {
      //       let tiporadSel;
      //       tiporadSel = $('select[name=tipoRad]').val();
      //       let envios_tipo = ['FISICO', 'EMAIL', 'AMBOS', 'EMAILNC']
      //       if (tiporadSel == 1 && v["*MEDIOENVIO*"] === undefined) {
      //         valido = false
      //         errores += `una salida debe tener medio de envio EMAIL o EMAILNC o  FISICO o AMBOS valor actual: vacio \n`;
      //       }
      //       $.each(v, function(i, val) {

      //         if (tiporadSel == 1 && i == '*MEDIOENVIO*' && !envios_tipo.includes(val)) {
      //           valido = false
      //           errores += `una salida debe tener medio de envio EMAIL o EMAILNC o  FISICO o AMBOS valor actual: ${val} \n`;
      //         }
      //         //old

      //         if (i == '*EMAIL*' && val != '') {
      //           // Split emails by comma, trim spaces
      //           let emails = val.split(';').map(e => e.trim());
      //           // Expresión regular mejorada para validar correos electrónicos
      //           let emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
      //           emails.forEach(function(email) {
      //             if (email.length > 0 && (!emailRegex.test(email) || email.includes(' '))) {
      //               valido = false;
      //               errores += `El campo *EMAIL* contiene un correo inválido o con espacios: "${email}"\n`;
      //             }
      //           });
      //         }

      //         // Nueva validación de correos electrónicos
      //         if (i == '*EMAIL*' && val != '') {
      //           // Validación de errores de dominio antes de la validación estándar
      //           const resultadoDominio = validarEmails(val);
      //           if (resultadoDominio.errores) {
      //             errores += resultadoDominio.errores;
      //           }
      //           if (!resultadoDominio.valido) {
      //             valido = false;
      //           }
      //           // Split emails by comma, trim spaces
      //           let emails = val.split(';').map(e => e.trim());

      //           // Verificar que validator esté disponible
      //           if (typeof validator !== 'undefined') {
      //             emails.forEach(function(email) {
      //               if (email.length > 0 && !validator.isEmail(email)) {
      //                 valido = false;
      //                 errores += `El campo *EMAIL* contiene un correo inválido: "${email}"\n`;
      //               }
      //             });
      //           } else {
      //             console.error("La librería validator.js no se ha cargado correctamente");
      //             // Usar regex como fallback
      //             let emailRegex = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

      //             emails.forEach(function(email) {
      //               if (email.length > 0 && !emailRegex.test(email)) {
      //                 valido = false;
      //                 errores += `El campo *EMAIL* contiene un correo inválido: "${email}"\n`;
      //               }
      //             });
      //           }
      //         }

      //         if (i == '*ANEXOS*' && val != '' && val.length > 0 && !Number.isInteger(parseInt(val))) {
      //           valido = false
      //           errores += `la columna anexos debe ser un número sin espacios ni caracteres, valor actual: ${val} \n`;
      //         }

      //         if (i == '*MUNI_NOMBRE*' && val != '' && !municipios.includes(val)) {
      //           valido = false
      //           errores += `verifica la divipola municipio con error ${val} \n`;

      //         }

      //         if (i == '*DEPTO_NOMBRE*' && val != '' && !departamentos.includes(val)) {
      //           valido = false
      //           errores += `verifica la divipola departamento con error ${val} \n`;
      //         }

      //         var letters = /[´“”°ª–#'┃│]/g;
      //         let data = val.match(letters);
      //         if (data !== null) {
      //           valido = false
      //           errores += `El excel contiene los siguientes caracteres no validos: ${val} \n`
      //         }
      //       });
      //     });

      //     if (valido == false) {
      //       $('#envia22').prop('disabled', true);
      //       Swal.fire({
      //         icon: 'error',
      //         title: 'Errores en la plantilla',
      //         html: errores.replace(/\n/g, '<br>') + "<br><b>Debes ajustar y cargar la plantilla nuevamente.</b>"
      //       });
      //       document.getElementById('archivoPlantilla').value = null;
      //       throw new Error("error");
      //     } else {
      //       $('#envia22').prop('disabled', false);
      //     }
      //   }
      // }

      // $('#borrarFichero').on('click', function() {
      //   Swal.fire({
      //     icon: 'info',
      //     title: 'Plantilla borrada',
      //     text: 'Plantilla borrada puede cargar nuevamente'
      //   });
      //   document.getElementById('archivoPlantilla').value = null;
      //   $('#envia22').prop('disabled', true); // Deshabilita el botón Radicar
      //   throw new Error("error");
      // })

      const btnBorrar = document.getElementById('borrarFichero');
      const inputArchivo = document.getElementById('archivoPlantilla');
      const btnEnviar = document.getElementById('envia22');

      btnBorrar?.addEventListener('click', function() {
        Swal.fire({
          icon: 'info',
          title: 'Plantilla borrada',
          text: 'Plantilla borrada puede cargar nuevamente'
        });

        // Limpia el input file
        if (inputArchivo) {
          inputArchivo.value = '';
        }

        // Deshabilita el botón Radicar
        if (btnEnviar) {
          btnEnviar.disabled = true;
        }

        // Equivalente funcional al throw (detiene ejecución)
        throw new Error('error');
      });

      //funcion para reemplazar variables en la plantilla por los registros del csv
      // function cargarDatos(id) {
      //   $('#info').html("Pág. " + ((id % dataset.length) + 1) + " de " + dataset.length);
      //   var html = CKEDITOR.instances.texrich.getData();

      //   if (dataset.length > 0) {
      //     var new_html = html;
      //     $.each(dataset[id % dataset.length], function(k, v) {
      //       new_html = new_html.replace(k, v);
      //     });
      //     $('#preview').html(new_html);
      //   }
      // }

      function cargarDatos(id) {

        const info = document.getElementById('info');
        const preview = document.getElementById('preview');

        if (!dataset || dataset.length === 0) return;

        const paginaActual = (id % dataset.length) + 1;

        // $('#info').html(...)
        if (info) {
          info.innerHTML = `Pág. ${paginaActual} de ${dataset.length}`;
        }

        // CKEditor
        const html = CKEDITOR.instances.texrich.getData();
        let new_html = html;

        // $.each(...)
        const fila = dataset[id % dataset.length];
        Object.keys(fila).forEach(function(k) {
          new_html = new_html.replace(k, fila[k]);
        });

        // $('#preview').html(...)
        if (preview) {
          preview.innerHTML = new_html;
        }
      }

      //paginador previsualización
      // $('#anterior').on('click', function(e) {
      //   indice--;
      //   cargarDatos(Math.abs(indice));
      // });

      // $('#siguiente').on('click', function(e) {
      //   indice++;
      //   cargarDatos(Math.abs(indice));
      // });

      // $(document).ready(function() {
      //   $('#select-plantillas').select2();
      // });

      // $('#previsualizar').on('click', function(e) {
      //   validar();
      //   cargarDatos(indice);
      //   $('#modal').modal('show');
      // })

      // Paginador previsualización
      document.getElementById('anterior')?.addEventListener('click', function(e) {
        indice--;
        cargarDatos(Math.abs(indice));
      });

      document.getElementById('siguiente')?.addEventListener('click', function(e) {
        indice++;
        cargarDatos(Math.abs(indice));
      });

      // Reemplazo de select2 (básico)
      const selectPlantillas = document.getElementById('select-plantillas');
      if (selectPlantillas) {
        // Aquí solo queda como select normal
        // Si quieres algo tipo select2 nativo → te explico abajo
      }

      const btnPrevisualizar = document.getElementById('previsualizar');
      btnPrevisualizar?.addEventListener('click', function(e) {
        validar();
        cargarDatos(indice);

        // Mostrar modal (Bootstrap 5 nativo)
        const modalEl = document.getElementById('modal');
        if (modalEl) {
          const modal = new bootstrap.Modal(modalEl);
          modal.show();
          modalEl.style.display = 'block';
        }
      });
    })
  </script>
</body>

</html>