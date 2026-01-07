

// Se inicializan los butones
const btnCloseExpedient = document.getElementById('btnCloseExpedient');
const btnOpenExpedient = document.getElementById('btnOpenExpedient');
const btnLinkNewExpedient = document.getElementById('btnLinkNewExpedient');
const btnUngrupExpedient = document.getElementById('btnUngrupExpedient');

// Objeto expedient para realizar los casos de uso
const numExpedient = Utility.getNumberCurrentExpedient();
const expedient = new Expedient(numExpedient);


// Asociar un nuevo expediente
btnLinkNewExpedient.addEventListener('click', () => {
    const numLinkExp = document.getElementById('numLinkExp');
    expedient.linkNewExpedient(numLinkExp.value);
})

// Desasociar un expediente
btnUngrupExpedient.addEventListener('click', async () => {
    const numUngroupExpedient = document.getElementById('numUngroupExp');
    expedient.ungroundExpedient(numUngroupExpedient.value);
})


// Cerrar un expediente
if (btnCloseExpedient){
    btnCloseExpedient.addEventListener('click', () => {
        expedient.closeExpedient()
    })
}

// Reabrir un expediente
if (btnOpenExpedient){
    btnOpenExpedient.addEventListener('click', () => {
        const reasonOpenExpedient = document.getElementById('reasonOpenExp');
        expedient.openExpedient(reasonOpenExpedient.value);
    })
}