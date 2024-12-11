# Siroko Project  
Se administra la creación de categorias, productos de forma agil y sencilla para garantizar la satisfacción de los clientes.  

# Project description:  
Se usan 3 contenedores docker: Apache2, Mysql y Redis     
Se desarrollaron 4 paquetes laravel aplicando arquitectura hexagonal y DDD  
Se usa REDIS para que el listado de categorias y productos sean presentados rápidamente  
Se aplicaron tests automáticos respectivos en cada paquete  
Se usó Bootstrap y Jquery en el frontend de forma básica  
Se efectuan las validaciones respectivas en cada formulario para no tener redundancia de datos o errores inesperados  

# Developed laravel packages:  
- laravel-users: Paquete desarrollado para registrar datos de nuevos usuarios, para hacer login y crear nuevos usuarios.    
- laravel-categories: Paquete que administra las categorias de productos.  
- laravel-products: Paquete que administra los productos y muestra productos a clientes.  
- laravel-shopping-cart: Paquete que administra el carrito de compras.  

# Environment variables:  
SERVER_FQDN="http://localhost"    
LOGIN_REDIRECT_ENDPOINT="/"  
COMPANY_NAME="SIROKO 4 You!..."      
BEARER_TOKEN="Pega aquí el token generado al registrar un usuario con postman"      

# Steps to configure project:  
## Ejecutar los siguientes comandos desde la consola:    
1.- composer install  
2.- cp .env.example .env    
3.- php artisan sail:install (Seleccionar MYSQL Y REDIS)     
4.- sail up -d  
5.- sail artisan key:generate    
6.- sail artisan migrate   
7.- sail artisan vendor:publish --tag=tests --force  
8.- sail artisan vendor:publish --tag=views --force  
9.- sail artisan categories:init  
10.- sail artisan products:init  
11.- execute postman endpoint "user-register"  

## Postman Collection:  
En la carpeta tests está una colección postman para registrar usuarios y acceder a los CRUDs de usuarios, categorías y productos.     
1.- Ejecutar el endpoint user-register que crea un nuevo usuario  
2.- Copia el token generado en una variable de entorno "BEARER_TOKEN" que está en el archivo .env.  

## On a web navigator:  
http://localhost (Como cliente puede seleccionar productos y agregar al carrito de compras)  
http://localhost/web/admin/categories (Colocar las credenciales email y password del usuario creado con postman para acceder a la sección de administración de categorías y productos)  

# Tests:   
Los tests automáticos se ejecutan en una base de datos SQLITE y en REDIS.    
sail artisan test   
