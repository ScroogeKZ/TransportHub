<?php
/**
 * Transportation Requests API endpoints
 */

require_once '../config/database.php';
require_once '../config/session.php';
require_once '../models/TransportationRequest.php';

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

// Extract ID if present
$id = null;
if (count($pathSegments) > 2 && is_numeric(end($pathSegments))) {
    $id = (int) end($pathSegments);
}

try {
    $requestModel = new TransportationRequest();
    $currentUser = getCurrentUser();

    switch ($method) {
        case 'GET':
            if ($id) {
                $request = $requestModel->findById($id);
                if ($request) {
                    echo json_encode($request);
                } else {
                    http_response_code(404);
                    echo json_encode(['message' => 'Request not found']);
                }
            } else {
                $requests = $requestModel->getAll($currentUser['id'], $currentUser['role']);
                echo json_encode($requests);
            }
            break;

        case 'POST':
            $input = json_decode(file_get_contents('php://input'), true);
            
            $requestData = [
                'from_city' => $input['fromCity'] ?? $input['from_city'],
                'from_address' => $input['fromAddress'] ?? $input['from_address'] ?? '',
                'to_city' => $input['toCity'] ?? $input['to_city'],
                'to_address' => $input['toAddress'] ?? $input['to_address'] ?? '',
                'cargo_type' => $input['cargoType'] ?? $input['cargo_type'],
                'weight' => $input['weight'] ?? '0',
                'width' => $input['width'] ?? '0',
                'length' => $input['length'] ?? '0',
                'height' => $input['height'] ?? '0',
                'description' => $input['description'] ?? '',
                'transport_type' => $input['transportType'] ?? $input['transport_type'] ?? 'auto',
                'urgency' => $input['urgency'] ?? 'normal',
                'created_by_id' => $currentUser['id']
            ];

            $request = $requestModel->create($requestData);
            http_response_code(201);
            echo json_encode($request);
            break;

        case 'PATCH':
            if (!$id) {
                http_response_code(400);
                echo json_encode(['message' => 'ID required']);
                exit;
            }

            $input = json_decode(file_get_contents('php://input'), true);
            $existingRequest = $requestModel->findById($id);
            
            if (!$existingRequest) {
                http_response_code(404);
                echo json_encode(['message' => 'Request not found']);
                exit;
            }

            // Check permissions
            $canEdit = $this->checkEditPermission($currentUser['role'], $existingRequest['status'], 
                                                 $existingRequest['created_by_id'], $currentUser['id']);
            if (!$canEdit) {
                http_response_code(403);
                echo json_encode(['message' => 'Insufficient permissions']);
                exit;
            }

            $request = $requestModel->update($id, $input);
            echo json_encode($request);
            break;

        case 'DELETE':
            if (!$id) {
                http_response_code(400);
                echo json_encode(['message' => 'ID required']);
                exit;
            }

            // Only super admin can delete
            if ($currentUser['role'] !== 'супер_админ') {
                http_response_code(403);
                echo json_encode(['message' => 'Insufficient permissions']);
                exit;
            }

            $result = $requestModel->delete($id);
            if ($result) {
                echo json_encode(['message' => 'Request deleted successfully']);
            } else {
                http_response_code(404);
                echo json_encode(['message' => 'Request not found']);
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

function checkEditPermission($userRole, $requestStatus, $createdById, $userId) {
    switch ($userRole) {
        case 'супер_админ':
        case 'генеральный_директор':
            return true;
            
        case 'прораб':
            return $requestStatus === 'created' && $createdById === $userId;
            
        case 'логист':
            return in_array($requestStatus, ['created', 'logistics']);
            
        case 'руководитель_смт':
            return in_array($requestStatus, ['logistics', 'manager']);
            
        case 'финансовый_директор':
            return in_array($requestStatus, ['manager', 'financial']);
            
        default:
            return false;
    }
}