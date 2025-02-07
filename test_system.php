<?php
// test_system.php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$config = [
    'db' => [
        'host' => 'localhost',
        'user' => '',
        'pass' => '',
        'name' => ''
    ],
    'required_tables' => ['admins', 'users', 'messages'],
    'required_php_version' => '7.4.0',
    'required_extensions' => ['pdo_mysql', 'session', 'json']
];
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Системная диагностика</title>
    <style>
        body {
            background: #0a0a0a;
            color: #00ff00;
            font-family: monospace;
            margin: 20px;
        }
        .test-block {
            border: 1px solid #00ff00;
            padding: 15px;
            margin: 10px 0;
        }
        .success { color: #00ff00; }
        .warning { color: #ffff00; }
        .error { color: #ff0000; }
        pre {
            background: #000;
            padding: 10px;
            border: 1px dashed #444;
        }
    </style>
</head>
<body>
    <h1>🔍 Тест системы CipherVeil</h1>

    <div class="test-block">
        <h2>1. Проверка PHP</h2>
        <?php
        $php_version = phpversion();
        $php_check = version_compare($php_version, $config['required_php_version'], '>=');
        ?>
        <p class="<?= $php_check ? 'success' : 'error' ?>">
            PHP Version: <?= $php_version ?> 
            (Минимум <?= $config['required_php_version'] ?>)
        </p>
        
        <h3>Необходимые расширения:</h3>
        <ul>
            <?php foreach ($config['required_extensions'] as $ext): ?>
                <li class="<?= extension_loaded($ext) ? 'success' : 'error' ?>">
                    <?= $ext ?>: <?= extension_loaded($ext) ? '✓' : '✗' ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <div class="test-block">
        <h2>2. Проверка базы данных</h2>
        <?php
        try {
            $dsn = "mysql:host={$config['db']['host']};dbname={$config['db']['name']};charset=utf8mb4";
            $conn = new PDO($dsn, $config['db']['user'], $config['db']['pass'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
            echo '<p class="success">✓ Успешное подключение к MySQL</p>';
            
            $tables = $conn->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
            $missing_tables = array_diff($config['required_tables'], $tables);
            
            echo empty($missing_tables) 
                ? '<p class="success">✓ Все необходимые таблицы существуют</p>'
                : '<p class="error">✗ Отсутствующие таблицы: ' . implode(', ', $missing_tables) . '</p>';
            
            $test_query = $conn->query("SELECT 1+1 AS result")->fetch();
            echo '<p class="success">✓ Тестовый запрос: 1+1 = ' . $test_query['result'] . '</p>';
            
        } catch(PDOException $e) {
            echo '<p class="error">✗ Ошибка подключения: ' . $e->getMessage() . '</p>';
        }
        ?>
    </div>

    <div class="test-block">
        <h2>3. Проверка сессий</h2>
        <?php
        $_SESSION['test_session'] = 'working';
        $session_check = (session_status() === PHP_SESSION_ACTIVE);
        ?>
        <p class="<?= $session_check ? 'success' : 'error' ?>">
            Статус сессии: <?= $session_check ? 'Активна' : 'Не активна' ?>
        </p>
        <p>Тестовое значение сессии: <?= $_SESSION['test_session'] ?? 'не установлено' ?></p>
    </div>

    <div class="test-block">
        <h2>4. Проверка файловой системы</h2>
        <?php
        $files = [
            'main page' => file_exists('index.php'),
            'login page' => file_exists('auth/user/index.php'),
            'login script' => file_exists('auth/user/login.php'),
            'admin login page' => file_exists('apnl/admin_login.php'),
            'admin action' => file_exists('apnl/admin_action.php'),
            'admin panel' => file_exists('apnl/admin.php')
        ];
        
        foreach ($files as $file => $exists): ?>
            <p class="<?= $exists ? 'success' : 'error' ?>">
                <?= $file ?> <?= $exists ? '✓' : '✗' ?>
            </p>
        <?php endforeach; ?>
    </div>

    <div class="test-block">
        <h2>5. Проверка безопасности</h2>
        <?php
        $security = [
            'HTTPS' => !empty($_SERVER['HTTPS']),
            'Display Errors' => (ini_get('display_errors') == 0),
            'PHP Version Exposure' => (ini_get('expose_php') === '0')
        ]; ?>
        <ul>
            <li class="<?= $security['HTTPS'] ? 'success' : 'warning' ?>">
                HTTPS: <?= $security['HTTPS'] ? '✓' : '✗' ?>
            </li>
            <li class="<?= $security['Display Errors'] ? 'success' : 'warning' ?>">
                Отображение ошибок: <?= $security['Display Errors'] ? '✗' : '✓' ?>
            </li>
            <li class="<?= $security['PHP Version Exposure'] ? 'success' : 'warning' ?>">
                Сокрытие версии PHP: <?= $security['PHP Version Exposure'] ? '✓' : '✗' ?>
            </li>
        </ul>
    </div>

    <div class="test-block">
        <h2>6. Системная информация</h2>
        <pre><?php
            echo "Сервер:    " . $_SERVER['SERVER_SOFTWARE'] . "\n";
            echo "Память:    " . ini_get('memory_limit') . "\n";
            echo "Времязона: " . date_default_timezone_get() . "\n";
            echo "Путь:      " . __DIR__;
        ?></pre>
    </div>

    <div class="test-block warning">
        <h3>⚠️ Важно!</h3>
        <p>Удалите этот файл после завершения тестирования!</p>
    </div>
</body>
</html>
