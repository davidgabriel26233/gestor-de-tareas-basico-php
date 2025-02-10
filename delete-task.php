<?php
session_start();

if (!$_SESSION['auth']) {
    header('location: ./login.php');
}

require __DIR__ . '/config/database.php';

$taskId = $_GET['id'] ?? null;

if (is_null($taskId)) {
    http_response_code(404);
    include '404.html';
    exit;
}

$stmt = mysqli_prepare($conexion, 'SELECT * FROM tasks WHERE user_id = ? AND id = ?');
mysqli_stmt_bind_param($stmt, 'ii', $_SESSION['auth']['id'], $taskId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
mysqli_stmt_close($stmt);

if ($result->num_rows === 0) {
    http_response_code(404);
    include '404.html';
    exit;
}

$task = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = mysqli_prepare($conexion, 'DELETE FROM tasks WHERE id = ? AND user_id = ?');
    mysqli_stmt_bind_param($stmt, 'ii', $task['id'], $_SESSION['auth']['id']);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header('location: ./');
}

include __DIR__ . '/includes/header.php';
?>
<section class="row g-3 align-items-center justify-content-center">
    <div class="col-12 col-md-6">
        <div class="card">
            <div class="card-body">
                <h2 class="h5 text-center mb-3">¿Estas seguro de eliminar la tarea?</h2>
                <form method="post">
                    <div class="d-flex justify-content-center gap-3">
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                        <a href="./" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
<?php
include __DIR__ . '/includes/footer.php';
?>