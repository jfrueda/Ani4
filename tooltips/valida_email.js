function validate(html) {

  const correosPermitidos = [
    'yulicita1982-@hotmail.com',
    'corporativo@almamater.hospital',
    'cocampo@diazyocampo.legal',
    'notificaciones@diazyocampo.legal',
    'contabilidad@fracturasyfracturas.com.co',
    'juridico@fracturasyfracturas.com.co',
    'notificaciones@diazyocampo.legal',
    'ALCALDIA@OROCUE-CASANARE.GOV.CO',
    'CONTACTENOS@CABUYARO-META.GOV.CO'
  ];

	const id=$(html).attr('id');
	let emailsString = document.getElementById(id).value;
	emailsString = emailsString.toString().replace(/\s/g, '');

	/********************************************************/
	//Quita el ; final en la cadena de los correos
  emailsString = eliminarCorreosDuplicados(emailsString);
	emailsString = emailsString.replace(/;+$/, '');
	let inpEmail = document.getElementById(id);
	inpEmail.value = emailsString;
	/********************************************************/
	
	if(emailsString.length > 499) {
		createPopup("ERROR - excedes los 500 de caracteres ")
	}else {

		let emailsSplited = emailsString.split(";")
		let invalidEmails = ""

		emailsSplited.forEach((email) => {
			 let finalEmail = email.trim()
        // Si el email no está en correosPermitidos, realiza la validación
        if (!correosPermitidos.includes(finalEmail)) 
        {
            if (!validateEmail(finalEmail) && invalidEmails.length == 0) 
            {
              invalidEmails += finalEmail;
            } 
            else if (!validateEmail(finalEmail)) 
            {
              invalidEmails += ", " + finalEmail;
            }
        }
    });

    if (invalidEmails.length != 0) 
    {
      createPopup("Error con los emails =" + invalidEmails);
      document.getElementById("errormail").value = 1;
    } 
    else 
    {
      document.getElementById("errormail").value = 0;
    }
      

		if(invalidEmails.length!=0){

			createPopup("error con los emails ="+invalidEmails)
			document.getElementById("errormail").value=1;
		}
		else
		{
			document.getElementById("errormail").value=0;
		}
	}
 
}

function validateEmail(email) 
{
	//if(email == 'sarlaft@almamater.hospital' || email == 'corporativo@almamater.hospital' || email.indexOf('@coal.clinic') != -1) {
	if(email == 'sarlaft@almamater.hospital' || email.indexOf('@coal.clinic') != -1) {
		return true;
	}	

	let cadena = email;
	let termino = ".coop";
	let posicion = cadena.indexOf(termino);

	if (posicion !== -1 && !correosPermitidos.includes(email))
	{
		let mailRegex = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/;

	 	if(email.match(mailRegex)){
	 		return true
	 	}else {
	 		return false
	 	}
	}
	else
	{
		let mailRegex = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;

	 	if(email.match(mailRegex)){
	 		return true
	 	}else {
	 		return false
	 	}

	}
}

function createPopup(message) {
  // Create a basic popup element without external libraries
  const popup = document.createElement('div');
  popup.id = 'validationPopup'; // Optional for styling
  popup.style.cssText = `
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: #fff;
    border: 1px solid #ddd;
    padding: 10px;
    box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
    z-index: 9999; 
  `;

  const closeButton = document.createElement('button');
  closeButton.textContent = 'Cerrar';
  closeButton.style.cssText = `
  	  width:100%;
      margin-top: auto;
	  padding: 5px 10px;
	  border: 1px solid #ddd;
	  border-radius: 3px;
	  cursor: pointer;
	  background-color: #286090;
	  border-color: #2e6da4;
	  color: #fff;
  `;

  closeButton.addEventListener('click', () => {
    popup.parentNode.removeChild(popup);
  });

  const content = document.createElement('div'); // Cambia a div para poder agregar la imagen
  content.style.display = 'flex';
  content.style.alignItems = 'center';

  const icon = document.createElement('img');
  icon.src = '../img/icons8-warning-64.png'; // Reemplaza con la ruta de tu imagen
  icon.style.width = '60px'; // Ajusta el tamaño según sea necesario
  icon.style.marginRight = '20px';
  content.appendChild(icon);


  const text = document.createElement('p');
  text.style.whiteSpace = 'pre-line';
  text.textContent = message;
  content.appendChild(text);

  popup.appendChild(content);
  popup.appendChild(closeButton);

  document.body.appendChild(popup);
}

function eliminarCorreosDuplicados(correo) {
  // Divide la cadena en correos separados por ';'
  let correos = correo.split(';');
  // Usa un Set para almacenar solo correos únicos
  let correosUnicos = new Set();
  // Itera sobre los correos y agrega solo los que no están duplicados
  correos.forEach(correo => {
      // Elimina espacios adicionales
      correo = correo.trim();
      // Verifica que el correo tenga un formato válido (opcional)
      if (/^[\w\.-]+@[\w\.-]+\.\w+$/.test(correo)) {
          correosUnicos.add(correo);
      }
  });
  // Une los correos únicos de vuelta a una cadena separada por ';'
  return Array.from(correosUnicos).join(';');
}