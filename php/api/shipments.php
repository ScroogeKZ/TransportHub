<?php
/**
 * Shipments API endpoints
 */

require_once '../config/database.php';
require_once '../config/session.php';
require_once '../models/Shipment.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, OPTIONS');
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
    $shipmentModel = new Shipment();

    switch ($method) {
        case 'GET':
            if ($id) {
                $action = $pathSegments[count($pathSegments) - 1];
                
                if ($action === 'tracking') {
                    $trackingPoints = $shipmentModel->getTrackingPoints($id);
                    echo json_encode($trackingPoints);
                } else {
                    $shipment = $shipmentModel->findById($id);
                    if ($shipment) {
                        echo json_encode($shipment);
                    } else {
                        http_response_code(404);
                        echo json_encode(['message' => 'Shipment not found']);
                    }
                }
            } else {
                $shipments = $shipmentModel->getAll();
                echo json_encode($shipments);
            }
            break;

        case 'POST':
            if ($id && in_array('tracking', $pathSegments)) {
                // Add tracking point
                $input = json_decode(file_get_contents('php://input'), true);
                $input['shipment_id'] = $id;
                
                $trackingId = $shipmentModel->addTrackingPoint($input);
                http_response_code(201);
                echo json_encode(['id' => $trackingId, 'message' => 'Tracking point added']);
            } else {
                // Create new shipment
                $input = json_decode(file_get_contents('php://input'), true);
                
                $shipmentData = [
                    'request_id' => $input['requestId'] ?? $input['request_id'],
                    'driver_name' => $input['driverName'] ?? $input['driver_name'],
                    'driver_phone' => $input['driverPhone'] ?? $input['driver_phone'],
                    'vehicle_number' => $input['vehicleNumber'] ?? $input['vehicle_number'],
                    'carrier_id' => $input['carrierId'] ?? $input['carrier_id'],
                    'route_id' => $input['routeId'] ?? $input['route_id'],
                    'status' => $input['status'] ?? 'in_transit',
                    'current_location' => $input['currentLocation'] ?? $input['current_location'],
                    'progress' => $input['progress'] ?? 0,
                    'estimated_arrival' => $input['estimatedArrival'] ?? $input['estimated_arrival']
                ];

                $shipment = $shipmentModel->create($shipmentData);
                http_response_code(201);
                echo json_encode($shipment);
            }
            break;

        case 'PATCH':
            if (!$id) {
                http_response_code(400);
                echo json_encode(['message' => 'ID required']);
                exit;
            }

            $input = json_decode(file_get_contents('php://input'), true);
            $shipment = $shipmentModel->update($id, $input);
            echo json_encode($shipment);
            break;

        default:
            http_response_code(405);
            echo json_encode(['message' => 'Method not allowed']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['message' => 'Internal server error: ' . $e->getMessage()]);
}