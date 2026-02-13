<?php
session_start();

$ruta_raiz = "..";
if (!$_SESSION['dependencia'])
  header("Location: $ruta_raiz/cerrar_session.php");
//Programa que genera el listado de todos los grupos de masiva generados por la dependencia, que no han sido enviados y da la opci�n de 
//generar el env�o respectivo

foreach ($_GET as $key => $valor)   ${$key} = $valor;
foreach ($_POST as $key => $valor)   ${$key} = $valor;

$krd            = $_SESSION["krd"];
$dependencia    = $_SESSION["dependencia"];
$usua_doc       = $_SESSION["usua_doc"];
$codusuario     = $_SESSION["codusuario"];


include_once "$ruta_raiz/class_control/GrupoMasiva.php";
include_once "$ruta_raiz/class_control/usuario.php";
require_once("$ruta_raiz/include/db/ConnectionHandler.php");
include_once "$ruta_raiz/class_control/TipoDocumento.php";
require_once("$ruta_raiz/include/combos.php");
//require "$ruta_raiz/Kint.phar";

if (!$db)
  $db = new ConnectionHandler($ruta_raiz);

$db->conn->SetFetchMode(ADODB_FETCH_ASSOC);

$grupoMas   =  new GrupoMasiva($db);
$usuarioGen =  new Usuario($db);
$tipoDoc    =  new TipoDocumento($db);

if (strlen($dep_sel) < 1)
  $dep_sel = $dependencia;

$sqlFechaHoy = $db->conn->DBDate($fecha_hoy);

?>

<html>

<head>
  <meta http-equiv="Cache-Control" content="cache">
  <meta http-equiv="Pragma" content="public">
  <?
  //variable con la fecha formateada
  $fechah = date("dmy") . "_" . time("h_m_s");
  //variable con elementos de sesi�n
  $encabezado = session_name() . "=" . session_id() . "&krd=$krd";

  ?>
  <link rel="stylesheet" href="../estilos/orfeo.css">
  <script>
    function back() {
      history.go(-1);
    }

    function envioTx() {
      var sw = 0;
      for (i = 1; i < document.form1.elements.length; i++)
        if (document.form1.elements[i].checked)
          sw = 1;

      if (sw == 0) {
        alert("Debe seleccionar al menos un elemento");
        return false;
      }
    }
  </script>
  <?php

  //variable que indica la acci�n a ejecutar en el formulario
  $accion_sal = "Enviar";
  //variable que indica la acci�n a ejecutar en el formulario
  $pagina_sig = "envio_masiva.php";

  //var de formato para la tabla
  $tbbordes = "#CEDFC6";
  //var de formato para la tabla
  $tbfondo = "#FFFFCC";

  //le pone valor a la variable que maneja el criterio de ordenamiento inicial
  if (!$orno) {
    $orno = 1;
  }

  $imagen = "flechadesc.gif";
  ?>
  <script>
    //  Esta funcion esconde el combo de las dependencia e inforados Se activan cuando el menu envie una se�al de cambio.
    function window_onload() {
      form1.depsel.style.display = '';
      form1.enviara.style.display = '';
      form1.depsel8.style.display = 'none';
      form1.carpper.style.display = 'none';
      setVariables();
      setupDescriptions();
    }
    <?php
    function tohtml($strValue)
    {
      return htmlspecialchars($strValue);
    }
    ?>

    function cambioDependecia(dep) {
      document.formDep.action = "cuerpo_masiva.php?krd=<?= $krd ?>&dep_sel=" + dep;
      document.formDep.submit();
    }
  </script>
  <?php
  include_once $ruta_raiz . "/htmlheader.inc.php";
  ?>
</head>

<body topmargin="0">
  <div id="object1" style="position:absolute; visibility:show; left:10px; top:-50px; width:80%; z-index:2">
    <p>Cuadro de Historico</p>
  </div>
  <?php

  $sqlFecha = $db->conn->SQLDate("Y/m/d", "r.SGD_RENV_FECH");
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
    $order = " $sqlFecha $ascdesc";
    $img4 = "<img src='../iconos/$imagen' border=0 alt='$data'>";
  }

  $datosaenviar = "fechaf=$fechaf&tipo_carp=$tipo_carp&ascdesc=$ascdesc&orno=$orno";
  $encabezado = session_name() . "=" . session_id() . "&dep_sel=$dep_sel&krd=$krd&estado_sal=$estado_sal&fechah=$fechah&estado_sal_max=$estado_sal_max&ascdesc=$ascdesc&orno=";
  $fechah = date("dmy") . "_" . time("h_m_s");
  $check = 1;
  $fechaf = date("dmy") . "_" . time("hms");
  $row = array();

  ?>
  <div class="container-fluid my-3">

    <?php
    /** 
     * Instruccion que realiza la consulta de radicados segun criterios
     * Tambien observamos que se encuentra la varialbe $carpetaenviar que maneja la carpeta 11.
     */
    include "$ruta_raiz/include/query/radsalida/queryCuerpoMasiva.php";
    $rs = $db->query($isql);
    ?>

    <div class="card shadow-sm border-0 rounded-3">
      <div class="card-body">

        <!-- Primera fila -->
        <div class="row g-3">

          <!-- Columna: Listado -->
          <div class="col-md-4">
            <div class="p-3 border rounded bg-light h-100">
              <p class="mb-1 text-secondary small">Listado de:</p>
              <h6 class="fw-bold text-primary">
                <?= $nomcarpeta == "" ? "RADICADOS DE MASIVA" : $nomcarpeta ?>
              </h6>
            </div>
          </div>

          <!-- Columna: Usuario -->
          <div class="col-md-4">
            <div class="p-3 border rounded bg-light h-100">
              <p class="mb-1 text-secondary small">Usuario:</p>
              <h6 class="fw-bold"><?= $nombusuario ?></h6>
            </div>
          </div>

          <!-- Columna: Dependencia -->
          <div class="col-md-4">
            <div class="p-3 border rounded bg-light h-100">
              <p class="mb-1 text-secondary small">Dependencia:</p>

              <form name="formDep">
                <input type="hidden" name="<?= session_name() ?>" value="<?= session_id() ?>">

                <select
                  name="dep_sel"
                  id="tipo_clase"
                  class="form-select form-select-sm"
                  onchange="cambioDependecia(this.value)">
                  <option selected value="null">----- tipos de documento -----</option>

                  <?php
                  $a = new combo($db);
                  $codiATexto = $db->conn->numToString("DEPE_CODI");
                  $concatSQL = $db->conn->Concat($codiATexto, "' '", "DEPE_NOMB");
                  $s = "SELECT DEPE_CODI,$concatSQL as NOMBRE from dependencia ORDER BY depe_codi ASC";
                  $a->conectar($s, "DEPE_CODI", "NOMBRE", $dep_sel, 0, 0);
                  ?>
                </select>

                <input type="hidden" name="krd" value="<?= $krd ?>">
              </form>

              <form
                name="form_busq_dep"
                action="cuerpo_masiva.php?<?= session_name() . "=" . session_id() . "&krd=$krd" ?>&estado_sal=<?= $estado_sal ?>&estado_sal_max=<?= $estado_sal_max ?>&pagina_sig=<?= $pagina_sig ?>"
                method="post">
                <input type="hidden" name="<?= session_name() ?>" value="<?= session_id() ?>">
              </form>
            </div>
          </div>
        </div>
      </div>

      <div class="card shadow-sm border-0 mt-4">
        <div class="card-body">
          <!-- Formulario principal -->
          <form name="form1"
            action="<?= $pagina_sig ?>?<?= "fechah=$fechah&dep_sel=$dep_sel&estado_sal=$estado_sal&estado_sal_max=$estado_sal_max" ?>"
            method="post">

            <input type="hidden" name="dep_sel" value="<?= $dep_sel ?>">
            <input type="hidden" name="krd" value="<?= $krd ?>">

            <!-- Encabezado con botón -->
            <div class="d-flex justify-content-between align-items-center mb-3">
              <h5 class="mb-0 fw-bold text-primary">
                Gestión de Grupos Masivos
              </h5>

              <input type="submit" value="<?= $accion_sal ?>" name="Enviar" class="btn btn-primary btn-sm px-4" onclick="return envioTx()">
            </div>

            <!-- Tabla moderna -->
            <div class="table-responsive rounded">
              <table class="table table-hover table-bordered align-middle mb-0">
                <thead class="table-primary text-center">
                  <tr>
                    <th width="10%">
                      <a href="cuerpo_masiva.php?<?= $encabezado ?>1&ordcambio=1" class="link-dark fw-bold text-decoration-none">
                        <?= $img1 ?> Grupo
                      </a>
                    </th>
                    <th width="9%">Radicado Inicial</th>
                    <th width="11%">Radicado Final</th>
                    <th width="7%">
                      <a href="cuerpo_masiva.php?<?= $encabezado ?>4&ordcambio=1"
                        class="link-dark fw-bold text-decoration-none">
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
                <tbody>
                  <?php
                  $i = 1;
                  $ki = 0;
                  $registro = $pagina * 20;

                  while ($rs && !$rs->EOF) {
                    if ($ki >= $registro && $ki < ($registro + 20)) {

                      $radi_nume_grupo = $rs->fields['RADI_NUME_GRUPO'];
                      $documentos = $rs->fields['DOCUMENTOS'];

                      $formato = ($i == 1 ? "table-light" : "table-white");
                      $i = ($i == 1 ? 2 : 1);

                      $grupoMas->limpiarGrupoSacado();
                      $grupoMas->setGrupoSacado($radi_nume_grupo);
                      $eliminados = $grupoMas->getNumeroSacados();

                      $usuarioGen->limpiarAtributos();
                      $usuarioGen->usuarioDocto($rs->fields['USUA_DOC']);
                      $tipoDoc->TipoDocumento_codigo($rs->fields['TDOC_CODI']);
                  ?>
                      <tr class="<?= $formato ?>">
                        <td class="text-center fw-bold">
                          <a href="lista_sacar_grupo.php?<?= session_name() . "=" . session_id() ?>&grupo=<?= $radi_nume_grupo ?>&krd=<?= $krd ?>&dep_sel=<?= $dep_sel ?>"
                            class="text-decoration-none">
                            <?= $radi_nume_grupo ?>
                          </a>
                        </td>

                        <td class="text-center"><?= $rs->fields['RAD_INI'] ?></td>
                        <td class="text-center"><?= $rs->fields['RAD_FIN'] ?></td>
                        <td class="text-center"><?= $rs->fields['FECHA'] ?></td>

                        <td class="text-center fw-bold"><?= $documentos ?></td>
                        <td class="text-center text-danger fw-bold"><?= $eliminados ?></td>

                        <td><?= $usuarioGen->get_usua_nomb() ?></td>
                        <td class="text-center"><?= $tipoDoc->get_sgd_tpr_descrip() ?></td>

                        <td class="text-center">
                          <?php if ($documentos - $eliminados > 0) { ?>
                            <input type="radio" name="radGrupo" value="<?= $radi_nume_grupo ?>">
                          <?php } ?>
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
        </div>
      </div>

      <div class="d-flex justify-content-center mt-4 margin-botton-table">
        <nav aria-label="Paginación de resultados">
          <ul class="pagination pagination-sm">

            <?php
            $numerot = $ki;
            $paginas = ($numerot / 20);

            if (intval($paginas) <= $paginas) {
              $paginas = $paginas;
            } else {
              $paginas = $paginas - 1;
            }

            echo '<li class="page-item disabled">
                            <span class="page-link bg-light text-dark fw-bold">Páginas</span>
                        </li>';

            for ($ii = 0; $ii < $paginas; $ii++) {

              $active = ($pagina == $ii) ? "active" : "";
              $colorText = ($pagina == $ii) ? "text-white" : "text-primary";

              echo "
                            <li class='page-item $active'>
                                <a class='page-link $colorText'
                                href='cuerpo_masiva.php?dep_sel=$dep_sel&pagina=$ii&$encabezado$orno'>
                                " . ($ii + 1) . "
                                </a>
                            </li>";
            }
            ?>

          </ul>
        </nav>
      </div>
    </div>
  </div>
</body>

</html>