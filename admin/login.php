<?php
session_start(); // Mulai session untuk menyimpan data login

include '../koneksi/koneksi.php'; // Koneksi ke database

$error = ''; // Variabel untuk menyimpan pesan error

if (isset($_POST['login'])) {
    $username = $_POST['user'];
    $password = $_POST['pass'];

    // Query untuk memeriksa kredensial pengguna
    $query = "SELECT * FROM user WHERE username='$username' AND password=MD5('$password')";
    $result = mysqli_query($koneksi, $query);

    if (mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);

        // Simpan informasi pengguna dalam session
        $_SESSION['id_admin'] = $user['id_admin'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['noHp'] = $user['noHp'];
        $_SESSION['alamat'] = $user['alamat'];
        $_SESSION['role'] = $user['role'];

        // Redirect berdasarkan peran
        if ($user['role'] === 'admin') {
            echo "<script>
                    alert('Login berhasil sebagai admin');
                    window.location.href = 'index.php';
                  </script>";
        } elseif ($user['role'] === 'user') {
            echo "<script>
                    alert('Login berhasil sebagai user');
                    window.location.href = '../home.php';
                  </script>";
        }
        exit();
    } else {
        $error = "Username atau Password salah";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Pempek Biniku Login</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
</head>

<body class="bg-gradient-to-r from-blue-500 to-blue-800">
    <div class="pt-8"></div>
    <div class="container mx-auto px-4">
        <!-- Outer Row -->
        <div class="flex justify-center">
            <div class="w-full md:w-1/2">
                <div class="bg-white rounded-lg shadow-2xl my-5 border-0">
                    <div class="p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="flex">
                            <div class="w-full">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="text-xl font-semibold text-gray-900 mb-4">Welcome Back!</h1>
                                    </div>
                                    <?php if (!empty($error)): ?>
                                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                                            <?php echo $error; ?>
                                        </div>
                                    <?php endif; ?>
                                    <form action="login.php" method="post" class="user">
                                        <div class="mb-4">
                                            <input type="text" name="user" class="w-full px-3 py-2 text-sm rounded-full border focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                id="exampleInputEmail" aria-describedby="emailHelp"
                                                placeholder="Username" required>
                                        </div>
                                        <div class="mb-4">
                                            <input type="password" name="pass" class="w-full px-3 py-2 text-sm rounded-full border focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                id="exampleInputPassword" placeholder="Password" required>
                                        </div>
                                        <button name="login" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-full">
                                            Login
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="../assets/vendor/jquery/jquery.min.js"></script>
    <!-- jQuery Easing -->
    <script src="../assets/vendor/jquery-easing/jquery.easing.min.js"></script>
</body>

</html>
