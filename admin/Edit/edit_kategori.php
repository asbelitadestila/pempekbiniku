<div class="shadow p-3 mb-3 bg-white rounded">
    <h3 class="font-bold">Halaman Edit Kategori</h3>
</div>

<?php

$id_kategori =  $_GET['id'];

$ambil = $koneksi->query("SELECT * FROM kategori WHERE id_kategori='$id_kategori'");
$edit = $ambil->fetch_assoc();

?>


<form method="post">
    <div class="bg-white shadow rounded">
        <div class="p-4">

        <div class="flex flex-row mb-4">
            <label class="w-1/4 py-2">Nama Kategori :</label>
            <div class="w-3/4">
        <input type="text" name="nama" class="w-full px-3 py-2 border rounded" value="<?php echo 
        $edit ['nama_kategori']; ?>"> 
</div>
</div>

</div>
<div class="px-4 py-3 bg-gray-100 rounded-b">
    <div class="flex flex-row">
        <div class="w-11/12">
            <button name="simpan" class="px-3 py-1 text-sm text-white bg-green-500 rounded hover:bg-green-600">Simpan</button>
            </div>
            <div class="w-1/12 text-right">
                <a href="index.php?halaman=Kategori" class="px-3 py-1 text-sm text-white bg-red-500 rounded hover:bg-red-600">Kembali</a>
        </div>
    </div>
</div>

</div>
</form>

<?php

if(isset($_POST['simpan']))
{
    $nama = $_POST['nama'];

    $koneksi->query("UPDATE kategori SET nama_kategori='$nama'
    WHERE id_kategori='$id_kategori'");

echo "<script>alert('data berhasil diedit); </script>";
echo "<script>location='index.php?halaman=Kategori'; </script>";
}

?>
