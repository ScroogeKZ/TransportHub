<?php
// Start session before any output
session_start();

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: http://localhost:5173');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Credentials: true');

// Handle preflight requests
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once 'config/database.php';
require_once 'models/User.php';
require_once 'models/TransportationRequest.php';
require_once 'models/Carrier.php';
require_once 'models/Route.php';
require_once 'models/Shipment.php';
require_once 'controllers/AuthController.php';
require_once 'controllers/RequestController.php';
require_once 'controllers/CarrierController.php';
require_once 'controllers/RouteController.php';
require_once 'controllers/ShipmentController.php';
require_once 'controllers/AdminController.php';

// Parse the request URI
$request_uri = $_SERVER['REQUEST_URI'] ?? '/';
$path = parse_url($request_uri, PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

// Remove the base path if running in subdirectory
$path = str_replace('/php-backend', '', $path);

// Route the request
try {
    switch (true) {
        // Auth routes
        case $path === '/api/auth/login' && $method === 'POST':
            $controller = new AuthController();
            $controller->login();
            break;
            
        case $path === '/api/auth/register' && $method === 'POST':
            $controller = new AuthController();
            $controller->register();
            break;
            
        case $path === '/api/auth/logout' && $method === 'POST':
            $controller = new AuthController();
            $controller->logout();
            break;
            
        case $path === '/api/auth/user' && $method === 'GET':
            $controller = new AuthController();
            $controller->getCurrentUser();
            break;
            
        // Transportation request routes
        case $path === '/api/requests' && $method === 'GET':
            $controller = new RequestController();
            $controller->getAllRequests();
            break;
            
        case $path === '/api/requests' && $method === 'POST':
            $controller = new RequestController();
            $controller->createRequest();
            break;
            
        case preg_match('/^\/api\/requests\/(\d+)$/', $path, $matches) && $method === 'GET':
            $controller = new RequestController();
            $controller->getRequest($matches[1]);
            break;
            
        case preg_match('/^\/api\/requests\/(\d+)$/', $path, $matches) && $method === 'PUT':
            $controller = new RequestController();
            $controller->updateRequest($matches[1]);
            break;
            
        case preg_match('/^\/api\/requests\/(\d+)$/', $path, $matches) && $method === 'DELETE':
            $controller = new RequestController();
            $controller->deleteRequest($matches[1]);
            break;
            
        // Carrier routes
        case $path === '/api/carriers' && $method === 'GET':
            $controller = new CarrierController();
            $controller->getAllCarriers();
            break;
            
        case $path === '/api/carriers' && $method === 'POST':
            $controller = new CarrierController();
            $controller->createCarrier();
            break;
            
        case preg_match('/^\/api\/carriers\/(\d+)$/', $path, $matches) && $method === 'PUT':
            $controller = new CarrierController();
            $controller->updateCarrier($matches[1]);
            break;
            
        case preg_match('/^\/api\/carriers\/(\d+)$/', $path, $matches) && $method === 'DELETE':
            $controller = new CarrierController();
            $controller->deleteCarrier($matches[1]);
            break;
            
        // Route routes
        case $path === '/api/routes' && $method === 'GET':
            $controller = new RouteController();
            $controller->getAllRoutes();
            break;
            
        case $path === '/api/routes' && $method === 'POST':
            $controller = new RouteController();
            $controller->createRoute();
            break;
            
        case preg_match('/^\/api\/routes\/(\d+)$/', $path, $matches) && $method === 'PUT':
            $controller = new RouteController();
            $controller->updateRoute($matches[1]);
            break;
            
        case preg_match('/^\/api\/routes\/(\d+)$/', $path, $matches) && $method === 'DELETE':
            $controller = new RouteController();
            $controller->deleteRoute($matches[1]);
            break;
            
        // Shipment routes
        case $path === '/api/shipments' && $method === 'GET':
            $controller = new ShipmentController();
            $controller->getAllShipments();
            break;
            
        case $path === '/api/shipments' && $method === 'POST':
            $controller = new ShipmentController();
            $controller->createShipment();
            break;
            
        case preg_match('/^\/api\/shipments\/(\d+)$/', $path, $matches) && $method === 'PUT':
            $controller = new ShipmentController();
            $controller->updateShipment($matches[1]);
            break;
            
        // Admin routes
        case $path === '/api/admin/users' && $method === 'GET':
            $controller = new AdminController();
            $controller->getAllUsers();
            break;
            
        case preg_match('/^\/api\/admin\/users\/(\d+)$/', $path, $matches) && $method === 'PUT':
            $controller = new AdminController();
            $controller->updateUser($matches[1]);
            break;
            
        case preg_match('/^\/api\/admin\/users\/(\d+)$/', $path, $matches) && $method === 'DELETE':
            $controller = new AdminController();
            $controller->deleteUser($matches[1]);
            break;
            
        case $path === '/api/admin/stats' && $method === 'GET':
            $controller = new AdminController();
            $controller->getStats();
            break;
            
        default:
            http_response_code(404);
            echo json_encode(['error' => 'Route not found']);
            break;
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Internal server error: ' . $e->getMessage()]);
}
?>