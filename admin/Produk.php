<div class="shadow p-3 mb-3 bg-white rounded">
    <h3 class="font-bold">Halaman Produk</h3>
</div>

<?php

$produk = array();
$ambil = $koneksi->query("SELECT * FROM produk JOIN kategori
ON produk.id_kategori=kategori.id_kategori");
while ($pecah = $ambil->fetch_assoc()) {
    $produk[] = $pecah;
}

?>

<a href="index.php?halaman=tambah_produk" class="inline-block px-2 py-1 text-sm text-white bg-green-500 rounded hover:bg-green-600">Tambah</a>

<div class="mt-3 bg-white rounded shadow">
    <div class="p-4">
        <table class="w-full border-collapse" id="tables">
            <thead>
                <tr>
                    <th class="p-2 border border-gray-300">NO</th>
                    <th class="p-2 border border-gray-300">Kategori</th>
                    <th class="p-2 border border-gray-300">Nama Produk</th>
                    <th class="p-2 border border-gray-300">Harga</th>
                    <th class="p-2 border border-gray-300">Stok</th>
                    <th class="p-2 border border-gray-300">Foto</th>
                    <th class="p-2 border border-gray-300">Opsi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($produk as $key => $value): ?>
                    <tr class="hover:bg-gray-100">
                        <td class="p-2 text-center border border-gray-300 w-12"> <?php echo $key + 1; ?> </td>
                        <td class="p-2 border border-gray-300"> <?php echo $value['nama_kategori']; ?> </td>
                        <td class="p-2 border border-gray-300"> <?php echo $value['nama_produk']; ?> </td>
                        <td class="p-2 border border-gray-300">Rp. <?php echo number_format($value['harga_produk']); ?> </td>
                        <td class="p-2 border border-gray-300"><?php echo number_format($value['stok_produk']); ?></td>
                        <td class="p-2 text-center border border-gray-300">
                            <img class="w-36 inline-block" src="../assets/foto_produk/<?php echo $value['foto_produk']; ?>">
                        </td>
                        <td class="p-2 text-center border border-gray-300 w-36">
                            <a href="index.php?halaman=detail_produk&id=<?php echo $value['id_produk']; ?>"
                                class="inline-block px-2 py-1 text-sm text-white bg-blue-500 rounded hover:bg-blue-600">Detail</a>
                            <a href="index.php?halaman=hapus_produk&id=<?php echo $value['id_produk']; ?>"
                                class="inline-block px-2 py-1 text-sm text-white bg-red-500 rounded hover:bg-red-600">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>
