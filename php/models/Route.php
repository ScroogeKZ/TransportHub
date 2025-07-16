<?php
/**
 * Route model for managing transportation routes
 */

class Route {
    private $db;
    private $pdo;

    public function __construct() {
        $this->db = new Database();
        $this->pdo = $this->db->getPdo();
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM routes ORDER BY name");
        return $stmt->fetchAll();
    }

    public function findById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM routes WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $stmt = $this->pdo->prepare("
            INSERT INTO routes (
                name, from_city, to_city, distance, estimated_time, 
                toll_cost, fuel_cost, is_optimized, created_at, updated_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
        ");

        $stmt->execute([
            $data['name'],
            $data['from_city'],
            $data['to_city'],
            $data['distance'],
            $data['estimated_time'],
            $data['toll_cost'] ?? 0,
            $data['fuel_cost'] ?? 0,
            $data['is_optimized'] ?? 0
        ]);

        $id = $this->pdo->lastInsertId();
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
            $sql = "UPDATE routes SET " . implode(', ', $fields) . ", updated_at = NOW() WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($values);
        }

        return $this->findById($id);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM routes WHERE id = ?");
        return $stmt->execute([$id]);
    }
}