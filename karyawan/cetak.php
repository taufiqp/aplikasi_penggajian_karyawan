<?php
// Memanggil file konfigurasi
require_once '../config/database.php';

// Mengambil data perusahaan untuk kop laporan
$perusahaan_result = mysqli_query($koneksi, "SELECT nama_perusahaan, alamat FROM perusahaan LIMIT 1");
$perusahaan = mysqli_fetch_assoc($perusahaan_result);

// Mengambil semua data karyawan
$karyawan_result = mysqli_query($koneksi, "SELECT * FROM karyawan ORDER BY nama_karyawan ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Data Karyawan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* CSS untuk menyembunyikan elemen saat mencetak */
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                font-size: 11pt;
            }
        }
        .report-header {
            border-bottom: 3px solid #000;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <div class="report-header text-center mb-4 pb-2">
            <h3 class="mb-0"><?= htmlspecialchars($perusahaan['nama_perusahaan'] ?? 'Nama Perusahaan') ?></h3>
            <p class="mb-1"><?= htmlspecialchars($perusahaan['alamat'] ?? 'Alamat Perusahaan') ?></p>
            <h4 class="mt-3">LAPORAN DATA KARYAWAN</h4>
            <p>Per Tanggal: <?= date('d F Y') ?></p>
        </div>

        <table class="table table-bordered">
            <thead class="table-dark">
                <tr class="text-center">
                    <th>No</th>
                    <th>NIK</th>
                    <th>Nama Karyawan</th>
                    <th>Jabatan</th>
                    <th>Tanggal Masuk</th>
                    <th class="text-end">Gaji Pokok</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($karyawan_result) > 0): ?>
                    <?php $no = 1; ?>
                    <?php while($data = mysqli_fetch_assoc($karyawan_result)): ?>
                        <tr>
                            <td class="text-center"><?= $no++ ?></td>
                            <td><?= htmlspecialchars($data['nik']) ?></td>
                            <td><?= htmlspecialchars($data['nama_karyawan']) ?></td>
                            <td><?= htmlspecialchars($data['jabatan']) ?></td>
                            <td class="text-center"><?= date('d-m-Y', strtotime($data['tanggal_masuk'])) ?></td>
                            <td class="text-end"><?= format_rupiah($data['gaji_pokok']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">Tidak ada data karyawan untuk ditampilkan.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="text-center mt-4 no-print">
            <button onclick="window.print()" class="btn btn-primary">
                <i class="fa-solid fa-print me-1"></i> Cetak Laporan
            </button>
            <a href="<?= BASE_URL ?>/karyawan/index.php" class="btn btn-secondary">
                <i class="fa-solid fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>
</body>
</html>