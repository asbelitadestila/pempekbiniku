<?php

include '../koneksi/koneksi.php';

$id_bulan =  $_GET['id'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="px-4 py-2">
        <button type="button" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded" onclick="document.getElementById('tambahData').classList.remove('hidden')">
            + Tambah Data Perbulan 
        </button>
        <div class="mt-3">
            <table class="min-w-full">
                <thead>
                    <tr>
                        <th class="text-left py-2">Nama Produk</th>
                        <th class="text-left py-2">Terjual</th>
                        <th class="text-left py-2">Total</th>
                        <th class="text-left py-2"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php 
                        $query = "SELECT * FROM pendapatan WHERE bulan_id = $id_bulan";
                        $result = mysqli_query($koneksi, $query);
                        if(mysqli_num_rows($result) > 0){
                            foreach($result as $row){
                    ?>
                    <tr class="hover:bg-gray-50">
                        <td class="py-2">
                            <?php echo $row['nama_produk'];?>
                        </td>
                        <td class="py-2"><?php echo $row['terjual'] ?></td>  
                        <td class="py-2"><?php echo $row['total'] ?></td>                      
                        <td class="py-2">
                            <div class="relative inline-block text-left" x-data="{ open: false }">
                                <button @click="open = !open" type="button" class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none">
                                    Action
                                    <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                                <div x-show="open" @click.away="open = false" class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
                                    <div class="py-1" role="none">
                                        <a href="index.php?halaman=hapus-pendapatan&id=<?php echo $row['id'];?>&bulan_id=<?php echo $row['bulan_id']; ?>" class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100" role="menuitem">Delete</a>
                                        <button class="text-gray-700 block w-full text-left px-4 py-2 text-sm hover:bg-gray-100 edit-btn" 
                                            data-id="<?php echo $row['id']; ?>" 
                                            data-nama="<?php echo $row['nama_produk']; ?>" 
                                            data-terjual="<?php echo $row['terjual']; ?>" 
                                            data-total="<?php echo $row['total']; ?>"
                                            onclick="openEditModal(this)">Edit</button>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>    

                    <script>
                        function openEditModal(button) {
                            var id = button.getAttribute('data-id');
                            var nama = button.getAttribute('data-nama');
                            var terjual = button.getAttribute('data-terjual');
                            var total = button.getAttribute('data-total');

                            // Set data ke dalam modal
                            document.getElementById('dapat').value = id;
                            document.getElementById('edit_nama_produk').value = nama;
                            document.getElementById('edit_terjual').value = terjual;
                            document.getElementById('edit_total').value = total;
                            
                            // Tampilkan modal
                            document.getElementById('staticBackdrop').classList.remove('hidden');
                        }
                    </script>
                    <?php 
                            }
                        } else {
                            echo "
                            <tr>
                                <td colspan='4' class='text-center py-4'> Tidak ada data </td>
                            </tr>
                            ";
                        }
                    ?>                           
                </tbody>
            </table>
        </div>   
    </div>

    <!-- modal tambah pendapatan -->
    <div id="tambahData" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden" x-data="{ show: false }">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex justify-between items-center pb-3">
                    <h5 class="text-lg font-medium text-gray-900">Tambah Data Penjualan Perbulan</h5>
                    <button type="button" class="text-gray-400 hover:text-gray-500" onclick="document.getElementById('tambahData').classList.add('hidden')">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="p-3">
                    <form action="index.php" method="POST">
                        <input type="number" name="id_bulan" id="id_bulan" value="<?php echo $id_bulan ?>" hidden/>
                        <div class="mb-3">
                            <label for="nama_produk" class="block text-sm font-medium text-gray-700 mb-1">Nama produk</label>
                            <input type="text" name="nama_produk" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" id="nama_produk" required/>
                        </div> 
                        
                        <div class="mb-3">
                            <label for="terjual" class="block text-sm font-medium text-gray-700 mb-1">Terjual</label>
                            <input type="number" name="terjual" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" id="terjual" required/>
                        </div> 

                        <div class="mb-3">
                            <label for="total" class="block text-sm font-medium text-gray-700 mb-1">Total</label>
                            <input type="number" name="total" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" id="total" required/>
                        </div> 

                        <div class="text-right">
                            <button type="button" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md mr-2" onclick="document.getElementById('tambahData').classList.add('hidden')">Cancel</button>
                            <button type="submit" name="inputPendapatan" id="masukkandata" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md">Submit</button>
                        </div>
                    </form>
                </div>              
            </div>
        </div>
    </div>

    <!-- modal edit pendapatan -->
    <div id="staticBackdrop" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex justify-between items-center pb-3">
                    <h5 class="text-lg font-medium text-gray-900">Edit Data Penjualan Perbulan</h5>
                    <button type="button" class="text-gray-400 hover:text-gray-500" onclick="document.getElementById('staticBackdrop').classList.add('hidden')">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="p-3">
                    <form action="index.php" method="POST">
                        <input type="number" name="dapat" id="dapat" value="" hidden/>
                        <input type="number" name="edit_id_bulan" id="edit_id_bulan" value="<?php echo $id_bulan ?>" hidden/>
                        
                        <div class="mb-3">
                            <label for="edit_nama_produk" class="block text-sm font-medium text-gray-700 mb-1">Nama produk</label>
                            <input type="text" name="edit_nama_produk" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" id="edit_nama_produk" required/>
                        </div> 
                        
                        <div class="mb-3">
                            <label for="edit_terjual" class="block text-sm font-medium text-gray-700 mb-1">Terjual</label>
                            <input type="number" name="edit_terjual" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" id="edit_terjual" required/>
                        </div> 

                        <div class="mb-3">
                            <label for="edit_total" class="block text-sm font-medium text-gray-700 mb-1">Total</label>
                            <input type="number" name="edit_total" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" id="edit_total" required/>
                        </div> 

                        <div class="text-right">
                            <button type="submit" name="edit_pendapatan" id="masukkandata" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md">Submit</button>
                        </div>
                    </form>
                </div>              
            </div>
        </div>
    </div>
     
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script>
        // Initialize Alpine.js dropdown functionality
        document.addEventListener('alpine:init', () => {
            Alpine.data('dropdown', () => ({
                open: false,
                toggle() {
                    this.open = !this.open
                }
            }))
        })
    </script>
</body>
</html>
