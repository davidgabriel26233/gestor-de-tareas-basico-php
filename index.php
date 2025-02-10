<?php
session_start();

if (!$_SESSION['auth']) {
    header('location: ./login.php');
}

require __DIR__ . '/config/database.php';

$stmt = mysqli_prepare($conexion, 'SELECT * FROM tasks WHERE user_id = ? ORDER BY created_at DESC');
mysqli_stmt_bind_param($stmt, 'i', $_SESSION['auth']['id']);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
mysqli_stmt_close($stmt);
mysqli_close($conexion);

include __DIR__ . '/includes/header.php';
?>
<section class="row g-3">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <h2 class="h5 mb-0">Mis Tareas</h2>
        <a href="./add-task.php" class="btn btn-primary" id="btn-add-task">Nueva Tarea</a>
    </div>
    <?php if ($result->num_rows > 0) :  ?>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <div class="col-12 col-md-6 col-lg-4">
                <article class="card <?= $row['complete'] ? 'border-success' : 'border-warning' ?>">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="h6 mb-0 text-truncate"><?= $row['title'] ?></h3>
                        <div class="dropdown">
                            <button class="btn btn-outline-dark btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">opciones</button>
                            <ul class="dropdown-menu">
                                <li><button class="dropdown-item toggle-complete"><?= $row['complete'] ? 'Marcar como incomplete' : 'Marcar como completada' ?></button></li>
                                <li><a class="dropdown-item" href="./update-task.php?id=<?= $row['id'] ?>">Actualizar Tarea</a></li>
                                <li><a class="dropdown-item" href="./delete-task.php?id=<?= $row['id'] ?>">Eliminar Tarea</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="fs-6 mb-0"><?= $row['description'] ?></p>
                    </div>
                </article>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="col-12">
            <div class="alert alert-warning mb-0">No hay tareas</div>
        </div>
    <?php endif; ?>
</section>
<?php
$scripts = ['index.js'];
include __DIR__ . '/includes/footer.php';
?>