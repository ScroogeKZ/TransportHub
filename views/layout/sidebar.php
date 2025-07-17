<?php $user = getCurrentUser(); ?>
<aside class="w-80 bg-gray-900/95 backdrop-blur-xl border-r border-gray-700/50 min-h-screen">
    <div class="p-6">
        <!-- Navigation Header -->
        <div class="mb-8">
            <h2 class="text-lg font-bold text-white mb-2">Навигация</h2>
            <div class="h-1 w-12 bg-gradient-to-r from-emerald-500 to-blue-500 rounded-full"></div>
        </div>
        
        <nav>
            <ul class="space-y-2">
                <li>
                    <a href="index.php?page=dashboard" 
                       class="flex items-center px-4 py-4 rounded-2xl transition-all duration-300 group relative overflow-hidden <?php echo $page === 'dashboard' ? 'bg-gradient-to-r from-emerald-500 to-blue-600 text-white shadow-lg' : 'text-gray-300 hover:text-white hover:bg-gray-800/50'; ?>">
                        <?php if ($page === 'dashboard'): ?>
                            <div class="absolute inset-0 bg-gradient-to-r from-white/10 to-transparent"></div>
                        <?php endif; ?>
                        <div class="relative flex items-center space-x-4 w-full">
                            <div class="w-10 h-10 rounded-xl <?php echo $page === 'dashboard' ? 'bg-white/20' : 'bg-gray-800 group-hover:bg-emerald-500/20'; ?> flex items-center justify-center transition-all duration-300">
                                <i class="fas fa-chart-pie <?php echo $page === 'dashboard' ? 'text-white' : 'text-gray-400 group-hover:text-emerald-400'; ?>"></i>
                            </div>
                            <span class="font-semibold">Панель управления</span>
                            <?php if ($page === 'dashboard'): ?>
                                <div class="ml-auto w-2 h-2 bg-white/50 rounded-full"></div>
                            <?php endif; ?>
                        </div>
                    </a>
                </li>
            

            
            <?php if (in_array($user['role'], ['логист', 'руководитель_смт', 'финансовый_директор', 'генеральный_директор', 'супер_админ'])): ?>
            <li>
                <a href="index.php?page=carriers" 
                   class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-gray-100 <?php echo $page === 'carriers' ? 'bg-blue-50 text-blue-700' : ''; ?>">
                    <i class="fas fa-building mr-3"></i>
                    Перевозчики
                </a>
            </li>
            
            <li>
                <a href="index.php?page=routes" 
                   class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-gray-100 <?php echo $page === 'routes' ? 'bg-blue-50 text-blue-700' : ''; ?>">
                    <i class="fas fa-route mr-3"></i>
                    Маршруты
                </a>
            </li>
            
            <li>
                <a href="index.php?page=calculator" 
                   class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-gray-100 <?php echo $page === 'calculator' ? 'bg-blue-50 text-blue-700' : ''; ?>">
                    <i class="fas fa-calculator mr-3"></i>
                    Калькулятор
                </a>
            </li>
            
            <li>
                <a href="index.php?page=tracking" 
                   class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-gray-100 <?php echo $page === 'tracking' ? 'bg-blue-50 text-blue-700' : ''; ?>">
                    <i class="fas fa-map-marker-alt mr-3"></i>
                    Отслеживание
                </a>
            </li>
            <?php endif; ?>
            
            <?php if (in_array($user['role'], ['генеральный_директор', 'супер_админ'])): ?>
            <li class="pt-4 border-t">
                <h3 class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wide">Администрирование</h3>
            </li>
            
            <li>
                <a href="index.php?page=users" 
                   class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-gray-100 <?php echo $page === 'users' ? 'bg-blue-50 text-blue-700' : ''; ?>">
                    <i class="fas fa-users mr-3"></i>
                    Пользователи
                </a>
            </li>
            <?php endif; ?>
        </ul>
    </nav>
</aside>