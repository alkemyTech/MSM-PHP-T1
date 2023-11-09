# Billetera virtual en PHP con Laravel

Este proyecto es una billetera virtual desarrollada utilizando el framework Laravel en PHP. La aplicación contendrá las funciones bancarias básicas como: transacciones, asociar tarjetas físicas y virtuales almacenar dinero en un entorno digital y realizar pagos online.

## Desarrolladores del proyecto

- Emilio Vernet
- Jorge Martín Lorente
- Javier Cavalero
- Fabián Loza
- Emiliano Rodriguez

## Requisitos Previos

Asegúrate de tener las siguientes versiones de software instaladas antes de comenzar:

- [PHP](https://www.php.net/): Se recomienda PHP 7.3 o superior.
- [Composer](https://getcomposer.org/): Utiliza Composer 2.0 o superior.
- [Git](https://git-scm.com/): Se recomienda Git 2.0 o superior.

## Librerías del proyecto

- [Passport](https://laravel.com/docs/10.x/passport): Para la autenticación.
- [Telescope](https://laravel.com/docs/10.x/telescope): Para el registro de solicitudes.

## Documentación de las solicitudes

- Podés encontrar la colección de Postman para este proyecto [acá](https://www.postman.com/spacecraft-astronomer-43947792/workspace/my-workspace/collection/26441397-7c814d86-5734-4c47-a0b5-8d0b8540f72f?action=share&creator=26441397&active-environment=26441397-a58a9b50-18ca-4644-94c8-c72a3631c76e) 

# Instalacion del proyecto

1) Dentro del directorio "C:\xampp\htdocs" clona el repositorio 

```bash
git clone https://github.com/alkemyTech/MSM-PHP-T1/tree/main
```
2) Cambia al directorio del proyecto

```bash
cd MSM-PHP-T1
```
3) Instala las dependencias del proyecto utilizando Composer

 ```bash
 composer install
 ```
 
4) Crea un archivo .env a partir del archivo de ejemplo ".env.example"

 ```bash
 copy .env.example .env
 ```

5) Genera una clave de aplicación única para Laravel

 ```bash
 php artisan key:generate
 ```

6) Crea la base de datos ejecutando las migraciones

 ```bash
 php artisan migrate
 ```

7) Llena la base de datos con datos de ejemplo

 ```bash
 php artisan db:seed
 ```

8) Inicia el servidor

 ```bash
 php artisan serve
 ```

## Respuestas HTTP

- Utilizaremos la convención estándar de respuestas HTTP.

