<div class="offcanvas offcanvas-start" tabindex="-1" id="sidebarMenu" aria-labelledby="sidebarMenuLabel">
  
  <div class="offcanvas-header">
    <h5 class="offcanvas-title" id="sidebarMenuLabel">
        <i class="fa-solid fa-rocket me-2"></i> Menu Navigasi
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>

  <div class="offcanvas-body p-0">
    <div class="list-group list-group-flush">
        <a href="<?= BASE_URL ?>/index.php" class="list-group-item active">
            <i class="fa-solid fa-house fa-fw me-3"></i>Beranda
        </a>
        <a href="<?= BASE_URL ?>/perusahaan/index.php" class="list-group-item">
            <i class="fa-solid fa-building fa-fw me-3"></i>Perusahaan
        </a>
        <a href="<?= BASE_URL ?>/karyawan/index.php" class="list-group-item">
            <i class="fa-solid fa-users fa-fw me-3"></i>Karyawan
        </a>
        <a href="<?= BASE_URL ?>/penggajian/index.php" class="list-group-item">
            <i class="fa-solid fa-file-invoice-dollar fa-fw me-3"></i>Data Gaji
        </a>
    </div>
  </div>

  <div class="list-group list-group-flush mt-auto">
    <a href="<?= BASE_URL ?>/logout.php" class="list-group-item list-group-item-action list-group-item-danger p-3">
        <i class="fa-solid fa-right-from-bracket fa-fw me-3"></i>Logout
    </a>
</div>

</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const links = document.querySelectorAll('.list-group-item');
    const currentLocation = window.location.pathname;

    links.forEach(link => {
        // Hapus kelas 'active' dari semua link terlebih dahulu
        link.classList.remove('active');
        
        // Jika href link cocok dengan path URL saat ini, tambahkan kelas 'active'
        if (link.getAttribute('href').includes(currentLocation)) {
            if (currentLocation !== '<?= BASE_URL ?>/' && link.getAttribute('href') === '<?= BASE_URL ?>/index.php') {
                // Jangan tandai 'Beranda' jika bukan halaman beranda
            } else {
                link.classList.add('active');
            }
        }
    });

    // Penanganan khusus untuk halaman beranda
    if (currentLocation === '<?= rtrim(parse_url(BASE_URL, PHP_URL_PATH), '/') ?>/' || currentLocation === '<?= rtrim(parse_url(BASE_URL, PHP_URL_PATH), '/') ?>/index.php') {
        document.querySelector('a[href="<?= BASE_URL ?>/index.php"]').classList.add('active');
    }
});


</script>