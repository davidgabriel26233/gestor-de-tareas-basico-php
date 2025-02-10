<?php

session_start();

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

if (!isset($_SESSION['auth'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

if ($method !== 'PUT') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['taskId']) || (int)$data['taskId'] < 0) {
    http_response_code(400);
    echo json_encode(['taskId' => 'Ingrese un taskId válido']);
    exit;
}

require __DIR__ . '/config/database.php';

$taskId = $data['taskId'];

$stmt = mysqli_prepare($conexion, 'SELECT complete FROM tasks WHERE id = ? AND user_id = ?');
mysqli_stmt_bind_param($stmt, 'ii', $taskId, $_SESSION['auth']['id']);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($task = mysqli_fetch_assoc($result)) {
    $newStatus = (int)!$task['complete'];

    $stmt = mysqli_prepare($conexion, 'UPDATE tasks SET complete = ? WHERE id = ? AND user_id = ?');
    mysqli_stmt_bind_param($stmt, 'iii', $newStatus, $taskId, $_SESSION['auth']['id']);
    mysqli_stmt_execute($stmt);

    echo json_encode(['complete' => (bool)$newStatus]);
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Task not found']);
}

mysqli_stmt_close($stmt);
mysqli_close($conexion);
