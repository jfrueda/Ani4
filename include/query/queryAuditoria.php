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
		$isql = '((
					select TO_CHAR(h.HIST_FECH, \'YYYY-MM-DD HH24:MI AM\') as "DAT_Fecha Transaccion",
								b.RADI_NUME_RADI as "HID_RADI_NUME_RADI",
								b.RADI_NUME_RADI as "Radicado",
								b.ra_asun as "Asunto",
								\'\' as "Expediente",
								us.USUA_NOMB AS "Usuario",
								us.USUA_DOC AS "Documento",
								h.HIST_OBSE AS "Observacion",
								ttr.SGD_TTR_DESCRIP AS "Tipo Transaccion"
					FROM HIST_EVENTOS h
						join USUARIO us on h.USUA_DOC=us.USUA_DOC
						join SGD_TTR_TRANSACCION ttr on h.SGD_TTR_CODIGO=ttr.SGD_TTR_CODIGO
						join radicado b on h.radi_nume_radi = b.radi_nume_radi
					where '.$whereUsuario.'
				)union(
					SELECT TO_CHAR(t.SGD_HFLD_FECH, \'YYYY-MM-DD HH24:MI AM\') as "DAT_Fecha Transaccion",
						t.RADI_NUME_RADI as "HID_RADI_NUME_RADI",
						t.RADI_NUME_RADI as "Radicado",
						NULL as "Asunto",
						t.SGD_EXP_NUMERO as "Expediente",
						us.USUA_NOMB AS "Usuario",
						us.USUA_DOC AS "Documento",
						t.SGD_HFLD_OBSERVA AS "Observacion",
						ttr.SGD_TTR_DESCRIP AS "Tipo Transaccion"
    				FROM public.sgd_hfld_histflujodoc t
						join SGD_TTR_TRANSACCION ttr on t.SGD_TTR_CODIGO=ttr.SGD_TTR_CODIGO
						join USUARIO us on t.USUA_DOC=us.USUA_DOC
					where '.$whereUsuarioExp.'
				) )';
	}break;
	default:
	{	
		$isql = '';
	}break;
}
?>
