/*	Funci�n rightTrim.
*	Hace las veces del comando RTRIM de otros lenguajes.
*	Se envia la cadena y la retorna sin espacios a la derecha.
*/
function rightTrim(sString) {
	while (sString.substring(sString.length - 1, sString.length) == ' ') { sString = sString.substring(0, sString.length - 1); }
	return sString;
}

function addOpt(oCntrl, iPos, sTxt, sVal) {
	var selOpcion = new Option(rightTrim(sTxt), sVal);
	eval(oCntrl.options[iPos] = selOpcion);
}

/**	Funci�n Cambia.
*	Llena un objeto-Combo (id2mod) con los valores de un vector teniendo como enlace el id del "padre" (idpadre).
*	Por ejemplo, Se cambia el continente, se llena el combo paises enviando el nombre del combo paises y su combo padre.
*/
//function cambia(forma, id2mod, idpadre)
function cambia(parametros) {
	var forma = cambia.arguments[0];
	var id2mod = cambia.arguments[1];
	var idpadre = cambia.arguments[2];

	var oCntrl = document.getElementById(id2mod);

	// 1. Validar que el control existe antes de acceder
	if (oCntrl && oCntrl.type === 'select-one') {
		if (forma && forma.elements) {
			for (var i = 0; i < forma.elements.length; i++) {	// Realizamos procesos y creamos variables respecto al combo a modificar.
				// if (forma.elements[i].name == id2mod) {	// Capturamos el objeto a modificar y su posicion en el formulario.
				// 	oCntrl = forma.elements[i];
				// 	pos = i;
				// 	prefijo = (oCntrl.name).substring(0, (oCntrl.name).length - 1);
				// 	if (prefijo == 'muni_us') {	// En caso de ser el combo municipio a modificar, capturamos la opcion del combo pais como referencia
				// 		// adicional. Se asume que el objeto pais se encuentra a 2 posiciones del combo municipios.
				// 		$idpas = forma.elements[i - 2].options[forma.elements[i - 2].selectedIndex].value;
				// 		break;
				// 	}
				// }

				var elementoActual = forma.elements[i];

				// 2. Validar existencia del elemento en la iteración
				if (elementoActual && elementoActual.name === id2mod) {
					oCntrl = elementoActual;
					pos = i;

					// Usamos validación de string para el nombre
					var nombreControl = oCntrl.name || "";
					prefijo = nombreControl.substring(0, nombreControl.length - 1);

					if (prefijo === 'muni_us') {
						// 3. VALIDACIÓN : i - 2
						// Verificamos que el índice sea mayor o igual a 0
						// Verificamos que el elemento en i-2 exista y tenga opciones
						var elementoPais = (i >= 2) ? forma.elements[i - 2] : null;

						if (elementoPais && elementoPais.options && elementoPais.selectedIndex >= 0) {
							// Capturamos el valor de forma segura
							$idpas = elementoPais.options[elementoPais.selectedIndex].value;
						} else {
							console.warn("No se pudo acceder al combo país en la posición " + (i - 2));
							$idpas = null;
						}
						break;
					}
				}

				// Creamos variable respecto al combo padre.
				// if (forma?.elements[i]?.name == idpadre) {	// Capturamos la opci�n del combo seleccionada en el combo "padre".
				// 	var opc_padre = forma?.elements[i]?.options[forma?.elements[i]?.selectedIndex]?.value;
				// }

				// Validamos que 'forma' y 'forma.elements' existan antes de entrar al ciclo o lógica
				if (forma && forma.elements) {
					const elementoActual = forma.elements[i];
					if (elementoActual && elementoActual.name === idpadre) {
						// Validamos la existencia de options y que el selectedIndex sea válido (mayor a -1)
						const tieneOpciones = elementoActual.options && elementoActual.options.length > 0;
						const indiceValido = elementoActual.selectedIndex >= 0;

						if (tieneOpciones && indiceValido) {
							// Ahora es 100% seguro acceder al value
							var opc_padre = elementoActual.options[elementoActual.selectedIndex].value;

							console.log("Opción padre capturada:", opc_padre);
						} else {
							console.warn("El combo padre existe pero no tiene opciones o selección válida.");
							var opc_padre = null; // O un valor por defecto
						}
					}
				}
			}
		}
		Vector = new Array();
		switch (prefijo) {
			case 'idpais':
				Vector = vp;
				borra_combo(forma, pos);
				borra_combo(forma, pos + 1);
				borra_combo(forma, pos + 2);
				break;
			case 'codep_us':
				Vector = vd;
				borra_combo(forma, pos);
				borra_combo(forma, pos + 1);
				break;
			case 'muni_us':
				Vector = vm;
				borra_combo(forma, pos);
				break;
		}
		$indice = 0;
		addOpt(oCntrl, $indice, "<< Seleccione >>", $indice);
		for ($x = 0; $x < Vector.length; $x++) {
			switch (prefijo) {
				case 'idpais':
				case 'codep_us':
					if (Vector[$x]['ID0'] == opc_padre) {
						$indice += 1;
						addOpt(oCntrl, $indice, Vector[$x]['NOMBRE'], Vector[$x]['ID1']);
					}
					break;
				case 'muni_us':
					if ((Vector[$x]['ID'] == $idpas) && (Vector[$x]['ID'] + "-" + Vector[$x]['ID0'] == opc_padre))	// Es del pais Y es del dpto?
					{
						$indice += 1;
						addOpt(oCntrl, $indice, Vector[$x]['NOMBRE'], Vector[$x]['ID1']);
					}
					break;
			}
		}
	}
}

/*
*	Funci�n que elimina todo el contenido de un combo (select). Se le envia el formulario y la posicion del objeto.
*/
function borra_combo(nomforma, num_obj) {
	Cntrl = nomforma.elements[num_obj * 1];
	while (Cntrl.length) { Cntrl.remove(0); }
}


/*
*	Esta funcion sirve para visualizar todos los objetos de una forma y su correspondiente ordinal.
*/
function ver_objetos(forma) {
	var S = forma.name + " tiene " + forma.length + " elementos y son: \n";
	for (var i = 0; i < forma.elements.length; i++) {
		var e = forma.elements[i];
		S = S + i + "." + e.name + "  ";
		if (i % 3 == 0) S = S + "\n";
	}
	alert(S);
}
