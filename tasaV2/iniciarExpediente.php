<?php

$ruta_raiz = "..";
session_start();

if (!$_SESSION['dependencia'])
    header ("Location: $ruta_raiz/cerrar_session.php");

?>

<html>
<head>
<script src="jquery.js"></script>
<title>TASA - Masivos Expediente.</title>
<script type="text/javascript">

	var contador = 0;

	$(document).ready(function(){  		
        ejecutarProceso();
	});

	function ejecutarProceso() {
        
		contador++;			
		$.ajax({url: "masivaExpediente.php", 
						    type: "POST",
							data: { depeRadica : 0 },
							success: function(result){							    		
					    		$( "#D_Contenido" ).append( "<p>" + result + "</p>" );					    	
					    		/*if(!result.includes('*FIN*') && !result.includes('Error')) {
					    			setTimeout(function(){ ejecutarProceso(); }, 300);
					    		}*/

					    		if(!result.includes('*FIN*')) {
					    			setTimeout(function(){ ejecutarProceso(); }, 1000);
					    		}


					  		},
					  		error: function(XMLHttpRequest, textStatus, errorThrown) {
					  			console.log(XMLHttpRequest);
					  			console.log(textStatus);
					  			console.log(errorThrown);
					  			alert("Ocurrió un error, comuníquese con el administrador del sistema.");
					  		 }
					 });	
	}

</script>
</head>
<body>

<p>Proceso Iniciado......</p>
<div id="D_Contenido"></div>
</body>
</html>	