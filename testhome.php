<?php 
$test = 1;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body>
    <nav class="bg-red-600 flex justify-between items-center px-[7%] py-1 fixed top-0 left-0 right-0 z-50">
    <img src="./assets/foto/Logoo.png" class="w-[75px]">
    <div class="navbar-menu">
        <a href="index.php" class="text-white text-base mx-4 font-bold inline-block hover:text-red-200 relative after:content-[''] after:block after:pb-2 after:border-b after:border-white after:scale-x-0 after:transition-transform after:duration-200 hover:after:scale-x-50">Beranda</a>
        <a href="#about" class="text-white text-base mx-4 font-bold inline-block hover:text-red-200 relative after:content-[''] after:block after:pb-2 after:border-b after:border-white after:scale-x-0 after:transition-transform after:duration-200 hover:after:scale-x-50">About</a>
        <a href="#produk" class="text-white text-base mx-4 font-bold inline-block hover:text-red-200 relative after:content-[''] after:block after:pb-2 after:border-b after:border-white after:scale-x-0 after:transition-transform after:duration-200 hover:after:scale-x-50">Produk</a>
        <a href="#kontak" class="text-white text-base mx-4 font-bold inline-block hover:text-red-200 relative after:content-[''] after:block after:pb-2 after:border-b after:border-white after:scale-x-0 after:transition-transform after:duration-200 hover:after:scale-x-50">Kontak</a>
    </div>
    <div class="flex">
        <div class="users">
            <?php if(isset($_SESSION['username'])){ ?>
            <a href="keranjang.php" class="relative">
                <i class="fas fa-shopping-cart text-white mx-2"></i>
                <?php
                    $sql_keranjang = "SELECT COUNT(*) as total FROM keranjang_user WHERE id_user = '$user_id'";
                    $result=mysqli_query($koneksi, $sql_keranjang);
                    $data=mysqli_fetch_assoc($result);
                ?>
                <span class="absolute -top-2 -right-2 bg-white text-red-600 rounded-full text-xs px-1.5 py-0.5" id="jumlah_pesanan"><?php echo $data['total']; ?></span>
            </a>
            <?php } ?>
        </div>
        <a href="#" id="btn-user" class="text-white mx-2"><i class="fas fa-user"></i></a>
        <a href="#" id="btn-menu" class="text-white mx-2 hidden"><i class="fas fa-bars"></i></a>
    </div>

    <div class="user absolute top-full right-[-100%] h-[15vh] w-40 px-4 bg-white text-center rounded shadow-md transition-all duration-300 ease-in-out">
        <?php if(isset($_SESSION['username'])){ ?>
        <li class="list-none my-5"><a href="profil.php" class="no-underline text-xl text-red-600">Profil</a></li>
        <li class="list-none my-5"><a href="admin/logout.php" class="no-underline text-xl text-red-600">Logout</a></li>
        <?php } else { ?>
        <li class="list-none my-5"><a href="admin/login.php" class="no-underline text-xl text-red-600">Login</a></li>
        <li class="list-none my-5"><a href="daftar.php" class="no-underline text-xl text-red-600">Daftar</a></li>
        <?php } ?>
    </div>
</nav>

</body>
</html>