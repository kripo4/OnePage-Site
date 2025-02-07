<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Подключение к БД
$servername = "localhost";
$username = "";
$password = "";
$dbname = "";

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

// Создание администратора
$admin_login = ' ';
$admin_password = '666777444123';

$hash = password_hash($admin_password, PASSWORD_DEFAULT);

try {
    $stmt = $conn->prepare("
        INSERT INTO admins (username, password_hash)
        VALUES (:username, :hash)
    ");
    $stmt->execute([
        ':username' => $admin_login,
        ':hash' => $hash
    ]);
    echo "Администратор создан!";
} catch (PDOException $e) {
    die("Ошибка: " . $e->getMessage());
}
?>
