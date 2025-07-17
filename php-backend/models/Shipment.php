<?php
class Shipment {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function create($data) {
        $sql = "INSERT INTO shipments (id, request_id, carrier_id, route_id, tracking_number, status, pickup_date, delivery_date, cost, created_at, updated_at) 
                VALUES (:id, :request_id, :carrier_id, :route_id, :tracking_number, :status, :pickup_date, :delivery_date, :cost, NOW(), NOW()) 
                RETURNING *";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':id' => $this->generateId(),
            ':request_id' => $data['requestId'],
            ':carrier_id' => $data['carrierId'],
            ':route_id' => $data['routeId'],
            ':tracking_number' => $this->generateTrackingNumber(),
            ':status' => $data['status'] ?? 'pending',
            ':pickup_date' => $data['pickupDate'],
            ':delivery_date' => $data['deliveryDate'],
            ':cost' => $data['cost'] ?? 0
        ]);
        
        return $stmt->fetch();
    }
    
    public function getAll() {
        $sql = "SELECT s.*, tr.request_number, c.name as carrier_name, r.name as route_name
                FROM shipments s
                LEFT JOIN transportation_requests tr ON s.request_id = tr.id
                LEFT JOIN carriers c ON s.carrier_id = c.id
                LEFT JOIN routes r ON s.route_id = r.id
                ORDER BY s.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function findById($id) {
        $sql = "SELECT s.*, tr.request_number, c.name as carrier_name, r.name as route_name
                FROM shipments s
                LEFT JOIN transportation_requests tr ON s.request_id = tr.id
                LEFT JOIN carriers c ON s.carrier_id = c.id
                LEFT JOIN routes r ON s.route_id = r.id
                WHERE s.id = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
    
    public function update($id, $data) {
        $fields = [];
        $params = [':id' => $id];
        
        foreach ($data as $key => $value) {
            $field_name = $this->camelToSnake($key);
            $fields[] = "$field_name = :$field_name";
            $params[":$field_name"] = $value;
        }
        
        $fields[] = "updated_at = NOW()";
        
        $sql = "UPDATE shipments SET " . implode(', ', $fields) . " WHERE id = :id RETURNING *";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetch();
    }
    
    public function delete($id) {
        $sql = "DELETE FROM shipments WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
    
    private function generateId() {
        return 'shipment_' . time() . '_' . bin2hex(random_bytes(6));
    }
    
    private function generateTrackingNumber() {
        return 'TRK-' . date('Y') . '-' . strtoupper(bin2hex(random_bytes(4)));
    }
    
    private function camelToSnake($input) {
        return strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $input));
    }
    
    public function toArray($shipment) {
        if (!$shipment) return null;
        
        return [
            'id' => $shipment['id'],
            'requestId' => $shipment['request_id'],
            'carrierId' => $shipment['carrier_id'],
            'routeId' => $shipment['route_id'],
            'trackingNumber' => $shipment['tracking_number'],
            'status' => $shipment['status'],
            'pickupDate' => $shipment['pickup_date'],
            'deliveryDate' => $shipment['delivery_date'],
            'cost' => (float)$shipment['cost'],
            'createdAt' => $shipment['created_at'],
            'updatedAt' => $shipment['updated_at'],
            'requestNumber' => $shipment['request_number'] ?? null,
            'carrierName' => $shipment['carrier_name'] ?? null,
            'routeName' => $shipment['route_name'] ?? null
        ];
    }
}
?>