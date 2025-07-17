<?php
// PHP Development Server for Transportation Registry API
// Run with: php -S localhost:8000 -t php-backend server.php

// Configure PHP settings for development
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Set session cookie parameters for cross-origin requests
session_set_cookie_params([
    'lifetime' => 86400, // 24 hours
    'path' => '/',
    'domain' => '',
    'secure' => false,
    'httponly' => true,
    'samesite' => 'Lax'
]);

// Handle static files
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
if ($uri !== '/' && file_exists(__DIR__ . $uri)) {
    return false; // Serve static files directly
}

// Route all API requests to index.php
if (strpos($uri, '/api/') === 0) {
    require_once __DIR__ . '/index.php';
} else {
    // For non-API requests, show a simple status page
    header('Content-Type: application/json');
    echo json_encode([
        'message' => 'Transportation Registry PHP API Server',
        'version' => '1.0.0',
        'endpoints' => [
            'POST /api/auth/login' => 'User authentication',
            'POST /api/auth/register' => 'User registration',
            'GET /api/auth/user' => 'Get current user',
            'POST /api/auth/logout' => 'User logout',
            'GET /api/requests' => 'Get transportation requests',
            'POST /api/requests' => 'Create transportation request',
            'GET /api/carriers' => 'Get carriers',
            'POST /api/carriers' => 'Create carrier',
            'GET /api/routes' => 'Get routes',
            'POST /api/routes' => 'Create route',
            'GET /api/shipments' => 'Get shipments',
            'POST /api/shipments' => 'Create shipment',
            'GET /api/admin/users' => 'Get all users (admin)',
            'GET /api/admin/stats' => 'Get system statistics'
        ]
    ]);
}
?>