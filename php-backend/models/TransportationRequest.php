<?php
class TransportationRequest {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function create($data) {
        $sql = "INSERT INTO transportation_requests (
                    id, request_number, user_id, cargo_type, cargo_weight, cargo_dimensions_width, 
                    cargo_dimensions_length, cargo_dimensions_height, pickup_date, delivery_date, 
                    pickup_address, delivery_address, destination_city, status, comments, created_at, updated_at
                ) VALUES (
                    :id, :request_number, :user_id, :cargo_type, :cargo_weight, :cargo_dimensions_width,
                    :cargo_dimensions_length, :cargo_dimensions_height, :pickup_date, :delivery_date,
                    :pickup_address, :delivery_address, :destination_city, :status, :comments, NOW(), NOW()
                ) RETURNING *";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':id' => $this->generateId(),
            ':request_number' => $this->generateRequestNumber(),
            ':user_id' => $data['userId'],
            ':cargo_type' => $data['cargoType'],
            ':cargo_weight' => $data['cargoWeight'],
            ':cargo_dimensions_width' => $data['cargoDimensions']['width'] ?? null,
            ':cargo_dimensions_length' => $data['cargoDimensions']['length'] ?? null,
            ':cargo_dimensions_height' => $data['cargoDimensions']['height'] ?? null,
            ':pickup_date' => $data['pickupDate'],
            ':delivery_date' => $data['deliveryDate'],
            ':pickup_address' => $data['pickupAddress'],
            ':delivery_address' => $data['deliveryAddress'],
            ':destination_city' => $data['destinationCity'],
            ':status' => $data['status'] ?? 'created',
            ':comments' => $data['comments'] ?? null
        ]);
        
        return $stmt->fetch();
    }
    
    public function getAll($userId = null, $role = null) {
        $sql = "SELECT tr.*, u.first_name, u.last_name, u.email, u.role as user_role
                FROM transportation_requests tr
                JOIN users u ON tr.user_id = u.id";
        
        $params = [];
        
        // Apply role-based filtering
        if ($role && $role !== 'супер_админ' && $role !== 'генеральный_директор') {
            if ($role === 'прораб') {
                $sql .= " WHERE tr.user_id = :user_id";
                $params[':user_id'] = $userId;
            } elseif ($role === 'логист') {
                $sql .= " WHERE tr.status IN ('created', 'logistics')";
            } elseif ($role === 'руководитель_смт') {
                $sql .= " WHERE tr.status IN ('logistics', 'manager')";
            } elseif ($role === 'финансовый_директор') {
                $sql .= " WHERE tr.status IN ('manager', 'finance', 'approved')";
            }
        }
        
        $sql .= " ORDER BY tr.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    public function findById($id) {
        $sql = "SELECT tr.*, u.first_name, u.last_name, u.email, u.role as user_role
                FROM transportation_requests tr
                JOIN users u ON tr.user_id = u.id
                WHERE tr.id = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
    
    public function update($id, $data) {
        $fields = [];
        $params = [':id' => $id];
        
        foreach ($data as $key => $value) {
            if ($key === 'cargoDimensions') {
                if (isset($value['width'])) {
                    $fields[] = "cargo_dimensions_width = :cargo_dimensions_width";
                    $params[':cargo_dimensions_width'] = $value['width'];
                }
                if (isset($value['length'])) {
                    $fields[] = "cargo_dimensions_length = :cargo_dimensions_length";
                    $params[':cargo_dimensions_length'] = $value['length'];
                }
                if (isset($value['height'])) {
                    $fields[] = "cargo_dimensions_height = :cargo_dimensions_height";
                    $params[':cargo_dimensions_height'] = $value['height'];
                }
            } else {
                $field_name = $this->camelToSnake($key);
                $fields[] = "$field_name = :$field_name";
                $params[":$field_name"] = $value;
            }
        }
        
        $fields[] = "updated_at = NOW()";
        
        $sql = "UPDATE transportation_requests SET " . implode(', ', $fields) . " WHERE id = :id RETURNING *";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetch();
    }
    
    public function delete($id) {
        $sql = "DELETE FROM transportation_requests WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
    
    public function getStats() {
        $sql = "SELECT 
                    COUNT(*) as total,
                    COUNT(CASE WHEN status = 'created' THEN 1 END) as created,
                    COUNT(CASE WHEN status = 'logistics' THEN 1 END) as logistics,
                    COUNT(CASE WHEN status = 'manager' THEN 1 END) as manager,
                    COUNT(CASE WHEN status = 'finance' THEN 1 END) as finance,
                    COUNT(CASE WHEN status = 'approved' THEN 1 END) as approved,
                    COUNT(CASE WHEN status = 'rejected' THEN 1 END) as rejected
                FROM transportation_requests";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetch();
    }
    
    private function generateId() {
        return 'req_' . time() . '_' . bin2hex(random_bytes(8));
    }
    
    private function generateRequestNumber() {
        return 'TR-' . date('Y') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
    }
    
    private function camelToSnake($input) {
        return strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $input));
    }
    
    public function toArray($request) {
        if (!$request) return null;
        
        return [
            'id' => $request['id'],
            'requestNumber' => $request['request_number'],
            'userId' => $request['user_id'],
            'cargoType' => $request['cargo_type'],
            'cargoWeight' => $request['cargo_weight'],
            'cargoDimensions' => [
                'width' => $request['cargo_dimensions_width'],
                'length' => $request['cargo_dimensions_length'],
                'height' => $request['cargo_dimensions_height']
            ],
            'pickupDate' => $request['pickup_date'],
            'deliveryDate' => $request['delivery_date'],
            'pickupAddress' => $request['pickup_address'],
            'deliveryAddress' => $request['delivery_address'],
            'destinationCity' => $request['destination_city'],
            'status' => $request['status'],
            'comments' => $request['comments'],
            'createdAt' => $request['created_at'],
            'updatedAt' => $request['updated_at'],
            'user' => [
                'firstName' => $request['first_name'],
                'lastName' => $request['last_name'],
                'email' => $request['email'],
                'role' => $request['user_role']
            ]
        ];
    }
}
?>