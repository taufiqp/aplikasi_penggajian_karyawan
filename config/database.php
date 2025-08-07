<?php
// config/database.php

// --- PENGATURAN DASAR APLIKASI ---
define('BASE_URL', 'http://localhost:3000'); // Sesuaikan dengan URL aplikasi Anda
define('BASE_PATH', 'C:/xampp/htdocs/website_penggajian'); // Sesuaikan path ini jika XAMPP Anda diinstal di lokasi lain

// --- KONEKSI DATABASE ---
date_default_timezone_set('Asia/Jakarta');
$host = 'localhost';
$user = 'root';
$pass = ''; // Masukkan password database Anda jika ada
$db   = 'db_penggajian';
$port = 3306;

$koneksi = mysqli_connect($host, $user, $pass, $db, $port);

if (!$koneksi) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}

// Fungsi format Rupiah
function format_rupiah($angka) {
    return "Rp " . number_format($angka, 0, ',', '.');
}
?>