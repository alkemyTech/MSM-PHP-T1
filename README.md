# Billetera virtual en PHP con Laravel

Este proyecto es una billetera virtual desarrollada utilizando el framework Laravel en PHP. La aplicación contendrá las funciones bancarias básicas como: transacciones, asociar tarjetas físicas y virtuales almacenar dinero en un entorno digital y realizar pagos online.

# Integrantes del grupo

- Emilio Vernet
- Jorge Martín Lorente
- Javier Cavalero
- Fabián Loza
- Emiliano Rodriguez

# Instalacion del proyecto

1) Dentro del directorio "C:\xampp\htdocs" ejecutar el comando ```git clone https://github.com/alkemyTech/MSM-PHP-T1/tree/main```
2) Luego ```cd MSM-PHP-T1```.
3) Ejecutar el comando ```composer update``` para instalar las dependecias.
4) Crear archivo .env con el comando ```copy .env.example .env```.
5) Generar Key con el comando ```php artisan key:generate```.
6) Para crear la base de datos ejecutar el comando ```php artisan migrate```.
7) Para llenar la base de datos ejecutar ```php artisan db:seed```.
8) Para levantar el servidor ejecutar ```php artisan serve```.

# Librerías del proyecto

- Para la autenticación utilizaremos Passport.
- Para el log de request utilizaremos Telescope.

# Respuestas HTTP

- Utilizaremos la convención estándar de respuestas HTTP.
