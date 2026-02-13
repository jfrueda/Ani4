<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$ruta_raiz = ".";
require_once($ruta_raiz."/include/db/ConnectionHandler.php");
require_once($ruta_raiz."/processConfig.php");
$db = new ConnectionHandler($ruta_raiz);


?>

<!-- formulario.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Formulario con Textarea</title>
</head>
<body>

    <h2>Escribe algo en el textarea</h2>

    <form method="post" action="">
        <textarea name="contenido" rows="10" cols="50" placeholder="Escribe aquí..."></textarea><br><br>
        <input type="submit" value="Enviar">
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Verificamos si se envió el contenido
        if (!empty($_POST['contenido'])) {
            $texto = $_POST['contenido'];
            $arreglo = explode(',', $texto);
            $exp = array_map('trim', $arreglo);    
            
            for($i = 0; $i < count($exp); $i++) {
                $sql = "select * from sgd_exp_expediente where sgd_exp_numero = '" . $exp[$i] . "'";
                $contadorRadi = 0;
                $contadorBorrador = 0;
                $radiFina = 0;
                $borraFina = 0;
                $radiAbi = 0;
                $borraAbi = 0;    
                $borrador = false;
                $rs = $db->query($sql);  
                while (!$rs->EOF){
                    if(substr($rs->fields["RADI_NUME_RADI"],0,1) == 2) {
                        $contadorRadi++;
                        $borrador = false;
                    } else {
                        $contadorBorrador++;
                        $borrador = true;
                    }

                        $sqlDepe = "select radi_depe_actu from radicado where radi_nume_radi = " . $rs->fields["RADI_NUME_RADI"];
                        $rsSqlDepe = $db->query($sqlDepe);  
                        if($rsSqlDepe->fields["RADI_DEPE_ACTU"] == 999) {
                            if($borrador) {
                                $borraFina++;
                            } else {
                                $radiFina++;
                            }
                        } else {
                            if($borrador) {
                                $borraAbi++;
                            } else {
                                $radiAbi++;
                            }
                        }
                    $rs->MoveNext();
                }
                echo $exp[$i] . ";" . $contadorRadi . ";" . $contadorBorrador . ";" . $radiFina . ";" . $borraFina . ";" . $radiAbi . ";" . $borraAbi;
                echo "<br>";
            }            
           
        } else {
            echo "<p>No escribiste nada.</p>";
        }
    }
    ?>

</body>
</html>
