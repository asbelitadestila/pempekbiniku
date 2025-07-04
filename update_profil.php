<?php
session_start();
include 'koneksi/koneksi.php';

// Ambil ID user yang sedang login
$user_id = $_SESSION['id_admin'];

// Ambil data dari form
$username = $_POST['username'];
$no_hp = $_POST['no_hp'];
$alamat = $_POST['alamat'];
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];

// Cek apakah username sudah digunakan oleh user lain
$query_cek_username = "SELECT * FROM user WHERE username = '$username' AND id_admin != '$user_id'";
$result = mysqli_query($koneksi, $query_cek_username);

// Jika ada username yang sama selain dari user ini
if (mysqli_num_rows($result) > 0) {
    echo "<script>
        alert('Gagal memperbarui profil. Username sudah digunakan oleh pengguna lain.');
        window.history.back();
    </script>";
    exit();
}

// Jika password diisi, lakukan validasi dan update
if (!empty($password)) {
    if ($password !== $confirm_password) {
        echo "<script>
            alert('Password baru dan konfirmasi password tidak cocok.');
            window.history.back();
        </script>";
        exit();
    }
    
    // Enkripsi password baru
    // $password_hashed = password_hash($password, PASSWORD_DEFAULT);
    $password_hashed = md5($password);

    // Query untuk memperbarui data user dengan password
    $query_update = "UPDATE user SET noHp = '$no_hp', alamat = '$alamat', password = '$password_hashed' WHERE id_admin = '$user_id'";
} else {
    // Query untuk memperbarui data user tanpa password
    $query_update = "UPDATE user SET noHp = '$no_hp', alamat = '$alamat' WHERE id_admin = '$user_id'";
}

// Eksekusi query update
if (mysqli_query($koneksi, $query_update)) {
    echo "<script>
        alert('Profil berhasil diperbarui.');
        window.location.href = 'profil.php';
    </script>";
} else {
    echo "<script>
        alert('Gagal memperbarui profil. Silakan coba lagi.');
        window.history.back();
    </script>";
}
?>
