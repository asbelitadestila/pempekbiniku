<?php
session_start();
include '../koneksi/koneksi.php';

// session_start();
// Cek apakah pengguna sudah login dan perannya adalah user
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

if (isset($_POST['inputBulan'])) {
    $tanggal = $_POST['bulan'];

    $bulan = date('m', strtotime($tanggal));
    $tahun = date('Y', strtotime($tanggal));


    // $koneksi->query("INSERT INTO pendapatan (bulan) VALUES ('$tanggal')");

    $sql = "SELECT * FROM bulan WHERE MONTH(bulan) = '$bulan' AND YEAR(bulan) = '$tahun'";
    $result = $koneksi->query($sql);

    // Jika data ditemukan
    if ($result->num_rows > 0) {
        echo "<script>alert('Data untuk bulan dan tahun ini sudah ada.');</script>";
    } else {
        // Proses penyimpanan data ke database jika tidak ada data yang sama
        $sql_insert = "INSERT INTO bulan (bulan) VALUES ('$tanggal')";
        if ($koneksi->query($sql_insert) === TRUE) {
            echo "<script>alert('Data berhasil ditambahkan.');</script>";
            echo "<script>location='index.php'; </script>";

        } else {
            echo "Error: " . $sql_insert . "<br>" . $koneksi->error;
        }
    }

    // echo "<script>alert('data berhasil disimpan'); </script>";
}



if (isset($_POST['inputPendapatan'])) {

    // Ambil data dari form
    $bulan_id = $_POST['id_bulan'];
    $nama = $_POST['nama_produk'];
    $terjual = $_POST['terjual'];
    $total_baru = $_POST['total'];

    // Ambil nilai total yang ada di database berdasarkan pendapatan_id
    $sql_total = 'SELECT total FROM bulan WHERE id = "' . $bulan_id . '"';
    $result = $koneksi->query($sql_total);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $total_sekarang = $row['total'];

        // Tambahkan nilai baru ke nilai yang ada
        $hasil = intval($total_sekarang) + intval($total_baru);

        // Update nilai total di tabel pendapatan
        $sql_bulan = 'UPDATE bulan SET total = "' . $hasil . '" WHERE id = "' . $bulan_id . '"';

        if ($koneksi->query($sql_bulan) === TRUE) {

            // Tambahkan data baru ke tabel bulanan
            $sql_bulanan = 'INSERT INTO pendapatan (bulan_id, nama_produk, terjual, total) VALUES ("' . $bulan_id . '", "' . $nama . '", "' . $terjual . '", "' . $total_baru . '")';
            if ($koneksi->query($sql_bulanan) === TRUE) {
                echo "<script>alert('Data berhasil ditambahkan.');</script>";
                echo "<script>location='index.php?halaman=detail-pendapatan&id=" . $bulan_id . "'; </script>";
            } else {
                echo "Error: " . $sql_bulanan . "<br>" . $koneksi->error;
            }

        } else {
            echo "Error: " . $sql_bulan . "<br>" . $koneksi->error;
        }
    } else {
        echo "<script>alert('Data pendapatan tidak ditemukan.');</script>";
    }
}


if (isset($_POST['edit_pendapatan'])) {
    $id = $_POST['dapat'];
    $bulan_id = $_POST['edit_id_bulan'];
    $nama = $_POST['edit_nama_produk'];
    $terjual = $_POST['edit_terjual'];
    $total_baru = $_POST['edit_total'];

    $sql_profit = 'SELECT total FROM bulan WHERE id = "' . $bulan_id . '"';
    $result = $koneksi->query($sql_profit);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $total_profit = $row['total'];

        $sql_pendapatan = 'SELECT total FROM pendapatan WHERE id = "' . $id . '"';
        $result2 = $koneksi->query($sql_pendapatan);

        if ($result2->num_rows > 0) {
            $row2 = $result2->fetch_assoc();
            $total_pendapatan = $row2['total'];
        }

        $hasil = intval($total_profit) - intval($total_pendapatan);

        $hasil_baru = $hasil + intval($total_baru);

        $sql_update_bulan = 'UPDATE bulan SET total = "' . $hasil_baru . '" WHERE id = "' . $bulan_id . '"';

        if ($koneksi->query($sql_update_bulan) === TRUE) {
            $sql_update_pendapatan = 'UPDATE pendapatan SET nama_produk = "' . $nama . '", terjual = "' . $terjual . '", total = "' . $total_baru . '" WHERE id = "' . $id . '"';
            if ($koneksi->query($sql_update_pendapatan) === TRUE) {
                echo "<script>alert('Data berhasil diubah.');</script>";
                echo "<script>location='index.php?halaman=detail-pendapatan&id=" . $bulan_id . "'; </script>";
            } else {
                echo "Error: " . $sql_update_pendapatan . "<br>" . $koneksi->error;
            }

        } else {
            echo "Error: " . $sql_update_bulan . "<br>" . $koneksi->error;
        }
    }
    // } else {
    //     echo "<script>alert('Data pendapatan tidak ditemukan.');</script>";
    // }

    // $sql = 'UPDATE pendapatan SET nama_produk = "'. $nama. '", terjual = "'. $terjual. '", total = "'. $total_baru. '" WHERE id = "'. $id. '"';
    // if ($koneksi->query($sql) === TRUE) {
    //     echo "<script>alert('Data berhasil diubah.');</script>";
    //     echo "<script>location='index.php?halaman=detail-pendapatan&id=" . $bulan_id ."'; </script>";        
    // } else {
    //     echo "Error: " . $sql . "<br>" . $koneksi->error;
    // }

} else {

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

    <title>Pempek Biniku</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <style>
        /* Custom styles to match Bootstrap's SB Admin 2 theme */
        .bg-gradient-primary {
            background-color: #4e73df;
            background-image: linear-gradient(180deg, #4e73df 10%, #224abe 100%);
            background-size: cover;
        }
        
        .rotate-n-15 {
            transform: rotate(-15deg);
        }
        
        /* Additional styles to ensure design consistency */
        .sidebar-dark .nav-item .nav-link {
            color: rgba(255, 255, 255, 0.8);
        }
        
        .sidebar-dark .nav-item .nav-link:hover {
            color: #fff;
        }
        
        .sidebar-dark .sidebar-brand {
            color: #fff;
        }
        
        .topbar .dropdown .dropdown-menu {
            border: none;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }
        
        .topbar .dropdown-list .dropdown-header {
            background-color: #4e73df;
            border: 1px solid #4e73df;
            color: #fff;
        }
        
        .dropdown-item {
            white-space: normal;
            padding: 0.5rem 1rem;
        }
        
        .dropdown-item:hover {
            background-color: #eaecf4;
        }
    </style>
</head>

<body class="bg-gray-100">
    <!-- Page Wrapper -->
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <div class="bg-gradient-primary w-64 flex-shrink-0 hidden md:block">
            <div class="flex items-center justify-center h-16 text-white">
                <div class="text-xl font-bold">Pempek Biniku</div>
            </div>
            
            <!-- Divider -->
            <hr class="border-t border-gray-200 opacity-25 my-0">
            
            <!-- Nav Item - Dashboard -->
            <div class="py-2 px-4">
                <a href="index.php" class="flex items-center text-white opacity-80 hover:opacity-100 py-2 px-4 rounded">
                    <i class="fas fa-fw fa-tachometer-alt mr-2"></i>
                    <span>Dashboard</span>
                </a>
            </div>
            
            <!-- Divider -->
            <hr class="border-t border-gray-200 opacity-25 my-2">
            
            <div class="py-2 px-4">
                <a href="index.php?halaman=Kategori" class="flex items-center text-white opacity-80 hover:opacity-100 py-2 px-4 rounded">
                    <i class="fas fa-fw fa-tachometer-alt mr-2"></i>
                    <span>Kategori</span>
                </a>
            </div>
            
            <div class="py-2 px-4">
                <a href="index.php?halaman=Produk" class="flex items-center text-white opacity-80 hover:opacity-100 py-2 px-4 rounded">
                    <i class="fas fa-fw fa-tachometer-alt mr-2"></i>
                    <span>Produk</span>
                </a>
            </div>
            
            <div class="py-2 px-4">
                <a href="index.php?halaman=Pembelian" class="flex items-center text-white opacity-80 hover:opacity-100 py-2 px-4 rounded">
                    <i class="fas fa-fw fa-tachometer-alt mr-2"></i>
                    <span>Transaksi</span>
                </a>
            </div>
            
            <div class="py-2 px-4">
                <a href="index.php?halaman=Pelanggan" class="flex items-center text-white opacity-80 hover:opacity-100 py-2 px-4 rounded">
                    <i class="fas fa-fw fa-tachometer-alt mr-2"></i>
                    <span>Customers</span>
                </a>
            </div>
            
            <div class="py-2 px-4">
                <a href="index.php?halaman=Logout" class="flex items-center text-white opacity-80 hover:opacity-100 py-2 px-4 rounded">
                    <i class="fas fa-fw fa-tachometer-alt mr-2"></i>
                    <span>Logout</span>
                </a>
            </div>
            
            <!-- Divider -->
            <hr class="border-t border-gray-200 opacity-25 my-2">
            
            <!-- Sidebar Toggler -->
            <div class="text-center mt-4 hidden md:block">
                <button id="sidebarToggle" class="rounded-full border-0 p-2 bg-gray-100 bg-opacity-20">
                    <i class="fas fa-angle-left text-white"></i>
                </button>
            </div>
        </div>
        <!-- End of Sidebar -->
        
        <!-- Content Wrapper -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Main Content -->
            <div class="flex-1 overflow-auto">
                <!-- Topbar -->
                <nav class="bg-white shadow-md flex justify-between items-center p-4">
                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="md:hidden rounded-full p-2 text-gray-400 hover:bg-gray-100">
                        <i class="fa fa-bars"></i>
                    </button>
                    
                    <!-- Topbar Search -->
                    <div class="hidden md:flex items-center flex-1 ml-4">
                        <div class="relative w-full max-w-md">
                            <input type="text" class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:border-blue-500" placeholder="Search for...">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <button class="text-gray-500 focus:outline-none">
                                    <i class="fas fa-search fa-sm"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Topbar Navbar -->
                    <ul class="flex items-center">
                        <!-- Nav Item - User Information -->
                        <li class="relative">
                            <a href="#" class="flex items-center text-gray-600 hover:text-gray-900" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 hidden lg:inline text-sm font-medium">Admin <?php echo $_SESSION['username']; ?></span>
                            </a>
                        </li>
                    </ul>
                </nav>
                <!-- End of Topbar -->
                
                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <!-- Page Heading -->
                    <?php
                    if (isset($_GET['halaman'])) {
                        // halaman kategori
                        if ($_GET['halaman'] == "Kategori") {
                            include 'Kategori.php';
                        } elseif ($_GET['halaman'] == "tambah_kategori") {
                            include 'tambah/tambah_kategori.php';
                        } elseif ($_GET['halaman'] == "edit_kategori") {
                            include 'edit/edit_kategori.php';
                        } elseif ($_GET['halaman'] == "hapus_kategori") {
                            include 'hapus/hapus_kategori.php';
                        }

                        //halaman produk
                        elseif ($_GET['halaman'] == "Produk") {
                            include 'Produk.php';
                        } elseif ($_GET['halaman'] == "tambah_produk") {
                            include 'tambah/tambah_produk.php';
                        } elseif ($_GET['halaman'] == "detail_produk") {
                            include 'detail/detail_produk.php';
                        } elseif ($_GET['halaman'] == "hapus_foto") {
                            include 'hapus/hapus_foto.php';
                        } elseif ($_GET['halaman'] == "edit_produk") {
                            include 'edit/edit_produk.php';
                        } elseif ($_GET['halaman'] == "hapus_produk") {
                            include 'hapus/hapus_produk.php';
                        }

                        //halaman pembelian
                        elseif ($_GET['halaman'] == "Pembelian") {
                            include 'Pembelian.php';
                        } elseif ($_GET['halaman'] == "detail_pembelian") {
                            include 'detail/detail_pembelian.php';
                        } elseif ($_GET['halaman'] == "Logout") {
                            include 'logout.php';
                        }

                        // halaman data penjualan
                        elseif ($_GET['halaman'] == "detail-pendapatan") {
                            include 'detail/detail_pendapatan.php';
                        } elseif ($_GET['halaman'] == "hapus-pendapatan") {
                            include 'hapus/hapus_pendapatan.php';
                        }

                        //halaman pelanggan
                        elseif ($_GET['halaman'] == "Pelanggan") {
                            include 'Pelanggan.php';
                        }
                    } else {
                        include 'dashboard.php';
                    }
                    ?>
                </div>
                <!-- End of Page Content -->
            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="bg-white py-4 border-t border-gray-200">
                <div class="container mx-auto px-6">
                    <div class="text-center">
                        <span class="text-sm text-gray-600">Copyright &copy; Pempek Biniku 2024</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->
        </div>
        <!-- End of Content Wrapper -->
    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button -->
    <a class="fixed bottom-4 right-4 hidden rounded-full w-10 h-10 bg-gray-800 flex items-center justify-center text-white shadow-lg hover:bg-gray-700" href="#page-top" id="scrollToTop">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal -->
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50" id="logoutModal">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="border-b border-gray-200 px-6 py-4">
                <h5 class="text-lg font-medium text-gray-900">Ready to Leave?</h5>
                <button class="absolute top-3 right-3 text-gray-400 hover:text-gray-500" type="button" data-dismiss="modal">
                    <span class="text-2xl">&times;</span>
                </button>
            </div>
            <div class="px-6 py-4">
                <p class="text-gray-600">Select "Logout" below if you are ready to end your current session.</p>
            </div>
            <div class="border-t border-gray-200 px-6 py-4 flex justify-end space-x-2">
                <button class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300" type="button" data-dismiss="modal">Cancel</button>
                <a class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700" href="login.html">Logout</a>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="../assets/vendor/jquery/jquery.min.js"></script>
    <script src="../assets/vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="../assets/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="../assets/vendor/datatables/dataTables.bootstrap4.min.js"></script>
    
    <!-- Custom scripts -->
    <script>
        // Sidebar toggle
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.querySelector('.bg-gradient-primary').classList.toggle('w-64');
            document.querySelector('.bg-gradient-primary').classList.toggle('w-20');
        });
        
        // Mobile sidebar toggle
        document.getElementById('sidebarToggleTop').addEventListener('click', function() {
            document.querySelector('.bg-gradient-primary').classList.toggle('hidden');
            document.querySelector('.bg-gradient-primary').classList.toggle('block');
        });
        
        // Scroll to top button visibility
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 100) {
                document.getElementById('scrollToTop').classList.remove('hidden');
                document.getElementById('scrollToTop').classList.add('flex');
            } else {
                document.getElementById('scrollToTop').classList.remove('flex');
                document.getElementById('scrollToTop').classList.add('hidden');
            }
        });
        
        // DataTables initialization
        $(document).ready(function() {
            $('.dataTable').DataTable();
            
            $(".btn-tambah").on("click", function() {
                $(".input-foto").append("<input type='file' name='foto[]' class='w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent'>");
            });
        });
    </script>
</body>
</html>
