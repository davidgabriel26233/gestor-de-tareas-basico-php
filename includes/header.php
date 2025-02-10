<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestor de tareas basico | Iniciar Sesión</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="./assets/css/styles.css">
</head>

<body>
    <header class="py-3 border-bottom">
        <div class="container-md">
            <div class="row g-3 align-items-center">
                <?php if (isset($_SESSION['auth'])): ?>
                    <div class="col-8">
                        <h1 class="h4 mb-0 fw-bolder">Gestor De Tareas Basico</h1>
                    </div>
                    <div class="col-4 text-end">
                        <a href="./logout.php" class="btn btn-dark">Cerrar Sesión</a>
                    </div>
                <?php else: ?>
                    <div class="col-12">
                        <h1 class="h2 mb-0 fw-bolder text-center">Gestor De Tareas Basico</h1>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </header>
    <main class="container-md py-3">