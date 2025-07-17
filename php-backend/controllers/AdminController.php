<?php
class AdminController extends AuthController {
    private $userModel;
    private $requestModel;
    
    public function __construct() {
        parent::__construct();
        $this->userModel = new User();
        $this->requestModel = new TransportationRequest();
    }
    
    public function getAllUsers() {
        $this->requireRole(['супер_админ', 'генеральный_директор']);
        
        $users = $this->userModel->getAll();
        
        $response = array_map(function($user) {
            return $this->userModel->toArray($user);
        }, $users);
        
        echo json_encode($response);
    }
    
    public function updateUser($id) {
        $this->requireRole(['супер_админ', 'генеральный_директор']);
        $input = json_decode(file_get_contents('php://input'), true);
        
        $user = $this->userModel->findById($id);
        
        if (!$user) {
            http_response_code(404);
            echo json_encode(['error' => 'User not found']);
            return;
        }
        
        try {
            $updated = $this->userModel->update($id, $input);
            $response = $this->userModel->toArray($updated);
            echo json_encode($response);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to update user: ' . $e->getMessage()]);
        }
    }
    
    public function deleteUser($id) {
        $this->requireRole(['супер_админ']);
        
        $success = $this->userModel->delete($id);
        
        if ($success) {
            echo json_encode(['message' => 'User deleted successfully']);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'User not found']);
        }
    }
    
    public function getStats() {
        $this->requireAuth();
        
        $requestStats = $this->requestModel->getStats();
        $userStats = $this->getUserStats();
        
        $response = [
            'requests' => $requestStats,
            'users' => $userStats,
            'totalRequests' => (int)$requestStats['total'],
            'totalUsers' => (int)$userStats['total']
        ];
        
        echo json_encode($response);
    }
    
    private function getUserStats() {
        $sql = "SELECT 
                    COUNT(*) as total,
                    COUNT(CASE WHEN role = 'прораб' THEN 1 END) as foreman,
                    COUNT(CASE WHEN role = 'логист' THEN 1 END) as logistician,
                    COUNT(CASE WHEN role = 'руководитель_смт' THEN 1 END) as manager,
                    COUNT(CASE WHEN role = 'финансовый_директор' THEN 1 END) as financial,
                    COUNT(CASE WHEN role = 'генеральный_директор' THEN 1 END) as general,
                    COUNT(CASE WHEN role = 'супер_админ' THEN 1 END) as super_admin
                FROM users";
        
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare($sql);
        $stmt->execute();
        return $stmt->fetch();
    }
}
?>