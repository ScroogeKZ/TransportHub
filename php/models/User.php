<?php
/**
 * User model for handling user operations
 */

class User {
    private $db;
    private $pdo;

    public function __construct() {
        $this->db = new Database();
        $this->pdo = $this->db->getPdo();
    }

    public function findByEmail($email) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public function findById($id) {
        $stmt = $this->pdo->prepare("SELECT id, email, first_name, last_name, role, profile_image_url, created_at, updated_at FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $id = 'user_' . time() . '_' . bin2hex(random_bytes(5));
        
        $stmt = $this->pdo->prepare("
            INSERT INTO users (id, email, password, first_name, last_name, role, created_at, updated_at) 
            VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())
        ");
        
        $stmt->execute([
            $id,
            $data['email'],
            $data['password'],
            $data['first_name'],
            $data['last_name'],
            $data['role'] ?? 'прораб'
        ]);

        return $this->findById($id);
    }

    public function update($id, $data) {
        $fields = [];
        $values = [];
        
        foreach ($data as $key => $value) {
            if ($key !== 'id' && $value !== null) {
                $fields[] = $key . " = ?";
                $values[] = $value;
            }
        }
        
        if (!empty($fields)) {
            $values[] = $id;
            $sql = "UPDATE users SET " . implode(', ', $fields) . ", updated_at = NOW() WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($values);
        }

        return $this->findById($id);
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT id, email, first_name, last_name, role, profile_image_url, created_at, updated_at FROM users ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    public function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }

    public function hashPassword($password) {
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
    }
}