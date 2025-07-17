<?php
// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create_request') {
    $user = getCurrentUser();
    $data = [
        'fromCity' => $_POST['fromCity'],
        'fromAddress' => $_POST['fromAddress'],
        'toCity' => $_POST['toCity'],
        'toAddress' => $_POST['toAddress'],
        'cargoType' => $_POST['cargoType'],
        'weight' => $_POST['weight'],
        'width' => $_POST['width'],
        'length' => $_POST['length'],
        'height' => $_POST['height'],
        'description' => $_POST['description'],
        'createdById' => $user['id']
    ];
    
    if (createTransportationRequest($data)) {
        $success_message = "Заявка успешно создана!";
    } else {
        $error_message = "Ошибка при создании заявки";
    }
}

$cities = [
    'Алматы', 'Астана', 'Шымкент', 'Караганда', 'Актобе', 'Тараз', 'Павлодар', 
    'Усть-Каменогорск', 'Семей', 'Атырау', 'Костанай', 'Кызылорда', 'Уральск',
    'Петропавловск', 'Актау', 'Темиртау', 'Туркестан', 'Кокшетау', 'Талдыкорган', 'Экибастуз'
];

$cargoTypes = [
    'Нержавеющая сталь',
    'Стеклянные изделия',
    'Перила и ограждения',
    'Лифтовое оборудование',
    'Архитектурные элементы',
    'Металлоконструкции',
    'Строительные материалы',
    'Оборудование',
    'Другое'
];
?>

<div class="animate-fade-in">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Создать заявку на перевозку</h1>
        <p class="text-gray-600">Заполните форму для создания новой заявки</p>
    </div>

    <?php if (isset($success_message)): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            <?php echo htmlspecialchars($success_message); ?>
            <a href="index.php?page=requests" class="ml-4 text-green-800 hover:text-green-900 font-medium">
                Посмотреть заявки →
            </a>
        </div>
    <?php endif; ?>

    <?php if (isset($error_message)): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <?php echo htmlspecialchars($error_message); ?>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <form method="post" class="p-8 space-y-6">
            <input type="hidden" name="action" value="create_request">
            
            <!-- Route Information -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-map-marker-alt mr-2 text-blue-600"></i>
                        Откуда
                    </h3>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Город отправления *</label>
                        <select name="fromCity" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Выберите город</option>
                            <?php foreach ($cities as $city): ?>
                                <option value="<?php echo htmlspecialchars($city); ?>"><?php echo htmlspecialchars($city); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Адрес отправления</label>
                        <input type="text" name="fromAddress" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Укажите точный адрес">
                    </div>
                </div>

                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-flag-checkered mr-2 text-green-600"></i>
                        Куда
                    </h3>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Город назначения *</label>
                        <select name="toCity" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Выберите город</option>
                            <?php foreach ($cities as $city): ?>
                                <option value="<?php echo htmlspecialchars($city); ?>"><?php echo htmlspecialchars($city); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Адрес доставки</label>
                        <input type="text" name="toAddress" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Укажите точный адрес">
                    </div>
                </div>
            </div>

            <!-- Cargo Information -->
            <div class="border-t pt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-boxes mr-2 text-purple-600"></i>
                    Информация о грузе
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Тип груза *</label>
                        <select name="cargoType" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Выберите тип груза</option>
                            <?php foreach ($cargoTypes as $type): ?>
                                <option value="<?php echo htmlspecialchars($type); ?>"><?php echo htmlspecialchars($type); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Вес (тонн) *</label>
                        <input type="number" name="weight" step="0.1" min="0" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="0.0">
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Ширина (м)</label>
                        <input type="number" name="width" step="0.1" min="0"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="0.0">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Длина (м)</label>
                        <input type="number" name="length" step="0.1" min="0"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="0.0">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Высота (м)</label>
                        <input type="number" name="height" step="0.1" min="0"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="0.0">
                    </div>
                </div>
                
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Дополнительная информация</label>
                    <textarea name="description" rows="4"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Опишите особенности груза, требования к перевозке и другую важную информацию"></textarea>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="border-t pt-6">
                <div class="flex justify-end space-x-4">
                    <a href="index.php?page=requests" 
                       class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                        Отмена
                    </a>
                    <button type="submit" 
                            class="px-6 py-3 btn-gradient text-white rounded-lg hover:shadow-lg transition-all duration-300">
                        <i class="fas fa-plus mr-2"></i>
                        Создать заявку
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>