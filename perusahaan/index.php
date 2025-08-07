<?php
require_once '../config/database.php';
include(BASE_PATH . '/templates/header.php');
include(BASE_PATH . '/templates/sidebar.php');

$result = mysqli_query($koneksi, "SELECT * FROM perusahaan LIMIT 1");
$perusahaan = mysqli_fetch_assoc($result);
?>

<div id="page-content-wrapper">
    <div class="container-fluid px-4">
        <h1 class="mt-4">Data Perusahaan</h1>
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0"><i class="fa-solid fa-building me-2"></i> Profil Perusahaan</h5>
            </div>
            <div class="card-body">
                <?php if (isset($_GET['pesan'])): ?>
                    <div class='alert alert-success'><?= htmlspecialchars($_GET['pesan']) ?></div>
                <?php endif; ?>

                <?php if ($perusahaan): ?>
                    <table class="table table-bordered table-striped">
                        <tr><th style="width: 200px;">Nama Perusahaan</th><td><?= htmlspecialchars($perusahaan['nama_perusahaan']) ?></td></tr>
                        <tr><th>Alamat</th><td><?= nl2br(htmlspecialchars($perusahaan['alamat'])) ?></td></tr>
                        <tr><th>Telepon</th><td><?= htmlspecialchars($perusahaan['telepon']) ?></td></tr>
                        <tr><th>Email</th><td><?= htmlspecialchars($perusahaan['email']) ?></td></tr>
                        <tr><th>Tanggal Berdiri</th><td><?= $perusahaan['tanggal_berdiri'] ? date('d F Y', strtotime($perusahaan['tanggal_berdiri'])) : '-' ?></td></tr>
                        <tr><th>No. Referensi</th><td><?= htmlspecialchars($perusahaan['no_referensi'] ?? '-') ?></td></tr>
                    </table>
                    <div class="mt-3 text-end">
                        <a href="<?= BASE_URL ?>/perusahaan/ubah.php?id=<?= $perusahaan['id_perusahaan'] ?>" class="btn btn-warning"><i class="fa-solid fa-pen-to-square me-1"></i> Ubah Data</a>
                        <a href="<?= BASE_URL ?>/perusahaan/hapus.php?id=<?= $perusahaan['id_perusahaan'] ?>" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus data perusahaan?')"><i class="fa-solid fa-trash me-1"></i> Hapus Data</a>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <p>Data perusahaan belum ada.</p>
                        <a href="<?= BASE_URL ?>/perusahaan/tambah.php" class="btn btn-primary"><i class="fa-solid fa-plus me-1"></i> Tambah Data Perusahaan</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php
include(BASE_PATH . '/templates/footer.php');
?>