<?php
require_once '../config/database.php';

if (mysqli_num_rows(mysqli_query($koneksi, "SELECT id_perusahaan FROM perusahaan LIMIT 1")) > 0) {
    header("Location: " . BASE_URL . "/perusahaan/index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama_perusahaan'];
    $alamat = $_POST['alamat'];
    $telepon = $_POST['telepon'];
    $email = $_POST['email'];
    // Ambil data baru
    $tanggal_berdiri = $_POST['tanggal_berdiri'];
    $no_referensi = $_POST['no_referensi'];

    $stmt = mysqli_prepare($koneksi, "INSERT INTO perusahaan (nama_perusahaan, alamat, telepon, email, tanggal_berdiri, no_referensi) VALUES (?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "ssssss", $nama, $alamat, $telepon, $email, $tanggal_berdiri, $no_referensi);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: " . BASE_URL . "/perusahaan/index.php?pesan=Data perusahaan berhasil ditambahkan!");
        exit();
    } else {
        $error = "Gagal menyimpan data perusahaan.";
    }
}

include(BASE_PATH . '/templates/header.php');
include(BASE_PATH . '/templates/sidebar.php');
?>

<div id="page-content-wrapper">
    <div class="container-fluid px-4">
        <h1 class="mt-4">Tambah Data Perusahaan</h1>
        <div class="card">
            <div class="card-body">
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>
                <form action="" method="POST">
                    <div class="mb-3"><label class="form-label">Nama Perusahaan</label><input type="text" class="form-control" name="nama_perusahaan" required></div>
                    <div class="mb-3"><label class="form-label">Alamat</label><textarea class="form-control" name="alamat" rows="3" required></textarea></div>
                    <div class="row">
                        <div class="col-md-6 mb-3"><label class="form-label">Telepon</label><input type="tel" class="form-control" name="telepon" required></div>
                        <div class="col-md-6 mb-3"><label class="form-label">Email</label><input type="email" class="form-control" name="email" required></div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3"><label class="form-label">Tanggal Berdiri</label><input type="date" class="form-control" name="tanggal_berdiri"></div>
                        <div class="col-md-6 mb-3"><label class="form-label">No. Referensi</label><input type="text" class="form-control" name="no_referensi"></div>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
include(BASE_PATH . '/templates/footer.php');
?>