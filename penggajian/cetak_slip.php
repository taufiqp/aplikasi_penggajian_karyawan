<?php
// Memuat file konfigurasi
require_once '../config/database.php';

// Validasi ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Error: ID Penggajian tidak valid atau tidak ditemukan.");
}
$id_penggajian = $_GET['id'];

// Query untuk mengambil semua data yang dibutuhkan dari 3 tabel
$query = "SELECT 
            pg.*, 
            k.nik, k.nama_karyawan, k.jabatan, k.alamat as alamat_karyawan, k.no_telpon,
            p.nama_perusahaan, p.alamat as alamat_perusahaan, p.telepon as telpon_perusahaan, p.tanggal_berdiri, p.no_referensi
          FROM 
            penggajian pg
          JOIN 
            karyawan k ON pg.id_karyawan = k.id_karyawan
          LEFT JOIN 
            perusahaan p ON 1=1
          WHERE 
            pg.id_penggajian = ? 
          LIMIT 1";

$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, "i", $id_penggajian);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    die("Data slip gaji tidak ditemukan.");
}

// Menyiapkan variabel untuk perhitungan dan tampilan
$periode = date('F Y', mktime(0, 0, 0, $data['bulan'], 1, $data['tahun']));
$total_penerimaan = $data['gaji_pokok'] + $data['bonus'] + $data['tunjangan'];
$total_potongan = $data['potongan'] + $data['hutang'];
$sisa_hutang_sebelumnya = $data['sisa_hutang'] + $data['hutang'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slip Gaji - <?= htmlspecialchars($data['nama_karyawan']) ?> - <?= $periode ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #e9ecef; }
        .slip-container {
            max-width: 800px;
            margin: 2rem auto;
            background-color: #fff;
            border: 1px solid #dee2e6;
            box-shadow: 0 0 15px rgba(0,0,0,0.05);
        }
        .slip-header { padding: 1.5rem; }
        .slip-body { padding: 1.5rem; }
        .slip-footer { padding: 1.5rem; }
        .company-title { font-weight: 700; font-size: 1.5rem; }
        .slip-title { font-weight: 700; font-size: 1.2rem; border-top: 1px solid #dee2e6; border-bottom: 1px solid #dee2e6; padding: 0.5rem 0;}
        .table-rincian td, .table-rincian th { padding: 0.4rem; }
        
        @media print {
            body { background-color: #fff; }
            .slip-container { margin: 0; border: none; box-shadow: none; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>
    <div class="slip-container">
        <div class="slip-header text-center">
            <h3 class="company-title"><?= htmlspecialchars($data['nama_perusahaan'] ?? 'Nama Perusahaan') ?></h3>
            <p class="mb-0 small">
                <?= htmlspecialchars($data['alamat_perusahaan'] ?? 'Alamat') ?><br>
                Telp: <?= htmlspecialchars($data['telpon_perusahaan'] ?? '-') ?> | Tgl Berdiri: <?= $data['tanggal_berdiri'] ? date('d-m-Y', strtotime($data['tanggal_berdiri'])) : '-' ?> | No. Ref: <?= htmlspecialchars($data['no_referensi'] ?? '-') ?>
            </p>
        </div>

        <div class="slip-body">
            <h5 class="text-center slip-title my-3">SLIP GAJI</h5>
            <div class="row small mb-3">
                <div class="col-6">
                    <p class="mb-1"><strong>Kode Karyawan:</strong> <?= htmlspecialchars($data['nik']) ?></p>
                    <p class="mb-1"><strong>Nama Karyawan:</strong> <?= htmlspecialchars($data['nama_karyawan']) ?></p>
                    <p class="mb-1"><strong>Jabatan:</strong> <?= htmlspecialchars($data['jabatan']) ?></p>
                </div>
                <div class="col-6 text-end">
                    <p class="mb-1"><strong>Periode Gaji:</strong> <?= $periode ?></p>
                    <p class="mb-1"><strong>Alamat:</strong> <?= htmlspecialchars($data['alamat_karyawan']) ?></p>
                    <p class="mb-1"><strong>Telepon:</strong> <?= htmlspecialchars($data['no_telpon']) ?></p>
                </div>
            </div>

            <div class="row">
                <div class="col-6">
                    <h6 class="text-success">PENERIMAAN</h6>
                    <table class="table table-sm table-rincian">
                        <tr><td>Gaji Pokok</td><td class="text-end"><?= format_rupiah($data['gaji_pokok']) ?></td></tr>
                        <tr><td>Bonus</td><td class="text-end"><?= format_rupiah($data['bonus']) ?></td></tr>
                        <tr><td>Lain-lain (Tunjangan)</td><td class="text-end"><?= format_rupiah($data['tunjangan']) ?></td></tr>
                    </table>
                </div>
                <div class="col-6">
                    <h6 class="text-danger">POTONGAN</h6>
                    <table class="table table-sm table-rincian">
                        <tr><td>Potongan</td><td class="text-end"><?= format_rupiah($data['potongan']) ?></td></tr>
                        <tr><td>Hutang Bulan Ini</td><td class="text-end"><?= format_rupiah($data['hutang']) ?></td></tr>
                    </table>
                </div>
            </div>
            
            <table class="table table-bordered mt-2">
                <tr class="table-light">
                    <th class="text-success">TOTAL PENERIMAAN</th>
                    <th class="text-success text-end"><?= format_rupiah($total_penerimaan) ?></th>
                </tr>
                <tr class="table-light">
                    <th class="text-danger">TOTAL POTONGAN</th>
                    <th class="text-danger text-end"><?= format_rupiah($total_potongan) ?></th>
                </tr>
                <tr class="table-primary">
                    <th class="h5">TOTAL DITERIMA (GAJI BERSIH)</th>
                    <th class="h5 text-end"><?= format_rupiah($data['gaji_bersih']) ?></th>
                </tr>
            </table>

            <div class="small border p-2 mt-3">
                <strong>Keterangan Hutang:</strong>
                Sisa Hutang Sebelumnya: <?= format_rupiah($sisa_hutang_sebelumnya) ?> | 
                Dibayar: <?= format_rupiah($data['hutang']) ?> | 
                Sisa Hutang Akhir: <strong><?= format_rupiah($data['sisa_hutang']) ?></strong>
            </div>
        </div>

        <div class="slip-footer">
            <div class="row mt-4">
                <div class="col-6 text-center">
                    <p>Disetujui Oleh,</p>
                    <br><br><br>
                    <p class="mb-0">( HRD / Pimpinan )</p>
                </div>
                <div class="col-6 text-center">
                    <p>Diterima Oleh,</p>
                    <br><br><br>
                    <p class="mb-0">( <?= htmlspecialchars($data['nama_karyawan']) ?> )</p>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center my-4 no-print">
        <button onclick="window.print()" class="btn btn-primary"><i class="fa-solid fa-print me-1"></i> Cetak Slip</button>
        <a href="<?= BASE_URL ?>/penggajian" class="btn btn-secondary"><i class="fa-solid fa-arrow-left me-1"></i> Kembali</a>
    </div>
</body>
</html>