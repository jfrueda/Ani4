<?php

$ruta_raiz = "../";
//error_reporting(E_ALL);
session_start();
require_once($ruta_raiz . "include/db/ConnectionHandler.php");
include($ruta_raiz . "processConfig.php");

if (!$db) {
  $db = new ConnectionHandler($ruta_raiz);
}

$db->conn->SetFetchMode(ADODB_FETCH_ASSOC);

foreach ($_GET as $key => $valor)   ${$key} = $valor;
foreach ($_POST as $key => $valor)   ${$key} = $valor;

include("common.php");
$fechah = date("ymd") . "_" . time("hms");

$params = session_name() . "=" . session_id() . "&krd=$krd";
$url = $supercorws;
$ws_user = parse_url($url, PHP_URL_USER);
$ws_pass = parse_url($url, PHP_URL_PASS);

$mimetypes = [
  [
    "Extension" => ".aac",
    "Kind of document" => "AAC audio",
    "MIME Type" => "audio/aac"
  ],
  [
    "Extension" => ".abw",
    "Kind of document" => "AbiWord document",
    "MIME Type" => "application/x-abiword"
  ],
  [
    "Extension" => ".arc",
    "Kind of document" => "Archive document (multiple files embedded)",
    "MIME Type" => "application/x-freearc"
  ],
  [
    "Extension" => ".avi",
    "Kind of document" => "AVI: Audio Video Interleave",
    "MIME Type" => "video/x-msvideo"
  ],
  [
    "Extension" => ".azw",
    "Kind of document" => "Amazon Kindle eBook format",
    "MIME Type" => "application/vnd.amazon.ebook"
  ],
  [
    "Extension" => ".bin",
    "Kind of document" => "Any kind of binary data",
    "MIME Type" => "application/octet-stream"
  ],
  [
    "Extension" => ".bmp",
    "Kind of document" => "Windows OS/2 Bitmap Graphics",
    "MIME Type" => "image/bmp"
  ],
  [
    "Extension" => ".bz",
    "Kind of document" => "BZip archive",
    "MIME Type" => "application/x-bzip"
  ],
  [
    "Extension" => ".bz2",
    "Kind of document" => "BZip2 archive",
    "MIME Type" => "application/x-bzip2"
  ],
  [
    "Extension" => ".csh",
    "Kind of document" => "C-Shell script",
    "MIME Type" => "application/x-csh"
  ],
  [
    "Extension" => ".css",
    "Kind of document" => "Cascading Style Sheets (CSS)",
    "MIME Type" => "text/css"
  ],
  [
    "Extension" => ".csv",
    "Kind of document" => "Comma-separated values (CSV)",
    "MIME Type" => "text/csv"
  ],
  [
    "Extension" => ".doc",
    "Kind of document" => "Microsoft Word",
    "MIME Type" => "application/msword"
  ],
  [
    "Extension" => ".docx",
    "Kind of document" => "Microsoft Word (OpenXML)",
    "MIME Type" => "application/vnd.openxmlformats-officedocument.wordprocessingml.document"
  ],
  [
    "Extension" => ".eot",
    "Kind of document" => "MS Embedded OpenType fonts",
    "MIME Type" => "application/vnd.ms-fontobject"
  ],
  [
    "Extension" => ".epub",
    "Kind of document" => "Electronic publication (EPUB)",
    "MIME Type" => "application/epub+zip"
  ],
  [
    "Extension" => ".gz",
    "Kind of document" => "GZip Compressed Archive",
    "MIME Type" => "application/gzip"
  ],
  [
    "Extension" => ".gif",
    "Kind of document" => "Graphics Interchange Format (GIF)",
    "MIME Type" => "image/gif"
  ],
  [
    "Extension" => ".htm\n     .html",
    "Kind of document" => "HyperText Markup Language (HTML)",
    "MIME Type" => "text/html"
  ],
  [
    "Extension" => ".ico",
    "Kind of document" => "Icon format",
    "MIME Type" => "image/vnd.microsoft.icon"
  ],
  [
    "Extension" => ".ics",
    "Kind of document" => "iCalendar format",
    "MIME Type" => "text/calendar"
  ],
  [
    "Extension" => ".jar",
    "Kind of document" => "Java Archive (JAR)",
    "MIME Type" => "application/java-archive"
  ],
  [
    "Extension" => ".jpeg\n     .jpg",
    "Kind of document" => "JPEG images",
    "MIME Type" => "image/jpeg"
  ],
  [
    "Extension" => ".js",
    "Kind of document" => "JavaScript",
    "MIME Type" => "text/javascript, per the following specifications:            https://html.spec.whatwg.org/multipage/#scriptingLanguages      https://html.spec.whatwg.org/multipage/#dependencies:willful-violation      https://datatracker.ietf.org/doc/draft-ietf-dispatch-javascript-mjs/"
  ],
  [
    "Extension" => ".json",
    "Kind of document" => "JSON format",
    "MIME Type" => "application/json"
  ],
  [
    "Extension" => ".jsonld",
    "Kind of document" => "JSON-LD format",
    "MIME Type" => "application/ld+json"
  ],
  [
    "Extension" => ".mid\n     .midi",
    "Kind of document" => "Musical Instrument Digital Interface (MIDI)",
    "MIME Type" => "audio/midi audio/x-midi"
  ],
  [
    "Extension" => ".mjs",
    "Kind of document" => "JavaScript module",
    "MIME Type" => "text/javascript"
  ],
  [
    "Extension" => ".mp3",
    "Kind of document" => "MP3 audio",
    "MIME Type" => "audio/mpeg"
  ],
  [
    "Extension" => ".cda",
    "Kind of document" => "CD audio",
    "MIME Type" => "application/x-cdf"
  ],
  [
    "Extension" => ".mp4",
    "Kind of document" => "MP4 audio",
    "MIME Type" => "video/mp4"
  ],
  [
    "Extension" => ".mpeg",
    "Kind of document" => "MPEG Video",
    "MIME Type" => "video/mpeg"
  ],
  [
    "Extension" => ".mpkg",
    "Kind of document" => "Apple Installer Package",
    "MIME Type" => "application/vnd.apple.installer+xml"
  ],
  [
    "Extension" => ".odp",
    "Kind of document" => "OpenDocument presentation document",
    "MIME Type" => "application/vnd.oasis.opendocument.presentation"
  ],
  [
    "Extension" => ".ods",
    "Kind of document" => "OpenDocument spreadsheet document",
    "MIME Type" => "application/vnd.oasis.opendocument.spreadsheet"
  ],
  [
    "Extension" => ".odt",
    "Kind of document" => "OpenDocument text document",
    "MIME Type" => "application/vnd.oasis.opendocument.text"
  ],
  [
    "Extension" => ".oga",
    "Kind of document" => "OGG audio",
    "MIME Type" => "audio/ogg"
  ],
  [
    "Extension" => ".ogv",
    "Kind of document" => "OGG video",
    "MIME Type" => "video/ogg"
  ],
  [
    "Extension" => ".ogx",
    "Kind of document" => "OGG",
    "MIME Type" => "application/ogg"
  ],
  [
    "Extension" => ".opus",
    "Kind of document" => "Opus audio",
    "MIME Type" => "audio/opus"
  ],
  [
    "Extension" => ".otf",
    "Kind of document" => "OpenType font",
    "MIME Type" => "font/otf"
  ],
  [
    "Extension" => ".png",
    "Kind of document" => "Portable Network Graphics",
    "MIME Type" => "image/png"
  ],
  [
    "Extension" => ".pdf",
    "Kind of document" => "Adobe Portable Document Format (PDF)",
    "MIME Type" => "application/pdf"
  ],
  [
    "Extension" => ".php",
    "Kind of document" => "Hypertext Preprocessor (Personal Home Page)",
    "MIME Type" => "application/x-httpd-php"
  ],
  [
    "Extension" => ".ppt",
    "Kind of document" => "Microsoft PowerPoint",
    "MIME Type" => "application/vnd.ms-powerpoint"
  ],
  [
    "Extension" => ".pptx",
    "Kind of document" => "Microsoft PowerPoint (OpenXML)",
    "MIME Type" => "application/vnd.openxmlformats-officedocument.presentationml.presentation"
  ],
  [
    "Extension" => ".rar",
    "Kind of document" => "RAR archive",
    "MIME Type" => "application/vnd.rar"
  ],
  [
    "Extension" => ".rtf",
    "Kind of document" => "Rich Text Format (RTF)",
    "MIME Type" => "application/rtf"
  ],
  [
    "Extension" => ".sh",
    "Kind of document" => "Bourne shell script",
    "MIME Type" => "application/x-sh"
  ],
  [
    "Extension" => ".svg",
    "Kind of document" => "Scalable Vector Graphics (SVG)",
    "MIME Type" => "image/svg+xml"
  ],
  [
    "Extension" => ".swf",
    "Kind of document" => "Small web format (SWF) or Adobe Flash document",
    "MIME Type" => "application/x-shockwave-flash"
  ],
  [
    "Extension" => ".tar",
    "Kind of document" => "Tape Archive (TAR)",
    "MIME Type" => "application/x-tar"
  ],
  [
    "Extension" => ".tif\n     .tiff",
    "Kind of document" => "Tagged Image File Format (TIFF)",
    "MIME Type" => "image/tiff"
  ],
  [
    "Extension" => ".ts",
    "Kind of document" => "MPEG transport stream",
    "MIME Type" => "video/mp2t"
  ],
  [
    "Extension" => ".ttf",
    "Kind of document" => "TrueType Font",
    "MIME Type" => "font/ttf"
  ],
  [
    "Extension" => ".txt",
    "Kind of document" => "Text, (generally ASCII or ISO 8859-n)",
    "MIME Type" => "text/plain"
  ],
  [
    "Extension" => ".vsd",
    "Kind of document" => "Microsoft Visio",
    "MIME Type" => "application/vnd.visio"
  ],
  [
    "Extension" => ".wav",
    "Kind of document" => "Waveform Audio Format",
    "MIME Type" => "audio/wav"
  ],
  [
    "Extension" => ".weba",
    "Kind of document" => "WEBM audio",
    "MIME Type" => "audio/webm"
  ],
  [
    "Extension" => ".webm",
    "Kind of document" => "WEBM video",
    "MIME Type" => "video/webm"
  ],
  [
    "Extension" => ".webp",
    "Kind of document" => "WEBP image",
    "MIME Type" => "image/webp"
  ],
  [
    "Extension" => ".woff",
    "Kind of document" => "Web Open Font Format (WOFF)",
    "MIME Type" => "font/woff"
  ],
  [
    "Extension" => ".woff2",
    "Kind of document" => "Web Open Font Format (WOFF)",
    "MIME Type" => "font/woff2"
  ],
  [
    "Extension" => ".xhtml",
    "Kind of document" => "XHTML",
    "MIME Type" => "application/xhtml+xml"
  ],
  [
    "Extension" => ".xls",
    "Kind of document" => "Microsoft Excel",
    "MIME Type" => "application/vnd.ms-excel"
  ],
  [
    "Extension" => ".xlsx",
    "Kind of document" => "Microsoft Excel (OpenXML)",
    "MIME Type" => "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
  ],
  [
    "Extension" => ".xml",
    "Kind of document" => "XML",
    "MIME Type" => "application/xml if not readable from casual users (RFC 3023, section 3)\n     text/xml if readable from casual users (RFC 3023, section 3)"
  ],
  [
    "Extension" => ".xul",
    "Kind of document" => "XUL",
    "MIME Type" => "application/vnd.mozilla.xul+xml"
  ],
  [
    "Extension" => ".zip",
    "Kind of document" => "ZIP archive",
    "MIME Type" => "application/zip"
  ],
  [
    "Extension" => ".3gp",
    "Kind of document" => "3GPP audio/video container",
    "MIME Type" => "video/3gpp\n     audio/3gpp if it doesn't contain video"
  ],
  [
    "Extension" => ".3g2",
    "Kind of document" => "3GPP2 audio/video container",
    "MIME Type" => "video/3gpp2\n     audio/3gpp2 if it doesn't contain video"
  ],
  [
    "Extension" => ".7z",
    "Kind of document" => "7-zip archive",
    "MIME Type" => "application/x-7z-compressed"
  ]
];

function searchExtension($mime, $array)
{
  foreach ($array as $key => $val) {
    if ($val['MIME Type'] === $mime) {
      return $key;
    }
  }
  return null;
}

if (isset($_POST['a'])) {
  $client = new SoapClient($url, array(
    "trace" => 1,
    "exception" => 0,
    'login' => $ws_user,
    'password' => $ws_pass
  ));
  $p = [
    'validador' => "WS",
    'operador' => "WS-REST-TEST",
  ];

  switch ($_POST['a']) {
    case 'hist':
      $p['nurc'] = $_POST['r'];
      $result = $client->__soapCall('ConsultarHistorial', ['parameters' => $p]);
      $ret = $result->return;
      if (!is_array($result->return))
        $ret = [$result->return];
      $ret['comentario'] = $ret;
      break;
    case 'anex':
      $p['radicado'] = $_POST['r'];
      $result = $client->__soapCall('AnexosxRadicado', ['parameters' => $p]);
      $ret = $result->return;
      if (!is_array($result->return))
        $ret = [$result->return];
      foreach ($ret as $anexo) {
        $decoded = base64_decode($anexo->img_anexo);
        $file = $ruta_raiz . '/bodega/supercore/' . $anexo->img_nombreImagen;
        file_put_contents($file, $decoded);
        unset($anexo->img_anexo);
      }
      $ret['anexos'] = $ret;
      break;
    case 'cerrar':
      $p['nurc'] = $_POST['r'];
      $p['comentario'] = $_POST['c'];
      $p['cedulaResponsable'] = $_SESSION['usua_doc'];
      $result = $client->__soapCall('RegistrarCierre', ['parameters' => $p]);
      $ret['res'] = $result->return;
      break;
  }
  header("Content-Type: application/json; charset=UTF-8");
  echo json_encode($ret);
  exit;
}

if (!empty($_POST['Busqueda']) && ($_POST['Busqueda'] == "Busqueda")) {
  try {
    $client = new SoapClient($url, array("trace" => 1, "exception" => 0));
    $result = null;
    $t = substr($nume_radi, 0, 1);
    $tipo = $t == '2' ? 'ConsultaNumeroRadSalida' : 'ConsultaNumeroRadicacion';

    $p = [
      'validador' => "BPM",
      'operador' => "9999",
      'rad_Numero' => $nume_radi,
      'dependencia' => ''
    ];

    $result = $client->__soapCall($tipo, ['parameters' => $p]);

    $data = $client->__soapCall('ConsultarImagenRadicado', ['parameters' => $p]);

    if (!file_exists($ruta_raiz . '/bodega/supercore')) {
      mkdir($ruta_raiz . '/bodega/supercore', 0777, true);
    }

    if (strlen($data->return) > 10) {
      $decoded = base64_decode($data->return);
      $file = $ruta_raiz . '/bodega/supercore/' . $nume_radi;
      file_put_contents($file, $decoded);
      $ext = searchExtension(mime_content_type($file), $mimetypes);
      rename($file, $file . $mimetypes[$ext]['Extension']);
    } else {
      $p = [
        'validador' => "WS",
        'operador' => "WS-REST-TEST",
        'rad_Numero' => $nume_radi,
        'tipoConsulta' => '',
      ];
      $urlimg = $client->__soapCall('ConsultarImagenRuta', ['parameters' => $p]);
    }
  } catch (SoapFault $ex) {
    $error = 'Error: No se pudo conectar al servidor de supercor. ';
  } catch (Exception $ex) {
    $error = 'Error: ' . $ex->getMessage();
  }
}
?>
<!DOCTYPE html>
<html>

<head>
  <?php include_once "$ruta_raiz/htmlheader.inc.php"; ?>
  <title>Consultas Expedientes</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <script language="JavaScript" src="<?= $ruta_raiz ?>/js/formchek.js"></script>
</head>

<body>
  <div class="container-fluid">
    <div class="col-sm-12" id="app">
      <Transition name="slide-fade">
        <form v-if="showForm" action="supercor.php?<?= $params ?>" method="post" enctype="multipart/form-data" class="form-horizontal" name="formSeleccion" id="formSeleccion">
          <section id="widget-grid" class="mt-4">
            <div class="card shadow-sm border-0">
              <div class="card-header bg-orfeo text-white py-3">
                <h5 class="mb-0 fw-semibold">Búsqueda Supercor</h5>
              </div>

              <div class="card-body">
                <div class="row g-3">
                  <!-- Campo Radicado -->
                  <div class="col-12 col-md-6">
                    <label class="form-label fw-semibold">Radicado</label>
                    <input class="form-control" type="text" name="nume_radi" maxlength="17" value="<?= $nume_radi ?>" autocomplete="off" size="25">
                  </div>

                  <!-- Botones -->
                  <div class="col-12 mt-3">
                    <div class="d-flex gap-2">
                      <input id="limpiar" class="btn btn-outline-secondary px-4" value="Limpiar" type="button">
                      <input class="btn btn-primary px-4" name="Busqueda" type="submit" id="envia22" value="Busqueda">
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </section>
        </form>
      </Transition>

      <?php if ($error): ?>
        <div class="alert alert-danger my-2" role="alert">
          <?= $error ?>
        </div>
      <?php endif; ?>

      <?php if ($result) { ?>
        <div class="row">
          <section id="widget-grid">
            <div class="col-md-12">
              <article>
                <div class="jarviswidget jarviswidget-color-darken" id="wid-id-2" data-widget-editbutton="false">
                  <header>
                    <h2>
                      Resultados
                    </h2>
                  </header>
                  <div class="widget-body">
                    <style>
                      .jarviswidget-color-darken .nav-tabs li:not(.active) a {
                        color: grey !important;
                      }
                    </style>
                    <ul id="myTabs" class="nav nav-tabs nav-justified">
                      <li role="presentation" class="active"><a href="#informacion">Información</a></li>
                      <li role="presentation"><a href="#historico">Historico</a></li>
                      <li role="presentation"><a href="#anexos">Anexos</a></li>
                      <li role="presentation"><a href="#cerrar">Cerrar</a></li>
                    </ul>
                    <script>
                      var hist = anex = cerrar = false;
                      $('#myTabs a').click(function(e) {
                        e.preventDefault();
                        $(this).tab('show');
                        switch (e.target.hash) {
                          case '#informacion':
                            $('#envia22').click();
                            break;
                          case '#historico':
                            if (hist) break;
                            $.ajax({
                              method: 'POST',
                              data: {
                                a: 'hist',
                                r: '<?= $nume_radi ?>'
                              },
                              beforeSend: function() {
                                $('#loader').show();
                              },
                              complete: function() {
                                $('#loader').hide();
                              },
                            }).done(function(data, textStatus, jqXHR) {
                              for (e of data.comentario) {
                                if (e.respuesta) {
                                  console.log(e.respuesta);
                                  $('#historico').append(e.respuesta);
                                } else {
                                  $('#hist').append(`<tr>
                          <td>${e.fecha}</td>
                          <td>${e.radNumero}</td>
                          <td>${e.responsable}</td>
                          <td>${e.comentario}</td>
                          </tr>`);
                                  $('#hist').show();
                                }
                              }
                            }).fail(function(jqXHR, textStatus, errorThrown) {
                              console.log(textStatus + ': ' + errorThrown);
                            });
                            hist = true;
                            break;
                          case '#anexos':
                            if (anex) break;
                            $.ajax({
                              method: 'POST',
                              data: {
                                a: 'anex',
                                r: '<?= $nume_radi ?>'
                              },
                              beforeSend: function() {
                                $('#loader').show();
                              },
                              complete: function() {
                                console.log('fin anexos');
                                $('#loader').hide();
                              },
                            }).done(function(data, textStatus, jqXHR) {
                              for (e of data.anexos) {
                                if (e.mensajerespuesta) {
                                  $('#anexos').append(e.mensajerespuesta);
                                  return;
                                }
                                var ext = e.img_nombreImagen.toLowerCase().split('.').pop();
                                console.log(ext);
                                if (ext == 'pdf') {
                                  link = `
                            <a class="vinculos abrirVisor" href="javascript:void(0)"
                                link="../bodega/supercore/${e.img_nombreImagen}">
                                <img src="../img/icono_pdf.jpg" title="pdf" width="25">
                                ${e.img_nombreImagen}
                            </a>`;
                                } else {
                                  link = `
                            <a class="vinculos" href="../bodega/supercore/${e.img_nombreImagen}">
                                ${e.img_nombreImagen}
                            </a>`;
                                }
                                $('#anex').append(`<tr>
                        <td>${link}</td>
                        <td>${e.img_fecha}</td>
                        <td>${e.radicado}</td>
                        <td>${e.img_comentario}</td>
                        </tr>`);
                              }
                              $('#anex').show();
                              visor();
                            }).fail(function(jqXHR, textStatus, errorThrown) {
                              console.log(textStatus + ': ' + errorThrown);
                            });
                            anex = true;
                            break;
                        }
                      })
                    </script>

                    <!-- Tab panes -->
                    <div class="tab-content">
                      <div role="tabpanel" class="tab-pane active" id="informacion">
                        <br />

                        <div class="row">
                          <?php if (isset($result->return->codigoAccion) && $result->return->codigoAccion !== ' ') { ?>
                            <div class="col-md-12">
                              Sin resultados (<?= $result->return->codigoAccion ?>)
                              <?php if ($urlimg->return->img_anexo) echo "<a href='{$urlimg->return->img_anexo}' target='_blank'>Ver imagen</a>"; ?>
                            </div>
                          <?php } else { ?>
                            <?php
                            $fechaRadicacion = DateTime::createFromFormat('Y-m-d\TH:i:sP', $result->return->fechaRadicacionRadicado);
                            ?>
                            <div class="col-md-12">
                              <h4>Radicado Nº <?= $result->return->numeroRadicado ?>
                                <small>
                                  <?php if (strlen($data->return) > 10) { ?>
                                    <a href="supercord_fdl.php?<?= $params ?>&num=<?= $nume_radi ?>&ext=<?= $ext ?>"><i class="icon-download-alt"></i>DESCARGAR</a>
                                  <?php } ?>
                                  <?php if ($urlimg->return->img_anexo) echo "<a href='{$urlimg->return->img_anexo}' target='_blank'>Ver imagen</a>"; ?>
                                </small>
                              </h4><br>
                            </div>
                            <div class="col-md-12">
                              <label for=""><strong>Fecha radicación:</strong></label>
                              <p><small><?= $fechaRadicacion->format('Y-m-d H:i:s') ?>&nbsp;</small></p>
                            </div>
                            <div class="col-md-12">
                              <label for=""><strong>Dependencia remitente:</strong></label>
                              <p><small><?= $result->return->nombreDependenciaRemitente ?>&nbsp;</small></p>
                            </div>
                            <?php if ($t != '2') { ?>
                              <div class="col-md-12">
                                <label for=""><strong>Dependencia destino:</strong></label>
                                <p><small><?= $result->return->nombreDependenciaDestino ?>&nbsp;</small></p>
                              </div>
                            <?php } else { ?>
                              <div class="col-md-12">
                                <label for=""><strong>Entidad destino:</strong></label>
                                <p><small><?= $result->return->nombreEntidadDestino ?>&nbsp;</small></p>
                              </div>
                            <?php } ?>
                            <div class="col-md-12">
                              <label for=""><strong>Estado:</strong></label>
                              <p><small><?= $result->return->estadoRadicado ?>&nbsp;</small></p>
                            </div>
                            <div class="col-md-6">
                              <label for=""><strong>Remitente:</strong></label>
                              <p><small><?= $result->return->nombreRemitente ?>&nbsp;</small></p>
                            </div>
                            <div class="col-md-6">
                              <label for=""><strong>Destino:</strong></label>
                              <p><small><?= $result->return->nombreDestino ?>&nbsp;</small></p>
                            </div>
                            <div class="col-md-12">
                              <label for=""><strong>Asunto:</strong></label>
                              <p><small><?= $result->return->asuntoRadicado ?></small></p>
                            </div>
                            <div class="col-md-12">
                              <label for=""><strong>Observaciones:</strong></label>
                              <p><small><?= $result->return->observacionesRadicado ?>&nbsp;</small></p>
                            </div>
                            <?php if ($t != '2') { ?>
                              <div class="col-md-6">
                                <label for=""><strong>Cedula remitente:</strong></label>
                                <p><small><?= $result->return->cedulaRemitente ?>&nbsp;</small></p>
                              </div>
                              <div class="col-md-6">
                                <label for=""><strong>Cedula destino:</strong></label>
                                <p><small><?= $result->return->cedulaDestino ?>&nbsp;</small></p>
                              </div>
                            <?php } else { ?>
                              <div class="col-md-6">
                                <label for=""><strong>Dirección destino:</strong></label>
                                <p><small><?= $result->return->direccionDestino ?>&nbsp;</small></p>
                              </div>
                            <?php } ?>
                          <?php } ?>
                        </div>
                      </div>
                      <div role="tabpanel" class="tab-pane" id="historico">
                        <br />
                        <table id="hist" class="table table-striped table-hover table-bordered" width="100%" align="center" style="display:none">
                          <thead>
                            <tr class="pr2" align="center">
                              <th>FECHA</th>
                              <th>RADICADO</th>
                              <th>RESPONSABLE</th>
                              <th>COMENTARIO</th>
                            </tr>
                          </thead>
                        </table>
                      </div>
                      <div role="tabpanel" class="tab-pane" id="anexos">
                        <table id="anex" class="table" width="100%" align="center" style="display:none">
                          <br />
                          <thead>
                            <tr class="pr2">
                              <th width="20%">Documento</th>
                              <th width="20%">Fecha</th>
                              <th width="30%">Radicado</th>
                              <th width="30%">Comentarios</th>
                            </tr>
                          </thead>
                          <tbody>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                      <div role="tabpanel" class="tab-pane" id="cerrar">
                        <br>
                        <div id="finalizado" style="display:none" class="alert alert-info" role="alert">Finalizado</div>
                        <form id="frmcomentario" style="display:none">
                          <div class="form-group">
                            <label for="comentario" class="col-sm-2 control-label">Comentario</label>
                            <textarea name="comentario" class="form-control" id="comentario" placeholder="Escriba un Comentario" rows="3"></textarea>
                          </div>
                          <div class="form-group">
                            <input class="btn btn-primary" name="guardar" type="submit" id="guardar" value="Guardar">
                          </div>
                        </form>
                      </div>
                    </div>
                    <div id="loader" style="text-align:center;display:none"><img src="../img/ajax-loader.gif" width="70"></div>
                  </div>
                </div>
              </article>
            </div>
          </section>
        </div>
      <?php } ?>
    </div>
  </div>

  <div id='visor' style='display:none;position:fixed;padding:26px 30px 30px;top:0;left:0;right:0;bottom:0;z-index:2'>
    <button class='cerrarVisor' type='button' style='float:right; background-color:red;'><b>x</b></button>
    <iframe style='width:100%; height:100%; z-index:-2;background-color:#d5d5d5'></iframe>
  </div>

  <script>
    function visor() {
      $('#visor').dialog({
        autoOpen: false
      });
      $('.abrirVisor').on('click', function() {
        link = $(this).attr('link');
        if ($('#visor iframe').attr('src') != link) {
          $('#visor iframe').attr('src', 'about:blank');
          $('#visor iframe').attr('src', link);
        }
        $('#visor').dialog('open');
      });

      $('.cerrarVisor').on('click', function() {
        $('#visor').dialog('close');
      });
    }

    $(function() {

      if ('<?= $result->return->estadoRadicado ?>' == 'FINALIZADO') {
        $('#finalizado').show();
      } else {
        $('#frmcomentario').show();
      }
      $("#frmcomentario").submit(function(event) {
        console.log($("#comentario").val());
        $.ajax({
          type: "POST",
          data: {
            a: 'cerrar',
            r: '<?= $nume_radi ?>',
            c: $("#comentario").val()
          },
        }).done(function(data) {
          $('#frmcomentario').hide();
          $('#finalizado').show();
        });

        event.preventDefault();
      });

      $('#limpiar').click(function() {
        $(':input', '#formSeleccion')
          .not(':button, :submit, :reset, :hidden')
          .val('')
          .removeAttr('checked')
          .removeAttr('selected');
      });
    });
  </script>

  <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
  <script>
    const {
      createApp
    } = Vue;

    createApp({
      data() {
        return {
          showForm: false
        };
      },
      mounted() {
        this.showForm = true
      }
    }).mount('#app');
  </script>
</body>

</html>