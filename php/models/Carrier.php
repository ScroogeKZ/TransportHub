<?php
/**
 * Carrier model for managing transport companies
 */

class Carrier {
    private $db;
    private $pdo;

    public function __construct() {
        $this->db = new Database();
        $this->pdo = $this->db->getPdo();
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM carriers ORDER BY name");
        return $stmt->fetchAll();
    }

    public function findById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM carriers WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $stmt = $this->pdo->prepare("
            INSERT INTO carriers (
                name, contact_person, phone, email, address, 
                transport_types, rating, price_range, notes, 
                is_active, created_at, updated_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 1, NOW(), NOW())
        ");

        $transportTypes = is_array($data['transport_types']) ? $data['transport_types'] : [];
        
        $stmt->execute([
            $data['name'],
            $data['contact_person'],
            $data['phone'],
            $data['email'] ?? null,
            $data['address'],
            '{' . implode(',', array_map(function($type) { return '"' . addslashes($type) . '"'; }, $transportTypes)) . '}',
            $data['rating'] ?? 5,
            $data['price_range'] ?? '',
            $data['notes'] ?? ''
        ]);

        $id = $this->pdo->lastInsertId();
        return $this->findById($id);
    }

    public function update($id, $data) {
        $fields = [];
        $values = [];
        
        foreach ($data as $key => $value) {
            if ($key !== 'id' && $value !== null) {
                if ($key === 'transport_types' && is_array($value)) {
                    $fields[] = "transport_types = ?";
                    $values[] = '{' . implode(',', array_map(function($type) { return '"' . addslashes($type) . '"'; }, $value)) . '}';
                } else {
                    $fields[] = $key . " = ?";
                    $values[] = $value;
                }
            }
        }
        
        if (!empty($fields)) {
            $values[] = $id;
            $sql = "UPDATE carriers SET " . implode(', ', $fields) . ", updated_at = NOW() WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($values);
        }

        return $this->findById($id);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM carriers WHERE id = ?");
        return $stmt->execute([$id]);
    }
}