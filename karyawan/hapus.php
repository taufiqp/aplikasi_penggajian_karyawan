<?php
require_once('../config/database.php');
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = mysqli_prepare($koneksi, "DELETE FROM karyawan WHERE id_karyawan = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    if(mysqli_stmt_execute($stmt)){ header("Location: ".BASE_URL."/karyawan/index.php?pesan=Data berhasil dihapus."); }
    mysqli_stmt_close($stmt);
}
?>