<?php
require_once '../config/database.php';

// Pastikan id_karyawan dikirim
if (isset($_POST['id_karyawan'])) {
    $id_karyawan = $_POST['id_karyawan'];

    // --- Ambil Gaji Pokok ---
    $q_karyawan = mysqli_query($koneksi, "SELECT gaji_pokok FROM karyawan WHERE id_karyawan = $id_karyawan");
    $gaji_pokok = mysqli_fetch_assoc($q_karyawan)['gaji_pokok'] ?? 0;

    // --- Ambil Sisa Hutang Bulan Lalu ---
    $bulan_lalu = date('m', strtotime('-1 month'));
    $tahun_bulan_lalu = date('Y', strtotime('-1 month'));

    $query = "SELECT sisa_hutang FROM penggajian WHERE id_karyawan = ? AND bulan = ? AND tahun = ? ORDER BY id_penggajian DESC LIMIT 1";
    
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "iss", $id_karyawan, $bulan_lalu, $tahun_bulan_lalu);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $data_hutang = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    $sisa_hutang = $data_hutang['sisa_hutang'] ?? 0;

    // Kirim kedua data sebagai JSON
    echo json_encode([
        'gaji_pokok' => $gaji_pokok,
        'sisa_hutang' => $sisa_hutang
    ]);
}
?>