<?php
/**
 * Transportation Request model
 */

class TransportationRequest {
    private $db;
    private $pdo;

    public function __construct() {
        $this->db = new Database();
        $this->pdo = $this->db->getPdo();
    }

    public function getAll($userId, $userRole) {
        if ($userRole === 'генеральный_директор' || $userRole === 'супер_админ') {
            $stmt = $this->pdo->query("SELECT * FROM transportation_requests ORDER BY created_at DESC");
            return $stmt->fetchAll();
        } else {
            $stmt = $this->pdo->prepare("SELECT * FROM transportation_requests WHERE created_by_id = ? ORDER BY created_at DESC");
            $stmt->execute([$userId]);
            return $stmt->fetchAll();
        }
    }

    public function findById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM transportation_requests WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        // Generate request number
        $stmt = $this->pdo->query("SELECT COUNT(*) as count FROM transportation_requests");
        $count = $stmt->fetch()['count'];
        $requestNumber = 'REQ-' . str_pad($count + 1, 4, '0', STR_PAD_LEFT);

        $stmt = $this->pdo->prepare("
            INSERT INTO transportation_requests (
                request_number, from_city, from_address, to_city, to_address, 
                cargo_type, weight, width, length, height, description, 
                transport_type, urgency, status, created_by_id, created_at, updated_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
        ");

        $stmt->execute([
            $requestNumber,
            $data['from_city'],
            $data['from_address'] ?? '',
            $data['to_city'],
            $data['to_address'] ?? '',
            $data['cargo_type'],
            $data['weight'] ?? '0',
            $data['width'] ?? '0',
            $data['length'] ?? '0',
            $data['height'] ?? '0',
            $data['description'] ?? '',
            $data['transport_type'] ?? 'auto',
            $data['urgency'] ?? 'normal',
            'created',
            $data['created_by_id']
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
            $sql = "UPDATE transportation_requests SET " . implode(', ', $fields) . ", updated_at = NOW() WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($values);
        }

        return $this->findById($id);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM transportation_requests WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getDashboardStats() {
        $stmt = $this->pdo->query("
            SELECT 
                COUNT(*) as total_transportations,
                COALESCE(SUM(CAST(estimated_cost as DECIMAL)), 0) as total_expenses,
                COUNT(CASE WHEN status IN ('created', 'logistics', 'manager', 'financial') THEN 1 END) as active_requests,
                COALESCE(AVG(CAST(estimated_cost as DECIMAL)), 0) as average_cost
            FROM transportation_requests
        ");
        
        return $stmt->fetch();
    }

    public function getMonthlyStats() {
        $stmt = $this->pdo->query("
            SELECT 
                TO_CHAR(created_at, 'YYYY-MM') as month,
                COUNT(*) as count,
                COALESCE(SUM(CAST(estimated_cost as DECIMAL)), 0) as amount
            FROM transportation_requests 
            WHERE created_at >= CURRENT_DATE - INTERVAL '12 months'
            GROUP BY TO_CHAR(created_at, 'YYYY-MM')
            ORDER BY month
        ");
        
        return $stmt->fetchAll();
    }

    public function getStatusStats() {
        $stmt = $this->pdo->query("
            SELECT status, COUNT(*) as count 
            FROM transportation_requests 
            GROUP BY status
        ");
        
        return $stmt->fetchAll();
    }
}