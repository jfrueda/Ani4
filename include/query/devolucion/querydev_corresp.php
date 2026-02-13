<?
	/**
	  * CONSULTA VERIFICACION PREVIA A LA RADICACION
	  */
        //$db->con->debug = true;
        $ln= $_SESSION["digitosDependencia"];
	switch($db->driver)
	{  
	 case 'mssql':
	 
	 $systemDate = $db->conn->sysTimeStamp;
	 $sqlOffset = $db->conn->OffsetDate("a.sgd_fech_impres");
	 $redondeo = $db->conn->round($systemDate."-".$sqlOffset);
	 $where_depe = ' and '.$db->conn->substr.'(convert(char(15),radi_nume_salida,0), 5, 3) in ('.$lista_depcod .')';
	 
	 $isqlC = 'select  count(1) Numero
		from ANEXOS
		where 
		sgd_fech_impres <= '.$db->conn->DBTimeStamp($fecha_fin).'
		and  ANEX_ESTADO=3
		'.$where_like.'
		and '.$db->conn->substr.'(convert(char(15),radi_nume_salida), 5, 3) in ('.$lista_depcod .')
		and sgd_deve_codigo is null';
		$isql = 'select  
	        convert(char(15),a.radi_nume_salida) as "Numero Radicacion"
			,a.anex_radi_nume  as "HID_anex_radi_nume"
			,'.$db->conn->substr.'(convert(char(15),radi_nume_salida), 5, 3) as "Dependencia"
			,'.$fech_devol.' as "Fecha Devolucion"
			,'.$usua_devol.' as "Usuario Realiza Devolucion"
			,'.$redondeo.' as "Tiempo de Espera (Dias)"
		from ANEXOS a
		where
		sgd_fech_impres <= '.$db->conn->DBTimeStamp($fecha_fin).'
		and a.ANEX_ESTADO=3
		'.$where_like.'
		and '.$db->conn->substr.'(convert(char(15),radi_nume_salida), 5, 3) in ('.$lista_depcod .')
		and a.sgd_deve_codigo is null';
		$isqlF = 'select  
	        convert(char(15),a.radi_nume_salida) as radi_nume_salida
			,a.anex_radi_nume 
			, a.sgd_dir_tipo
			,'.$db->conn->substr.'(convert(char(15),radi_nume_salida), 5, 3) as "Dependencia"
			,'.$redondeo.' as "Tiempo de Espera (Dias)"
		from ANEXOS a
		where 
		sgd_fech_impres <= '.$db->conn->DBTimeStamp($fecha_fin).'
		and  ANEX_ESTADO=3 
		'.$where_like.'
		and '.$db->conn->substr.'(convert(char(15),radi_nume_salida), 5, 3) in ('.$lista_depcod .')
		and sgd_deve_codigo is null';

		$isqlU = 'update ANEXOS
			set anex_estado=2,
			sgd_deve_codigo=99
			where ANEX_ESTADO=3 AND anex_radi_nume='.$anex_radi_nume.'
			and '.$db->conn->substr.'(convert(char(15),radi_nume_salida, 5), 3) in ('.$lista_depcod .')
			 and sgd_deve_codigo is null 
			 ';
	break;		
	case 'oracle':
	case 'oci8':	
	case 'oci805':		
	$where_depe = ' and '.$db->conn->substr.'(radi_nume_salida, 5, 3) in ('.$lista_depcod .')';
	$sqlConcat = $db->conn->Concat("depe_codi","'-'","depe_nomb");
	
	$isqlC = 'select  count(1) Numero
		from ANEXOS
		where 
		sgd_fech_impres <= '.$db->conn->DBTimeStamp($fecha_fin).'
		and  ANEX_ESTADO=3
		'.$where_like.'
		and '.$db->conn->substr.'(radi_nume_salida, 5, 3) in ('.$lista_depcod .')
		and sgd_deve_codigo is null
		';
		$isql = 'select  
	        a.radi_nume_salida as "Numero Radicacion"
			,a.anex_radi_nume  as "HID_anex_radi_nume"
			,'.$db->conn->substr.'(radi_nume_salida, 5, 3) as "Dependencia"
			,'.$fech_devol.' as "Fecha Devolucion"
			,'.$usua_devol.' as "Usuario Realiza Devolucion"
			,round((sysdate - a.sgd_fech_impres),1) as "Tiempo de Espera (Dias)"
		from ANEXOS a
		where
		sgd_fech_impres <= '.$db->conn->DBTimeStamp($fecha_fin).'
		and a.ANEX_ESTADO=3
		'.$where_like.'
		and '.$db->conn->substr.'(radi_nume_salida, 5, 3) in ('.$lista_depcod .')
		and a.sgd_deve_codigo is null';
		$isqlF = 'select  
	        a.radi_nume_salida 
			,a.anex_radi_nume 
			, a.sgd_dir_tipo
			,'.$db->conn->substr.'(radi_nume_salida, 5, 3) as "Dependencia"
			,round((sysdate - a.sgd_fech_impres),1) as "T_ESPERA"
		from ANEXOS a
		where 
		sgd_fech_impres <= '.$db->conn->DBTimeStamp($fecha_fin).'
		and  ANEX_ESTADO=3 
		'.$where_like.'
		and '.$db->conn->substr.'(radi_nume_salida, 5, 3) in ('.$lista_depcod .')
		and sgd_deve_codigo is null';

		$isqlU = 'update ANEXOS
			set anex_estado=2,
			sgd_deve_codigo=99
			where ANEX_ESTADO=3 AND anex_radi_nume='.$anex_radi_nume.'
			and '.$db->conn->substr.'(radi_nume_salida, 5, 3) in ('.$lista_depcod .')
			 and sgd_deve_codigo is null 
			 ';
			
	break;
	case 'postgres':
		$where_depe = ' and cast('.$db->conn->substr.'(cast(radi_nume_salida as varchar(20)), 5, '.$ln.') as numeric) in ('.$lista_depcod .')';
		$sqlConcat = $db->conn->Concat("depe_codi","'-'","depe_nomb");
		$fecha_hoy = $db->conn->sysTimeStamp;
		$whereSubStrDepe = 'cast('.$db->conn->substr.'(cast(radi_nume_salida as varchar(20)), 5, '.$ln.') as numeric)';
		$isqlC = "select  count(1) as Numero
			from ANEXOS a,SGD_RAD_ENVIOS s
			where 
			ANEX_ESTADO=3
			".$where_like."
			and ".$whereSubStrDepe ." in (".$lista_depcod .")
			and sgd_deve_codigo is null
			and cast(radi_nume_salida as varchar) like '2%%%%%%%%%%%%%%%1'
			and (now()-anex_fech_anex) > '2 days'
			and (select count(*) from hist_eventos where radi_nume_radi = a.radi_nume_salida and sgd_ttr_codigo in (26,25)) =0
			and a.id=s.id_anexo
			and s.tipo='Físico'";

		$isql = 'select  
	        	a.radi_nume_salida as "Numero Radicacion"
			,a.radi_nume_salida  as "HID_anex_radi_nume"
			,'.$db->conn->substr.'(cast(radi_nume_salida as varchar(20)), 5, '.$ln. ') as "Dependencia"
			,r.radi_fech_radi as "Fecha Radicacion"
			, (now() - r.radi_fech_radi) as "Tiempo de Espera (Dias)"';

			$isql.=",concat('<input type=checkbox name=radicado[] value=',a.radi_nume_salida,' checked=checked>') as Seleccione ";
			
			$isql.='from ANEXOS a,SGD_RAD_ENVIOS s, RADICADO r
			where
			a.ANEX_ESTADO=3
			'.$where_like.'
			and '.$whereSubStrDepe .' in ('.$lista_depcod .')
			and (select count(*) from hist_eventos where radi_nume_radi = a.radi_nume_salida and sgd_ttr_codigo in (26,25)) = 0
			and a.sgd_deve_codigo is null';

		$isql.=" and cast(radi_nume_salida as varchar) like '2%%%%%%%%%%%%%%%1'
			and (now()-anex_fech_anex) > '2 days'
			and a.id=s.id_anexo
			and s.tipo='Físico'
			and a.radi_nume_salida=r.radi_nume_radi";

		$isqlF = 'select  
	        	a.radi_nume_salida 
			,a.anex_radi_nume 
			, a.sgd_dir_tipo
			,'.$db->conn->substr.'(cast(radi_nume_salida as varchar(20)), 5, '.$ln .') as "Dependencia"
			,('.$fecha_hoy.' - a.sgd_fech_impres) as "T_ESPERA"
			from ANEXOS a
			where 
			sgd_fech_impres <= '.$db->conn->DBTimeStamp($fecha_fin).'
			and  ANEX_ESTADO=3 
			'.$where_like.'
			and '.$whereSubStrDepe .' in ('.$lista_depcod .')
			and sgd_deve_codigo is null';
	
		$isqlU = 'update ANEXOS
			set anex_estado=2,
			sgd_deve_codigo=99
			where ANEX_ESTADO=3 AND anex_radi_nume='.$anex_radi_nume.'
			and '.$whereSubStrDepe .' in ('.$lista_depcod .')
			 and sgd_deve_codigo is null ';	
	}
?>
