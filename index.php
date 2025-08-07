<?php
// WAJIB: Memuat konfigurasi utama
require_once 'config/database.php';
require_once 'config/check_login.php';

// Memuat template dengan include
include 'templates/header.php';
include 'templates/sidebar.php';

// (Kode untuk mengambil data dashboard tetap sama)
$total_karyawan = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM karyawan"))['total'];
$total_gaji_bulan_ini = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT SUM(gaji_bersih) as total FROM penggajian WHERE bulan = '".date('m')."' AND tahun = '".date('Y')."'"))['total'];
$nama_perusahaan = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT nama_perusahaan FROM perusahaan LIMIT 1"))['nama_perusahaan'] ?? 'Data Perusahaan Belum Ada';
?>

<div id="page-content-wrapper">
    <div class="container-fluid px-4">
        <h1 class="mt-4">Dashboard</h1>
        <p>Selamat datang di GajiApp, sistem manajemen penggajian untuk <strong><?= htmlspecialchars($nama_perusahaan) ?></strong>.</p>
        
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card text-center h-100">
                    <div class="card-body"><div class="display-4 text-primary"><i class="fa-solid fa-users"></i></div><h5 class="card-title mt-3">Jumlah Karyawan</h5><p class="card-text fs-1 fw-bold"><?= $total_karyawan ?></p><a href="<?= BASE_URL ?>/karyawan/" class="btn btn-outline-primary">Kelola Karyawan</a></div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card text-center h-100">
                    <div class="card-body"><div class="display-4 text-success"><i class="fa-solid fa-money-bill-wave"></i></div><h5 class="card-title mt-3">Total Gaji Bulan Ini (<?= date('F Y') ?>)</h5><p class="card-text fs-1 fw-bold"><?= format_rupiah($total_gaji_bulan_ini ?? 0) ?></p><a href="<?= BASE_URL ?>/penggajian/" class="btn btn-outline-success">Lihat Riwayat Gaji</a></div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Memuat footer dengan include
include 'templates/footer.php';
?>