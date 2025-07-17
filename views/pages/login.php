<div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-black relative overflow-hidden">
    <!-- Animated Background Elements -->
    <div class="absolute inset-0">
        <div class="absolute top-20 left-20 w-72 h-72 bg-emerald-500/20 rounded-full mix-blend-multiply filter blur-xl animate-pulse"></div>
        <div class="absolute top-40 right-20 w-96 h-96 bg-blue-500/20 rounded-full mix-blend-multiply filter blur-xl animate-pulse" style="animation-delay: 2s;"></div>
        <div class="absolute -bottom-8 left-1/2 w-80 h-80 bg-purple-500/20 rounded-full mix-blend-multiply filter blur-xl animate-pulse" style="animation-delay: 4s;"></div>
    </div>
    
    <!-- Grid Pattern -->
    <div class="absolute inset-0 bg-grid-pattern opacity-10"></div>
    
    <div class="relative z-10 flex items-center justify-center min-h-screen p-4">
        <div class="w-full max-w-md">
            <!-- Modern Logo Section -->
            <div class="text-center mb-12 animate-fade-in">
                <div class="relative inline-block mb-8">
                    <div class="w-20 h-20 bg-gradient-to-br from-emerald-400 via-blue-500 to-purple-600 rounded-3xl flex items-center justify-center shadow-2xl transform hover:rotate-12 transition-all duration-500 relative overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-br from-white/20 to-transparent"></div>
                        <div class="relative text-white font-black text-xl tracking-tight">
                            <div class="leading-none">ХР</div>
                            <div class="text-sm opacity-90">KZ</div>
                        </div>
                    </div>
                </div>
                
                <h1 class="text-4xl font-black bg-gradient-to-r from-emerald-300 via-blue-300 to-purple-300 bg-clip-text text-transparent mb-4">
                    Хром-KZ
                </h1>
                <p class="text-gray-400 text-lg font-medium">Современная логистика</p>
            </div>

            <!-- Modern Login Form -->
            <div class="bg-gray-800/40 backdrop-blur-2xl p-8 rounded-3xl border border-gray-700/50 shadow-2xl animate-fade-in" style="animation-delay: 0.3s;">
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-bold text-white mb-2">Авторизация</h2>
                    <p class="text-gray-400">Войдите в панель управления</p>
                </div>
            
            <?php if (isset($login_error)): ?>
                <div class="bg-red-100 text-red-700 p-3 rounded-lg text-sm mb-4">
                    <?php echo htmlspecialchars($login_error); ?>
                </div>
            <?php endif; ?>
            
            <form method="post" class="space-y-4">
                <input type="hidden" name="action" value="login">
                
                <div class="space-y-6">
                    <div class="group">
                        <label class="block text-gray-300 text-sm font-medium mb-3 group-focus-within:text-emerald-400 transition-colors">Email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-user text-gray-500 group-focus-within:text-emerald-400 transition-colors"></i>
                            </div>
                            <input type="email" name="email" required 
                                   class="w-full pl-12 pr-4 py-4 bg-gray-900/50 border border-gray-600 rounded-2xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-300"
                                   placeholder="your@email.com">
                        </div>
                    </div>
                    
                    <div class="group">
                        <label class="block text-gray-300 text-sm font-medium mb-3 group-focus-within:text-emerald-400 transition-colors">Пароль</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-500 group-focus-within:text-emerald-400 transition-colors"></i>
                            </div>
                            <input type="password" name="password" required 
                                   class="w-full pl-12 pr-4 py-4 bg-gray-900/50 border border-gray-600 rounded-2xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-300"
                                   placeholder="••••••••">
                        </div>
                    </div>
                </div>
                
                <button type="submit" class="w-full mt-8 bg-gradient-to-r from-emerald-500 to-blue-600 text-white py-4 rounded-2xl font-semibold text-lg hover:from-emerald-600 hover:to-blue-700 focus:outline-none focus:ring-4 focus:ring-emerald-400/20 transform hover:scale-[1.02] active:scale-[0.98] transition-all duration-200 shadow-xl relative overflow-hidden group">
                    <div class="absolute inset-0 bg-gradient-to-r from-white/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative flex items-center justify-center space-x-2">
                        <i class="fas fa-arrow-right"></i>
                        <span>Войти</span>
                    </div>
                </button>
            </form>

                <!-- Demo Access Info -->
                <div class="mt-8 bg-gray-800/60 backdrop-blur-sm p-6 rounded-2xl border border-gray-600/50">
                    <div class="flex items-start space-x-4">
                        <div class="w-10 h-10 bg-emerald-500/20 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-info-circle text-emerald-400"></i>
                        </div>
                        <div>
                            <h3 class="text-white font-semibold mb-3">Тестовый доступ</h3>
                            <div class="space-y-2 text-sm">
                                <div class="flex items-center space-x-2">
                                    <span class="text-gray-400">Email:</span>
                                    <code class="text-emerald-400 bg-gray-900/50 px-2 py-1 rounded">test@test.com</code>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="text-gray-400">Пароль:</span>
                                    <code class="text-emerald-400 bg-gray-900/50 px-2 py-1 rounded">test123</code>
                                </div>
                                <div class="mt-3">
                                    <span class="inline-block px-3 py-1 bg-gradient-to-r from-emerald-500/20 to-blue-500/20 text-emerald-300 rounded-full text-xs font-medium border border-emerald-500/30">
                                        Роль: Прораб
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
        </div>
    </div>
</div>