<?php
require_once '../config/database.php';
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) { header('Location: '.BASE_URL.'/penggajian/index.php'); exit(); }
$id_penggajian = $_GET['id'];

$query = "SELECT pg.*, k.nik, k.nama_karyawan, k.jabatan, p.nama_perusahaan FROM penggajian pg JOIN karyawan k ON pg.id_karyawan = k.id_karyawan LEFT JOIN perusahaan p ON 1=1 WHERE pg.id_penggajian = ? LIMIT 1";
$stmt = mysqli_prepare($koneksi, $query); mysqli_stmt_bind_param($stmt, "i", $id_penggajian); mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt); $data = mysqli_fetch_assoc($result);

if (!$data) {
    include(BASE_PATH . '/templates/header.php'); include(BASE_PATH . '/templates/sidebar.php');
    echo "<div id='page-content-wrapper'><div class='container-fluid px-4'><div class='alert alert-danger'>Data tidak ditemukan.</div></div></div>";
    include(BASE_PATH . '/templates/footer.php'); exit();
}

$periode = date('F Y', mktime(0, 0, 0, $data['bulan'], 1, $data['tahun']));
$total_penerimaan = $data['gaji_pokok'] + $data['tunjangan'] + $data['bonus'];
$total_potongan = $data['potongan'] + $data['hutang'];

include(BASE_PATH . '/templates/header.php');
include(BASE_PATH . '/templates/sidebar.php');
?>

<div id="page-content-wrapper">
    <div class="container-fluid px-4">
        <h1 class="mt-4">Detail Gaji Karyawan</h1>
        <div class="card col-md-10 mx-auto">
            <div class="card-header"><h5 class="card-title mb-0"><i class="fa-solid fa-receipt me-2"></i>Rincian Gaji</h5></div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6"><p class="mb-1"><strong>Karyawan:</strong> <?= htmlspecialchars($data['nama_karyawan']) ?></p><p class="mb-0"><strong>Jabatan:</strong> <?= htmlspecialchars($data['jabatan']) ?></p></div>
                    <div class="col-md-6 text-md-end"><p class="mb-0"><strong>Periode Gaji:</strong> <?= htmlspecialchars($periode) ?></p></div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-success fw-bold">PENERIMAAN</h6>
                        <table class="table table-sm table-borderless">
                            <tr><td>Gaji Pokok</td><td class="text-end"><?= format_rupiah($data['gaji_pokok']) ?></td></tr>
                            <tr><td>Tunjangan</td><td class="text-end"><?= format_rupiah($data['tunjangan']) ?></td></tr>
                            <tr><td>Bonus</td><td class="text-end"><?= format_rupiah($data['bonus']) ?></td></tr>
                            <tfoot class="table-group-divider"><tr><th>Total Penerimaan</th><th class="text-end"><?= format_rupiah($total_penerimaan) ?></th></tr></tfoot>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-danger fw-bold">POTONGAN</h6>
                        <table class="table table-sm table-borderless">
                            <tr><td>Potongan Lain</td><td class="text-end"><?= format_rupiah($data['potongan']) ?></td></tr>
                            <tr><td>Pembayaran Hutang</td><td class="text-end"><?= format_rupiah($data['hutang']) ?></td></tr>
                            <tfoot class="table-group-divider"><tr><th>Total Potongan</th><th class="text-end"><?= format_rupiah($total_potongan) ?></th></tr></tfoot>
                        </table>
                    </div>
                </div>
                <hr>
                <div class="alert alert-info mt-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">GAJI BERSIH (Take Home Pay)</h5>
                        <h4 class="mb-0 fw-bolder"><?= format_rupiah($data['gaji_bersih']) ?></h4>
                    </div>
                </div>
            </div>
            <div class="card-footer text-end">
                <a href="<?= BASE_URL ?>/penggajian/index.php" class="btn btn-secondary">Kembali</a>
                <a href="<?= BASE_URL ?>/penggajian/cetak_slip.php?id=<?= $id_penggajian ?>" class="btn btn-primary" target="_blank">Cetak Slip</a>
            </div>
        </div>
    </div>
</div>

<?php
include(BASE_PATH . '/templates/footer.php');
?>