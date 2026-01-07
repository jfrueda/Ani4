<?
//muestra nombre mes
function nombremes($mes)
{
	switch($mes)
	{
		case "01":
			return "enero";
		case "02":
			return "febrero";
		case "03":
			return "marzo";
		case "04":
			return "abril";
		case "05":
			return "mayo";
		case "06":
			return "junio";
		case "07":
			return "julio";
		case "08":
			return "agosto";
		case "09":
			return "septiembre";
		case "10":
			return "octubre";
		case "11":
			return "noviembre";
		case "12":
			return "diciembre";
	}
}
//muestra nombre dia
function nombredia($dia)
{
	switch($dia)
		{
		case 1:
			return "Lunes";
		case 2:
			return "Martes";
		case 3:
			return "Miercoles";
		case 4:
			return "Jueves";
		case 5:
			return "Viernes";
		case 6:
			return "Sabado";
		case 7:
			return "Domingo";
		}
}
//inserta en cualquier tabla, los parametros campos y valores son arreglos
function inserta($tabla,$campos,$valores,$db)
{
	$tcampos=count($campos);
	$tvalores=count($valores);
	$sql="insert into ".$tabla;
	$sql.="(";
	$k=1;
	foreach($campos as $valorc)
		{
			$sql.=$valorc;
			if($k < $tcampos)
				$sql.=",";
			$k++;
		}
	$sql.=")values(";
	$k=1;
	foreach($valores as $valorv)
		{
			if(gettype($valorv)=="string")
				$sql.="'".$valorv."'";
			else	
				$sql.=$valorv;
			if($k < $tvalores)
				$sql.=",";
			$k++;
		}
	$sql.=")";
$db->conn->Execute($sql);
//echo $sql;
echo $db->ErrorMsg();
}
//\ el ultimo consecutivo de cualquier tabla
function consecutivo($tabla,$primaria,$db)
{
$sql="select MAX(".$primaria.") as cont from ".$tabla;
$rs=$db->conn->Execute($sql);
$ultimo=$rs->fields['cont'];
return $ultimo;
}
//borra un registro
function borra($tabla,$campo,$valor,$db)
{
$sql_del="delete from ".$tabla." where ".$campo."=".$valor;
$rs_del=$db->conn->Execute($sql_del);
}
//reemplaza tildes
function texto_ajax($texto)
{
	if($texto)
	{
		$texto=replace($texto,"á","&aacute;");
		$texto=replace($texto,"Á","&Aacute;");
		$texto=replace($texto,"é","&eacute;");
		$texto=replace($texto,"É","&Eacute;");
		$texto=replace($texto,"í","&iacute;");
		$texto=replace($texto,"Í","&Iacute;");
		$texto=replace($texto,"ó","&oacute;");
		$texto=replace($texto,"Ó","&Oacute;");
		$texto=replace($texto,"ú","&uacute;");
		$texto=replace($texto,"Ú","&Uacute;");
		$texto=replace($texto,"ñ","&ntilde;");
		$texto=replace($texto,"Ñ","&Ntilde;");
		$texto=replace($texto,"�","&iquest;");
		$texto=replace($texto,"?","&#63;");
		
		
//		$texto=replace($texto,'"',"&quot;");
//		$texto=replace($texto,"\\","");
//		$texto=replace($texto,'"',"&quot;");
//		$texto=replace($texto,"\"","&");
	}
	return $texto;
}
function texto_ajax2($texto)
{
	if($texto)
	{
		$texto=replace($texto,"&aacute;","�");
		$texto=replace($texto,"&Aacute;","�");
		$texto=replace($texto,"&eacute;","�");
		$texto=replace($texto,"&Eacute;","�");
		$texto=replace($texto,"&iacute;","�");
		$texto=replace($texto,"&Iacute;","�");
		$texto=replace($texto,"&oacute;","�");
		$texto=replace($texto,"&Oacute;","�");
		$texto=replace($texto,"&uacute;","�");
		$texto=replace($texto,"&Uacute;","�");
		$texto=replace($texto,"&ntilde;","�");
		$texto=replace($texto,"&Ntilde;","�");
//		$texto=replace($texto,'"',"&quot;");
//		$texto=replace($texto,"\"","&");
	}
	return $texto;
}

function replace($original,$nuevo,$otro)
{
	return str_replace($nuevo,$otro,$original);
}
//funcion para validar direcciones de correo electronico
function check_email_address($email) 
{
	// Primero, chequeamos que solo haya un simbolo @, y que los largos sean correctos
  if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $email)) 
	{
		// correo invalido por numero incorrecto de caracteres en una parte, o numero incorrecto de simbolos @
    return false;
  }
  // se divide en partes para hacerlo mas sencillo
  $email_array = explode("@", $email);
  $local_array = explode(".", $email_array[0]);
  for ($i = 0; $i < sizeof($local_array); $i++) 
	{
    if (!ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$", $local_array[$i])) 
		{
      return false;
    }
  } 
  // se revisa si el dominio es una IP. Si no, debe ser un nombre de dominio valido
	if (!ereg("^\[?[0-9\.]+\]?$", $email_array[1])) 
	{ 
     $domain_array = explode(".", $email_array[1]);
     if (sizeof($domain_array) < 2) 
		 {
        return false; // No son suficientes partes o secciones para se un dominio
     }
     for ($i = 0; $i < sizeof($domain_array); $i++) 
		 {
        if (!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$", $domain_array[$i])) 
				{
           return false;
        }
     }
  }
  return true;
}
//validador de correos
function emailValidator($email){
	$email	= trim($email);
	if(!(preg_match('/^[\w\-\.]+[@][A-z0-9]+[\w\-.]*([.][A-z]{2,6}){1}([.][A-z]{2}){0,1}$/x',$email))){
		return false;
	}
	return true;
}
// web en miniatura
function miniatura_web($url, $servicio = "browsercamp", $tamanio = "1", $calidad = "high"){
	$tamanios = array("800", "832", "1024", "1280", "1600");
	$calidades = array("png" => "1", "high" => "2", "medium" => "3", "low" => "4");
	if("ipinfo" == $servicio){
		$sevicios = 'http://ipinfo.info/netrenderer/index.php?browser=ie7&url='.$url;
		$exp_info = '!http://renderer.geotek.de/image.php\?imgid=(.+)&browser=ie7!U';
		$query = @file_get_contents($sevicios);
		preg_match_all($exp_info, $query, $info);
		$s = $info[0][0];
		return $s;
	}
	if("browsercamp" == $servicio){
		$sevicios  = "http://www.browsrcamp.com/?get=1&width=".$tamanios[$tamanio]."&url=".$url;
		$sevicios .= "&quality=".$calidades[$calidad];
		$exp_info = '!<a href="(.+)" target="_blank">!U';
		$query = @file_get_contents($sevicios);
		preg_match_all($exp_info, $query, $info);
		$s = array(
			"full" => $info[1][0],
			"thumb" => str_replace("full", "thumb", $info[1][0])
		);
		return $s;
	}
	if("thumbalizr" == $servicio){
		$s = "http://www.thumbalizr.com/api/?url=".$url."&width=".$tamanios[$tamanio];
		return $s;
	}
}
function ordena_fecha($fecha)
{
	$fechav=split("-",$fecha);
	$fechac=$fechav[2]."-".$fechav[1]."-".$fechav[0];
	return $fechac;
}

function textoPDF($texto){
	return iconv('UTF-8', 'ISO-8859-1//IGNORE', $texto);
	//return $texto;
}

function valida_fecha($db)
{
	return date('Y/m/d H:i:s');
}

function getNumPagesPdf($filepath){
    $fp = @fopen(preg_replace("/\[(.*?)\]/i", "",$filepath),"r");
    $max=0;
    while(!feof($fp)) {
            $line = fgets($fp,255);
            if (preg_match('/\/Count [0-9]+/', $line, $matches)){
                    preg_match('/[0-9]+/',$matches[0], $matches2);
                    if ($max<$matches2[0]) $max=$matches2[0];
            }
    }
    fclose($fp);
    if($max==0){
        $im = new imagick($filepath);
        $max=$im->getNumberImages();
    }

    return $max;
}

function is_empty($var, $default)
{
	return !empty($var) ? $var : $default;
}

function invalidateArray(array $array, $keys): bool
{
    foreach ($keys as $key) {
        if ($array[$key] === null || trim($array[$key]) === '' || !isset($array[$key])) {
            return true;
        }
    }

    return false;
}

function getBrowserInfo(){
    $browserInfo = array('user_agent'=>'','browser'=>'','browser_version'=>'','os_platform'=>'','pattern'=>'', 'device'=>'');

    $u_agent = $_SERVER['HTTP_USER_AGENT']; 
    $bname = 'Unknown';
    $ub = 'Unknown';
    $version = "";
    $platform = 'Unknown';

    $deviceType='Desktop';

    if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$u_agent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($u_agent,0,4))){

        $deviceType='Mobile';

    }

    if($_SERVER['HTTP_USER_AGENT'] == 'Mozilla/5.0(iPad; U; CPU iPhone OS 3_2 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Version/4.0.4 Mobile/7B314 Safari/531.21.10') {
        $deviceType='Tablet';
    }

    if(stristr($_SERVER['HTTP_USER_AGENT'], 'Mozilla/5.0(iPad;')) {
        $deviceType='Tablet';
    }

    //$detect = new Mobile_Detect();
    
    //First get the platform?
    if (preg_match('/linux/i', $u_agent)) {
        $platform = 'linux';

    } elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
        $platform = 'mac';

    } elseif (preg_match('/windows|win32/i', $u_agent)) {
        $platform = 'windows';
    }

    // Next get the name of the user agent yes seperately and for good reason
    if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)) 
    { 
        $bname = 'IE'; 
        $ub = "MSIE";

    } else if(preg_match('/Firefox/i',$u_agent))
    { 
        $bname = 'Mozilla Firefox'; 
        $ub = "Firefox"; 

    } else if(preg_match('/Chrome/i',$u_agent) && (!preg_match('/Opera/i',$u_agent) && !preg_match('/OPR/i',$u_agent))) 
    { 
        $bname = 'Chrome'; 
        $ub = "Chrome"; 

    } else if(preg_match('/Safari/i',$u_agent) && (!preg_match('/Opera/i',$u_agent) && !preg_match('/OPR/i',$u_agent))) 
    { 
        $bname = 'Safari'; 
        $ub = "Safari"; 

    } else if(preg_match('/Opera/i',$u_agent) || preg_match('/OPR/i',$u_agent)) 
    { 
        $bname = 'Opera'; 
        $ub = "Opera"; 

    } else if(preg_match('/Netscape/i',$u_agent)) 
    { 
        $bname = 'Netscape'; 
        $ub = "Netscape"; 

    } else if((isset($u_agent) && (strpos($u_agent, 'Trident') !== false || strpos($u_agent, 'MSIE') !== false)))
    {
        $bname = 'Internet Explorer'; 
        $ub = 'Internet Explorer'; 
    } 
    

    // finally get the correct version number
    $known = array('Version', $ub, 'other');
    $pattern = '#(?<browser>' . join('|', $known) . ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';

    if (!preg_match_all($pattern, $u_agent, $matches)) {
        // we have no matching number just continue
    }

    // see how many we have
    $i = count($matches['browser']);
    if ($i != 1) {
        //we will have two since we are not using 'other' argument yet
        //see if version is before or after the name
        if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
            $version= $matches['version'][0];

        } else {
            $version= @$matches['version'][1];
        }

    } else {
        $version= $matches['version'][0];
    }

    // check if we have a number
    if ($version==null || $version=="") {$version="?";}

    return array(
        'user_agent' => $u_agent,
        'browser'      => $bname,
        'browser_version'   => $version,
        'os_platform'  => $platform,
        'pattern'   => $pattern,
        'device'    => $deviceType
    );
}

if (!function_exists('dd')) {
    function dd()
    {
        echo '<pre>';
        array_map(function($x) {var_dump($x);}, func_get_args());
        die;
    }
}
?>
