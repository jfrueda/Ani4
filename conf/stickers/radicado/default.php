<?$ruta_raiz="../.."?> <html>

<title>Sticker web</title>
<link rel="stylesheet" href="estilo_imprimir.css" TYPE="text/css" MEDIA="print">
<style type="text/css">

body {
    margin-bottom:0;
    margin-left:0;
    margin-right:0;
    margin-top:0;
    padding-bottom:0;
    padding-left:0;
    padding-right:0;
    padding-top:0;
    font-family: Arial, Helvetica, sans-serif;
    width: 440px;
}

.flex-container {
  padding: 0;
  margin: 0;
  list-style: none;
  -ms-box-orient: horizontal;
  display: -webkit-box;
  display: -moz-box;
  display: -ms-flexbox;
  display: -moz-flex;
  display: -webkit-flex;
  display: flex;
}

.wrap{
  -webkit-flex-wrap: wrap;
  flex-wrap: wrap;
}

.flex-item {
  text-align: center;
  margin-left: 0.5rem;
}
</style>
</head>

<body marginheight="0" marginwidth="0">
<ul class="flex-container wrap" style="margin-top:10px">
  <li class="flex-item">
    <?=$noRadBarras?>
  </li>
  <li class="flex-item">
    <img width="100" src="data:image/png;base64,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" alt="logo" />
  </li>
  <li class="flex-item">
    <font face="Arial" size="1">
        Al contestar cite este número:
    </font>
  </li>
  <li class="flex-item">
	<font face="Arial" size="2"><?=$noRad?></font>
  </li>
  <li class="flex-item">
	<font face="Arial" size="2"><?=substr($radi_fech_radi,0,17)?></font>
  </li>
  <li class="flex-item">
  </li>
  <?php
    if(empty($dirLogo)){
        echo "
        <li class='flex-item'>
           <font face='Arial' size='1'><?=$entidad_corto?></font>
        </li> ";
    }

    if(!empty($anexos)){
        echo "<li class='flex-item'><font face='Arial' size='1'>Anexos".$anexos.".</font></li>";
    }

    if(!empty($folios)){
        echo "<li class='flex-item'><font face='Arial' size='1'>Folios:".$folios.".</font></li>";
    }
?>
  <li class="flex-item">
	<font face="Arial" size="1">Destino: <?=$depeNombActu?>.</font>
  </li>
  <li class="flex-item">
	<font face="Arial" size="1">Origen: <?=$remitente?>.</font>
  </li>
<?php if(count($res_informados) > 0) { ?>
  <li class="flex-item">
  <font face="Arial" size="1">Informados: <?=$informados?>.</font>
  </li>
<?php } ?>
</ul>

</body>
</html>
