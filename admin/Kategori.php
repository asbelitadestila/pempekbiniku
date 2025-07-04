<div class="shadow p-3 mb-3 bg-white rounded">
    <h3 class="font-bold">Halaman Kategori</h3>
</div>

<?php

$kategori = array();
$ambil = $koneksi->query("SELECT * FROM kategori");
while($pecah = $ambil->fetch_assoc())
{
    $kategori[] = $pecah;
}

?>

<a href="index.php?halaman=tambah_kategori" class="inline-block px-2 py-1 text-xs text-white bg-green-500 rounded hover:bg-green-600">Tambah</a>

<div class="mt-3 bg-white rounded shadow">
    <div class="p-4">
        <table class="w-full border-collapse" id="tables">
            <thead>
                <tr>
                    <th class="p-2 border border-gray-300">NO</th>
                    <th class="p-2 border border-gray-300">Nama</th>
                    <th class="p-2 border border-gray-300">Opsi</th>
                </tr>
            </thead>
            <tbody>
                
            <?php foreach ($kategori as $key => $value): ?>

                <tr class="hover:bg-gray-100">
                    <td class="p-2 text-center border border-gray-300 w-[50px]"> <?php echo $key+1; ?> </td>
                    <td class="p-2 border border-gray-300"><?php echo $value['nama_kategori']; ?> </td>
                    <td class="p-2 text-center border border-gray-300 w-[200px]">
                        <a href="index.php?halaman=edit_kategori&id=<?php echo $value['id_kategori']; ?>" class="inline-block px-2 py-1 text-xs text-white bg-blue-500 rounded hover:bg-blue-600">Edit</a>
                        <a href="index.php?halaman=hapus_kategori&id=<?php echo $value['id_kategori']; ?>" class="inline-block px-2 py-1 text-xs text-white bg-red-500 rounded hover:bg-red-600">Hapus</a>
                    </td>
                 </tr>
                 <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>
