<?php
class CarrierController extends AuthController {
    private $carrierModel;
    
    public function __construct() {
        parent::__construct();
        $this->carrierModel = new Carrier();
    }
    
    public function getAllCarriers() {
        $this->requireAuth();
        
        $carriers = $this->carrierModel->getAll();
        
        $response = array_map(function($carrier) {
            return $this->carrierModel->toArray($carrier);
        }, $carriers);
        
        echo json_encode($response);
    }
    
    public function createCarrier() {
        $this->requireRole(['супер_админ', 'генеральный_директор', 'логист']);
        $input = json_decode(file_get_contents('php://input'), true);
        
        $required = ['name', 'contactPerson', 'phone', 'email', 'address'];
        foreach ($required as $field) {
            if (!isset($input[$field]) || empty($input[$field])) {
                http_response_code(400);
                echo json_encode(['error' => "Field '$field' is required"]);
                return;
            }
        }
        
        try {
            $carrier = $this->carrierModel->create($input);
            $response = $this->carrierModel->toArray($carrier);
            echo json_encode($response);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to create carrier: ' . $e->getMessage()]);
        }
    }
    
    public function updateCarrier($id) {
        $this->requireRole(['супер_админ', 'генеральный_директор', 'логист']);
        $input = json_decode(file_get_contents('php://input'), true);
        
        $carrier = $this->carrierModel->findById($id);
        
        if (!$carrier) {
            http_response_code(404);
            echo json_encode(['error' => 'Carrier not found']);
            return;
        }
        
        try {
            $updated = $this->carrierModel->update($id, $input);
            $response = $this->carrierModel->toArray($updated);
            echo json_encode($response);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to update carrier: ' . $e->getMessage()]);
        }
    }
    
    public function deleteCarrier($id) {
        $this->requireRole(['супер_админ', 'генеральный_директор']);
        
        $success = $this->carrierModel->delete($id);
        
        if ($success) {
            echo json_encode(['message' => 'Carrier deleted successfully']);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Carrier not found']);
        }
    }
}
?>