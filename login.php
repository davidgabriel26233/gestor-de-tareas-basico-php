<?php
session_start();

if ($_SESSION['user']) {
    header('location: ./');
}

require_once __DIR__ . '/config/database.php';

$email = '';
$password = '';
$alert = null;
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, 'password');

    if ($email === '') {
        $errors['email'] = 'El email es obligatorio';
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'El email no es valido';
    }

    if ($password === '') {
        $errors['password'] = 'La contraseña es obligatoria';
    }

    if (count($errors) === 0) {
        $stmt = mysqli_prepare($conexion, 'SELECT * FROM users WHERE email = ?');
        mysqli_stmt_bind_param($stmt, 's', $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        mysqli_stmt_close($stmt);

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            if (!password_verify($password, $user['password'])) {
                $alert = [
                    'type' => 'danger',
                    'message' => 'Email o contraseña incorrectos'
                ];
            } else {
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'email' => $user['email']
                ];

                header('location: ./');
            }
        } else {
            $alert = [
                'type' => 'danger',
                'message' => 'Email o contraseña incorrectos'
            ];
        }

        $email = '';
        $password = '';
    }
}
include __DIR__ . '/includes/header.php';
?>
<section class="row g-3 justify-content-center">
    <div class="col-12 col-md-6">
        <h2 class="h5 text-center mb-2">Iniciar Sesión</h2>
        <?php if (!is_null($alert)): ?>
            <div class="alert alert-<?= $alert['type'] ?>"><?= $alert['message'] ?></div>
        <?php endif; ?>
        <form method="post">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" placeholder="Ingrese su email" class="form-control<?= isset($errors['email']) ? ' is-invalid' : '' ?>" id="email" value="<?= htmlspecialchars($email) ?>" required>
                <?php if (isset($errors['email'])) : ?>
                    <div class="invalid-feedback"><?= $errors['email'] ?></div>
                <?php endif; ?>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" name="password" placeholder="Ingrese su contraseña" class="form-control<?= isset($errors['password']) ? ' is-invalid' : '' ?>" id="password" required minlength="8">
                <?php if (isset($errors['password'])) : ?>
                    <div class="invalid-feedback"><?= $errors['password'] ?></div>
                <?php endif; ?>
            </div>
            <button type="submit" class="btn btn-primary w-100">Iniciar Sesión</button>
        </form>
        <div class="mt-3 text-center">
            <span>¿No tienes cuenta? <a href="./register.php">Registrarse</a></span>
        </div>
    </div>
</section>
<?php
$scripts = ['login.js'];
include __DIR__ . '/includes/footer.php';
?>