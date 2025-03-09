mkdir ./data
docker-compose up -d
sleep 3
docker-compose exec php-fpm composer install
sleep 3
docker-compose exec php-fpm php artisan migrate
docker-compose exec php-fpm php artisan db:seed --class=LineItemSeeder

