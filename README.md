PASO A PASO

REQUERIMIENTOS.
Contar con npm, php "^7.2.5|^8.0", composer

1. Descargar el proyecto en formato zip o clonar el respositorio.
2. Revisar en la raiz del proyecto y buscar con nombre archivo con nombre .env,
si no buscar el archivo con nombre .env.example y hacerle una copia y ponerle el nombre .env
3. Diligenciar archivo .env, respecto a la configuración de la base datos.
4. Abrir la consola del sistema y ejecutar el siguiente comando "composer install".
5. Abrir la consola del sistema y ejecutar el siguiente comando "npm i".
6. Abrir la consola del sistema y ejecutar el siguiente comando "php artisan key:generate".
7. php artisan migrate --seed
8. usuario de prueba usuario: admin@test.com, contraseña: 123456789
