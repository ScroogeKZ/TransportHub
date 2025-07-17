<?php
session_start();

// Load environment variables
$host = $_ENV['PGHOST'] ?? 'localhost';
$port = $_ENV['PGPORT'] ?? '5432';
$dbname = $_ENV['PGDATABASE'] ?? 'main';
$username = $_ENV['PGUSER'] ?? 'postgres';
$password = $_ENV['PGPASSWORD'] ?? '';

// Simple database connection
try {
    $pdo = new PDO("pgsql:host={$host};port={$port};dbname={$dbname}", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Simple auth functions
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function getCurrentUser() {
    global $pdo;
    if (!isLoggedIn()) return null;
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch();
}

function login($email, $password) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
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

function getPageTitle($page) {
    $titles = [
        'login' => 'Вход в систему',
        'dashboard' => 'Панель управления',
        'requests' => 'Заявки на перевозку',
        'create' => 'Создать заявку'
    ];
    return $titles[$page] ?? 'TransportHub';
}

// Handle auth
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'login') {
        if (login($_POST['email'], $_POST['password'])) {
            header('Location: index.php?page=dashboard');
            exit;
        } else {
            $login_error = "Неверные учетные данные";
        }
    }
}

// Simple routing
$page = $_GET['page'] ?? 'dashboard';

// Check authentication for protected pages
$protected_pages = ['dashboard', 'requests', 'create', 'users', 'carriers', 'routes', 'calculator', 'tracking'];
if (in_array($page, $protected_pages) && !isLoggedIn()) {
    $page = 'login';
}

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo getPageTitle($page); ?> - Хром-KZ</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <style>
        /* Dark Theme Base */
        body { 
            background: linear-gradient(135deg, #111827 0%, #1f2937 50%, #000000 100%);
            color: #ffffff;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }
        
        .dark-gradient-bg { 
            background: linear-gradient(135deg, #111827 0%, #1f2937 50%, #000000 100%); 
        }
        
        .glass-card-dark { 
            backdrop-filter: blur(20px); 
            background: rgba(31, 41, 55, 0.4); 
            border: 1px solid rgba(75, 85, 99, 0.3);
        }
        
        .btn-modern { 
            background: linear-gradient(135deg, #10b981 0%, #3b82f6 100%); 
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .btn-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 40px -12px rgba(16, 185, 129, 0.4);
        }
        
        .animate-fade-in { 
            animation: fadeInUp 0.8s cubic-bezier(0.4, 0, 0.2, 1); 
        }
        
        @keyframes fadeInUp { 
            from { 
                opacity: 0; 
                transform: translateY(40px); 
            } 
            to { 
                opacity: 1; 
                transform: translateY(0); 
            } 
        }
        
        /* Modern effects */
        .neon-glow {
            box-shadow: 
                0 0 20px rgba(16, 185, 129, 0.3),
                0 0 40px rgba(16, 185, 129, 0.2),
                0 0 80px rgba(16, 185, 129, 0.1);
        }
        
        .modern-card {
            background: rgba(17, 24, 39, 0.7);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(75, 85, 99, 0.3);
            transition: all 0.3s ease;
        }
        
        .modern-card:hover {
            background: rgba(17, 24, 39, 0.8);
            border-color: rgba(16, 185, 129, 0.3);
            transform: translateY(-4px);
        }
        
        /* Grid pattern background */
        .bg-grid {
            background-image: 
                linear-gradient(rgba(255,255,255,0.1) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.1) 1px, transparent 1px);
            background-size: 20px 20px;
        }
    </style>
</head>
<body class="dark-gradient-bg bg-grid min-h-screen text-white">
    <?php if (isLoggedIn()): ?>
        <?php include 'views/layout/header.php'; ?>
        <div class="flex">
            <?php include 'views/layout/sidebar.php'; ?>
            <main class="flex-1 p-6 bg-gray-900/20">
                <?php include "views/pages/{$page}.php"; ?>
            </main>
        </div>
    <?php else: ?>
        <?php include "views/pages/{$page}.php"; ?>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="assets/js/app.js"></script>
</body>
</html>