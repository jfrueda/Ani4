<?php namespace App\Lib;

use Symfony\Component\Dotenv\Dotenv;

class ADOdb
{
	public $conn;
	public $rutaRaiz;
	public $digitosDependencia;
	Public $lonRadicado;
	Public $depeRadweb;


	public function __construct($params = [])
	{

		$db_driver = $_ENV['BD_DRIVER'];
		$db_database = $_ENV['BD'];
		$db_user = $_ENV['BD_USER'];
		$db_password = $_ENV['BD_PASS'];
		$servidor = $_ENV['BD_SERVER'].':'.$_ENV['BD_PORT'];

		define('ADODB_ASSOC_CASE', ADODB_ASSOC_CASE_UPPER);
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		
		$db = adoNewConnection($db_driver);
		//$db->debug = true;
		$db->connect($servidor, $db_user, $db_password, $db_database);
		
		$db->SetFetchMode(ADODB_FETCH_ASSOC);
		
		$this->rutaRaiz = "..";
		$this->conn = $db;
		//$this->conn->charSet = 'utf8';
		$this->digitosDependencia = $digitosDependencia ;
		$this->lonRadicado        =  $lonRadicado ;
		$this->depeRadweb         =  $depeRadicaFormularioWeb ;
	}
}
