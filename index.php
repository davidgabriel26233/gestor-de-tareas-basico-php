<?php
session_start();

if (!$_SESSION['user']) {
    header('location: ./login.php');
}

include __DIR__ . '/includes/header.php';
?>
<section class="row align-items-center justify-content-center">
</section>
<?php
include __DIR__ . '/includes/footer.php';
?>