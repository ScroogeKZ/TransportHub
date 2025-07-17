#!/bin/bash

# Скрипт для запуска PHP-сервера
echo "Запуск PHP-сервера для Хром-KZ..."

# Переход в папку с PHP-бэкендом
cd php-backend

# Проверка PHP
if ! command -v php &> /dev/null; then
    echo "PHP не найден. Установите PHP 8.2+"
    exit 1
fi

# Проверка расширений
php -m | grep -E "(pdo|pdo_pgsql|session|json|openssl|curl)" || {
    echo "Не все необходимые расширения PHP установлены"
    exit 1
}

# Проверка переменных окружения
if [ -z "$DATABASE_URL" ]; then
    echo "Переменная окружения DATABASE_URL не установлена"
    exit 1
fi

echo "Запуск PHP-сервера на порту 8000..."
echo "Доступ к API: http://localhost:8000/api/"
echo "Тестовая страница: http://localhost:8000/test.php"
echo "Для остановки нажмите Ctrl+C"

# Запуск сервера
php -S 0.0.0.0:8000 server.php