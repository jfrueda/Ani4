# Configuración Para servicio de carga masiva de acuses

1. se debe dirigir al directorio ***orfeo/webServices/acuses***  
2. renombrar el archivo ***confAcuses.sample.php*** por ***confAcuses.php***  

| TIPO VARIABLE | VALOR       | descripcion |
| ------------- | ------------| ----------  |
|DEFINE         |URLTRAZA     | VALOR URL DEL API SCMAIL-REST CON METODO GET |
|DEFINE         |URLCERTSMAIL | VALOR URL DEL API  ORFEO SCMAIL CON METODO POST |
|DEFINE         |X_SCKEY_TOKEN| VALOR X-SCKEY-TOKEN PARA EL API SCMAIL-REST |

## Archivo de datos.

En el directorio  ***orfeo/webServices/acuses*** se debe subir el archivo **.xlsx** nombrado como **data.xlsx** con la siguiente estructura y sin cabeceras.  

|Uuid |Fecha de envío| amazonMessageId| Destinatario| Estado | Remitente | Número de mensajes enviados| Asunto | Aperturas| Clicks hechos|
|-----|--------------|----------------|-------------|--------|-----------|----------------------------|--------|----------|--------------|
|identificador del servicio|fecha con formato ('Y-m-dThms')|     |correo destinatario|Estado del acuse|correo remitente| |Supersalud: Radicado numero radicado
|tipo de apertura|              |


## Ejecucion.

Para la ejecucion del servicio debe digitar la siguiente URL en el navegador ***<DOMINIO>/<subdominio>/webServices/acuses/Acuses.php***  

Ejemplos

http://137.184.3.150:8080/orfeo/webServices/acuses/Acuses.php
http://127.0.0.1:8080/orfeo/webServices/acuses/Acuses.php
https://pruebassuperargo.supersalud.gov.co/preproduccion/webServices/acuses/Acuses.php
