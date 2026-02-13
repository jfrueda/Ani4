<?

session_start();
error_reporting(7);
$ruta_raiz = ".";
include_once "./include/db/ConnectionHandler.php";


if (!$db) {
  $db = new ConnectionHandler(".");
}

$db->conn->SetFetchMode(ADODB_FETCH_ASSOC);

foreach ($_GET as $key => $valor) {
  ${$key} = $valor;
}
foreach ($_POST as $key => $valor) {
  ${$key} = $valor;
}

#Inicializo el krd
$krd            = $_SESSION["krd"];

#Traigo todos los datos segun el documento el krd
include 'cargodatosusuario.php';

#Recupero todos los datos del POST para Mostrarlos a tiempo
if ($_POST["usua_doc"]) {
  $usua_doc = $_POST["usua_doc"];
}
if ($_POST["usua_dia"]) {
  $usua_dia = $_POST["usua_dia"];
}
if ($_POST["usua_mes"]) {
  $usua_mes = $_POST["usua_mes"];
}
if ($_POST["usua_ano"]) {
  $usua_ano = $_POST["usua_ano"];
}
if ($_POST["usua_email"]) {
  $usua_email = $_POST["usua_email"];
}
if ($_POST["usua_piso"]) {
  $usua_piso = $_POST["usua_piso"];
}
if ($_POST["usua_ext"]) {
  $usua_ext = $_POST["usua_ext"];
}
if ($_POST["usua_at"]) {
  $usua_at = $_POST["usua_at"];
}
if ($_POST) {
  $notificar = $_POST['notificar'] ? 1 : 0;
}

#Debug
#$db->conn->debug = true; 
?>

<head>
  <?php include_once "htmlheader.inc.php"; ?>
</head>

<body onload="SetFocus();">
  <form enctype="multipart/form-data" name="datos_personales" action="" class="form-smart" method="post">
    <div class="container mt-4">
      <div class="card shadow-sm border-0">
        <div class="card-header bg-orfeo bg-gradient text-white py-3">
          <div class="d-flex align-items-center justify-content-center text-center">
            <i class="fa fa-info-circle me-3 fa-2x"></i>
            <h6 class="mb-0 fw-bold">
              La información aquí reportada se considera oficial y es indispensable para
              iniciar el acceso al Sistema de Gestión en la Entidad <?= $entidad ?>
            </h6>
          </div>
        </div>

        <div class="card-body bg-light p-4">
          <div class="row g-3 mb-4">
            <div class="col-md-4">
              <div class="p-3 bg-white rounded shadow-sm h-100">
                <label class="form-label fw-bold small text-uppercase">Documento C.C</label>
                <?
                if ($info) {
                  $info = "false";
                } else {
                  $info = "true";
                }
                ?>
                <input type="text" name="usua_doc" class="form-control" value="<?= TRIM($usua_doc) ?>" maxlength="20" readonly="<?= $info ?>">
                <div class="form-text mt-1" style="font-size: 0.75rem;">Sin puntos, comas o caracteres especiales.</div>
              </div>
            </div>

            <div class="col-md-6">
              <div class="p-3 bg-white rounded shadow-sm h-100">
                <label class="form-label fw-bold small text-uppercase">Fecha de Nacimiento</label>
                <div class="d-flex gap-2">
                  <?
                  $ano_fin = date("Y");
                  $ano_fin++;
                  $ano_fin = $ano_fin - 10;
                  $ano_ini = $ano_fin - 80;
                  ?>
                  <select name="usua_dia" class="form-select">
                    <option value="0">Día</option>
                    <?
                    for ($i = 1; $i <= 31; $i++) {
                      $datoss = ($i == $usua_dia) ? "selected" : "";
                      echo "<option value=$i $datoss>$i</option>";
                    }
                    ?>
                  </select>

                  <select name="usua_mes" class="form-select">
                    <option value="0">Mes</option>
                    <option value="1" <?= ($usua_mes == 1) ? "selected" : "" ?>>Ene</option>
                    <option value="2" <?= ($usua_mes == 2) ? "selected" : "" ?>>Feb</option>
                    <option value="3" <?= ($usua_mes == 3) ? "selected" : "" ?>>Mar</option>
                    <option value="4" <?= ($usua_mes == 4) ? "selected" : "" ?>>Abr</option>
                    <option value="5" <?= ($usua_mes == 5) ? "selected" : "" ?>>May</option>
                    <option value="6" <?= ($usua_mes == 6) ? "selected" : "" ?>>Jun</option>
                    <option value="7" <?= ($usua_mes == 7) ? "selected" : "" ?>>Jul</option>
                    <option value="8" <?= ($usua_mes == 8) ? "selected" : "" ?>>Ago</option>
                    <option value="9" <?= ($usua_mes == 9) ? "selected" : "" ?>>Sep</option>
                    <option value="10" <?= ($usua_mes == 10) ? "selected" : "" ?>>Oct</option>
                    <option value="11" <?= ($usua_mes == 11) ? "selected" : "" ?>>Nov</option>
                    <option value="12" <?= ($usua_mes == 12) ? "selected" : "" ?>>Dic</option>
                  </select>

                  <select name="usua_ano" class="form-select">
                    <option value="0">Año</option>
                    <?
                    for ($i = 1; $i <= 80; $i++) {
                      $ano = ($ano_fin - $i);
                      $datoss = ($ano == $usua_ano) ? "selected" : "";
                      echo "<option value='$ano' $datoss>$ano</option>";
                    }
                    ?>
                  </select>
                </div>
              </div>
            </div>

            <div class="col-md-2">
              <div class="p-3 bg-white rounded shadow-sm h-100">
                <label class="form-label fw-bold small text-uppercase">Extensión</label>
                <input type="text" name="usua_ext" class="form-control text-center" value="<?= $usua_ext ?>" maxlength="4">
              </div>
            </div>
          </div>

          <div class="row g-3 mb-4">
            <div class="col-md-5">
              <div class="p-3 bg-white rounded shadow-sm h-100">
                <label class="form-label fw-bold small text-uppercase text-secondary">Correo Electrónico</label>
                <div class="input-group">
                  <span class="input-group-text bg-white"><i class="fa fa-envelope-o text-muted"></i></span>
                  <input type="text" name="usua_email" class="form-control" value="<?= trim($usua_email) ?>" maxlength="70">
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="p-3 bg-white rounded shadow-sm h-100">
                <label class="form-label fw-bold small text-uppercase text-secondary">Identificación Equipo</label>
                <input type="text" name="usua_at" class="form-control" value="<?= $usua_at ?>" placeholder="Ej: at-999" maxlength="35">
              </div>
            </div>
            <div class="col-md-3">
              <div class="p-3 bg-white rounded shadow-sm h-100">
                <label class="form-label fw-bold small text-uppercase text-secondary">Piso</label>
                <input type="text" name="usua_piso" class="form-control text-center" value="<?= $usua_piso ?>" maxlength="2">
              </div>
            </div>
          </div>

          <div class="row g-3">
            <?
            $bodega_firmas = $ruta_raiz . '/bodega/firmas/';
            $uriFile1 = $bodega_firmas . $usua_doc;
            $uriFile2 = $bodega_firmas . $usua_doc . '.p12';
            ?>

            <? if (isset($_SESSION["usua_perm_firma"])) { ?>
              <div class="col-md-5">
                <div class="p-3 border rounded bg-white shadow-sm h-100 border-start border-4 border-info">
                  <label class="form-label fw-bold small">Imagen de Firma Mecánica</label>
                  <input type="file" name="file1" class="form-control form-control-sm mb-2">
                  <? if (file_exists($uriFile1)) { ?>
                    <div class="badge bg-danger-subtle text-danger border border-danger p-2 w-100 text-wrap">
                      <i class="fa fa-warning me-1"></i> Ya existe una imagen cargada
                    </div>
                  <? } ?>
                </div>
              </div>

              <div class="col-md-5">
                <div class="p-3 border rounded bg-white shadow-sm h-100 border-start border-4 border-info">
                  <label class="form-label fw-bold small">Firma Digital (.p12)</label>
                  <input type="file" name="file2" class="form-control form-control-sm mb-2">
                  <? if (file_exists($uriFile2)) { ?>
                    <div class="badge bg-danger-subtle text-danger border border-danger p-2 w-100 text-wrap">
                      <i class="fa fa-warning me-1"></i> Ya existe una firma cargada
                    </div>
                  <? } ?>
                </div>
              </div>
            <? } ?>

            <div class="<?= isset($_SESSION["usua_perm_firma"]) ? 'col-md-2' : 'col-md-12' ?>">
              <div class="p-3 border rounded bg-white shadow-sm h-100 d-flex flex-column justify-content-center align-items-center">
                <label class="form-check-label fw-bold small text-center mb-2" for="notificar">
                  Enviar notificaciones
                </label>
                <div class="form-check form-switch">
                  <input class="form-check-input" style="transform: scale(1.5);" id="notificar" name="notificar" type="checkbox" <?= $notificar ? 'checked' : '' ?>>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="card-footer bg-white py-3 text-center">
          <input type=submit name=grabar_datos_per class="btn btn-primary btn-lg px-5 fw-bold shadow" value="Grabar Datos Personales">
        </div>
      </div>
    </div>
  </form>

  <?
  $usua_email = $_GET['usua_email'] = $_POST["usua_email"];
  $usua_at = $GET['usua_email'] = $_POST["usua_at"];
  $grabar_datos_per = $_POST["grabar_datos_per"];
  #echo "$usua_doc and $grabar_datos_per";
  if ($usua_doc && $grabar_datos_per) {
    #compruebo si el check llega vacio, coloco zero de lo contrario coloco 1
    if (isset($_POST["USUA_PERM_FIRMA"])) {
      #Esta chekeado
      $record["USUA_PERM_FIRMA"] = 1;
    } else {
      #NO esta chekeado
      $record["USUA_PERM_FIRMA"] = 0;
    }
    $record["USUA_PERM_NOTIFICA"] = $notificar;
    #Obtengo y guardo la fecha
    $usua_ano = $_POST["usua_ano"];
    $usua_mes = $_POST["usua_mes"];
    $usua_dia = $_POST["usua_dia"];

    $fechaNacimiento = "" . $usua_ano . "-" . substr("0$usua_mes", -2) . "-" . substr("0$usua_dia", -2) . "";
    if ($fechaNacimiento == '0-00-00') {
      $fechaNacimiento = null;
    }
    $record["USUA_DOC"] = "$usua_doc";
    if (trim($usua_email)) {
      $record["USUA_EMAIL"] = "'" . $usua_email . "'";
    }
    $usua_dia = substr("0$usua_dia", -2);

    $record["USUA_NACIM"] = $db->conn->DBDate($fechaNacimiento);
    if (trim($usua_piso)) {
      $record["USUA_PISO"] = "'" . $usua_piso . "'";
    }
    if (trim($usua_ext)) {
      $record["USUA_EXT"] = "'" . $usua_ext . "'";
    }
    if (trim($usua_at)) {
      $record["USUA_AT"] = "'" . $usua_at . "'";
    }
    $record1["USUA_LOGIN"] = "'" . $krd . "'";
    $db->update("USUARIO", $record, $record1);
    $db->conn->CommitTrans();

    #Ddespues de insertado el registro y con el usua_codi, actualizo en la base de datos la firma digital
    #echo "subi firmas"; exit;

    include 'subirfirmas.php';
  ?>

    <TABLE BORDER=0 WIDTH=100%>
      <TR>
        <TD class="etextomenu">
          <center><B>Los datos han sido guardados, Por favor ingrese de modo normal al sistema.</center>
        </TD>
      </TR>
    </TABLE>
  <?
  } else {
  ?>
    <div class="container mt-3">
      <div class="alert alert-warning border-start border-4 border-warning shadow-sm d-flex align-items-center" role="alert">
        <i class="fa fa-exclamation-triangle fs-4 me-3 text-warning"></i>

        <div class="w-100 text-center">
          <span class="fw-bold text-dark">
            Todos los datos deben ser grabados correctamente.
            <span class="d-block d-md-inline text-danger-emphasis">
              De lo contrario no podrá seguir navegando por el sistema.
            </span>
          </span>
        </div>
      </div>
    </div>
  <?
  }
  ?>
</body>