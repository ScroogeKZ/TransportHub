<?php
// Простой тест PHP-бэкенда для системы Хром-KZ
session_start();

// Тест подключения к базе данных
try {
    require_once 'config/database.php';
    $db = Database::getInstance()->getConnection();
    echo "✓ Подключение к базе данных успешно<br>";
} catch (Exception $e) {
    echo "✗ Ошибка подключения к базе данных: " . $e->getMessage() . "<br>";
}

// Тест моделей
try {
    require_once 'models/User.php';
    require_once 'models/TransportationRequest.php';
    require_once 'models/Carrier.php';
    require_once 'models/Route.php';
    require_once 'models/Shipment.php';
    
    $userModel = new User();
    $requestModel = new TransportationRequest();
    $carrierModel = new Carrier();
    $routeModel = new Route();
    $shipmentModel = new Shipment();
    
    echo "✓ Все модели загружены успешно<br>";
} catch (Exception $e) {
    echo "✗ Ошибка загрузки моделей: " . $e->getMessage() . "<br>";
}

// Тест контроллеров
try {
    require_once 'controllers/AuthController.php';
    require_once 'controllers/RequestController.php';
    require_once 'controllers/CarrierController.php';
    require_once 'controllers/RouteController.php';
    require_once 'controllers/ShipmentController.php';
    require_once 'controllers/AdminController.php';
    
    echo "✓ Все контроллеры загружены успешно<br>";
} catch (Exception $e) {
    echo "✗ Ошибка загрузки контроллеров: " . $e->getMessage() . "<br>";
}

// Тест API endpoints
echo "<div class='header'>";
echo "<img src='logo.png' alt='Хром-KZ' class='logo'>";
echo "<h1>Хром-KZ</h1>";
echo "<p class='subtitle'>Система управления транспортом - PHP Backend</p>";
echo "</div>";

echo "<h2>API Endpoints</h2>";
echo "<ul>";
echo "<li>POST /api/auth/login - Авторизация</li>";
echo "<li>POST /api/auth/register - Регистрация</li>";
echo "<li>GET /api/auth/user - Текущий пользователь</li>";
echo "<li>GET /api/requests - Список заявок</li>";
echo "<li>POST /api/requests - Создание заявки</li>";
echo "<li>GET /api/carriers - Список перевозчиков</li>";
echo "<li>GET /api/routes - Список маршрутов</li>";
echo "<li>GET /api/shipments - Список отгрузок</li>";
echo "<li>GET /api/admin/users - Список пользователей</li>";
echo "<li>GET /api/admin/stats - Статистика</li>";
echo "</ul>";

echo "<h2>Тестирование API</h2>";
echo "<button onclick=\"testLogin()\">Тест авторизации</button>";
echo "<button onclick=\"testRegister()\">Тест регистрации</button>";
echo "<button onclick=\"testCurrentUser()\">Тест текущего пользователя</button>";
echo "<div id='result'></div>";

?>

<script>
function testLogin() {
    fetch('/api/auth/login', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            email: 'test@example.com',
            password: 'test123'
        })
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('result').innerHTML = '<h3>Результат авторизации:</h3><pre>' + JSON.stringify(data, null, 2) + '</pre>';
    })
    .catch(error => {
        document.getElementById('result').innerHTML = '<h3>Ошибка:</h3><pre>' + error + '</pre>';
    });
}

function testRegister() {
    fetch('/api/auth/register', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            email: 'newuser@example.com',
            password: 'newpass123',
            firstName: 'Новый',
            lastName: 'Пользователь'
        })
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('result').innerHTML = '<h3>Результат регистрации:</h3><pre>' + JSON.stringify(data, null, 2) + '</pre>';
    })
    .catch(error => {
        document.getElementById('result').innerHTML = '<h3>Ошибка:</h3><pre>' + error + '</pre>';
    });
}

function testCurrentUser() {
    fetch('/api/auth/user', {
        method: 'GET',
        credentials: 'include'
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('result').innerHTML = '<h3>Текущий пользователь:</h3><pre>' + JSON.stringify(data, null, 2) + '</pre>';
    })
    .catch(error => {
        document.getElementById('result').innerHTML = '<h3>Ошибка:</h3><pre>' + error + '</pre>';
    });
}
</script>

<style>
body {
    font-family: Arial, sans-serif;
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
}
.header {
    text-align: center;
    margin-bottom: 30px;
}
.logo {
    width: 80px;
    height: 80px;
    object-fit: contain;
    margin: 0 auto 10px;
    display: block;
}
h1 {
    color: #333;
    margin: 0;
    font-size: 2.5em;
}
.subtitle {
    color: #666;
    margin-top: 5px;
}
button {
    background: #007bff;
    color: white;
    border: none;
    padding: 10px 20px;
    margin: 5px;
    cursor: pointer;
    border-radius: 5px;
}
button:hover {
    background: #0056b3;
}
pre {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 5px;
    overflow-x: auto;
}
</style>