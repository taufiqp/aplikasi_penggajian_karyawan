<?php
// Memuat file konfigurasi
require_once 'config/database.php';
$error = '';
$sukses = '';

// Memproses form saat disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = trim($_POST['nama']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // 1. Validasi Input
    if (empty($nama) || empty($username) || empty($password)) {
        $error = "Semua kolom wajib diisi!";
    } elseif ($password !== $confirm_password) {
        $error = "Konfirmasi password tidak cocok!";
    } else {
        // 2. Cek apakah username sudah ada
        $stmt_check = mysqli_prepare($koneksi, "SELECT id FROM users WHERE username = ?");
        mysqli_stmt_bind_param($stmt_check, "s", $username);
        mysqli_stmt_execute($stmt_check);
        mysqli_stmt_store_result($stmt_check);

        if (mysqli_stmt_num_rows($stmt_check) > 0) {
            $error = "Username sudah digunakan, silakan pilih yang lain.";
        } else {
            // 3. Jika semua validasi lolos, hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // 4. Masukkan pengguna baru ke database
            $stmt_insert = mysqli_prepare($koneksi, "INSERT INTO users (nama, username, password) VALUES (?, ?, ?)");
            mysqli_stmt_bind_param($stmt_insert, "sss", $nama, $username, $hashed_password);
            
            if (mysqli_stmt_execute($stmt_insert)) {
                // Jika berhasil, arahkan ke halaman login dengan pesan sukses
                header("Location: login.php?status=registrasi_sukses");
                exit();
            } else {
                $error = "Terjadi kesalahan. Gagal mendaftar.";
            }
            mysqli_stmt_close($stmt_insert);
        }
        mysqli_stmt_close($stmt_check);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi - Admin GajiApp</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --bs-primary: #4f46e5; --bs-light: #f8fafc; }
        body { font-family: 'Inter', sans-serif; background-color: var(--bs-light); }
        .register-container { min-height: 100vh; }
        .register-card { border: none; border-radius: 1rem; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.07), 0 4px 6px -2px rgba(0,0,0,0.05); }
        .form-control:focus { border-color: var(--bs-primary); box-shadow: 0 0 0 0.25rem rgba(79, 70, 229, 0.25); }
        .btn-primary { background-color: var(--bs-primary); border-color: var(--bs-primary); }
    </style>
</head>
<body>
    <div class="container">
        <div class="row d-flex justify-content-center align-items-center register-container">
            <div class="col-md-6 col-lg-5 col-xl-4">
                <div class="card register-card">
                    <div class="card-body p-4 p-md-5">
                        <div class="text-center mb-4">
                            <h2 class="fw-bold">Registrasi Admin Baru</h2>
                            <p class="text-muted">Buat akun untuk mengelola GajiApp</p>
                        </div>
                        
                        <?php if ($error): ?>
                            <div class="alert alert-danger" role="alert"><?= $error ?></div>
                        <?php endif; ?>
                        
                        <form action="register.php" method="POST">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama Lengkap" required>
                                <label for="nama">Nama Lengkap</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
                                <label for="username">Username</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                                <label for="password">Password</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Konfirmasi Password" required>
                                <label for="confirm_password">Konfirmasi Password</label>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg fw-bold">Daftar</button>
                            </div>
                        </form>
                        <div class="text-center mt-4">
                            <small class="text-muted">Sudah punya akun? <a href="login.php">Login di sini</a></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>