# Transportation Registry PHP Backend

Полная PHP-реализация бэкенда для системы управления транспортом.

## Особенности

- **Чистый PHP 8.2+** без фреймворков
- **PostgreSQL** с подготовленными запросами
- **Сессионная аутентификация** с bcrypt хешированием
- **Роли и права доступа** для разных типов пользователей
- **RESTful API** с полной совместимостью с React фронтендом
- **CORS поддержка** для кросс-доменных запросов

## Установка

### 1. Системные требования

```bash
# PHP 8.2+ с расширениями
php --version
# PostgreSQL (используется существующая база данных)
```

### 2. Запуск сервера разработки

```bash
# Из корня проекта
cd php-backend
php -S localhost:8000 server.php
```

### 3. Тестирование API

```bash
# Тест регистрации
curl -X POST http://localhost:8000/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{"email":"test@example.com","password":"test123","firstName":"Test","lastName":"User"}'

# Тест авторизации
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"test@example.com","password":"test123"}'
```

## Структура проекта

```
php-backend/
├── index.php              # Главный роутер
├── server.php             # Сервер разработки
├── .htaccess              # Apache конфигурация
├── config/
│   └── database.php       # Подключение к БД
├── models/
│   ├── User.php           # Модель пользователя
│   ├── TransportationRequest.php # Модель заявки
│   ├── Carrier.php        # Модель перевозчика
│   ├── Route.php          # Модель маршрута
│   └── Shipment.php       # Модель отгрузки
└── controllers/
    ├── AuthController.php # Аутентификация
    ├── RequestController.php # Заявки
    ├── CarrierController.php # Перевозчики
    ├── RouteController.php   # Маршруты
    ├── ShipmentController.php # Отгрузки
    └── AdminController.php   # Администрирование
```

## API Endpoints

### Аутентификация
- `POST /api/auth/login` - Вход в систему
- `POST /api/auth/register` - Регистрация
- `GET /api/auth/user` - Текущий пользователь
- `POST /api/auth/logout` - Выход из системы

### Заявки на перевозку
- `GET /api/requests` - Список заявок
- `POST /api/requests` - Создание заявки
- `GET /api/requests/{id}` - Получить заявку
- `PUT /api/requests/{id}` - Обновить заявку
- `DELETE /api/requests/{id}` - Удалить заявку

### Перевозчики
- `GET /api/carriers` - Список перевозчиков
- `POST /api/carriers` - Создать перевозчика
- `PUT /api/carriers/{id}` - Обновить перевозчика
- `DELETE /api/carriers/{id}` - Удалить перевозчика

### Маршруты
- `GET /api/routes` - Список маршрутов
- `POST /api/routes` - Создать маршрут
- `PUT /api/routes/{id}` - Обновить маршрут
- `DELETE /api/routes/{id}` - Удалить маршрут

### Отгрузки
- `GET /api/shipments` - Список отгрузок
- `POST /api/shipments` - Создать отгрузку
- `PUT /api/shipments/{id}` - Обновить отгрузку

### Администрирование
- `GET /api/admin/users` - Список пользователей
- `PUT /api/admin/users/{id}` - Обновить пользователя
- `DELETE /api/admin/users/{id}` - Удалить пользователя
- `GET /api/admin/stats` - Статистика системы

## Роли пользователей

1. **Прораб** - создание и редактирование собственных заявок
2. **Логист** - обработка заявок, управление перевозчиками и маршрутами
3. **Руководитель СМТ** - утверждение заявок после логистики
4. **Финансовый директор** - финансовое утверждение заявок
5. **Генеральный директор** - полный доступ к системе
6. **Супер админ** - управление пользователями и системой

## Безопасность

- Пароли хешируются с помощью `password_hash()`
- Все запросы используют подготовленные SQL-запросы
- Сессии защищены от XSS и CSRF атак
- Проверка прав доступа для каждого endpoint'а

## Совместимость

PHP-бэкенд полностью совместим с существующим React фронтендом без необходимости изменений в клиентском коде.

## Развертывание

### Apache/Nginx
Скопируйте файлы в веб-каталог и настройте виртуальный хост.

### Docker
```dockerfile
FROM php:8.2-apache
RUN docker-php-ext-install pdo pdo_pgsql
COPY php-backend/ /var/www/html/
```

### Replit
PHP-бэкенд готов к развертыванию в Replit с автоматической настройкой.