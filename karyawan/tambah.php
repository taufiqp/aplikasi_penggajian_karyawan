<?php
// WAJIB: Memuat konfigurasi utama
require_once '../config/database.php';

// Proses form saat disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil semua data dari form
    $nik = $_POST['nik'];
    $nama_karyawan = $_POST['nama_karyawan'];
    $jabatan = $_POST['jabatan'];
    $alamat = $_POST['alamat'];
    $no_telpon = $_POST['no_telpon'];
    $email = $_POST['email'];
    $gaji_pokok = $_POST['gaji_pokok'];
    $nama_bank = $_POST['nama_bank'];
    $no_rekening = $_POST['no_rekening'];
    $tanggal_masuk = $_POST['tanggal_masuk'];

    // Query INSERT dengan kolom-kolom baru
    $query = "INSERT INTO karyawan (nik, nama_karyawan, jabatan, alamat, no_telpon, email, gaji_pokok, nama_bank, no_rekening, tanggal_masuk) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = mysqli_prepare($koneksi, $query);
    // Sesuaikan tipe data di bind_param: sss sss dss s
    mysqli_stmt_bind_param($stmt, "ssssssdsss", $nik, $nama_karyawan, $jabatan, $alamat, $no_telpon, $email, $gaji_pokok, $nama_bank, $no_rekening, $tanggal_masuk);
    
    if(mysqli_stmt_execute($stmt)){
        header("Location: ".BASE_URL."/karyawan/index.php?pesan=Data karyawan berhasil ditambahkan");
        exit();
    } else {
        $error = "Gagal menambahkan data. Pastikan NIK tidak duplikat.";
    }
    mysqli_stmt_close($stmt);
}

// Memuat template
include '../templates/header.php';
include '../templates/sidebar.php';
?>

<div id="page-content-wrapper">
    <div class="container-fluid px-4">
        <h1 class="mt-4">Tambah Karyawan Baru</h1>
        <div class="card">
            <div class="card-body">
                <?php if(isset($error)) { echo "<div class='alert alert-danger'>$error</div>"; } ?>
                <form action="" method="POST">
                    <h5 class="mb-3">Informasi Pribadi</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3"><label class="form-label">NIK</label><input type="text" class="form-control" name="nik" required></div>
                        <div class="col-md-6 mb-3"><label class="form-label">Nama Lengkap</label><input type="text" class="form-control" name="nama_karyawan" required></div>
                    </div>
                    <div class="mb-3"><label class="form-label">Alamat</label><textarea class="form-control" name="alamat" rows="2"></textarea></div>
                    <div class="row">
                        <div class="col-md-6 mb-3"><label class="form-label">Email</label><input type="email" class="form-control" name="email"></div>
                        <div class="col-md-6 mb-3"><label class="form-label">No. Telepon</label><input type="tel" class="form-control" name="no_telpon"></div>
                    </div>

                    <hr class="my-4">
                    <h5 class="mb-3">Informasi Pekerjaan & Gaji</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3"><label class="form-label">Jabatan</label><input type="text" class="form-control" name="jabatan" required></div>
                        <div class="col-md-6 mb-3"><label class="form-label">Tanggal Masuk</label><input type="date" class="form-control" name="tanggal_masuk" required></div>
                    </div>
                    <div class="mb-3"><label class="form-label">Gaji Pokok</label><input type="number" class="form-control" name="gaji_pokok" required></div>
                    <div class="row">
                        <div class="col-md-6 mb-3"><label class="form-label">Nama Bank</label><input type="text" class="form-control" name="nama_bank"></div>
                        <div class="col-md-6 mb-3"><label class="form-label">No. Rekening</label><input type="text" class="form-control" name="no_rekening"></div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="<?= BASE_URL ?>/karyawan/index.php" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
include '../templates/footer.php';
?>