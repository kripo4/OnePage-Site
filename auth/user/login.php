<?php
// Подключение к базе данных
$servername = "localhost"; // или адрес вашего сервера
$username = ""; // ваше имя пользователя базы данных
$password = ""; // ваш пароль базы данных
$dbname = ""; // имя вашей базы данных (замените на реальное имя)

$conn = new mysqli($servername, $username, $password, $dbname);

// Проверка соединения
if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

// Получение данных из формы
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    // Подготовка и выполнение SQL-запроса
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $user, $pass);
    $stmt->execute();
    $result = $stmt->get_result();

    // Проверка результата
    if ($result->num_rows > 0) {
        // Успешный вход
        header("Location: /chat/index.php");
        exit();
    } else {
        // Неверный логин или пароль
        echo "<script>alert('Your password wrong!'); window.location.href='index.php';</script>";
    }
}

$conn->close();
?>
