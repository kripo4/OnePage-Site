<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Конфигурация подключения к БД
$servername = "localhost";
$username = "";
$password = "";
$dbname = ""; // Имя базы данных

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

// Обработка формы входа
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input_login = $_POST['username'] ?? '';
    $input_password = $_POST['password'] ?? '';

    try {
        // Поиск администратора в базе
        $stmt = $conn->prepare("SELECT * FROM admins WHERE username = ?");
        $stmt->execute([$input_login]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($input_password, $admin['password_hash'])) {
            $_SESSION['admin'] = [
                'id' => $admin['id'],
                'username' => $admin['username']
            ];
            header("Location: admin.php");
            exit;
        } else {
            $error = "Неверные учетные данные!";
        }
    } catch (PDOException $e) {
        $error = "Ошибка базы данных: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход в админ-панель</title>
    <style>
        body {
            background-color: black;
            color: white;
            font-family: monospace;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-form {
            background-color: #222;
            padding: 25px;
            border-radius: 8px;
            width: 90%;
            max-width: 400px;
            box-sizing: border-box; /* Учитываем padding в ширине */
            border: 1px solid #444;
        }
        .login-form h2 {
            margin-top: 0;
            text-align: center;
        }
        .login-form input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #444;
            background-color: #111;
            color: white;
            font-family: monospace;
            box-sizing: border-box; /* Учитываем padding в ширине */
            border-radius: 5px;
        }
        .login-form input:focus {
            border-color: #4f46e5;
            outline: none;
        }
        .login-form button {
            width: 100%;
            padding: 10px;
            background-color: #4f46e5;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-family: monospace;
            margin-top: 10px;
        }
        .login-form button:hover {
            background-color: #6366f1;
        }
        .error {
            color: red;
            margin-top: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="login-form">
        <h2>Вход в админ-панель</h2>
        <?php if (isset($error)): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Логин" required>
            <input type="password" name="password" placeholder="Пароль" required>
            <button type="submit">Войти</button>
        </form>
    </div>
</body>
</html>
