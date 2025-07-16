#!/bin/bash
# Script to test both Node.js and PHP backends

echo "=== Testing Both Backends ==="
echo

# Test Node.js backend (port 5000)
echo "1. Testing Node.js backend (port 5000)..."
NODE_RESPONSE=$(curl -s http://localhost:5000/api/auth/user 2>/dev/null)
if [ $? -eq 0 ] && [[ "$NODE_RESPONSE" == *"email"* ]]; then
    echo "✅ Node.js backend is running and responding"
    echo "   Response: ${NODE_RESPONSE:0:100}..."
else
    echo "❌ Node.js backend not accessible"
fi

echo

# Start PHP backend if not running
echo "2. Starting PHP backend (port 8000)..."
PHP_PID=$(pgrep -f "php.*8000")
if [ -z "$PHP_PID" ]; then
    cd php/public
    nohup php -S 0.0.0.0:8000 index.php > /tmp/php-server.log 2>&1 &
    NEW_PHP_PID=$!
    echo "   Started PHP server with PID: $NEW_PHP_PID"
    cd ../..
    sleep 3
else
    echo "   PHP server already running with PID: $PHP_PID"
fi

# Test PHP backend
echo "3. Testing PHP backend..."
PHP_TEST=$(curl -s http://localhost:8000/test 2>/dev/null)
if [ $? -eq 0 ] && [[ "$PHP_TEST" == *"PHP server is running"* ]]; then
    echo "✅ PHP backend is running and responding"
    echo "   Response: $PHP_TEST"
else
    echo "❌ PHP backend not accessible"
    echo "   Checking logs..."
    if [ -f /tmp/php-server.log ]; then
        echo "   PHP log: $(tail -3 /tmp/php-server.log)"
    fi
fi

echo

# Test PHP login
echo "4. Testing PHP authentication..."
PHP_LOGIN=$(curl -s -X POST http://localhost:8000/api/auth/login \
    -H "Content-Type: application/json" \
    -d '{"email":"admin@test.com","password":"admin123"}' 2>/dev/null)

if [[ "$PHP_LOGIN" == *"first_name"* ]]; then
    echo "✅ PHP authentication working"
    echo "   Login successful"
else
    echo "❌ PHP authentication failed"
    echo "   Response: $PHP_LOGIN"
fi

echo

# Show running processes
echo "5. Active backend processes:"
ps aux | grep -E "(tsx|php)" | grep -v grep | while read line; do
    echo "   $line"
done

echo
echo "=== Backend Status Summary ==="
echo "Node.js backend: http://localhost:5000 (for React frontend)"
echo "PHP backend:     http://localhost:8000 (alternative backend)"
echo
echo "Both backends use the same PostgreSQL database."
echo "You can switch between them by changing the API base URL."
echo "Login credentials: admin@test.com / admin123"