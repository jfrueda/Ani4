# REST api 
Es un modulo para el sistema de gestion documental de orfeo que adoptar el estandar PSR-4 y maneja un micro ambiente tipo framework para facilitar el uso de rutas que aseguran el servidor y modularizar los componentes que se instalan dentro de el.

Tenemos un modulo expedientes que adopta la metodologia MVC

 * Se requiere modulo rewrite engine de apache habilitado
 * Se requiere composer para funcionar
 * Clonar proyecto dentro de la ruta raiz de orfeo
 * Entrar al directorio dms
 * Ejecutar composer install
 * Crear carpeta logs y asignarle permisos de escritura a www-data

 ## Proceso para subir a pruebas:

> Recuerden que su desarrollo debe estar en una rama independiente creada desde master. Porfavor no hacer cambios directamente en master.

1. Inicialmente dentro de RESTapi(Dentro de su rama) hacemos un push para que nuestro cambios queden guardados en el repositorio restapi.

2. Posteriormente utilizamos el comando: 

   `find -type d -name *.git` este comando retornara todos los archivos .git

3. Cada uno de los archivos .git encontrados en el paso anterior deben ser eliminados con el comando:

    `rm -rf archivo.git`

4. Ahora nos posicionamos sobre la carpeta html.

5. Ya en html utilizamos el comando:

    `zip /tmp/restapi.zip restapi -r` de esta manera se guardara una copia de nuestro dms en el `/tmp`

6. Posteriormente eliminamos la carpeta dms con el comando:

    `rm -rf restapi`

7. Ahora pasamos a la rama `pruebas` y hacemos un pull

8. Posteriormente utilizamos el comando:

   `unzip /tmp/restapi.zip -d .`

9. Seleccionamos la opción All`[A]` de esta manera nuestro folder dms con nuestros cambios quedara en la rama de pruebas.

10. Finalmente hacemos un commit con el comando:

    `git commit -a`

11. Y el paso final es hacer un push.

> Siguiendo estos pasos habremos enviado nuestro cambios a la rama de pruebas (Si tienes dudas contacta algún miembro del módulo DMS :blush:).
