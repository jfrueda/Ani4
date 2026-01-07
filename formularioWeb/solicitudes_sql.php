<?php

function paises($db) {
	$sql = "select distinct * from paises order by nombre";
	return $db->conn->getAll($sql);
}

function departamentos($db) {
	$sql = "select distinct * from departamento where id_pais=170 and dpto_codi not in (0, 1) order by dpto_nomb";
	return $db->conn->getAll($sql);
}

function ciudades($db, $id_depto) {
	$sql="select distinct * from municipio where dpto_codi = ? and activa = 1 order by muni_nomb";
	return $db->conn->getAll($sql, $id_depto);
}

function ciudades_tx($db, $depto) {
	$sql="select distinct m.* from municipio m, departamento d where m.dpto_codi = d.dpto_codi and d.dpto_nomb = ? and m.activa = 1 order by muni_nomb";
	return $db->conn->getAll($sql, $depto);
}

function ciudades_tx_all($db) {
	$sql="select distinct m.*, d.dpto_nomb from municipio m, departamento d where m.dpto_codi = d.dpto_codi and m.activa = 1 order by d.dpto_nomb, m.muni_nomb";
	return $db->conn->getAll($sql);
}

function tipos_entidades($db) {
	$sql = "SELECT * FROM sgd_tipo_eps WHERE id != 10 and id != 11 ORDER BY nombre_tipo";
	return $db->conn->getAll($sql);
}

function entidades($db, $id_tipo) {
	$sql = "SELECT * FROM sgd_eps WHERE tipo_vig_sns = ? AND tipo_vig_sns != 11 AND liquidado = false ORDER BY nombre_eps";
	return $db->conn->getAll($sql, [$id_tipo]);
}

function tipos_documentos($db) {
	$sql = "SELECT * FROM tipo_doc_identificacion WHERE tdid_codi != '4' ORDER BY tdid_desc";
	return $db->conn->getAll($sql);
}

function ips($db, $dane) {
	$sql = 'SELECT * FROM sgd_eps WHERE liquidado = false AND (dane = ? OR id = 111111) ORDER BY nombre_eps';
	return $db->conn->getAll($sql, [$dane]);
}

function expediente($db, $radicado) {
	$sql = 'SELECT s.* FROM sgd_sexp_secexpedientes s WHERE UPPER(s."sgd_sexp_parexp1") = ?';
	return $db->conn->getAll($sql, strtoupper($radicado));
}

function expediente_radicado($db, $radicado) {
	$sql = 'SELECT s.* FROM sgd_exp_expediente s WHERE radi_nume_radi = ? ORDER BY id ASC LIMIT 1';
	return $db->conn->getAll($sql, strtoupper($radicado));
}

function discapacidades($db) {
	$sql = 'SELECT * FROM discapacidad ORDER BY nombre';
	return $db->conn->getAll($sql);
}

function medicamentos($db) {
	$sql = 'SELECT * FROM pqrd_medicamento WHERE estado = 1 ORDER BY medicamento';
	return $db->conn->getAll($sql);
}