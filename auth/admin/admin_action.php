<?php
// admin_action.php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Проверка авторизации
if (!isset($_SESSION['admin'])) {
    http_response_code(403);
    die(json_encode(['status' => 'error', 'message' => 'Access denied']));
}

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
    http_response_code(500);
    die(json_encode(['status' => 'error', 'message' => 'Database connection failed']));
}

// Получение действия
$action = $_GET['action'] ?? null;

header('Content-Type: application/json');

try {
    switch ($action) {
        case 'delete_user':
            if (!isset($_GET['id'])) {
                throw new Exception('Missing user ID');
            }
            
            $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
            
            if (!$id || $id < 1) {
                throw new Exception('Invalid user ID');
            }

            $stmt = $conn->prepare("DELETE FROM users WHERE id = :id");
            $stmt->execute([':id' => $id]);
            
            if ($stmt->rowCount() === 0) {
                throw new Exception('User not found');
            }

            echo json_encode(['status' => 'success', 'message' => 'User deleted']);
            break;

        default:
            http_response_code(400);
            echo json_encode([
                'status' => 'error', 
                'message' => 'Invalid or missing action parameter',
                'available_actions' => ['delete_user']
            ]);
            break;
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage(),
        'error_code' => $e->getCode()
    ]);
}
