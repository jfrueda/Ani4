const data = {
	dep:document.getElementById('dependencia'),
	usua:document.getElementById('usuario'),
	dListUsr:document.getElementById('dataListUsua'),
	send:document.getElementById('btnSend'),
	dataRes:document.getElementById('dataRes'),
	usuaPazSalvo:document.getElementById('usuaPazSalvo'),
};

// Helper function to normalize spaces
function normalizeSpaces(str) {
    return str.replace(/\s+/g, ' ').trim();
}

data.dep.addEventListener('change',async(e)=>{
	
	let depSel = data.dep.value.split('-');

	try
	{
		let res = await fetch(`req_resp.php`,{
				method:'POST',
				mode:'cors',
				body:JSON.stringify({
					condicion:'usuarios',
					dep:depSel[0],
				}),
				headers:{
					'Content-type':'application/json'
				}
			}),
			json = await res.json();
			//console.log(json);

			if(json.resp == '')
			{
				alert('No existen usuarios en la dependencia seleccionada');
				data.dep.value = '';
				data.dep.focus();
				data.usua.value = '';
				data.send.setAttribute('hidden','');
			}
			else
			{
				data.usua.removeAttribute('disabled');
				// Update the autocompletar generation
				json.resp.forEach((el) => {
				    let dataUsr = el.split('-');
				    let normalizedName = normalizeSpaces(dataUsr[2]);
				    data.dListUsr.innerHTML += `<option data-id="${dataUsr[0]}-${dataUsr[1]}-${normalizedName}-${depSel[0]}">${normalizedName}</option>`;
				});
			}
	}
	catch(err)
	{
		console.log(`ERROR-LOG (${err})`);
	}
})

data.usua.addEventListener('change', (ev) => {
    if (data.usua.value == '') {
        data.send.setAttribute('hidden', '');
    } else {
        data.send.removeAttribute('hidden');
    }

    let opcUsuario = document.querySelectorAll('#dataListUsua option');
    let dataUserSel = document.getElementById('magicDataUsr');

    console.log("Selected User:", data.usua.value); // Debugging
    for (let i = 0; i < opcUsuario.length; i++) {
        let optionText = normalizeSpaces(opcUsuario[i].innerText);
        let userInput = normalizeSpaces(data.usua.value);

        console.log("Normalized Option Text:", optionText); // Debugging
        console.log("Normalized User Input:", userInput); // Debugging

        // Use localeCompare for better string comparison
        if (userInput.localeCompare(optionText, undefined, { sensitivity: 'base' }) === 0) {
            dataUserSel.value = opcUsuario[i].dataset.id;
            console.log("Matched Data-ID:", dataUserSel.value); // Debugging
            break;
        }
    }
});

//MODAL
function abrirModal() {
  var modal = document.getElementById("myModal");
  modal.style.display = "block";
}

function cerrarModal() {

	var modal = document.getElementById("myModal");
	modal.style.display = "none";
	window.location.replace('./ReportePazSalvo.php');
}


function abrirModalErr() {
  var modal = document.getElementById("ModalError");
  modal.style.display = "block";
  data.usuaPazSalvo.innerHTML = `<h2>El usuario ${data.usua.value} Se encuentra a paz y salvo</h2>`;
}

function cerrarModalErr() {

	var modal = document.getElementById("ModalError");
	modal.style.display = "none";
	window.location.replace('./ReportePazSalvo.php');
}


data.send.addEventListener('click',async(ev)=>{
	let dataUserSel = document.getElementById('magicDataUsr');
	let tituloModal = document.getElementById('titulo');


	dataDocCodi = dataUserSel.value.split('-');

	console.error(dataUserSel.value.split('-'));

	tituloModal.innerHTML = `<h2>Informe Paz Y Salvo Del Usuario ${dataDocCodi[2]}</h2>`;

	const $linkDOM = document.querySelector(".overlay");
	$linkDOM.style.setProperty("display", "flex");

	try
	{
		let res = await fetch(`req_resp.php`,{
					method:'POST',
					mode:'cors',
					body:JSON.stringify({
						condicion:'getData',
						codiUsa:dataDocCodi[0],
						codiDoc:dataDocCodi[1],
						depend:dataDocCodi[3],
					}),
					headers:{
						'Content-type':'application/json'
					}
				}),
				json = await res.json();
				console.log(json);//return;

				if(json.status == true)
				{
					const newRow = document.createElement('tr');

					let borradores,informados, radicado_ent,radicado_res, radicado_sal, radicado_memo, circul_int, cir_ext, autos,
					vobo,devueltos,jefe_area,infor_mem_multi;

					informados = (json.informados == '' || json.informados == 0 || json.informados == null) 
											? informados = 'N/A'
											:	json.informados;

					infor_mem_multi = (json.mem_multInform == 0 || json.mem_multInform == '' || json.mem_multInform == null)
														? infor_mem_multi = 'N/A'
														:	json.mem_multInform;

					let expedient = (json.expedientes == '' || json.expedientes == 0) ? 'N/A': json.expedientes;

					radicado_gen  = (typeof json.general == 'undefined' || json.general == null) ? 'N/A' : json.general;
					radicado_ent  = (typeof json.entrada == 'undefined' || json.entrada == null) ? 'N/A' : json.entrada;
					radicado_memo = (typeof json.Memos == 'undefined' || json.Memos  == null ) ? 'N/A' : json.Memos;
					radicado_res  = (typeof json.Resol == 'undefined' || json.Resol  == null) ? 'N/A' : json.Resol;
					radicado_sal  = (typeof json.Salida == 'undefined'|| json.Salida == null) ? 'N/A' : json.Salida;
					circul_int  = (typeof json.circul_int == 'undefined'|| json.circul_int == null) ? 'N/A' : json.circul_int;
					cir_ext  = (typeof json.cir_ext == 'undefined'|| json.cir_ext == null) ? 'N/A' : json.cir_ext;
					autos  = (typeof json.autos == 'undefined'|| json.autos == null) ? 'N/A' : json.autos;
					vobo  = (typeof json.vobo == 'undefined'|| json.vobo == null) ? 'N/A' : json.vobo;
					devueltos  = (typeof json.devueltos == 'undefined'|| json.devueltos == null) ? 'N/A' : json.devueltos;
					jefe_area  = (typeof json.jefe_area == 'undefined'|| json.jefe_area == null) ? 'N/A' : json.jefe_area;

					let radGen = (radicado_gen == 'N/A') ? `<td>${radicado_gen}</td>`: `<td><a target="_blank" href="./VerDetalle.php?codiUsa=${dataDocCodi[0]}&codiDoc=${dataDocCodi[1]}&nombUsua=${dataDocCodi[2]}&depend=${dataDocCodi[3]}&tp_rad=19">${radicado_gen}</a></td>` ;
					let radEnt = (radicado_ent == 'N/A') ? `<td>${radicado_ent}</td>`: `<td><a target="_blank" href="./VerDetalle.php?codiUsa=${dataDocCodi[0]}&codiDoc=${dataDocCodi[1]}&nombUsua=${dataDocCodi[2]}&depend=${dataDocCodi[3]}&tp_rad=2">${radicado_ent}</a></td>` ;
					let radSal = (radicado_sal == 'N/A') ? `<td>${radicado_sal}</td>`: `<td><a target="_blank" href="./VerDetalle.php?codiUsa=${dataDocCodi[0]}&codiDoc=${dataDocCodi[1]}&nombUsua=${dataDocCodi[2]}&depend=${dataDocCodi[3]}&tp_rad=1">${radicado_sal}</a></td>` ;
					let radres = (radicado_res == 'N/A') ? `<td>${radicado_res}</td>`: `<td><a target="_blank" href="./VerDetalle.php?codiUsa=${dataDocCodi[0]}&codiDoc=${dataDocCodi[1]}&nombUsua=${dataDocCodi[2]}&depend=${dataDocCodi[3]}&tp_rad=6">${radicado_res}</a></td>` ;
					let radMem = (radicado_memo == 'N/A') ? `<td>${radicado_memo}</td>`: `<td><a target="_blank" href="./VerDetalle.php?codiUsa=${dataDocCodi[0]}&codiDoc=${dataDocCodi[1]}&nombUsua=${dataDocCodi[2]}&depend=${dataDocCodi[3]}&tp_rad=3">${radicado_memo}</a></td>` ;
					let b_cirInt = (circul_int == 'N/A') ? `<td>${circul_int}</td>`: `<td><a target="_blank" href="./VerDetalle.php?codiUsa=${dataDocCodi[0]}&codiDoc=${dataDocCodi[1]}&nombUsua=${dataDocCodi[2]}&depend=${dataDocCodi[3]}&tp_rad=8">${circul_int}</a></td>` ;
					let b_cirExt = (cir_ext == 'N/A') ? `<td>${cir_ext}</td>`: `<td><a target="_blank" href="./VerDetalle.php?codiUsa=${dataDocCodi[0]}&codiDoc=${dataDocCodi[1]}&nombUsua=${dataDocCodi[2]}&depend=${dataDocCodi[3]}&tp_rad=9">${cir_ext}</a></td>` ;
					let b_autos = (autos == 'N/A') ? `<td>${autos}</td>`: `<td><a target="_blank" href="./VerDetalle.php?codiUsa=${dataDocCodi[0]}&codiDoc=${dataDocCodi[1]}&nombUsua=${dataDocCodi[2]}&depend=${dataDocCodi[3]}&tp_rad=7">${autos}</a></td>` ;
					let b_vobo = (vobo == 'N/A') ? `<td>${vobo}</td>`: `<td><a target="_blank" href="./VerDetalle.php?codiUsa=${dataDocCodi[0]}&codiDoc=${dataDocCodi[1]}&nombUsua=${dataDocCodi[2]}&depend=${dataDocCodi[3]}&tp_rad=11">${vobo}</a></td>` ;
					let b_devueltos = (devueltos == 'N/A') ? `<td>${devueltos}</td>`: `<td><a target="_blank" href="./VerDetalle.php?codiUsa=${dataDocCodi[0]}&codiDoc=${dataDocCodi[1]}&nombUsua=${dataDocCodi[2]}&depend=${dataDocCodi[3]}&tp_rad=12">${devueltos}</a></td>` ;
					let b_jefe_area = (jefe_area == 'N/A') ? `<td>${jefe_area}</td>`: `<td><a target="_blank" href="./VerDetalle.php?codiUsa=${dataDocCodi[0]}&codiDoc=${dataDocCodi[1]}&nombUsua=${dataDocCodi[2]}&depend=${dataDocCodi[3]}&tp_rad=13">${jefe_area}</a></td>` ;
					let b_mem_multi = (infor_mem_multi == 'N/A') ? `<td>${infor_mem_multi}</td>`: `<td><a target="_blank" href="./VerDetalle.php?codiUsa=${dataDocCodi[0]}&codiDoc=${dataDocCodi[1]}&nombUsua=${dataDocCodi[2]}&depend=${dataDocCodi[3]}&tp_rad=20">${infor_mem_multi}</a></td>` ;
					let radInf = (informados == 'N/A') ? `<td>${informados}</td>`: `<td><a target="_blank" href="./VerDetalle.php?codiUsa=${dataDocCodi[0]}&codiDoc=${dataDocCodi[1]}&nombUsua=${dataDocCodi[2]}&depend=${dataDocCodi[3]}&tp_rad=4">${informados}</a></td>` ;
					let radExp = (expedient == 'N/A') ? `<td>${expedient}</td>`: `<td><a target="_blank" href="./VerDetalle.php?codiUsa=${dataDocCodi[0]}&codiDoc=${dataDocCodi[1]}&nombUsua=${dataDocCodi[2]}&depend=${dataDocCodi[3]}&tp_rad=5">${expedient}</a></td>` ;
					console.log(`${radEnt}-${radSal}`);
					newRow.innerHTML = `
						${radGen}
						${radEnt}
						${radSal}
						${radres}
						${radMem}
						${b_cirInt}
						${b_cirExt}
						${b_autos}
						${b_vobo}
						${b_devueltos}
						${b_jefe_area}
						${b_mem_multi}
						${radInf}
						${radExp}
					`;
					$linkDOM.style.setProperty("display", "none");
					data.dataRes.appendChild(newRow);
				}
				else
				{
					abrirModalErr();
					//window.location.replace('./ReportePazSalvo.php');
					//alert(`El Usuario  ${dataDocCodi[2]} Se encuentra a Paz Y Salvo`);
					return;
				}

	}
	catch(err)
	{
		console.log(`ERROR-LOG (${err})`);
	}

	abrirModal();

})
