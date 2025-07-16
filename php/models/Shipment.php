<?php
/**
 * Shipment model for tracking deliveries
 */

class Shipment {
    private $db;
    private $pdo;

    public function __construct() {
        $this->db = new Database();
        $this->pdo = $this->db->getPdo();
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM shipments ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    public function findById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM shipments WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function findByRequestId($requestId) {
        $stmt = $this->pdo->prepare("SELECT * FROM shipments WHERE request_id = ?");
        $stmt->execute([$requestId]);
        return $stmt->fetch();
    }

    public function create($data) {
        $stmt = $this->pdo->prepare("
            INSERT INTO shipments (
                request_id, driver_name, driver_phone, vehicle_number, 
                carrier_id, route_id, status, current_location, progress,
                estimated_arrival, created_at, updated_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
        ");

        $stmt->execute([
            $data['request_id'],
            $data['driver_name'],
            $data['driver_phone'] ?? null,
            $data['vehicle_number'],
            $data['carrier_id'] ?? null,
            $data['route_id'] ?? null,
            $data['status'] ?? 'in_transit',
            $data['current_location'] ?? null,
            $data['progress'] ?? 0,
            $data['estimated_arrival'] ?? null
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
            $sql = "UPDATE shipments SET " . implode(', ', $fields) . ", updated_at = NOW() WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($values);
        }

        return $this->findById($id);
    }

    public function getTrackingPoints($shipmentId) {
        $stmt = $this->pdo->prepare("SELECT * FROM tracking_points WHERE shipment_id = ? ORDER BY timestamp DESC");
        $stmt->execute([$shipmentId]);
        return $stmt->fetchAll();
    }

    public function addTrackingPoint($data) {
        $stmt = $this->pdo->prepare("
            INSERT INTO tracking_points (
                shipment_id, timestamp, location, coordinates, 
                speed, fuel_level, status, notes
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->execute([
            $data['shipment_id'],
            $data['timestamp'] ?? date('Y-m-d H:i:s'),
            $data['location'],
            $data['coordinates'] ?? null,
            $data['speed'] ?? 0,
            $data['fuel_level'] ?? null,
            $data['status'],
            $data['notes'] ?? null
        ]);

        return $this->pdo->lastInsertId();
    }
}