<table><tr><td> </td></tr></table>
   <table class="table"  cellpad=2 cellspacing='2' WIDTH=100%  align='center' >
     <tr> 
	 <form name=formListado action='../envios/paramListaImpresos.php?<?=$encabezado?>' method=post>
      
      <div class="resBtn2">
        <img src="<?=$ruta_raiz?>/imagenes/estadoDocInfo.gif" height="30">
        <a href='<?=$pagina_actual?>?<?=$encabezado?> '></a>
        <input type=submit value="<?=$accion_sal2?>" name="Enviar" id="Enviar" valign="middle" class="btn btn-warning">		
      </div>
      
        <td width='50%' align='left' height="30" class="titulos2 resBtnorg" ><img src="<?=$ruta_raiz?>/imagenes/estadoDocInfo.gif" height="30">
        </td>
        <td width="50%" align="center" class="titulos2 resBtnorg" >
           <a href='<?=$pagina_actual?>?<?=$encabezado?> '></a>
           <input type=submit value="<?=$accion_sal2?>" name="Enviar" id="Enviar" valign="middle" class="btn btn-warning">			
        </td>

    </form>
		</tr>
     </table>
