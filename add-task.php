<?php
session_start();

if (!$_SESSION['auth']) {
    header('location: ./login.php');
}

require __DIR__ . '/config/database.php';

$title = '';
$description = '';
$errors = [];
$alert = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = filter_input(INPUT_POST, 'title');
    $description = filter_input(INPUT_POST, 'description');

    if ($title === '') {
        $errors['title'] = 'El titulo es obligatorio';
    } else if (!preg_match('/^[a-zA-Z ]+$/', $title)) {
        $errors['title'] = 'El titulo solo puede tener letras y espacios';
    }

    if ($description === '') {
        $errors['description'] = 'La descripción es obligatoria';
    }

    if (count($errors) === 0) {
        $stmt = mysqli_prepare($conexion, 'INSERT INTO tasks (user_id, title, description) VALUES (?,?,?)');
        mysqli_stmt_bind_param($stmt, 'iss', $_SESSION['auth']['id'], $title, $description);

        if (!mysqli_stmt_execute($stmt)) {
            $alert = [
                'type' => 'danger',
                'message' => 'Ocurrio un error al agregar la tarea'
            ];
        } else {
            header('location: ./');
        }
    }
}

include __DIR__ . '/includes/header.php';
?>
<section class="row g-3 align-items-center justify-content-center">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <h2 class="h5 mb-0">Agregar Tarea</h2>
        <a href="./" class="btn btn-outline-dark">Volver</a>
    </div>
    <div class="col-12">
        <form method="post">
            <div class="mb-3">
                <label for="title" class="form-label">Titulo</label>
                <input type="text" name="title" placeholder="Ingrese un titulo" class="form-control<?= isset($errors['title']) ? ' is-invalid' : '' ?>" id="title" value="<?= htmlspecialchars($title) ?>" required pattern="^[a-zA-Z ]+$" title="El titulo solo puede tener letras y espacios">
                <?php if (isset($errors['title'])) : ?>
                    <div class="invalid-feedback"><?= $errors['title'] ?></div>
                <?php endif; ?>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Descripción</label>
                <textarea name="description" placeholder="Ingrese una descripción" class="form-control<?= isset($errors['description']) ? ' is-invalid' : '' ?>" id="description" required><?= htmlspecialchars($description) ?></textarea>
                <?php if (isset($errors['description'])) : ?>
                    <div class="invalid-feedback"><?= $errors['description'] ?></div>
                <?php endif; ?>
            </div>
            <button type="submit" class="btn btn-primary">Agregar Tarea</button>
        </form>
    </div>
</section>
<?php
$scripts = ['save-task.js'];
include __DIR__ . '/includes/footer.php';
?>