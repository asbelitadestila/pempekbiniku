<div class="shadow p-3 mb-3 bg-white rounded">
    <h3 class="font-bold">Halaman Pelanggan</h3>
</div>

<?php

$pelanggan = array();
$ambil = $koneksi->query("SELECT * FROM user");
while($pecah = $ambil->fetch_assoc())
{
    $pelanggan[] = $pecah;
}

?>

<div class="bg-white shadow rounded">
    <div class="p-6">
        <table class="min-w-full border border-gray-300" id="tables">
        <thead>
                <tr>
                    <th class="border border-gray-300 px-4 py-2">NO</th>
                    <th class="border border-gray-300 px-4 py-2">Nama</th>
                    <th class="border border-gray-300 px-4 py-2">Telepon</th>
                    <th class="border border-gray-300 px-4 py-2">Alamat</th>
                    <th class="border border-gray-300 px-4 py-2">Opsi</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($pelanggan as $key => $value): ?>
                <tr class="hover:bg-gray-100">
                    <td class="border border-gray-300 px-4 py-2 text-center w-12"> <?php echo $key+1;  ?> </td>
                    <td class="border border-gray-300 px-4 py-2"> <?php echo $value['username']; ?> </td>
                    <td class="border border-gray-300 px-4 py-2"> <?php echo $value['noHp']; ?> </td>
                    <td class="border border-gray-300 px-4 py-2"> <?php echo $value['alamat']; ?> </td>
                    <td class="border border-gray-300 px-4 py-2 text-center w-48">
                        <a href='hapus/hapus_user.php?id=<?php echo $value['id_admin']; ?>' class="bg-red-600 hover:bg-red-700 text-white py-1 px-2 rounded text-sm">Hapus</a>
                    </td>
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>
