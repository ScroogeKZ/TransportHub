<?php
class RouteController extends AuthController {
    private $routeModel;
    
    public function __construct() {
        parent::__construct();
        $this->routeModel = new Route();
    }
    
    public function getAllRoutes() {
        $this->requireAuth();
        
        $routes = $this->routeModel->getAll();
        
        $response = array_map(function($route) {
            return $this->routeModel->toArray($route);
        }, $routes);
        
        echo json_encode($response);
    }
    
    public function createRoute() {
        $this->requireRole(['супер_админ', 'генеральный_директор', 'логист']);
        $input = json_decode(file_get_contents('php://input'), true);
        
        $required = ['name', 'origin', 'destination', 'distance', 'estimatedTime', 'costPerKm'];
        foreach ($required as $field) {
            if (!isset($input[$field]) || empty($input[$field])) {
                http_response_code(400);
                echo json_encode(['error' => "Field '$field' is required"]);
                return;
            }
        }
        
        try {
            $route = $this->routeModel->create($input);
            $response = $this->routeModel->toArray($route);
            echo json_encode($response);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to create route: ' . $e->getMessage()]);
        }
    }
    
    public function updateRoute($id) {
        $this->requireRole(['супер_админ', 'генеральный_директор', 'логист']);
        $input = json_decode(file_get_contents('php://input'), true);
        
        $route = $this->routeModel->findById($id);
        
        if (!$route) {
            http_response_code(404);
            echo json_encode(['error' => 'Route not found']);
            return;
        }
        
        try {
            $updated = $this->routeModel->update($id, $input);
            $response = $this->routeModel->toArray($updated);
            echo json_encode($response);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to update route: ' . $e->getMessage()]);
        }
    }
    
    public function deleteRoute($id) {
        $this->requireRole(['супер_админ', 'генеральный_директор']);
        
        $success = $this->routeModel->delete($id);
        
        if ($success) {
            echo json_encode(['message' => 'Route deleted successfully']);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Route not found']);
        }
    }
}
?>