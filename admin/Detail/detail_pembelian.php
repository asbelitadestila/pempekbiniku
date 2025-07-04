<div class="shadow-md p-3 mb-3 bg-white rounded-lg">
    <h3><b>Halaman Detail Transaksi</b></h3>
</div>

<?php

// $id_transaksi = $_GET['id'];
// $ambil = $koneksi->query("SELECT * FROM transaksi WHERE id = '$id_transaksi'");
// $detail = $ambil->fetch_assoc();

$id_transaksi = isset($_GET['id']) ? $_GET['id'] : null;

if ($id_transaksi) {
    $ambil = $koneksi->query("SELECT * FROM transaksi WHERE id = '$id_transaksi'");
    
    // Cek apakah query berhasil dan mengembalikan data
    if (mysqli_num_rows($ambil) > 0) {
        $detail = $ambil->fetch_assoc();
    } else {
        // Jika tidak ada data yang ditemukan, handle error di sini
        echo "Transaksi tidak ditemukan.";
        exit;
    }
} else {
    echo "ID Transaksi tidak valid.";
    exit;
}

?>

<div class="flex flex-wrap -mx-2">
    <div class="w-full px-2">
        <div class="shadow-md bg-white rounded-lg border border-gray-200">
            <div class="bg-gray-50 px-4 py-3 border-b border-gray-200 rounded-t-lg">
                <strong>Data Pelanggan</strong>
            </div>
            <div class="p-4 flex flex-wrap">
                <!-- Nama -->
                <label class="w-full md:w-1/3 py-2 font-medium">Nama :</label>
                <label class="w-full md:w-2/3 py-2"><?php echo htmlspecialchars($detail['nama_pelanggan']); ?></label>
                
                <!-- Alamat -->
                <label class="w-full md:w-1/3 py-2 font-medium">Alamat :</label>
                <label class="w-full md:w-2/3 py-2"><?php echo htmlspecialchars($detail['alamat']); ?></label>
                
                <!-- Telepon -->
                <label class="w-full md:w-1/3 py-2 font-medium">Telepon :</label>
                <label class="w-full md:w-2/3 py-2"><?php echo htmlspecialchars($detail['no_hp']); ?></label>
            </div>
        </div>
    </div>

    <div class="w-full px-2 mt-5">
        <div class="shadow-md bg-white rounded-lg border border-gray-200">
            <div class="bg-gray-50 px-4 py-3 border-b border-gray-200 rounded-t-lg">
                <strong>Data Pembelian</strong>
            </div>
            <div class="p-4 flex flex-wrap">
                <!-- Tanggal --> 
                <label class="w-full md:w-1/3 py-2 font-medium">Tanggal :</label>
                <label class="w-full md:w-2/3 py-2">
                    <?php echo date("d F Y", strtotime($detail['tanggal'])); ?> 
                </label>
                <!-- Total --> 
                <label class="w-full md:w-1/3 py-2 font-medium">Total :</label>
                <label class="w-full md:w-2/3 py-2">
                    Rp. <?php echo number_format($detail['total']); ?> 
                </label>
            </div>
        </div>
    </div>
</div>

<?php

$pp= array();
$ambil = $koneksi->query("SELECT * FROM history WHERE id_transaksi = '$id_transaksi'");
while($pecah = $ambil->fetch_assoc())
{
    $pp[] = $pecah;
}

?>

<div class="shadow-md bg-white rounded-lg border border-gray-200 mt-3">
    <div class="p-4">
        <table class="w-full border-collapse border border-gray-300" id="tables">
            <thead>
                <tr class="bg-gray-50">
                    <th class="border border-gray-300 px-4 py-2 text-left">NO</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Nama</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Harga</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Jumlah</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Subtotal</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($pp as $key => $value): ?>
                <?php $subtotal = $value['harga']*$value['jumlah']; ?>
                <tr class="hover:bg-gray-50 odd:bg-white even:bg-gray-50">
                    <td class="border border-gray-300 px-4 py-2 text-center w-12"> <?php echo $key+1;  ?> </td>
                    <td class="border border-gray-300 px-4 py-2"> <?php echo $value['nama']; ?> </td>
                    <td class="border border-gray-300 px-4 py-2">Rp. <?php echo number_format($value['harga']); ?> </td>
                    <td class="border border-gray-300 px-4 py-2"> <?php echo $value['jumlah']; ?> </td>
                    <td class="border border-gray-300 px-4 py-2">Rp. <?php echo number_format($subtotal); ?> </td>
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>
