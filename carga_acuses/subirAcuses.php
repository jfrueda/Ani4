<?php
session_start();
$dependencia    = $_SESSION["dependencia"];
$codusuario     = $_SESSION["codusuario"];
$pathDir        = $_SESSION["dirOrfeo"];

//var_dump($dependencia, $codusuario, $pathDir); 
require_once './../include/db/ConnectionHandler.php';

$bd = new ConnectionHandler("./../");
$sql = "SELECT DISTINCT RTRIM(cast(SGD_FENV_CODIGO as varchar))|| '-' || SGD_FENV_DESCRIP as NOMBRE,
                SGD_FENV_CODIGO
            FROM SGD_FENV_FRMENVIO sff
            WHERE sff.SGD_FENV_CODIGO IN (25, 103, 105, 106) ORDER BY SGD_FENV_CODIGO ASC";
$rs = $bd->conn->execute($sql);

if (!$ruta_raiz) {
  $ruta_raiz = "..";
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Formulario Radicado</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= $ruta_raiz ?>/estilos/custom.css">
  <style>
    .swal2-confirm.custom-consultar {
      background-color: #6c757d;
      /* Verde */
      color: white;
      /* Color del texto */
      border: none;
      /* Elimina el borde */
      padding: 10px 20px;
      /* Ajusta el padding */
      border-radius: 5px;
      /* Bordes redondeados */
    }

    .swal2-confirm.custom-consultar:hover {
      background-color: #5a6268;
      /* Color del botón al pasar el ratón */
    }

    #circle {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      width: 150px;
      height: 150px;
    }

    .loader {
      width: calc(100% - 0px);
      height: calc(100% - 0px);
      border: 8px solid #162534;
      border-top: 8px solid #09f;
      border-radius: 50%;
      animation: rotate 5s linear infinite;
    }

    @keyframes rotate {
      100% {
        transform: rotate(360deg);
      }
    }
  </style>
</head>

<label id="dependencia" hidden><?= $dependencia ?></label>
<label id="coduser" hidden><?= $codusuario ?></label>
<label id="path" hidden><?= $pathDir ?></label>

<body class="bg-light">
  <!--LOADER -->
  <div id="circle" hidden>
    <div class="loader">
      <div class="loader">
        <div class="loader">
          <div class="loader">
          </div>
        </div>
      </div>
    </div>
    <div class="mt-2 text-center">
      <p><b>Cargando Datos...</b></p>
    </div>
  </div>
  <!--------------------------------------->
  <div class="container-fluid" id="frm">
    <div class="card shadow-sm">
      <div class="card-header bg-orfeo text-white">
        <h3 class="mb-0">CARGUE DE ACUSES A LOS RADICADOS</h3>
      </div>
      <div class="card-body p-5">
        <form action="#" method="post" enctype="multipart/form-data">
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="radicado" class="form-label"><b>Número de Radicado</b></label>
              <input type="text" id="radicado" name="radicado" class="form-control" maxlength="17">
              <div class="alert alert-danger mt-1" role="alert" id="error-rad" hidden>
              </div>
            </div>
            <div class="col-md-6 mb-3">
              <label for="fecha" class="form-label"><b>Fecha de Correo</b></label>
              <input type="date" id="fecha" name="fecha" class="form-control">
              <div class="alert alert-danger mt-1" role="alert" id="error-fech" hidden>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="hora" class="form-label"><b>Hora</b></label>
              <input type="time" id="hora" name="hora" class="form-control">
              <div class="alert alert-danger mt-1" role="alert" id="error-hor" hidden></div>
            </div>
            <div class="col-md-6 mb-3">
              <label for="correo" class="form-label"><b>Correo Electrónico</b></label>
              <div class="input-group">
                <input type="email" id="correo" name="correo" class="form-control" style="height: 40px;">
              </div>
              <div class="alert alert-danger mt-1" role="alert" id="error-mail" hidden></div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="documento" class="form-label"><b>Cargar Documento</b></label>
              <div><input type="file" class="form-control" aria-label="file example" accept="application/pdf" id="documento"></div>
              <div class="alert alert-danger mt-1" role="alert" id="error-file" hidden></div>
            </div>

            <div class="col-md-6 mb-3">
              <label for="tipoEnvio" class="form-label"><b>Tipo de envío</b></label>
              <select id="tipoEnvio" class="form-select">
                <option value="0" selected>Seleccione tipo de envío para el cargue de los acuses</option>
                <?php foreach ($rs as $value): ?>
                  <option value="<?= $value['SGD_FENV_CODIGO'] ?>"><?= $value['NOMBRE'] ?></option>
                <?php endforeach; ?>
              </select>
              <div class="alert alert-danger mt-1" role="alert" id="error-tpenv" hidden></div>
            </div>
          </div>
          <button type="submit" id="sendData" class="btn btn-primary w-100 mt-3 text-center">Enviar</button>
        </form>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script type="text/javascript" src="./actions.js"></script>
  <!-- Inicialización de Tooltips -->
  <script>
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl)
    })
  </script>
</body>

</html>