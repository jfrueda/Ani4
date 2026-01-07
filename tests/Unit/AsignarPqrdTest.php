<?php

$ruta_raiz = ".";
include_once "$ruta_raiz/pqrd/Asignador.php";
include_once "$ruta_raiz/include/db/ConnectionHandler.php";
$db = new ConnectionHandler($ruta_raiz);
$usuario = null;

beforeAll(function() use ($db, &$usuario) {
	$asignador = new Asignador($db->conn);
	$usuario = $asignador->obtenerUsuarioActual('20212100000916742');
	echo $usuario;
});

beforeEach(function() use ($db) {
	$this->db = $db->conn;
});

test('validar objeto db', function() {
	expect(get_class($this->db))->toEqual('ADODB_postgres9');
});

test('dependencia radicado', function () {
	$asignador = new Asignador($this->db);

    expect($asignador->validarDependenciaRadicado('20212100000916742', '999'))->toBe('999');
});

test('dependencia radicado invalido', function () {
	$asignador = new Asignador($this->db);

    expect($asignador->validarDependenciaRadicado('20212100300916744', '999'))->toBeFalsy();
});

test('usuario actual radicado', function() {
	$asignador = new Asignador($this->db);

	expect($asignador->obtenerUsuarioActual('20212100000916742'))->toBe('123654');
});

test('asignar a radicado con usuario previo', function() {
	$asignador = new Asignador($this->db);

	expect(function() use ($asignador) { 
			$asignador->asignarRadicado('20212100000916742', '52456053');
		})->toThrow(RadicadoAsignado::class, 'El radicado ya tiene un usuario asignado');
});


test('borrar asignaciones radicado', function() {
	$asignador = new Asignador($this->db);

	expect($asignador->borrarAsignaciones('20212100000916742'))->toBeTruthy();
});

test('asignacion radicado usuario invalido', function() {
	$asignador = new Asignador($this->db);
	
	expect(function() use ($asignador) { 
			$asignador->asignarRadicado('20212100000916742', '1000010000');
		})->toThrow(UsuarioDesconocido::class, 'El documento no existe en la base de datos de usuarios');
});

test('asignacion radicado usuario valido', function() {
	$asignador = new Asignador($this->db);
	$asignador->asignarRadicado('20212100000916742', '52456053');

	expect($asignador->obtenerUsuarioActual('20212100000916742'))->toBe('52456053');
});

afterAll(function() use($db, &$usuario) {
	$asignador = new Asignador($db->conn);

	$asignador->borrarAsignaciones('20212100000916742');
	$asignador->asignarRadicado('20212100000916742', $usuario);
});
