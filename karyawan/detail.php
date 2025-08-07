<?php
require_once '../config/database.php';
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) { header('Location: ' . BASE_URL . '/karyawan/index.php'); exit(); }
$id_karyawan = $_GET['id'];
$stmt = mysqli_prepare($koneksi, "SELECT * FROM karyawan WHERE id_karyawan = ?");
mysqli_stmt_bind_param($stmt, "i", $id_karyawan); mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt); $karyawan = mysqli_fetch_assoc($result);
if (!$karyawan) {
    include 'templates/header.php'; include 'templates/sidebar.php';
    echo "<div id='page-content-wrapper'><div class='container-fluid px-4'><div class='alert alert-danger'>Data tidak ditemukan.</div></div></div>";
    include 'templates/footer.php'; exit();
}

include '../templates/header.php';
include '../templates/sidebar.php';
?>

<div id="page-content-wrapper">
    <div class="container-fluid px-4">
        <h1 class="mt-4">Detail Karyawan</h1>
        <div class="card">
            <div class="card-header"><h5 class="card-title mb-0"><i class="fa-solid fa-user-tie me-2"></i>Profil: <?= htmlspecialchars($karyawan['nama_karyawan']) ?></h5></div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 text-center">
                        <i class="fa-solid fa-user-circle fa-8x text-secondary mb-3"></i>
                        <h5><?= htmlspecialchars($karyawan['nama_karyawan']) ?></h5>
                        <p class="text-muted"><?= htmlspecialchars($karyawan['jabatan']) ?></p>
                    </div>
                    <div class="col-md-8">
                        <h6 class="text-primary">INFORMASI PRIBADI & KONTAK</h6>
                        <table class="table table-sm table-borderless">
                            <tr><th style="width:30%">NIK</th><td>: <?= htmlspecialchars($karyawan['nik']) ?></td></tr>
                            <tr><th>Alamat</th><td>: <?= htmlspecialchars($karyawan['alamat']) ?></td></tr>
                            <tr><th>Email</th><td>: <?= htmlspecialchars($karyawan['email']) ?></td></tr>
                            <tr><th>No. Telepon</th><td>: <?= htmlspecialchars($karyawan['no_telpon']) ?></td></tr>
                        </table>
                        <hr>
                        <h6 class="text-primary">INFORMASI PEKERJAAN & BANK</h6>
                        <table class="table table-sm table-borderless">
                            <tr><th style="width:30%">Jabatan</th><td>: <?= htmlspecialchars($karyawan['jabatan']) ?></td></tr>
                            <tr><th>Tanggal Masuk</th><td>: <?= date('d F Y', strtotime($karyawan['tanggal_masuk'])) ?></td></tr>
                            <tr><th>Gaji Pokok</th><td class="fw-bold">: <?= format_rupiah($karyawan['gaji_pokok']) ?></td></tr>
                            <tr><th>Bank</th><td>: <?= htmlspecialchars($karyawan['nama_bank']) ?></td></tr>
                            <tr><th>No. Rekening</th><td>: <?= htmlspecialchars($karyawan['no_rekening']) ?></td></tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card-footer text-end">
                <a href="<?= BASE_URL ?>/karyawan/index.php" class="btn btn-secondary">Kembali</a>
                <a href="<?= BASE_URL ?>/karyawan/ubah.php?id=<?= $karyawan['id_karyawan'] ?>" class="btn btn-warning">Ubah Data</a>
            </div>
        </div>
    </div>
</div>

<?php
include '../templates/footer.php';
?>