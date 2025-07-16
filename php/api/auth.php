<?php
/**
 * Authentication API endpoints
 */

require_once '../config/database.php';
require_once '../config/session.php';
require_once '../models/User.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

initSession();

$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$pathSegments = explode('/', trim($path, '/'));

// Get the action from the URL
$action = end($pathSegments);

try {
    $userModel = new User();

    switch ($method) {
        case 'POST':
            $input = json_decode(file_get_contents('php://input'), true);
            
            if ($action === 'login') {
                if (!isset($input['email']) || !isset($input['password'])) {
                    http_response_code(400);
                    echo json_encode(['message' => 'Email и пароль обязательны']);
                    exit;
                }

                $user = $userModel->findByEmail($input['email']);
                if (!$user || !$userModel->verifyPassword($input['password'], $user['password'])) {
                    http_response_code(401);
                    echo json_encode(['message' => 'Неверные учетные данные']);
                    exit;
                }

                setUserSession($user);
                
                // Return user without password
                unset($user['password']);
                echo json_encode($user);
                
            } elseif ($action === 'register') {
                if (!isset($input['email']) || !isset($input['password']) || 
                    !isset($input['first_name']) || !isset($input['last_name'])) {
                    http_response_code(400);
                    echo json_encode(['message' => 'Все поля обязательны']);
                    exit;
                }

                // Check if user exists
                if ($userModel->findByEmail($input['email'])) {
                    http_response_code(400);
                    echo json_encode(['message' => 'Пользователь с таким email уже существует']);
                    exit;
                }

                $hashedPassword = $userModel->hashPassword($input['password']);
                $userData = [
                    'email' => $input['email'],
                    'password' => $hashedPassword,
                    'first_name' => $input['first_name'],
                    'last_name' => $input['last_name'],
                    'role' => $input['role'] ?? 'прораб'
                ];

                $user = $userModel->create($userData);
                setUserSession($user);

                echo json_encode($user);
                
            } elseif ($action === 'logout') {
                destroySession();
                echo json_encode(['message' => 'Logged out successfully']);
            }
            break;

        case 'GET':
            if ($action === 'user') {
                $user = getCurrentUser();
                if ($user) {
                    echo json_encode($user);
                } else {
                    http_response_code(401);
                    echo json_encode(['message' => 'Unauthorized']);
                }
            }
            break;

        default:
            http_response_code(405);
            echo json_encode(['message' => 'Method not allowed']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['message' => 'Internal server error: ' . $e->getMessage()]);
}