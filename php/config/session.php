<?php
/**
 * Session configuration
 */

// Start session with secure settings
function initSession() {
    if (session_status() === PHP_SESSION_NONE) {
        // Configure session settings
        ini_set('session.cookie_httponly', 1);
        ini_set('session.use_only_cookies', 1);
        ini_set('session.cookie_secure', isset($_SERVER['HTTPS']) ? 1 : 0);
        ini_set('session.cookie_samesite', 'Lax');
        
        // Set session lifetime (24 hours)
        ini_set('session.gc_maxlifetime', 86400);
        ini_set('session.cookie_lifetime', 86400);
        
        session_start();
    }
}

function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

function requireAuth() {
    if (!isLoggedIn()) {
        http_response_code(401);
        echo json_encode(['message' => 'Unauthorized']);
        exit;
    }
}

function getCurrentUser() {
    if (!isLoggedIn()) {
        return null;
    }
    
    try {
        $db = new Database();
        $pdo = $db->getPdo();
        
        $stmt = $pdo->prepare("SELECT id, email, first_name, last_name, role, profile_image_url, created_at, updated_at FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        
        return $stmt->fetch();
    } catch (Exception $e) {
        return null;
    }
}

function setUserSession($user) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['user_role'] = $user['role'];
}

function destroySession() {
    session_destroy();
    unset($_SESSION);
}