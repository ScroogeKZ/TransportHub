<?php
$user = getCurrentUser();
$requests = getTransportationRequests($user['id'], $user['role']);
?>

<div class="animate-fade-in">
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Заявки на перевозку</h1>
            <p class="text-gray-600">Управление заявками на транспортировку грузов</p>
        </div>
        <a href="index.php?page=create" 
           class="btn-gradient text-white px-6 py-3 rounded-lg hover:shadow-lg transition-all duration-300 flex items-center">
            <i class="fas fa-plus mr-2"></i>
            Создать заявку
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6">
        <div class="p-6">
            <form method="get" class="flex flex-wrap gap-4 items-end">
                <input type="hidden" name="page" value="requests">
                
                <div class="flex-1 min-w-48">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Поиск по номеру</label>
                    <input type="text" name="search" value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="TR-2025-001">
                </div>
                
                <div class="min-w-48">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Статус</label>
                    <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Все статусы</option>
                        <option value="created" <?php echo ($_GET['status'] ?? '') === 'created' ? 'selected' : ''; ?>>Создана</option>
                        <option value="logistics" <?php echo ($_GET['status'] ?? '') === 'logistics' ? 'selected' : ''; ?>>Логистика</option>
                        <option value="manager" <?php echo ($_GET['status'] ?? '') === 'manager' ? 'selected' : ''; ?>>На рассмотрении</option>
                        <option value="finance" <?php echo ($_GET['status'] ?? '') === 'finance' ? 'selected' : ''; ?>>Финансы</option>
                        <option value="approved" <?php echo ($_GET['status'] ?? '') === 'approved' ? 'selected' : ''; ?>>Одобрена</option>
                        <option value="rejected" <?php echo ($_GET['status'] ?? '') === 'rejected' ? 'selected' : ''; ?>>Отклонена</option>
                    </select>
                </div>
                
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-search mr-2"></i>Найти
                </button>
            </form>
        </div>
    </div>

    <!-- Requests List -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <?php if (empty($requests)): ?>
            <div class="p-12 text-center">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-inbox text-2xl text-gray-400"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Заявок пока нет</h3>
                <p class="text-gray-500 mb-6">Создайте первую заявку на перевозку</p>
                <a href="index.php?page=create" 
                   class="inline-flex items-center px-6 py-3 btn-gradient text-white rounded-lg hover:shadow-lg transition-all duration-300">
                    <i class="fas fa-plus mr-2"></i>
                    Создать заявку
                </a>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">№ Заявки</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Маршрут</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Груз</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Статус</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Дата</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Действия</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($requests as $request): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        <?php echo htmlspecialchars($request['requestNumber']); ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        <div class="flex items-center">
                                            <i class="fas fa-map-marker-alt text-blue-500 mr-2"></i>
                                            <?php echo htmlspecialchars($request['fromCity']); ?>
                                        </div>
                                        <div class="flex items-center mt-1">
                                            <i class="fas fa-flag-checkered text-green-500 mr-2"></i>
                                            <?php echo htmlspecialchars($request['toCity']); ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900"><?php echo htmlspecialchars($request['cargoType']); ?></div>
                                    <?php if ($request['weight']): ?>
                                        <div class="text-xs text-gray-500"><?php echo $request['weight']; ?> тонн</div>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php echo getStatusBadge($request['status']); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php echo formatDate($request['createdAt']); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <button class="text-blue-600 hover:text-blue-900"
                                                onclick="viewRequest(<?php echo $request['id']; ?>)">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        
                                        <?php if (canEditRequest($request, $user)): ?>
                                            <button class="text-green-600 hover:text-green-900"
                                                    onclick="editRequest(<?php echo $request['id']; ?>)">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        <?php endif; ?>
                                        
                                        <?php if (canApproveRequest($request, $user)): ?>
                                            <button class="text-purple-600 hover:text-purple-900"
                                                    onclick="approveRequest(<?php echo $request['id']; ?>)">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Request Details Modal -->
<div id="requestModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50" onclick="closeModal()">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl max-w-2xl w-full max-h-screen overflow-y-auto" onclick="event.stopPropagation()">
            <div id="modalContent">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>

<script>
function viewRequest(id) {
    // Simple implementation - in real app would load via AJAX
    document.getElementById('requestModal').classList.remove('hidden');
    document.getElementById('modalContent').innerHTML = `
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Детали заявки</h3>
                <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <p class="text-gray-600">Заявка #${id}</p>
            <p class="text-sm text-gray-500 mt-2">Подробная информация о заявке будет загружена здесь.</p>
        </div>
    `;
}

function editRequest(id) {
    alert('Редактирование заявки #' + id);
}

function approveRequest(id) {
    if (confirm('Подтвердить заявку #' + id + '?')) {
        // Here would be AJAX call to approve
        alert('Заявка одобрена');
    }
}

function closeModal() {
    document.getElementById('requestModal').classList.add('hidden');
}
</script>