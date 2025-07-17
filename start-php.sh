#!/bin/bash

echo "🚀 Запуск PHP версии TransportHub..."
echo "📂 Переход в корневую директорию..."

# Установка переменных окружения для PHP
export PGHOST=${PGHOST:-localhost}
export PGPORT=${PGPORT:-5432}
export PGDATABASE=${PGDATABASE:-main}
export PGUSER=${PGUSER:-postgres}
export PGPASSWORD=${PGPASSWORD:-}

echo "🗄️ Переменные базы данных:"
echo "   Host: $PGHOST"
echo "   Port: $PGPORT"
echo "   Database: $PGDATABASE"
echo "   User: $PGUSER"

echo "🌐 Запуск PHP сервера на порту 8080..."
echo "🔗 Приложение будет доступно по адресу: http://0.0.0.0:8080"
echo ""

# Запуск PHP сервера
php -S 0.0.0.0:8080 -t . index.php