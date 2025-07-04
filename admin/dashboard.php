<div class="shadow p-4 mb-3 bg-white rounded">
    <h3>Selamat datang <strong><?php echo $_SESSION['username']; ?>
</strong> anda Login sebagai <strong>Admin</strong> !</h3>
</div>


<?php

include '../koneksi/koneksi.php';


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Custom fonts -->
    <link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4">
        <button type="button" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded focus:outline-none focus:shadow-outline" data-modal-target="staticBackdrop" data-modal-toggle="staticBackdrop">
            + Tambah Pendapatan 
        </button>
        <div class="flex flex-wrap -mx-3">
            <!-- Dashboard panels -->
                <?php
                    $query = "SELECT * FROM bulan";
                    $result = mysqli_query($koneksi, $query);

                    if(mysqli_num_rows($result) > 0){
                        foreach($result as $row){
                        $bulan = date("F", strtotime($row['bulan']));
               ?>
                    <div class="w-full md:w-1/2 lg:w-1/3 px-3 py-3">
                        <div class="bg-white rounded-lg shadow-md h-full">
                            <div class="p-5">
                                <h5 class="text-xl font-medium mb-2"><?php echo $bulan;?></h5>
                                <p class="text-gray-700 mb-4">
                                    profit <span> <?php echo number_format($row['total'], 0, ',', '.'); ?></span>
                                </p>
                                <div class="flex justify-center">
                                    <a href="index.php?halaman=detail-pendapatan&id=<?php echo $row['id'] ?>" class="w-full">
                                        <button type="button" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full">Detail</button>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php
                        }
                    } else {
                        echo "
                        <div class='flex justify-center w-full'>
                            <h3 class='text-xl font-medium'>Data tidak ditemukan</h3>
                        </div>
                        ";
                    }
                ?>                                     
             
        </div>
    </div>

    <!-- Modal -->
    <div id="staticBackdrop" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-lg shadow">
                <div class="flex items-center justify-between p-4 border-b rounded-t">
                    <h3 class="text-xl font-medium text-gray-900">Tambah Bulan</h3>
                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center" data-modal-hide="staticBackdrop">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                        <span class="sr-only">Close</span>
                    </button>
                </div>
                <div class="p-6">
                    <form action="index.php" method="POST">
                        <div class="mb-4">
                            <label for="tanggal" class="block mb-2 text-sm font-medium text-gray-900">Tanggal</label>
                            <input type="date" name="bulan" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" id="tanggal" required/>
                            <div id="error-message" class="text-red-500 mt-2 text-sm"></div>
                        </div>                                               
                        <div class="text-right">
                            <button type="submit" name="inputBulan" id="masukkandata" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Submit</button>
                        </div>
                    </form>
                </div>              
            </div>
        </div>
    </div>

    <script>
        document.getElementById('tanggal').addEventListener('change', function() {
            var inputDate = new Date(this.value);
            var currentDate = new Date();
            var btnTambah = document.getElementById('masukkandata');

            // Set tanggal saat ini ke awal bulan untuk membandingkan hanya bulan dan tahun
            currentDate.setDate(1);
            inputDate.setDate(1);

            if (inputDate > currentDate) {
                document.getElementById('error-message').innerText = "Tanggal yang dipilih tidak boleh melebihi bulan saat ini.";
                this.value = ""; // Reset input jika tidak valid
                btnTambah.disabled = true;

            } else {
                document.getElementById('error-message').innerText = "";
                btnTambah.disabled = false;

            }
        });
    </script>
    
    <!-- Flowbite JS for modal functionality -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.5/flowbite.min.js"></script>
</body>
</html>
