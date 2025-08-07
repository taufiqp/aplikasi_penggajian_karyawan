<?php
// Bagian PHP di atas (untuk proses POST) tetap sama seperti sebelumnya
require_once '../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_karyawan = $_POST['id_karyawan']; $bulan = $_POST['bulan']; $tahun = $_POST['tahun'];
    $tunjangan = $_POST['tunjangan'] ?? 0; $bonus = $_POST['bonus'] ?? 0;
    $potongan = $_POST['potongan'] ?? 0; $bayar_hutang = $_POST['hutang'] ?? 0;
    $sisa_hutang_lalu = $_POST['sisa_hutang_lalu'] ?? 0;
    $q_karyawan = mysqli_query($koneksi, "SELECT gaji_pokok FROM karyawan WHERE id_karyawan = $id_karyawan");
    $gaji_pokok = mysqli_fetch_assoc($q_karyawan)['gaji_pokok'];
    $gaji_bersih = ($gaji_pokok + $tunjangan + $bonus) - ($potongan + $bayar_hutang);
    $sisa_hutang_baru = $sisa_hutang_lalu - $bayar_hutang;
    $stmt = mysqli_prepare($koneksi, "INSERT INTO penggajian (id_karyawan, bulan, tahun, gaji_pokok, tunjangan, bonus, potongan, hutang, sisa_hutang, gaji_bersih) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "issddddddd", $id_karyawan, $bulan, $tahun, $gaji_pokok, $tunjangan, $bonus, $potongan, $bayar_hutang, $sisa_hutang_baru, $gaji_bersih);
    if(mysqli_stmt_execute($stmt)){ header("Location: ".BASE_URL."/penggajian/index.php?pesan=Gaji berhasil dibuat!"); exit(); } else { $error = "Gagal membuat data gaji."; }
}

include(BASE_PATH . '/templates/header.php');
include(BASE_PATH . '/templates/sidebar.php');
?>

<div id="page-content-wrapper">
    <div class="container-fluid px-4">
        <h1 class="mt-4">Buat Gaji Baru</h1>
        <div class="card">
            <div class="card-body">
                <?php if(isset($error)) { echo "<div class='alert alert-danger'>$error</div>"; } ?>
                <form action="" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Pilih Karyawan</label>
                        <select class="form-select" name="id_karyawan" id="id_karyawan" required>
                            <option value="" disabled selected>-- Pilih Karyawan --</option>
                            <?php 
                            $k_list = mysqli_query($koneksi, "SELECT id_karyawan, nik, nama_karyawan FROM karyawan ORDER BY nama_karyawan");
                            while ($k = mysqli_fetch_assoc($k_list)): ?>
                                <option value="<?= $k['id_karyawan']; ?>"><?= htmlspecialchars($k['nik']) . ' - ' . htmlspecialchars($k['nama_karyawan']); ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3"><label class="form-label">Bulan</label><select name="bulan" class="form-select"><?php for ($i=1; $i<=12; $i++): $bln=str_pad($i,2,'0',STR_PAD_LEFT);?><option value="<?=$bln;?>" <?=(date('m')==$bln)?'selected':'';?>><?=date('F',mktime(0,0,0,$i,10));?></option><?php endfor;?></select></div>
                        <div class="col-md-6 mb-3"><label class="form-label">Tahun</label><input type="number" class="form-control" name="tahun" value="<?= date('Y'); ?>" required></div>
                    </div>
                    <hr>

                    <div class="mb-3">
                        <label class="form-label">Gaji Pokok</label>
                        <div id="display_gaji_pokok" class="form-control-plaintext fw-bold fs-5">-</div>
                    </div>
                    
                    <h5 class="mb-3">Komponen Gaji</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3"><label class="form-label">Tunjangan</label><input type="number" class="form-control financial-input" id="tunjangan" name="tunjangan" value="0"></div>
                        <div class="col-md-6 mb-3"><label class="form-label">Bonus</label><input type="number" class="form-control financial-input" id="bonus" name="bonus" value="0"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3"><label class="form-label">Potongan Lain-lain</label><input type="number" class="form-control financial-input" id="potongan" name="potongan" value="0"></div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Pembayaran Hutang</label>
                            <input type="number" class="form-control financial-input" id="hutang" name="hutang" value="0">
                            <small id="info_sisa_hutang" class="form-text text-muted"></small>
                            <input type="hidden" id="sisa_hutang_lalu" name="sisa_hutang_lalu" value="0">
                        </div>
                    </div>

                    <div class="alert alert-info mt-3">
                        <h5 class="mb-0 fw-bold">Perkiraan Gaji Bersih: <span id="display_gaji_bersih">-</span></h5>
                    </div>

                    <button type="submit" class="btn btn-primary mt-3">Simpan & Buat Gaji</button>
                    <a href="<?= BASE_URL ?>/penggajian/index.php" class="btn btn-secondary mt-3">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Variabel global untuk menyimpan data gaji
    let gajiPokok = 0;

    // Fungsi untuk memformat angka menjadi Rupiah
    function formatRupiah(angka) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(angka);
    }

    // Fungsi utama untuk menghitung gaji bersih
    function calculateNetSalary() {
        // Ambil nilai dari setiap input, jika kosong anggap 0
        let tunjangan = parseFloat($('#tunjangan').val()) || 0;
        let bonus = parseFloat($('#bonus').val()) || 0;
        let potongan = parseFloat($('#potongan').val()) || 0;
        let hutang = parseFloat($('#hutang').val()) || 0;

        // Hitung total penerimaan dan potongan
        let totalPenerimaan = gajiPokok + tunjangan + bonus;
        let totalPotongan = potongan + hutang;
        let gajiBersih = totalPenerimaan - totalPotongan;

        // Tampilkan hasil di layar
        $('#display_gaji_bersih').text(formatRupiah(gajiBersih));
    }

    // Event listener saat karyawan dipilih
    $('#id_karyawan').change(function() {
        var id_karyawan = $(this).val();
        if (id_karyawan) {
            $.ajax({
                type: 'POST',
                url: 'get_sisa_hutang.php', // File backend
                data: { id_karyawan: id_karyawan },
                dataType: 'json',
                success: function(response) {
                    // Update Gaji Pokok
                    gajiPokok = parseFloat(response.gaji_pokok) || 0;
                    $('#display_gaji_pokok').text(formatRupiah(gajiPokok));

                    // Update Sisa Hutang
                    var sisa_hutang = parseFloat(response.sisa_hutang) || 0;
                    $('#info_sisa_hutang').text('Sisa hutang bulan lalu: ' + formatRupiah(sisa_hutang));
                    $('#sisa_hutang_lalu').val(sisa_hutang);
                    
                    // Panggil fungsi hitung untuk pertama kali
                    calculateNetSalary();
                }
            });
        }
    });

    // Event listener untuk setiap input finansial
    // .on('keyup change') berarti fungsi akan jalan saat diketik atau saat nilainya berubah
    $('.financial-input').on('keyup change', function() {
        calculateNetSalary();
    });
});
</script>

<?php
include(BASE_PATH . '/templates/footer.php');
?>