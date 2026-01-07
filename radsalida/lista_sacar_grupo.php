<?php
session_start();

$ruta_raiz = "..";
if (!$_SESSION['dependencia'])
  header("Location: $ruta_raiz/cerrar_session.php");
/**
 * Programa que lista los radicados que hacen parte de un grupo de masiva. Desde este listado es posible sacar los radicados del grupo
 * que no seran enviados, es llamado desde cuerpo_masiva.php
 * @author      Sixto Angel Pinzon
 * @version     1.0
 */
foreach ($_GET as $key => $valor)   ${$key} = $valor;
foreach ($_POST as $key => $valor)   ${$key} = $valor;

$krd            = $_SESSION["krd"];
$dependencia    = $_SESSION["dependencia"];
$usua_doc       = $_SESSION["usua_doc"];
$codusuario     = $_SESSION["codusuario"];

include_once "../class_control/Radicado.php";
require_once("$ruta_raiz/include/db/ConnectionHandler.php");
include_once "$ruta_raiz/class_control/GrupoMasiva.php";

if (!$db)
  $db = new ConnectionHandler($ruta_raiz);
$db->conn->SetFetchMode(ADODB_FETCH_ASSOC);

//variable que referencia un objeto tipo radicado
$rad =  new Radicado($db);
//variable que referencia un objeto tipo grupo massiva
$grupoMas = new GrupoMasiva($db);
//if (strlen($dep_sel)<1)
//	$dep_sel=$dependencia;
//variable que contiene un arrego de radicados de un grupo de masiva
$radsGrupo = $grupoMas->obtenerGrupo($dep_sel, $grupo, $busq_radicados);

?>
<html>

<head>
  <?php
  include_once $ruta_raiz . "/htmlheader.inc.php";
  ?>
  <style type="text/css">
    .btn-oculto {
      font-size: x-small;
      padding: 3px;
      background-color: #56565578;
      border-radius: 6px;
      margin-left: 5px;
    }
  </style>
  <script>
    /** 
     * Env�a el formulario hacia el programa que realiza la edici�n del grupo de radicados
     */
    function enviar() {
      document.formSacarGrupo.submit();
    }
  </script>
  <script src='../dist/js/jquery-3.5.1.js'></script>
  <script src='../dist/js/jquery.dataTables.min.js'></script>
  <script src='../dist/js/dataTables.buttons.min.js'></script>
  <script src='../dist/js/jszip.min.js'></script>
  <script src='../dist/js/pdfmake.min.js'></script>
  <script src='../dist/js/vfs_fonts.js'></script>
  <script src='../dist/js/buttons.html5.min.js'></script>
  <script src='../dist/js/buttons.print.min.js'></script>
  <script src='../dist/js/buttons.colVis.min.js'></script>
  <link rel="stylesheet" type="text/css" href="../dist/css/jquery.dataTables.min.css">
  <link rel="stylesheet" type="text/css" href="../dist/css/buttons.dataTables.min.css">
</head>

<body>
  <?php
  $nomcarpeta = "EDICION DE RADICADOS DEL GRUPO <b>$grupo</b><br>DE RADICACION MASIVA";
  ?>
  <div class="container-fluid mt-4">
    <div class="row g-3">

      <!-- LISTADO DE -->
      <div class="col-md-4">
        <div class="card shadow-sm border-0 h-100">
          <div class="card-header bg-primary text-white py-2">
            <span class="fw-bold">LISTADO DE</span>
          </div>
          <div class="card-body bg-light">
            <div class="fw-semibold text-dark">
              <?= $nomcarpeta ?>
            </div>
          </div>
        </div>
      </div>

      <!-- USUARIO -->
      <div class="col-md-4">
        <div class="card shadow-sm border-0 h-100">
          <div class="card-header bg-primary text-white py-2">
            <span class="fw-bold">USUARIO</span>
          </div>
          <div class="card-body bg-light">
            <div class="fw-semibold text-dark">
              <?= $nombusuario ?>
            </div>
          </div>
        </div>
      </div>

      <!-- DEPENDENCIA -->
      <div class="col-md-4">
        <div class="card shadow-sm border-0 h-100">
          <div class="card-header bg-primary text-white py-2">
            <span class="fw-bold">DEPENDENCIA</span>
          </div>
          <div class="card-body bg-light">
            <div class="fw-semibold text-dark">
              <?= $depe_nomb ?>
            </div>
          </div>
        </div>
      </div>
    </div>

    <table width="100%" align="center" cellspacing="0" cellpadding="0" class="mt-3">
      <tr>
        <td>
          <form name="form_busq_rad"
            action="lista_sacar_grupo.php?<?= session_name() . '=' . trim(session_id()) ?>"
            method="post"
            class="p-3 border rounded bg-light shadow-sm">

            <input type="hidden" name="<?= session_name() ?>" value="<?= session_id() ?>">

            <div class="mb-3">
              <label class="form-label fw-bold">
                Buscar radicado(s) <small class="text-muted ms-1">(separados por coma)</small>
              </label>

              <input
                name="busq_radicados"
                type="text"
                size="70"
                class="form-control"
                placeholder="Ejemplo: 2024-123, 2024-456, 2024-789"
                value="<?= $busq_radicados ?>">
            </div>

            <div class="d-flex gap-2">
              <input
                type="submit"
                name="buscar"
                valign="middle"
                class="btn btn-primary px-4"
                value="Buscar" />

              <!-- Mantengo exactamente los hidden originales -->
              <input name="grupo" type="hidden" value="<?= $grupo ?>" />
              <input name="dep_sel" type="hidden" value="<?= $dep_sel ?>" />
              <input name="krd" type="hidden" value="<?= $krd ?>" />
            </div>

            <?php
            //almacena los elementos de sesión
            $encabezado  = "&" . session_name() . "=" . session_id();
            $encabezado .= "&krd=$krd&carpeta=$carpeta&tipo_carp=$tipo_carp";
            $encabezado .= "&fechah=$fechah&ascdesc=$ascdesc&agendado=$agendado";
            $encabezado .= "&mostrar_opc_envio=$mostrar_opc_envio&chk_carpeta=$chk_carpeta";
            $encabezado .= "&busq_radicados=$busq_radicados&nomcarpeta=$nomcarpeta&orno=";
            ?>
          </form>
        </td>
      </tr>
    </table>

    <div class="table-responsive shadow-sm rounded border mt-4 margin-botton-table">
      <table id="datatable_masiva" class="table table-striped table-hover align-middle mb-0">
        <thead class="table-primary text-center">
          <tr>
            <th>NUMERO RADICADO</th>
            <th width="10%">FECHA RADICADO</th>
            <th width="15%">ASUNTO</th>
            <th width="8%">NOMBRE DESTINATARIO</th>
            <th width="8%">DOCUMENTO</th>
            <th width="15%">EMAIL</th>
            <th width="8%">DIRECCION</th>
            <th width="8%">DEPARTAMENTO</th>
            <th width="8%">MUNICIPIO</th>
            <th width="8%">FECHA ENVIO</th>
            <th width="8%">ACUSES</th>
            <th width="8%">ESTADO</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $num = count($radsGrupo);
          $i = 0;
          $retirados = "";
          $registro = $pagina * 5000;

          while ($i < $num) {

            if ($i >= $registro and $i < ($registro + 5000)) {

              $clase = ($i % 2 == 0) ? "table-light" : "table-white";

              $datosRad = $rad->radicado_codigo($radsGrupo[$i]);
              $datosRad = $rad->getDatosRemitente();
              $chequeado = "";

              if ($grupoMas->radicadoRetirado($grupo, $radsGrupo[$i])) {
                $retirados .= ";" . $radsGrupo[$i] . ";";
                $chequeado = "checked";
              }
          ?>
              <tr class="<?= $clase ?>">
                <td><span class="fw-semibold"><?= $radsGrupo[$i] ?></span></td>
                <td><?= $rad->getRadi_fech_radi() ?></td>
                <td><?= $rad->getAsuntoRad() ?></td>
                <td><?= $datosRad["nombre"] ?></td>
                <td><?= $datosRad["documento_ciu"] ?></td>
                <td><?= $datosRad["email"] ?></td>
                <td><?= $datosRad["direccion"] ?></td>
                <td><?= $datosRad["deptoNombre"] ?></td>
                <td><?= $datosRad["muniNombre"] ?></td>
                <td><?= $rad->getFechaEnvio() ?></td>
                <td><?= $rad->getAcuses($db) ?></td>

                <td>
                  <?php if (intval($rad->getEstado()) == 4) : ?>
                    <span class="badge bg-success">Enviado</span>
                  <?php elseif (intval($rad->getEstado()) == 3) : ?>
                    <span class="badge bg-warning text-dark">Por enviar</span>
                  <?php elseif (intval($rad->getEstado()) == 2) : ?>
                    <span class="badge bg-danger">Devuelto</span>
                  <?php endif; ?>
                </td>

              </tr>
          <?php
            }
            $i++;
          }
          ?>
        </tbody>
      </table>
    </div>

    <input name="retirados" type="hidden" id="retirados" value="<?php echo $retirados ?>">
  </div>

  <script type="text/javascript">
    $(function() {
      var options = {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
      };
      var today = new Date();
      console.log(today.toLocaleDateString("es-CO", options)); // 9/17/2016
      var table = $('#datatable_masiva').DataTable({
        "language": {
          "url": "//cdn.datatables.net/plug-ins/1.10.11/i18n/Spanish.json"
        },
        dom: 'Bfrtip',
        lengthMenu: [
          [50, 100, 500, -1],
          ['50 registros', '100 registros', '500 rows', 'todos all']
        ],
        buttons: [{
          extend: 'excelHtml5',
          title: 'Masiva ' + today.toLocaleDateString("es-CO", options)
        }, {
          extend: 'pdfHtml5',
          orientation: 'landscape',
          pageSize: 'A2',
          title: 'Masiva ' + today.toLocaleDateString("es-CO", options)
        }, 'pageLength', 'colvis']
      });
    });
  </script>
</body>

</html>