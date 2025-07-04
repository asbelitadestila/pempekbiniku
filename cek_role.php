<?php
// itnclude("koneksi/koneksi.php");
// session_start();

function cek_role($role_dibutuhkan) {
    // Cek apakah pengguna sudah login
    if (!isset($_SESSION['username'])) {
        // Jika belum login, redirect ke halaman login
        header("Location: admin/login.php");
        exit();
    }

    // Cek apakah role pengguna sesuai dengan yang dibutuhkan
    if ($_SESSION['role'] !== $role_dibutuhkan) {
        // Jika role tidak sesuai, tampilkan pesan error dan hentikan akses
        echo "<script>alert('Akses ditolak. Anda tidak memiliki izin untuk mengakses halaman ini.')</script>";
        exit();
    }
}
?>