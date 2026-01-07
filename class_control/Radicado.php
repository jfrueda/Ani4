<?
require_once("$ruta_raiz/include/db/ConnectionHandler.php");
require_once("$ruta_raiz/class_control/Departamento.php");
require_once("$ruta_raiz/class_control/Municipio.php");
require_once("$ruta_raiz/class_control/Esp.php");

/**
 * Radicado es la clase encargada de gestionar la informacion referente a un radicado
 * @author      Sixto Angel Pinzon
 * @version     1.0
 */
class Radicado{
/**
   * Gestor de las transacciones con la base de datos
   * @var ConnectionHandler
   * @access public
   */
	var $cursor;
 /**
   * Variable que se corresponde con su par, uno de los campos de la tabla Radicado
   * @var numeric
   * @access public
   */
	var $tdoc_codi;
/**
   * Variable que se corresponde con su par, uno de los campos de la tabla Radicado
   * @var string
   * @access public
   */
	var $radi_fech_radi;
/**
   * Variable que se corresponde con su par, uno de los campos de la tabla Radicado
   * @var numeric
   * @access public
   */
	var $radi_nume_radi;
/**
   * Variable que se corresponde con su par, uno de los campos de la tabla Radicado
   * @var numeric
   * @access public
   */
	var $tdid_codi;
/**
   * Variable que se corresponde con su par, uno de los campos de la tabla Radicado
   * @var string
   * @access public
   */
	var $radi_path;
/**
   * Variable que se corresponde con su par, uno de los campos de la tabla Radicado
   * @var string
   * @access public
   */
	var $radi_usua_radi;
/**
   * Variable que se corresponde a la dependencia actual del radicado
   * @var string
   * @access public
   */
        var $radi_depe_actu;

/**
   * Variable que se corresponde al usuario actual del radicado
   * @var string
   * @access public
   */
        var $radi_usua_actu;


/**
   * Variable que se corresponde al nivel de seguridaddel radicado
   * @var string
   * @access public
   */
        var $sgd_spub_codigo;


/**
   * Variable que se corresponde al nivel de seguridaddel radicado
   * @var string
   * @access public
   */
        var $ra_asun;
/** 
* Constructor encargado de obtener la conexion
* @param	$db	ConnectionHandler es el objeto conexion
* @return   void
*/

  function __construct($db){
    $this->cursor = $db;
  }

  function Radicado($db){
		$this->cursor = $db;
	}

	

/** 
* Carga los atributos de la clase con los datos del radicado enviado como parametro, si existen datos retorna true, de lo contrario false
* @param	$codigo	string	es el codigo del radicado 
* @return   boolen
*/	
	function radicado_codigo($codigo){
	//almacena el query
	     $sqlFecha = $this->cursor->conn->SQLDate("Y/m/d","r.radi_fech_radi");
	     $db = &$this->cursor;
	     include ($this->cursor->rutaRaiz."/include/query/class_control/queryRadicado.php");
	     $rs=$this->cursor->query($qeryRadicado_codigo);

		//Si existen resultados
		if  (!$rs->EOF){
			$this->tdid_codi=$rs->fields['TDID_CODI'];
			$this->tdoc_codi=$rs->fields['TDOC_CODI']; 
			$this->radi_fech_radi=$rs->fields['FECDOC'];
			$this->radi_nume_radi = $rs->fields['RADNUM']; 
			$this->radi_path = $rs->fields['RADI_PATH']; 
			$this->radi_usua_radi = $rs->fields['RADI_USUA_RADI']; 
			$this->radi_usua_actu = $rs->fields['RADI_USUA_ACTU'];
			$this->radi_depe_actu = $rs->fields['RADI_DEPE_ACTU'];
			$this->sgd_spub_codigo = $rs->fields['SGD_SPUB_CODIGO'];
			$this->ra_asun = $rs->fields['RA_ASUN'];
			
			return true;
		}else{
			$this->tdid_codi="";
			$this->tdoc_codi=""; 
			$this->radi_fech_radi="";
			$this->radi_nume_radi = ""; 
			$this->radi_path = ""; 
			$this->radi_usua_radi="";
			$this->radi_usua_actu = "";
			$this->radi_depe_actu = "";
			$this->sgd_spub_codigo = "";
			$this->ra_asun = "";
			return false;
		}
		
	}


/** 
* Retorna un array con los datos del remitente de un radicado, este vector contiene los indices 'nombre','direccion','deptoNombre','muniNombre','deptoCodi','muniCodi'; antes de invocar esta funcion, se debe llamar a  radicado_codigo()
* @return   array
*/
	function getDatosRemitente(){
  	//almacena el query
		$q="select  d.*,ciu.sgd_ciu_cedula  from sgd_dir_drecciones d left join sgd_ciu_ciudadano ciu on  d.sgd_ciu_codigo = ciu.sgd_ciu_codigo  where d.radi_nume_radi =".$this->radi_nume_radi;

		$rs=$this->cursor->query($q);
		//Agregada por Johnny debido a solicitud de usuarios
		$direccion = $rs->fields['SGD_DIR_DIRECCION']; 
		$deptoCodi = $rs->fields['DPTO_CODI']; 
		$muniCodi = $rs->fields['MUNI_CODI'];
		$paisCodi = $rs->fields['ID_PAIS'];
		$documento = empty($rs->fields['SGD_DIR_DOC'])?$rs->fields['SGD_DOC_FUN']:$rs->fields['SGD_DIR_DOC'];
		$documento_ciu = $rs->fields['SGD_CIU_CEDULA'];
		$contCodi = $rs->fields['ID_CONT'];
		$email    = $rs->fields['SGD_DIR_MAIL'];
		//Agregada por Johnny debido a solicitud de usuarios
		$nombre = $rs->fields['SGD_DIR_NOMREMDES']; 
		$dep= new Departamento($this->cursor);
		$mun =  new Municipio($this->cursor); 
		$dep->departamento_codigo($paisCodi.'-'.$deptoCodi);
		$mun->municipio_codigo($paisCodi.'-'.$deptoCodi,$paisCodi.'-'.$deptoCodi.'-'.$muniCodi);
	
	//Si se hallaron datos del remitente
	if ($dep){
		$vecDatos["nombre"]=$nombre;
		$vecDatos["direccion"]=$direccion;
		$vecDatos["deptoNombre"]=$dep->get_dpto_nomb();
		$vecDatos["muniNombre"]=$mun->get_muni_nomb();
		$vecDatos["contCodi"]=$contCodi;
		$vecDatos["paisCodi"]=$paisCodi;
		$vecDatos["deptoCodi"]=$deptoCodi;
		$vecDatos["muniCodi"]=$muniCodi;		
		$vecDatos["email"]=$email;		
		$vecDatos["documento"]=$documento;		
		$vecDatos["documento_ciu"]=$documento_ciu;		
		
		
	}
	
	return ($vecDatos);
}

/** 
* Retorna un array con los datos del salida de un radicado
* @return   entero
*/
function getEstado(){
	//almacena el query
	$q="select *  from anexos where radi_nume_salida =".$this->radi_nume_radi;
	$rs=$this->cursor->query($q);
	//Agregada por Johnny debido a solicitud de usuarios
	$estado = $rs->fields['ANEX_ESTADO']; 
	return (isset($estado)?$estado:0);
}


/** 
* Retorna un array con los datos del salida de un radicado
* @return   entero
*/
function getAcuses($conn){
	//almacena el query
	$q="select * from sgd_renv_regenvio where sgd_renv_dir like '%target=\"_blank\" href=\"./bodega/cert/0%' and radi_nume_sal =".$this->radi_nume_radi;
	$rs=$conn->query($q);
	$acuses = '';
	if($rs){
		while(!$rs->EOF){
			$acuses .= $rs->fields['SGD_RENV_DIR'].'  '; 
			$rs->MoveNext();
		}
	}
	return (empty($acuses)?'Sin acuses':$acuses);
}

/** 
* Retorna un array con los datos del salida de un radicado
* @return   entero
*/
function getFechaEnvio(){
	//almacena el query
	$q="select *  from anexos where radi_nume_salida =".$this->radi_nume_radi;
	$rs=$this->cursor->query($q);
	//Agregada por Johnny debido a solicitud de usuarios
	$estado = $rs->fields['ANEX_FECH_ENVIO']; 
	return (isset($estado)?$estado:'No registra');
}

/** 
* Retorna un array con los datos del salida de un radicado
* @return   entero
*/
function getEmail(){
	//almacena el query
	$q="select *  from anexos where radi_nume_salida =".$this->radi_nume_radi;
	$rs=$this->cursor->query($q);
	//Agregada por Johnny debido a solicitud de usuarios
	$correo = $rs->fields['SGD_DIR_MAIL'];
	return (isset($correo)?$correo:'');
}

/** 
* Retorna un string  con el dato correspondiente a la fecha de radicacion;  antes de invocar esta funcion, se debe llamar a  radicado_codigo()
* @return   string
*/
	function getRadi_fech_radi($formato = null){
		if (!empty($formato)) {
			// en la pos0 es el ano, pos1 mes, pos2 dia
			$arregloFecha = explode("/",$this->radi_fech_radi);
						$arregloFecha[2].
						$arregloFecha[0]."<hr>";
			return date($formato, @mktime(0, 0, 0, 
						$arregloFecha[1],
						$arregloFecha[2],
						$arregloFecha[0]));
		}
		return($this->radi_fech_radi);
	}
	
	
/**
 *  Retorna un array de objetos Radicado donde se instancian sus propiedades principales 
 */	
	function getObjects($conn, $RADI_NUME_RADI="%"){
	   	$sql="SELECT * FROM RADICADO WHERE RADI_NUME_RADI LIKE '$RADI_NUME_RADI'";	   		
	    $rs=$conn->query($sql);
		while(!$rs->EOF){
			$rad=new Radicado();
			$rad->tdid_codi=$rs->fields['TDID_CODI'];
			$rad->tdoc_codi=$rs->fields['TDOC_CODI']; 
			$rad->radi_fech_radi=$rs->fields['FECDOC'];
			$rad->radi_nume_radi = $rs->fields['RADNUM']; 
			$rad->radi_path = $rs->fields['RADI_PATH']; 
			$rad->radi_usua_radi = $rs->fields['RADI_USUA_RADI'];
			$rad->ra_asun = $rs->fields['RA_ASUN'];
			
			$xarray[]=$rad;
			$rs->moveNext();						
		}
		return $xarray;
	}

/** 
* Retorna un string  con el dato correspondiente al path de la imagen digitalizada del radicado
* @return   string
*/
	function getRadi_path(){
		return($this->radi_path);
	}


/** 
* Retorna un string  con el dato correspondiente al codigo del tipo de documento que es el radicado
* @return   string
*/
	function getTdocCodi(){
		return($this->tdoc_codi);
	}
	
/** 
* Retorna un string  con el dato correspondiente al codigo del usuario radicador
* @return   string
*/
	function getUsuaRad(){
		return($this->radi_usua_radi);
	}

/** 
* Retorna un entero con el dato correspondiente a la dependencia actual del radicado
* @return   string
*/
        function getRadiDepeActuRad(){
                return($this->radi_depe_actu);
        }


/** 
* Retorna un entero con el dato correspondiente al usuario actual del radicado
* @return   string
*/
        function getRadiUsuaActuRad(){
                return($this->radi_usua_actu);
        }


/** 
* Retorna un entero con el dato correspondiente nivel de seguridad del radicado
* @return   string
*/
        function getSpubCodigoRad(){
                return($this->sgd_spub_codigo);
        }
	
	
/** 
* Retorna un entero con el dato correspondiente nivel de seguridad del radicado
* @return   string
*/
        function getAsuntoRad(){
                return($this->ra_asun);
        }

	function validarSeguridadExpediente($radicado, $expediente, $usuario) {
		$radicado = $this->cursor->conn->getRow('SELECT * FROM radicado WHERE radi_nume_radi = ?', [$radicado]);
		
		$seguridad_expediente = 'Público';
		switch($expediente['SEGURIDAD'])
		{
			case '0':
				$seguridad_expediente = "Público";
			break;
			case '1':
				$seguridad_expediente = "Reservado";
			break;
			case '2':
				$seguridad_expediente = "Clasificado";
			break;
		}

		$seguridad_radicado = 'Público';
		switch($radicado['SGD_SPUB_CODIGO'])
		{
			case '0':
				$seguridad_radicado = "Público";
			break;
			case '1':
				$seguridad_radicado = "Reservado";
			break;
			case '2':
				$seguridad_radicado = "Clasificado";
			break;
			default :
				$seguridad_radicado = "Público";
			break;
		}

		$es_responsable_expediente = $usuario['usua_doc'] == $expediente['USUA_DOC_RESPONSABLE'];

		if($seguridad_expediente == 'Público') {
			if ($seguridad_radicado == 'Público') {
				return true;
			} else if ($seguridad_radicado == 'Reservado') {
				$es_usuario_dependencia = $this->esUsuarioDependencia($expediente, $radicado, $usuario);
				$es_usuario_memorando = $radicado['SGD_TRAD_CODIGO'] == '3' ? $this->esUsuarioMemorando($usuario, $radicado['RADI_NUME_RADI']) : false;
				return $es_usuario_dependencia || 
						$es_usuario_memorando || 
						$es_responsable_expediente;

			} else if ($seguridad_radicado == 'Clasificado') {
				$usuario_creador_ttr = $this->cursor->conn->getRow('SELECT usua_codi, depe_codi FROM hist_eventos WHERE radi_nume_radi = ? AND sgd_ttr_codigo = 2 LIMIT 1', [$radicado['RADI_NUME_RADI']]);
				$usario_creador = [
					'dependencia' => $usuario_creador_ttr['DEPE_CODI'],
					'codigo' => $usuario_creador_ttr['USUA_CODI']
				];

				$usuario_actual = [
					'dependencia' => $radicado['RADI_DEPE_ACTU'],
					'codigo' => $radicado['RADI_USUA_ACTU']
				];

				$es_usuario_creador = $usario_creador['codigo'] == $usuario['codusuario'] && $usario_creador['dependencia'] == $usuario['dependencia'];
				$es_usuario_actual = $usuario_actual['codigo'] == $usuario['codusuario'] && $usuario_actual['dependencia'] == $usuario['dependencia'];
				$es_jefe_dependencia_creador = $usuario['USUA_JEFE_DE_GRUPO'] && $usario_creador['dependencia'] == $usuario['dependencia'];
				$es_jefe_dependencia_actual = $usuario['USUA_JEFE_DE_GRUPO'] && $usuario_actual['dependencia'] == $usuario['dependencia'];
				$es_jefe_dependencia_expediente = $usuario['USUA_JEFE_DE_GRUPO'] && $expediente['DEPE_CODI'] == $usuario['dependencia'];
				$es_usuario_historico = $this->esUsuarioHistorico($usuario, $radicado['RADI_NUME_RADI']);

				return $es_usuario_creador || 
						$es_usuario_actual || 
						$es_jefe_dependencia_creador || 
						$es_jefe_dependencia_actual || 
						$es_jefe_dependencia_expediente || 
						$es_usuario_historico ||
						$es_responsable_expediente;
			}
		} else if ($seguridad_expediente == 'Reservado') {
			if ($seguridad_radicado == 'Reservado' || $seguridad_radicado == 'Público') {
				$es_usuario_dependencia = $this->esUsuarioDependencia($expediente, $radicado, $usuario);
				$es_usuario_memorando = $radicado['SGD_TRAD_CODIGO'] == '3' ? $this->esUsuarioMemorando($usuario, $radicado['RADI_NUME_RADI']) : false;
				return $es_usuario_dependencia || 
						$es_usuario_memorando ||
						$es_responsable_expediente;
			} else if ($seguridad_radicado == 'Clasificado') {
				$usuario_creador_ttr = $this->cursor->conn->getRow('SELECT usua_codi, depe_codi FROM hist_eventos WHERE radi_nume_radi = ? AND sgd_ttr_codigo = 2 LIMIT 1', [$radicado['RADI_NUME_RADI']]);
				$usario_creador = [
					'dependencia' => $usuario_creador_ttr['DEPE_CODI'],
					'codigo' => $usuario_creador_ttr['USUA_CODI']
				];

				$usuario_actual = [
					'dependencia' => $radicado['RADI_DEPE_ACTU'],
					'codigo' => $radicado['RADI_USUA_ACTU']
				];
				
				$es_usuario_creador = $usario_creador['codigo'] == $usuario['codusuario'] && $usario_creador['dependencia'] == $usuario['dependencia'];
				$es_usuario_actual = $usuario_actual['codigo'] == $usuario['codusuario'] && $usuario_actual['dependencia'] == $usuario['dependencia'];
				$es_jefe_dependencia_creador = $usuario['USUA_JEFE_DE_GRUPO'] && $usario_creador['dependencia'] == $usuario['dependencia'];
				$es_jefe_dependencia_actual = $usuario['USUA_JEFE_DE_GRUPO'] && $usuario_actual['dependencia'] == $usuario['dependencia'];
				$es_jefe_dependencia_expediente = $usuario['USUA_JEFE_DE_GRUPO'] && $expediente['DEPE_CODI'] == $usuario['dependencia'];
				$es_usuario_historico = $this->esUsuarioHistorico($usuario, $radicado['RADI_NUME_RADI']);

				return $es_usuario_creador ||
					$es_usuario_actual ||
					$es_jefe_dependencia_creador || 
					$es_jefe_dependencia_expediente ||
					$es_usuario_historico ||
					$es_responsable_expediente;
			}
		} else if ($seguridad_expediente == 'Clasificado') {
			if ($seguridad_radicado == 'Clasificado') {
				$usuario_creador_ttr = $this->cursor->conn->getRow('SELECT usua_codi, depe_codi FROM hist_eventos WHERE radi_nume_radi = ? AND sgd_ttr_codigo = 2 LIMIT 1', [$radicado['RADI_NUME_RADI']]);
				$usario_creador = [
					'dependencia' => $usuario_creador_ttr['DEPE_CODI'],
					'codigo' => $usuario_creador_ttr['USUA_CODI']
				];

				$usuario_actual = [
					'dependencia' => $radicado['RADI_DEPE_ACTU'],
					'codigo' => $radicado['RADI_USUA_ACTU']
				];

				$es_usuario_creador = $usario_creador['codigo'] == $usuario['codusuario'] && $usario_creador['dependencia'] == $usuario['dependencia'];
				$es_usuario_actual = $usuario_actual['codigo'] == $usuario['codusuario'] && $usuario_actual['dependencia'] == $usuario['dependencia'];
				$es_jefe_dependencia_creador = $usuario['USUA_JEFE_DE_GRUPO'] && $usario_creador['dependencia'] == $usuario['dependencia'];
				$es_jefe_dependencia_actual = $usuario['USUA_JEFE_DE_GRUPO'] && $usuario_actual['dependencia'] == $usuario['dependencia'];
				$es_jefe_dependencia_expediente = $usuario['USUA_JEFE_DE_GRUPO'] && $expediente['DEPE_CODI'] == $usuario['dependencia'];
				$es_usuario_historico = $this->esUsuarioHistorico($usuario, $radicado['RADI_NUME_RADI']);

				return $es_usuario_creador ||
					$es_usuario_actual || 
					$es_jefe_dependencia_creador || 
					$es_jefe_dependencia_actual ||
					$es_jefe_dependencia_expediente || 
					$es_usuario_historico ||
					$es_responsable_expediente;
			}
		} else {
			return false;
		}
	}

	function esUsuarioDependencia($expediente, $radicado, $usuario) {
		$dependencias_habilitadas = [
			$expediente['DEPE_CODI']
		];

		$dependencias_transacciones = $this->cursor->conn->getAll('
			SELECT
				depe_codi AS dependencia
			FROM
				hist_eventos
			WHERE
				radi_nume_radi = ? AND sgd_ttr_codigo not in (110, 8) UNION
			SELECT 
				depe_codi_dest AS dependencia
			FROM
				hist_eventos
			WHERE
				radi_nume_radi = ? AND sgd_ttr_codigo not in (110, 8)', [$radicado['RADI_NUME_RADI'], $radicado['RADI_NUME_RADI']]);

		foreach($dependencias_transacciones as $dependencia) {
			if(!in_array($dependencia['DEPENDENCIA'], $dependencias_habilitadas))
				$dependencias_habilitadas[] = $dependencia['DEPENDENCIA'];
		}

		return in_array($usuario['dependencia'], $dependencias_habilitadas);
	}

	function esUsuarioHistorico($usuario, $radicado) {
		$usuarios_historicos = $this->cursor->conn->getAll('
					SELECT usua_codi as codigo, depe_codi as dependencia FROM hist_eventos WHERE radi_nume_radi = ? UNION 
					SELECT usua_codi_dest as codigo, depe_codi_dest as dependencia FROM hist_eventos WHERE radi_nume_radi = ?', [
						$radicado,
						$radicado
					]);
		
		foreach($usuarios_historicos as $usuario_historico)
		{
			// si es usuario del historico
			if ($usuario['dependencia'] == $usuario_historico['DEPENDENCIA'] && $usuario['codusuario'] == $usuario_historico['CODIGO'])
				return true;

			// si es jefe de la dependencia del historico
			if ($usuario['USUA_JEFE_DE_GRUPO'] && $usuario['dependencia'] == $usuario_historico['DEPENDENCIA'])
				return true;

		}

		return false;
	}

	function esUsuarioMemorando($usuario, $radicado) {
		$radicadoEnHistorico = $radicado;
		$estaEnHistorico = false;
		$esDestinatario = false;
		$usua_doc = $usuario['usua_doc'];
		$codusuario = $usuario['codusuario'];
		$dependencia = $usuario['dependencia'];

		$sqlverRelacionados = "SELECT
			(SELECT COUNT(*) FROM INFORMADOS WHERE cast(RADI_NUME_RADI as varchar(20)) = '$radicadoEnHistorico' AND USUA_DOC = '$usua_doc') AS total_informado,
			(SELECT COUNT(*) FROM TRAMITECONJUNTO WHERE cast(RADI_NUME_RADI as varchar(20)) = '$radicadoEnHistorico' AND USUA_DOC = '$usua_doc') AS total_conjunto,
			(SELECT COUNT(*) FROM SGD_DIR_DRECCIONES WHERE RADI_NUME_RADI = '$radicadoEnHistorico' AND SGD_DIR_DOC = '$usua_doc') AS total_destinatario,
			(SELECT COUNT(*) FROM HIST_EVENTOS WHERE RADI_NUME_RADI = '$radicadoEnHistorico' AND ((USUA_DOC = '$usua_doc') OR (USUA_CODI = '$codusuario' AND DEPE_CODI = '$dependencia') OR (HIST_DOC_DEST = '$usua_doc'))) AS total_historico";

		$rsverRelacionados = $this->cursor->conn->Execute($sqlverRelacionados);
		if (!$rsverRelacionados->EOF) {
			if ($rsverRelacionados->fields['TOTAL_DESTINATARIO'] > 0) {
				$esDestinatario = true;
			}
			if ($rsverRelacionados->fields['TOTAL_INFORMADO'] > 0 || $rs->fields['TOTAL_HISTORICO'] > 0 || $rsverRelacionados->fields['TOTAL_CONJUNTO'] > 0) {
				$estaEnHistorico = true;
			}
		}

		return $esDestinatario || $estaEnHistorico;
	}

	function obtenerMensajeRadicadoDesdeExpediente($key) {
		$key = explode('|', $key);
		$numero_expediente = $key[0];
		$numero_radicado = $key[1];

		$expediente = $this->consultarExp($numero_expediente);
		$radicado = $this->cursor->conn->getRow('SELECT * FROM radicado WHERE radi_nume_radi = ?', [$numero_radicado]);

		$seguridad_expediente = 'Público';
		switch($expediente['SEGURIDAD'])
		{
			case '0':
				$seguridad_expediente = "Público";
			break;
			case '1':
				$seguridad_expediente = "Reservado";
			break;
			case '2':
				$seguridad_expediente = "Clasificado";
			break;
			default :
				$seguridad_expediente = "Público";
			break;
		}

		$seguridad_radicado = 'Público';
		switch($radicado['SGD_SPUB_CODIGO'])
		{
			case '0':
				$seguridad_radicado = "Público";
			break;
			case '1':
				$seguridad_radicado = "Reservado";
			break;
			case '2':
				$seguridad_radicado = "Clasificado";
			break;
			default :
				$seguridad_radicado = "Público";
			break;
		}

		if($seguridad_expediente == 'Público') {
			if ($seguridad_radicado == 'Público') {
				return '
					<div class="alert alert-success alert-dismissable">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						 Expediente público. Disponible para todos los usuarios con acceso al sistema.
					</div>
				';
			} else if ($seguridad_radicado == 'Reservado') {
				return '
					<div class="alert alert-warning alert-dismissable">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						Este expediente contiene radicados reservados. Solo las dependencias que hayan intervenido en el histórico de dichos radicados pueden acceder a su contenido.
					</div>
				';
			} else if ($seguridad_radicado == 'Clasificado') {
				return '
					<div class="alert alert-warning alert-dismissable">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						Este expediente contiene radicados clasificados. El acceso está restringido únicamente a los usuarios responsables y a quienes hayan intervenido en el histórico de estos radicados.
					</div>
				';
			}
		} else if ($seguridad_expediente == 'Reservado') {
			if ($seguridad_radicado == 'Reservado' || $seguridad_radicado == 'Público') {
				return '
					<div class="alert alert-warning alert-dismissable">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						Expediente reservado. Acceso permitido exclusivamente a la dependencia productora y a las dependencias que hayan intervenido en los históricos de los radicados reservados.
					</div>
				';
			} else if ($seguridad_radicado == 'Clasificado') {
				return '
					<div class="alert alert-warning alert-dismissable">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						Expediente reservado con contenido clasificado. Solo puede ser consultado por la dependencia productora del expediente y los usuarios autorizados que intervinieron en los históricos de los radicados clasificados.
					</div>
				';
			}
		} else if ($seguridad_expediente == 'Clasificado') {
			return '
				<div class="alert alert-warning alert-dismissable">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					Expediente clasificado. Solo el jefe de la dependencia productora del expediente y los usuarios responsables de los radicados clasificados pueden consultar su contenido. El acceso a los demás funcionarios está restringido.
				</div>
			';
		}
	}

	function validarLlaveRadicado($key) {
		$key = explode('|', $key);
		try {
			$count = $this->cursor->conn->GetOne('SELECT COUNT(*) FROM sgd_exp_expediente WHERE sgd_exp_numero = ? AND radi_nume_radi = ?', $key);
			return $count > 0;
		} catch (Exception $e) {
			return false;
		}
	}

	public function consultarExp($numExp)
    {
        $iSql = "
        select SEXP.*, u.usua_nomb creador,ub.usua_nomb responsable,d.depe_nomb depe
         from SGD_SEXP_SECEXPEDIENTES SEXP
        left join usuario u on u.usua_doc=SEXP.usua_doc
        left join usuario ub on ub.usua_doc=SEXP.usua_doc_responsable
        left join dependencia d on d.depe_codi=SEXP.depe_codi
        WHERE SEXP.SGD_EXP_NUMERO = '$numExp'
        
        ";

        $rs = $this->cursor->conn->query($iSql);

        if (!$rs->EOF) {
                foreach ($rs->fields as $key => $value) {                   
                        $dd[strtoupper($key)] = $value;
                }
                $rs->MoveNext();
         
        }

        return $dd;

    }
}

?>
