<?php
require_once 'config/database.php';

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function getCurrentUser() {
    if (!isLoggedIn()) return null;
    
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch();
}

function login($email, $password) {
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role'] = $user['role'];
        return true;
    }
    return false;
}

function register($email, $password, $firstName, $lastName, $role = 'прораб') {
    $db = getDB();
    
    // Check if user exists
    $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        return false; // User already exists
    }
    
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $userId = 'user_' . time() . '_' . substr(str_shuffle('abcdefghijklmnopqrstuvwxyz'), 0, 9);
    
    $stmt = $db->prepare("
        INSERT INTO users (id, email, password, \"firstName\", \"lastName\", role, \"createdAt\", \"updatedAt\") 
        VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())
    ");
    
    return $stmt->execute([$userId, $email, $hashedPassword, $firstName, $lastName, $role]);
}

function logout() {
    session_destroy();
    header('Location: index.php?page=login');
    exit;
}

// Handle auth requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'login':
                if (login($_POST['email'], $_POST['password'])) {
                    header('Location: index.php?page=dashboard');
                } else {
                    $login_error = "Неверные учетные данные";
                }
                break;
                
            case 'register':
                if (register($_POST['email'], $_POST['password'], $_POST['firstName'], $_POST['lastName'])) {
                    if (login($_POST['email'], $_POST['password'])) {
                        header('Location: index.php?page=dashboard');
                    }
                } else {
                    $register_error = "Ошибка регистрации или пользователь уже существует";
                }
                break;
                
            case 'logout':
                logout();
                break;
        }
    }
}
?>