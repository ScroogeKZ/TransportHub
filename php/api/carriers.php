<?php
/**
 * Carriers API endpoints
 */

require_once '../config/database.php';
require_once '../config/session.php';
require_once '../models/Carrier.php';

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
    $carrierModel = new Carrier();
    $currentUser = getCurrentUser();

    switch ($method) {
        case 'GET':
            if ($id) {
                $carrier = $carrierModel->findById($id);
                if ($carrier) {
                    echo json_encode($carrier);
                } else {
                    http_response_code(404);
                    echo json_encode(['message' => 'Carrier not found']);
                }
            } else {
                $carriers = $carrierModel->getAll();
                echo json_encode($carriers);
            }
            break;

        case 'POST':
            $input = json_decode(file_get_contents('php://input'), true);
            
            $carrierData = [
                'name' => $input['name'],
                'contact_person' => $input['contactPerson'] ?? $input['contact_person'],
                'phone' => $input['phone'],
                'email' => $input['email'] ?? '',
                'address' => $input['address'],
                'transport_types' => $input['transportTypes'] ?? $input['transport_types'] ?? [],
                'rating' => $input['rating'] ?? 5,
                'price_range' => $input['priceRange'] ?? $input['price_range'] ?? '',
                'notes' => $input['notes'] ?? ''
            ];

            $carrier = $carrierModel->create($carrierData);
            http_response_code(201);
            echo json_encode($carrier);
            break;

        case 'PATCH':
            if (!$id) {
                http_response_code(400);
                echo json_encode(['message' => 'ID required']);
                exit;
            }

            $input = json_decode(file_get_contents('php://input'), true);
            $carrier = $carrierModel->update($id, $input);
            echo json_encode($carrier);
            break;

        case 'DELETE':
            if (!$id) {
                http_response_code(400);
                echo json_encode(['message' => 'ID required']);
                exit;
            }

            $result = $carrierModel->delete($id);
            if ($result) {
                echo json_encode(['message' => 'Carrier deleted successfully']);
            } else {
                http_response_code(404);
                echo json_encode(['message' => 'Carrier not found']);
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