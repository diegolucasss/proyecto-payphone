# Usamos un servidor Apache con PHP listo para producción
FROM php:8.2-apache

# Copiamos todos tus archivos PHP al servidor web
COPY . /var/www/html/

# Exponemos el puerto 80
EXPOSE 80