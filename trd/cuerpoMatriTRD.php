<?php

/**
 * @module cuerpoMatriTRD
 *
 * @author Jairo Losada   <jlosada@gmail.com>
 * @author Cesar Gonzalez <aurigadl@gmail.com>
 * @license  GNU AFFERO GENERAL PUBLIC LICENSE
 * @copyright

OrfeoGPL Models are the data definition of OrfeoGPL Information System
Copyright (C) 2013 Infometrika Ltda.

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU Affero General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Affero General Public License for more details.

You should have received a copy of the GNU Affero General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

session_start();
$ruta_raiz = "..";

foreach ($_GET as $key => $valor)   ${$key} = $valor;
foreach ($_POST as $key => $valor)   ${$key} = $valor;

$krd = $_SESSION["krd"];
$dependencia = $_SESSION["dependencia"];
$usua_doc    = $_SESSION["usua_doc"];
$codusuario  = $_SESSION["codusuario"];
$fecha_fin   = date("Y/m/d");
if (!$coddepe) $coddepe = 0;
if (!$tsub) $tsub = 0;
if (!$codserie) $codserie = 0;
$where_fecha = "";

include_once "$ruta_raiz/include/db/ConnectionHandler.php";
$db = new ConnectionHandler("$ruta_raiz");

include_once "$ruta_raiz/include/query/envios/queryPaencabeza.php";

/* Salvar o Actulizar Matriz
  *
  * Salvar la información al actualizar o para crear una nueva relación
  * de tipos documentales con serie y sub serie.
  *
  */

//Si nos llega la variable enviar verificamos que exista
//cada uno de los parametros necesarios para actulizar o para
//insertar una nueva relación en la tabla.
if (
  $_POST['actu_mtrd'] && $_POST['coddepe']
  && $_POST['idSerie'] && $_POST['idSubSerie']
) {
  //Si existen arreglos de tipos documentales seleccionados por
  //el usuario podemos realizar el procedimiento.
  if ($_POST['checkValue']) {

    //Convertimos el arreglo en elementos seriales que podamos incluir
    //en la consulta de validacion para cada uno de los tipos
    //documentales.

    $iSql = "SELECT * FROM sgd_sbrd_subserierd WHERE sgd_srd_id='$idSerie' and id='$idSubSerie'";
    $rsSbrd = $db->conn->query($iSql);
    if (!$rsSbrd->EOF) {
      $codserie = $rsSbrd->fields["SGD_SRD_CODIGO"];
      $tsub = $rsSbrd->fields["SGD_SBRD_CODIGO"];
    }
    foreach ($_POST['checkValue'] as $key => $value) {
      $tipDocumentales[] = $key;

      $isqlCodId = 'select max(sgd_mrd_codigo)+1 as COD from SGD_MRD_MATRIRD';
      $resCodId = $db->conn->query($isqlCodId);

      $record['DEPE_CODI_APLICA'] = $depecodiaplica;
      $record['SOPORTE'] = $med;
      $record['SGD_SRD_ID'] = $idSerie;
      $record['SGD_SBRD_ID'] = $idSubSerie;
      $record['SGD_SRD_CODIGO'] = $codserie;
      $record['SGD_SBRD_CODIGO'] = $tsub;
      $record['DEPE_CODI'] = $coddepe;
      $record['SGD_TPR_CODIGO'] = $key;
      $record['SGD_MRD_ESTA'] = 1;
      $record['SGD_MRD_CODIGO'] = $resCodId->fields['COD'];
      $db->conn->replace('SGD_MRD_MATRIRD', $record, array('SGD_SRD_ID', 'SGD_SBRD_ID', 'DEPE_CODI', 'SGD_TPR_CODIGO'), true);
    }

    $tprCodi = implode(",", $tipDocumentales);
    $sqlVali = "
        select
        sgd_tpr_codigo
        from
        sgd_mrd_matrird m
        where
        m.depe_codi           = '$coddepe'
        and m.sgd_srd_id  = '$idSerie'
        and m.sgd_sbrd_id = '$idSubSerie'
        and m.sgd_tpr_codigo  in ($tprCodi)
        and cast(m.sgd_mrd_esta as numeric(1)) = 1";
    $resSql = $db->conn->query($sqlVali);

    //por cada uno de los elementos seleccionados por el usuario
    //validamos si ya existe y lo actulizamos de lo contrario
    //lo creamos

    //Creamos un arreglo con los elementos para actulizar
    //y uno con los elementos a insertar
    while ($resSql && !$resSql->EOF) {
      $tpr_codigo = $resSql->fields['SGD_TPR_CODIGO'];
      if ($key = array_search($tpr_codigo, $tipDocumentales) !== false) {
        //Borramos los elementos que ya existen y dejamos los nuevos
        //para crearlos.
        unset($tipDocumentales[$key]);
      } else {
        $actuTipDoc[] = $tpr_codigo;
      }
      $resSql->MoveNext();
    }

    //Realizamos la actulizacion del los tipos documentales ya creados
    $sqlUp = "update
                  sgd_mrd_matrird
                set depe_codi_aplica = '$depecodiaplica'
                 ,  soporte            = '$med'
                where
                  m.sgd_srd_id  = '$idSerie'
                  and m.sgd_sbrd_id = '$idSubSerie'
                  and m.depe_codi       = '$coddepe'
                  and m.sgd_tpr_codigo  in ($tprCodi)";

    //b->conn->query($sqlUp);

    //Insertamos los nuevos tipos documentales en la matriz
    /*
      foreach($tipDocumentales => $value){
        $fechini = db->conn->OffsetDate(0,$this->db->conn->sysTimeStamp);
        $sqlIns = "insert into
          sgd_mrd_matrird
          values((select max(sgd_mrd_codigo) + 1 from sgd_mrd_matrird)
          ,'$coddepe'
          ,'$depecodiaplica'
          ,'$codserie'
          ,'$tsub'
          ,'$codserie'
          ,'$tsub'
          ,'$value'
          ,'$med
          , $fechini
          , null
          , 1
        )"

        $db->conn->query($sqlIns);
      }*/
  }
}

$encabezado = "" . session_name() . "=" . session_id() . "&filtroSelect=$filtroSelect&accion_sal=$accion_sal&dependencia=$dependencia&tpAnulacion=$tpAnulacion&orderNo=";
$linkPagina = "$PHP_SELF?$encabezado&accion_sal=$accion_sal&orderTipo=$orderTipo&orderNo=$orderNo";

/*  GENERACION LISTADO DE RADICADOS
 *  Aqui utilizamos la clase adodb para generar el listado de los radicados
 *  Esta clase cuenta con una adaptacion a las clases utiilzadas de orfeo.
 *  el archivo original es adodb-pager.inc.php la modificada es adodb-paginacion.inc.php
 */
if (trim($orderTipo) == "") $orderTipo = "ASC";
if ($orden_cambio == 1) {
  if (trim($orderTipo) != "DESC") {
    $orderTipo = "DESC";
  } else {
    $orderTipo = "ASC";
  }
}

//Cargar las dependencias y generar el selector de forma dinamica
//con las librerias de adodb
$sqlConcat = $db->conn->Concat($db->conn->substr . "($conversion,1,5) ", "'-'", $db->conn->substr . "(depe_nomb,1,30) ");
$sql       = "select $sqlConcat ,depe_codi from dependencia where depe_codi >= 10000 and depe_estado = 1 order by depe_codi";
$rsDep     = $db->conn->Execute($sql);
if (!$depeBuscada) $depeBuscada = $dependencia;
$selecDepe = $rsDep->GetMenu2("coddepe", "$coddepe", false, false, 0, " onChange='submit();' class='select'");

//Cargar las series y generar el selector de forma dinamica
//con las librerias de adodb
//include "$ruta_raiz/trd/actu_matritrd.php";
if (!$codserie) $codserie = 0;
if (!$idSerie) $idSerie = 0;
$fechah      = date("dmy") . " " . time("h_m_s");
$fecha_hoy   = date("d-m-y");
$sqlFechaHoy = "'" . $fecha_hoy . "'";
$check       = 1;
$fechaf      = date("dmy") . "_" . time("hms");
$num_car     = 4;
$nomb_varc   = "sgd_srd_codigo";
$nomb_varde  = "sgd_srd_descrip";
include "$ruta_raiz/include/query/trd/queryCodiDetalle.php";
$querySerie = "select
                  distinct ($sqlConcat) as detalle,id, sgd_srd_codigo
                from
                  sgd_srd_seriesrd
		where sgd_srd_estado = 1
                  order by detalle ";
$rsD = $db->conn->query($querySerie);
$comentarioDev = "Muestra las Series Docuementales";
include "$ruta_raiz/include/tx/ComentarioTx.php";
$selecSerie = $rsD->GetMenu2("idSerie", $idSerie, "0:-- Seleccione --", false, "", "onChange='submit()' class='select'");

//Cargar las subSeries y generar el selector de forma dinamica
//con las librerias de adodb
$nomb_varc  = "sgd_sbrd_codigo";
$nomb_varde = "sgd_sbrd_descrip";
include "$ruta_raiz/include/query/trd/queryCodiDetalle.php";
$fecha_hoy   = date("Y-m-d");
$sqlFechaHoy = "'" . $fecha_hoy . "'";
$querySub    = "select
                   distinct ($sqlConcat) as detalle,  id, sgd_sbrd_codigo
                 from
                   sgd_sbrd_subserierd
                 where
                   sgd_srd_id = '$idSerie'
		   and sgd_sbrd_estado = 1
                   and $sqlFechaHoy between $sgd_sbrd_fechini and $sgd_sbrd_fechfin
                   order by detalle";


$rsSub = $db->conn->query($querySub);
include "$ruta_raiz/include/tx/ComentarioTx.php";
$selecSubSerie = $rsSub->GetMenu2("idSubSerie", $idSubSerie, "0:-- Seleccione --", false, "", "onChange='submit()' class='select'");

//Cargar los tipos de soporte y generar el selector de forma dinamica
//con las librerias de adodb
$datosel = ($med == 1) ? " selected " : " ";
$selecSoporte .= "<option value='1' $datosel><font>1. PAPEL</font></option>";
$datosel = ($med == 2) ? " selected " : " ";
$selecSoporte .= "<option value='2' $datosel><font>2. MAGNETICO </font></option>";
$datosel = ($med == 3) ? " selected " : " ";
$selecSoporte .= "<option value='3' $datosel><font>3. PAPEL / MAGNETICO </font></option>";

$isql = "select
             m.depe_codi_aplica
          from
            sgd_mrd_matrird m
          where
              m.depe_codi       = '$coddepe'
          and m.sgd_srd_id  = '$idSerie'
          and m.sgd_sbrd_id = '$idSubSerie'";

$rs = $db->conn->query($isql);
$depeCodiAplica = $rs->fields["DEPE_CODI_APLICA"];

//Mostrar la información del las relaciones existente
if (strlen($orderNo) == 0) {
  $orderNo = "1";
  $order  = 1;
} else {
  $order = $orderNo + 1;
}
if ($idSubSerie == '') {
  $idSubSerie = 0;
}
$isql = " select
	      t.sgd_tpr_codigo as CODIGO, t.sgd_tpr_descrip as DETALLE, m.depe_codi_aplica,
               t.sgd_tpr_codigo AS \"CHK_SGD_TPR_CODIGO\"
           from
              sgd_mrd_matrird m, sgd_tpr_tpdcumento t
           where
                 m.depe_codi       = '$coddepe'
             and m.sgd_srd_id  = '$idSerie'
             and m.sgd_sbrd_id = '$idSubSerie'
             and m.sgd_tpr_codigo  = t.sgd_tpr_codigo
             and cast(m.sgd_mrd_esta as numeric(1)) = 1";

$isql  = $isql . " order by " . $order . " " . $orderTipo;

//echo $isql;

$encabezado = "" . session_name() . "=" . session_id() . "&krd=$krd&estado_sal=$estado_sal&estado_sal_max=$estado_sal_max&accion_sal=$accion_sal&coddepe=$coddepe&dep_sel=$dep_sel&med=$med&idSubSerie=$idSubSerie&idSerie=$idSerie&tsub=$tsub&codserie=$codserie&nomcarpeta=$nomcarpeta&orderTipo=$orderTipo&orderNo=";
$linkPagina = "$PHP_SELF?$encabezado&orderTipo=$orderTipo&orderNo=$orderNo";
$pager = new ADODB_Pager($db, $isql, 'adodb', true, $orderNo, $orderTipo);
$pager->checkTitulo = true;
$pager->checkAll = true;
$pager->toRefLinks = $linkPagina;
$pager->toRefVars = $encabezado;

//echo "variables enviadas &krd=$krd&estado_sal=$estado_sal&estado_sal_max=$estado_sal_max&accion_sal=$accion_sal";
$isqlF = "select
               a.sgd_tpr_codigo as CODIGO
              ,a.sgd_tpr_descrip as DETALLLE
              ,a.sgd_tpr_codigo AS \"CHK_SGD_TPR_CODIGO\"
            from
              sgd_tpr_tpdcumento a
            where a.sgd_tpr_codigo not in (
                select
                  t.sgd_tpr_codigo
                from
                  sgd_mrd_matrird m, sgd_tpr_tpdcumento t
                where
                      m.depe_codi        = '$coddepe'
                  and m.sgd_srd_id   = '$idSerie'
                  and m.sgd_sbrd_id  = '$idSubSerie'
                  and m.sgd_tpr_codigo   = t.sgd_tpr_codigo)
              and a.sgd_tpr_estado   = 1
              and a.sgd_tpr_codigo  != '0' ";

$isqlF = $isqlF .  'order by ' . $order . ' ' . $orderTipo;

$encabezado = "" . session_name() . "=" . session_id() . "&krd=$krd&estado_sal=$estado_sal&estado_sal_max=$estado_sal_max&accion_sal=$accion_sal&coddepe=$coddepe&dep_sel=$dep_sel&idSerie=$idSerie&med=$med&idSubSerie=$idSubSerie&tsub=$tsub&nomcarpeta=$nomcarpeta&orderTipo=$orderTipo&orderNo=";
$linkPagina = "$PHP_SELF?$encabezado&orderTipo=$orderTipo&orderNo=$orderNo";

//echo $isqlF;
$pager2 = new ADODB_Pager($db, $isqlF, 'adodb', true, $orderNo, $orderTipo);
$pager2->checkAll    = false;
$pager2->checkTitulo = true;
$pager2->toRefLinks  = $linkPagina;
$pager2->toRefVars   = $encabezado;
?>

<html>

<head>
  <?php include_once "$ruta_raiz/htmlheader.inc.php"; ?>
</head>

<body>
  <div class="container-fluid py-4">

    <form name="formEnviar"
      action="../trd/cuerpoMatriTRD.php?<?= session_name() . '=' . session_id() ?>&estado_sal=<?= $estado_sal ?>&estado_sal_max=<?= $estado_sal_max ?>&pagina_sig=<?= $pagina_sig ?>&dep_sel=<?= $dep_sel ?>&nomcarpeta=<?= $nomcarpeta ?>&orderNo=<?= $orderNo ?>"
      method="post">

      <!-- ENCABEZADO -->
      <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-orfeo text-white text-center py-3">
          <h2 class="fw-bold ">Matriz Tabla de Retención Documental (TRD)</h2>
        </div>
      </div>

      <!-- FILA PRINCIPAL -->
      <div class="row g-4">
        <!-- COLUMNA 1 -->
        <div class="col-12 col-md-6">
          <div class="card shadow-sm border-0">
            <div class="card-body">

              <h5 class="fw-bold text-secondary mb-3">Filtros de búsqueda</h5>

              <div class="mb-3">
                <label class="form-label fw-semibold">Dependencia</label>
                <div class="form-select p-0 border-0">
                  <?= $selecDepe ?>
                </div>
              </div>

              <div class="mb-3">
                <label class="form-label fw-semibold">Serie</label>
                <div class="form-select p-0 border-0">
                  <?= $selecSerie ?>
                </div>
              </div>

              <div class="mb-3">
                <label class="form-label fw-semibold">Subserie</label>
                <div class="form-select p-0 border-0">
                  <?= $selecSubSerie ?>
                </div>
              </div>

            </div>
          </div>
        </div>

        <!-- COLUMNA 2 -->
        <div class="col-12 col-md-6">
          <div class="card shadow-sm border-0">
            <div class="card-body">

              <h5 class="fw-bold text-secondary mb-3">Parámetros adicionales</h5>

              <div class="mb-3">
                <label class="form-label fw-semibold">Soporte</label>
                <select name="med" class="form-select">
                  <?= $selecSoporte ?>
                </select>
              </div>

              <div class="mb-3">
                <label class="form-label fw-semibold">
                  Dependencias a las que aplica (separadas por coma)
                </label>
                <small class="text-muted d-block mb-1">
                  Estas serán dependencias/áreas que usan la combinación,
                  pero no se reflejarán en el reporte.
                </small>
                <input type="text" name="depecodiaplica" value="<?= $depeCodiAplica ?>" class="form-control">
              </div>

              <div class="mt-4">
                <input type="submit" name="actu_mtrd" value="Enviar" class="btn btn-info px-4">
                <input name="aceptar" id="envia22" type="button" value="Cancelar" class="btn btn-danger px-4"
                  onClick="window.close();">
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- DOCUMENTOS ASIGNADOS -->
      <div class="card shadow-sm border-0 mt-4">
        <div class="card-body">
          <h5 class="fw-bold text-primary">Documentos asignados a estos parámetros</h5>
          <hr>
          <?php $pager->Render($rows_per_page = 1600, $linkPagina, $checkbox = chkEnviar); ?>
        </div>
      </div>

      <!-- DOCUMENTOS SIN ASIGNAR -->
      <div class="card shadow-sm border-0 mt-4 mb-5">
        <div class="card-body">
          <h5 class="fw-bold text-primary">Documentos sin asignar a estos parámetros</h5>
          <hr>
          <?php $pager2->Render($rows_per_page = 1600, $linkPagina, $checkbox = chkEnviar); ?>
        </div>
      </div>
    </form>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const coddepe = document.querySelector("select[name='coddepe']");
      const idSerie = document.querySelector("select[name='idSerie']");
      const idSubSerie = document.querySelector("select[name='idSubSerie']");

      if (coddepe) {
        coddepe.classList.add("form-select");
      }
      if (idSerie) {
        idSerie.classList.add("form-select");
      }
      if (idSubSerie) {
        idSubSerie.classList.add("form-select");
      }
    });
  </script>
</body>

</html>