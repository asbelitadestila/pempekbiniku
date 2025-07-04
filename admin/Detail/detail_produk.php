<div class="shadow p-3 mb-3 bg-white rounded">
    <h3 class="font-bold">Halaman Detail Produk</h3>
</div>

<?php

$id_produk = $_GET['id'];

$ambil = $koneksi->query("SELECT * FROM produk JOIN kategori 
ON produk.id_kategori=kategori.id_kategori WHERE id_produk='$id_produk'");
$detailproduk = $ambil->fetch_assoc();

$produk_foto = array();
$ambil = $koneksi->query("SELECT * FROM produk_foto WHERE id_produk='$id_produk'");
while($tiap = $ambil->fetch_assoc())
{
    $produk_foto[]=$tiap;
}

?>

<div class="bg-white shadow rounded">
    <div class="px-4 py-3 border-b">
        <strong>Data Produk</strong>
    </div>
    <div class="p-4">

        <!-- Nama Kategori -->
        <div class="flex flex-wrap mb-4">
            <label class="w-full sm:w-3/12 pt-2">Nama Kategori :</label>
            <div class="w-full sm:w-9/12">
                <input disabled class="w-full px-3 py-2 border rounded" value="<?php 
                echo $detailproduk['nama_kategori']; ?>">
            </div>
        </div>

        <!-- Nama Produk -->
        <div class="flex flex-wrap mb-4">
            <label class="w-full sm:w-3/12 pt-2">Nama Produk :</label>
            <div class="w-full sm:w-9/12">
                <input disabled class="w-full px-3 py-2 border rounded" value="<?php echo $detailproduk['nama_produk']; ?>">
            </div>
        </div>

        <!-- Harga Produk -->
        <div class="flex flex-wrap mb-4">
            <label class="w-full sm:w-3/12 pt-2">Harga Produk :</label>
            <div class="w-full sm:w-9/12">
                <input disabled class="w-full px-3 py-2 border rounded" value="<?php echo $detailproduk['harga_produk']; ?>">
            </div>
        </div>

        <!-- Deskripsi Produk -->
        <div class="flex flex-wrap mb-4">
            <label class="w-full sm:w-3/12 pt-2">Deskripsi Produk :</label>
            <div class="w-full sm:w-9/12">
                <textarea disabled class="w-full px-3 py-2 border rounded" placeholder="<?php 
                echo $detailproduk['deskripsi_produk']; ?>"></textarea>
            </div>
        </div>

        <!-- Stok Produk -->
        <div class="flex flex-wrap mb-4">
            <label class="w-full sm:w-3/12 pt-2">Stok Produk :</label>
            <div class="w-full sm:w-9/12">
                <input disabled class="w-full px-3 py-2 border rounded" value="<?php 
                echo $detailproduk['stok_produk']; ?>">
            </div>
        </div>

    </div>
</div>

<div class="border-t p-4">
    <div class="flex flex-wrap">
        <div class="w-11/12">
            <a href="index.php?halaman=edit_produk&id=<?php echo 
            $detailproduk['id_produk']; ?>" class="inline-block px-3 py-1 text-sm text-white bg-blue-600 rounded">Edit Produk</a>
        </div>
        <div class="w-1/12 text-right">
            <a href="index.php?halaman=Produk" class="inline-block px-3 py-1 text-sm text-white bg-red-600 rounded">Kembali</a>
        </div>
    </div>
</div>

<div class="flex flex-wrap mt-4">
    <?php foreach ($produk_foto as $key => $value): ?>
    <div class="w-full md:w-1/3 px-2 mb-4">
        <div class="border rounded overflow-hidden" style="width: 21rem;">
            <img src="../assets/foto_produk/<?php echo $value['nama_produk_foto']; ?>"
            class="w-full">
            <div class="p-3 text-center">
                <a href="index.php?halaman=hapus_foto&idfoto=<?php echo $value['id_produk_foto'];
                ?>&idproduk=<?php echo $value['id_produk']; ?>" class="inline-block px-3 py-1 text-sm text-white bg-red-600 rounded">Hapus</a>
            </div>
        </div>
    </div>
    <?php endforeach ?>
</div>

<form method="post" enctype="multipart/form-data">
    <div class="bg-white shadow rounded mt-4">
        <div class="px-4 py-3 border-b">
            <strong>Tambah Foto</strong>
        </div>
        <div class="p-4">
            <div class="flex flex-wrap mb-4">
                <label class="w-full sm:w-3/12 pt-2">File Foto :</label>
                <div class="w-full sm:w-9/12">
                    <input type="file" name="produk_foto" class="w-full px-3 py-2 border rounded">
                </div>
            </div>
        </div>
        <div class="border-t p-4">
            <div class="flex flex-wrap">
                <div class="w-11/12">
                    <button name="simpan" class="inline-block px-3 py-1 text-sm text-white bg-green-600 rounded">Simpan</button>
                </div>
                <div class="w-1/12 text-right">
                    <a href="index.php?halaman=Produk" class="inline-block px-3 py-1 text-sm text-white bg-red-600 rounded">Kembali</a>
                </div>
            </div>
        </div>
    </div>
</form>

<?php

if(isset($_POST['simpan']))
{
    $namafoto = $_FILES['produk_foto']['name'];
    $lokasifoto = $_FILES['produk_foto']['tmp_name'];

    $tgl_foto = date('YmdHis') . $namafoto;

    move_uploaded_file($lokasifoto, "../assets/foto_produk/" . $tgl_foto);

    $koneksi->query("INSERT INTO produk_foto (id_produk,nama_produk_foto)
    VALUES ('$id_produk','$tgl_foto')");

    echo "<script>alert('foto produk berhasil disimpan');</script>";
    echo "<script>location='index.php?halaman=detail+produk&id=$id_produk';</script>";
}
?>
