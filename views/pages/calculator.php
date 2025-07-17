<div class="animate-fade-in">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Калькулятор стоимости</h1>
        <p class="text-gray-600">Расчет стоимости транспортировки</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="p-8">
            <form x-data="calculator()" @submit.prevent="calculate()">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Расстояние (км)</label>
                        <input type="number" x-model="distance" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Вес груза (тонн)</label>
                        <input type="number" x-model="weight" step="0.1"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="5.0">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Тип транспорта</label>
                        <select x-model="transportType" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="truck">Грузовик</option>
                            <option value="train">Железная дорога</option>
                            <option value="plane">Авиа</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Срочность</label>
                        <select x-model="urgency" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="normal">Обычная</option>
                            <option value="urgent">Срочная</option>
                            <option value="express">Экспресс</option>
                        </select>
                    </div>
                </div>
                
                <div class="mt-6">
                    <button type="submit" 
                            class="btn-gradient text-white px-6 py-3 rounded-lg hover:shadow-lg transition-all duration-300">
                        <i class="fas fa-calculator mr-2"></i>
                        Рассчитать стоимость
                    </button>
                </div>
                
                <div x-show="result > 0" class="mt-8 p-6 bg-blue-50 rounded-lg border border-blue-200">
                    <h3 class="text-lg font-semibold text-blue-900 mb-2">Результат расчета</h3>
                    <div class="text-2xl font-bold text-blue-700" x-text="formatCurrency(result)"></div>
                    <p class="text-sm text-blue-600 mt-2">Примерная стоимость транспортировки</p>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function calculator() {
    return {
        distance: 0,
        weight: 0,
        transportType: 'truck',
        urgency: 'normal',
        result: 0,
        
        calculate() {
            if (!this.distance || !this.weight) {
                alert('Пожалуйста, заполните все поля');
                return;
            }
            
            const baseRate = {
                truck: 50,
                train: 30,
                plane: 200
            };
            
            const urgencyMultiplier = {
                normal: 1,
                urgent: 1.5,
                express: 2
            };
            
            this.result = this.distance * this.weight * baseRate[this.transportType] * urgencyMultiplier[this.urgency];
        },
        
        formatCurrency(amount) {
            return new Intl.NumberFormat('ru-RU', {
                style: 'currency',
                currency: 'KZT'
            }).format(amount);
        }
    }
}
</script>