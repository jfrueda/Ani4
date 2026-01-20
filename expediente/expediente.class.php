<?php
include_once "$ruta_raiz/include/db/ConnectionHandler.php";

class expediente
{
    const PUBLICO = 0;
    const RESERVADO = 2;
    const CLASIFICADO = 4;
    private $ruta_raiz;
    private $link;
    private $mesesN = array('01' => "Enero", '02' => "Febrero", '03' => "Marzo", '04' => "Abril", '05' => "Mayo", '06' => "Junio", '07' => "Julio", '08' => "Agosto", '09' => "Septiembre", '10' => "Octubre", '11' => "Noviembre", '12' => "Diciembre");
    private $dbOld;
    private $depeNombre;

    public function __construct($ruta_raiz)
    {
        $this->ruta_raiz = $ruta_raiz;
        // parent::__construct();
        $this->link = new ConnectionHandler($ruta_raiz);
        //  include($ruta_raiz."processConfig.php");
        list($driver1, $host1, $user1, $pass1, $dbname1) = explode(',', $this->nomdb());
        $this->dbOld = ADONewConnection($driver1);
        //   echo "$host1,$user1,$pass1,$dbname1";
        $this->dbOld->Connect($host1, $user1, $pass1, $dbname1);
        //$this->dbOld->debug =true;
        //  $this->dblink = $this->nomdb();
        //  $this->depeNombre =$this->dependecias();
        //  $this->link->conn->debug =true;

    }

    public function nomdb()
    {
        $sql = "  select conf_valor valor from sgd_config where conf_nombre='superargo1'";
        $rs = $this->link->conn->query($sql);
        return $rs->fields['VALOR'];
        //  else return '';
    }

    public function listar($tp, $usua_doc, $usua_id, $depe, $anoDep, $search = '')
    {
        $from = '';
        $where = "s.usua_doc_responsable='$usua_doc'";
        if ($tp == 'dp') {
            $where = "s.depe_codi='$depe'"; //"and s.usua_doc_responsable<>'$usua_doc' ";
        }
        if ($tp == 'dpOLD') {
            $where .= "and  length(s.sgd_exp_numero) =18 ";
        } else {
            $where .= "and  length(s.sgd_exp_numero)!=18 ";
        }
        if ($tp == 'co') {
            $from = "sgd_aexp_aclexp a,";
            $where = " a.num_expediente = s.sgd_exp_numero and 
             ( a.aexp_acl>0 and( a.depe_codi=$depe and (a.usua_codi=$usua_id or a.usua_codi is null)))   ";
            // $where ="s.depe_codi='$depe' and s.usua_doc_responsable<>'$usua_doc'  s.usua_doc_responsable='$usua_doc' ";
        }
        if ($tp == 'ie') {
            $where = "s.indice_electronico IS NOT NULL";
        }

        if ($anoDep <> 'all')
            $where .= " and s.sgd_sexp_ano='$anoDep' ";

        $search_word = trim($search);
        if ($search_word != '')
            $where .= "AND (s.sgd_exp_numero ILIKE '%" . $search_word . "%' OR s.sgd_sexp_parexp1 ILIKE '%" . $search_word . "%')";

        $iSql = "select s.sgd_exp_numero num,to_char(s.sgd_sexp_fech, 'DD-MM-YYYY HH24:MI') fech,u.usua_nomb creador,s.sgd_sexp_parexp1 titulo,ub.usua_nomb responsable,d.depe_nomb depe,s.sgd_sexp_estado estado, indice_electronico
        from  $from sgd_sexp_secexpedientes s
        left join usuario u on u.usua_doc=s.usua_doc
        left join usuario ub on ub.usua_doc=s.usua_doc_responsable
        left join dependencia d on d.depe_codi=s.depe_codi where $where ";

        $rs = $this->link->conn->query($iSql);

        if (!$rs->EOF) {
            $i = 1;
            while (!$rs->EOF) {
                $dd['NUM'] = $i;
                foreach ($rs->fields as $key => $value) {
                    $dd[strtoupper($key)] = $value;
                }
                $datos[] = $dd;
                $i++;
                $rs->MoveNext();
            }
            //  $resp['titulo'] = $campot;
        }

        return $datos;
    }

    public function consultarExp($numExp)
    {
        $iSql = "
        select SEXP.*, u.usua_nomb creador,ub.usua_nomb responsable,d.depe_nomb depe,(select count(*) from sgd_aexp_aclexp where num_expediente='" . $numExp . "') permisos, seguridad
         from SGD_SEXP_SECEXPEDIENTES SEXP
        left join usuario u on u.usua_doc=SEXP.usua_doc
        left join usuario ub on ub.usua_doc=SEXP.usua_doc_responsable
        left join dependencia d on d.depe_codi=SEXP.depe_codi
        WHERE SEXP.SGD_EXP_NUMERO = '$numExp'
        
        ";

        $rs = $this->link->conn->query($iSql);

        if (!$rs->EOF) {
            foreach ($rs->fields as $key => $value) {
                $dd[strtoupper($key)] = $value;
            }
            $rs->MoveNext();
        }

        return $dd;
    }

    /**
     * 
     * @param type $expnum
     * @param type $filtro
     * @param type $ordem
     * @return type
     */

    function listar_anexos($expnum, $filtro, $ordem = 'asc', $tpc = 0, $pag = 1, $wbsq = null, $page_size = 1, $search = "")
    {
        $filtrosql = 'radica';
        $total = 0;
        switch ($filtro) {
            case 'ORrad':
                $filtrosql = 'radica,';
                break;
            case 'ORfech':
                $filtrosql = '';
                break;
            case 'ORtp':
                $filtrosql = 'tpdoc,';
                break;
            case 'ORremite':
                $filtrosql = 'remitente,';
                break;
            /*  case 'ORcarp':
              $filtrosql = 'carpeta';
              break; */
            case 'ORcarp':
                $filtrosql = 'carpeta,';
                break;
            case 'ORfisico':
                $filtrosql = 'fisico,';
                break;
            case 'ORsub':
                $filtrosql = 'subexp,';
                break;
            case 'ORasunto':
                $filtrosql = 'asunto,';
                break;
            case 'ORorden':
                $filtrosql = 'orden,';
                break;

            default:
                break;
        }
        $estadoExp = 0;
        $estadoaaExp = 'N';
        if ($tpc == 'E') {
            $estadoExp = '1,2';
            $estadoaaExp = 'S';
        }
        $whereX = '';
        $whereXc = '';
        $limit = 3000;
        if ($pag == 1)
            $of = 0;
        else
            $of = $limit * ($pag - 1);
        if (strlen($wbsq) >= 14) {
            $whereX = " and r.radi_nume_radi =$wbsq ";
            $whereXc = " and p.exp_consecutivo ='$wbsq' ";
            $whereXcf = " and p.exp_consecutivo =''$wbsq'' ";
            $of = 0;
        }

        /*         * anexos */
        $sqlanexosRad = "
               select 0 as anu,'anexo' as tipo, trim(cast(a.anex_radi_nume as character(30))) as radica,a.anex_codigo as anex , TO_CHAR(a.anex_fech_anex, 'YYYY/MM/DD HH24:MI:SS') fecha,anex_desc as asunto
               ,a.anex_nomb_archivo as path,'' carpeta,null fisico ,'' subexp,'' tpdoc,'' sr,radi_nume_salida rasal,'' remitente,'' orden,'/'||substr(cast(a.anex_radi_nume as character(30)), 1,4)||'/'||substr(cast(a.anex_radi_nume as character(30)), 5,3)||'/docs/' ruta from  sgd_exp_expediente e,anexos a where  a.anex_radi_nume=e.radi_nume_radi  and e.sgd_exp_numero='$expnum' and anex_borrado='N' and  e.sgd_exp_estado in ($estadoExp)
               order by radica,fecha ";
        $rsanex = $this->link->conn->query($sqlanexosRad);

        $datosAnex = array();
        $i = 1;
        if ($tpc != 'E')
            if (!$rsanex->EOF) {
                while (!$rsanex->EOF) {
                    foreach ($rsanex->fields as $key => $value) {
                        $datosAnex[$rsanex->fields['RADICA']][$rsanex->fields['ANEX']][strtoupper($key)] = $value;
                    }
                    $rsanex->MoveNext();
                    $i++;
                }
            }
        //  print_r($datosAnex); and(sgd_eanu_codigo not in (1,2) or sgd_eanu_codigo is null)
        /* radicado y  anesox expediente
        (select count(1) 
                    from sgd_aexp_anexexpediente p,SGD_TPR_TPDCUMENTO pt where p.num_expediente ='$expnum' and p.aexp_tpdoc=pt.sgd_tpr_codigo and p.aexp_borrado='$estadoaaExp'   $whereXc) num
                   
          + (select count(1) from sgd_exp_anexos where  exp_anex_nomb_archivo like '20210900010400001E%' and p.aexp_borrado='$estadoaaExp') num           */

        $sqlpag = "select  (select count(1) 
                    from radicado r ,sgd_exp_expediente e ,SGD_TPR_TPDCUMENTO tpr,sgd_dir_drecciones g
                    where  r.radi_nume_radi=e.radi_nume_radi and e.sgd_exp_numero='$expnum' and g.radi_nume_radi=r.radi_nume_radi and e.sgd_exp_estado in ($estadoExp) and  r.tdoc_codi=tpr.sgd_tpr_codigo   and g.sgd_dir_tipo=1  $whereX)
                    numr,(select count(1) 
                    from radicado r ,sgd_exp_expediente e ,SGD_TPR_TPDCUMENTO tpr,sgd_dir_drecciones g
                    where  r.radi_nume_radi=e.radi_nume_radi and e.sgd_exp_numero='$expnum' and g.radi_nume_radi=r.radi_nume_radi and e.sgd_exp_estado in ($estadoExp) and  r.tdoc_codi=tpr.sgd_tpr_codigo   and g.sgd_dir_tipo=1  $whereX)
                    +(select count(1) from sgd_exp_anexos where  exp_anex_nomb_archivo like '$expnum%' and exp_anex_borrado='$estadoaaExp' $whereXc)
                     numt,(select count(1) from sgd_exp_anexos where  exp_anex_nomb_archivo like '$expnum%' and exp_anex_borrado='$estadoaaExp' $whereXc) num ";
        $rs2 = $this->link->conn->query($sqlpag);
        $regis2 = $rs2->fields['NUMR'];
        $regis = $rs2->fields['NUM'];
        $regist = $rs2->fields['NUMT'];
        $validad = $regist / $limit;
        $paginas = ceil(($regist / $limit));


        $filter = '';
        $filter_anex = '';

        if (trim($search) != '') {
            $filter = "AND (r.radi_nume_radi::text LIKE '%" . $search . "%' OR r.ra_asun LIKE '%" . $search . "%' OR g.sgd_dir_nomremdes LIKE '%" . $search . "%')";
            $filter_anex = "AND (p.exp_anex_nomb_archivo LIKE '%" . $search . "%' OR p.exp_anex_desc LIKE '%" . $search . "%')";
        }

        $importado_sql = 'SELECT 1 FROM sgd_sexp_secexpedientes WHERE sgd_exp_numero = ?';
        $importado = $this->link->conn->getOne($importado_sql, [$expnum]);

        if (strlen($expnum) == 18 && $importado != '1') {
            $sql = "
                select 0 as anu,'aexpe' as tipo,trim(cast(p.exp_consecutivo as character(18))) as radica,' ' as anex ,TO_CHAR(p.exp_anex_radi_fech, 'YYYY/MM/DD HH24:MI:SS') fecha,p.exp_anex_desc as asunto,p.exp_anex_nomb_archivo path,
                p.exp_carpeta carpeta,trim(cast(p.exp_subexp as character(18))) subexpb,pt.SGD_TPR_DESCRIP tpdoc,p.exp_fisico fisico,'' sr,'' remitente
                from sgd_exp_anexos p left join SGD_TPR_TPDCUMENTO pt on p.exp_tpdoc=pt.sgd_tpr_codigo where  p.exp_anex_nomb_archivo like '$expnum%' and p.exp_anex_borrado='$estadoaaExp'  $whereXc";
        } else {
            $sql = "
                select 0 as anu,'aexp' as tipo,trim(cast(p.exp_consecutivo as character(18))) as radica,' ' as anex ,TO_CHAR(p.exp_anex_radi_fech, 'YYYY/MM/DD HH24:MI:SS') fecha,p.exp_anex_desc as asunto,p.exp_anex_nomb_archivo path,
                p.exp_carpeta carpeta,trim(cast(p.exp_subexp as character(18))) subexpb,pt.SGD_TPR_DESCRIP tpdoc,p.exp_fisico fisico,'' sr,'' remitente
                from sgd_exp_anexos p,SGD_TPR_TPDCUMENTO pt  where  p.exp_anex_nomb_archivo like '$expnum%' and p.exp_anex_borrado='$estadoaaExp' and p.exp_tpdoc=pt.sgd_tpr_codigo $filter_anex $whereXc"
                . "order by $filtrosql fecha $ordem";
        }

        $total = $this->link->conn->getOne("SELECT COUNT(*) FROM (" . $sql . ") as total");
        $sql .= ' LIMIT ' . $page_size . ' OFFSET ' . (($pag - 1) * $page_size);

        if (strlen($expnum) == 18 && $importado != '1') {
            $rs = $this->dbOld->Execute($sql);
        } else {
            $rs = $this->link->conn->query($sql);
        }

        $datos = array();
        $i = 1;
        if (!$rs->EOF) {
            while (!$rs->EOF) {
                foreach ($rs->fields as $key => $value) {
                    $datos[$i][strtoupper($key)] = $value;
                }
                if ($rs->fields['TIPO'] == 'radi') {
                }
                $rs->MoveNext();
                $i++;
            }
        }

        $res['pagActal'] = $pag ? $pag : 1;
        $res['numReg'] = $regis;
        $res['numRad'] = $regis2;
        $res['numt'] = $regist;
        $res['pag'] = $paginas;
        $res['datos'] = $datos;
        $res['anexos'] = [];
        $res['total'] = $total;
        $res['page_size'] = $page_size;
        return $res;
    }

    function listar_radicados($expnum, $filtro, $ordem = 'asc', $tpc = 0, $pag = 1, $wbsq = null, $page_size = 100, $search = "")
    {
        $filtrosql = 'radica';
        $total = 0;
        switch ($filtro) {
            case 'ORrad':
                $filtrosql = 'radica,';
                break;
            case 'ORfech':
                $filtrosql = '';
                break;
            case 'ORtp':
                $filtrosql = 'tpdoc,';
                break;
            case 'ORremite':
                $filtrosql = 'remitente,';
                break;
            /*  case 'ORcarp':
              $filtrosql = 'carpeta';
              break; */
            case 'ORcarp':
                $filtrosql = 'carpeta,';
                break;
            case 'ORfisico':
                $filtrosql = 'fisico,';
                break;
            case 'ORsub':
                $filtrosql = 'subexp,';
                break;
            case 'ORasunto':
                $filtrosql = 'asunto,';
                break;
            case 'ORorden':
                $filtrosql = 'orden,';
                break;

            default:
                break;
        }
        $estadoExp = 0;
        $estadoaaExp = 'N';
        if ($tpc == 'E') {
            $estadoExp = '1,2';
            $estadoaaExp = 'S';
        }
        $whereX = '';
        $whereXc = '';
        $limit = 3000;
        if ($pag == 1)
            $of = 0;
        else
            $of = $limit * ($pag - 1);
        if (strlen($wbsq) >= 14) {
            $whereX = " and r.radi_nume_radi =$wbsq ";
            $whereXc = " and p.exp_consecutivo ='$wbsq' ";
            $whereXcf = " and p.exp_consecutivo =''$wbsq'' ";
            $of = 0;
        }

        /*         * anexos */
        $sqlanexosRad = "
               select 0 as anu,'anexo' as tipo, trim(cast(a.anex_radi_nume as character(30))) as radica,a.anex_codigo as anex , TO_CHAR(a.anex_fech_anex, 'YYYY/MM/DD HH24:MI:SS') fecha,anex_desc as asunto
               ,a.anex_nomb_archivo as path,'' carpeta,null fisico ,'' subexp,'' tpdoc,'' sr,radi_nume_salida rasal,'' remitente,'' orden,'/'||substr(cast(a.anex_radi_nume as character(30)), 1,4)||'/'||substr(cast(a.anex_radi_nume as character(30)), 5,3)||'/docs/' ruta from  sgd_exp_expediente e,anexos a where  a.anex_radi_nume=e.radi_nume_radi  and e.sgd_exp_numero='$expnum' and anex_borrado='N' and  e.sgd_exp_estado in ($estadoExp)
               order by radica,fecha ";
        //echo $sqlanexosRad;
        $rsanex = $this->link->conn->query($sqlanexosRad);

        $datosAnex = array();
        $i = 1;
        if ($tpc != 'E')
            if (!$rsanex->EOF) {
                while (!$rsanex->EOF) {
                    foreach ($rsanex->fields as $key => $value) {
                        $datosAnex[$rsanex->fields['RADICA']][$rsanex->fields['ANEX']][strtoupper($key)] = $value;
                    }
                    $rsanex->MoveNext();
                    $i++;
                }
            }
        //  print_r($datosAnex); and(sgd_eanu_codigo not in (1,2) or sgd_eanu_codigo is null)
        /* radicado y  anesox expediente
        (select count(1) 
                    from sgd_aexp_anexexpediente p,SGD_TPR_TPDCUMENTO pt where p.num_expediente ='$expnum' and p.aexp_tpdoc=pt.sgd_tpr_codigo and p.aexp_borrado='$estadoaaExp'   $whereXc) num
                   
          + (select count(1) from sgd_exp_anexos where  exp_anex_nomb_archivo like '20210900010400001E%' and p.aexp_borrado='$estadoaaExp') num           */

        $sqlpag = "select  (select count(1) 
                    from radicado r ,sgd_exp_expediente e ,SGD_TPR_TPDCUMENTO tpr,sgd_dir_drecciones g
                    where  r.radi_nume_radi=e.radi_nume_radi and e.sgd_exp_numero='$expnum' and g.radi_nume_radi=r.radi_nume_radi and e.sgd_exp_estado in ($estadoExp) and  r.tdoc_codi=tpr.sgd_tpr_codigo   and g.sgd_dir_tipo=1  $whereX)
                    numr,(select count(1) 
                    from radicado r ,sgd_exp_expediente e ,SGD_TPR_TPDCUMENTO tpr,sgd_dir_drecciones g
                    where  r.radi_nume_radi=e.radi_nume_radi and e.sgd_exp_numero='$expnum' and g.radi_nume_radi=r.radi_nume_radi and e.sgd_exp_estado in ($estadoExp) and  r.tdoc_codi=tpr.sgd_tpr_codigo   and g.sgd_dir_tipo=1  $whereX)
                    +(select count(1) from sgd_exp_anexos where  exp_anex_nomb_archivo like '$expnum%' and exp_anex_borrado='$estadoaaExp' $whereXc)
                     numt,(select count(1) from sgd_exp_anexos where  exp_anex_nomb_archivo like '$expnum%' and exp_anex_borrado='$estadoaaExp' $whereXc) num ";
        $rs2 = $this->link->conn->query($sqlpag);
        $regis2 = $rs2->fields['NUMR'];
        $regis = $rs2->fields['NUM'];
        $regist = $rs2->fields['NUMT'];
        $validad = $regist / $limit;
        $paginas = ceil(($regist / $limit));

        $filter = '';

        $filter = '';
        $filter_anex = '';

        if (trim($search) != '') {
            $filter = "AND (r.radi_nume_radi::text LIKE '%" . $search . "%' OR r.ra_asun LIKE '%" . $search . "%' OR g.sgd_dir_nomremdes LIKE '%" . $search . "%')";
            $filter_anex = "AND (p.exp_anex_nomb_archivo LIKE '%" . $search . "%' OR p.exp_anex_desc LIKE '%" . $search . "%')";
        }

        $importado_sql = 'SELECT 1 FROM sgd_sexp_secexpedientes WHERE sgd_exp_numero = ?';
        $importado = $this->link->conn->getOne($importado_sql, [$expnum]);

        if (strlen($expnum) == 18 && $importado != '1') {
            $sql = "select sgd_eanu_codigo as anu, 'radie' as tipo,trim(cast(r.radi_nume_radi as character(18))) as radica,'' as anex, TO_CHAR(r.radi_fech_radi, 'YYYY/MM/DD HH24:MI:SS') fecha,r.ra_asun asunto,r.radi_path as  path, 
                e.sgd_exp_carpeta  carpeta,trim(cast(e.SGD_EXP_SUBEXPEDIENTE  as character(18))) subexpb,tpr.sgd_tpr_descrip tpdoc,e.sgd_exp_ufisica fisico,
                (select mr.sgd_srd_codigo||' | '||mr.sgd_sbrd_codigo||'**' || tpd.SGD_TPR_DESCRIP from sgd_rdf_retdocf rf,sgd_mrd_matrird mr,sgd_sexp_secexpedientes se ,SGD_TPR_TPDCUMENTO tpd
                where rf.radi_nume_radi=r.radi_nume_radi and mr.sgd_mrd_codigo=rf.sgd_mrd_codigo and e.sgd_exp_numero=se.sgd_exp_numero   and rf.radi_nume_radi=e.radi_nume_radi and tpd.sgd_tpr_codigo=mr.sgd_tpr_codigo
                and e.sgd_exp_numero=se.sgd_exp_numero and mr.sgd_srd_codigo=se.sgd_srd_codigo and mr.sgd_sbrd_codigo=se.sgd_sbrd_codigo limit 1)  sr,g.sgd_dir_nomremdes remitente
                from radicado r ,sgd_exp_expediente e ,SGD_TPR_TPDCUMENTO tpr,sgd_dir_drecciones g
                where  r.radi_nume_radi=e.radi_nume_radi and e.sgd_exp_numero='$expnum' and g.radi_nume_radi=r.radi_nume_radi $whereXcf    and  r.tdoc_codi=tpr.sgd_tpr_codigo   and g.sgd_dir_tipo=1 $filter
                ";
        } else {
            $sql = "select sgd_eanu_codigo as anu, 'radi' as tipo,trim(cast(r.radi_nume_radi as character(18))) as radica,'' as anex, TO_CHAR(r.radi_fech_radi, 'YYYY/MM/DD HH24:MI:SS') fecha,r.ra_asun asunto,r.radi_path as  path, 
                e.sgd_exp_carpeta  carpeta,trim(cast(e.SGD_EXP_SUBEXPEDIENTE  as character(18))) subexpb,tpr.sgd_tpr_descrip tpdoc,e.sgd_exp_ufisica fisico,
                (select mr.sgd_srd_codigo||' | '||mr.sgd_sbrd_codigo||'**' || tpd.SGD_TPR_DESCRIP from sgd_rdf_retdocf rf,sgd_mrd_matrird mr,sgd_sexp_secexpedientes se ,SGD_TPR_TPDCUMENTO tpd
                    where rf.radi_nume_radi=r.radi_nume_radi and mr.sgd_mrd_codigo=rf.sgd_mrd_codigo and e.sgd_exp_numero=se.sgd_exp_numero   and rf.radi_nume_radi=e.radi_nume_radi and tpd.sgd_tpr_codigo=mr.sgd_tpr_codigo
                    and e.sgd_exp_numero=se.sgd_exp_numero and mr.sgd_srd_codigo=se.sgd_srd_codigo and mr.sgd_sbrd_codigo=se.sgd_sbrd_codigo limit 1)  sr,g.sgd_dir_nomremdes remitente
                    from radicado r ,sgd_exp_expediente e ,SGD_TPR_TPDCUMENTO tpr,sgd_dir_drecciones g
                    where  r.radi_nume_radi=e.radi_nume_radi and e.sgd_exp_numero='$expnum' and g.radi_nume_radi=r.radi_nume_radi and e.sgd_exp_estado in ($estadoExp)   $whereX and  r.tdoc_codi=tpr.sgd_tpr_codigo and g.sgd_dir_tipo=1 $filter";
        }

        $total = $this->link->conn->getOne("SELECT COUNT(*) FROM (" . $sql . ") as total");
        $sql .= ' LIMIT ' . $page_size . ' OFFSET ' . (($pag - 1) * $page_size);
        if (strlen($expnum) == 18 && $importado != '1') {
            $rs = $this->dbOld->Execute($sql);
        } else {
            $rs = $this->link->conn->query($sql);
        }

        $datos = array();
        $i = 1;
        if (!$rs->EOF) {
            while (!$rs->EOF) {
                foreach ($rs->fields as $key => $value) {
                    $datos[$i][strtoupper($key)] = $value;
                }
                if ($rs->fields['TIPO'] == 'radi') {
                }
                $rs->MoveNext();
                $i++;
            }
        }

        //print_r($datos);
        $res['pagActal'] = $pag ? $pag : 1;
        $res['numReg'] = $regis;
        $res['numRad'] = $regis2;
        $res['numt'] = $regist;
        $res['pag'] = $paginas;
        $res['datos'] = $datos;
        $res['anexos'] = $datosAnex;
        $res['total'] = $total;
        $res['page_size'] = $page_size;
        return $res;
    }

    function listardtexp($expnum, $filtro, $ordem = 'asc', $tpc = 0, $pag = 1, $wbsq = null, $page_size = 100, $search = "")
    {
        $filtrosql = 'radica';
        $total = 0;
        switch ($filtro) {
            case 'ORrad':
                $filtrosql = 'radica,';
                break;
            case 'ORfech':
                $filtrosql = '';
                break;
            case 'ORtp':
                $filtrosql = 'tpdoc,';
                break;
            case 'ORremite':
                $filtrosql = 'remitente,';
                break;
            /*  case 'ORcarp':
              $filtrosql = 'carpeta';
              break; */
            case 'ORcarp':
                $filtrosql = 'carpeta,';
                break;
            case 'ORfisico':
                $filtrosql = 'fisico,';
                break;
            case 'ORsub':
                $filtrosql = 'subexp,';
                break;
            case 'ORasunto':
                $filtrosql = 'asunto,';
                break;
            case 'ORorden':
                $filtrosql = 'orden,';
                break;

            default:
                break;
        }
        $estadoExp = 0;
        $estadoaaExp = 'N';
        if ($tpc == 'E') {
            $estadoExp = '1,2';
            $estadoaaExp = 'S';
        }
        $whereX = '';
        $whereXc = '';
        $limit = 3000;
        if ($pag == 1)
            $of = 0;
        else
            $of = $limit * ($pag - 1);
        if (strlen($wbsq) >= 14) {
            $whereX = " and r.radi_nume_radi =$wbsq ";
            $whereXc = " and p.exp_consecutivo ='$wbsq' ";
            $whereXcf = " and p.exp_consecutivo =''$wbsq'' ";
            $of = 0;
        }

        /*         * anexos */
        $sqlanexosRad = "
               select 0 as anu,'anexo' as tipo, trim(cast(a.anex_radi_nume as character(30))) as radica,a.anex_codigo as anex , TO_CHAR(a.anex_fech_anex, 'YYYY/MM/DD HH24:MI:SS') fecha,anex_desc as asunto
               ,a.anex_nomb_archivo as path,'' carpeta,null fisico ,'' subexp,'' tpdoc,'' sr,radi_nume_salida rasal,'' remitente,'' orden,'/'||substr(cast(a.anex_radi_nume as character(30)), 1,4)||'/'||substr(cast(a.anex_radi_nume as character(30)), 5,3)||'/docs/' ruta from  sgd_exp_expediente e,anexos a where  a.anex_radi_nume=e.radi_nume_radi  and e.sgd_exp_numero='$expnum' and anex_borrado='N' and  e.sgd_exp_estado in ($estadoExp)
               order by radica,fecha ";
        $rsanex = $this->link->conn->query($sqlanexosRad);

        $datosAnex = array();
        $i = 1;
        if ($tpc != 'E')
            if (!$rsanex->EOF) {
                while (!$rsanex->EOF) {
                    foreach ($rsanex->fields as $key => $value) {
                        $datosAnex[$rsanex->fields['RADICA']][$rsanex->fields['ANEX']][strtoupper($key)] = $value;
                    }
                    $rsanex->MoveNext();
                    $i++;
                }
            }
        //  print_r($datosAnex); and(sgd_eanu_codigo not in (1,2) or sgd_eanu_codigo is null)
        /* radicado y  anesox expediente
        (select count(1) 
                    from sgd_aexp_anexexpediente p,SGD_TPR_TPDCUMENTO pt where p.num_expediente ='$expnum' and p.aexp_tpdoc=pt.sgd_tpr_codigo and p.aexp_borrado='$estadoaaExp'   $whereXc) num
                   
          + (select count(1) from sgd_exp_anexos where  exp_anex_nomb_archivo like '20210900010400001E%' and p.aexp_borrado='$estadoaaExp') num           */

        $sqlpag = "select  (select count(1) 
                    from radicado r ,sgd_exp_expediente e ,SGD_TPR_TPDCUMENTO tpr,sgd_dir_drecciones g
                    where  r.radi_nume_radi=e.radi_nume_radi and e.sgd_exp_numero='$expnum' and g.radi_nume_radi=r.radi_nume_radi and e.sgd_exp_estado in ($estadoExp) and  r.tdoc_codi=tpr.sgd_tpr_codigo   and g.sgd_dir_tipo=1  $whereX)
                    numr,(select count(1) 
                    from radicado r ,sgd_exp_expediente e ,SGD_TPR_TPDCUMENTO tpr,sgd_dir_drecciones g
                    where  r.radi_nume_radi=e.radi_nume_radi and e.sgd_exp_numero='$expnum' and g.radi_nume_radi=r.radi_nume_radi and e.sgd_exp_estado in ($estadoExp) and  r.tdoc_codi=tpr.sgd_tpr_codigo   and g.sgd_dir_tipo=1  $whereX)
                    +(select count(1) from sgd_exp_anexos where  exp_anex_nomb_archivo like '$expnum%' and exp_anex_borrado='$estadoaaExp' $whereXc)
                     numt,(select count(1) from sgd_exp_anexos where  exp_anex_nomb_archivo like '$expnum%' and exp_anex_borrado='$estadoaaExp' $whereXc) num ";
        $rs2 = $this->link->conn->query($sqlpag);
        $regis2 = $rs2->fields['NUMR'];
        $regis = $rs2->fields['NUM'];
        $regist = $rs2->fields['NUMT'];
        $validad = $regist / $limit;
        $paginas = ceil(($regist / $limit));
        //           if($validad>$paginas)
         //       $paginas=$paginas+1;
         //  print_r($rs2);
        /**
         * 
                union
               select 0 as anu,'aexp' as tipo,p.aexp_codi as radica,' ' as anex ,TO_CHAR(p.exp_fech, 'YYYY/MM/DD HH24:MI:SS') fecha,p.aexp_asunto as asunto,p.aexp_nombre path,
               (select carp.num_carpeta from arch_carp_carpeta carp where  carp.sgd_num_expediente=p.num_expediente and  carp.anex_exp=p.aexp_codi  ) carpeta,p.AEXP_FISICO fisico,p.AEXP_SUBEXP subexp,pt.SGD_TPR_DESCRIP tpdoc,'' sr,'' remitente,
                (select num_posion from sgd_oexp_orderexp where num_expediente='$expnum'  and trim(num_item)=p.aexp_codi limit 1 ) orden,p.aexp_path as ruta
               from sgd_aexp_anexexpediente p,SGD_TPR_TPDCUMENTO pt where p.num_expediente ='$expnum' and p.aexp_tpdoc=pt.sgd_tpr_codigo and p.aexp_borrado='$estadoaaExp'   $whereXc
                (select carp.num_carpeta from arch_carp_carpeta carp where  carp.sgd_num_expediente=e.sgd_exp_numero and r.radi_nume_radi=carp.radi_nume_radi and   carp.anex_nume='' and carp.anex_exp='')

                    ,
                 (select num_posion from sgd_oexp_orderexp where num_expediente='$expnum'  and trim(num_item)=trim(cast(r.radi_nume_radi as character(18)))) orden,substr(r.radi_path, 0,10)
                 select count(1) from sgd_exp_anexos where  exp_anex_nomb_archivo like '$expnum%' and p.aexp_borrado='$estadoaaExp') num
         */
        //$paginas=$pagina1+$pagina2;

        $filter = '';
        $filter_anex = '';

        if (trim($search) != '') {
            $filter = "AND (r.radi_nume_radi::text LIKE '%" . $search . "%' OR r.ra_asun LIKE '%" . $search . "%' OR g.sgd_dir_nomremdes LIKE '%" . $search . "%')";
            $filter_anex = "AND (p.exp_anex_nomb_archivo LIKE '%" . $search . "%' OR p.exp_anex_desc LIKE '%" . $search . "%')";
        }

        $importado_sql = 'SELECT 1 FROM sgd_sexp_secexpedientes WHERE sgd_exp_numero = ?';
        $importado = $this->link->conn->getOne($importado_sql, [$expnum]);

        if (strlen($expnum) == 18 && $importado != '1') {
            $sql = "select sgd_eanu_codigo as anu, 'radie' as tipo,trim(cast(r.radi_nume_radi as character(18))) as radica,'' as anex, TO_CHAR(r.radi_fech_radi, 'YYYY/MM/DD HH24:MI:SS') fecha,r.ra_asun asunto,r.radi_path as  path, 
                e.sgd_exp_carpeta  carpeta,trim(cast(e.SGD_EXP_SUBEXPEDIENTE  as character(18))) subexpb,tpr.sgd_tpr_descrip tpdoc,e.sgd_exp_ufisica fisico,
                (select mr.sgd_srd_codigo||' | '||mr.sgd_sbrd_codigo||'**' || tpd.SGD_TPR_DESCRIP from sgd_rdf_retdocf rf,sgd_mrd_matrird mr,sgd_sexp_secexpedientes se ,SGD_TPR_TPDCUMENTO tpd
                where rf.radi_nume_radi=r.radi_nume_radi and mr.sgd_mrd_codigo=rf.sgd_mrd_codigo and e.sgd_exp_numero=se.sgd_exp_numero   and rf.radi_nume_radi=e.radi_nume_radi and tpd.sgd_tpr_codigo=mr.sgd_tpr_codigo
                and e.sgd_exp_numero=se.sgd_exp_numero and mr.sgd_srd_codigo=se.sgd_srd_codigo and mr.sgd_sbrd_codigo=se.sgd_sbrd_codigo limit 1)  sr,g.sgd_dir_nomremdes remitente, r.sgd_spub_codigo seguridad, r.RADI_DEPE_ACTU depe_actu, r.RADI_USUA_ACTU usua_actu, r.RADI_DEPE_RADI depe_radi, r.RADI_USUA_RADI usua_radi
                from radicado r ,sgd_exp_expediente e ,SGD_TPR_TPDCUMENTO tpr,sgd_dir_drecciones g
                where  r.radi_nume_radi=e.radi_nume_radi and e.sgd_exp_numero='$expnum' and g.radi_nume_radi=r.radi_nume_radi $whereXcf    and  r.tdoc_codi=tpr.sgd_tpr_codigo and g.sgd_dir_tipo=1 $filter
                " . " union
                select 0 as anu,'aexpe' as tipo,trim(cast(p.exp_consecutivo as character(18))) as radica,' ' as anex ,TO_CHAR(p.exp_anex_radi_fech, 'YYYY/MM/DD HH24:MI:SS') fecha,p.exp_anex_desc as asunto,p.exp_anex_nomb_archivo path,
                p.exp_carpeta carpeta,trim(cast(p.exp_subexp as character(18))) subexpb,pt.SGD_TPR_DESCRIP tpdoc,p.exp_fisico fisico,'' sr,'' remitente, 0 seguridad, 0 depe_actu, 0 usua_actu, 0 depe_radi, 0 usua_radi
                from sgd_exp_anexos p left join SGD_TPR_TPDCUMENTO pt on p.exp_tpdoc=pt.sgd_tpr_codigo where  p.exp_anex_nomb_archivo like '$expnum%' and p.exp_anex_borrado='$estadoaaExp'  $whereXc";
        } else {
            $sql = "select sgd_eanu_codigo as anu, 'radi' as tipo,trim(cast(r.radi_nume_radi as character(18))) as radica,'' as anex, TO_CHAR(r.radi_fech_radi, 'YYYY/MM/DD HH24:MI:SS') fecha,r.ra_asun asunto,r.radi_path as  path, 
                e.sgd_exp_carpeta  carpeta,trim(cast(e.SGD_EXP_SUBEXPEDIENTE  as character(18))) subexpb,tpr.sgd_tpr_descrip tpdoc,e.sgd_exp_ufisica fisico,
                (select mr.sgd_srd_codigo||' | '||mr.sgd_sbrd_codigo||'**' || tpd.SGD_TPR_DESCRIP from sgd_rdf_retdocf rf,sgd_mrd_matrird mr,sgd_sexp_secexpedientes se ,SGD_TPR_TPDCUMENTO tpd
                    where rf.radi_nume_radi=r.radi_nume_radi and mr.sgd_mrd_codigo=rf.sgd_mrd_codigo and e.sgd_exp_numero=se.sgd_exp_numero   and rf.radi_nume_radi=e.radi_nume_radi and tpd.sgd_tpr_codigo=mr.sgd_tpr_codigo
                    and e.sgd_exp_numero=se.sgd_exp_numero and mr.sgd_srd_codigo=se.sgd_srd_codigo and mr.sgd_sbrd_codigo=se.sgd_sbrd_codigo limit 1)  sr,g.sgd_dir_nomremdes remitente, r.sgd_spub_codigo seguridad, r.RADI_DEPE_ACTU depe_actu, r.RADI_USUA_ACTU usua_actu, r.RADI_DEPE_RADI depe_radi, r.RADI_USUA_RADI usua_radi
                    from radicado r ,sgd_exp_expediente e ,SGD_TPR_TPDCUMENTO tpr,sgd_dir_drecciones g
                    where  r.radi_nume_radi=e.radi_nume_radi and e.sgd_exp_numero='$expnum' and g.radi_nume_radi=r.radi_nume_radi and e.sgd_exp_estado in ($estadoExp) $whereX and  r.tdoc_codi=tpr.sgd_tpr_codigo and g.sgd_dir_tipo=1 $filter"
                . " union
                select 0 as anu,'aexp' as tipo,trim(cast(p.exp_consecutivo as character(18))) as radica,' ' as anex ,TO_CHAR(p.exp_anex_radi_fech, 'YYYY/MM/DD HH24:MI:SS') fecha,p.exp_anex_desc as asunto,p.exp_anex_nomb_archivo path,
                p.exp_carpeta carpeta,trim(cast(p.exp_subexp as character(18))) subexpb,pt.SGD_TPR_DESCRIP tpdoc,p.exp_fisico fisico,'' sr,'' remitente, 0 seguridad, 0 depe_actu, 0 usua_actu, 0 depe_radi, 0 usua_radi
                from sgd_exp_anexos p,SGD_TPR_TPDCUMENTO pt  where  p.exp_anex_nomb_archivo like '$expnum%' and p.exp_anex_borrado='$estadoaaExp' and p.exp_tpdoc=pt.sgd_tpr_codigo $filter_anex $whereXc"
                . "order by $filtrosql fecha $ordem";
        }

        $total = $this->link->conn->getOne("SELECT COUNT(*) FROM (" . $sql . ") as total");
        $sql .= ' LIMIT ' . $page_size . ' OFFSET ' . (($pag - 1) * $page_size);

        if (strlen($expnum) == 18 && $importado != '1') {
            $rs = $this->dbOld->Execute($sql);
        } else {
            $rs = $this->link->conn->query($sql);
        }

        $datos = array();
        $i = 1;
        if (!$rs->EOF) {
            while (!$rs->EOF) {
                foreach ($rs->fields as $key => $value) {
                    $datos[$i][strtoupper($key)] = $value;
                }
                if ($rs->fields['TIPO'] == 'radi') {
                }
                $rs->MoveNext();
                $i++;
            }
        }

        //print_r($datos);
        $res['pagActal'] = $pag ? $pag : 1;
        $res['numReg'] = $regis;
        $res['numRad'] = $regis2;
        $res['numt'] = $regist;
        $res['pag'] = $paginas;
        $res['datos'] = $datos;
        $res['anexos'] = $datosAnex;
        $res['total'] = $total;
        $res['page_size'] = $page_size;
        return $res;
    }

    /**
     * 
     * @param type $expnum
     */
    function listarhistexp($expnum)
    {
        $sqlFecha = $this->link->conn->SQLDate("d-m-Y H:i", "he.SGD_HFLD_FECH");
        //$sqlFecha = $db->conn->SQLDate("Y-m-d","he.SGD_HFLD_FECH");
        $isql = "select  $sqlFecha as FECHA
			, d.DEPE_NOMB depe
                        , u.usua_nomb usuario
                        ,t.SGD_TTR_DESCRIP trasn
			, he.USUA_CODI codi
			, he.RADI_NUME_RADI radicado
			, he.SGD_HFLD_OBSERVA as HIST_OBSERVA 
			, he.SGD_FEXP_CODIGO refcodi
			
			from SGD_HFLD_HISTFLUJODOC he,usuario u,dependencia d,sgd_ttr_transaccion t
		 where 
			he.SGD_EXP_NUMERO ='$expnum'
			and he.usua_codi=u.usua_codi
            and u.depe_codi=d.depe_codi
			and he.depe_codi=d.depe_codi
			and he.sgd_ttr_codigo =t.sgd_ttr_codigo 
			order by he.SGD_HFLD_FECH desc"; //and t.sgd_ttr_codigo not in (79,80,81,82,83,84)
        $rs = $this->link->conn->query($isql);
        if (!$rs->EOF) {
            $i = 0;
            while (!$rs->EOF) {
                foreach ($rs->fields as $key => $value) {
                    $datos[$i][strtoupper($key)] = $value;
                }
                $i++;
                $rs->MoveNext();
            }
        } else {
            $datos['ERROR'] = 'No se encontro radidcados en el Expediente';
        }
        return $datos;
    }

    /**
     * 
     * @param type $expnum
     */
    function listarhistexpArch($expnum)
    {
        $sqlFecha = $this->link->conn->SQLDate("d-m-Y H:i", "he.SGD_HFLD_FECH");
        //$sqlFecha = $db->conn->SQLDate("Y-m-d","he.SGD_HFLD_FECH");
        $isql = "select  $sqlFecha as FECHA
			, d.DEPE_NOMB depe
                        , u.usua_nomb usuario
                        ,t.SGD_TTR_DESCRIP trasn
			, he.USUA_CODI codi
			, he.RADI_NUME_RADI radicado
			, he.SGD_HFLD_OBSERVA as HIST_OBSERVA 
			, he.SGD_FEXP_CODIGO refcodi
			
			from SGD_HFLD_HISTFLUJODOC he,usuario u,dependencia d,sgd_ttr_transaccion t
		 where 
			he.SGD_EXP_NUMERO ='$expnum'
			and he.usua_codi=u.usua_codi
			and he.depe_codi=d.depe_codi
			and he.sgd_ttr_codigo = t.sgd_ttr_codigo and t.sgd_ttr_codigo in (79,80,81,82,83,84)
			order by he.SGD_HFLD_FECH desc";
        $rs = $this->link->conn->query($isql);
        if (!$rs->EOF) {
            $i = 0;
            while (!$rs->EOF) {
                foreach ($rs->fields as $key => $value) {
                    $datos[$i][strtoupper($key)] = $value;
                }
                $i++;
                $rs->MoveNext();
            }
        } else {
            $datos['ERROR'] = 'No se encontro radidcados en el Expediente';
        }
        return $datos;
    }

    function parametrosEXP($depe)
    {
        $isql = "sELECT SGD_PAREXP_ETIQUETA, SGD_PAREXP_ORDEN, SGD_PAREXP_EDITABLE FROM SGD_PAREXP_PARAMEXPEDIENTE PE WHERE PE.DEPE_CODI = $depe ORDER BY SGD_PAREXP_ORDEN ASC";
        $rs = $this->link->conn->query($isql);
        if (!$rs->EOF) {
            $i = 0;
            while (!$rs->EOF) {
                $datos[$rs->fields['SGD_PAREXP_ORDEN']] = $rs->fields['SGD_PAREXP_ETIQUETA'];
                $rs->MoveNext();
            }
        } else {
            $datos['ERROR'] = 'No se encontro radidcados en el Expediente';
        }
        return $datos;
    }

    function numExp($dependencia, $codiSRD, $codiSBRD, $anoExp)
    {
        $trdExp = substr("00" . $codiSRD, -2) . substr("00" . $codiSBRD, -2);
        $secExp = $this->secExpediente($dependencia, $codiSRD, $codiSBRD, $anoExp);
        $consecutivoExp = substr("000000" . $secExp, -6);
        $datasec = $anoExp . '00000' + $dependencia;
        $digCheck = 'E';
        $numeroExpediente = $datasec . $trdExp . $consecutivoExp . $digCheck;
        //   echo " $numeroExpediente = $datasec . $trdExp . $consecutivoExp . $digCheck;$consecutivoExp  mm $secExp ";
        return $numeroExpediente;
    }

    function secExpediente($dependencia, $codiSRD, $codiSBRD, $anoExp)
    {
        $numExpediente = '';
        $query = "select se.SGD_EXP_NUMERO, se.SGD_FEXP_CODIGO, se.SGD_SEXP_SECUENCIA	from SGD_SEXP_SECEXPEDIENTES se
			WHERE
				SGD_SRD_CODIGO=$codiSRD
				AND SGD_SBRD_CODIGO=$codiSBRD
				AND SGD_SEXP_ANO=$anoExp
				AND DEPE_CODI = $dependencia
				AND SGD_SEXP_SECUENCIA > 0
				AND SGD_SEXP_SECUENCIA IS NOT NULL
			ORDER BY
				SGD_SEXP_SECUENCIA DESC
			";

        $rs = $this->link->conn->Execute($query);
        $secExp = 1;
        if (!$rs->EOF) {
            //  $numExpediente = $rs->fields["SGD_EXP_NUMERO"];
            $secExp = $rs->fields["SGD_SEXP_SECUENCIA"];
            $secExp = $secExp + 1;
        }
        return $secExp;
    }

    function crearExpediente($numExpediente, $coduser, $depe_codi, $codiSRD, $codiSBRD, $secExp, $anoExp,  $dependencia, $codUsuario, $titulo, $estadoExp = 0, $usua_doc, $fechaExp = null, $arrParametro = null)
    {
        //** trae el usua doc del resposable   
        //echo $estadoExp;
        // $arrParametro = null;
        $codiPROC = null;
        $expOld = null;
        $expManual = '';
        $radicado = 'null';
        $data = $this->userCrea($coduser, $depe_codi);
        $radicado = '';
        $estado_expediente = 0;
        $query = "select SGD_EXP_NUMERO from SGD_SEXP_SECEXPEDIENTES WHERE SGD_EXP_NUMERO='$numExpediente'";

        if ($expOld == "false") {
            $rs = $this->link->conn->Execute($query);
            $trdExp = substr("00" . $codiSRD, -2) . substr("00" . $codiSBRD, -2);
            $anoExp = substr($numExpediente, 0, 4);
            $secExp = substr($numExpediente, 2, 5);
            $consecutivoExp = substr("000000" . $secExp, -6);
            $numeroExpediente = $anoExp . $dependencia . $trdExp . $consecutivoExp;
        } else {
            $secExp = "0";
            $consecutivoExp = "000000";
            $anoExp = substr($numExpediente, 0, 4);
        }

        if ($rs->fields["SGD_EXP_NUMERO"] == $numExpediente) {
            return 0;
        } else {
            $fecha_hoy = Date("Y-m-d");
            //echo $fechaExp;
            if (!$fechaExp) // $fechaExp = $fecha_hoy;
                $sqlFechaHoy = $this->link->conn->OffsetDate(0, $this->db->conn->sysTimeStamp);
            else
                $sqlFechaHoy = $this->link->conn->DBDate($fechaExp);
            if (!$codiPROC)
                $codiPROC = "0";
            if (!$secExp)
                $secExp = 1;
            $secExp = substr($numExpediente, 13, 6);
            //$queryDel = "DELETE FROM SGD_SEXP_SECEXPEDIENTES WHERE SGD_EXP_NUMERO='$numExpediente'";
            //$this->db->conn->query($queryDel);

            $campoParametro = [];
            $valores = [
                $numExpediente,
                $data['DEPE'],
                $usua_doc,
                $codiSRD,
                $codiSBRD,
                $secExp,
                $anoExp,
                $data['CC'],
                $codiPROC,
                $titulo,
                $estadoExp
            ];

            if (is_array($arrParametro)) {
                foreach ($arrParametro as $orden => $datoParametro) {
                    $campoParametro[] = "SGD_SEXP_PAREXP" . $orden;
                    $valores[] = $datoParametro;
                }
            }

            $query = "INSERT into SGD_SEXP_SECEXPEDIENTES(
                        SGD_EXP_NUMERO,
                        SGD_SEXP_FECH,
                        DEPE_CODI,
                        USUA_DOC,
                        SGD_FEXP_CODIGO,
                        SGD_SRD_CODIGO,
                        SGD_SBRD_CODIGO,
                        SGD_SEXP_SECUENCIA,
                        SGD_SEXP_ANO,
                        USUA_DOC_RESPONSABLE,
                        SGD_PEXP_CODIGO,
                        sgd_sexp_parexp1,
                        sgd_sexp_estado";

            if (count($campoParametro) > 0)
                $query .= ', ' . implode(', ', $campoParametro);

            $query .= " )";

            $query .= " VALUES (
                            ?, 
                            CURRENT_TIMESTAMP, 
                            ?,
                            ?,
                            0, 
                            ?,
                            ?,
                            ?, 
                            ?,
                            ?, 
                            ?,
                            ?,
                            ?";

            $keys = [];
            for ($i = 0; $i < count($campoParametro); $i++)
                $keys[$i] = '?';

            if (count($keys) > 0)
                $query .= ', ' . implode(',', $keys);

            $query .= " )";


            // die();
            if (!$rs = $this->link->conn->Execute($query, $valores)) {
                //echo '<br>Lo siento no pudo agregar el expediente<br>';
                // return "No se ha podido insertar el Expediente";
                return 0;
            }
        }
        /* historico de creacion */
        $radicados = array();
        $observacion = 'Creacion de expedientes ' . $numExpediente;
        $tipoTx = 51;

        $this->insertarHistoricoExp($numExpediente, $radicados, $dependencia, $codUsuario, $observacion, $tipoTx, 0, $numExpediente);
        return $numExpediente;;
    }

    function userCrea($codigo, $depe)
    {
        $sql = "SELECT   depe_codi depe,   usua_codi codi,   usua_doc cc  FROM  usuario u where  u.usua_codi = $codigo AND  depe_codi = $depe ";
        $rs = $this->link->query($sql);
        $datos['ERROR'] = '';
        if (!$rs->EOF) {
            foreach ($rs->fields as $key => $value) {
                $datos[strtoupper($key)] = $value;
            }
        }
        return $datos;
    }

    /**
     * FUNCION QUE INSERTA HISTORICO DE EXPEDIENTES
     *
     * @radicados   array Arreglo de radicados
     * @dependencia	int   Dependencia que realiza la transaccion
     * @depeDest    int   Dependencia destino
     * @codUsuario  int   Documento del usuario que realiza la transaccion
     * @tipoTx      int   Tipo de Transaccion
     * @return void
     *
     */
    function insertarHistoricoExp($numeroExpediente, $radicados, $dependencia, $codUsuario, $observacion, $tipoTx, $codigoFldExp)
    {
        //Arreglo que almacena los nombres de columna
        #==========================
        //$this->link->conn->debug = true;
        # Busca el Documento del usuario Origen
        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $this->link->conn->SetFetchMode(ADODB_FETCH_ASSOC);
        $sql = "SELECT u.USUA_DOC, u.USUA_LOGIN FROM USUARIO u	WHERE
					u.DEPE_CODI=$dependencia AND u.USUA_CODI=$codUsuario";
        # Busca el usuairo Origen para luego traer sus datos.
        $rs = $this->link->conn->Execute($sql);
        $usDoc = $rs->fields["USUA_DOC"];
        $usuLogin = $rs->fields["USUA_LOGIN"];
        //$this->link->conn->debug = true;
        $record = array(); # Inicializa el arreglo que contiene los datos a insertar
        if ($radicados) {
            foreach ($radicados as $noRadicado) {
                # Asignar el valor de los campos en el registro
                # Observa que el nombre de los campos pueden ser mayusculas o minusculas

                $record["SGD_EXP_NUMERO"] = "'" . $numeroExpediente . "'";
                $record["SGD_FEXP_CODIGO"] = $codigoFldExp;
                $record["RADI_NUME_RADI"] = "'" . $noRadicado . "'";
                $record["DEPE_CODI"] = $dependencia;
                $record["USUA_CODI"] = $codUsuario;
                $record["USUA_DOC"] = $usDoc;
                $record["SGD_TTR_CODIGO"] = $tipoTx;
                $record["SGD_HFLD_OBSERVA"] = "'$observacion'";
                $record["SGD_HFLD_FECH"] = $this->link->conn->OffsetDate(0, $this->link->conn->sysTimeStamp);
                /*if ($codigoArista) {
                    $record["SGD_FARS_CODIGO"] = $codigoArista;
                }*/
                # Mandar como parametro el recordset vacio y el arreglo conteniendo los datos a insertar
                # a la funcion GetInsertSQL. Esta procesara los datos y regresara un enunciado SQL
                # para procesar el INSERT.
                $insertSQL = $this->link->insert("SGD_HFLD_HISTFLUJODOC", $record, "true");
            }
            return ($radicados);
        } else {
            $record["SGD_EXP_NUMERO"] = "'" . $numeroExpediente . "'";
            $record["SGD_FEXP_CODIGO"] = $codigoFldExp;
            $record["RADI_NUME_RADI"] = 0;
            $record["DEPE_CODI"] = $dependencia;
            $record["USUA_CODI"] = $codUsuario;
            $record["USUA_DOC"] = $usDoc;
            $record["SGD_TTR_CODIGO"] = $tipoTx;
            $record["SGD_HFLD_OBSERVA"] = "'$observacion'";
            $record["SGD_HFLD_FECH"] = $this->link->conn->OffsetDate(0, $this->link->conn->sysTimeStamp);
            /*if ($codigoArista) {
                $record["SGD_FARS_CODIGO"] = $codigoArista;
            }*/
            # Mandar como parametro el recordset vacio y el arreglo conteniendo los datos a insertar
            # a la funcion GetInsertSQL. Esta procesara los datos y regresara un enunciado SQL
            # para procesar el INSERT.
            $insertSQL = $this->link->insert("SGD_HFLD_HISTFLUJODOC", $record, "true");
        }
    }

    function ConsultarTRD($srd, $sbrd)
    {
        $sql = "select  s.sgd_srd_descrip serie,d.sgd_sbrd_descrip subserie from sgd_srd_seriesrd s, sgd_sbrd_subserierd d where s.sgd_srd_codigo=d.sgd_srd_codigo 
                and s.sgd_srd_codigo=$srd and d.sgd_sbrd_codigo=$sbrd  "; //and s.trd_version_id=$ver
        # Busca el usuairo Origen para luego traer sus datos.
        $rs = $this->link->conn->Execute($sql);
        return $rs->fields["SERIE"] . '/' . $rs->fields["SUBSERIE"];
    }

    function ConsultarTdoc($srd, $sbrd, $depe)
    {
        $sql = "select t.sgd_tpr_codigo codi, t.sgd_tpr_descrip nom from sgd_mrd_matrird  m, sgd_tpr_tpdcumento t where   
                m.sgd_srd_codigo=$srd and m.sgd_mrd_esta = '1' and m.sgd_sbrd_codigo=$sbrd and t.sgd_tpr_codigo=m.sgd_tpr_codigo and m.depe_codi=$depe"; //and s.trd_version_id=$ver
        # Busca el usuairo Origen para luego traer sus datos.
        $rs = $this->link->conn->Execute($sql);
        //$datos['ERROR'] = '';
        $i = 0;
        if (!$rs->EOF) {
            while (!$rs->EOF) {
                foreach ($rs->fields as $key => $value) {
                    $datos[$i][strtoupper($key)] = $value;
                }
                $rs->MoveNext();
                $i++;
            }
        }
        //print_r($datos);
        return $datos;
    }

    function aclExpediente($numExpediente)
    {
        $sql = "SELECT a.*,d.depe_nomb,  u.usua_nomb  FROM sgd_aexp_aclexp a 
                left join dependencia d on a.depe_codi=d.depe_codi
                left join usuario u on  u.usua_codi=a.usua_codi and u.depe_codi=d.depe_codi
                WHERE num_expediente='$numExpediente'   ";
        $rs = $this->link->conn->Execute($sql);
        $datos = array();
        $i = 0;
        if (!$rs->EOF) {
            while (!$rs->EOF) {
                if (!$rs->fields['DEPE_NOMB'])
                    $datos[$i] = array('DEPE_NOMB' => 'Todos', 'AEXP_ACL' => '0', 'USUA_NOMB' => null, 'NUM_EXPEDIENTE' => $rs->fields['NUM_EXPEDIENTE']);
                else
                    foreach ($rs->fields as $key => $value) {
                        $datos[$i][strtoupper($key)] = $value;
                    }
                $rs->MoveNext();
                $i++;
            }
        } else {
        }
        return $datos;
    }

    /**
     * funcion de validación de id del acl de permiso
     * @param type $numExpediente
     * @param type $depe
     * @param type $usuario
     * @param type $tpseg
     * @return type
     */
    function valacldExp($numExpediente, $depe, $usuario, $tpseg = null)
    {
        $tpss = '';
        $dtusuario = ' and USUA_CODI is null';
        if ($usuario != 0)
            $dtusuario = ' and USUA_CODI=' . $usuario;
        if ($tpseg) {
            $tpss = " and AEXP_ACL=$tpseg";
        }
        $sql = "SELECT ID_AEXP FROM sgd_aexp_aclexp WHERE num_expediente='$numExpediente' and depe_codi=$depe $dtusuario $tpss";
        $rs = $this->link->query($sql);
        if (!$rs->EOF) {
            $datos = $rs->fields['ID_AEXP'];
        }
        return $datos;
    }
    function addAclExp($numExpediente, $depe, $usuario, $acl, $aclimg = 'NULL')
    {
        $dato = "";
        $data = "";
        if ($usuario != 0) {
            $dato = ",usua_codi";
            $data = ",$usuario";
        }
        $consulta = "INSERT INTO sgd_aexp_aclexp 
            (num_expediente,depe_codi$dato,aexp_acl,aexp_acl_img)
           VALUES ('{$numExpediente}',{$depe} $data ,$acl,$aclimg)";
        $this->link->query($consulta);
        $this->link->conn->insert_Id();
        return true;
    }
    function aclmod($aclid, $acl)
    {
        $consulta = "update sgd_aexp_aclexp set aexp_acl=$acl where ID_AEXP='{$aclid}'";
        $resultado = $this->link->query($consulta);
        return true;
    }

    public function bsqlistar($tp, $numExp, $radicado, $parametro, $usua_doc, $depe)
    {
        $where = '1=1';

        if ($depe)
            $where .= " and s.depe_codi='$depe' ";

        if ($usua_doc)
            $where .= " and s.usua_doc_responsable='$usua_doc'";

        if ($numExp)
            $where .= " and s.sgd_exp_numero like '%$numExp%' ";

        if ($parametro)
            $where .= " and ( upper(s.sgd_sexp_parexp1) like  upper('%$parametro%') or upper(s.sgd_sexp_parexp2) like  upper('%$parametro%') or  upper(s.sgd_sexp_parexp3) like  upper('%$parametro%') or  upper(s.sgd_sexp_parexp4) like  upper('%$parametro%')  or  upper(s.sgd_sexp_parexp5) like  upper('%$parametro%') ) ";

        $iSql = "select s.sgd_exp_numero num,to_char(s.sgd_sexp_fech, 'DD-MM-YYYY HH24:MI') fech,u.usua_nomb creador,s.sgd_sexp_parexp1 titulo,ub.usua_nomb responsable,d.depe_nomb depe,s.sgd_sexp_estado estado,s.sgd_sexp_parexp2 param2,s.sgd_sexp_parexp3 param3,s.sgd_sexp_parexp4 param4,s.sgd_sexp_parexp5 param5
                from sgd_sexp_secexpedientes s
                left join usuario u on u.usua_doc=s.usua_doc
                left join usuario ub on ub.usua_doc=s.usua_doc_responsable
                left join dependencia d on d.depe_codi=s.depe_codi where $where limit 100";

        if ($radicado) {
            if ($where)
                $where .= ' AND ' . $where;

            $iSql = "select s.sgd_exp_numero num, to_char(s.sgd_sexp_fech, 'DD-MM-YYYY HH24:MI') fech,u.usua_nomb creador,
                s.sgd_sexp_parexp1 titulo,ub.usua_nomb responsable,d.depe_nomb depe,s.sgd_sexp_estado estado,s.sgd_sexp_parexp2 param2,s.sgd_sexp_parexp3 param3,s.sgd_sexp_parexp4 param4,s.sgd_sexp_parexp5 param5
                from sgd_sexp_secexpedientes s
                left join usuario u on u.usua_doc=s.usua_doc
                left join usuario ub on ub.usua_doc=s.usua_doc_responsable
                left join dependencia d on d.depe_codi=s.depe_codi
                ,sgd_exp_expediente e where e.radi_nume_radi in ($radicado) and s.sgd_exp_numero=e.sgd_exp_numero and e.sgd_exp_estado=0 $where  limit 100";
        }

        $rs = $this->link->conn->query($iSql);

        if (!$rs->EOF) {
            $i = 1;
            while (!$rs->EOF) {
                $dd['NUM'] = $i;
                foreach ($rs->fields as $key => $value) {
                    $dd[strtoupper($key)] = $value;
                }
                $datos[] = $dd;
                $i++;
                $rs->MoveNext();
            }
        }
        return $datos;
    }

    public function bsqlistar_paginado($tp, $numExp, $radicado, $parametro, $usua_doc, $depe, $start = 1, $length = 10)
    {
        $where = '1=1';

        if ($depe == 0 && $usua_doc == 0) {
            return [
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => []
            ];
        }

        if ($depe)
            $where .= " and s.depe_codi='$depe' ";

        if ($usua_doc)
            $where .= " and s.usua_doc_responsable='$usua_doc'";

        if ($numExp)
            $where .= " and s.sgd_exp_numero like '%$numExp%' ";

        if ($parametro)
            $where .= " and ( upper(s.sgd_sexp_parexp1) like  upper('%$parametro%') or upper(s.sgd_sexp_parexp2) like  upper('%$parametro%') or  upper(s.sgd_sexp_parexp3) like  upper('%$parametro%') or  upper(s.sgd_sexp_parexp4) like  upper('%$parametro%')  or  upper(s.sgd_sexp_parexp5) like  upper('%$parametro%') ) ";

        $iSql = "select s.sgd_exp_numero num,to_char(s.sgd_sexp_fech, 'DD-MM-YYYY HH24:MI') fech,u.usua_nomb creador,s.sgd_sexp_parexp1 titulo,ub.usua_nomb responsable,d.depe_nomb depe,s.sgd_sexp_estado estado,s.sgd_sexp_parexp2 param2,s.sgd_sexp_parexp3 param3,s.sgd_sexp_parexp4 param4,s.sgd_sexp_parexp5 param5
                from sgd_sexp_secexpedientes s
                left join usuario u on u.usua_doc=s.usua_doc
                left join usuario ub on ub.usua_doc=s.usua_doc_responsable
                left join dependencia d on d.depe_codi=s.depe_codi where $where";

        if ($radicado) {
            if ($where)
                $where .= ' AND ' . $where;

            $iSql = "select s.sgd_exp_numero num, to_char(s.sgd_sexp_fech, 'DD-MM-YYYY HH24:MI') fech,u.usua_nomb creador,
                s.sgd_sexp_parexp1 titulo,ub.usua_nomb responsable,d.depe_nomb depe,s.sgd_sexp_estado estado,s.sgd_sexp_parexp2 param2,s.sgd_sexp_parexp3 param3,s.sgd_sexp_parexp4 param4,s.sgd_sexp_parexp5 param5
                from sgd_sexp_secexpedientes s
                left join usuario u on u.usua_doc=s.usua_doc
                left join usuario ub on ub.usua_doc=s.usua_doc_responsable
                left join dependencia d on d.depe_codi=s.depe_codi
                ,sgd_exp_expediente e where e.radi_nume_radi in ($radicado) and s.sgd_exp_numero=e.sgd_exp_numero and e.sgd_exp_estado=0 $where";
        }

        $total = $this->link->conn->getOne("SELECT COUNT(*) FROM ($iSql) AS total");
        $iSql .= " limit $length offset $start";

        $rs = $this->link->conn->query($iSql);

        if (!$rs->EOF) {
            $i = 1;
            while (!$rs->EOF) {
                $dd['NUM'] = $i;
                foreach ($rs->fields as $key => $value) {
                    $dd[strtoupper($key)] = $value;
                }
                $datos[] = $dd;
                $i++;
                $rs->MoveNext();
            }
        }

        return [
            "recordsTotal" => intval($total),
            "recordsFiltered" => intval($total),
            "data" => $datos
        ];
    }

    public function bsqlistarV1($tp, $numExp, $parametro)
    {
        $where = '';
        $and = '';

        if ($numExp) {
            $where .= "   $and s.sgd_exp_numero like '%$numExp%' ";
            $and = ' and';
        }
        if ($parametro)
            $where .= "   $and ( upper(s.sgd_sexp_parexp1) like  upper('%$parametro%') or upper(s.sgd_sexp_parexp2) like  upper('%$parametro%') or  upper(s.sgd_sexp_parexp3) like  upper('%$parametro%') or  upper(s.sgd_sexp_parexp4) like  upper('%$parametro%')  or  upper(s.sgd_sexp_parexp5) like  upper('%$parametro%') ) ";

        $iSqla = "select s.sgd_exp_numero num,to_char(s.sgd_sexp_fech, 'DD-MM-YYYY HH24:MI') fech,u.usua_nomb creador,s.sgd_sexp_parexp1 titulo,ub.usua_nomb responsable,d.depe_nomb depe,s.sgd_sexp_estado estado,s.sgd_sexp_parexp2 param2,s.sgd_sexp_parexp3 param3,s.sgd_sexp_parexp4 param4,s.sgd_sexp_parexp5 param5
        from sgd_sexp_secexpedientes s
        left join usuario u on u.usua_doc=s.usua_doc
        left join usuario ub on ub.usua_doc=s.usua_doc_responsable
        left join dependencia d on d.depe_codi=s.depe_codi where $where limit 100";

        $rs = $this->dbOld->Execute($iSqla);


        //   SGD_EXP_NUMERO
        if (!$rs->EOF) {
            $i = 1;
            while (!$rs->EOF) {
                $dd['NUM'] = $i;
                $dd['respNew'] = '';
                $expdata =  $this->consultarExp($numExp);
                //print_r($expdata);
                if ($expdata) $dd['respNew'] = $expdata['RESPONSABLE'];
                foreach ($rs->fields as $key => $value) {
                    $dd[strtoupper($key)] = $value;
                }
                $datos[] = $dd;
                $i++;
                $rs->MoveNext();
            }
            //  $resp['titulo'] = $campot;
        }

        return $datos;
    }

    function modExp($exp, $camp, $cambio, $usuario, $dependencia)
    {
        if ($camp == 'USUA_DOC_RESPONSABLE') {
            $usua_doc_ante = $this->link->conn->getOne('SELECT usua_doc_responsable FROM sgd_sexp_secexpedientes WHERE sgd_exp_numero = ?', [$exp]);
            $usua_ante = $this->link->conn->getOne('SELECT usua_nomb FROM usuario WHERE usua_doc = ? LIMIT 1', [$usua_doc_ante]);
            $usua_nuevo = $this->link->conn->getOne('SELECT usua_nomb FROM usuario WHERE usua_doc = ? LIMIT 1', [$cambio]);
            $observacion = 'Cambio de responsable ' . $usua_ante . ' a ' . $usua_nuevo;
            $this->insertarHistoricoExp($exp, [], $dependencia, $usuario, $observacion, 56, 0);
        }
        $consulta = "update sgd_sexp_secexpedientes set $camp='{$cambio}' where sgd_exp_numero='{$exp}'";
        $resultado = $this->link->query($consulta);
        return true;
    }

    function jefe($depe)
    {
        $query = "select u.usua_codi from usuario u, autm_membresias m where u.id = m.autu_id
        AND m.autg_id = 2 AND depe_codi = $depe";
        $resp = $this->link->query($query);
        $data = $resp->fields['USUA_CODI'];
        return $data;
    }

    function responsable($expediente)
    {
        $query = "select u.usua_codi 
        from usuario u, sgd_sexp_secexpedientes m 
        where u.usua_doc = m.usua_doc_responsable
        AND m.sgd_exp_numero = '$expediente'";
        $resp = $this->link->query($query);
        $data = $resp->fields['USUA_CODI'];
        return $data;
    }

    function creador($expediente)
    {
        $query = "select u.usua_codi 
        from usuario u, sgd_sexp_secexpedientes m 
        where u.usua_doc = m.usua_doc
        AND m.sgd_exp_numero = '$expediente'";
        $resp = $this->link->query($query);
        $data = $resp->fields['USUA_CODI'];
        return $data;
    }

    function modradExp($exp, $rad, $camp, $cambio)
    {
        $consulta = "update sgd_exp_expediente set $camp='{$cambio}' where sgd_exp_numero='{$exp}' and radi_nume_radi=$rad";
        $resultado = $this->link->query($consulta);
        return true;
    }

    function creaanexo($exp, $conse, $id, $anextipo = 7, $size, $login, $namefile, $path_nomb, $hashs, $path, $fisico, $carpeta, $subexp, $tpdoc = 0, $trd_vesion)
    {

        $sqlI = "INSERT INTO sgd_exp_anexos(
            id, exp_anex_tipo, exp_anex_tamano, exp_anex_creador, exp_anex_desc, exp_anex_nomb_archivo, exp_anex_borrado, exp_anex_radi_fech, exp_anex_hash,
             exp_numero, exp_anex_path, exp_consecutivo, exp_fisico, exp_carpeta, exp_subexp,exp_tpdoc)
            values ( {$id},{$anextipo}, '{$size}','{$login}', '{$namefile}',  '{$path_nomb}', 'N',  CURRENT_TIMESTAMP , '{$hashs}', '{$exp}','{$path}','{$conse}' ,'{$fisico}','{$carpeta}','{$subexp}',$tpdoc )";

        $this->link->query($sqlI);
    }

    function consAnexNumeExpe($exp)
    {
        $sql = "select count(*) cons,(select depe_codi from sgd_sexp_secexpedientes where sgd_exp_numero='20210900010400001E') depe from sgd_exp_anexos where  exp_anex_nomb_archivo like '$exp%';";
        $resp = $this->link->query($sql);
        $data['con'] = $resp->fields['CONS'] + 1;
        $data['depe'] = $resp->fields['DEPE'];
        return $data;
    }

    function getTpAnex()
    {
        /* Se traen los anexos_tipo */
        $sql_anexosTipo = "select anex_tipo_ext from anexos_tipo";
        $rs_anexosTipo = $this->link->query($sql_anexosTipo);;

        foreach ($rs_anexosTipo as $item) {
            $exts[] = "." . $item["ANEX_TIPO_EXT"];
        }
        return $exts;
    }
    /**
     * Functrion fisico permite  la actualizacion de fisico a virtual  biceversa
     * @param type $numExpediente
     * @param type $fisico
     * @param type $radicado
     * @param type $anexos
     */
    function fisicoexp($numExpediente, $fisico, $radicado = '', $anexos = '')
    {
        // echo "$numExpediente, $fisico, $radicado, $anexos";
        //print_r($radicado);
        $codUsuario = $_SESSION['codusuario'];
        $usua_doc = $_SESSION['usua_doc'];
        $dependencia = $_SESSION['dependencia'];
        $radicados = explode(',', $radicado);
        if ($radicados[0]) {
            //foreach ($radicado as $key => $value) {
            $sql = "update sgd_exp_expediente set  sgd_exp_ufisica='$fisico'  where sgd_exp_numero='$numExpediente' and radi_nume_radi in ($radicado) ";
            $rs = $this->link->query($sql);

            $nom = 'VIRTUAL ';
            if ($fisico == 'VIRTUAL ')
                $nom = 'FISICO';
            $observacion = 'Cambio de estado radicado campo fisico  de ' . $nom . ' a ' . $fisico;
            $tipoTx = 79;
            $codigoFldExp = 0;
            $this->insertarHistoricoExp($numExpediente, $radicados, $dependencia, $codUsuario, $observacion, $tipoTx, $codigoFldExp);
        }
        $radicados = explode(',', $anexos);
        if ($anexos[0]) {
            $sql = "update sgd_exp_anexos set  exp_fisico='$fisico' where exp_consecutivo in ($anexos)  and exp_numero='$numExpediente' ";
            $rs = $this->link->query($sql);
            $nom = 'VIRTUAL ';
            if ($fisico == 'VIRTUAL ')
                $nom = 'FISICO';
            $observacion = 'Cambio de estado anexo campo fisico de ' . $nom . ' a ' . $fisico;
            $tipoTx = 79;
            $codigoFldExp = 0;
            $radicado[0] = 0;
            $this->insertarHistoricoExp($numExpediente, $radicado, $dependencia, $codUsuario, $observacion, $tipoTx, $codigoFldExp);
            return true;
        }
    }

    /**
     * function de modificacicion de subexpediente.
     * @param type $numExpediente
     * @param type $subexp
     * @param type $radicado
     * @param type $anexos
     */
    function modSubExp($numExpediente, $subexp = '', $radicado = '', $anexos = '')
    {
        $codUsuario = $_SESSION['codusuario'];
        $usua_doc = $_SESSION['usua_doc'];
        $dependencia = $_SESSION['dependencia'];
        $radicados = explode(',', $radicado);
        if ($radicados[0]) {
            $sql = "update SGD_EXP_EXPEDIENTE SET SGD_EXP_SUBEXPEDIENTE='" . substr($subexp, 0, 100) . "' WHERE SGD_EXP_NUMERO ='$numExpediente' and radi_nume_radi in ($radicado) ";
            $rs = $this->link->query($sql);

            $observacion = 'Cambio de Subexp  a ' .  $subexp;
            $tipoTx = 81;
            $codigoFldExp = 0;
            $this->insertarHistoricoExp($numExpediente, $radicado, $dependencia, $codUsuario, $observacion, $tipoTx, $codigoFldExp);
        }
        $radicados = explode(',', $anexos);
        if ($anexos[0]) {

            $sql = "update sgd_exp_anexos SET exp_subexp='$subexp' WHERE exp_numero ='$numExpediente' and  exp_consecutivo in ($anexos)";
            $rs = $this->link->query($sql);
            $observacion = 'Cambio de Subexp  a ' . $subexp;
            $tipoTx = 81;
            $codigoFldExp = 0;
            $radicado[0] = 0;
            $this->insertarHistoricoExp($numExpediente, $radicado, $dependencia, $codUsuario, $observacion, $tipoTx, $codigoFldExp);
        }
    }

    /**
     * function de modificacicion de subexpediente.
     * @param type $numExpediente
     * @param type $subexp
     * @param type $radicado
     * @param type $anexos
     */
    function modCarp($numExpediente, $carpeta = '', $radicado = '', $anexos = '')
    {
        $codUsuario = $_SESSION['codusuario'];
        $usua_doc = $_SESSION['usua_doc'];
        $dependencia = $_SESSION['dependencia'];
        $radicados = explode(',', $radicado);
        if ($radicados[0]) {
            $sql = "update SGD_EXP_EXPEDIENTE SET sgd_exp_carpeta='" . $carpeta . "' WHERE SGD_EXP_NUMERO ='$numExpediente' and radi_nume_radi in ($radicado) ";
            $rs = $this->link->query($sql);

            $observacion = 'Cambio de Subexp  a ' .  $carpeta;
            $tipoTx = 81;
            $codigoFldExp = 0;
            $this->insertarHistoricoExp($numExpediente, $radicado, $dependencia, $codUsuario, $observacion, $tipoTx, $codigoFldExp);
        }
        $radicados = explode(',', $anexos);
        if ($anexos[0]) {

            $sql = "update sgd_exp_anexos SET exp_carpeta='$carpeta' WHERE exp_numero ='$numExpediente' and  exp_consecutivo in ($anexos)";
            $rs = $this->link->query($sql);
            $observacion = 'Cambio de Subexp  a ' . $carpeta;
            $tipoTx = 81;
            $codigoFldExp = 0;
            $radicado[0] = 0;
            $this->insertarHistoricoExp($numExpediente, $radicado, $dependencia, $codUsuario, $observacion, $tipoTx, $codigoFldExp);
        }
    }
    function valExpAnu($numExpediente)
    {
        $sql = "select (select count(*) from sgd_exp_expediente where sgd_exp_numero='$numExpediente' and sgd_exp_estado!=1 and sgd_exp_estado!=2) +
                       (select count(*) from  sgd_exp_anexos where num_expediente='$numExpediente' and exp_anex_borrado='N') as conteo";

        //and (SGD_SEXP_ESTADO=0 or SGD_SEXP_ESTADO is null)
        $rs = $this->link->query($sql);
        $datos['ERROR'] = '';
        if (!$rs->EOF) {
            $dato = $rs->fields["CONTEO"];
        }
        return $dato;
    }

    function consultarExpRadicadosVal($numExpediente)
    {
        $sql = "SELECT   r.radi_nume_radi,   e.sgd_exp_numero,   r.sgd_eanu_codigo,   r.radi_usua_actu,   r.radi_depe_actu
           FROM   radicado r,  sgd_exp_expediente e
           WHERE    e.radi_nume_radi = r.radi_nume_radi AND  e.sgd_exp_numero = '$numExpediente' and e.sgd_exp_estado!=2";
        $rs = $this->link->query($sql);
        $i = 0;
        if (!$rs->EOF) {
            while (!$rs->EOF) {
                foreach ($rs->fields as $key => $value) {
                    $datos[$i][strtoupper($key)] = $value;
                }
                $i++;
                $rs->MoveNext();
            }
        } else {
            $datos['ERROR'] = 'No se encontro radicados en el Expediente';
        }
        return $datos;
    }

    /* cambia el estado del espediente */

    function ExpCambioEstado($numExp, $estado)
    {
        $sql = "update SGD_SEXP_SECEXPEDIENTES set sgd_sexp_estado=$estado WHERE SGD_EXP_NUMERO='$numExp' ";
        if ($estado == '0') {
            $this->link->conn->Execute('UPDATE sgd_sexp_secexpedientes SET indice_electronico = NULL WHERE sgd_exp_numero = ?', [$numExp]);
        }
        $rs = $this->link->query($sql);
    }

    function permisos($numExpediente, $depe, $usuario, $respondable)
    {
        $data = 'and usua_codi is null';
        if ($usuario) {
            $data = "and usua_codi=$usuario";
        }
        $sql = "SELECT * FROM sgd_aexp_aclexp WHERE num_expediente='$numExpediente' and depe_codi=$depe $data";
        $rs = $this->link->query($sql);

        if (!$rs->EOF) {
            // print_r($rs->fields);
            foreach ($rs->fields as $key => $value) {
                $datos[strtoupper($key)] = $value;
            }
        } else {

            $sql = "SELECT * FROM sgd_aexp_aclexp WHERE num_expediente='$numExpediente' and depe_codi=$depe and usua_codi is null";
            $rs = $this->link->query($sql);
            if (!$rs->EOF) {
                foreach ($rs->fields as $key => $value) {
                    $datos[strtoupper($key)] = $value;
                }
            } else {

                $sql = "SELECT * FROM sgd_aexp_aclexp WHERE num_expediente='$numExpediente' and depe_codi=0 ";
                $rs = $this->link->query($sql);
                if (!$rs->EOF) {
                    foreach ($rs->fields as $key => $value) {
                        $datos[strtoupper($key)] = $value;
                    }
                }
            }
        }


        $total_permisos = $this->link->conn->getOne("SELECT count(num_expediente) FROM sgd_aexp_aclexp WHERE num_expediente='$numExpediente'");

        $listar = 0;
        $otro = true;

        if ($total_permisos < self::RESERVADO) {
            $listar = 1;
            $otro = false;
        }

        if ($total_permisos == self::RESERVADO) {
            $de_dependencia = $this->link->conn->getOne("SELECT count(num_expediente) FROM sgd_aexp_aclexp WHERE num_expediente='$numExpediente' and depe_codi::text LIKE '" . substr($depe, 0, -1) . "%'") == 1;
            if ($de_dependencia) {
                $listar = 1;
                $otro = false;
            }
        }

        if ($total_permisos == self::CLASIFICADO) {
            $de_dependencia = $this->link->conn->getOne("SELECT count(num_expediente) FROM sgd_aexp_aclexp WHERE num_expediente='$numExpediente' and depe_codi::text LIKE '" . substr($depe, 0, -1) . "%'") != 0;
            if ($respondable == $_SESSION['usua_doc'] || ($_SESSION['USUA_JEFE_DE_GRUPO'] && $de_dependencia)) {
                $listar = 1;
                $otro = false;
            }
        }

        if ($otro) {
            $listar = 1;
        }

        $PerVer['list'] = 1;
        $segdata = 0;
        if (!$rs->EOF)
            $segdata = $datos['AEXP_ACL'];
        if ($segdata == null)
            $segdata = 4;
        if ($respondable == $_SESSION['usua_doc'])
            $segdata = 3;
        if (1 == $_SESSION['ADM_EXP'])
            $segdata = 3;
        elseif (1 == $_SESSION['NOTI_EXP'] and $segdata <> 3)
            $segdata = 5;
        //$segdata=3;
        $PerVer['segperm'] = $segdata;

        switch ($segdata) {
            case 0:
                $PerVer['list'] = $listar;
                break;
            case 1:
                $PerVer['list'] = $listar;
                break;
            case 2:
                $PerVer['list'] = $listar;
                $PerVer['imgRad'] = 1;
                break;
            case 3:
                $PerVer['list'] = $listar;
                $PerVer['subexp'] = 1;
                $PerVer['admExp'] = 1;
                $PerVer['segAdm'] = 1;
                $PerVer['estado'] = 1;
                $PerVer['imgRad'] = 1;
                $PerVer['carpeta'] = 1;
                $PerVer['fisico'] = 1;
                $PerVer['anexa'] = 1;
                $PerVer['inclotro'] = 1;
                $PerVer['excluir'] = 1;
                $PerVer['arch'] = 1;
                $PerVer['orden'] = 1;
                break;
            case 4:
                $PerVer = array('fisico' => 0, 'carpeta' => 0, 'subexp' => 0, 'orden' => 0, 'fisico' => 0, 'admExp' => 0, 'anexa' => 0, 'segAdm' => 0, 'estado' => 0, 'datosEXp' => 0, 'imgRad' => 0, 'inclotro' => 0, 'excluir' => 0, 'arch' => 0); //orden= orden, fisico y  carpeta //admexp titulo , nombre exp
                $PerVer['list'] = $listar;
                break;
            case 5:
                $PerVer['list'] = $listar;
                $PerVer['imgRad'] = 1;
                $PerVer['anexa'] = 1;
                $PerVer['inclotro'] = 1;
                $PerVer['excluir'] = 1;
                break;
        }
        return $PerVer;
    }

    public function crearOld($numExp, $usua, $depe)
    {
        $responsable = $usua;
        $where = '';
        $and = '';
        $where .= "   $and s.sgd_exp_numero like '%$numExp%' ";
        $and = ' and';
        $iSqla = "select s.sgd_exp_numero num,s.sgd_sexp_fech fech,u.usua_nomb creador,s.sgd_sexp_parexp1 titulo,ub.usua_nomb responsable,d.depe_nomb depe,s.sgd_sexp_estado estado,s.sgd_sexp_parexp2 param2,s.sgd_sexp_parexp3 param3,s.sgd_sexp_parexp4 param4,s.sgd_sexp_parexp5 param5,s.sgd_srd_codigo, s.sgd_sbrd_codigo,s.usua_doc,sgd_sexp_ano, s.sgd_srd_id, sgd_sbrd_id,sgd_sexp_estado,ss.sgd_srd_descrip param9,sbs.sgd_sbrd_descrip param10
        from sgd_sexp_secexpedientes s  left join sgd_srd_seriesrd as ss on s.sgd_srd_codigo =ss.sgd_srd_codigo and ss.sgd_srd_estado=1  left join sgd_sbrd_subserierd as sbs on s.sgd_srd_codigo =sbs.sgd_srd_codigo and s.sgd_sbrd_codigo =sbs.sgd_sbrd_codigo
        left join usuario u on u.usua_doc=s.usua_doc
        left join usuario ub on ub.usua_doc=s.usua_doc_responsable
        left join dependencia d on d.depe_codi=s.depe_codi where $where ";

        $rs = $this->dbOld->Execute($iSqla);
        // print_r($rs->fields);
        if (!$rs->EOF) {

            foreach ($rs->fields as $key => $value) {
                $dd[strtolower($key)] = $value;
            }
            $rs->MoveNext();
            // }

        }
        /*  echo '<hr>';
        print_r($dd);
        echo '<hr>';*/
        $estado = $dd['sgd_sexp_estado'] ? $dd['sgd_sexp_estado'] : 0;
        $sql = "INSERT INTO sgd_sexp_secexpedientes(
            sgd_exp_numero, sgd_srd_codigo, sgd_sbrd_codigo,  depe_codi, usua_doc, sgd_sexp_fech,  sgd_sexp_ano, usua_doc_responsable, sgd_sexp_parexp1, sgd_sexp_parexp2, sgd_sexp_parexp3, sgd_sexp_parexp4, sgd_sexp_parexp5, sgd_sexp_parexp9, sgd_sexp_parexp10,  sgd_sexp_estado, sgd_srd_id, sgd_sbrd_id)
            VALUES ('" . $dd['num'] . "', '" . $dd['sgd_srd_codigo'] . "', '" . $dd['sgd_sbrd_codigo'] . "', '" . $depe . "', '" . $dd['usua_doc'] . "', '" . $dd['fech'] . "', '" . $dd['sgd_sexp_ano'] . "', '" . $responsable . "', '" . $dd['titulo'] . "', '" . $dd['param2'] . "', '" . $dd['param3'] . "', '" . $dd['param4'] . "', '" . $dd['param5'] . "', '" . $dd['param9'] . "', '" . $dd['param10'] . "', '" . $estado . "', '" . $dd['sgd_srd_id'] . "', '" . $dd['sgd_sbrd_id'] . "');";
        $rs = $this->link->conn->query($sql);
        return true;
    }

    function ExcluirExpR($numExp, $numRad)
    {
        $consulta = "update SGD_EXP_EXPEDIENTE set  SGD_EXP_ESTADO=2   where   SGD_EXP_NUMERO='$numExp' and RADI_NUME_RADI in ($numRad)";
        $resultado = $this->link->query($consulta);
    }

    function ExcluirExpAnexo($numExp, $numRad)
    {
        $consulta = "update sgd_exp_anexos set  exp_anex_borrado='S'   where    exp_numero='$numExp' and exp_consecutivo in ($numRad)";
        $resultado = $this->link->query($consulta);
    }

    function cambiarSeguridad($expediente, $seguridad)
    {
        $update = 'UPDATE sgd_sexp_secexpedientes SET seguridad = ? WHERE sgd_exp_numero = ?';
        $resultado = $this->link->conn->Execute($update, [$seguridad, $expediente]);
        return $resultado;
    }

    function cambiarResponsableMasiva($expedientes, $usua_doc, $usuario, $dependencia)
    {
        foreach ($expedientes as $expediente) {
            $usua_doc_ante = $this->link->conn->getOne('SELECT usua_doc_responsable FROM sgd_sexp_secexpedientes WHERE sgd_exp_numero = ?', [$expediente]);
            $usua_ante = $this->link->conn->getOne('SELECT usua_nomb FROM usuario WHERE usua_doc = ? LIMIT 1', [$usua_doc_ante]);
            $usua_nuevo = $this->link->conn->getOne('SELECT usua_nomb FROM usuario WHERE usua_doc = ? LIMIT 1', [$usua_doc]);
            $observacion = 'Cambio de responsable ' . $usua_ante . ' a ' . $usua_nuevo;
            $this->insertarHistoricoExp($expediente, [], $dependencia, $usuario, $observacion, 56, 0);
        }

        $update = 'UPDATE sgd_sexp_secexpedientes SET usua_doc_responsable = ? WHERE sgd_exp_numero IN (\'' . implode("','", $expedientes) . '\')';
        $resultado = $this->link->conn->Execute($update, [$usua_doc]);
        return $resultado;
    }

    function cambiarPermisosMasivo($expedientes_array, $depe_codi, $usua_codi_array, $permisos)
    {
        $trasanccion_satisfactoria = true;

        foreach ($usua_codi_array as $usua_codi) {
            foreach ($expedientes_array as $expediente) {
                $delete = 'DELETE FROM sgd_aexp_aclexp WHERE depe_codi = ? AND usua_codi = ? AND num_expediente = ?';
                $resultado = $this->link->conn->Execute($delete, [$depe_codi, $usua_codi, $expediente]);
                $insert = 'INSERT INTO sgd_aexp_aclexp (num_expediente, depe_codi, usua_codi, aexp_acl) VALUES (?, ?, ?, ?)';
                $transaccion = $this->link->conn->Execute($insert, [$expediente, $depe_codi, $usua_codi, $permisos]);
                if (!$transaccion)
                    $trasanccion_satisfactoria = false;
            }
        }

        return $trasanccion_satisfactoria;
    }

    function validarEstadoExpediente($expediente, $estado)
    {
        return $this->link->conn->getOne('SELECT 1 FROM sgd_sexp_secexpedientes WHERE sgd_exp_numero = ? AND sgd_sexp_estado = ?', [$expediente, $estado]);
    }

    function validarExpedienteVacio($expediente)
    {
        $anexos = $this->link->conn->getOne("SELECT count(id) FROM sgd_exp_anexos WHERE exp_anex_borrado = 'N' AND exp_anex_nomb_archivo LIKE '%" . $expediente . "%'");
        $radicados = $this->link->conn->getOne("SELECT count(id) as total from sgd_exp_expediente WHERE sgd_exp_numero = '" . $expediente . "' AND sgd_exp_estado != 2");

        return $anexos == 0 && $radicados == 0;
    }

    function totalAnexos($expediente)
    {
        $anexos = $this->link->conn->getOne("SELECT count(id) FROM sgd_exp_anexos WHERE exp_anex_borrado = 'N' AND exp_anex_nomb_archivo LIKE '%" . $expediente . "%'");

        return $anexos;
    }

    function copiarArchivoAnexo($nombreArchivo, $expedienteDestino)
    {
        $rutaActual = $this->ruta_raiz;
        $rutaArchivoOrigen = $rutaActual . '/bodega/' . substr($nombreArchivo, 0, 4) . '/' . substr($nombreArchivo, 4, 5) . '/docs/' . $nombreArchivo;
        $archivo = substr($nombreArchivo, strpos($nombreArchivo, '_'));

        // Construir la ruta del archivo de destino
        $rutaArchivoDestino = $rutaActual . '/bodega/' . substr($expedienteDestino, 0, 4) . '/' . substr($expedienteDestino, 4, 5) . '/docs/' . $expedienteDestino . $archivo;

        // Verificar si el archivo de origen existe
        if (file_exists($rutaArchivoOrigen)) {
            // Copiar el archivo al destino
            if (copy($rutaArchivoOrigen, $rutaArchivoDestino)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function clonarAnexo($expediente, $data)
    {
        $exp_consecutivo = $this->link->conn->getOne("SELECT max(exp_consecutivo) FROM sgd_exp_anexos WHERE exp_anex_nomb_archivo LIKE '" . $expediente . "%'");
        $exp_consecutivo = $exp_consecutivo == NULL ? 0 : $exp_consecutivo;
        $exp_anex_nomb_archivo = $expediente . substr($data['EXP_ANEX_NOMB_ARCHIVO'], strpos($data['EXP_ANEX_NOMB_ARCHIVO'], '_'));
        $id = time();

        if ($this->copiarArchivoAnexo($data['EXP_ANEX_NOMB_ARCHIVO'], $expediente)) {
            $insert = '
                    insert into public.sgd_exp_anexos (
                        id,
                        exp_anex_tipo, 
                        exp_anex_tamano, 
                        exp_anex_creador, 
                        exp_anex_desc,
                        exp_anex_nomb_archivo, 
                        exp_anex_borrado, 
                        exp_anex_radi_fech, 
                        exp_anex_hash,
                        exp_numero, 
                        exp_anex_path, 
                        exp_consecutivo, 
                        exp_fisico, 
                        exp_carpeta, 
                        exp_subexp,
                        exp_tpdoc
                    ) values (
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?
                    )';

            $status = $this->link->conn->Execute($insert, [
                $id,
                $data['EXP_ANEX_TIPO'],
                $data['EXP_ANEX_TAMANO'],
                $data['EXP_ANEX_CREADOR'],
                $data['EXP_ANEX_DESC'],
                $exp_anex_nomb_archivo,
                $data['EXP_ANEX_BORRADO'],
                $data['EXP_ANEX_RADI_FECH'],
                $data['EXP_ANEX_HASH'],
                $data['EXP_NUMERO'],
                $data['EXP_ANEX_PATH'],
                $exp_consecutivo + 1,
                $data['EXP_FISICO'],
                $data['EXP_CARPETA'],
                $data['EXP_SUBEXP'],
                $data['EXP_TPDOC']
            ]);

            return 'INCLUIR ANEXO EN EXPEDIENTE: ' . $exp_anex_nomb_archivo;
        } else {
            return 'No se encontro el archivo: ' . $data['EXP_ANEX_NOMB_ARCHIVO'];
        }
    }

    function clonarRadicado($expediente, $data)
    {
        $insert = '
            insert into public.sgd_exp_expediente (
                sgd_exp_numero,
                radi_nume_radi,
                sgd_exp_fech,
                sgd_exp_fech_mod,
                depe_codi,
                usua_codi,
                usua_doc,
                sgd_exp_estado,
                sgd_exp_titulo,
                sgd_exp_asunto,
                sgd_exp_carpeta,
                sgd_exp_ufisica,
                sgd_exp_isla,
                sgd_exp_estante,
                sgd_exp_caja,
                sgd_exp_fech_arch,
                sgd_srd_codigo,
                sgd_sbrd_codigo,
                sgd_fexp_codigo,
                sgd_exp_subexpediente,
                sgd_exp_archivo,
                sgd_exp_unicon,
                sgd_exp_fechfin,
                sgd_exp_folios,
                sgd_exp_rete,
                sgd_exp_entrepa,
                radi_usua_arch,
                sgd_exp_edificio,
                sgd_exp_caja_bodega,
                sgd_exp_carro,
                sgd_exp_carpeta_bodega,
                sgd_exp_privado,
                sgd_exp_cd,
                sgd_exp_nref,
                sgd_exp_fechafin,
                sgd_prd_codigo
            ) values (
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?
            )
        ';

        $status = $this->link->conn->Execute($insert, [
            $expediente,
            $data['RADI_NUME_RADI'],
            $data['SGD_EXP_FECH'],
            $data['SGD_EXP_FECH_MOD'],
            $data['DEPE_CODI'],
            $data['USUA_CODI'],
            $data['USUA_DOC'],
            $data['SGD_EXP_ESTADO'],
            $data['SGD_EXP_TITULO'],
            $data['SGD_EXP_ASUNTO'],
            $data['SGD_EXP_CARPETA'],
            $data['SGD_EXP_UFISICA'],
            $data['SGD_EXP_ISLA'],
            $data['SGD_EXP_ESTANTE'],
            $data['SGD_EXP_CAJA'],
            $data['SGD_EXP_FECH_ARCH'],
            $data['SGD_SRD_CODIGO'],
            $data['SGD_SBRD_CODIGO'],
            $data['SGD_FEXP_CODIGO'],
            $data['SGD_EXP_SUBEXPEDIENTE'],
            $data['SGD_EXP_ARCHIVO'],
            $data['SGD_EXP_UNICON'],
            $data['SGD_EXP_FECHFIN'],
            $data['SGD_EXP_FOLIOS'],
            $data['SGD_EXP_RETE'],
            $data['SGD_EXP_ENTREPA'],
            $data['RADI_USUA_ARCH'],
            $data['SGD_EXP_EDIFICIO'],
            $data['SGD_EXP_CAJA_BODEGA'],
            $data['SGD_EXP_CARRO'],
            $data['SGD_EXP_CARPETA_BODEGA'],
            $data['SGD_EXP_PRIVADO'],
            $data['SGD_EXP_CD'],
            $data['SGD_EXP_NREF'],
            $data['SGD_EXP_FECHAFIN'],
            $data['SGD_PRD_CODIGO']
        ]);
    }

    function incluirRadicadosEnExpediente($expediente, $elementos, $usuario, $dependencia)
    {
        $status = true;
        foreach ($elementos as $elemento) {
            if ($elemento['tipo'] == 'aexp') {
                $data = $this->link->conn->getRow('SELECT * FROM sgd_exp_anexos WHERE exp_anex_nomb_archivo = ?', [$elemento['path']]);
                $result = $this->clonarAnexo($expediente, $data);
                $observacion = $result;
                $this->insertarHistoricoExp($expediente, [], $dependencia, $usuario, $observacion, 91, 0);
            } else {
                $data = $this->link->conn->getRow('SELECT * FROM sgd_exp_expediente WHERE radi_nume_radi = ? AND sgd_exp_estado = 0', [$elemento['seleccion']]);
                $result = $this->clonarRadicado($expediente, $data);
                $observacion = 'Incluir radicado en expediente';
                $this->insertarHistoricoExp($expediente, [$elemento['seleccion']], $dependencia, $usuario, $observacion, 53, 0);
            }
        };

        return $status;
    }
}
