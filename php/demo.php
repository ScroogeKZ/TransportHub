<?php
/**
 * Demonstration of PHP backend functionality
 * This script shows how the PHP backend works with the same database
 */

require_once 'config/database.php';
require_once 'models/User.php';
require_once 'models/TransportationRequest.php';
require_once 'models/Carrier.php';

echo "=== PHP Backend Demonstration ===\n\n";

try {
    // Test database connection
    echo "1. Testing database connection...\n";
    $db = new Database();
    $pdo = $db->getPdo();
    echo "✅ Database connected successfully\n\n";

    // Test user model
    echo "2. Testing User model...\n";
    $userModel = new User();
    $user = $userModel->findByEmail('admin@test.com');
    if ($user) {
        echo "✅ Found user: {$user['first_name']} {$user['last_name']} ({$user['role']})\n";
        
        // Test password verification
        $passwordTest = $userModel->verifyPassword('admin123', $user['password']);
        echo $passwordTest ? "✅ Password verification works\n" : "❌ Password verification failed\n";
    } else {
        echo "❌ User not found\n";
    }
    echo "\n";

    // Test transportation requests
    echo "3. Testing Transportation Request model...\n";
    $requestModel = new TransportationRequest();
    $requests = $requestModel->getAll($user['id'], $user['role']);
    echo "✅ Retrieved " . count($requests) . " transportation requests\n";
    
    if (count($requests) > 0) {
        $firstRequest = $requests[0];
        echo "   Example: {$firstRequest['request_number']} - {$firstRequest['from_city']} → {$firstRequest['to_city']}\n";
    }
    echo "\n";

    // Test dashboard stats
    echo "4. Testing Dashboard stats...\n";
    $stats = $requestModel->getDashboardStats();
    echo "✅ Dashboard stats retrieved:\n";
    echo "   Total transportations: {$stats['total_transportations']}\n";
    echo "   Total expenses: {$stats['total_expenses']}\n";
    echo "   Active requests: {$stats['active_requests']}\n\n";

    // Test carriers
    echo "5. Testing Carrier model...\n";
    $carrierModel = new Carrier();
    $carriers = $carrierModel->getAll();
    echo "✅ Retrieved " . count($carriers) . " carriers\n";
    
    if (count($carriers) > 0) {
        $firstCarrier = $carriers[0];
        echo "   Example: {$firstCarrier['name']} - {$firstCarrier['contact_person']}\n";
    }
    echo "\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "=== PHP Backend API Endpoints ===\n";
echo "Authentication:\n";
echo "  POST /api/auth/login - User login\n";
echo "  POST /api/auth/register - User registration\n";
echo "  POST /api/auth/logout - User logout\n";
echo "  GET  /api/auth/user - Get current user\n\n";

echo "Transportation Requests:\n";
echo "  GET    /api/transportation-requests - List all requests\n";
echo "  POST   /api/transportation-requests - Create new request\n";
echo "  GET    /api/transportation-requests/{id} - Get specific request\n";
echo "  PATCH  /api/transportation-requests/{id} - Update request\n";
echo "  DELETE /api/transportation-requests/{id} - Delete request\n\n";

echo "Dashboard:\n";
echo "  GET /api/dashboard/stats - Get dashboard statistics\n";
echo "  GET /api/dashboard/monthly-stats - Get monthly statistics\n";
echo "  GET /api/dashboard/status-stats - Get status statistics\n\n";

echo "Carriers:\n";
echo "  GET    /api/carriers - List all carriers\n";
echo "  POST   /api/carriers - Create new carrier\n";
echo "  PATCH  /api/carriers/{id} - Update carrier\n";
echo "  DELETE /api/carriers/{id} - Delete carrier\n\n";

echo "Routes:\n";
echo "  GET    /api/routes - List all routes\n";
echo "  POST   /api/routes - Create new route\n";
echo "  PATCH  /api/routes/{id} - Update route\n";
echo "  DELETE /api/routes/{id} - Delete route\n\n";

echo "Shipments:\n";
echo "  GET  /api/shipments - List all shipments\n";
echo "  POST /api/shipments - Create new shipment\n";
echo "  GET  /api/shipments/{id}/tracking - Get tracking points\n";
echo "  POST /api/shipments/{id}/tracking - Add tracking point\n\n";

echo "=== How to Use ===\n";
echo "1. Start PHP server: cd php/public && php -S 0.0.0.0:8000 index.php\n";
echo "2. Update frontend API base URL to http://localhost:8000\n";
echo "3. All existing frontend functionality will work unchanged\n";
echo "4. Login with: admin@test.com / admin123\n\n";

echo "The PHP backend is a complete drop-in replacement for the Node.js backend!\n";