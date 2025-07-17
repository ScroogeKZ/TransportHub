<?php
class ShipmentController extends AuthController {
    private $shipmentModel;
    
    public function __construct() {
        parent::__construct();
        $this->shipmentModel = new Shipment();
    }
    
    public function getAllShipments() {
        $this->requireAuth();
        
        $shipments = $this->shipmentModel->getAll();
        
        $response = array_map(function($shipment) {
            return $this->shipmentModel->toArray($shipment);
        }, $shipments);
        
        echo json_encode($response);
    }
    
    public function createShipment() {
        $this->requireRole(['супер_админ', 'генеральный_директор', 'логист']);
        $input = json_decode(file_get_contents('php://input'), true);
        
        $required = ['requestId', 'carrierId', 'routeId', 'pickupDate', 'deliveryDate'];
        foreach ($required as $field) {
            if (!isset($input[$field]) || empty($input[$field])) {
                http_response_code(400);
                echo json_encode(['error' => "Field '$field' is required"]);
                return;
            }
        }
        
        try {
            $shipment = $this->shipmentModel->create($input);
            $response = $this->shipmentModel->toArray($shipment);
            echo json_encode($response);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to create shipment: ' . $e->getMessage()]);
        }
    }
    
    public function updateShipment($id) {
        $this->requireRole(['супер_админ', 'генеральный_директор', 'логист']);
        $input = json_decode(file_get_contents('php://input'), true);
        
        $shipment = $this->shipmentModel->findById($id);
        
        if (!$shipment) {
            http_response_code(404);
            echo json_encode(['error' => 'Shipment not found']);
            return;
        }
        
        try {
            $updated = $this->shipmentModel->update($id, $input);
            $response = $this->shipmentModel->toArray($updated);
            echo json_encode($response);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to update shipment: ' . $e->getMessage()]);
        }
    }
}
?>