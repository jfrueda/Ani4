<?php
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
		$isql = "SELECT l.id as id,
						l.modelo as modelo,
						l.transaccion as transaccion,
						l.registro_antes as registro_antes,
						l.registro_despues as registro_despues,
						l.usuario as usuario_id,
						COALESCE(us.USUA_NOMB, 'Usuario no encontrado') as usuario_nombre,
						TO_CHAR(l.fecha, 'YYYY-MM-DD HH24:MI:SS') as fecha
				 FROM public.log l
				 LEFT JOIN (SELECT DISTINCT ID, USUA_NOMB FROM USUARIO) us ON l.usuario = us.id
				 WHERE $whereLog
				 ORDER BY l.fecha DESC";
	}break;
	default:
	{	
		$isql = '';
	}break;
}
?>