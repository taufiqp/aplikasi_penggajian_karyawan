<?php
// 1. Panggil config tetap dengan path relatif
require_once '../config/database.php';
// 2. Sekarang semua include bisa simpel
include '../templates/header.php';
include '../templates/sidebar.php';
// Ambil data perusahaan
$result = mysqli_query($koneksi, "SELECT * FROM perusahaan LIMIT 1");
$perusahaan = mysqli_fetch_assoc($result);
?>

<div id="page-content-wrapper">
    <div class="container-fluid px-4">
        <h1 class="mt-4">Data Riwayat Gaji</h1>
       <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0"><i class="fa-solid fa-file-invoice-dollar me-2"></i> Riwayat Gaji</h5>
                <div>
                    <a href="<?= BASE_URL ?>/penggajian/cetak.php" class="btn btn-info btn-sm" target="_blank">
                        <i class="fa-solid fa-print me-1"></i> Cetak Laporan
                    </a>
                    
                    <a href="<?= BASE_URL ?>/penggajian/tambah.php" class="btn btn-primary btn-sm">
                        <i class="fa-solid fa-plus me-1"></i> Buat Gaji Baru
                    </a>
                </div>
            </div>
            <div class="card-body">
                <?php if (isset($_GET['pesan'])) { echo "<div class='alert alert-success'>".htmlspecialchars($_GET['pesan'])."</div>"; } ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-bordered">
                        <thead class="table-dark">
                             <tr>
                                <th>No</th>
                                <th>Periode</th>
                                <th>NIK</th>
                                <th>Nama Karyawan</th>
                                <th class="text-end">Gaji Bersih</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT pg.id_penggajian, pg.bulan, pg.tahun, pg.gaji_bersih, k.nik, k.nama_karyawan FROM penggajian pg JOIN karyawan k ON pg.id_karyawan = k.id_karyawan ORDER BY pg.tahun DESC, pg.bulan DESC";
                            $result = mysqli_query($koneksi, $query);
                            $no = 1;
                            if (mysqli_num_rows($result) > 0) {
                                while ($data = mysqli_fetch_assoc($result)) {
                                    $periode = date('F Y', mktime(0, 0, 0, $data['bulan'], 1, $data['tahun']));
                                    echo "<tr>";
                                    echo "<td>".$no++."</td>";
                                    echo "<td>".htmlspecialchars($periode)."</td>";
                                    echo "<td>".htmlspecialchars($data['nik'])."</td>";
                                    echo "<td>".htmlspecialchars($data['nama_karyawan'])."</td>";
                                    echo "<td class='text-end'>".format_rupiah($data['gaji_bersih'])."</td>";
                                    echo "<td class='text-center'>
                                            <a href='".BASE_URL."/penggajian/detail.php?id=".$data['id_penggajian']."' class='btn btn-success btn-sm' title='Detail'><i class='fa-solid fa-eye'></i></a>
                                             <a href='".BASE_URL."/penggajian/ubah.php?id=".$data['id_penggajian']."' class='btn btn-warning btn-sm' title='Ubah'><i class='fa-solid fa-pen-to-square'></i></a>
                                            <a href='".BASE_URL."/penggajian/cetak_slip.php?id=".$data['id_penggajian']."' class='btn btn-info btn-sm' title='Cetak' target='_blank'><i class='fa-solid fa-print'></i></a>
                                            <a href='".BASE_URL."/penggajian/hapus.php?id=".$data['id_penggajian']."' class='btn btn-danger btn-sm' title='Hapus' onclick='return confirm(\"Yakin ingin menghapus data ini?\")'><i class='fa-solid fa-trash'></i></a>
                                          </td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='6' class='text-center'>Belum ada data penggajian.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Memuat footer
include '../templates/footer.php';
?>