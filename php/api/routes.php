<?php
/**
 * Routes API endpoints
 */

require_once '../config/database.php';
require_once '../config/session.php';
require_once '../models/Route.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

initSession();
requireAuth();

$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$pathSegments = explode('/', trim($path, '/'));

$id = null;
if (count($pathSegments) > 2 && is_numeric(end($pathSegments))) {
    $id = (int) end($pathSegments);
}

try {
    $routeModel = new Route();

    switch ($method) {
        case 'GET':
            if ($id) {
                $route = $routeModel->findById($id);
                if ($route) {
                    echo json_encode($route);
                } else {
                    http_response_code(404);
                    echo json_encode(['message' => 'Route not found']);
                }
            } else {
                $routes = $routeModel->getAll();
                echo json_encode($routes);
            }
            break;

        case 'POST':
            $input = json_decode(file_get_contents('php://input'), true);
            
            $routeData = [
                'name' => $input['name'],
                'from_city' => $input['fromCity'] ?? $input['from_city'],
                'to_city' => $input['toCity'] ?? $input['to_city'],
                'distance' => $input['distance'],
                'estimated_time' => $input['estimatedTime'] ?? $input['estimated_time'],
                'toll_cost' => $input['tollCost'] ?? $input['toll_cost'] ?? 0,
                'fuel_cost' => $input['fuelCost'] ?? $input['fuel_cost'] ?? 0,
                'is_optimized' => $input['isOptimized'] ?? $input['is_optimized'] ?? 0
            ];

            $route = $routeModel->create($routeData);
            http_response_code(201);
            echo json_encode($route);
            break;

        case 'PATCH':
            if (!$id) {
                http_response_code(400);
                echo json_encode(['message' => 'ID required']);
                exit;
            }

            $input = json_decode(file_get_contents('php://input'), true);
            $route = $routeModel->update($id, $input);
            echo json_encode($route);
            break;

        case 'DELETE':
            if (!$id) {
                http_response_code(400);
                echo json_encode(['message' => 'ID required']);
                exit;
            }

            $result = $routeModel->delete($id);
            if ($result) {
                echo json_encode(['message' => 'Route deleted successfully']);
            } else {
                http_response_code(404);
                echo json_encode(['message' => 'Route not found']);
            }
            break;

        default:
            http_response_code(405);
            echo json_encode(['message' => 'Method not allowed']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['message' => 'Internal server error: ' . $e->getMessage()]);
}