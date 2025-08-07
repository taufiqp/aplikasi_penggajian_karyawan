<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Jika tidak ada session user_id (artinya belum login)
if (!isset($_SESSION['user_id'])) {
    // Arahkan paksa ke halaman login
    header("Location: " . BASE_URL . "/login.php");
    exit();
}
?>