<?php
/**
 * Main entry point for PHP API
 * Routes requests to appropriate API endpoints
 */

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Test output
if ($_SERVER['REQUEST_URI'] === '/test') {
    echo json_encode(['status' => 'PHP server is running', 'php_version' => phpversion()]);
    exit;
}

// Set headers
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// Get the request URI and method
$requestUri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

// Parse the URL
$path = parse_url($requestUri, PHP_URL_PATH);
$pathSegments = explode('/', trim($path, '/'));

// Remove 'php' and 'public' from path if present
$pathSegments = array_filter($pathSegments, function($segment) {
    return !in_array($segment, ['php', 'public']);
});
$pathSegments = array_values($pathSegments);

// Route to appropriate API endpoint
if (count($pathSegments) >= 2 && $pathSegments[0] === 'api') {
    $endpoint = $pathSegments[1];
    
    switch ($endpoint) {
        case 'auth':
            require_once '../api/auth.php';
            break;
            
        case 'transportation-requests':
            require_once '../api/transportation-requests.php';
            break;
            
        case 'dashboard':
            require_once '../api/dashboard.php';
            break;
            
        case 'carriers':
            require_once '../api/carriers.php';
            break;
            
        case 'routes':
            require_once '../api/routes.php';
            break;
            
        case 'shipments':
            require_once '../api/shipments.php';
            break;
            
        default:
            http_response_code(404);
            echo json_encode(['message' => 'API endpoint not found']);
    }
} else {
    // Serve static files or frontend
    if ($path === '/') {
        // Redirect to the React frontend
        header('Location: http://localhost:5000');
        exit;
    } else {
        http_response_code(404);
        echo json_encode(['message' => 'Not found']);
    }
}