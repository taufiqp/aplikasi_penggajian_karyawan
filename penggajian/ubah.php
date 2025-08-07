<?php
require_once '../config/database.php';
$id_penggajian = $_GET['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_karyawan = $_POST['id_karyawan']; $bulan = $_POST['bulan']; $tahun = $_POST['tahun'];
    $tunjangan = $_POST['tunjangan'] ?? 0; $bonus = $_POST['bonus'] ?? 0;
    $potongan = $_POST['potongan'] ?? 0; $bayar_hutang = $_POST['hutang'] ?? 0;

    $q_karyawan = mysqli_query($koneksi, "SELECT gaji_pokok FROM karyawan WHERE id_karyawan = $id_karyawan");
    $gaji_pokok = mysqli_fetch_assoc($q_karyawan)['gaji_pokok'];
    $gaji_bersih = ($gaji_pokok + $tunjangan + $bonus) - ($potongan + $bayar_hutang);
    $sisa_hutang = 0; // Logika sisa hutang disederhanakan

    $stmt = mysqli_prepare($koneksi, "UPDATE penggajian SET id_karyawan=?, bulan=?, tahun=?, gaji_pokok=?, tunjangan=?, bonus=?, potongan=?, hutang=?, sisa_hutang=?, gaji_bersih=? WHERE id_penggajian=?");
    mysqli_stmt_bind_param($stmt, "issdddddddi", $id_karyawan, $bulan, $tahun, $gaji_pokok, $tunjangan, $bonus, $potongan, $bayar_hutang, $sisa_hutang, $gaji_bersih, $id_penggajian);

    if(mysqli_stmt_execute($stmt)){
        header("Location: ".BASE_URL."/penggajian/index.php?pesan=Data gaji berhasil diubah!");
        exit();
    } else { $error = "Gagal mengubah data."; }
}

$stmt_get = mysqli_prepare($koneksi, "SELECT * FROM penggajian WHERE id_penggajian=?");
mysqli_stmt_bind_param($stmt_get, "i", $id_penggajian); mysqli_stmt_execute($stmt_get);
$result = mysqli_stmt_get_result($stmt_get); $data = mysqli_fetch_assoc($result);

include(BASE_PATH . '/templates/header.php');
include(BASE_PATH . '/templates/sidebar.php');
?>

<div id="page-content-wrapper">
    <div class="container-fluid px-4">
        <h1 class="mt-4">Ubah Data Gaji</h1>
        <div class="card">
            <div class="card-body">
                <?php if(isset($error)) { echo "<div class='alert alert-danger'>$error</div>"; } ?>
                <form action="" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Pilih Karyawan</label>
                        <select class="form-select" name="id_karyawan" required>
                            <?php $k_list = mysqli_query($koneksi, "SELECT id_karyawan, nik, nama_karyawan FROM karyawan ORDER BY nama_karyawan"); while ($k = mysqli_fetch_assoc($k_list)): ?>
                                <option value="<?= $k['id_karyawan']; ?>" <?= ($k['id_karyawan'] == $data['id_karyawan']) ? 'selected' : '' ?>><?= htmlspecialchars($k['nik']) . ' - ' . htmlspecialchars($k['nama_karyawan']); ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3"><label class="form-label">Bulan</label><select name="bulan" class="form-select"><?php for ($i=1; $i<=12; $i++): $bln=str_pad($i,2,'0',STR_PAD_LEFT);?><option value="<?=$bln;?>" <?=($data['bulan']==$bln)?'selected':'';?>><?=date('F',mktime(0,0,0,$i,10));?></option><?php endfor;?></select></div>
                        <div class="col-md-6 mb-3"><label class="form-label">Tahun</label><input type="number" class="form-control" name="tahun" value="<?= htmlspecialchars($data['tahun']) ?>" required></div>
                    </div>
                    <hr>
                    <h5 class="mb-3">Komponen Gaji</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3"><label class="form-label">Tunjangan</label><input type="number" class="form-control" name="tunjangan" value="<?= htmlspecialchars($data['tunjangan']) ?>"></div>
                        <div class="col-md-6 mb-3"><label class="form-label">Bonus</label><input type="number" class="form-control" name="bonus" value="<?= htmlspecialchars($data['bonus']) ?>"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3"><label class="form-label">Potongan Lain-lain</label><input type="number" class="form-control" name="potongan" value="<?= htmlspecialchars($data['potongan']) ?>"></div>
                        <div class="col-md-6 mb-3"><label class="form-label">Pembayaran Hutang</label><input type="number" class="form-control" name="hutang" value="<?= htmlspecialchars($data['hutang']) ?>"></div>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    <a href="<?= BASE_URL ?>/penggajian/index.php" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
include(BASE_PATH . '/templates/footer.php');
?>