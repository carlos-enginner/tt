#!/bin/sh

# subindo a aplicação
docker-compose down && docker-compose up -d

# subindo o processo do php-fpm
docker exec -it laravel_app php-fpm -D

# subindo o processo do nginx
docker exec -it laravel_app nginx

# rodando composer dump-autoload optimize
docker exec laravel_app composer dump-autoload -o

# rodar os migrates
docker exec laravel_app php artisan migrate

# subindo as filas
docker exec laravel_app php artisan queue:work --queue=default,purchase_order,payment_order &

# executado o servidor de websocket
docker exec laravel_app php artisan reverb:start --debug &

# subindo a aplicacao
docker exec -it laravel_app npm run dev