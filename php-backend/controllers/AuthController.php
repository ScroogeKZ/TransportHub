<?php
class AuthController {
    private $userModel;
    
    public function __construct() {
        $this->userModel = new User();
    }
    
    public function login() {
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($input['email']) || !isset($input['password'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Email and password are required']);
            return;
        }
        
        $user = $this->userModel->findByEmail($input['email']);
        
        if (!$user || !$this->userModel->verifyPassword($input['password'], $user['password_hash'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Invalid credentials']);
            return;
        }
        
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_role'] = $user['role'];
        
        $response = $this->userModel->toArray($user);
        echo json_encode($response);
    }
    
    public function register() {
        $input = json_decode(file_get_contents('php://input'), true);
        
        $required = ['email', 'password', 'firstName', 'lastName'];
        foreach ($required as $field) {
            if (!isset($input[$field]) || empty($input[$field])) {
                http_response_code(400);
                echo json_encode(['error' => "Field '$field' is required"]);
                return;
            }
        }
        
        // Check if user already exists
        if ($this->userModel->findByEmail($input['email'])) {
            http_response_code(409);
            echo json_encode(['error' => 'User with this email already exists']);
            return;
        }
        
        // Validate password length
        if (strlen($input['password']) < 6) {
            http_response_code(400);
            echo json_encode(['error' => 'Password must be at least 6 characters']);
            return;
        }
        
        try {
            $user = $this->userModel->create($input);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_role'] = $user['role'];
            
            $response = $this->userModel->toArray($user);
            echo json_encode($response);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to create user: ' . $e->getMessage()]);
        }
    }
    
    public function logout() {
        session_destroy();
        echo json_encode(['message' => 'Logged out successfully']);
    }
    
    public function getCurrentUser() {
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['message' => 'Unauthorized']);
            return;
        }
        
        $user = $this->userModel->findById($_SESSION['user_id']);
        
        if (!$user) {
            http_response_code(401);
            echo json_encode(['message' => 'User not found']);
            return;
        }
        
        $response = $this->userModel->toArray($user);
        echo json_encode($response);
    }
    
    protected function requireAuth() {
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['message' => 'Unauthorized']);
            exit;
        }
        
        return $_SESSION['user_id'];
    }
    
    protected function requireRole($allowedRoles) {
        $userId = $this->requireAuth();
        
        if (!in_array($_SESSION['user_role'], $allowedRoles)) {
            http_response_code(403);
            echo json_encode(['message' => 'Forbidden']);
            exit;
        }
        
        return $userId;
    }
}
?>