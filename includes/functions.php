<?php
function getPageTitle($page) {
    $titles = [
        'login' => 'Вход в систему',
        'dashboard' => 'Панель управления',
        'requests' => 'Заявки на перевозку',
        'create' => 'Создать заявку',
        'users' => 'Пользователи',
        'carriers' => 'Перевозчики',
        'routes' => 'Маршруты',
        'calculator' => 'Калькулятор стоимости',
        'tracking' => 'Отслеживание'
    ];
    return $titles[$page] ?? 'TransportHub';
}

function formatDate($date) {
    return date('d.m.Y H:i', strtotime($date));
}

function getStatusBadge($status) {
    $badges = [
        'created' => '<span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded-full">Создана</span>',
        'logistics' => '<span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-800 rounded-full">Логистика</span>',
        'manager' => '<span class="px-2 py-1 text-xs bg-orange-100 text-orange-800 rounded-full">На рассмотрении</span>',
        'finance' => '<span class="px-2 py-1 text-xs bg-purple-100 text-purple-800 rounded-full">Финансы</span>',
        'approved' => '<span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full">Одобрена</span>',
        'rejected' => '<span class="px-2 py-1 text-xs bg-red-100 text-red-800 rounded-full">Отклонена</span>',
        'completed' => '<span class="px-2 py-1 text-xs bg-gray-100 text-gray-800 rounded-full">Завершена</span>'
    ];
    return $badges[$status] ?? $status;
}

function getRoleName($role) {
    $roles = [
        'прораб' => 'Прораб',
        'логист' => 'Логист',
        'руководитель_смт' => 'Руководитель СМТ',
        'финансовый_директор' => 'Финансовый директор',
        'генеральный_директор' => 'Генеральный директор',
        'супер_админ' => 'Супер Админ'
    ];
    return $roles[$role] ?? $role;
}

function canEditRequest($request, $user) {
    if ($user['role'] === 'генеральный_директор' || $user['role'] === 'супер_админ') {
        return true;
    }
    
    if ($user['role'] === 'прораб' && $request['status'] === 'created' && $request['createdById'] === $user['id']) {
        return true;
    }
    
    return false;
}

function canApproveRequest($request, $user) {
    $canApprove = [
        'логист' => ['created'],
        'руководитель_смт' => ['logistics'],
        'финансовый_директор' => ['manager'],
        'генеральный_директор' => ['created', 'logistics', 'manager', 'finance'],
        'супер_админ' => ['created', 'logistics', 'manager', 'finance']
    ];
    
    return isset($canApprove[$user['role']]) && in_array($request['status'], $canApprove[$user['role']]);
}

function getTransportationRequests($userId = null, $role = null) {
    $db = getDB();
    
    if ($role === 'генеральный_директор' || $role === 'супер_админ') {
        $stmt = $db->prepare("SELECT * FROM transportation_requests ORDER BY \"createdAt\" DESC");
        $stmt->execute();
    } else {
        $stmt = $db->prepare("SELECT * FROM transportation_requests WHERE \"createdById\" = ? ORDER BY \"createdAt\" DESC");
        $stmt->execute([$userId]);
    }
    
    return $stmt->fetchAll();
}

function createTransportationRequest($data) {
    $db = getDB();
    
    // Generate request number
    $stmt = $db->prepare("SELECT COUNT(*) as count FROM transportation_requests");
    $stmt->execute();
    $count = $stmt->fetch()['count'];
    $requestNumber = 'TR-2025-' . str_pad($count + 1, 3, '0', STR_PAD_LEFT);
    
    $stmt = $db->prepare("
        INSERT INTO transportation_requests (
            \"requestNumber\", \"fromCity\", \"fromAddress\", \"toCity\", \"toAddress\", 
            \"cargoType\", weight, width, length, height, description, status, 
            \"createdById\", \"createdAt\", \"updatedAt\"
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'created', ?, NOW(), NOW())
    ");
    
    return $stmt->execute([
        $requestNumber,
        $data['fromCity'],
        $data['fromAddress'],
        $data['toCity'], 
        $data['toAddress'],
        $data['cargoType'],
        $data['weight'],
        $data['width'] ?? null,
        $data['length'] ?? null,
        $data['height'] ?? null,
        $data['description'] ?? '',
        $data['createdById']
    ]);
}

function getDashboardStats($userId, $role) {
    $db = getDB();
    
    if ($role === 'генеральный_директор' || $role === 'супер_админ') {
        $stmt = $db->prepare("
            SELECT 
                COUNT(*) as total_requests,
                COUNT(CASE WHEN status IN ('created', 'logistics', 'manager', 'finance') THEN 1 END) as active_requests,
                COALESCE(SUM(CAST(\"estimatedCost\" AS DECIMAL)), 0) as total_cost
            FROM transportation_requests
        ");
        $stmt->execute();
    } else {
        $stmt = $db->prepare("
            SELECT 
                COUNT(*) as total_requests,
                COUNT(CASE WHEN status IN ('created', 'logistics', 'manager', 'finance') THEN 1 END) as active_requests,
                COALESCE(SUM(CAST(\"estimatedCost\" AS DECIMAL)), 0) as total_cost
            FROM transportation_requests 
            WHERE \"createdById\" = ?
        ");
        $stmt->execute([$userId]);
    }
    
    return $stmt->fetch();
}
?>