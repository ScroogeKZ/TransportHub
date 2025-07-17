<header class="bg-gray-900/90 backdrop-blur-xl border-b border-gray-700/50 sticky top-0 z-50">
    <div class="px-8 py-4 flex justify-between items-center">
        <div class="flex items-center space-x-6">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-emerald-500 via-blue-500 to-purple-600 flex items-center justify-center shadow-xl relative overflow-hidden group">
                    <div class="absolute inset-0 bg-gradient-to-br from-white/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative text-white text-sm font-black text-center leading-none">
                        <div>ХР</div>
                        <div class="text-xs opacity-90">KZ</div>
                    </div>
                </div>
                <div>
                    <h1 class="text-2xl font-black bg-gradient-to-r from-emerald-400 via-blue-400 to-purple-400 bg-clip-text text-transparent">
                        Хром-KZ
                    </h1>
                    <p class="text-gray-400 text-sm font-medium">Transport Management</p>
                </div>
            </div>
        </div>
        
        <div class="flex items-center space-x-6">
            <div class="flex items-center space-x-4 bg-gray-800/50 rounded-2xl p-3 border border-gray-700/50">
                <div class="w-10 h-10 bg-gradient-to-br from-emerald-500/20 to-blue-500/20 rounded-xl flex items-center justify-center border border-emerald-500/30">
                    <i class="fas fa-user text-emerald-400"></i>
                </div>
                <div>
                    <p class="text-sm font-semibold text-white">
                        <?php 
                        $user = getCurrentUser();
                        echo htmlspecialchars($user['firstName'] . ' ' . $user['lastName']); 
                        ?>
                    </p>
                    <span class="inline-block px-3 py-1 text-xs font-medium bg-gradient-to-r from-emerald-500/20 to-blue-500/20 text-emerald-300 rounded-full border border-emerald-500/30">
                        <?php echo getRoleName($user['role']); ?>
                    </span>
                </div>
            </div>
            
            <form method="post" style="display: inline;">
                <input type="hidden" name="action" value="logout">
                <button type="submit" class="flex items-center space-x-2 px-4 py-2 bg-red-500/10 hover:bg-red-500/20 text-red-400 hover:text-red-300 rounded-xl transition-all duration-200 font-medium border border-red-500/30 hover:border-red-500/50 group">
                    <i class="fas fa-sign-out-alt group-hover:rotate-12 transition-transform duration-200"></i>
                    <span>Выход</span>
                </button>
            </form>
        </div>
    </div>
</header>