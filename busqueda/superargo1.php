<?php

$ruta_raiz = "../";
session_start();
require_once($ruta_raiz."include/db/ConnectionHandler.php");
include($ruta_raiz."processConfig.php");

if (!$db){
    $db = new ConnectionHandler($ruta_raiz);
}

$db->conn->SetFetchMode(ADODB_FETCH_ASSOC);

list($driver1,$host1,$user1,$pass1,$dbname1) = explode(',',$superargo1);
$db1 = ADONewConnection($driver1);
$db1->Connect($host1,$user1,$pass1,$dbname1);

foreach ($_GET as $key => $valor)   ${$key} = $valor;
foreach ($_POST as $key => $valor)   ${$key} = $valor;

include ("common.php");
$fechah = date("ymd") . "_" . time("hms");

$params = session_name()."=".session_id()."&krd=$krd";
$url = $supercorws;
$ws_user = parse_url($url, PHP_URL_USER);
$ws_pass = parse_url($url, PHP_URL_PASS);

function searchExtension($mime, $array) {
    foreach ($array as $key => $val) {
        if ($val['MIME Type'] === $mime) {
            return $key;
        }
    }
    return null;
 }

if (isset($_POST['a'])) {
    switch ($_POST['a']) {
        case 'hist':
            $sql = <<<SQL
select d.depe_nomb, h.hist_fech, t.sgd_ttr_descrip, us.usua_nomb as us, h.hist_obse, ud.usua_nomb as ud
from hist_eventos h
left join dependencia d on d.depe_codi = h.depe_codi
left join sgd_ttr_transaccion t on t.sgd_ttr_codigo = h.sgd_ttr_codigo
left join usuario us on us.usua_codi = h.usua_codi and us.depe_codi = h.depe_codi
left join usuario ud on ud.usua_codi = h.usua_codi_dest and ud.depe_codi = h.depe_codi_dest
where h.radi_nume_radi='{$_GET['nume_radi']}'
order by h.hist_fech desc
SQL;
            $rs = $db1->Execute($sql);
            $ret['hist'] = $rs->GetRows();

            break;
        case 'anex':
            $sql = <<<SQL
select a.anex_codigo AS DOCU
,at.anex_tipo_ext AS EXT
,a.anex_tamano AS TAMA
,a.anex_solo_lect AS RO
,usua_nomb AS CREA
,substr(anex_desc, 0, 100) AS DESCR
,a.anex_nomb_archivo AS NOMBRE
,a.ANEX_CREADOR
,a.ANEX_ORIGEN
,a.ANEX_SALIDA
, RADI_NUME_SALIDA
,a.ANEX_ESTADO
,a.SGD_PNUFE_CODI
,a.SGD_DOC_SECUENCIA
,SGD_DIR_TIPO
,SGD_DOC_PADRE
,a.SGD_TPR_CODIGO
,a.SGD_TRAD_CODIGO
,a.ANEX_TIPO
,a.ANEX_FECH_ANEX AANEX_FECH_ANEX
,a.ANEX_FECH_ANEX
,a.ANEX_RADI_NUME
,a.ANEX_TIPO_FINAL
,a.ANEX_ENV_EMAIL
,tpr.SGD_TPR_DESCRIP
,TO_CHAR(a.sgd_fech_doc,'YYYY-MM-DD HH24:MI:SS AM') FECDOC
,TO_CHAR(a.anex_fech_anex,'YYYY-MM-DD HH24:MI:SS AM') FEANEX
,a.ANEX_TIPO NUMEXTDOC
,rsal.radi_path PATH_RAD_SALIDA
from  anexos_tipo at ,usuario u,
anexos a
left join radicado rsal           on (a.radi_nume_salida=rsal.radi_nume_radi)
left join sgd_tpr_tpdcumento tpr  on (a.sgd_tpr_codigo=tpr.sgd_tpr_codigo)
where anex_radi_nume={$_GET['nume_radi']} and a.anex_tipo=at.anex_tipo_codi
and a.anex_codigo like '{$_GET['nume_radi']}%'
and a.anex_creador=u.usua_login and a.anex_borrado='N'
order by a.id, a.anex_codigo, a.ANEX_FECH_ANEX, sgd_dir_tipo,a.anex_radi_nume,a.radi_nume_salida limit 324
SQL;
            $rs = $db1->Execute($sql);
            $ret['anexos'] = $rs->GetRows();
            break;
        case 'cerrar':
            $query = "select * from usuario where usua_login = '{$_SESSION['krd']}'";
            $rs = $db1->Execute($query);
            $usr1 = $rs->fetchRow();

            $comentario = $_POST['c'];
            $sql = <<<SQL
update radicado
              set
              RADI_USU_ANTE='{$_SESSION['krd']}'
              ,RADI_DEPE_ACTU=999
              ,RADI_USUA_ACTU=15
              ,CARP_CODI=0
              ,CARP_PER=0
              ,RADI_LEIDO=0
              ,radi_fech_agend=null
              ,radi_agend=null
              ,CODI_NIVEL=1
              ,SGD_SPUB_CODIGO=0
              ,RADI_NRR=1
              where
              RADI_NUME_RADI in({$_GET['nume_radi']});
insert into HIST_EVENTOS(RADI_NUME_RADI,DEPE_CODI,USUA_CODI,USUA_CODI_DEST,DEPE_CODI_DEST,USUA_DOC,HIST_DOC_DEST,
    SGD_TTR_CODIGO,HIST_OBSE,HIST_FECH)
    values ({$_GET['nume_radi']},{$usr1['DEPE_CODI']},{$usr1["USUA_CODI"]},15,999,{$usr1["USUA_DOC"]},999999,
        65,'{$comentario}',now());
SQL;
            $rs = $db1->Execute($sql);
            $ret['res'] = $rs ? true : false;
            break;
    }
    header("Content-Type: application/json; charset=UTF-8");
    echo json_encode($ret);
    exit;
}

if( (!empty($_POST['Busqueda']) && ($_POST['Busqueda']=="Busqueda")) ||
    isset($_GET['nume_radi']))
{
    $er_cerrar = false;
    $tx_cerrar = '';
    $sql = <<<SQL
select r.radi_path, r.radi_nume_radi, r.radi_fech_radi, r.ra_asun, r.radi_nume_folio, r.radi_nume_anexo, r.radi_desc_anex,
r.sgd_spub_codigo, r.codi_nivel, u.usua_nomb, u.usua_doc, r.radi_depe_radi, r.radi_usua_radi, r.radi_usu_ante, r.radi_depe_actu, d.depe_nomb
from radicado r
left join usuario u on r.radi_usua_actu= u.usua_codi and r.radi_depe_actu= u.depe_codi
left join dependencia d on d.depe_codi = r.radi_depe_actu
where r.radi_nume_radi='{$_GET['nume_radi']}'
SQL;
    $rs = $db1->Execute($sql);
    $rad = $rs->fetchRow();

    $rs = $db1->Execute("select sgd_exp_numero from sgd_exp_expediente where radi_nume_radi in ({$_GET['nume_radi']}) limit 1");
    $exp = $rs->fetchRow();

    $searchRegs = $_GET['nume_radi'];
    $iSql = "SELECT RADI_NUME_RADI FROM RADICADO
        WHERE
        radi_nume_radi in ($searchRegs)
        and RADI_NUME_RADI NOT IN
        (SELECT radi_nume_radi
        FROM sgd_rdf_retdocf where radi_nume_radi in ($searchRegs))";
    $rs = $db1->Execute($iSql); # Ejecuta la busqueda
    $validate = true;
    if ($rs) {
        while (!$rs->EOF) {
            $regsTrdFalse[] = $rs->fields['RADI_NUME_RADI'];
            $rs->MoveNext();
            $validate = false;
        }
    }
    $aSql = "SELECT RADI_NUME_SALIDA, ANEX_RADI_NUME FROM ANEXOS A, RADICADO R
        WHERE
        A.ANEX_RADI_NUME in ($searchRegs)
        AND A.anex_SALIDA=1 and A.RADI_NUME_SALIDA IS NOT NULL
        and A.ANEX_RADI_NUME<>A.RADI_NUME_SALIDA
        and  R.RADI_NUME_RADI = A.RADI_NUME_SALIDA
        and (R.SGD_EANU_CODIGO not in (2) or R.SGD_EANU_CODIGO is null)
        AND A.RADI_NUME_SALIDA NOT IN
        (SELECT RDF.radi_nume_radi
        FROM sgd_rdf_retdocf RDF where RDF.radi_nume_radi in (
            SELECT RADI_NUME_SALIDA FROM ANEXOS A
            WHERE
            A.ANEX_RADI_NUME in ($searchRegs)
            and A.ANEX_RADI_NUME<>A.RADI_NUME_SALIDA
            AND A.anex_SALIDA=1 and A.RADI_NUME_SALIDA IS NOT NULL
            ))";
    $rsA = $db1->Execute($aSql); # Ejecuta la busqueda
    if ($rsA) {
        while (!$rsA->EOF) {
            $regsATrdFalse[] = $rsA->fields['RADI_NUME_SALIDA'] . " (de:" . $rsA->fields['ANEX_RADI_NUME'] . ")";
            $rsA->MoveNext();
            $validate = false;
        }
    }

    //include("$ruta_raiz/include/tx/Tx.php");
    //$tx = new Tx($db1);
    //if(!$tx->validateTrdSend(["{$_GET['nume_radi']}"=>0])){
    if(!$validate){
        $er_cerrar = true;
        if(count($regsTrdFalse)>=1){
            $tx_cerrar = "Radicados sin Clasificacion TRD $regs";
        }
        if(count($regsATrdFalse)>=1){
            $tx_cerrar = "Anexos de Radicados sin Clasificacion: ".implode(", ",$tx->regsATrdFalse);
        }
    }
    if (!$exp) {
        $er_cerrar = true;
        $tx_cerrar = "Radicado sin expediente";
    }

}
?>
<!DOCTYPE html>
<html>
    <head>
        <?php include_once "$ruta_raiz/htmlheader.inc.php"; ?>
        <title>Consultas Expedientes</title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <script language="JavaScript" src="<?=$ruta_raiz?>/js/formchek.js"></script>
    </head>

    <body>
        <div class="container-fluid">
            <div class="col-sm-12">
<!--
                <form method="post" enctype="multipart/form-data"
                    class="form-horizontal"
                    name="formSeleccion" id="formSeleccion">
                    <section id="widget-grid" style="margin-top: 15px;">
                        <article>
                            <div class="jarviswidget jarviswidget-color-darken" id="wid-id-1" data-widget-editbutton="false">
                                <header>
                                    <h2>
                                        B&uacute;squeda Superargo1
                                    </h2>
                                </header>
                                <div class="widget-body">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Radicado </label>
                                            <input  class="form-control"
                                                    type="text"
                                                    name="nume_radi"
                                                    maxlength="17"
                                                    value="<?=$nume_radi?>"
                                                    autocomplete="off"
                                                    size="25">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <input
                                                id="limpiar"
                                                class="btn btn-default"
                                                value="Limpiar"
                                                type="button">

                                            <input
                                                class="btn btn-primary"
                                                name="Busqueda"
                                                type="submit"
                                                id="envia22"
                                                value="Busqueda">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </article>
                    </section>
                </form>
-->
                <?php if(!$result) {  //?? ?>
                    <div class="row">
                        <section id="widget-grid">
                            <div class="col-md-12">
                                <article>
                                    <div class="jarviswidget jarviswidget-color-darken" id="wid-id-2" data-widget-editbutton="false">
                                        <header>
                                            <h2>
                                            Radicado <?=$nume_radi?>
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
$('#myTabs a').click(function (e) {
    e.preventDefault();
    $(this).tab('show');
    switch(e.target.hash) {
        case '#informacion':
            $('#envia22').click();
            break;
        case '#historico':
            if (hist) break;
            $.ajax({
                method: 'POST',
                data: {a:'hist',r:'<?=$nume_radi?>'},
                beforeSend: function() {
                    $('#loader').show();
                },
                complete: function() {
                    $('#loader').hide();
                },
            }).done(function(data, textStatus, jqXHR) {
console.log(data);
                for (e of data.hist) {
                    //if (e.respuesta) {
                        //console.log(e.respuesta);
                        //$('#historico').append(e.respuesta);
                    //}
                    //else {
                      $('#hist').append(`<tr>
                          <td>${e.DEPE_NOMB}</td>
                          <td>${e.HIST_FECH}</td>
                          <td>${e.SGD_TTR_DESCRIP}</td>
                          <td>${e.US}</td>
                          <td>${e.HIST_OBSE}</td>
                          <td>${e.UD}</td>
                          </tr>`);
                        $('#hist').show();
                    //}
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
                data: {a:'anex',r:'<?=$nume_radi?>'},
                beforeSend: function() {
                    $('#loader').show();
                },
                complete: function() {
console.log('fin anexos');
                    $('#loader').hide();
                },
            }).done(function(data, textStatus, jqXHR) {
                for (e of data.anexos) {
            console.log('anexos');
                    if (e.mensajerespuesta) {
                        $('#anexos').append(e.mensajerespuesta);
                        return;
                    }
                    var ext = '';
                    if (e.PATH_RAD_SALIDA) {
                        ext = e.PATH_RAD_SALIDA.toLowerCase().split('.').pop();
                    }
                    console.log(ext);
                    if (ext == 'pdf') {
                        link = `
                            <a class="vinculos abrirVisor" href="javascript:void(0)"
                                link="../bodega/${e.PATH_RAD_SALIDA}">
                                <img src="../img/icono_pdf.jpg" title="pdf" width="25">
                                ${e.RADI_NUME_SALIDA}
                            </a>`;
                    }
                    else {
                        link = `
                            <a class="vinculos" href="../bodega/${e.PATH_RAD_SALIDA}">
                                ${e.RADI_NUME_SALIDA}
                            </a>`;
                    }
                    $('#anex').append(`<tr>
                        <td>${link}</td>
                        <td>${e.TAMA}</td>
                        <td>${e.CREA}</td>
                        <td>${e.DESCR}</td>
                        <td>${e.FEANEX}</td>
                        </tr>`);
                }
console.log($('#anex'));
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
<br/>

                                            <div class="row">

                                                <?php if(!$rad) { ?>
                                                    <div class="col-md-12">
                                                        Sin resultados
                                                    </div>
                                                <?php } else { ?>
                                                    <?php
                                                        //$fechaRadicacion = DateTime::createFromFormat('Y-m-d\TH:i:sP', $result->return->fechaRadicacionRadicado);
                                                    ?>
                                                    <div class="col-md-12">
                                                        <h4>Radicado Nº <?=$rad['RADI_NUME_RADI']?> 
                                                            <small>
<?php if ($rad['RADI_PATH']) echo "<a href='{$ruta_raiz}/bodega/{$rad['RADI_PATH']}' target='_blank'>Ver imagen</a>"; ?>
                                                            </small>
                                                        </h4><br>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label for=""><strong>Fecha radicación:</strong></label>
                                                        <p><small><?=$rad['RADI_FECH_RADI']?>&nbsp;</small></p>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label for=""><strong>Asunto:</strong></label>
                                                        <p><small><?=$rad['RA_ASUN']?></small></p>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label for=""><strong>Usuario actual:</strong></label>
                                                        <p><small><?=$rad['USUA_NOMB']?>&nbsp;</small></p>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label for=""><strong>Dependencia actual:</strong></label>
                                                        <p><small><?=$rad['DEPE_NOMB']?>&nbsp;</small></p>
                                                    </div>
                                                <?php } ?>
                                            </div>
</div>
    <div role="tabpanel" class="tab-pane" id="historico">
<br/>
<table id="hist" class="table table-striped table-hover table-bordered" width="100%" align="center"  style="display:none">
  <thead>
    <tr class="pr2" align="center">
      <th>DEPENDENCIA</th>
      <th>FECHA</th>
      <th>TRANSACCIÓN</th>
      <th>US. ORIGEN</th>
      <th>COMENTARIO</th>
      <th>US. DESTINO</th>
    </tr>
  </thead>
        </table>
    </div>
    <div role="tabpanel" class="tab-pane" id="anexos">
<table id="anex" class="table" width="100%" align="center" style="display:none">
<br/>
    <thead>
        <tr class="pr2">
            <th width="20%">Documento</th>
            <th width="20%">Tamaño</th>
            <th width="20%">Creador</th>
            <th width="20%">Descripción</th>
            <th width="20%">Fecha</th>
        </tr>
    </thead>
	<tbody>
<!--
	<tr>
<td><b><a class="vinculos abrirVisor" href="javascript:void(0)" contador="1" link="./bodega/2021/900/docs/20210900000025100001.pdf">
            		<img src="../img/icono_pdf.jpg" title="pdf" width="25">
            		</a><a class="vinculos abrirVisor" href="javascript:void(0)" contador="1" link="./bodega/2021/900/docs/20210900000025100001.pdf">
            			202109000000251
            		</a>
            	<div id="visor_1" style="display:none; 
            position:fixed;
            padding:26px 30px 30px;
            top:0;
            left:0;
            right:0;
            bottom:0;
            z-index:2">
            <button class="cerrarVisor" type="button" style="float:right; background-color:red;" contador="1"><b>x</b></button>
          </div>	</b></td>-->
	</tr>
</tbody></table>
</div>
    <div role="tabpanel" class="tab-pane" id="cerrar">
<br>
<div id="finalizado" style="display:none" class="alert alert-info" role="alert">Archivado</div>
<form id="frmcomentario" style="display:none">
<div class="form-group">
    <label for="comentario" class="col-sm-2 control-label">Comentario</label>
    <textarea name="comentario" class="form-control" id="comentario" placeholder="Escriba un Comentario" rows="3" required></textarea>
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
<!--<script type="text/javascript" src="../js/jquery.min.js"></script>-->
<!--<script type="text/javascript" src="../js/libs/jquery-ui-1.10.4.js"></script>-->
<script>
function visor() {
    $('#visor').dialog({ autoOpen: false });
  $('.abrirVisor').on('click',function(){
    link = $(this).attr('link');
    if ($('#visor iframe').attr('src') != link) {
      $('#visor iframe').attr('src', 'about:blank');
      $('#visor iframe').attr('src', link);
    }
    $('#visor').dialog('open');
  });

  $('.cerrarVisor').on('click',function(){
    $('#visor').dialog('close');
  });
}

            $(function(){

if ('<?=$rad['RADI_DEPE_ACTU']?>' == '999') {
$('#finalizado').show();
}
else {
if ('<?=$er_cerrar?>' == true) {
    $('#finalizado').text('<?=$tx_cerrar?>');
$('#finalizado').show();
}
else {
$('#frmcomentario').show();
}
}
 $("#frmcomentario").submit(function (event) {
    console.log($("#comentario").val());
    $.ajax({
      type: "POST",
      data: {a:'cerrar',r:'<?=$nume_radi?>',c:$("#comentario").val()},
    }).done(function (data) {
      $('#frmcomentario').hide();
      $('#finalizado').show();
    });

    event.preventDefault();
  });

                $('#limpiar').click(function(){
                    $(':input','#formSeleccion')
                        .not(':button, :submit, :reset, :hidden')
                        .val('')
                        .removeAttr('checked')
                        .removeAttr('selected');
                });
            });
        </script>
    </body>
</html>
