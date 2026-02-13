
/*
    La clase Utility se encarga de servir datos que se necesitan en varias partes
*/

class Utility {

    static getNumberCurrentExpedient(){
        let numberExpedient = null;
        const regex = /(\/dms\/expedient\/)(\d*E)/;
        const result = regex.exec(window.location.href);

        if (result){
            numberExpedient = result[2];
        }

        return numberExpedient;
    }

}