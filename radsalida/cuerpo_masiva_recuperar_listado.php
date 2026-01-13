<?php
session_start();

$ruta_raiz = "..";
if (!$_SESSION['dependencia'])
  header("Location: $ruta_raiz/cerrar_session.php");
//Programa que genera el listado de todos los grupos de masiva 
//generados por la dependencia y da la opci�n de recuperar el listado de cualquiera de

foreach ($_GET  as $key => $valor) ${$key} = $valor;
foreach ($_POST as $key => $valor) ${$key} = $valor;

$dependencia = $_SESSION['dependencia'];

include_once "$ruta_raiz/class_control/GrupoMasiva.php";
include_once "$ruta_raiz/class_control/usuario.php";
require_once("$ruta_raiz/include/db/ConnectionHandler.php");
include_once "$ruta_raiz/class_control/TipoDocumento.php";

if (!$db)
  $db = new ConnectionHandler($ruta_raiz);

$grupoMas   = new GrupoMasiva($db);
$usuarioGen = new Usuario($db);
$tipoDoc    = new TipoDocumento($db);
?>

<html>

<head>
  <meta http-equiv="Cache-Control" content="cache">
  <meta http-equiv="Pragma" content="public">
  <?php include_once "../htmlheader.inc.php"; ?>
  <?
  $fechah = date("dmy") . "_" . time("h_m_s");
  $encabezado = session_name() . "=" . session_id() . "&krd=$krd";
  ?>
  <script>
    function back() {
      history.go(-1);
    }
  </script>
  <?php
  error_reporting(7);
  ?>
  <?PHP

  if ($dep_sel) {
    $accion_sal        = "Recuperar Listado";
    $pagina_sig        = "recuperar_listado.php";
    $dependencia_busq2 = " and sgd_depe_genera    = '$dep_sel'";
  } else {
    $accion_sal        = "Envio de Documentos";
    $pagina_sig        = "recuperar_listado.php";
    $dependencia_busq2 = " and sgd_depe_genera    = '$dependencia'";
  }

  if ($busq_radicados) {
    $busq_radicados = trim($busq_radicados);
    $textElements = split(",", $busq_radicados);
    $newText = "";
    foreach ($textElements as $item) {
      $item = trim($item);
      if (strlen($item) != 0) {
        $sec = str_pad($item, 6, "0", STR_PAD_left);
        $item = date("Y") . $dep_sel . $sec;
        $busq_radicados_tmp .= "$item,";
      }
    }
    if (substr($busq_radicados_tmp, -1) == ",")   $busq_radicados_tmp = substr($busq_radicados_tmp, 0, strlen($busq_radicados_tmp) - 1);
    $dependencia_busq2 .= " and radi_nume_salida in($busq_radicados_tmp)  ";
  }

  $tbbordes = "#CEDFC6";
  $tbfondo  = "#FFFFCC";

  if (!$orno) {
    $orno = 1;
  }

  $imagen = "flechadesc.gif";

  if ($estado_sal == 2) {
    $dependencia_busq1 = " and radi_nume_sal like '2004$dependencia%'";
    $dependencia_busq2 = " and radi_nume_salida like '2004$dependencia%'";
  }

  ?>
  <script>
    <?php
    //include "libjs.php";
    function tohtml($strValue)
    {
      return htmlspecialchars($strValue);
    }
    ?>
  </script>
</head>

<body bgcolor="#FFFFFF" topmargin="0">
  <div id="object1" style="position:absolute; visibility:show; left:10px; top:-50px; width=80%; z-index:2">
    <p>Cuadro de Historico</p>
  </div>
  <?php
  /** 
   * PARA EL FUNCIONAMIENTO CORRECTO DE ESTA PAGINA SE NECESITAN UNAS VARIABLE QUE DEBEN VENIR 
   * carpeta  "Codigo de la carpeta a abrir"
   * nomcarpeta "Nombre de la Carpeta"
   * tipocarpeta "Tipo de Carpeta  (0,1)(Generales,Personales)"
   * 
   * seleccionar todos los checkboxes
   */
  $img1 = "";
  $img2 = "";
  $img3 = "";
  $img4 = "";
  $img5 = "";
  $img6 = "";
  $img7 = "";
  $img8 = "";
  $img9 = "";

  if ($ordcambio) {
    if ($ascdesc == "") {
      $ascdesc = "DESC";
      $imagen = "flechadesc.gif";
    } else {
      $ascdesc = "";
      $imagen = "flechaasc.gif";
    }
  }

  if ($orno == 1) {
    $order = " r.radi_nume_grupo $ascdesc";
    $img1 = "<img src='../iconos/$imagen' border=0 alt='$data'>";
  }

  if ($orno == 4) {
    $order = " 5 $ascdesc";
    $img4 = "<img src='../iconos/$imagen' border=0 alt='$data'>";
  }

  $datosaenviar = "fechaf=$fechaf&tipo_carp=$tipo_carp&ascdesc=$ascdesc&orno=$orno";
  $encabezado = session_name() . "=" . session_id() . "&dep_sel=$dep_sel&krd=$krd&estado_sal=$estado_sal&fechah=$fechah&estado_sal_max=$estado_sal_max&ascdesc=$ascdesc&orno=";
  $fechah = date("dmy") . "_" . time("h_m_s");

  $check = 1;
  $fechaf = date("dmy") . "_" . time("hms");
  //$numeroa=0;$numero=0;$numeros=0;$numerot=0;$numerop=0;$numeroh=0;

  //$resultado = ora_parse($cursor,$isql);
  //$resultado = ora_exec($cursor);
  $row = array();
  //ora_fetch_into($cursor,$row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);  
  // Validacion de Usuario y COntrase�a MD5
  ?>


  <br>
  <?php
  /** Instruccion que realiza la consulta de radicados segun criterios
   * Tambien observamos que se encuentra la varialbe $carpetaenviar que maneja la carpeta 11.
   */
  $sqlFecha = $db->conn->SQLDate("Y/m/D", "r.SGD_RENV_FECH");

  $limit = "";
  include "$ruta_raiz/include/query/radsalida/queryCuerpoMasivaRecuperearListado.php";
  $rs = $db->query($isql);

  if ($nomcarpeta == "") {
    $nomcarpeta = " RECUPERACION DE LISTADOS GENERADOS -MASIVA ";
  }
  ?>

  <div class="container-fluid">
    <div class="row g-3 mb-4">
      <!-- LISTADO -->
      <div class="col-12 col-md-4">
        <div class="card h-100 shadow-sm border-0">
          <div class="card-header bg-primary text-white py-2">
            <small class="fw-semibold">
              <i class="fa fa-folder-open me-1"></i>
              LISTADO DE
            </small>
          </div>
          <div class="card-body py-3">
            <span class="fw-semibold text-dark">
              <?= $nomcarpeta ?>
            </span>
          </div>
        </div>
      </div>

      <!-- USUARIO -->
      <div class="col-12 col-md-4">
        <div class="card h-100 shadow-sm border-0">
          <div class="card-header bg-primary text-white py-2">
            <small class="fw-semibold">
              <i class="fa fa-user me-1"></i>
              USUARIO
            </small>
          </div>
          <div class="card-body py-3">
            <span class="fw-semibold text-dark">
              <?= $nombusuario ?>
            </span>
          </div>
        </div>
      </div>

      <!-- DEPENDENCIA -->
      <div class="col-12 col-md-4">
        <div class="card h-100 shadow-sm border-0">
          <div class="card-header bg-primary text-white py-2">
            <small class="fw-semibold">
              <i class="fa fa-building me-1"></i>
              DEPENDENCIA
            </small>
          </div>
          <div class="card-body py-3">
            <span class="fw-semibold text-dark">
              <?= $depe_nomb ?>
            </span>
          </div>
        </div>
      </div>
    </div>

    <form name='form1'
      action='<?= $pagina_sig ?>?<?= session_name() . "=" . session_id() . "&krd=$krd&fechah=$fechah&dep_sel=$dep_sel&estado_sal=$estado_sal&estado_sal_max=$estado_sal_max" ?>'
      method="post">
      <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle text-nowrap">
          <!-- ENCABEZADO -->
          <thead class="table-primary text-center">
            <tr>
              <th width="10%">
                <a href='cuerpo_masiva_recuperar_listado.php?<?= $encabezado ?>1&ordcambio=1'
                  class="text-decoration-none text-dark fw-semibold">
                  <?= $img1 ?> Grupo
                </a>
              </th>
              <th width="9%">Radicado Inicial</th>
              <th width="11%">Radicado Final</th>
              <th width="7%">
                <a href='cuerpo_masiva_recuperar_listado.php?<?= $encabezado ?>4&ordcambio=1'
                  class="text-decoration-none text-dark fw-semibold">
                  <?= $img4 ?> Fecha
                </a>
              </th>
              <th width="10%">Documentos</th>
              <th width="9%">Eliminados</th>
              <th width="28%">Generado por</th>
              <th width="12%">Tipo</th>
              <th width="4%">Enviar</th>
            </tr>
          </thead>

          <!-- CUERPO -->
          <tbody>
            <?php
            $i = 1;
            $ki = 0;
            $registro = $pagina * 220;
            $leido = "leidos";

            while ($rs && !$rs->EOF) {
              if ($ki >= $registro and $ki < ($registro + 220)) {

                $radi_nume_grupo = $rs->fields['RADI_NUME_GRUPO'];
                $documentos = $rs->fields['DOCUMENTOS'];

                $formato = ($i == 1) ? "listado1" : "listado2";
                $i = ($i == 1) ? 2 : 1;

                $grupoMas->limpiarGrupoSacado();
                $grupoMas->setGrupoSacado($radi_nume_grupo);
                $eliminados = $grupoMas->getNumeroSacados();

                $tipoDoc->TipoDocumento_codigo($rs->fields['TDOC_CODI']);

                $usuarioGen->limpiarAtributos();
                $usuarioGen->usuarioDocto($rs->fields['USUA_DOC']);
            ?>
                <tr class="<?= $formato ?>">
                  <td class="<?= $leido ?> text-center fw-semibold">
                    <a href='lista_sacar_grupo.php?grupo=<?= $radi_nume_grupo ?>&krd=<?= $krd ?>'
                      class="text-decoration-none">
                      <?= $radi_nume_grupo ?>
                    </a>
                  </td>
                  <td class="<?= $leido ?> text-center"><?= $rs->fields['RAD_INI']; ?></td>
                  <td class="<?= $leido ?> text-center"><?= $rs->fields['RAD_FIN']; ?></td>
                  <td class="<?= $leido ?> text-center"><?= $rs->fields['FECHA']; ?></td>
                  <td class="<?= $leido ?> text-center"><?= $documentos ?></td>
                  <td class="<?= $leido ?> text-center">
                    <span class="badge bg-danger-subtle text-danger">
                      <?= $eliminados ?>
                    </span>
                  </td>
                  <td class="<?= $leido ?>">
                    <?= $usuarioGen->get_usua_nomb(); ?>
                  </td>
                  <td class="<?= $leido ?>">
                    <?= $tipoDoc->get_sgd_tpr_descrip() ?>
                  </td>
                  <td class="<?= $leido ?> text-center">
                    <!-- reservado -->
                  </td>
                </tr>
            <?php
              }
              $ki++;
              $rs->MoveNext();
            }
            ?>
          </tbody>
        </table>
      </div>
    </form>

    <table width="98%" align="center" class="my-3">
      <tr>
        <td class="text-center">

          <?php
          $numerot = $ki;
          $paginas = ($numerot / 220);
          ?>

          <nav aria-label="Paginación">
            <ul class="pagination pagination-sm justify-content-center mb-0">

              <?php
              if (intval($paginas) <= $paginas) {
                $paginas = $paginas;
              } else {
                $paginas = $paginas - 1;
              }

              for ($ii = 0; $ii < $paginas; $ii++) {

                $active = ($pagina == $ii) ? ' active' : '';
              ?>
                <li class="page-item<?= $active ?>">
                  <a class="page-link"
                    href="cuerpo_masiva_recuperar_listado.php?pagina=<?= $ii ?>&<?= $encabezado . $orno ?>">
                    <?= ($ii + 1) ?>
                  </a>
                </li>
              <?php } ?>
            </ul>
          </nav>
        </td>
      </tr>
    </table>
  </div>
</body>

</html>