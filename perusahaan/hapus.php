<?php
/**
 * Memuat file konfigurasi
 */
require_once '../config/database.php';

// Cek apakah ID ada
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: ' . BASE_URL . '/perusahaan/index.php');
    exit();
}
$id_perusahaan = $_GET['id'];

// Query untuk menghapus data perusahaan
$stmt = mysqli_prepare($koneksi, "DELETE FROM perusahaan WHERE id_perusahaan = ?");
mysqli_stmt_bind_param($stmt, "i", $id_perusahaan);

if (mysqli_stmt_execute($stmt)) {
    // Jika berhasil, kembali ke halaman index dengan pesan sukses
    header("Location: " . BASE_URL . "/perusahaan/index.php?pesan=Data perusahaan berhasil dihapus!");
} else {
    // Jika gagal, kembali dengan pesan error
    header("Location: " . BASE_URL . "/perusahaan/index.php?pesan=Gagal menghapus data.");
}

mysqli_stmt_close($stmt);
exit();
?>