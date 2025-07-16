<?php
/**
 * Dashboard API endpoints
 */

require_once '../config/database.php';
require_once '../config/session.php';
require_once '../models/TransportationRequest.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

initSession();
requireAuth();

$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$pathSegments = explode('/', trim($path, '/'));
$action = end($pathSegments);

try {
    $requestModel = new TransportationRequest();

    switch ($method) {
        case 'GET':
            if ($action === 'stats') {
                $stats = $requestModel->getDashboardStats();
                echo json_encode($stats);
                
            } elseif ($action === 'monthly-stats') {
                $monthlyStats = $requestModel->getMonthlyStats();
                echo json_encode($monthlyStats);
                
            } elseif ($action === 'status-stats') {
                $statusStats = $requestModel->getStatusStats();
                echo json_encode($statusStats);
                
            } else {
                http_response_code(404);
                echo json_encode(['message' => 'Endpoint not found']);
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