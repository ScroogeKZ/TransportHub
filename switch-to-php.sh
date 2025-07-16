#!/bin/bash
# Script to switch from Node.js backend to PHP backend

echo "=== Switching to PHP Backend ==="
echo

# 1. Stop current Node.js server (if running)
echo "1. Stopping Node.js server..."
pkill -f "tsx server/index.ts" || echo "   No Node.js server running"

# 2. Start PHP server
echo "2. Starting PHP backend server..."
cd php/public
nohup php -S 0.0.0.0:8000 index.php > /tmp/php-server.log 2>&1 &
PHP_PID=$!
echo "   PHP server started with PID: $PHP_PID"
echo "   Server running on: http://localhost:8000"

# 3. Wait for server to start
echo "3. Waiting for server to initialize..."
sleep 3

# 4. Test the server
echo "4. Testing PHP backend..."
cd ../..
php php/test-api.php

echo
echo "=== PHP Backend Ready ==="
echo "Frontend should now connect to: http://localhost:8000"
echo "To view PHP server logs: tail -f /tmp/php-server.log"
echo "To stop PHP server: kill $PHP_PID"