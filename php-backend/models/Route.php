<?php
class Route {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function create($data) {
        $sql = "INSERT INTO routes (id, name, origin, destination, distance, estimated_time, cost_per_km, created_at, updated_at) 
                VALUES (:id, :name, :origin, :destination, :distance, :estimated_time, :cost_per_km, NOW(), NOW()) 
                RETURNING *";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':id' => $this->generateId(),
            ':name' => $data['name'],
            ':origin' => $data['origin'],
            ':destination' => $data['destination'],
            ':distance' => $data['distance'],
            ':estimated_time' => $data['estimatedTime'],
            ':cost_per_km' => $data['costPerKm']
        ]);
        
        return $stmt->fetch();
    }
    
    public function getAll() {
        $sql = "SELECT * FROM routes ORDER BY name ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function findById($id) {
        $sql = "SELECT * FROM routes WHERE id = :id";
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
        
        $sql = "UPDATE routes SET " . implode(', ', $fields) . " WHERE id = :id RETURNING *";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetch();
    }
    
    public function delete($id) {
        $sql = "DELETE FROM routes WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
    
    private function generateId() {
        return 'route_' . time() . '_' . bin2hex(random_bytes(6));
    }
    
    private function camelToSnake($input) {
        return strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $input));
    }
    
    public function toArray($route) {
        if (!$route) return null;
        
        return [
            'id' => $route['id'],
            'name' => $route['name'],
            'origin' => $route['origin'],
            'destination' => $route['destination'],
            'distance' => (float)$route['distance'],
            'estimatedTime' => $route['estimated_time'],
            'costPerKm' => (float)$route['cost_per_km'],
            'createdAt' => $route['created_at'],
            'updatedAt' => $route['updated_at']
        ];
    }
}
?>