<?php

$ruta_raiz = "../";
session_start();
require_once($ruta_raiz . "include/db/ConnectionHandler.php");
include($ruta_raiz . "processConfig.php");

list($driver1, $host1, $user1, $pass1, $dbname1) = explode(',', $superargo1);
$db1 = ADONewConnection($driver1);
$db1->Connect($host1, $user1, $pass1, $dbname1);

foreach ($_GET as $key => $valor)   ${$key} = $valor;
foreach ($_POST as $key => $valor)   ${$key} = $valor;

include("common.php");
$fechah = date("ymd") . "_" . time("hms");

$params = session_name() . "=" . session_id() . "&krd=$krd";
$url = $supercorws;
$ws_user = parse_url($url, PHP_URL_USER);
$ws_pass = parse_url($url, PHP_URL_PASS);

function searchExtension($mime, $array)
{
    foreach ($array as $key => $val) {
        if ($val['MIME Type'] === $mime) {
            return $key;
        }
    }
    return null;
}

//if(!empty($_POST['Busqueda']) && ($_POST['Busqueda']=="Busqueda"))
//{
//$db1->debug = true;
$sql = <<<SQL
select DISTINCT b.RADI_NUME_RADI "IDT_Numero RADICADO" ,b.RADI_PATH "HID_RADI_PATH" ,
b.RADI_FECH_RADI ,TO_CHAR(b.RADI_FECH_RADI,'YYYY-MM-DD HH24:MI AM') "DAT_FECHA RADICADO" ,
TO_CHAR(b.RADI_FECH_RADI,'YYYY-MM-DD HH24:MI AM') "HID_RADI_FECH_RADI" , 
b.RADI_NUME_RADI "HID_RADI_NUME_RADI" ,b.RA_ASUN "ASUNTO" , b.RADI_CUENTAI "REFERENCIA",
d.SGD_DIR_NOMREMDES "REMITENTE" ,c.SGD_TPR_DESCRIP "TIPO DOCUMENTO" ,
- extract(days from date_trunc('days', NOW()) - date_trunc('days',fech_vcmto)) "DIAS RESTANTES" ,
b.fech_vcmto "FECHA_VCMTO" ,b.RADI_USU_ANTE "ENVIADO POR" ,b.RADI_NUME_RADI "CHK_CHKANULAR" ,
b.RADI_LEIDO "HID_RADI_LEIDO" ,m.MREC_DESC "RADI_MREC_DESC" ,b.RADI_USUA_ACTU "RADI_USUA_ACTU" ,
b.RADI_NUME_HOJA "HID_RADI_NUME_HOJA" ,b.CARP_PER "HID_CARP_PER" ,b.CARP_CODI "HID_CARP_CODI" ,
b.SGD_EANU_CODIGO "HID_EANU_CODIGO" ,b.RADI_NUME_DERI "HID_RADI_NUME_DERI" ,
b.RADI_TIPO_DERI "HID_RADI_TIPO_DERI" ,b.SGD_TRAD_CODIGO "TIPO_RAD" ,a.ANEX_ESTADO "ANEX_ESTADO" ,
a.SGD_DEVE_CODIGO "SGD_DEVE_CODIGO" ,d.SGD_DIR_DOC "DOCUMENTO_USUARIO" 
from radicado b left outer join SGD_TPR_TPDCUMENTO c on b.tdoc_codi=c.sgd_tpr_codigo 
left join medio_recepcion m on b.mrec_codi = m.mrec_codi 
left outer join ANEXOS a on b.radi_nume_radi=a.RADI_NUME_SALIDA 
left outer join SGD_DIR_DRECCIONES d on (b.radi_nume_radi=d.radi_nume_radi 
    and d.sgd_dir_tipo=1)
inner join usuario u on u.usua_login = '{$_SESSION['krd']}'
where b.radi_nume_radi is not null
and b.radi_depe_actu=u.depe_codi and b.radi_usua_actu=u.usua_codi
-- and b.carp_codi=1 and b.carp_per=0 and b.radi_fech_radi 
order by b.RADI_FECH_RADI desc, 3 DESC ,
    b.RADI_NUME_RADI desc
SQL;
$limit = 20;
$page = @$_GET['page'] ?: 1;
$rs = $db1->PageExecute($sql, $limit, $page);
$rads = $rs->getArray();

$total = $rs->_maxRecordCount;
$page = $rs->_currentPage;
?>
<!DOCTYPE html>
<html>

<head>
    <?php include_once "$ruta_raiz/htmlheader.inc.php"; ?>
    <title>Consultas Expedientes</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <script language="JavaScript" src="<?= $ruta_raiz ?>/js/formchek.js"></script>

    <style>
        /* Mantiene tu estilo previo */
        .jarviswidget-color-darken .nav-tabs li:not(.active) a {
            color: grey !important;
        }

        /* Mejoras a la tabla */
        #anex tbody tr:hover {
            background: rgba(0, 123, 255, 0.07);
            transition: .2s ease-in-out;
            cursor: pointer;
        }

        /* Reduce padding vertical para una tabla más compacta */
        #anex td,
        #anex th {
            vertical-align: middle;
        }
    </style>
</head>

<body>
    <div class="container-fluid" id="app">
        <Transition name="slide-fade">
            <div class="row" v-if="showForm">
                <section id="widget-grid" class="mt-4">
                    <div class="col-12">
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-orfeo text-white py-3">
                                <h5 class="mb-0">
                                    Radicados pendientes <?= $total ?>
                                </h5>
                            </div>

                            <div class="card-body">
                                <div class="table-responsive mt-3">
                                    <table id="anex" class="table table-hover table-striped align-middle mb-4 w-100">
                                        <thead class="table-light">
                                            <tr>
                                                <th width="20%">Radicado</th>
                                                <th width="20%">Fecha</th>
                                                <th width="20%">Asunto</th>
                                                <th width="20%">Remitente / Destinatario</th>
                                                <th width="20%">Tipo documento</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($rads as $rad) { ?>
                                                <tr>
                                                    <td class="fw-semibold">
                                                        <?= $rad['IDT_NUMERO RADICADO'] ?>
                                                    </td>

                                                    <td>
                                                        <a class="text-primary fw-semibold"
                                                            href="superargo1.php?nume_radi=<?= $rad['IDT_NUMERO RADICADO'] ?>">
                                                            <?= $rad['RADI_FECH_RADI'] ?>
                                                        </a>
                                                    </td>

                                                    <td><?= $rad['ASUNTO'] ?></td>
                                                    <td><?= $rad['REMITENTE'] ?></td>
                                                    <td><?= $rad['TIPO DOCUMENTO'] ?></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Pagination -->
                                <div class="d-flex justify-content-center mt-3">
                                    <nav>
                                        <ul class="pagination pagination-sm">

                                            <?php for ($i = 1; $i <= ceil($total / $limit); $i++): ?>
                                                <?php if ($page == $i): ?>
                                                    <li class="page-item active">
                                                        <span class="page-link"><?= $i ?></span>
                                                    </li>
                                                <?php else: ?>
                                                    <li class="page-item">
                                                        <a class="page-link" href="?page=<?= $i ?>">
                                                            <?= $i ?>
                                                        </a>
                                                    </li>
                                                <?php endif; ?>
                                            <?php endfor; ?>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </Transition>
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
            $('.abrirVisor').off('click').on('click', function() {
                link = $(this).attr('link');
                if ($('#visor iframe').attr('src') != link) {
                    $('#visor iframe').attr('src', 'about:blank');
                    $('#visor iframe').attr('src', link);
                }
                $('#visor').dialog('open');
            });

            $('.cerrarVisor').off('click').on('click', function() {
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