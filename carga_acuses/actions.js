let radicado = document.getElementById('radicado'),
	fecha = document.getElementById('fecha'),
	hora = document.getElementById('hora'),
	correo = document.getElementById('correo'),
	documento = document.getElementById('documento'),
    tipoEnvio = document.getElementById('tipoEnvio'),
    sendData = document.getElementById('sendData'),
    dependencia = document.getElementById('dependencia'),
    coduser = document.getElementById('coduser'),
	path = document.getElementById('path'),
    consultar = false,
    correosValidos= new Set(),
    sendEmail='',
    archivoCargar='',
    errorRad = document.getElementById('error-rad'),
    errorfech = document.getElementById('error-fech'),
    errorhor = document.getElementById('error-hor'),
    errormail = document.getElementById('error-mail'),
    errorfile = document.getElementById('error-file'),
    errortpenv = document.getElementById('error-tpenv'),
    loader = document.getElementById('circle'),
	formulario = document.getElementById('frm');
    const numeroRegex = /^\d+$/;
    const mailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;


function alertasSwal(mensaje){
	Swal.fire({
        title: 'Error!',
        text: `${mensaje}`,
        icon: 'error',
        confirmButtonText: 'Cool'
    })
}

/*************************************************************************************/
// Validaciones de campo radicado.
/*************************************************************************************/
radicado.addEventListener('input', ()=> {

    if (!radicado.value) {
    	errorRad.textContent = 'El Campo de radicado no debe estar vacio.';
        errorRad.removeAttribute("hidden");
        consultar = false;
  	}
    else if (radicado.value.toString().slice(-1) != 1){
        errorRad.textContent = 'Para el radicado solo se aceptan comunicaciones de salida.';
        errorRad.removeAttribute("hidden");
        consultar = false;
    }
  	else if (!numeroRegex.test(radicado.value)) {
		errorRad.textContent = 'El campo de radicado solo debe contener números.';
		radicado.value = "";
        errorRad.removeAttribute("hidden");
        consultar = false;
  	}
  	else if(radicado.value.length <=13){
    	errorRad.textContent = 'El campo del radicado minimo es de 14 digitos.';
        errorRad.removeAttribute("hidden");
        consultar = false;
  	}
    else{
  	    errorRad.textContent = '';
  	    errorRad.setAttribute("hidden","true")
        consultar = true;
    }
});
/*************************************************************************************/
// Validaciones de campo fecha
/*************************************************************************************/

fecha.addEventListener('change',()=>{

    if(fecha.value){

        errorfech.textContent = '';
        errorfech.setAttribute("hidden",true);
    }
})
/*************************************************************************************/
// Validaciones de campo fecha
/*************************************************************************************/

hora.addEventListener('change',()=>{

    if(hora.value){

        errorhor.textContent = '';
        errorhor.setAttribute("hidden",true);
    }
})
/*************************************************************************************/
// Validaciones de campo correo.
/*************************************************************************************/
correo.addEventListener('change', () => {

    consultar = true;
    let correosInvalidos = [];

    /**********************************************/
    // Quita el punto y coma final de los correos
    /**********************************************/
    emailsString = correo.value.replace(/;+$/, '');
    correo.value = emailsString;
    
    emailsValueSplit = correo.value.split(";");

    emailsValueSplit.forEach((email) => {
        email = email.trim();

        if (!email) {
            errormail.textContent = 'El Campo del correo no debe estar vacio.';
            errormail.removeAttribute("hidden");
            consultar = false;
        } else if (!mailRegex.test(email)) {
            correosInvalidos.push(email);
            errormail.textContent = `Los correos [${correosInvalidos.join(' | ')}] no cumplen con la estructura requerida`;
            errormail.removeAttribute("hidden");
            consultar = false;
        } else {
            correosValidos.add(email); // Añade el correo al Set si es válido
        }
    });

    if (consultar) {
        sendEmail = Array.from(correosValidos).join(';').trim(); // Convertimos el Set a un array y lo unimos en una cadena
        errormail.setAttribute("hidden", true);
    }
});
/*************************************************************************************/
// Valida que la fecha no se pase de la actual
/*************************************************************************************/
document.addEventListener('DOMContentLoaded', function() {
    // Obtener la fecha actual en formato YYYY-MM-DD
    const today = new Date().toISOString().split('T')[0];
    // Establecer el atributo max del input de fecha
    document.getElementById('fecha').setAttribute('max', today);

    // Agregar un event listener para validar la fecha
    document.getElementById('fecha').addEventListener('input', function() {
        const selectedDate = this.value;
        const errorMessage = document.getElementById('error-fech');

        if (selectedDate > today) {
            errorMessage.removeAttribute('hidden');
        } else {
            errorMessage.setAttribute('hidden', true);
        }
    });
});
/*************************************************************************************/
// Convierte Archivo en b-64 y valida campo File cargar documento.
/*************************************************************************************/
function convertirArchivoABase64(archivo) {
  return new Promise((resolve, reject) => {
    const lector = new FileReader();
    lector.onload = () => resolve(lector.result);
    lector.onerror = reject;
    lector.readAsDataURL(archivo);
  });
}


documento.addEventListener('change', () => {
  const archivo = documento.files[0];
  convertirArchivoABase64(archivo)
    .then(base64 => {
 
      let destArch = base64.split(":");
      destArch = destArch[1].split(";");
      destArch = destArch[1].split(",");
      archivoCargar = destArch[1].trim();
      errorfile.textContent = '';
      errorfile.setAttribute("hidden","true");
    })
    .catch(error => {
      console.error('Error al convertir el archivo:', error);
    });
});
/*************************************************************************************/
// Validaciones de campo Tipo de envio.
/*************************************************************************************/
tipoEnvio.addEventListener('change', ()=>{

    if(tipoEnvio.value){
       
       errortpenv.textContent = '';
       errortpenv.setAttribute("hidden","true");
    }
})

/*************************************************************************************/

sendData.addEventListener('click', async (e) => {
    e.preventDefault();

    /************************************************************************************/
    // Valida Fecha Vacia
    /************************************************************************************/
    if (radicado.value == '') {
        errorRad.textContent = 'El Campo de radicado no debe estar vacio.';
        errorRad.removeAttribute("hidden");
    }
    else if (radicado.value.toString().slice(-1) != 1){
        errorRad.textContent = 'Para el radicado solo se aceptan comunicaciones de salida.';
        errorRad.removeAttribute("hidden");
    }
    else if(radicado.value.length <=13){
        errorRad.textContent = 'El campo del radicado minimo es de 14 digitos.';
        errorRad.removeAttribute("hidden");
    }
    else{
        errorRad.textContent = '';
        errorRad.setAttribute("hidden","true")
        try{

            let res = await fetch(`dataRequest.php`,{
                    method:'POST',
                    mode:'cors',
                    body:JSON.stringify({
                        validar:true,
                        radicado:radicado.value,
                    }),
                    headers:{
                        'Content-type':'application/json'
                    }
                }),
                json = await res.json();
                
                if(json.response == true){
                    errorRad.textContent = 'El Radicado No Existe En Esta Versión De Super Argo.';
                    errorRad.removeAttribute("hidden");
                }else if(json.estadoAnex != 4){

                    errorRad.textContent = 'El radicado no tiene el estado de envío requerido para adjuntar el acuse.';
                    errorRad.removeAttribute("hidden");
                }else{
                    errorRad.textContent = '';
                }

        }catch(err){console.log(err)}
    }
/*************************************************************************************/
// Validar radicado
/*************************************************************************************/
    
    /************************************************************************************/
    // Valida Fecha Vacia
    /************************************************************************************/

    if(fecha.value == ""){
        errorfech.textContent = 'El campo de fecha no debe estar vacío.';
        errorfech.removeAttribute("hidden");
    }
    /************************************************************************************/
    // Valida Hora Vacia
    /************************************************************************************/
    if(!hora.value){
        errorhor.textContent = 'El campo de la hora no debe estar vacío.';
        errorhor.removeAttribute("hidden");
    }
    /************************************************************************************/
    // Valida Correo
    /************************************************************************************/
    if(!sendEmail){
        errormail.textContent = 'El campo del correo no debe estar vacío.';
        errormail.removeAttribute("hidden");
    }else{
        errormail.textContent = '';
    }
    /************************************************************************************/
    // Valida archivo
    /************************************************************************************/
    if(!archivoCargar){
        errorfile.textContent = 'Se debe seleccionar un archivo PDF para realizar el cargue del acuse.';
        errorfile.removeAttribute("hidden");
    }
    /************************************************************************************/
    // Valida campo seleccionar tipo envio
    /************************************************************************************/
    if(tipoEnvio.value == 0){
        errortpenv.textContent = 'Se debe seleccionar un archivo tipo de envio.';
        errortpenv.removeAttribute("hidden");
    }

    console.log(errormail.textContent)

    if(!errorRad.textContent && !errorfech.textContent && !errorhor.textContent && !errormail.textContent && !errorfile.textContent && !errortpenv.textContent ){
        consultar = true;
    }
    else{
        consultar = false;
    }
    /************************************************************************************/
console.log(consultar)
    if(consultar == true){

        formulario.setAttribute('hidden',true)
        loader.removeAttribute('hidden')

        try{
            let res = await fetch(`dataRequest.php`,{
                method:'POST',
                mode:'cors',
                body:JSON.stringify({
                    radicado:radicado.value,
                    fecha:fecha.value,
                    hora:hora.value,
                    emails:sendEmail,
                    archB64:archivoCargar,
                    tpenvio:tipoEnvio.value
                }),
                headers:{
                    'Content-type':'application/json'
                }
            }),
            json = await res.json();
            console.log(json.result);
            if(json.result == true){

                setTimeout(()=>{
                    // $noti.outerHTML = json;
                    loader.setAttribute('hidden',true)
                    formulario.removeAttribute('hidden')
                    Swal.fire({
                        title: 'Señor Usuario!',
                        text: `Se ha agregado el acuse al radicado ${radicado.value}, desea cargar otro acuse o desea consultarlo`,
                        icon: 'info',
                        showCancelButton: true,
                        confirmButtonText: 'Cargar acuse con un nuevo radicado',
                        cancelButtonText: 'Cargar acuse con el mismo radicado',
                        customClass: {
                            confirmButton: 'custom-consultar', // Aplica la clase personalizada aquí
                        },
                        footer: '<button id="newReload" style="background-color: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 5px;">Consultar radicado</button>'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Acción para "Consultar"
                            radicado.removeAttribute("disabled");
                            radicado.value='';
                            fecha.value = ''; 
                            hora.value = '';
                            correo.value = '';
                            documento.value = ''; 
                            tipoEnvio.value = '';

                        } else if (result.dismiss === Swal.DismissReason.cancel) {
                            // Acción para "Volver a cargar"
                            radicado.setAttribute("disabled",true);
                            fecha.value = ''; 
                            hora.value = '';
                            correo.value = '';
                            documento.value = ''; 
                            tipoEnvio.value = '';
                        }
                    });

                    // Maneja el clic en el botón "Volver a cargar nuevo"
                    document.getElementById('newReload').addEventListener('click', () => {
                        // Acción para "Volver a cargar nuevo"
                        window.location.replace(`./../verradicado.php?verrad=${radicado.value}&nomcarpeta=Busquedas&depe_actu=${dependencia.textContent}&usuacodi=${coduser.textContent}#tabs-d`)
                    });
   
                },2000)
                
            }
        }
        catch(err){console.log(err)}
    }else{
        Swal.fire({
            title: 'Señor Usuario!',
            text: 'Hay campos vacios o no cumplen con las reglas, por favor revisar',
            icon: 'warning',
            confirmButtonText: 'Continuar'
        })
    }
    
});