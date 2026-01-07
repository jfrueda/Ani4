<?php namespace App\Lib;

class ManagementError
{

    private $management;

	public function __construct($db){
		include("../class_control/ManejoErrores.php");

        $errOrigen = basename($_SERVER['SCRIPT_NAME'] ).' | '.basename( __FILE__);
        $this->management = new \ManejoErrores($db, $errOrigen, '../..');
	}

    public function getFormatError($code){
        return $this->management->errorFormateado($code);
    }
}