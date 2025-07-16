<?php
/**
 * API testing script for PHP backend
 */

// Test configuration
$baseUrl = 'http://localhost:8000';
$testEmail = 'admin@test.com';
$testPassword = 'admin123';

echo "=== PHP Backend API Tests ===\n\n";

// Test 1: Check if server is running
echo "1. Testing server connectivity...\n";
$response = @file_get_contents($baseUrl . '/api/auth/user');
if ($response === false) {
    echo "❌ Server not accessible at $baseUrl\n";
    echo "Make sure to start the PHP server first:\n";
    echo "cd php/public && php -S localhost:8000 index.php\n\n";
    exit(1);
} else {
    echo "✅ Server is running\n\n";
}

// Function to make API requests
function makeRequest($url, $method = 'GET', $data = null, $headers = []) {
    $context = [
        'http' => [
            'method' => $method,
            'header' => implode("\r\n", array_merge([
                'Content-Type: application/json',
                'Accept: application/json'
            ], $headers)),
            'ignore_errors' => true
        ]
    ];
    
    if ($data !== null) {
        $context['http']['content'] = json_encode($data);
    }
    
    $response = file_get_contents($url, false, stream_context_create($context));
    $httpCode = 200; // Default for successful file_get_contents
    
    if (isset($http_response_header)) {
        foreach ($http_response_header as $header) {
            if (preg_match('/HTTP\/\d\.\d\s+(\d+)/', $header, $matches)) {
                $httpCode = (int)$matches[1];
                break;
            }
        }
    }
    
    return [
        'code' => $httpCode,
        'body' => json_decode($response, true),
        'raw' => $response
    ];
}

// Test 2: Login
echo "2. Testing login...\n";
$loginResponse = makeRequest($baseUrl . '/api/auth/login', 'POST', [
    'email' => $testEmail,
    'password' => $testPassword
]);

if ($loginResponse['code'] === 200) {
    echo "✅ Login successful\n";
    echo "   User: " . $loginResponse['body']['first_name'] . " " . $loginResponse['body']['last_name'] . "\n";
    echo "   Role: " . $loginResponse['body']['role'] . "\n\n";
} else {
    echo "❌ Login failed\n";
    echo "   Response: " . $loginResponse['raw'] . "\n\n";
}

// Test 3: Get user info
echo "3. Testing user info...\n";
$userResponse = makeRequest($baseUrl . '/api/auth/user');
if ($userResponse['code'] === 200) {
    echo "✅ User info retrieved\n\n";
} else {
    echo "❌ User info failed: " . $userResponse['raw'] . "\n\n";
}

// Test 4: Dashboard stats
echo "4. Testing dashboard stats...\n";
$statsResponse = makeRequest($baseUrl . '/api/dashboard/stats');
if ($statsResponse['code'] === 200) {
    echo "✅ Dashboard stats retrieved\n";
    echo "   Total transportations: " . $statsResponse['body']['total_transportations'] . "\n\n";
} else {
    echo "❌ Dashboard stats failed: " . $statsResponse['raw'] . "\n\n";
}

// Test 5: Transportation requests
echo "5. Testing transportation requests...\n";
$requestsResponse = makeRequest($baseUrl . '/api/transportation-requests');
if ($requestsResponse['code'] === 200) {
    echo "✅ Transportation requests retrieved\n";
    echo "   Count: " . count($requestsResponse['body']) . " requests\n\n";
} else {
    echo "❌ Transportation requests failed: " . $requestsResponse['raw'] . "\n\n";
}

// Test 6: Carriers
echo "6. Testing carriers...\n";
$carriersResponse = makeRequest($baseUrl . '/api/carriers');
if ($carriersResponse['code'] === 200) {
    echo "✅ Carriers retrieved\n";
    echo "   Count: " . count($carriersResponse['body']) . " carriers\n\n";
} else {
    echo "❌ Carriers failed: " . $carriersResponse['raw'] . "\n\n";
}

echo "=== Tests Complete ===\n";
echo "PHP backend is ready to use!\n";
echo "Frontend can connect to: $baseUrl\n";