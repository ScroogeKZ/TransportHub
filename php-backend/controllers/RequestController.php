<?php
class RequestController extends AuthController {
    private $requestModel;
    
    public function __construct() {
        parent::__construct();
        $this->requestModel = new TransportationRequest();
    }
    
    public function getAllRequests() {
        $userId = $this->requireAuth();
        $userRole = $_SESSION['user_role'];
        
        $requests = $this->requestModel->getAll($userId, $userRole);
        
        $response = array_map(function($request) {
            return $this->requestModel->toArray($request);
        }, $requests);
        
        echo json_encode($response);
    }
    
    public function getRequest($id) {
        $userId = $this->requireAuth();
        $userRole = $_SESSION['user_role'];
        
        $request = $this->requestModel->findById($id);
        
        if (!$request) {
            http_response_code(404);
            echo json_encode(['error' => 'Request not found']);
            return;
        }
        
        // Check permissions
        if ($userRole === 'прораб' && $request['user_id'] !== $userId) {
            http_response_code(403);
            echo json_encode(['error' => 'Access denied']);
            return;
        }
        
        $response = $this->requestModel->toArray($request);
        echo json_encode($response);
    }
    
    public function createRequest() {
        $userId = $this->requireAuth();
        $input = json_decode(file_get_contents('php://input'), true);
        
        $required = ['cargoType', 'cargoWeight', 'pickupDate', 'deliveryDate', 'pickupAddress', 'deliveryAddress', 'destinationCity'];
        foreach ($required as $field) {
            if (!isset($input[$field]) || empty($input[$field])) {
                http_response_code(400);
                echo json_encode(['error' => "Field '$field' is required"]);
                return;
            }
        }
        
        $input['userId'] = $userId;
        
        try {
            $request = $this->requestModel->create($input);
            $response = $this->requestModel->toArray($request);
            echo json_encode($response);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to create request: ' . $e->getMessage()]);
        }
    }
    
    public function updateRequest($id) {
        $userId = $this->requireAuth();
        $userRole = $_SESSION['user_role'];
        $input = json_decode(file_get_contents('php://input'), true);
        
        $request = $this->requestModel->findById($id);
        
        if (!$request) {
            http_response_code(404);
            echo json_encode(['error' => 'Request not found']);
            return;
        }
        
        // Check permissions based on role and status
        if (!$this->canUpdateRequest($request, $userRole, $userId)) {
            http_response_code(403);
            echo json_encode(['error' => 'Access denied']);
            return;
        }
        
        try {
            $updated = $this->requestModel->update($id, $input);
            $response = $this->requestModel->toArray($updated);
            echo json_encode($response);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to update request: ' . $e->getMessage()]);
        }
    }
    
    public function deleteRequest($id) {
        $this->requireRole(['супер_админ', 'генеральный_директор']);
        
        $success = $this->requestModel->delete($id);
        
        if ($success) {
            echo json_encode(['message' => 'Request deleted successfully']);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Request not found']);
        }
    }
    
    private function canUpdateRequest($request, $userRole, $userId) {
        switch ($userRole) {
            case 'супер_админ':
            case 'генеральный_директор':
                return true;
                
            case 'прораб':
                return $request['user_id'] === $userId && $request['status'] === 'created';
                
            case 'логист':
                return in_array($request['status'], ['created', 'logistics']);
                
            case 'руководитель_смт':
                return in_array($request['status'], ['logistics', 'manager']);
                
            case 'финансовый_директор':
                return in_array($request['status'], ['manager', 'finance']);
                
            default:
                return false;
        }
    }
}
?>