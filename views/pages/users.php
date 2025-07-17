<?php
$user = getCurrentUser();
if (!in_array($user['role'], ['генеральный_директор', 'супер_админ'])) {
    header('Location: index.php?page=dashboard');
    exit;
}

// Get all users
$db = getDB();
$stmt = $db->prepare("SELECT * FROM users ORDER BY \"createdAt\" DESC");
$stmt->execute();
$users = $stmt->fetchAll();
?>

<div class="animate-fade-in">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Управление пользователями</h1>
        <p class="text-gray-600">Администрирование пользователей системы</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="p-6 border-b border-gray-100">
            <div class="flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-900">Список пользователей</h2>
                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">
                    Всего: <?php echo count($users); ?>
                </span>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Пользователь</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Роль</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Дата регистрации</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Статус</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($users as $userData): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user text-blue-600"></i>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            <?php echo htmlspecialchars($userData['firstName'] . ' ' . $userData['lastName']); ?>
                                        </div>
                                        <div class="text-sm text-gray-500">ID: <?php echo htmlspecialchars($userData['id']); ?></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?php echo htmlspecialchars($userData['email']); ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 text-xs font-medium rounded-full 
                                    <?php echo $userData['role'] === 'генеральный_директор' ? 'bg-purple-100 text-purple-800' : 
                                              ($userData['role'] === 'супер_админ' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800'); ?>">
                                    <?php echo getRoleName($userData['role']); ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?php echo formatDate($userData['createdAt']); ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">
                                    Активен
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>