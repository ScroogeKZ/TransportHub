<?php
class User {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function create($data) {
        $sql = "INSERT INTO users (id, email, password_hash, first_name, last_name, role, created_at, updated_at) 
                VALUES (:id, :email, :password_hash, :first_name, :last_name, :role, NOW(), NOW()) 
                RETURNING *";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':id' => $this->generateId(),
            ':email' => $data['email'],
            ':password_hash' => password_hash($data['password'], PASSWORD_DEFAULT),
            ':first_name' => $data['firstName'],
            ':last_name' => $data['lastName'],
            ':role' => $data['role'] ?? 'прораб'
        ]);
        
        return $stmt->fetch();
    }
    
    public function findByEmail($email) {
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':email' => $email]);
        return $stmt->fetch();
    }
    
    public function findById($id) {
        $sql = "SELECT * FROM users WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
    
    public function getAll() {
        $sql = "SELECT id, email, first_name, last_name, role, created_at, updated_at FROM users ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function update($id, $data) {
        $fields = [];
        $params = [':id' => $id];
        
        foreach ($data as $key => $value) {
            if ($key === 'password') {
                $fields[] = "password_hash = :password_hash";
                $params[':password_hash'] = password_hash($value, PASSWORD_DEFAULT);
            } else {
                $field_name = $this->camelToSnake($key);
                $fields[] = "$field_name = :$field_name";
                $params[":$field_name"] = $value;
            }
        }
        
        $fields[] = "updated_at = NOW()";
        
        $sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE id = :id RETURNING *";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetch();
    }
    
    public function delete($id) {
        $sql = "DELETE FROM users WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
    
    public function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }
    
    private function generateId() {
        return 'user_' . time() . '_' . bin2hex(random_bytes(8));
    }
    
    private function camelToSnake($input) {
        return strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $input));
    }
    
    public function toArray($user) {
        if (!$user) return null;
        
        return [
            'id' => $user['id'],
            'email' => $user['email'],
            'firstName' => $user['first_name'],
            'lastName' => $user['last_name'],
            'profileImageUrl' => $user['profile_image_url'],
            'role' => $user['role'],
            'createdAt' => $user['created_at'],
            'updatedAt' => $user['updated_at']
        ];
    }
}
?>