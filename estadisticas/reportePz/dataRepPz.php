<?php
class DataRepPz
{
	protected $db;
	protected $memo_multip;

	public function __construct()
	{
		$ruta_raiz = (!empty($ruta_raiz)) ? $ruta_raiz : './../../';
		include_once("{$ruta_raiz}include/db/ConnectionHandler.php");
		//require ($_SERVER['DOCUMENT_ROOT'].'/include/db/ConnectionHandler.php');
		$this->db = new ConnectionHandler("{$ruta_raiz}");
	}

	public function getDependencia()
	{
		$sql = "SELECT DISTINCT d.depe_codi || '-' ||d.depe_nomb AS Dependencias FROM dependencia d INNER JOIN usuario u on d.depe_codi = u.depe_codi
				WHERE d.depe_estado = 1
				AND u.usua_esta = '1'
				ORDER BY Dependencias  ASC";

		$rs = $this->db->conn->Execute($sql);
		$dependencias = [];

		foreach($rs as $value)
		{
			$dependencias[]=$value;
		}
		return $dependencias;
	}

	public function getUsuaios($dep)
	{
		$sql = "SELECT u.usua_codi ||'-'|| u.usua_doc ||'-'|| u.usua_nomb AS usuarios FROM usuario u
				INNER JOIN dependencia d ON d.depe_codi = u.depe_codi
				WHERE d.depe_codi = '{$dep}' AND u.usua_esta = '1' ORDER BY u.usua_nomb ASC";

		$rs = $this->db->conn->Execute($sql);
		$usuarios = [];

		foreach ($rs as $value) {
			$usuarios[] = $value['USUARIOS'];
		}
		echo json_encode(['resp'=>$usuarios]);
	}

	public function getData($codiUsua, $docUsua, $depeUsua)
	{

		$sql = "SELECT c.carp_desc,
				COUNT(*) ,
				COUNT(CASE WHEN r.carp_per IN (0, 1) THEN 1 END) AS General,
    			COUNT(CASE WHEN r.carp_per = 0 THEN 1 END) AS Radicados,
				COUNT(DISTINCT CASE WHEN CAST(r.radi_nume_radi AS text) LIKE '30%' THEN r.radi_nume_radi END) AS Borradores,
				(select COUNT(*) as TOTAL
								from radicado b
								WHERE b.radi_nume_radi is not null
								and b.SGD_EANU_CODIGO is null
								and radi_nume_radi::text LIKE '%3'
								and (b.is_borrador is false or  b.radi_firma  is true)
								and (
								SELECT count(*)
								FROM SGD_DIR_DRECCIONES
								WHERE SGD_DIR_DRECCIONES.radi_nume_radi=b.radi_nume_radi  ) > 1
									and b.radi_depe_actu not in ('999')
									and (
									SELECT count(*)
									FROM SGD_DIR_DRECCIONES
									WHERE SGD_DIR_DRECCIONES.radi_nume_radi=b.radi_nume_radi AND sgd_doc_fun = '{$docUsua}' ) > 0
									and (
										SELECT count(t.*) 
											FROM hist_eventos t
											WHERE radi_nume_radi =  b.radi_nume_radi and usua_doc = '{$docUsua}'  and sgd_ttr_codigo = '13'
										) = 0) memoNormal

			FROM radicado r LEFT JOIN carpeta c ON r.carp_codi  = c.carp_codi
			WHERE r.radi_usua_actu = '{$codiUsua}' AND r.radi_depe_actu = '{$depeUsua}' 
			and (r.carp_per in(0,1))
			GROUP BY c.carp_desc
			order by c.carp_desc ASC";

			$rs = $this->db->conn->Execute($sql);

			$datos = [];

			foreach($rs as $value)
			{
				$datos[] = $value;
			}

			$rad = [];
			$borr = [];

			for ($i = 0; $i < count($datos); $i++) 
			{
				for ($j = 0; $j < count($datos[$i]); $j++) 
				{
					if ($j == 1) 
					{
						$rad [] =$datos[$i];
						$borr [] = $datos[$i]['BORRADORES'];
						$memMultNorm [] = $datos[$i]['MEMONORMAL'];
					}
				}
			}

			$this->memo_multipNorm = (isset($memMultNorm)) ?  max($memMultNorm) : '';
			$total_radicados = 0;
			foreach ($rad as $key => $value) 
			{
				if (isset($value['GENERAL'])) {
					$rad_gen += $value['GENERAL'] ;
				}
			
				switch ($value['CARP_DESC']) {
					case 'Entrada':
						$rad_ent = $value['RADICADOS'] + $this->memo_multipNorm;
						break;
					case 'Salida':
						$rad_sal = $value['RADICADOS']=="0"?null:$value['RADICADOS'];
						break;
					case 'Memorandos':
						$rad_mem = $value['RADICADOS']=="0"?null:$value['RADICADOS'];
						break;
					case 'Resoluciones':
						$rad_resol = $value['RADICADOS']=="0"?null:$value['RADICADOS'];
						break;
					case 'Circular Interna':
						$circ_int = $value['RADICADOS']=="0"?null:$value['RADICADOS'];
						break;
					case 'Circular Externa':
						$cir_ext = $value['RADICADOS']=="0"?null:$value['RADICADOS'];
						break;
					case 'Autos':
						$autos = $value['RADICADOS']=="0"?null:$value['RADICADOS'];
						break;
					case 'Vo.Bo.':
						$vobo = $value['RADICADOS']=="0"?null:$value['RADICADOS'];
						break;
					case 'Devueltos':
						$devueltos = $value['RADICADOS']=="0"?null:$value['RADICADOS'];
						break;
					case 'Jefe de Area':
						$jefe_area = $value['RADICADOS']=="0"?null:$value['RADICADOS'];
						break;
					default:
						$rad_ent[] = "";
						break;
				}
			}

			$sqlExp = "SELECT count(*) as cntExp from sgd_sexp_secexpedientes where usua_doc_responsable = '{$docUsua}' and (sgd_sexp_estado = 0 or sgd_sexp_estado is null)";
			$rs2 = $this->db->conn->Execute($sqlExp);
			$exp = $rs2->fields['CNTEXP'];

			$sqlInfor = "
				SELECT SUM(cntInf) AS cntInf
					FROM (
						SELECT COUNT(DISTINCT i.radi_nume_radi) AS cntInf
						FROM informados i
						INNER JOIN radicado r2 ON r2.radi_nume_radi = i.radi_nume_radi
						AND r2.is_borrador = FALSE
						WHERE i.depe_codi = '{$depeUsua}'
						AND i.usua_codi = {$codiUsua}
						AND i.info_codi IS NOT NULL
						GROUP BY i.radi_nume_radi
					) subquery;
				";
			$rs3 = $this->db->conn->Execute($sqlInfor);
			$infor = $rs3->fields['CNTINF'];

			$sqlMemNorm = "SELECT count(*) cntInf from tramiteconjunto i inner join radicado r2 on r2.radi_nume_radi  = i.radi_nume_radi and r2.is_borrador = false
						where i.depe_codi = '{$depeUsua}' and i.usua_codi = {$codiUsua} and i.info_codi is not null ";
			$rs4 = $this->db->conn->Execute($sqlMemNorm);
			$memMultInfr = $rs4->fields['CNTINF'];

		echo json_encode([
			'general'=>intval($rad_gen) + intval($this->memo_multipNorm),
			'entrada'=>$rad_ent,
			'Salida'=>$rad_sal,
			'Memos'=>$rad_mem,
			'Resol'=>$rad_resol,
			'circul_int'=>$circ_int,
			'cir_ext'=>$cir_ext,
			'autos'=>$autos,
			'vobo'=>$vobo,
			'devueltos'=>$devueltos,
			'jefe_area'=>$jefe_area,
			'mem_multi'=>$this->memo_multipNorm,
			'mem_multInform' => $memMultInfr,
			'informados' => $infor,
			'expedientes' => $exp,
			'status'=>($rs->EOF || $rs2->EOF || $rs3->EOF || $rs4->EOF) ? TRUE : FALSE,
			'query'=>$sql,
		]);
	}


	public function getDetalle($codiUsua, $docUsua, $depeUsua, $tp_rad)
	{
		if($tp_rad == 19) {$filtro = ''; $msm = 'general';}
		if($tp_rad == 1) {$filtro = 'AND c.carp_codi = 1'; $msm = 'salida';}
		if($tp_rad == 2) {$filtro = 'AND c.carp_codi = 0'; $msm = 'entrada';}
		if($tp_rad == 3) {$filtro = 'AND c.carp_codi = 3'; $msm = 'memorando';}
		if($tp_rad == 6) {$filtro = 'AND c.carp_codi = 6'; $msm = 'resolucion';}
		if($tp_rad == 8) {$filtro = 'AND c.carp_codi = 4'; $msm = 'cir_int';}
		if($tp_rad == 9) {$filtro = 'AND c.carp_codi = 5 AND carp_per = 0'; $msm = 'cir_ext';}
		if($tp_rad == 7) {$filtro = 'AND c.carp_codi = 7'; $msm = 'autos';}
		if($tp_rad == 11) {$filtro = 'AND c.carp_codi = 11'; $msm = 'vobo';}
		if($tp_rad == 12) {$filtro = 'AND c.carp_codi = 12'; $msm = 'devueltos';}
		if($tp_rad == 13) {$filtro = 'AND c.carp_codi = 13'; $msm = 'jefe_area';}
		if($tp_rad == 30) {$like = "AND CAST(r.radi_nume_radi as varchar(20)) LIKE '30%' AND c.carp_codi = 1"; $msm = 'borradores';}
		if($tp_rad == 18) {$filtro = 'AND c.carp_codi = 18'; $msm = 'memo_multip';}

		if($tp_rad == 4)
		{
			$sql = "SELECT DISTINCT
					inf.radi_nume_radi radicado,
					us.usua_nomb usuario,
					dep.depe_nomb dependencia
					from informados inf 
					INNER JOIN usuario us on inf.usua_codi = us.usua_codi
					INNER JOIN dependencia dep ON dep.depe_codi = us.depe_codi 
					INNER JOIN radicado rad on rad.radi_nume_radi = inf.radi_nume_radi
					WHERE 
					inf.info_codi IS NOT NULL 
					AND rad.is_borrador = false
					AND inf.depe_codi = '{$depeUsua}' 
					AND inf.usua_codi = {$codiUsua} 
					AND us.usua_doc = '{$docUsua}'";
			$msm = 'informados';
			
		}
		elseif($tp_rad == 5)
		{
			$sql = "SELECT 
					exp.sgd_exp_numero numero_exp, 
					exp.sgd_sexp_fech fecha_exp, 
					exp.sgd_sexp_ano anio_exp,
					us.usua_nomb nomb_resp,
					dep.depe_nomb dependencia
					FROM sgd_sexp_secexpedientes exp 
					INNER JOIN usuario us on exp.usua_doc_responsable = us.usua_doc
					INNER JOIN dependencia dep on dep.depe_codi = exp.depe_codi 
					where exp.usua_doc_responsable = '{$docUsua}' and (exp.sgd_sexp_estado = 0 or exp.sgd_sexp_estado is null)";
			$msm = 'expedientes';
		}
		elseif($tp_rad == 18)
		{
			$sql = "SELECT *
							from radicado b
							inner join usuario u on b.radi_usua_actu = u.usua_codi and b.radi_depe_actu = u.depe_codi
							inner join dependencia d on b.radi_depe_actu = d.depe_codi 
							WHERE b.radi_nume_radi is not null
									and b.radi_depe_actu not in ('999')
									and b.SGD_EANU_CODIGO is null
									and radi_nume_radi::text LIKE '%3'
									and (b.is_borrador is false or  b.radi_firma  is true)
									and (
								SELECT count(*)
								FROM SGD_DIR_DRECCIONES
								WHERE SGD_DIR_DRECCIONES.radi_nume_radi=b.radi_nume_radi  ) > 1
								and (
									SELECT count(*)
									FROM SGD_DIR_DRECCIONES
									WHERE SGD_DIR_DRECCIONES.radi_nume_radi=b.radi_nume_radi AND sgd_doc_fun = '{$docUsua}' ) > 0
									and (
										SELECT count(t.*) 
											FROM hist_eventos t
											WHERE radi_nume_radi =  b.radi_nume_radi and usua_doc = '{$docUsua}'  and sgd_ttr_codigo = '13'
								) = 0";
			$msm = 'memo_multip';

		}
		elseif($tp_rad == 20)
		{
			$sql = "SELECT DISTINCT
					inf.radi_nume_radi radicado,
					inf.info_fech fecha_informado,
					us.usua_nomb usuario,
					dep.depe_nomb dependencia
					from tramiteconjunto inf 
					INNER JOIN usuario us on inf.usua_codi = us.usua_codi
					INNER JOIN dependencia dep ON dep.depe_codi = us.depe_codi 
					INNER JOIN radicado rad on rad.radi_nume_radi = inf.radi_nume_radi
					WHERE 
					inf.info_codi IS NOT NULL 
					AND rad.is_borrador = false
					AND inf.depe_codi = '{$depeUsua}' 
					AND inf.usua_codi = {$codiUsua} 
					AND us.usua_doc = '{$docUsua}'";
			$msm = 'informados';
			
		}
		else
		{
			$memo_multiple_general = (in_array($tp_rad, [19, 2])) ? "union all
				(SELECT 
					distinct b.radi_nume_radi radicado,
					b.radi_fech_radi fecha_rad,
					u.usua_nomb usuario,
					d.depe_nomb dependencia,
					b.ra_asun asunto,
					b.radi_desc_anex anexos_desc,
					tdoc.sgd_tpr_descrip trd
						from radicado b
						inner join usuario u on b.radi_usua_actu = u.usua_codi and b.radi_depe_actu = u.depe_codi
						inner join sgd_tpr_tpdcumento tdoc on b.tdoc_codi = tdoc.sgd_tpr_codigo
						inner join dependencia d on b.radi_depe_actu = d.depe_codi 
						WHERE b.radi_nume_radi is not null
								and b.radi_depe_actu not in ('999')
								and b.SGD_EANU_CODIGO is null
								and radi_nume_radi::text LIKE '%3'
								and (b.is_borrador is false or  b.radi_firma  is true)
								and (
							SELECT count(*)
							FROM SGD_DIR_DRECCIONES
							WHERE SGD_DIR_DRECCIONES.radi_nume_radi=b.radi_nume_radi  ) > 1
							and (
								SELECT count(*)
								FROM SGD_DIR_DRECCIONES
								WHERE SGD_DIR_DRECCIONES.radi_nume_radi=b.radi_nume_radi AND sgd_doc_fun = '{$docUsua}' ) > 0
								and (
									SELECT count(t.*) 
										FROM hist_eventos t
										WHERE radi_nume_radi =  b.radi_nume_radi and usua_doc = '{$docUsua}'  and sgd_ttr_codigo = '13'
							) = 0)" : "";
			$sql= "(SELECT DISTINCT
					r.radi_nume_radi radicado,
					r.radi_fech_radi fecha_rad,
					u.usua_nomb usuario,
					d.depe_nomb dependencia,
					r.ra_asun asunto,
					r.radi_desc_anex anexos_desc,
					tdoc.sgd_tpr_descrip trd
				FROM radicado r 
					INNER JOIN usuario u ON r.radi_usua_actu = u.usua_codi
					INNER JOIN dependencia d on u.depe_codi = d.depe_codi
					INNER JOIN sgd_tpr_tpdcumento tdoc on r.tdoc_codi = tdoc.sgd_tpr_codigo
					LEFT JOIN carpeta c ON r.carp_codi  = c.carp_codi
				WHERE r.radi_usua_actu = '{$codiUsua}' 
				AND r.radi_depe_actu = '{$depeUsua}'
				and u.usua_doc = '{$docUsua}'
				{$like}
				{$filtro}){$memo_multiple_general}";
		}

		//die($sql);

		$rs = $this->db->conn->Execute($sql);

		$resp = array("resp"=>$msm,"query"=>$rs);
		return $resp;
	}

}