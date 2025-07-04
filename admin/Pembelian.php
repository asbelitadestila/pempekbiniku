<div class="shadow p-3 mb-3 bg-white rounded">
    <h3 class="font-bold">Halaman Transaksi</h3>
</div>

<?php

$pembelian = array();
$ambil = $koneksi->query("SELECT * FROM transaksi");
while($pecah = $ambil->fetch_assoc())
{
    $pembelian[] = $pecah;
}

?>

<div class="bg-white shadow mt-3 rounded"> 
    <div class="p-6">
        <table class="w-full border-collapse border border-gray-300" id="tables">
            <thead>
                <tr>
                    <th class="border border-gray-300 p-2">NO</th>
                    <th class="border border-gray-300 p-2">Nama</th>
                    <th class="border border-gray-300 p-2">Tanggal</th>
                    <th class="border border-gray-300 p-2">Alamat</th>
                    <th class="border border-gray-300 p-2">Total</th>
                    <th class="border border-gray-300 p-2">Status</th>
                    <th class="border border-gray-300 p-2">Opsi</th>
                </tr>
            </thead>
            <tbody>

            <?php foreach ($pembelian as $key => $value): ?>
                
                <tr class="hover:bg-gray-100 striped:bg-gray-50">
                    <td class="text-center border border-gray-300 p-2" style="width: 50px;"> <?php echo $key+1;  ?> </td>
                    <td class="border border-gray-300 p-2"> <?php echo $value['nama_pelanggan']; ?> </td>
                    <td class="border border-gray-300 p-2"> <?php echo date("d F Y", strtotime($value['tanggal'])); ?> </td>
                    <td class="border border-gray-300 p-2">  <?php echo $value['alamat']; ?> </td>
                    <td class="border border-gray-300 p-2">Rp. <?php echo number_format($value['total']); ?> </td>
                    <td class="border border-gray-300 p-2"> <?php echo $value['status']; ?></td>
                    <td class="text-center border border-gray-300 p-2" style="width: 200px;">
                        <a href="Edit/edit_transaksi.php?id=<?php 
                        echo $value['id']; ?>" class="inline-block px-2 py-1 text-xs text-white bg-blue-500 rounded hover:bg-blue-600">
                            <i class="fas fa-truck-pickup"></i>
                        </a>
                        <a href="index.php?halaman=detail_pembelian&id=<?php 
                        echo $value['id']; ?>" class="inline-block px-2 py-1 text-xs text-white bg-blue-500 rounded hover:bg-blue-600">
                            <i class="fas fa-info"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>
