<?php
session_start(); // Start the session

include 'cek_role.php';

// Cek apakah role pengguna adalah admin
cek_role('user');

include ("koneksi/koneksi.php");

// Pastikan variabel session ada sebelum digunakan
$user_id = $_SESSION['id_admin'] ?? null;
$user_name = $_SESSION['username'] ?? null;
$user_alamat = $_SESSION['alamat'] ?? null;
$user_noHp = $_SESSION['noHp'] ?? null;
// Ganti dengan Client Key Midtrans Anda yang sebenarnya
$midtrans_client_key = 'SB-Mid-client-xxxxxxxxxxxxxxxxx'; // <-- GANTI DENGAN CLIENT KEY ANDA

$items = [];
if ($user_id) {
    $query = "SELECT * FROM keranjang_user WHERE id_user = '$user_id'";
    $query_run = mysqli_query($koneksi, $query);

    if ($query_run && mysqli_num_rows($query_run) > 0) {
        $items = mysqli_fetch_all($query_run, MYSQLI_ASSOC);
    }
}


// Fungsi untuk menghapus produk dari keranjang
if (isset($_GET['hapus'])) {
    $id_produk = $_GET['hapus'];
    // Gunakan prepared statements untuk keamanan
    $stmt = $koneksi->prepare("DELETE FROM keranjang_user WHERE id_produk = ? AND id_user = ?");
    $stmt->bind_param("is", $id_produk, $user_id);
    if ($stmt->execute()) {
        echo "<script>
                alert('Produk berhasil dihapus');
                window.location.href = 'keranjang.php';
              </script>";
        exit();
    } else {
        echo "Produk gagal dihapus";
    }
}

// Fungsi untuk memperbarui jumlah produk di keranjang (jika diperlukan di masa depan)
if (isset($_POST['update'])) {
    $id_produk = $_POST['id_produk'];
    $jumlah = $_POST['jumlah'];
    // Gunakan prepared statements untuk keamanan
    $stmt = $koneksi->prepare("UPDATE keranjang_user SET jumlah = ? WHERE id_produk = ? AND id_user = ?");
    $stmt->bind_param("iis", $jumlah, $id_produk, $user_id);

    if ($stmt->execute()) {
        echo "<script>
                alert('Jumlah produk berhasil diperbarui');
                window.location.href = 'keranjang.php';
              </script>";
        exit();
    } else {
        echo "Jumlah produk gagal diperbarui";
    }
}

$total_amount = 0;
foreach ($items as $item) {
    $total_amount += $item['harga'] * $item['jumlah'];
}

require '/vendor/autoload.php';

// Set your server key
\Midtrans\Config::$serverKey = 'SB-Mid-server-C1ta5HP9_KFpsSrBQaSJP3zC'; // <-- PASTIKAN INI BENAR
\Midtrans\Config::$isProduction = false; // Set false untuk sandbox
\Midtrans\Config::$isSanitized = true;
\Midtrans\Config::$is3ds = true;

$snapToken = '';
if (!empty($items)) {
    $transaction_details = [
        'order_id' => rand(),
        'gross_amount' => $total_amount,
    ];

    $item_details = [];
    foreach ($items as $item) {
        $item_details[] = [
            'id' => $item['id_produk'],
            'price' => $item['harga'],
            'quantity' => $item['jumlah'],
            'name' => $item['nama'],
        ];
    }

    $transaction = [
        'transaction_details' => $transaction_details,
        'item_details' => $item_details,
    ];

    $snapToken = \Midtrans\Snap::getSnapToken($transaction);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="<?php echo $midtrans_client_key; ?>"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <style>
        /* Anda bisa menambahkan sedikit style kustom di sini jika diperlukan, 
           misalnya untuk font, tapi idealnya semua diatur oleh Tailwind */
        body {
            font-family: 'Poppins', sans-serif; /* Contoh mengganti font */
        }
    </style>
</head>
<body class="bg-gray-100">

    <nav class="bg-white shadow-md sticky top-0 z-50">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <div class="flex-shrink-0">
                    <img src="./assets/foto/Logoo.png" class="w-[75px]">
                </div>

                <div class="hidden md:flex items-center space-x-8">
                    <a href="index.php" class="text-gray-600 hover:text-blue-600 font-medium transition duration-300">Beranda</a>
                    <a href="#about" class="text-gray-600 hover:text-blue-600 font-medium transition duration-300">About</a>
                    <a href="#produk" class="text-gray-600 hover:text-blue-600 font-medium transition duration-300">Produk</a>
                    <a href="#kontak" class="text-gray-600 hover:text-blue-600 font-medium transition duration-300">Kontak</a>
                </div>

                <div class="flex items-center space-x-5">
                    <?php if(isset($_SESSION['username'])): ?>
                        <a href="keranjang.php" class="relative text-gray-600 hover:text-blue-600">
                            <i class="fas fa-shopping-cart fa-lg"></i>
                            <?php
                                $sql_keranjang = "SELECT COUNT(*) as total FROM keranjang_user WHERE id_user = '$user_id'";
                                $result = mysqli_query($koneksi, $sql_keranjang);
                                $data = mysqli_fetch_assoc($result);
                            ?>
                            <span class="absolute -top-2 -right-3 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center" id="jumlah_pesanan">
                                <?php echo $data['total'] ?? 0; ?>
                            </span>
                        </a>
                    <?php endif; ?>

                    <div class="relative">
                        <button id="btn-user" class="text-gray-600 hover:text-blue-600">
                            <i class="fas fa-user fa-lg"></i>
                        </button>
                        <div id="user-menu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-xl py-2 z-20">
                            <?php if(isset($_SESSION['username'])): ?>
                                <a href="profil.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profil</a>
                                <a href="admin/logout.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Logout</a>
                            <?php else: ?>
                                <a href="admin/login.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Login</a>
                                <a href="daftar.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Daftar</a>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="md:hidden">
                        <button id="btn-menu" class="text-gray-600 hover:text-blue-600">
                            <i class="fas fa-bars fa-lg"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div id="mobile-menu" class="hidden md:hidden">
            <a href="index.php" class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100">Beranda</a>
            <a href="#about" class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100">About</a>
            <a href="#produk" class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100">Produk</a>
            <a href="#kontak" class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100">Kontak</a>
        </div>
    </nav>
    <main class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="bg-white rounded-lg shadow-xl overflow-hidden">
            <div class="p-6 sm:p-8">
                <h2 class="text-3xl font-bold text-gray-800 mb-6">Keranjang <span class="text-blue-600">Belanja</span></h2>

                <?php if (empty($items)): ?>
                    <p class="text-center text-gray-500 py-10">Keranjang Anda masih kosong. 🛒</p>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table id="data-table" class="w-full text-center">
                            <thead class="bg-gray-100 text-gray-600 uppercase text-sm">
                                <tr>
                                    <th class="py-3 px-4 font-semibold">No.</th>
                                    <th class="py-3 px-4 font-semibold">Foto</th>
                                    <th class="py-3 px-4 font-semibold text-left">Nama</th>
                                    <th class="py-3 px-4 font-semibold">Jumlah</th>
                                    <th class="py-3 px-4 font-semibold w-40">Harga Satuan</th>
                                    <th class="py-3 px-4 font-semibold w-48">Total Harga</th>
                                    <th class="py-3 px-4 font-semibold w-32">Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <input type="hidden" id="id_user" name="id_user" value="<?php echo htmlspecialchars($user_id, ENT_QUOTES, 'UTF-8'); ?>">
                                <input type="hidden" id="totalAmount" name="totalAmount" value="<?php echo htmlspecialchars($total_amount, ENT_QUOTES, 'UTF-8'); ?>">
                                <input type="hidden" id="name_user" name="name_user" value="<?php echo htmlspecialchars($user_name, ENT_QUOTES, 'UTF-8'); ?>">
                                <input type="hidden" id="alamat_user" name="alamat_user" value="<?php echo htmlspecialchars($user_alamat, ENT_QUOTES, 'UTF-8'); ?>">
                                <input type="hidden" id="noHp_user" name="noHp_user" value="<?php echo htmlspecialchars($user_noHp, ENT_QUOTES, 'UTF-8'); ?>">

                                <?php foreach ($items as $index => $item): ?>
                                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                                        <td class="py-4 px-4 align-middle"><?php echo $index + 1; ?>.</td>
                                        <td class="py-4 px-4 align-middle">
                                            <img src="assets/foto_produk/<?php echo htmlspecialchars($item['foto'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($item['nama'], ENT_QUOTES, 'UTF-8'); ?>" class="w-16 h-16 object-cover rounded-md mx-auto">
                                        </td>
                                        <td class="py-4 px-4 align-middle text-left font-medium text-gray-800"><?php echo htmlspecialchars($item['nama'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td class="py-4 px-4 align-middle">
                                            <input type="hidden" name="id_produk" value="<?php echo htmlspecialchars($item['id_produk'], ENT_QUOTES, 'UTF-8'); ?>">
                                            <input type="hidden" name="jumlah" value="<?php echo htmlspecialchars($item['jumlah'], ENT_QUOTES, 'UTF-8'); ?>">
                                            <span class="text-lg font-medium"><?php echo htmlspecialchars($item['jumlah'], ENT_QUOTES, 'UTF-8'); ?></span>
                                        </td>
                                        <td class="py-4 px-4 align-middle">Rp. <span class="hargaItem"><?php echo number_format($item['harga'], 0, ',', '.'); ?></span></td>
                                        <td class="py-4 px-4 align-middle font-semibold">Rp. <?php echo number_format($item['harga'] * $item['jumlah'], 0, ',', '.'); ?></td>
                                        <td class="py-4 px-4 align-middle">
                                            <a href="keranjang.php?hapus=<?php echo htmlspecialchars($item['id_produk'], ENT_QUOTES, 'UTF-8'); ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?')" class="bg-red-500 text-white font-semibold py-2 px-4 rounded-lg hover:bg-red-600 transition duration-300 inline-block">
                                                Hapus
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>

                                <tr class="bg-gray-50 font-bold">
                                    <td colspan="4" class="py-4 px-4 text-right text-lg text-gray-800">Total Belanja:</td>
                                    <td colspan="2" class="py-4 px-4 text-lg text-blue-600">Rp. <span id="total-keseluruhan"><?php echo number_format($total_amount, 0, ',', '.'); ?></span></td>
                                    <td class="py-4 px-4">
                                        <button id="pay-button" class="w-full bg-green-500 text-white font-semibold py-2 px-4 rounded-lg hover:bg-green-600 transition duration-300">
                                            Beli Sekarang
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <script>
    // Script untuk toggle menu (user & mobile)
    $(document).ready(function() {
        $('#btn-user').on('click', function() {
            $('#user-menu').toggleClass('hidden');
        });
        $('#btn-menu').on('click', function() {
            $('#mobile-menu').toggleClass('hidden');
        });
        // Sembunyikan menu jika klik di luar
        $(document).on('click', function(event) {
            if (!$(event.target).closest('#btn-user, #user-menu').length) {
                $('#user-menu').addClass('hidden');
            }
        });
    });

    // SEMUA SCRIPT AJAX ANDA TETAP SAMA DI SINI
    $(document).ready(function() {
        $('#pay-button').on('click', function() {
            let data = [];
            let transaction = Date.now().toString(36) + Math.random().toString(36).substr(2); // ID Transaksi lebih unik
            let totalsemua = $('#totalAmount').val();
            let id_user = $('#id_user').val();
            let name_user = $('#name_user').val();
            let alamat_user = $('#alamat_user').val();
            let noHp_user = $('#noHp_user').val();
            
            // Loop hanya melalui baris produk, abaikan baris total
            $('#data-table tbody tr').not(':last-child').each(function() {
                let id_produk = $(this).find('input[name="id_produk"]').val();
                let nama = $(this).find('td:eq(2)').text().trim();
                let jumlah = $(this).find('input[name="jumlah"]').val();
                let harga = $(this).find('.hargaItem').text().replace(/[^0-9]/g, ''); 

                if (id_produk && nama && jumlah && harga) {
                    data.push({
                        id_transaksi: transaction,
                        id_produk: id_produk, // Tambahkan id_produk untuk cek stok
                        nama: nama,
                        jumlah: jumlah,
                        harga: harga 
                    });
                }
            });
            
            if (data.length === 0) {
                alert('Tidak ada item untuk dibeli.');
                return;
            }
            
            console.log("Data to send:", data);

            $.ajax({
                url: 'cek_stock.php', // Pastikan file ini ada dan berfungsi
                type: 'POST',
                data: {data: JSON.stringify(data)},
                dataType: 'json', // Harapkan response JSON
                success: function(response) {
                    console.log("Stock check response:", response);
                    if (response.success) {
                        console.log('Stok cukup, memproses pembayaran...');
                        snap.pay('<?php echo $snapToken; ?>', {
                            onSuccess: function(result){
                                console.log("Payment success:", result);
                                // Kirim data ke server untuk dicatat
                                $.ajax({
                                    url: 'tambah_transaksi.php',
                                    type: 'POST',
                                    data: {
                                        id: transaction,
                                        id_user: id_user,
                                        nama_pelanggan: name_user,
                                        tanggal: new Date().toISOString().slice(0, 19).replace('T', ' '), // Format tanggal SQL
                                        total: totalsemua,
                                        status: 'Diproses', // atau dari result.transaction_status
                                        alamat: alamat_user,
                                        no_hp: noHp_user,
                                        payment_method: result.payment_type, // Simpan metode pembayaran
                                        transaction_id_midtrans: result.transaction_id, // Simpan ID dari Midtrans
                                    },
                                    success: function(transaksiResponse){
                                        console.log('Transaksi berhasil disimpan:', transaksiResponse);
                                        // Simpan detail item transaksi (history)
                                        $.ajax({
                                            url: 'tambah_history.php',
                                            type: 'POST',
                                            data: {data: JSON.stringify(data)},
                                            success: function(historyResponse) {
                                                console.log('History berhasil disimpan:', historyResponse);
                                                // Kosongkan keranjang setelah semua berhasil
                                                $.post('hapus_keranjang_setelah_beli.php', { id_user: id_user }, function(deleteResponse){
                                                    console.log('Keranjang dikosongkan:', deleteResponse);
                                                    alert('Pembayaran berhasil! Transaksi sedang diproses.');
                                                    window.location.href = 'index.php'; // Arahkan ke halaman profil/pesanan saya
                                                });
                                            },
                                            error: function(xhr) { console.error("Error saat simpan history:", xhr.responseText); }
                                        });
                                    },
                                    error: function(xhr) { console.error("Error saat simpan transaksi:", xhr.responseText); }
                                });
                            },
                            onPending: function(result){
                                alert("Menunggu pembayaran Anda!"); 
                                console.log("Payment pending:", result);
                            },
                            onError: function(result){
                                alert("Pembayaran gagal!"); 
                                console.log("Payment error:", result);
                            },
                            onClose: function(){
                                alert('Anda menutup popup tanpa menyelesaikan pembayaran.');
                            }
                        });
                    } else {
                        // Stok tidak cukup
                        alert('Gagal! ' + (response.message || 'Stok produk tidak mencukupi.'));
                        console.log('Stok tidak cukup:', response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error saat cek stok:", xhr.responseText);
                    alert("Terjadi kesalahan saat memeriksa stok produk.");
                },
            });
        });
    });
    </script>
</body>
</html>