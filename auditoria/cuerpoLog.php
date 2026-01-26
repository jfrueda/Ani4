<?php
session_start();
$ruta_raiz = "..";
include_once "$ruta_raiz/processConfig.php";

if (!$_SESSION['dependencia'])
    header ("Location: $ruta_raiz/cerrar_session.php");

foreach ($_GET as $key => $valor)   ${$key} = $valor;
foreach ($_POST as $key => $valor)   ${$key} = $valor;

define('ADODB_ASSOC_CASE', 2);
$verrad         = "";
$krd            = $_SESSION["krd"];
$dependencia    = $_SESSION["dependencia"];
$usua_doc       = $_SESSION["usua_doc"];
$codusuario     = $_SESSION["codusuario"];
$verrad         = "";

function mostrar_json($jsonString) {
    $data = json_decode($jsonString, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        // Si no es un JSON válido, simplemente mostrar el texto original
        return htmlspecialchars($jsonString);
    }

    $html = "<div class='json-format'>";
    foreach ($data as $key => $value) {
        if (is_array($value)) {
            $html .= "<strong>$key:</strong> [" . implode(', ', array_map(function($v) {
                return '"' . htmlspecialchars($v) . '"';
            }, $value)) . "]<br>";
        } else {
            $html .= "<strong>$key:</strong> " . htmlspecialchars($value) . "<br>";
        }
    }
    $html .= "</div>";

    return $html;
}

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <?php include_once "$ruta_raiz/htmlheader.inc.php"; ?>
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
  <!-- Select2 for searchable selects -->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</head>
  <style>
    .json-scroll {
      max-height: 120px;
      min-height: 40px;
      max-width: 280px;
      min-width: 200px;
      overflow: auto;
      white-space: pre-wrap;
      word-wrap: break-word;
      font-family: 'Courier New', monospace;
      font-size: 10px;
      line-height: 1.2;
      background-color: #f8f9fa;
      border: 1px solid #dee2e6;
      border-radius: 4px;
      padding: 6px;
      margin: 0;
      resize: vertical;
    }
    .json-scroll::-webkit-scrollbar {
      width: 6px;
      height: 6px;
    }
    .json-scroll::-webkit-scrollbar-track {
      background: #f1f1f1;
      border-radius: 3px;
    }
    .json-scroll::-webkit-scrollbar-thumb {
      background: #888;
      border-radius: 3px;
    }
    .json-scroll::-webkit-scrollbar-thumb:hover {
      background: #555;
    }
    .json-scroll:focus {
      outline: 2px solid #007bff;
      outline-offset: 2px;
    }
    /* DataTable responsive adjustments */
    .dataTables_scrollBody table {
      margin: 0 !important;
    }
  </style>
<?

include_once "$ruta_raiz/include/db/ConnectionHandler.php";
require_once("$ruta_raiz/class_control/Mensaje.php");
if (!$db) $db = new ConnectionHandler($ruta_raiz);

$db->conn->SetFetchMode(ADODB_FETCH_ASSOC);
$objMensaje= new Mensaje($db);
$mesajes = $objMensaje->getMsgsUsr($_SESSION['usua_doc'],$_SESSION['dependencia']);

$nomcarpeta = "Reporte de Log";
include "./envios/paEncabeza.php";
?>
<body onLoad="window_onload();" class="waitMe_body" >
    <div class="waitMe_container img" style="background:rgba(255,255,255,0.7)"> 
      <div style="background:url('../bodega/sys_img/logo.png') no-repeat center center; background-size: contain; width: 256px; height: 256px; position: absolute; left: 50%; top: 50%; margin: -128px 0 0 -128px;"></div>
    </div>
<script src="https://unpkg.com/notie"></script>
<?
   

if ($swLog==1)
	echo ($mesajes);
	  if(trim($orderTipo)=="") $orderTipo="DESC";
  if($orden_cambio==1){
	  if(trim($orderTipo)!="DESC"){
		   $orderTipo="DESC";
		}else{
			$orderTipo="ASC";
		}
	}

	if(!$carpeta) $carpeta=0;
    // Valores por defecto y sanitización básica
    $hoy = date('Y-m-d');
    $porDefectoIni = date('Y-m-d', strtotime('-30 days'));
    // Capturar filtros del request
    $busqFechaInicial = isset($busqFechaInicial) && $busqFechaInicial ? $busqFechaInicial : $porDefectoIni;
    $busqFechaFinal   = isset($busqFechaFinal) && $busqFechaFinal ? $busqFechaFinal   : $hoy;
    $busqUsuario      = isset($busqUsuario) ? trim($busqUsuario) : '';
    $busqModelo       = isset($busqModelo) ? trim($busqModelo) : '';
    $busqTransaccion  = isset($busqTransaccion) ? trim($busqTransaccion) : '';

    // Construcción del WHERE con filtros opcionales
    $whereLog = "1=1";
    // Validación de fechas simples (formato YYYY-MM-DD)
    $fechaIniValida = preg_match('/^\d{4}-\d{2}-\d{2}$/', $busqFechaInicial);
    $fechaFinValida = preg_match('/^\d{4}-\d{2}-\d{2}$/', $busqFechaFinal);
    if ($fechaIniValida && $fechaFinValida) {
        $whereLog .= " AND l.fecha BETWEEN '".addslashes($busqFechaInicial)." 00:00:00' AND '".addslashes($busqFechaFinal)." 23:59:59'";
    }
  // Filtro por usuario (nombre)
  if ($busqUsuario !== '') {
    $whereLog  .= " AND us.USUA_NOMB = ".$db->conn->qstr($busqUsuario);
  }
  // Filtro por modelo
  if ($busqModelo !== '') {
    $whereLog  .= " AND l.modelo = ".$db->conn->qstr($busqModelo);
  }
  // Filtro por transacción
  if ($busqTransaccion !== '') {
    $whereLog  .= " AND l.transaccion = ".$db->conn->qstr($busqTransaccion);
  }
   $encabezado = "".session_name()."=".session_id();
   $linkPagina = "$PHP_SELF?$encabezado&orderTipo=$orderTipo&orderNo=$orderNo";

   // Listas con cache Redis (similar a cuerpoAuditoria)
   try {
     require dirname(__DIR__, 1)."/vendor/autoload.php";
     $client = new Predis\Client(array(
       'scheme' => 'tcp',
       'host'   => $redis_host??'redis',
       'port'   => 6379,
     ));
   } catch (Exception $e) {
     $client = null;
   }

   // Usuarios
   $usuarios = [];
   if ($client) {
     $cacheKeyUsuarios = 'usuarios';
     $cached = $client->get($cacheKeyUsuarios);
     if ($cached) {
       $usuarios = @unserialize($cached) ?: [];
     } else {
       $usuarios = $db->conn->GetAll("SELECT DISTINCT usua_nomb FROM public.usuario ORDER BY usua_nomb ASC");
       $client->set($cacheKeyUsuarios, serialize($usuarios), 'EX', 28800);
     }
   } else {
     $usuarios = $db->conn->GetAll("SELECT DISTINCT usua_nomb FROM public.usuario ORDER BY usua_nomb ASC");
   }

   // Modelos
   $modelos = [];
   if ($client) {
     $cacheKeyModelos = 'log_modelos';
     $cached = $client->get($cacheKeyModelos);
     if ($cached) {
       $modelos = @unserialize($cached) ?: [];
     } else {
       $modelos = $db->conn->GetAll("SELECT DISTINCT modelo FROM public.log WHERE modelo IS NOT NULL AND modelo <> '' ORDER BY modelo ASC");
       $client->set($cacheKeyModelos, serialize($modelos), 'EX', 28800);
     }
   } else {
     $modelos = $db->conn->GetAll("SELECT DISTINCT modelo FROM public.log WHERE modelo IS NOT NULL AND modelo <> '' ORDER BY modelo ASC");
   }

   // Transacciones
   $transacciones = [];
   if ($client) {
     $cacheKeyTrans = 'log_transacciones';
     $cached = $client->get($cacheKeyTrans);
     if ($cached) {
       $transacciones = @unserialize($cached) ?: [];
     } else {
       $transacciones = $db->conn->GetAll("SELECT DISTINCT transaccion FROM public.log WHERE transaccion IS NOT NULL AND transaccion <> '' ORDER BY transaccion ASC");
       $client->set($cacheKeyTrans, serialize($transacciones), 'EX', 28800);
     }
   } else {
     $transacciones = $db->conn->GetAll("SELECT DISTINCT transaccion FROM public.log WHERE transaccion IS NOT NULL AND transaccion <> '' ORDER BY transaccion ASC");
   }
 
?>
<div class="col-sm-12"> <!-- widget grid -->
  <section>
      <!-- row -->
      <div class="row">
      <!-- NEW WIDGET START -->
      <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <!-- Widget ID (each widget will need unique ID)-->
        <div class="well" data-widget-editbutton="false">
          <!-- widget content -->
          <div class="widget-body" class="smart-form">
            <h2 class="text-center">Informe auditoría - Log del sistema</h2>
            <form name="form_busq_log" id="form_busq_log" class="form-inline" action="<?=$_SERVER['PHP_SELF']?>?<?=$encabezado?>" method="post">
              <div style="display:flex; flex-wrap:wrap; gap:12px; align-items:flex-end;">
                <div>
                  <label>Usuario</label><br>
                  <select style="width: 240px;" name="busqUsuario" id="busqUsuario" class="input select2">
                    <option value="">Todos</option>
                    <?php foreach($usuarios as $u){
                        $val = isset($u['USUA_NOMB']) ? $u['USUA_NOMB'] : (isset($u['usua_nomb']) ? $u['usua_nomb'] : '');
                        if($val==='') continue;
                        $sel = (strcasecmp($busqUsuario, $val)===0) ? 'selected' : '';
                        echo "<option value='".htmlspecialchars($val,ENT_QUOTES)."' $sel>".htmlspecialchars($val)."</option>";
                      } ?>
                  </select>
                </div>
                <div>
                  <label>Modelo</label><br>
                  <select style="width: 220px;" name="busqModelo" id="busqModelo" class="input select2">
                    <option value="">Todos</option>
                    <?php foreach($modelos as $m){
                        $val = isset($m['MODELO']) ? $m['MODELO'] : (isset($m['modelo']) ? $m['modelo'] : '');
                        if($val==='') continue;
                        $sel = (strcasecmp($busqModelo, $val)===0) ? 'selected' : '';
                        echo "<option value='".htmlspecialchars($val,ENT_QUOTES)."' $sel>".htmlspecialchars($val)."</option>";
                      } ?>
                  </select>
                </div>
                <div>
                  <label>Transacción</label><br>
                  <select style="width: 240px;" name="busqTransaccion" id="busqTransaccion" class="input select2">
                    <option value="">Todas</option>
                    <?php foreach($transacciones as $t){
                        $val = isset($t['TRANSACCION']) ? $t['TRANSACCION'] : (isset($t['transaccion']) ? $t['transaccion'] : '');
                        if($val==='') continue;
                        $sel = (strcasecmp($busqTransaccion, $val)===0) ? 'selected' : '';
                        echo "<option value='".htmlspecialchars($val,ENT_QUOTES)."' $sel>".htmlspecialchars($val)."</option>";
                      } ?>
                  </select>
                </div>
                <div>
                  <label>Fecha inicial</label><br>
                  <input name="busqFechaInicial" required class="input" type="date" value="<?=htmlspecialchars($busqFechaInicial)?>">
                </div>
                <div>
                  <label>Fecha final</label><br>
                  <input name="busqFechaFinal" required class="input" type="date" value="<?=htmlspecialchars($busqFechaFinal)?>">
                </div>
                <div>
                  <label>Buscar en Modelo Afectado</label><br>
                  <input name="busqModeloAfectado" id="busqModeloAfectado" class="input" type="text" placeholder="Buscar en campos del modelo..." value="<?=htmlspecialchars($busqModeloAfectado ?? '')?>">
                </div>
                <div>
                  <button type="submit" name="Buscar" id="Buscar" class="btn btn-primary btn-sm">Buscar</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </article>
    </div>
  </section>
</div>

<div class="col-sm-12"> <!-- widget grid -->
<section>
<!-- row -->
<div class="row">
<!-- NEW WIDGET START -->
<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
<!-- Widget ID (each widget will need unique ID)-->
<div class="jarviswidget jarviswidget-color-darken" data-widget-editbutton="false">
<!-- widget div-->
<div>
  <!-- widget content -->
  <div class="widget-body">
    <div class="table-responsive">

  <?
  $currentPage = isset($_GET['adodb_next_page']) ? (int)$_GET['adodb_next_page'] : 1;
  $perPage = 100;
  $offset = ($currentPage - 1) * $perPage;
  $controlAgenda=1;
	if($carpeta==11 and !$tipo_carp and $codusuario!=1){
	}else
	{
  //include "./tx/txOrfeo.php";
	}
	/*  GENERACION LISTADO DE LOGS
	 *  Aqui utilizamos la clase adodb para generar el listado de los logs
	 */
	
        
        include "$ruta_raiz/include/query/queryLog.php";
        if($busqFechaInicial>$busqFechaFinal){
          echo "<script>notie.alert({ type: 'error', text: 'Fechas invalidas' })</script>";
          $error = 1;
        }
        
        // Always execute query since we have default dates
        $rs=$db->conn->Execute($isql);
        if ($rs->EOF)  {
          echo "<hr><center><b><span class='alarmas'>No se encuentra ningun registro con el criterio de busqueda</span></center></b></hr>";
        }
        else{
          // Output table for DataTable
          echo '<table id="logTable" class="display" style="width:100%">';
          echo '<thead>';
          echo '<tr>';
          echo '<th>ID</th>';
          echo '<th>MODELO</th>';
          echo '<th>TRANSACCIÓN</th>';
          echo '<th>REGISTRO ANTES</th>';
          echo '<th>REGISTRO DESPUÉS</th>';
          echo '<th>MODELO AFECTADO</th>';
          echo '<th>USUARIO NOMBRE</th>';
          echo '<th>FECHA</th>';
          echo '</tr>';
          echo '</thead>';
          echo '<tbody>';
          
          while (!$rs->EOF) {
            // Procesar modelo afectado: consultar la tabla del modelo con el ID del JSON
            $modeloAfectado = '';
            $modelo = strtoupper($rs->fields['MODELO']);
            $registroDespues = $rs->fields['REGISTRO_DESPUES'];
            if (!empty($registroDespues) && !empty($modelo)) {
              $data = json_decode($registroDespues, true);
              if (is_array($data) && isset($data['id'])) {
                $id = $data['id'];
                $sqlModelo = "SELECT * FROM   $modelo WHERE ID = " . $db->conn->qstr($id) . " LIMIT 1";
                $rsModelo = $db->conn->Execute($sqlModelo);
                if (!$rsModelo->EOF) {
                  $lines = [];
                  foreach ($rsModelo->fields as $key => $value) {
                    $lines[] = $key . ': ' . (is_array($value) ? json_encode($value) : $value);
                  }
                  $modeloAfectado = implode("\n", $lines);
                } else {
                  $modeloAfectado = 'Registro no encontrado';
                }
              } else {
                $modeloAfectado = 'ID no encontrado en JSON';
              }
            }
            
            echo '<tr>';
            echo '<td>' . $rs->fields['ID'] . '</td>';
            echo '<td>' . $rs->fields['MODELO'] . '</td>';
            echo '<td>' . $rs->fields['TRANSACCION'] . '</td>';
            echo '<td><div class="json-scroll">' . mostrar_json($rs->fields['REGISTRO_ANTES']) . '</div></td>';
            echo '<td><div class="json-scroll">' . mostrar_json($rs->fields['REGISTRO_DESPUES']) . '</div></td>';
            echo '<td><textarea class="json-scroll" readonly>' . htmlspecialchars($modeloAfectado) . '</textarea></td>';
            echo '<td>' . $rs->fields['USUARIO_NOMBRE'] . '</td>';
            echo '<td>' . $rs->fields['FECHA'] . '</td>';
            echo '</tr>';
            $rs->MoveNext();
          }
          
          echo '</tbody>';
          echo '</table>';
        }


        ?>
        </div>

      </div>
    </div>
  </div>

</article>
</div>
</section>
</div>
</body>
 <script>
      // Título para exportaciones basado en filtros actuales
      var exportTitle = 'Log del sistema: <?=htmlspecialchars($busqFechaInicial)?> a <?=htmlspecialchars($busqFechaFinal)?>' +
        <?php
          $extra = [];
          if(!empty($busqUsuario)) $extra[] = "Usuario: ".$busqUsuario;
          if(!empty($busqModelo)) $extra[] = "Modelo: ".$busqModelo;
          if(!empty($busqTransaccion)) $extra[] = "Transacción: ".$busqTransaccion;
          $extraTitle = count($extra) ? " (".implode(' | ', $extra).")" : '';
        ?>
        '<?=htmlspecialchars($extraTitle)?>';

      // Utilidad para limpiar HTML al exportar (quitar divs y etiquetas)
      function stripHtml(data) {
        if (typeof data !== 'string') return data;
        return data.replace(/<[^>]*>/g, '')
                   .replace(/\n+/g, '\n')
                   .trim();
      }

      $(document).ready(function() {
        // Habilitar búsqueda en selects
        $('.select2').select2({
          width: 'resolve',
          allowClear: true,
          placeholder: function(){
            return $(this).find('option:first').text() || '';
          }
        });

        // Inicializar DataTable (simple, sin filtros por columna; filtros se hacen en el servidor)
        var table = $('#logTable').DataTable({
          dom: 'Bfrtip',
          buttons: [
            {
              extend: 'excelHtml5',
              title: exportTitle,
              filename: 'log_sistema_<?=date('Ymd_His')?>',
              bom: true,
              exportOptions: {
                columns: [0,1,2,3,4,5,6,7],
                format: {
                  body: function(data, row, column) {
                    if (column === 3 || column === 4 || column === 5) { // JSON antes/después y modelo afectado
                      return stripHtml(data);
                    }
                    return stripHtml(data);
                  }
                }
              }
            },
            {
              extend: 'pdfHtml5',
              title: exportTitle,
              filename: 'log_sistema_<?=date('Ymd_His')?>',
              orientation: 'landscape',
              pageSize: 'A4',
              exportOptions: {
                columns: [0,1,2,3,4,5,6,7],
                format: {
                  body: function(data, row, column) {
                    if (column === 3 || column === 4 || column === 5) {
                      return stripHtml(data);
                    }
                    return stripHtml(data);
                  }
                }
              }
            }
          ],
          "pageLength": 25,
          "lengthMenu": [10, 25, 50, 100],
          "scrollX": true,
          "autoWidth": false,
          "language": {
            "lengthMenu": "Mostrar _MENU_ registros por página",
            "zeroRecords": "No se encontraron registros",
            "info": "Mostrando página _PAGE_ de _PAGES_",
            "infoEmpty": "No hay registros disponibles",
            "infoFiltered": "(filtrado de _MAX_ registros totales)",
            "search": "Buscar:",
            "paginate": {
              "first": "Primero",
              "last": "Último",
              "next": "Siguiente",
              "previous": "Anterior"
            }
          },
          "columnDefs": [
            {
              "targets": [3, 4, 5], // REGISTRO ANTES, DESPUÉS and MODELO AFECTADO columns
              "orderable": false,
              "searchable": true,
              "width": "250px"
            },
            {
              "targets": [0], // ID column
              "width": "80px"
            },
            {
              "targets": [1, 2, 6], // MODELO, TRANSACCIÓN, USUARIO NOMBRE columns
              "width": "120px"
            },
            {
              "targets": [7], // FECHA column
              "width": "150px"
            }
          ]
        });

        // Buscador en Modelo Afectado
        $('#busqModeloAfectado').on('keyup', function() {
          table.column(5).search(this.value).draw();
        });
      });

  </script>
</html>