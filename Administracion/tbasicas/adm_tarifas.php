<?php

/**
 * @module crearUsuario
 *
 * @author Jairo Losada   <jlosada@gmail.com>
 * @author Cesar Gonzalez <aurigadl@gmail.com>
 * @license  GNU AFFERO GENERAL PUBLIC LICENSE
 * @copyright

SIIM2 Models are the data definition of SIIM2 Information System
Copyright (C) 2013 Infometrika Ltda.

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU Affero General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Affero General Public License for more details.

You should have received a copy of the GNU Affero General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
session_start();
$ruta_raiz = "../..";
if (!$_SESSION['dependencia'])
	header("Location: $ruta_raiz/cerrar_session.php");

# Variables de la session de Orfeo
$krd         = $_SESSION["krd"];
$dependencia = $_SESSION["dependencia"];
$usua_doc    = $_SESSION["usua_doc"];
$codusuario  = $_SESSION["codusuario"];
$tip3Nombre  = $_SESSION["tip3Nombre"];
$tip3desc    = $_SESSION["tip3desc"];
$tip3img     = $_SESSION["tip3img"];

include_once("$ruta_raiz/include/db/ConnectionHandler.php");
require_once("$ruta_raiz/class_control/Mensaje.php");

$db = new ConnectionHandler($ruta_raiz);

foreach ($_GET as $key => $valor)   ${$key} = $valor;
foreach ($_POST as $key => $valor)   ${$key} = $valor;

function valueToJsValue($value, $encoding = false)
{
	if (!is_numeric($value)) {
		$value = str_replace('\\', '\\\\', $value);
		$value = str_replace('"', '\"', $value);
		$value = '"' . $value . '"';
	}
	if ($encoding) {
		switch ($encoding) {
			case 'utf8':
				return iconv("ISO-8859-2", "UTF-8", $value);
				break;
		}
	} else {
		return $value;
	}
}

function arrayToJsArray($array, $name, $nl = "\n", $encoding = false)
{
	if (is_array($array)) {
		$jsArray = $name . ' = new Array();' . $nl;
		foreach ($array as $key => $value) {
			switch (gettype($value)) {
				case 'unknown type':
				case 'resource':
				case 'object':
					break;
				case 'array':
					$jsArray .= arrayToJsArray($value, $name . '[' . valueToJsValue($key, $encoding) . ']', $nl);
					break;
				case 'NULL':
					$jsArray .= $name . '[' . valueToJsValue($key, $encoding) . '] = null;' . $nl;
					break;
				case 'boolean':
					$jsArray .= $name . '[' . valueToJsValue($key, $encoding) . '] = ' . ($value ? 'true' : 'false') . ';' . $nl;
					break;
				case 'string':
					$jsArray .= $name . '[' . valueToJsValue($key, $encoding) . '] = ' . valueToJsValue($value, $encoding) . ';' . $nl;
					break;
				case 'double':
				case 'integer':
					$jsArray .= $name . '[' . valueToJsValue($key, $encoding) . '] = ' . $value . ';' . $nl;
					break;
				default:
					trigger_error('Hoppa, ERRROR Ãºj tÃ­pus a PHP-ben?' . __CLASS__ . '::' . __FUNCTION__ . '()!', E_USER_WARNING);
			}
		}
		return $jsArray;
	} else {
		return false;
	}
}

if ($db) {
	if (isset($_POST['btn_accion'])) {
		$record = array();
		$record['SGD_FENV_CODIGO'] = $_POST['id_fenv'];		//Forma de envio.
		$record['SGD_TAR_CODIGO'] =  $_POST['txt_idTar'];	//Codigo de la tarifa.
		$record['SGD_CLTA_CODSER'] = $_POST['slc_TipoTar'];	//Tipo de tarifa.
		$record['SGD_CLTA_DESCRIP'] = $_POST['txt_desc'];	//Descripcion de tarifa
		$record['SGD_CLTA_PESDES'] = $_POST['txt_lim1'];	//Limite inferior peso
		$record['SGD_CLTA_PESHAST'] = $_POST['txt_lim2'];	//Limite superior peso

		switch ($_POST['btn_accion']) {

			case 'Agregar':
			case 'Modificar': {
					$db->conn->BeginTrans();
					$ok = $db->conn->Replace('SGD_CLTA_CLSTARIF', $record, array('SGD_FENV_CODIGO', 'SGD_TAR_CODIGO', 'SGD_CLTA_CODSER'), $autoquote = true);
					if ($ok) {
						$record = array_slice($record, 0, 3, true);
						if ($_POST['slc_TipoTar'] == 1) {
							$record['SGD_TAR_VALENV1'] = $_POST['txt_v1'];		//valor envio (local/grupo1)
							$record['SGD_TAR_VALENV2'] = $_POST['txt_v2'];		//valor envio (nacional/grupo2)
							$record['SGD_TAR_VALENV1G1'] = 0;				//valor envio (grupo1)
							$record['SGD_TAR_VALENV2G2'] = 0;				//valor envio (grupo2)
						} else {
							$record['SGD_TAR_VALENV1'] = 0;					//valor envio (local/grupo1)
							$record['SGD_TAR_VALENV2'] = 0;					//valor envio (nacional/grupo2)
							$record['SGD_TAR_VALENV1G1'] = $_POST['txt_v1'];	//valor envio (grupo1)
							$record['SGD_TAR_VALENV2G2'] = $_POST['txt_v2'];	//valor envio (grupo2)
						}
						$ok = $db->conn->Replace('SGD_TAR_TARIFAS', $record, array('SGD_FENV_CODIGO', 'SGD_TAR_CODIGO', 'SGD_CLTA_CODSER'), $autoquote = true);
					}
					if ($ok) {
						$db->conn->CommitTrans();
						$error = $ok;
					} else {
						$db->conn->RollbackTrans();
						$error = 3;
					}
				}
				break;
			case 'Eliminar': {
					$record = array_slice($record, 0, 3);
					$db->conn->BeginTrans();
					$ADODB_COUNTRECS = true;
					$query = "SELECT sgd_renv_codigo from sgd_renv_regenvio where sgd_fenv_codigo = (";
					$query .= 'SELECT SGD_FENV_CODIGO FROM SGD_CLTA_CLSTARIF WHERE SGD_FENV_CODIGO=' . $record['SGD_FENV_CODIGO'] . ' AND SGD_TAR_CODIGO=' . $record['SGD_TAR_CODIGO'] . ' AND SGD_CLTA_CODSER=' . $record['SGD_CLTA_CODSER'] . ')';
					$rs = $db->conn->Execute($query);
					$ADODB_COUNTRECS = false;
					if ($rs->RecordCount() <= 0) {
						$ok1 = $db->conn->Execute('DELETE FROM SGD_TAR_TARIFAS WHERE SGD_FENV_CODIGO=' . $record['SGD_FENV_CODIGO'] . ' AND SGD_TAR_CODIGO=' . $record['SGD_TAR_CODIGO'] . ' AND SGD_CLTA_CODSER=' . $record['SGD_CLTA_CODSER']);
						$ok2 = $db->conn->Execute('DELETE FROM SGD_CLTA_CLSTARIF WHERE SGD_FENV_CODIGO=' . $record['SGD_FENV_CODIGO'] . ' AND SGD_TAR_CODIGO=' . $record['SGD_TAR_CODIGO'] . ' AND SGD_CLTA_CODSER=' . $record['SGD_CLTA_CODSER']);
						if ($ok1 && $ok2) {
							$db->conn->CommitTrans();
							$error = 4;
						} else {
							$db->conn->RollbackTrans();
							$error = 3;
						}
					} else {
						$db->conn->RollbackTrans();
						$error = 5;
					}
				}
				break;
			default:
				break;
		}
		unset($record);
	}

	$sql_fenv = "SELECT SGD_FENV_DESCRIP, SGD_FENV_CODIGO FROM SGD_FENV_FRMENVIO WHERE SGD_FENV_ESTADO=1 ORDER BY SGD_FENV_DESCRIP";
	if (!($Rs_fenv)) {
		$error = 6;
		$nomTabla = "Formas de Envio";
	}


	if ($_POST['id_fenv'] and $_POST['slc_TipoTar']) {
		if ($_POST['slc_TipoTar'] == '1') {
			$sql_val = " SGD_TAR_TARIFAS.SGD_TAR_VALENV1 AS VAL1, SGD_TAR_TARIFAS.SGD_TAR_VALENV2 AS VAL2 ";
		} else {
			$sql_val = " SGD_TAR_TARIFAS.SGD_TAR_VALENV1G1 AS VAL1, SGD_TAR_TARIFAS.SGD_TAR_VALENV2G2 AS VAL2 ";
		}

		$sql_clta = "SELECT SGD_CLTA_CLSTARIF.SGD_CLTA_DESCRIP AS DESCCONSTAR, SGD_CLTA_CLSTARIF.SGD_TAR_CODIGO AS IDCONSTAR, " .
			"SGD_CLTA_CLSTARIF.SGD_CLTA_PESDES AS LIMPESOINF, SGD_CLTA_CLSTARIF.SGD_CLTA_PESHAST AS LIMPESOSUP, " .
			$sql_val . "FROM  SGD_CLTA_CLSTARIF, SGD_TAR_TARIFAS " .
			"WHERE SGD_CLTA_CLSTARIF.SGD_FENV_CODIGO = SGD_TAR_TARIFAS.SGD_FENV_CODIGO AND " .
			"SGD_CLTA_CLSTARIF.SGD_TAR_CODIGO = SGD_TAR_TARIFAS.SGD_TAR_CODIGO AND " .
			"SGD_CLTA_CLSTARIF.SGD_CLTA_CODSER = SGD_TAR_TARIFAS.SGD_CLTA_CODSER AND " .
			"SGD_CLTA_CLSTARIF.SGD_FENV_CODIGO = " . $_POST['id_fenv'] . " AND SGD_CLTA_CLSTARIF.SGD_CLTA_CODSER = " . $_POST['slc_TipoTar'] .
			"ORDER BY SGD_CLTA_CLSTARIF.SGD_CLTA_DESCRIP, SGD_CLTA_CLSTARIF.SGD_FENV_CODIGO";
		$Rs_clta = $db->conn->Execute($sql_clta);
		if ($Rs_clta) {
			$it = 1;
			$vcltav = array();
			while (!$Rs_clta->EOF) {
				$vcltav[$it]['IdConsTar'] = $Rs_clta->fields['IDCONSTAR'];
				$vcltav[$it]['DescConsTar'] = $Rs_clta->fields['DESCCONSTAR'];
				$vcltav[$it]['LimPesoInf'] = $Rs_clta->fields['LIMPESOINF'];
				$vcltav[$it]['LimPesoSup'] = $Rs_clta->fields['LIMPESOSUP'];
				$vcltav[$it]['Val1'] = $Rs_clta->fields['VAL1'];
				$vcltav[$it]['Val2'] = $Rs_clta->fields['VAL2'];
				$it += 1;
				$Rs_clta->MoveNext();
			}
			$Rs_clta = $db->conn->Execute($sql_clta);
		} else {
			$error = 3;
			$nomTabla = "Clasificacin de tarifas";
		}
	}
} else {
	$error = 6;
}

if ($error) {
	$msg_error = "<tr bordercolor='#FFFFFF'>
					<td width='3%' align='center' class='titulosError' colspan='5' bgcolor='#FFFFFF'>";
	// Implementado por si desean mostrar errores o mensajes personalizados.
	switch ($error) {
		case 1:
			$msg_error .= "Informaci&oacute;n actualizada!!";
			break;				//ACUTALIZACION REALIZADA
		case 2:
			$msg_error .= "Tarifa creada satisfactoriamente!!";
			break;			//INSERCION REALIZADA
		case 3:
			$msg_error .= "Error al gestionar datos, comun&iacute;quese con el Administrador de sistema !!";
			break;	//ERROR EJECUCCION SQL
		case 4:
			$msg_error .= "<blink>Tarifa eliminada exitosamente</blink>";
			break;	// EXITO EN LA ELIMINACION
		case 5:
			$msg_error .= "Error. No se puede eliminar registro. Existe hist&oacute;rico.";
			break;	// ERROR EN LA ELIMINACION
		case 6:
			$msg_error .= "   ";
			break;	// EL USUARIO AUN NO HA SELECCIONADO UNA ACCION
	}
	$msg_error .= "</td></tr>";
}
?>

<html>

<head>
	<title>Orfeo - Admon de Tarifas.</title>
	<?php include_once "$ruta_raiz/htmlheader.inc.php"; ?>
	<script language="JavaScript">
		<!--
		function Actualiza() {
			var Obj = document.getElementById('id_clta');
			var i = Obj.selectedIndex;
			if (i > 0) {
				document.getElementById('txt_idTar').value = vp[i]['IdConsTar'];
				document.getElementById('txt_desc').value = vp[i]['DescConsTar'];
				document.getElementById('txt_lim1').value = vp[i]['LimPesoInf'];
				document.getElementById('txt_lim2').value = vp[i]['LimPesoSup'];
				document.getElementById('txt_v1').value = vp[i]['Val1'];
				document.getElementById('txt_v2').value = vp[i]['Val2'];
			} else {
				document.getElementById('txt_idTar').value = '';
				document.getElementById('txt_desc').value = '';
				document.getElementById('txt_lim1').value = '';
				document.getElementById('txt_lim2').value = '';
				document.getElementById('txt_v1').value = '';
				document.getElementById('txt_v2').value = '';
			}
		}

		function rightTrim(sString) {
			while (sString.substring(sString.length - 1, sString.length) == ' ') {
				sString = sString.substring(0, sString.length - 1);
			}
			return sString;
		}

		function addOpt(oCntrl, iPos, sTxt, sVal) {
			var selOpcion = new Option(sTxt, sVal);
			eval(oCntrl.options[iPos] = selOpcion);
		}

		function cambia(oCntrl) {
			while (oCntrl.length) {
				oCntrl.remove(0);
			}
			$indice = 0;
			addOpt(oCntrl, $indice, "<< Seleccione Tarifa >>", $indice);
			for ($x = 0; $x < vp.length; $x++) {
				if (vp[$x]["IdConsTar"] == document.form1.id_fenv.options[document.form1.id_fenv.selectedIndex].value) {
					$indice += 1;
					addOpt(oCntrl, $indice, vp[$x]["DescConsTar"], vp[$x]["id_pais"]);
				}
			}
		}

		function validarinfo(form) {
			for (i = 0; i < form.length; i++) {
				switch (form.elements[i].type) {
					case 'text':
					case 'textarea':
					case 'select-multiple': {
						if (rightTrim(form.elements[i].value) == '') {
							alert("Por favor complete todos los campos del registro");
							form.elements[i].focus();
							return false;
						}
						if ((form.elements[i].name != 'txt_desc') && ((parseInt(form.elements[i].value) < 0) || isNaN(parseInt(form.elements[i].value)) || (parseInt(form.elements[i].value) > 9999999))) {
							alert("Digite cantidad numerica");
							form.elements[i].focus();
							return false;
						}
					}
					break;
					case 'checkbox': {
						alert(form.elements[i].checked);
					}
					break;
					case 'select-one': {
						if ((form.elements[i].name != 'id_clta') && (form.elements[i].value == '0')) {
							alert("Por favor complete todos los campos del registro");
							form.elements[i].focus();
							return false;
						}
					}
					break;
				}
			}
			form.submit();
		}

		function val_borrar(valor) {
			if (valor == null || valor == 0) {
				alert("Por favor seleccione Tarifa.");
				return false;
			}
		}

		function ver_listado(que) {
			window.open('listados.php?<?= session_name() . "=" . session_id() ?>&var=tar', '', 'scrollbars=yes,menubar=no,height=600,width=800,resizable=yes,toolbar=no,location=no,status=no');
		}

		function anula_todo() {
			document.form1.slc_TipoTar.value = 0;
			document.form1.id_clta.value = 0;
			Actualiza();
		}

		function anula_datos() {
			document.form1.txt_idTar.value = "";
			document.form1.txt_lim1.value = "";
			document.form1.txt_lim2.value = "";
			document.form1.txt_desc.value = "";
			document.form1.txt_v1.value = "";
			document.form1.txt_v2.value = "";
		}

		<? echo arrayToJsArray($vcltav, 'vp'); ?>
		//
		-->
	</script>
</head>

<body>
	<form name="form1" id="form1" method="post" action="<?= $_SERVER['PHP_SELF'] ?>">
		<input type='hidden' name='<?= session_name() ?>' value='<?= session_id() ?>'>
		<input type="hidden" id="hdBandera" name="hdBandera" value="">

		<div class="col-12">
			<section id="widget-grid">
				<div class="row">
					<article class="col-12">
						<div class="card shadow-sm">
							<div class="card-header bg-orfeo text-white">
								<h5 class="mb-0">
									Administración de tarifas
								</h5>
							</div>

							<div class="card-body p-0">
								<table class="table table-bordered table-striped table-sm align-middle mb-0">
									<tr>
										<td class="text-center fw-bold">1</td>
										<td class="fw-semibold">Forma del envío</td>
										<td colspan="4">
											<?php
											$sql_fenv = $db->conn->Execute($sql_fenv);
											echo $sql_fenv->GetMenu2(
												'id_fenv',
												$_POST['id_fenv'],
												"0:&lt;&lt; SELECCIONE &gt;&gt;",
												false,
												0,
												"id='id_fenv' class='form-select form-select-sm' Onchange='anula_todo();'"
											);
											?>
										</td>
									</tr>
									<tr>
										<td class="text-center fw-bold">2</td>
										<td class="fw-semibold">Localización del Envío</td>
										<td colspan="4">
											<select name="slc_TipoTar"
												class="form-select form-select-sm"
												id="slc_TipoTar"
												onChange="if (id_fenv.value == 0) {alert ('Seleccione Forma de Envio'); slc_TipoTar.value=0;} else this.form.submit()">
												<option value="0">&lt;&lt; seleccione &gt;&gt;</option>
												<option value="1" <? ($_POST['slc_TipoTar'] == '1') ? print "selected" : print "" ?>>Nacional</option>
												<option value="2" <? ($_POST['slc_TipoTar'] == '2') ? print "selected" : print "" ?>>Internacional</option>
											</select>
										</td>
									</tr>
									<tr>
										<td class="text-center fw-bold">3</td>
										<td class="fw-semibold">Seleccione Tarifa</td>
										<td colspan="4">
											<?php
											$Rs_clta = $db->conn->Execute($sql_clta);
											if ($_POST['slc_TipoTar'] > 0) {
												echo $Rs_clta->GetMenu2(
													'id_clta',
													false,
													"0:&lt;&lt; SELECCIONE &gt;&gt;",
													false,
													0,
													"id='id_clta' onchange='Actualiza()' class='form-select form-select-sm'"
												);
												$Rs_clta->Close();
											} else {
												echo "<select name='id_clta' id='id_clta' class='form-select form-select-sm'></select>";
											}
											?>
										</td>
									</tr>
									<tr>
										<td rowspan="5" class="text-center fw-bold">4</td>
										<td class="fw-semibold">Código</td>
										<td colspan="4" class="text-center">
											<input name="txt_idTar" id="txt_idTar" type="text"
												class="form-control form-control-sm text-center d-inline-block"
												style="max-width:120px;">
										</td>
									</tr>
									<tr>
										<td rowspan="2" class="fw-semibold">Peso</td>
										<td class="text-center fw-semibold">Límite Inferior</td>
										<td class="text-center fw-semibold">Límite Superior</td>
										<td colspan="2" class="text-center fw-semibold">Descripción</td>
									</tr>
									<tr>
										<td class="text-center">
											<input name="txt_lim1" id="txt_lim1" type="text"
												class="form-control form-control-sm text-center">
										</td>
										<td class="text-center">
											<input name="txt_lim2" id="txt_lim2" type="text"
												class="form-control form-control-sm text-center">
										</td>
										<td colspan="2">
											<input name="txt_desc" id="txt_desc" type="text"
												class="form-control form-control-sm">
										</td>
									</tr>
									<tr>
										<td rowspan="2" class="fw-semibold">Valor Envío</td>
										<td colspan="2" class="text-center fw-semibold">Local / Grupo 1 *</td>
										<td colspan="2" class="text-center fw-semibold">Nacional / Grupo 2 *</td>
									</tr>
									<tr>
										<td colspan="2">
											<input name="txt_v1" id="txt_v1" type="text"
												class="form-control form-control-sm text-center">
										</td>
										<td colspan="2">
											<input name="txt_v2" id="txt_v2" type="text"
												class="form-control form-control-sm text-center">
										</td>
									</tr>
									<tr>
										<td colspan="6" class="bg-light small text-muted">
											<strong>NOTA:</strong>
											El valor del Envío es relacional al punto 2, Si éste es a nivel Nacional entonces los valores serán
											Local y Nacional; sino (internacional) Grupo 1 y Grupo 2 se refiere al valor en caso que el país destino se encuentre o no
											en América respectivamente.
										</td>
									</tr>
									<?= $msg_error ?>
								</table>

								<table class="table table-borderless mt-3">
									<tr>
										<td class="text-center">
											<input name="btn_accion" type="button"
												class="btn btn-outline-secondary btn-sm"
												value="Listado"
												onClick="ver_listado('tarifas');">
										</td>
										<td class="text-center">
											<input name="btn_accion" type="submit"
												class="btn btn-success btn-sm"
												value="Agregar"
												onClick="document.form1.hdBandera.value='A'; return validarinfo(this.form);">
										</td>
										<td class="text-center">
											<input name="btn_accion" type="submit"
												class="btn btn-warning btn-sm"
												value="Modificar"
												onClick="document.form1.hdBandera.value='M'; return validarinfo(this.form);">
										</td>
										<td class="text-center">
											<input name="btn_accion" type="submit"
												class="btn btn-danger btn-sm"
												value="Eliminar"
												onClick="document.form1.hdBandera.value='E'; return val_borrar(document.form1.txt_idTar.value);">
										</td>
									</tr>
								</table>
							</div>
						</div>
					</article>
				</div>
			</section>
		</div>
	</form>
</body>

</html>