#!/bin/bash
# Start PHP development server

echo "Starting PHP backend server on port 8000..."
cd php/public
php -S 0.0.0.0:8000 index.php