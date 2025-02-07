<?php
// admin.php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Проверка авторизации
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit;
}

// Данные подключения
$servername = "";
$username = "";
$password = "";
$dbname = "";

// Подключение к БД
try {
    $conn = new PDO(
        "mysql:host=$servername;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (PDOException $e) {
    die("Ошибка подключения: " . $e->getMessage());
}

// Получение статистики
$users_count = $conn->query("SELECT COUNT(*) FROM users")->fetchColumn();
$messages_count = $conn->query("SELECT COUNT(*) FROM messages")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Админ-панель</title>
    <link href="https://fonts.googleapis.com/css2?family=Miki+Nice+Ainjoo&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #000;
            color: #0f0;
            font-family: 'Miki Nice Ainjoo', monospace;
            margin: 20px;
        }
        
        .admin-panel {
            max-width: 1200px;
            margin: 0 auto;
            border: 1px solid #0f0;
            padding: 20px;
        }
        
        .stats {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-box {
            border: 1px dashed #0f0;
            padding: 15px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        
        th, td {
            border: 1px solid #0f0;
            padding: 10px;
            text-align: left;
        }
        
        .danger {
            color: #f00;
            cursor: pointer;
        }
        
        .ascii-frame {
            border: 1px solid #0f0;
            padding: 10px;
            margin: 10px 0;
            background: #001100;
        }
    </style>
</head>
<body>
    <div class="admin-panel">
        <div class="ascii-frame">
            <pre>
  █████╗ ██████╗ ███╗   ███╗██╗███╗   ██╗
 ██╔══██╗██╔══██╗████╗ ████║██║████╗  ██║
 ███████║██║  ██║██╔████╔██║██║██╔██╗ ██║
 ██╔══██║██║  ██║██║╚██╔╝██║██║██║╚██╗██║
 ██║  ██║██████╔╝██║ ╚═╝ ██║██║██║ ╚████║
 ╚═╝  ╚═╝╚═════╝ ╚═╝     ╚═╝╚═╝╚═╝  ╚═══╝
            </pre>
        </div>

        <div class="stats">
            <div class="stat-box">
                <h2>👥 Пользователей: <?= $users_count ?></h2>
            </div>
            <div class="stat-box">
                <h2>💬 Сообщений: <?= $messages_count ?></h2>
            </div>
        </div>

        <div class="ascii-frame">
            <h2>📋 Список пользователей</h2>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Логин</th>
                    <th>Дата регистрации</th>
                    <th>Действия</th>
                </tr>
                <?php
                $stmt = $conn->query("SELECT * FROM users");
                while ($row = $stmt->fetch()):
                ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']) ?></td>
                    <td><?= htmlspecialchars($row['username']) ?></td>
                    <td><?= $row['reg_date'] ?></td>
                    <td>
                        <span class="danger" onclick="deleteUser(<?= $row['id'] ?>)">❌ Удалить</span>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>
    </div>

    <script>
        function deleteUser(userId) {
            if (confirm('Удалить пользователя ID ' + userId + '?')) {
                fetch(`admin_action.php?action=delete_user&id=${userId}`)
                    .then(response => {
                        if (response.ok) location.reload();
                        else alert('Ошибка удаления');
                    });
            }
        }
    </script>
</body>
</html>
