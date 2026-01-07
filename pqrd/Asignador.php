<?php

class UsuarioDesconocido extends \Exception {}

class RadicadoAsignado extends \Exception {}

class Asignador {
	
	function __construct($database)
	{
		$this->database = $database;
	}

	function validarDependenciaRadicado($radicado, $dependencia)
	{
		return $this->database->getOne('SELECT radi_depe_actu FROM radicado WHERE radi_nume_radi = ?', [$radicado]);
	}

	function obtenerUsuarioActual($radicado)
	{
		return $this->database->getOne('SELECT u.cedula FROM pqrd_asignaciones pa JOIN users u on u.id = pa.user_id WHERE pa.radicado = ?', [$radicado]);
	}

	function asignarRadicado($radicado, $usuario)
	{
		$user_id = $this->database->getOne('SELECT id FROM users WHERE cedula = ?', [$usuario]);

		if (!$user_id)
			throw new UsuarioDesconocido('El documento no existe en la base de datos de usuarios');

		$esta_asignado = $this->database->getOne('SELECT 1 FROM pqrd_asignaciones WHERE radicado = ?', [$radicado]);

		if ($esta_asignado)
			throw new RadicadoAsignado('El radicado ya tiene un usuario asignado');

		return $this->database->Execute('INSERT INTO pqrd_asignaciones (user_id, radicado, created_at, updated_at) VALUES (?,?,?,?)', [$user_id, $radicado, date('Y-m-d H:i:s'), date('Y-m-d H:i:s')]);
	}

	function borrarAsignaciones($radicado) 
	{
		return $this->database->Execute('DELETE FROM pqrd_asignaciones WHERE radicado = ?', [$radicado]);
	}
}