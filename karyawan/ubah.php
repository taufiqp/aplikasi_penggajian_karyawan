<?php
require_once '../config/database.php';
$id_karyawan = $_GET['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nik = $_POST['nik']; $nama_karyawan = $_POST['nama_karyawan']; $jabatan = $_POST['jabatan'];
    $alamat = $_POST['alamat']; $no_telpon = $_POST['no_telpon']; $email = $_POST['email'];
    $gaji_pokok = $_POST['gaji_pokok']; $nama_bank = $_POST['nama_bank']; $no_rekening = $_POST['no_rekening'];
    $tanggal_masuk = $_POST['tanggal_masuk'];

    $query = "UPDATE karyawan SET nik=?, nama_karyawan=?, jabatan=?, alamat=?, no_telpon=?, email=?, 
              gaji_pokok=?, nama_bank=?, no_rekening=?, tanggal_masuk=? WHERE id_karyawan=?";
    
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "ssssssdsssi", $nik, $nama_karyawan, $jabatan, $alamat, $no_telpon, $email, $gaji_pokok, $nama_bank, $no_rekening, $tanggal_masuk, $id_karyawan);
    
    if(mysqli_stmt_execute($stmt)){
        header("Location: ".BASE_URL."/karyawan/index.php?pesan=Data karyawan berhasil diubah!");
        exit();
    } else { $error = "Gagal mengubah data."; }
}

$stmt_get = mysqli_prepare($koneksi, "SELECT * FROM karyawan WHERE id_karyawan=?");
mysqli_stmt_bind_param($stmt_get, "i", $id_karyawan);
mysqli_stmt_execute($stmt_get);
$result = mysqli_stmt_get_result($stmt_get);
$data = mysqli_fetch_assoc($result);

include '../templates/header.php';
include '../templates/sidebar.php';
?>

<div id="page-content-wrapper">
    <div class="container-fluid px-4">
        <h1 class="mt-4">Ubah Data Karyawan</h1>
        <div class="card">
            <div class="card-body">
                <?php if(isset($error)) { echo "<div class='alert alert-danger'>$error</div>"; } ?>
                <form action="" method="POST">
                    <h5 class="mb-3">Informasi Pribadi</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3"><label class="form-label">NIK</label><input type="text" class="form-control" name="nik" value="<?= htmlspecialchars($data['nik']) ?>" required></div>
                        <div class="col-md-6 mb-3"><label class="form-label">Nama Lengkap</label><input type="text" class="form-control" name="nama_karyawan" value="<?= htmlspecialchars($data['nama_karyawan']) ?>" required></div>
                    </div>
                    <div class="mb-3"><label class="form-label">Alamat</label><textarea class="form-control" name="alamat" rows="2"><?= htmlspecialchars($data['alamat']) ?></textarea></div>
                    <div class="row">
                        <div class="col-md-6 mb-3"><label class="form-label">Email</label><input type="email" class="form-control" name="email" value="<?= htmlspecialchars($data['email']) ?>"></div>
                        <div class="col-md-6 mb-3"><label class="form-label">No. Telepon</label><input type="tel" class="form-control" name="no_telpon" value="<?= htmlspecialchars($data['no_telpon']) ?>"></div>
                    </div>

                    <hr class="my-4">
                    <h5 class="mb-3">Informasi Pekerjaan & Gaji</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3"><label class="form-label">Jabatan</label><input type="text" class="form-control" name="jabatan" value="<?= htmlspecialchars($data['jabatan']) ?>" required></div>
                        <div class="col-md-6 mb-3"><label class="form-label">Tanggal Masuk</label><input type="date" class="form-control" name="tanggal_masuk" value="<?= htmlspecialchars($data['tanggal_masuk']) ?>" required></div>
                    </div>
                    <div class="mb-3"><label class="form-label">Gaji Pokok</label><input type="number" class="form-control" name="gaji_pokok" value="<?= htmlspecialchars($data['gaji_pokok']) ?>" required></div>
                    <div class="row">
                        <div class="col-md-6 mb-3"><label class="form-label">Nama Bank</label><input type="text" class="form-control" name="nama_bank" value="<?= htmlspecialchars($data['nama_bank']) ?>"></div>
                        <div class="col-md-6 mb-3"><label class="form-label">No. Rekening</label><input type="text" class="form-control" name="no_rekening" value="<?= htmlspecialchars($data['no_rekening']) ?>"></div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    <a href="<?= BASE_URL ?>/karyawan/index.php" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
include '../templates/footer.php';
?>