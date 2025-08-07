<?php
include '../config/database.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $stmt = mysqli_prepare($koneksi, "DELETE FROM penggajian WHERE id_penggajian = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    
    if (mysqli_stmt_execute($stmt)) {
        header("Location: index.php?pesan=Data gaji berhasil dihapus.");
    } else {
        header("Location: index.php?pesan=Gagal menghapus data gaji.");
    }
    mysqli_stmt_close($stmt);
} else {
    header("Location: index.php");
}
exit();
?>