## Test Laravel Shop

### Описание сущностей
- Имеем три сущности: Products, Categories, Orders
- один продукт принадлежит к одной категории
- один продукт может быть в нескольких заказах
- один заказ может содержать несколько продуктов
- один заказ принадлежит одному пользователю
- один пользователь может иметь несколько заказов

### Схема базы данных
![image](public/images/schema_db.png)

### Инициализация
1) git clone https://github.com/partenit/test-shop.git
2) composer install
3) .env.example скопировать в .env и в нем настроить подключение к базе данных в соответствии с параметрами сервера
4) php artisan key:generate
5) php artisan migrate
6) php artisan db:seed - добавит фейковые данные в базу

### Тесты
php artisan test

или

vendor/bin/phpunit (лучше показывает прогресс)
