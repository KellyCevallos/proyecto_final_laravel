Documentación para montar el proyecto:
•	Hay que tomar en cuenta que él .env (si no aparece crearlo con: cp .env.example .env) en la backend es necesario configurarlo para una base de datos de postgress vacía para las migraciones.
•	Una vez teniendo la base de datos conectada es necesario abrir dos terminales, para lanzar la backend y la frontend por separado, la raíz del terminal debería verse así:
Backend: C: \ (El directorio en donde se encuentra) \AplicacionesWEb2\backend>
Frontend: C: \ (El directorio en donde se encuentra) \AplicacionesWEb2\Frontend>
•	Es necesario descargar para cada parte las dependencias requeridas, en la backend: composer install, y en la frontend: npm install 
•	Luego en el terminal de Backend ejecutar las migraciones: php artisan migrate, y luego ejecutar el seeder necesario para las tablas importantes: php artisan db:seed --class=Users
•	Una vez teniendo esas dos partes hechas se puede lanzar el servidor de cada cual, para la frontend: npm run dev, para la backend: php artisan serve
•	Por último, acceder al url de la front que aparece en la terminal
Credenciales:
Usuario Admin: user: admin, contraseña: admin
Usuario Profesor: user: Jzamora, contraseña: 123456
