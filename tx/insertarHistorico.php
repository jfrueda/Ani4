<?php
  session_start();
  $ruta_raiz = "..";
  include_once "$ruta_raiz/processConfig.php";
  if (!$_SESSION['dependencia'])
    header ("Location: $ruta_raiz/cerrar_session.php");

  foreach ($_GET as $key => $valor)   ${$key} = $valor;
  foreach ($_POST as $key => $valor)   ${$key} = $valor;

  include("$ruta_raiz/include/tx/Tx.php"); 	

  $db = new ConnectionHandler($ruta_raiz);
  $hist      = new Historico($db);
  if (!is_array($numrad))
    $numrad = [$numrad];
  
  if (isset($numrad, $_SESSION['dependencia'], $_SESSION['codusuario'], $_SESSION['dependencia'], $_SESSION['codusuario'], $tx_comentario, $tx_codigo)) {
    $hist->insertarHistorico($numrad, $_SESSION['dependencia'], $_SESSION['codusuario'], $_SESSION['dependencia'], $_SESSION['codusuario'], $tx_comentario, $tx_codigo);
    return json_encode($hist);
  } else {
    return "Se requieren todas las variables del historico";
  }
?>
