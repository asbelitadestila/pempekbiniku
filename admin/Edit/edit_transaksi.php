<?php
include("../../koneksi/koneksi.php");

$id_transaksi =  $_GET['id'];

// Query untuk memasukkan data ke tabel `transaksi`
$sql = "UPDATE transaksi SET status = 'Sedang Dikirim' where id = '$id_transaksi' ";

if ($koneksi->query($sql) === TRUE) {
    header('location:../index.php?halaman=Pembelian');
} else {
    echo "Error: " . $sql . "<br>" . $koneksi->error;
}

// Menutup koneksi
$koneksi->close();

?>