<?php
require_once '../config/database.php';

$bulan_filter = isset($_GET['bulan']) ? $_GET['bulan'] : date('m');
$tahun_filter = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');
$data_penggajian = [];
$total_gaji_pokok = $total_tunjangan = $total_bonus = $total_potongan = $total_hutang = $total_gaji_bersih = 0;

if (isset($_GET['filter'])) {
    $query = "SELECT pg.*, k.nik, k.nama_karyawan FROM penggajian pg JOIN karyawan k ON pg.id_karyawan = k.id_karyawan WHERE pg.bulan = ? AND pg.tahun = ? ORDER BY k.nama_karyawan ASC";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "ss", $bulan_filter, $tahun_filter);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_assoc($result)) {
        $data_penggajian[] = $row;
        $total_gaji_pokok += $row['gaji_pokok']; $total_tunjangan += $row['tunjangan']; $total_bonus += $row['bonus'];
        $total_potongan += $row['potongan']; $total_hutang += $row['hutang']; $total_gaji_bersih += $row['gaji_bersih'];
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Laporan Penggajian</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style> @media print { .no-print { display: none !important; } body { font-size: 9pt; } } .report-header { border-bottom: 3px solid #000; } </style>
</head>
<body>
    <div class="container-fluid mt-4">
        <div class="card p-3 mb-4 no-print">
            <h5 class="mb-3">Filter Laporan</h5>
            <form action="" method="GET">
                <div class="row g-3 align-items-end">
                    <div class="col-md-5"><label class="form-label">Bulan</label><select name="bulan" class="form-select"><?php for ($i=1; $i<=12; $i++): $bln=str_pad($i,2,'0',STR_PAD_LEFT);?><option value="<?=$bln;?>" <?=($bulan_filter==$bln)?'selected':'';?>><?=date('F',mktime(0,0,0,$i,10));?></option><?php endfor;?></select></div>
                    <div class="col-md-5"><label class="form-label">Tahun</label><input type="number" name="tahun" class="form-control" value="<?= $tahun_filter ?>"></div>
                    <div class="col-md-2"><button type="submit" name="filter" value="true" class="btn btn-primary w-100">Tampilkan</button></div>
                </div>
            </form>
        </div>

        <?php if (isset($_GET['filter'])): ?>
            <div class="report-header text-center mb-4 pb-2"><h3>LAPORAN PENGGAJIAN</h3><h4>Periode: <?= date('F', mktime(0,0,0,$bulan_filter,10)) . ' ' . $tahun_filter ?></h4></div>
            <table class="table table-bordered table-sm">
                <thead class="table-dark text-center">
                    <tr><th>No</th><th>NIK</th><th>Nama Karyawan</th><th>Gaji Pokok</th><th>Tunjangan</th><th>Bonus</th><th>Potongan</th><th>Hutang</th><th>Gaji Bersih</th></tr>
                </thead>
                <tbody>
                    <?php if (!empty($data_penggajian)): $no = 1; foreach($data_penggajian as $data): ?>
                        <tr>
                            <td class="text-center"><?= $no++ ?></td><td><?= htmlspecialchars($data['nik']) ?></td><td><?= htmlspecialchars($data['nama_karyawan']) ?></td>
                            <td class="text-end"><?= format_rupiah($data['gaji_pokok']) ?></td><td class="text-end"><?= format_rupiah($data['tunjangan']) ?></td><td class="text-end"><?= format_rupiah($data['bonus']) ?></td>
                            <td class="text-end"><?= format_rupiah($data['potongan']) ?></td><td class="text-end"><?= format_rupiah($data['hutang']) ?></td><td class="text-end fw-bold"><?= format_rupiah($data['gaji_bersih']) ?></td>
                        </tr>
                    <?php endforeach; else: ?>
                        <tr><td colspan="9" class="text-center">Tidak ada data untuk periode yang dipilih.</td></tr>
                    <?php endif; ?>
                </tbody>
                <?php if (!empty($data_penggajian)): ?>
                <tfoot class="fw-bold">
                    <tr>
                        <td colspan="3" class="text-center">TOTAL</td>
                        <td class="text-end"><?= format_rupiah($total_gaji_pokok) ?></td><td class="text-end"><?= format_rupiah($total_tunjangan) ?></td><td class="text-end"><?= format_rupiah($total_bonus) ?></td>
                        <td class="text-end"><?= format_rupiah($total_potongan) ?></td><td class="text-end"><?= format_rupiah($total_hutang) ?></td><td class="text-end bg-info-subtle"><?= format_rupiah($total_gaji_bersih) ?></td>
                    </tr>
                </tfoot>
                <?php endif; ?>
            </table>
            <div class="text-center mt-4 no-print"><button onclick="window.print()" class="btn btn-primary">Cetak</button> <a href="<?= BASE_URL ?>/penggajian/index.php" class="btn btn-secondary">Kembali</a></div>
        <?php endif; ?>
    </div>
</body>
</html>