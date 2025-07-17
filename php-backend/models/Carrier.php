<?php
class Carrier {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function create($data) {
        $sql = "INSERT INTO carriers (id, name, contact_person, phone, email, address, rating, services, created_at, updated_at) 
                VALUES (:id, :name, :contact_person, :phone, :email, :address, :rating, :services, NOW(), NOW()) 
                RETURNING *";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':id' => $this->generateId(),
            ':name' => $data['name'],
            ':contact_person' => $data['contactPerson'],
            ':phone' => $data['phone'],
            ':email' => $data['email'],
            ':address' => $data['address'],
            ':rating' => $data['rating'] ?? 0,
            ':services' => json_encode($data['services'] ?? [])
        ]);
        
        return $stmt->fetch();
    }
    
    public function getAll() {
        $sql = "SELECT * FROM carriers ORDER BY rating DESC, name ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function findById($id) {
        $sql = "SELECT * FROM carriers WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
    
    public function update($id, $data) {
        $fields = [];
        $params = [':id' => $id];
        
        foreach ($data as $key => $value) {
            if ($key === 'services') {
                $fields[] = "services = :services";
                $params[':services'] = json_encode($value);
            } else {
                $field_name = $this->camelToSnake($key);
                $fields[] = "$field_name = :$field_name";
                $params[":$field_name"] = $value;
            }
        }
        
        $fields[] = "updated_at = NOW()";
        
        $sql = "UPDATE carriers SET " . implode(', ', $fields) . " WHERE id = :id RETURNING *";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetch();
    }
    
    public function delete($id) {
        $sql = "DELETE FROM carriers WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
    
    private function generateId() {
        return 'carrier_' . time() . '_' . bin2hex(random_bytes(6));
    }
    
    private function camelToSnake($input) {
        return strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $input));
    }
    
    public function toArray($carrier) {
        if (!$carrier) return null;
        
        return [
            'id' => $carrier['id'],
            'name' => $carrier['name'],
            'contactPerson' => $carrier['contact_person'],
            'phone' => $carrier['phone'],
            'email' => $carrier['email'],
            'address' => $carrier['address'],
            'rating' => (float)$carrier['rating'],
            'services' => json_decode($carrier['services'], true) ?? [],
            'createdAt' => $carrier['created_at'],
            'updatedAt' => $carrier['updated_at']
        ];
    }
}
?>