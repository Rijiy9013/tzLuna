# 1) окружение
cp docker/.env.example docker/.env
cp src/.env.example src/.env 
# API_TOKEN в src/.env отвечает за авторизацию

# 2) запуск
docker compose up -d --build

# 3) установка зависимостей и ключа приложения
docker compose exec app composer install
docker compose exec app php artisan key:generate

# 4) миграции + сиды
docker compose exec app php artisan migrate:fresh --seed

# 5) сгенерировать Swagger
docker compose exec app php artisan l5-swagger:generate

Использовал слоистую архитектуру: контроллеры, валидация в реквестах и ресурсы. Бизнес логика в сервисах, доступ к данным через репозитории и спец-квери.
Такой подход позволяет быстро стартовать, и также быстро масштабировать и заменять части системы без переписывания остального кода.
