<?php
$coltp3Esp = '"'.$tip3Nombre[3][2].'"';

switch($db->driver)
{
	case 'mssql':
	{
		$isql = '';
	}break;
	case 'postgres':
	case 'postgres7':
	case 'oci8':
	{
		if(!empty($busqRadicado)){
			$isql = "(select to_timestamp(h.FECHA)::timestamp   as \"DAT_Fecha Evento\",
						us.USUA_NOMB AS \"Usuario\",
						us.USUA_DOC AS \"Documento Usuario\",
						h.IP AS \"Dirección IP\",
						CASE 
							WHEN LEFT(h.ISQL, 100) like 'UPDATE USUARIO SET USUA_SESION=%' THEN 'Inició sesión sistema'
							WHEN LEFT(h.ISQL, 100) like 'UPDATE RADICADO SET RADI_LEIDO=1%' THEN 
							'Ingreso a radicado: '	|| (SELECT (REGEXP_MATCHES(h.isql, 'RADI_NUME_RADI = (\d+)'))[1])
							ELSE NULL 
						END AS \"Tipo de Acción\"
				from			 
				SGD_AUDITORIA h
				join USUARIO us on h.usua_doc=us.USUA_DOC
			where 
				".(empty($whereUsuario)?'':$whereUsuario.' AND ')."
				LEFT(h.ISQL, 100) like 'UPDATE RADICADO SET RADI_LEIDO=1%' AND h.ISQL like '%$busqRadicado%'
			
			)";
		}else{
			$isql = "(select to_timestamp(h.FECHA)::timestamp   as \"DAT_Fecha Evento\",
						us.USUA_NOMB AS \"Usuario\",
						us.USUA_DOC AS \"Documento Usuario\",
						h.IP AS \"Dirección IP\",
						CASE 
							WHEN LEFT(h.ISQL, 100) like 'UPDATE USUARIO SET USUA_SESION=%' THEN 'Inició sesión sistema'
							WHEN LEFT(h.ISQL, 100) like 'UPDATE RADICADO SET RADI_LEIDO=1%' THEN 
							'Ingreso a radicado: '	|| (SELECT (REGEXP_MATCHES(h.isql, 'RADI_NUME_RADI = (\d+)'))[1]) -- Subquery to extract the value after RADI_NUME_RADI =
							ELSE NULL 
						END AS \"Tipo de Acción\"
				from			 
				SGD_AUDITORIA h
				join USUARIO us on h.usua_doc=us.USUA_DOC
			where ".$whereUsuario." and
			( 
				LEFT(h.ISQL, 100) like 'UPDATE USUARIO SET USUA_SESION=%' OR
				LEFT(h.ISQL, 100) like 'UPDATE RADICADO SET RADI_LEIDO=1%'
			)
			)";
		}
	}break;
	default:
	{	
		$isql = '';
	}break;
}
?>
