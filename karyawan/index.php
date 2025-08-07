<?php
/**
 * Memuat file konfigurasi dengan path relatif yang benar.
 */
require_once '../config/database.php';

/**
 * Memuat semua template menggunakan konstanta BASE_PATH.
 */
include(BASE_PATH . '/templates/header.php');
include(BASE_PATH . '/templates/sidebar.php');
?>

<div id="page-content-wrapper">
    <div class="container-fluid px-4">
        <h1 class="mt-4">Data Karyawan</h1>
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0"><i class="fa-solid fa-users me-2"></i> Daftar Karyawan</h5>
                <div>
                    <a href="<?= BASE_URL ?>/karyawan/cetak.php" class="btn btn-info btn-sm" target="_blank">
                        <i class="fa-solid fa-print me-1"></i> Cetak Data
                    </a>
                    
                    <a href="<?= BASE_URL ?>/karyawan/tambah.php" class="btn btn-primary btn-sm">
                        <i class="fa-solid fa-plus me-1"></i> Tambah Karyawan
                    </a>
                </div>
            </div>
            <div class="card-body">
                <?php
                // Menampilkan pesan notifikasi jika ada
                if (isset($_GET['pesan'])) {
                    echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                            " . htmlspecialchars($_GET['pesan']) . "
                            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                          </div>";
                }
                ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th class="text-center">No</th>
                                <th>NIK</th>
                                <th>Nama Karyawan</th>
                                <th>Jabatan</th>
                                <th class="text-end">Gaji Pokok</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT * FROM karyawan ORDER BY nama_karyawan ASC";
                            $result = mysqli_query($koneksi, $query);
                            $no = 1;
                            if (mysqli_num_rows($result) > 0) {
                                while ($data = mysqli_fetch_assoc($result)) {
                                    echo "<tr>";
                                    echo "<td class='text-center'>" . $no++ . "</td>";
                                    echo "<td>" . htmlspecialchars($data['nik']) . "</td>";
                                    echo "<td>" . htmlspecialchars($data['nama_karyawan']) . "</td>";
                                    echo "<td>" . htmlspecialchars($data['jabatan']) . "</td>";
                                    echo "<td class='text-end'>" . format_rupiah($data['gaji_pokok']) . "</td>";
                                    echo "<td class='text-center'>
                                            <a href='" . BASE_URL . "/karyawan/detail.php?id=" . $data['id_karyawan'] . "' class='btn btn-success btn-sm' title='Detail'><i class='fa-solid fa-eye'></i></a>
                                            <a href='" . BASE_URL . "/karyawan/ubah.php?id=" . $data['id_karyawan'] . "' class='btn btn-warning btn-sm' title='Ubah'><i class='fa-solid fa-pen-to-square'></i></a>
                                            <a href='" . BASE_URL . "/karyawan/hapus.php?id=" . $data['id_karyawan'] . "' class='btn btn-danger btn-sm' title='Hapus' onclick='return confirm(\"Apakah Anda yakin ingin menghapus data ini?\")'><i class='fa-solid fa-trash'></i></a>
                                          </td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='6' class='text-center'>Tidak ada data karyawan.</td></tr>";
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
/**
 * Memuat footer.
 */
include(BASE_PATH . '/templates/footer.php');
?>