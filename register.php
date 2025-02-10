<?php
session_start();

if ($_SESSION['auth']) {
    header('location: ./');
}

require_once __DIR__ . '/config/database.php';

$username = '';
$email = '';
$password = '';
$alert = null;
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = filter_input(INPUT_POST, 'username');
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, 'password');

    if ($username === '') {
        $errors['username'] = 'El nombre de usuario es obligatorio';
    } else if (!preg_match('/^[a-zA-Z0-9]+$/', $username)) {
        $errors['username'] = 'El nombre de usuario solo puede tener letras y numeros';
    } else if (strlen($username) < 4) {
        $errors['username'] = 'El nombre de usuario debe tener al menos 4 caracteres';
    } else if (strlen($username) > 16) {
        $errors['username'] = 'El nombre de usuario no debe tener mas de 16 caracteres';
    } else {
        $stmt = mysqli_prepare($conexion, 'SELECT * FROM users WHERE username = ? LIMIT 1');
        mysqli_stmt_bind_param($stmt, 's', $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        mysqli_stmt_close($stmt);

        if ($result->num_rows > 0) {
            $errors['username'] = 'El nombre de usuario ya esta en uso';
        }
    }

    if ($email === '') {
        $errors['email'] = 'El email es obligatorio';
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'El email no es valido';
    } else {
        $stmt = mysqli_prepare($conexion, 'SELECT * FROM users WHERE email = ? LIMIT 1');
        mysqli_stmt_bind_param($stmt, 's', $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        mysqli_stmt_close($stmt);

        if ($result->num_rows > 0) {
            $errors['email'] = 'El email ya esta en uso';
        }
    }

    if ($password === '') {
        $errors['password'] = 'La contraseña es obligatoria';
    } else if (strlen($password) < 8) {
        $errors['password'] = 'La contraseña debe tener al menos 8 caracteres';
    }

    if (count($errors) === 0) {
        $stmt = mysqli_prepare($conexion, 'INSERT INTO users (username, email, password) VALUES (?, ?, ?)');
        mysqli_stmt_bind_param($stmt, 'sss', $username, $email, password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]));
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        $_SESSION['auth'] = [
            'id' => mysqli_insert_id($conexion),
            'username' => $username,
            'email' => $email
        ];

        header('location: ./');
    }
}

include __DIR__ . '/includes/header.php';
?>
<section class="row g-3 justify-content-center">
    <div class="col-12 col-md-6">
        <h2 class="h5 text-center mb-2">Registrarse</h2>
        <?php if (!is_null($alert)): ?>
            <div class="alert alert-<?= $alert['type'] ?>"><?= $alert['message'] ?></div>
        <?php endif; ?>
        <form method="post">
            <div class="mb-3">
                <label for="username" class="form-label">Nombre de usuario</label>
                <input type="text" name="username" placeholder="Ingrese un nombre de usuario" class="form-control<?= isset($errors['username']) ? ' is-invalid' : '' ?>" id="username" value="<?= htmlspecialchars($username) ?>" required minlength="4" maxlength="16" pattern="^[a-zA-Z0-9]+$" title="Solo se permiten letras y numeros">
                <?php if (isset($errors['username'])) : ?>
                    <div class="invalid-feedback"><?= $errors['username'] ?></div>
                <?php endif; ?>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" placeholder="Ingrese un email" class="form-control<?= isset($errors['email']) ? ' is-invalid' : '' ?>" id="email" value="<?= htmlspecialchars($email) ?>">
                <?php if (isset($errors['email'])) : ?>
                    <div class="invalid-feedback"><?= $errors['email'] ?></div>
                <?php endif; ?>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" name="password" placeholder="Ingrese una contraseña" class="form-control<?= isset($errors['password']) ? ' is-invalid' : '' ?>" id="password" required minlength="8">
                <?php if (isset($errors['password'])) : ?>
                    <div class="invalid-feedback"><?= $errors['password'] ?></div>
                <?php endif; ?>
            </div>
            <button type="submit" class="btn btn-primary w-100">Registrarse</button>
        </form>
        <div class="mt-3 text-center">
            <span>¿Ya tienes cuenta? <a href="./login.php">Inicia Sesión</a></span>
        </div>
    </div>
</section>
<?php
$scripts = ['register.js'];
include __DIR__ . '/includes/footer.php';
?>