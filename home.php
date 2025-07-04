<?php
session_start();
include "koneksi/koneksi.php";

$produk = array();

$ambil = $koneksi->query("SELECT * FROM produk JOIN kategori
ON produk.id_kategori=kategori.id_kategori");

while ($pecah = $ambil->fetch_assoc()) {
    $produk[] = $pecah;
}

$user_id = null;
if (isset($_SESSION['username'])) {
    $user_id = $_SESSION['id_admin'] ?? $_SESSION['user_id'];
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Pempek Biniku</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        crimson: '#dc143c',
                        darksalmon: '#e9967a',
                        cream: '#FFFAE7',
                    },
                    fontFamily: {
                        roboto: ['Roboto', 'sans-serif'],
                        robotoMedium: ['Roboto Medium', 'sans-serif'],
                        robotoBold: ['Roboto Bold', 'sans-serif'],
                        robotoBlack: ['Roboto Black', 'sans-serif'],
                        playfair: ['Playfair Display', 'serif'],
                        playfairBold: ['Playfair Display Bold', 'serif'],
                        playfairExtraBold: ['Playfair Display ExtraBold', 'serif'],
                        openSansBold: ['Open Sans Bold', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <style>
        @font-face {
            font-family: 'Roboto';
            src: url(../font/Roboto-Regular.ttf);
        }

        @font-face {
            font-family: 'Roboto Medium';
            src: url(../font/Roboto-Medium.ttf);
        }

        @font-face {
            font-family: 'Roboto Bold';
            src: url(../font/Roboto-Bold.ttf);
        }

        @font-face {
            font-family: 'Roboto Black';
            src: url(../font/Roboto-Black.ttf);
        }

        @font-face {
            font-family: 'Playfair Display';
            src: url(../font/PlayfairDisplay-Regular.ttf);
        }

        @font-face {
            font-family: 'Playfair Display Bold';
            src: url(../font/PlayfairDisplay-Bold.ttf);
        }

        @font-face {
            font-family: 'Playfair Display ExtraBold';
            src: url(../font/PlayfairDisplay-ExtraBold.ttf);
        }

        @font-face {
            font-family: 'Open Sans Bold';
            src: url(../font/OpenSans-Bold.ttf);
        }

        /* For the underline animation effect */
        .nav-link::after {
            content: "";
            display: block;
            padding-bottom: 0.5rem;
            border-bottom: 0.1rem solid #fff;
            transform: scaleX(0);
            transition: 0.2s linear;
        }

        .nav-link:hover::after {
            transform: scaleX(0.5);
        }

        /* Hide number input spinners */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* carrousel */
        @keyframes fadeIn {
            0% {
                opacity: 0;
            }

            100% {
                opacity: 1;
            }
        }

        @keyframes slideUp {
            0% {
                opacity: 0;
                transform: translateY(30px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fadeIn {
            animation: fadeIn 1s ease-in-out forwards;
        }

        .animate-slideUp {
            animation: slideUp 1s ease-out forwards;
        }

        .animation-delay-300 {
            animation-delay: 0.3s;
        }

        .duration-7000 {
            transition-duration: 7000ms;
        }
    </style>
</head>

<body class="bg-[#FFFAE7] text-white text-center">
    <!-- Navbar -->
    <nav class="bg-red-600 flex justify-between items-center px-[7%] py-[0.2rem] fixed top-0 left-0 right-0 z-[9999]">
        <img src="./assets/foto/Logo.png" class="w-[75px]">

        <div class="navbar-menu">
            <a href="index.php"
                class="text-white text-base mx-4 font-bold inline-block font-robotoMedium nav-link hover:text-darksalmon">Beranda</a>
            <a href="#about"
                class="text-white text-base mx-4 font-bold inline-block font-robotoMedium nav-link hover:text-darksalmon">About</a>
            <a href="#produk"
                class="text-white text-base mx-4 font-bold inline-block font-robotoMedium nav-link hover:text-darksalmon">Produk</a>
            <a href="#kontak"
                class="text-white text-base mx-4 font-bold inline-block font-robotoMedium nav-link hover:text-darksalmon">Kontak</a>
        </div>

        <div class="navbar-icon flex items-center space-x-4">
            <?php if (isset($_SESSION['username'])) { ?>
                <!-- Shopping Cart Icon -->
                <div class="relative">
                    <a href="keranjang.php" class="text-white hover:text-darksalmon transition-colors duration-300">
                        <i class="fas fa-shopping-cart text-xl"></i>
                        <?php
                        // Make sure $user_id is defined
                        if (isset($_SESSION['user_id'])) {
                            $user_id = $_SESSION['user_id'];
                            $sql_keranjang = "SELECT COUNT(*) as total FROM keranjang_user WHERE id_user = '$user_id'";
                            $result = mysqli_query($koneksi, $sql_keranjang);
                            $data = mysqli_fetch_assoc($result);
                            if ($data['total'] > 0) {
                                ?>
                                <span
                                    class="absolute -top-2 -right-2 bg-white text-crimson rounded-full text-xs w-5 h-5 flex items-center justify-center font-bold">
                                    <?php echo $data['total']; ?>
                                </span>
                                <?php
                            }
                        }
                        ?>
                    </a>
                </div>

                <!-- User Profile Icon -->
                <div class="relative">
                    <button id="btn-user"
                        class="text-white hover:text-darksalmon transition-colors duration-300 p-2 rounded-full hover:bg-red-700">
                        <i class="fas fa-user text-xl"></i>
                    </button>

                    <!-- User Dropdown Menu -->
                    <div id="user-dropdown"
                        class="absolute top-full right-0 mt-2 w-48 bg-white text-black rounded-lg shadow-lg transition-all duration-300 opacity-0 invisible transform translate-y-2 z-50">
                        <div class="py-2">
                            <div class="px-4 py-2 border-b border-gray-200">
                                <p class="text-sm text-gray-600">Selamat datang,</p>
                                <p class="font-semibold text-crimson"><?php echo $_SESSION['username']; ?></p>
                            </div>
                            <a href="update_profil.php"
                                class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                                <i class="fas fa-user-circle mr-3 text-crimson"></i>
                                <span>Profil Saya</span>
                            </a>
                            <a href="keranjang.php"
                                class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                                <i class="fas fa-shopping-bag mr-3 text-crimson"></i>
                                <span>Pesanan Saya</span>
                            </a>
                            <div class="border-t border-gray-200"></div>
                            <a href="admin/logout.php"
                                class="flex items-center px-4 py-3 text-red-600 hover:bg-red-50 transition-colors duration-200">
                                <i class="fas fa-sign-out-alt mr-3"></i>
                                <span>Logout</span>
                            </a>
                        </div>
                    </div>
                </div>
            <?php } else { ?>
                <!-- Login/Register buttons for non-logged in users -->
                <div class="flex items-center space-x-2">
                    <a href="admin/login.php"
                        class="bg-white text-red-600 px-4 py-2 rounded-full font-robotoMedium hover:bg-gray-100 transition-colors duration-300">
                        Login
                    </a>
                    <a href="daftar.php"
                        class="border border-white text-white px-4 py-2 rounded-full font-robotoMedium hover:bg-white hover:text-red-600 transition-colors duration-300">
                        Daftar
                    </a>
                </div>
            <?php } ?>

            <!-- Mobile Menu Button (hidden by default) -->
            <button id="btn-menu" class="text-white mx-2 hidden lg:hidden">
                <i class="fas fa-bars text-xl"></i>
            </button>
        </div>
    </nav>


    <section class="relative w-full overflow-hidden">
        <!-- Carousel container -->
        <div id="carouselExampleIndicators" class="relative w-full h-screen">
            <!-- Carousel indicators -->
            <div class="absolute bottom-8 left-0 right-0 z-10 flex justify-center space-x-3">
                <button type="button" data-target="#carouselExampleIndicators" data-slide-to="1"
                    class="w-4 h-4 rounded-full bg-white opacity-50 active:opacity-100 hover:scale-125 transition-all duration-300"></button>
                <button type="button" data-target="#carouselExampleIndicators" data-slide-to="2"
                    class="w-4 h-4 rounded-full bg-white opacity-50 hover:scale-125 transition-all duration-300"></button>
                <button type="button" data-target="#carouselExampleIndicators" data-slide-to="3"
                    class="w-4 h-4 rounded-full bg-white opacity-50 hover:scale-125 transition-all duration-300"></button>
            </div>

            <!-- Carousel slides with fade transition -->
            <div class="carousel-inner h-full">
                <div
                    class="carousel-item active h-full absolute inset-0 transition-opacity duration-1000 ease-in-out opacity-100">
                    <div class="absolute inset-0 bg-black bg-opacity-30"></div>
                    <img class="w-full h-full object-cover transform scale-100 transition-transform duration-7000 ease-linear hover:scale-110"
                        src="./assets/foto/banner44.jpg" alt="First slide">
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="text-center px-4 animate-fadeIn">
                            <h2
                                class="text-5xl font-playfairBold text-white mb-4 opacity-0 translate-y-10 animate-slideUp">
                                Pempek Biniku</h2>
                            <p
                                class="text-xl text-white font-robotoMedium opacity-0 translate-y-10 animate-slideUp animation-delay-300">
                                Cita Rasa Autentik Palembang</p>
                        </div>
                    </div>
                </div>
                <div
                    class="carousel-item hidden h-full absolute inset-0 transition-opacity duration-1000 ease-in-out opacity-0">
                    <div class="absolute inset-0 bg-black bg-opacity-30"></div>
                    <img class="w-full h-full object-cover transform scale-100 transition-transform duration-7000 ease-linear hover:scale-110"
                        src="./assets/foto/banner22.jpg" alt="Second slide">
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="text-center px-4">
                            <h2 class="text-5xl font-playfairBold text-white mb-4">Kualitas Terbaik</h2>
                            <p class="text-xl text-white font-robotoMedium">Dari Bahan Pilihan Terbaik</p>
                        </div>
                    </div>
                </div>
                <div
                    class="carousel-item hidden h-full absolute inset-0 transition-opacity duration-1000 ease-in-out opacity-0">
                    <div class="absolute inset-0 bg-black bg-opacity-30"></div>
                    <img class="w-full h-full object-cover transform scale-100 transition-transform duration-7000 ease-linear hover:scale-110"
                        src="./assets/foto/banner33.jpg" alt="Third slide">
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="text-center px-4">
                            <h2 class="text-5xl font-playfairBold text-white mb-4">Pesan Sekarang</h2>
                            <p class="text-xl text-white font-robotoMedium">Nikmati Kelezatan Pempek Biniku</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Carousel controls with improved styling -->
            <button
                class="carousel-control-prev absolute top-1/2 left-6 transform -translate-y-1/2 z-10 flex items-center justify-center w-12 h-12 rounded-full bg-black bg-opacity-50 text-white hover:bg-opacity-70 transition-all duration-300 hover:scale-110"
                type="button" data-target="#carouselExampleIndicators" data-slide="prev">
                <span class="sr-only">Previous</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
            <button
                class="carousel-control-next absolute top-1/2 right-6 transform -translate-y-1/2 z-10 flex items-center justify-center w-12 h-12 rounded-full bg-black bg-opacity-50 text-white hover:bg-opacity-70 transition-all duration-300 hover:scale-110"
                type="button" data-target="#carouselExampleIndicators" data-slide="next">
                <span class="sr-only">Next</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
        </div>
    </section>


    <div class="container mx-auto">
        <section id="about" class="mt-28 pb-12">
            <div class="flex flex-wrap">
                <div class="w-full md:w-1/2 my-auto">
                    <img src="assets/foto/banner1.jpg" class="w-full">
                </div>
                <div class="w-full md:w-1/2 p-5">
                    <h2 class="text-4xl font-bold font-playfairBold text-red-600 mb-12 pb-2"><span>ABOUT US</span></h2>
                    <p class="text-xl text-red-600 font-openSansBold text-justify tracking-wider">
                        Selamat datang di Pempek Biniku, rumah bagi cita rasa autentik Palembang! Kami adalah produsen
                        dan penjual pempek yang berdedikasi untuk membawa kelezatan khas Palembang ke seluruh penjuru
                        negeri. Dengan resep tradisional yang diwariskan turun-temurun, kami hanya menggunakan
                        bahan-bahan
                        pilihan untuk memastikan setiap gigitan pempek kami memberikan rasa yang otentik dan memuaskan.
                    </p>
                </div>
            </div>
        </section>

        <section id="produk" class="mt-28 text-center">
            <h2 class="text-4xl font-bold font-playfairBold text-red-600 mb-12 pb-2"><span>PRODUK KAMI</span></h2>

            <!-- Filter Buttons -->
            <div class="mb-12 text-center">
                <div class="flex flex-wrap justify-center">
                    <div class="">
                        <button type="button"
                            class="px-5 py-2 text-base rounded-full border border-red-600 text-red-600 hover:bg-red-600 hover:text-white transition-colors duration-300 active:bg-red-600 active:text-white kategori-btn"
                            onclick="filterProduk('semua')">Semua</button>
                        <button type="button"
                            class="px-5 py-2 text-base rounded-full border border-red-600 text-red-600 hover:bg-red-600 hover:text-white transition-colors duration-300 kategori-btn"
                            onclick="filterProduk('Pempek Satuan')">Pempek Satuan</button>
                        <button type="button"
                            class="px-5 py-2 text-base rounded-full border border-red-600 text-red-600 hover:bg-red-600 hover:text-white transition-colors duration-300 kategori-btn"
                            onclick="filterProduk('Pempek Paket')">Pempek Paket</button>
                        <button type="button"
                            class="px-5 py-2 text-base rounded-full border border-red-600 text-red-600 hover:bg-red-600 hover:text-white transition-colors duration-300 kategori-btn"
                            onclick="filterProduk('Other')">Other</button>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4" id="produk-list">
                <?php foreach ($produk as $key => $value): ?>
                    <div class="produk-item mb-4" data-kategori="<?php echo strtolower($value['nama_kategori']); ?>">
                        <form action="index.php" method="post">
                            <div class="bg-white rounded-lg shadow-lg border-0 h-full">
                                <img src="assets/foto/<?php echo $value['foto_produk']; ?>"
                                    class="w-full h-48 object-cover rounded-t-lg">
                                <div class="p-4 text-center">
                                    <input type="hidden" name="id_produk" value="<?php echo $value['id_produk']; ?>">
                                    <input type="hidden" name="nama_produk" value="<?php echo $value['nama_produk']; ?>">
                                    <input type="hidden" name="harga_produk" value="<?php echo $value['harga_produk']; ?>">
                                    <input type="hidden" name="foto_produk" value="<?php echo $value['foto_produk']; ?>">
                                    <h5 class="text-black font-robotoBold"><?php echo $value['nama_produk']; ?></h5>
                                    <p class="text-black font-robotoBold">Stok:
                                        <?php echo number_format($value['stok_produk']); ?>
                                    </p>
                                    <p class="text-red-600 font-bold">
                                        Rp.<?php echo number_format($value['harga_produk']); ?></p>

                                    <div class="flex items-center justify-center mb-3">
                                        <button type="button"
                                            class="btn-decrease border border-gray-800 text-black px-2 py-1 rounded">-</button>
                                        <input type="number" name="jumlah" value="1" min="1"
                                            class="input-jumlah text-center mx-2 w-12 border border-gray-800"
                                            data-harga="<?php echo $value['harga_produk']; ?>"
                                            data-total-id="total-<?php echo $key; ?>">
                                        <button type="button"
                                            class="btn-increase border border-gray-800 text-black px-2 py-1 rounded">+</button>
                                    </div>

                                    <input type="submit" name="beli" value="Masukkan ke keranjang"
                                        class="w-full bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded transition duration-300">
                                </div>
                            </div>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

    </div>
    <section class="mt-28 mb-15 mx-16">
        <h2 class="text-4xl font-bold font-[playfair] text-red-600 mb-12 pb-2"><span>KONTAK KAMI</span></h2>
        <div class="row">

            <iframe class="w-full h-96 shadow-lg"
                src="https://www.google.com/maps/embed?pb=!1m17!1m12!1m3!1d16335807.982538566!2d92.02327591828396!3d-1.6145300000000005!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m2!1m1!2zMcKwMzYnNTIuMyJTIDEwM8KwMzcnMzAuMSJF!5e0!3m2!1sen!2sid!4v1724488443830!5m2!1sen!2sid"
                allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </section>

    <div class="w-full md:w-1/2">
        <!-- Contact form was commented out in the original code -->
    </div>
    <!-- </div>
    </section>
    </div> -->

    <!-- Footer -->
    <footer class="bg-red-500 text-white py-5 text-center font-playfair">
        <div class="footer-follow mb-3">
            <h5 class="text-lg mb-2">Follow Us</h5>
            <div class="flex justify-center space-x-4">
                <a href="https://instagram.com/pempekbiniku_" target="_blank">
                    <img src="assets/foto/instagram.jpeg" alt="Instagram" class="w-10 h-10 rounded">
                </a>
                <a href="https://www.tokopedia.com/pempekbiniku" target="_blank">
                    <img src="assets/foto/tokopedia.png" alt="Tokopedia" class="w-10 h-10 rounded">
                </a>
                <a href="https://shopee.co.id/pempekbiniku" target="_blank">
                    <img src="assets/foto/shopee.png" alt="Shopee" class="w-10 h-10 rounded">
                </a>
            </div>
        </div>

        <div class="container mx-auto">
            <div class="copyright">
                <span>Copyright &copy; Pempek Biniku 2024</span>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="assets/vendor/jquery/jquery.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="assets/js/sb-admin-2.min.js"></script>
    <script src="assets/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="assets/vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="assets/js/demo/datatables-demo.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Custom Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Carousel functionality
            const carousel = document.getElementById('carouselExampleIndicators');
            const items = carousel.querySelectorAll('.carousel-item');
            const indicators = carousel.querySelectorAll('[data-slide-to]');
            const prevBtn = carousel.querySelector('.carousel-control-prev');
            const nextBtn = carousel.querySelector('.carousel-control-next');
            let activeIndex = 0;
            let intervalId = null;

            // Function to update carousel
            function updateCarousel(index) {
                // Hide all items with fade effect
                items.forEach(item => {
                    if (item.classList.contains('active')) {
                        item.classList.remove('active');
                        item.style.opacity = '0';
                        setTimeout(() => {
                            item.classList.add('hidden');
                        }, 1000); // Match this with the transition duration
                    }
                });

                // Show active item with fade effect
                setTimeout(() => {
                    items[index].classList.remove('hidden');
                    // Trigger reflow
                    void items[index].offsetWidth;
                    items[index].classList.add('active');
                    items[index].style.opacity = '1';

                    // Reset and restart text animations
                    const textElements = items[index].querySelectorAll('.animate-slideUp');
                    textElements.forEach(el => {
                        el.style.opacity = '0';
                        el.style.transform = 'translateY(10px)';
                        // Trigger reflow
                        void el.offsetWidth;
                        el.style.opacity = '';
                        el.style.transform = '';
                    });
                }, 50);

                // Update indicators
                indicators.forEach((indicator, i) => {
                    if (i === index) {
                        indicator.classList.add('opacity-100');
                        indicator.classList.remove('opacity-50');
                    } else {
                        indicator.classList.add('opacity-50');
                        indicator.classList.remove('opacity-100');
                    }
                });

                activeIndex = index;
            }

            // Function to go to next slide
            function nextSlide() {
                let newIndex = activeIndex + 1;
                if (newIndex >= items.length) newIndex = 0;
                updateCarousel(newIndex);
            }

            // Function to go to previous slide
            function prevSlide() {
                let newIndex = activeIndex - 1;
                if (newIndex < 0) newIndex = items.length - 1;
                updateCarousel(newIndex);
            }

            // Function to start auto-sliding
            function startAutoSlide() {
                // Clear any existing interval
                if (intervalId) clearInterval(intervalId);

                // Set new interval
                intervalId = setInterval(nextSlide, 5000); // Change slide every 5 seconds
            }

            // Function to pause auto-sliding (when user interacts with carousel)
            function pauseAutoSlide() {
                if (intervalId) {
                    clearInterval(intervalId);
                    intervalId = null;
                }
            }

            // Function to resume auto-sliding
            function resumeAutoSlide() {
                startAutoSlide();
            }

            // Set up indicators
            indicators.forEach((indicator, index) => {
                indicator.addEventListener('click', () => {
                    updateCarousel(index);
                    pauseAutoSlide();
                    resumeAutoSlide(); // Restart auto-sliding after user interaction
                });
            });

            // Set up prev/next buttons
            prevBtn.addEventListener('click', () => {
                prevSlide();
                pauseAutoSlide();
                resumeAutoSlide(); // Restart auto-sliding after user interaction
            });

            nextBtn.addEventListener('click', () => {
                nextSlide();
                pauseAutoSlide();
                resumeAutoSlide(); // Restart auto-sliding after user interaction
            });

            // Pause auto-sliding when mouse is over the carousel
            carousel.addEventListener('mouseenter', pauseAutoSlide);

            // Resume auto-sliding when mouse leaves the carousel
            carousel.addEventListener('mouseleave', resumeAutoSlide);

            // Ensure first slide is properly displayed on page load
            // This is the key fix - we need to explicitly set the first slide's visibility
            items.forEach((item, i) => {
                if (i === 0) {
                    item.classList.add('active');
                    item.classList.remove('hidden');
                    item.style.opacity = '1';

                    // Ensure text animations play on first load
                    const textElements = item.querySelectorAll('.animate-slideUp');
                    textElements.forEach(el => {
                        el.style.opacity = '';
                        el.style.transform = '';
                    });
                } else {
                    item.classList.remove('active');
                    item.classList.add('hidden');
                    item.style.opacity = '0';
                }
            });

            // Set first indicator as active
            if (indicators.length > 0) {
                indicators[0].classList.add('opacity-100');
                indicators[0].classList.remove('opacity-50');
            }

            // Initialize carousel and start auto-sliding
            activeIndex = 0;
            startAutoSlide();
        });

        // User dropdown toggle
        document.addEventListener('DOMContentLoaded', function () {
            // User dropdown toggle
            const userBtn = document.getElementById('btn-user');
            const userDropdown = document.getElementById('user-dropdown');

            if (userBtn && userDropdown) {
                userBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();

                    // Toggle dropdown visibility
                    if (userDropdown.classList.contains('opacity-0')) {
                        // Show dropdown
                        userDropdown.classList.remove('opacity-0', 'invisible', 'translate-y-2');
                        userDropdown.classList.add('opacity-100', 'visible', 'translate-y-0');
                    } else {
                        // Hide dropdown
                        userDropdown.classList.add('opacity-0', 'invisible', 'translate-y-2');
                        userDropdown.classList.remove('opacity-100', 'visible', 'translate-y-0');
                    }
                });

                // Close dropdown when clicking outside
                document.addEventListener('click', function (e) {
                    if (!userBtn.contains(e.target) && !userDropdown.contains(e.target)) {
                        userDropdown.classList.add('opacity-0', 'invisible', 'translate-y-2');
                        userDropdown.classList.remove('opacity-100', 'visible', 'translate-y-0');
                    }
                });

                // Close dropdown when pressing Escape key
                document.addEventListener('keydown', function (e) {
                    if (e.key === 'Escape') {
                        userDropdown.classList.add('opacity-0', 'invisible', 'translate-y-2');
                        userDropdown.classList.remove('opacity-100', 'visible', 'translate-y-0');
                    }
                });
            }

            // Mobile menu toggle (if needed)
            const menuBtn = document.getElementById('btn-menu');
            const navbarMenu = document.querySelector('.navbar-menu');

            if (menuBtn && navbarMenu) {
                menuBtn.addEventListener('click', function () {
                    navbarMenu.classList.toggle('hidden');
                });
            }
        });

        // Filter products
        function filterProduk(kategori) {
            let items = document.querySelectorAll('.produk-item');
            kategori = kategori.toLowerCase();

            items.forEach(item => {
                let itemKategori = item.getAttribute('data-kategori');

                if (kategori === 'semua' || itemKategori.includes(kategori)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });

            // Update active button
            let buttons = document.querySelectorAll('.kategori-btn');
            buttons.forEach(btn => {
                btn.classList.remove('bg-red-600', 'text-white');
                btn.classList.add('text-red-600', 'bg-transparent');
            });
            event.target.classList.remove('text-red-600', 'bg-transparent');
            event.target.classList.add('bg-red-600', 'text-white');
        }

        // Quantity buttons
        document.addEventListener('DOMContentLoaded', function () {
            // Decrease quantity
            document.querySelectorAll('.btn-decrease').forEach(button => {
                button.addEventListener('click', function () {
                    let input = this.nextElementSibling;
                    let currentValue = parseInt(input.value);
                    if (currentValue > 1) {
                        input.value = currentValue - 1;
                    }
                });
            });

            // Increase quantity
            document.querySelectorAll('.btn-increase').forEach(button => {
                button.addEventListener('click', function () {
                    let input = this.previousElementSibling;
                    let currentValue = parseInt(input.value);
                    input.value = currentValue + 1;
                });
            });

            // Set first filter button as active
            document.querySelector('.kategori-btn').classList.add('bg-red-600', 'text-white');
        });
    </script>
</body>

</html>