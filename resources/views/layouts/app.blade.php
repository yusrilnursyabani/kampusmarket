<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'KampusMarket - Marketplace Kampus')</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'kampus-primary': '#00A500',
                        'kampus-dark': '#006E00',
                        'kampus-darker': '#005200',
                    }
                }
            }
        }
    </script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        .animate-fade-in {
            animation: fadeIn 0.5s ease-in;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
    
    @stack('styles')
</head>
<body class="bg-gray-50">
    <!-- Top Bar -->
    <div class="bg-kampus-darker text-white py-2">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center text-sm">
                <div class="flex items-center space-x-4">
                    <span><i class="fas fa-phone mr-1"></i> (021) 1234-5678</span>
                    <span><i class="fas fa-envelope mr-1"></i> info@kampusmarket.com</span>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ url('/seller') }}" target="_blank" class="hover:text-kampus-primary transition">
                        <i class="fas fa-store mr-1"></i> Panel Seller
                    </a>
                    <a href="{{ url('/admin') }}" target="_blank" class="hover:text-kampus-primary transition">
                        <i class="fas fa-user-shield mr-1"></i> Admin
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Bar -->
    <nav class="bg-white shadow-md sticky top-0 z-50">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <!-- Logo -->
                <a href="{{ route('products.index') }}" class="flex items-center">
                    <div class="w-12 h-12 bg-gradient-to-br from-kampus-primary to-kampus-darker rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-graduation-cap text-white text-2xl"></i>
                    </div>
                    <div>
                        <div class="text-2xl font-bold">
                            <span class="text-kampus-dark">Kampus</span><span class="text-kampus-primary">Market</span>
                        </div>
                        <p class="text-xs text-gray-500">Marketplace Mahasiswa Indonesia</p>
                    </div>
                </a>
                
                <!-- Search Bar (Desktop) -->
                <div class="hidden md:block flex-1 max-w-2xl mx-8">
                    <form action="{{ route('products.index') }}" method="GET">
                        <div class="relative">
                            <input type="text" name="search" placeholder="Cari produk, seller, atau kategori..." 
                                value="{{ request('search') }}"
                                class="w-full px-4 py-3 pr-12 border-2 border-kampus-primary rounded-lg focus:outline-none focus:border-kampus-dark transition">
                            <button type="submit" class="absolute right-0 top-0 h-full px-6 bg-kampus-primary text-white rounded-r-lg hover:bg-kampus-dark transition">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
                
                <!-- Navigation Links -->
                <div class="hidden lg:flex items-center space-x-6">
                    <a href="{{ route('products.index') }}" class="flex flex-col items-center text-gray-700 hover:text-kampus-primary transition {{ request()->is('products*') ? 'text-kampus-primary' : '' }}">
                        <i class="fas fa-home text-xl mb-1"></i>
                        <span class="text-sm font-medium">Beranda</span>
                    </a>
                    <a href="{{ route('seller.register') }}" class="px-6 py-2.5 bg-gradient-to-r from-kampus-primary to-kampus-dark text-white rounded-lg hover:from-kampus-dark hover:to-kampus-darker transition font-medium shadow-lg">
                        <i class="fas fa-user-plus mr-2"></i>Daftar Jadi Seller
                    </a>
                </div>

                <!-- Mobile Menu Button -->
                <button class="lg:hidden text-gray-700" onclick="toggleMobileMenu()">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
            </div>

            <!-- Mobile Search -->
            <div class="md:hidden pb-4">
                <form action="{{ route('products.index') }}" method="GET">
                    <div class="relative">
                        <input type="text" name="search" placeholder="Cari produk..." 
                            value="{{ request('search') }}"
                            class="w-full px-4 py-2 pr-12 border-2 border-kampus-primary rounded-lg focus:outline-none focus:border-kampus-dark">
                        <button type="submit" class="absolute right-0 top-0 h-full px-4 bg-kampus-primary text-white rounded-r-lg">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Mobile Menu -->
            <div id="mobileMenu" class="hidden lg:hidden pb-4 space-y-2">
                <a href="{{ route('products.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded">
                    <i class="fas fa-home mr-2"></i> Beranda
                </a>
                <a href="{{ route('seller.register') }}" class="block px-4 py-2 bg-kampus-primary text-white hover:bg-kampus-dark rounded">
                    <i class="fas fa-user-plus mr-2"></i> Daftar Seller
                </a>
            </div>
        </div>
    </nav>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="container mx-auto px-4 mt-4">
            <div class="bg-green-50 border-l-4 border-kampus-primary p-4 rounded-r-lg shadow-sm animate-fade-in">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-kampus-primary text-xl mr-3"></i>
                    <div class="flex-1">
                        <strong class="font-bold text-gray-800">Berhasil!</strong>
                        <span class="block sm:inline text-gray-700">{{ session('success') }}</span>
                    </div>
                    <button onclick="this.parentElement.parentElement.remove()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="container mx-auto px-4 mt-4">
            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg shadow-sm animate-fade-in">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle text-red-500 text-xl mr-3"></i>
                    <div class="flex-1">
                        <strong class="font-bold text-gray-800">Error!</strong>
                        <span class="block sm:inline text-gray-700">{{ session('error') }}</span>
                    </div>
                    <button onclick="this.parentElement.parentElement.remove()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <main class="min-h-screen">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-kampus-darker text-white mt-16">
        <div class="container mx-auto px-4 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- About -->
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-graduation-cap text-kampus-primary text-xl"></i>
                        </div>
                        <div>
                            <span class="text-xl font-bold">Kampus</span><span class="text-xl font-bold text-kampus-primary">Market</span>
                        </div>
                    </div>
                    <p class="text-gray-300 mb-4 leading-relaxed">
                        Platform marketplace terpercaya untuk mahasiswa dan pelaku UMKM kampus di seluruh Indonesia. 
                        Menghubungkan seller dengan pembeli dalam satu ekosistem digital yang aman dan terpercaya.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="w-10 h-10 bg-kampus-dark rounded-full flex items-center justify-center hover:bg-kampus-primary transition">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-kampus-dark rounded-full flex items-center justify-center hover:bg-kampus-primary transition">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-kampus-dark rounded-full flex items-center justify-center hover:bg-kampus-primary transition">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-kampus-dark rounded-full flex items-center justify-center hover:bg-kampus-primary transition">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h3 class="text-lg font-semibold mb-4 text-kampus-primary">Menu Cepat</h3>
                    <ul class="space-y-2">
                        <li><a href="{{ route('products.index') }}" class="text-gray-300 hover:text-kampus-primary transition flex items-center">
                            <i class="fas fa-angle-right mr-2"></i>Katalog Produk
                        </a></li>
                        <li><a href="{{ route('seller.register') }}" class="text-gray-300 hover:text-kampus-primary transition flex items-center">
                            <i class="fas fa-angle-right mr-2"></i>Daftar Seller
                        </a></li>
                        <li><a href="{{ url('/seller') }}" class="text-gray-300 hover:text-kampus-primary transition flex items-center">
                            <i class="fas fa-angle-right mr-2"></i>Panel Seller
                        </a></li>
                    </ul>
                </div>

                <!-- Contact -->
                <div>
                    <h3 class="text-lg font-semibold mb-4 text-kampus-primary">Hubungi Kami</h3>
                    <ul class="space-y-3 text-gray-300">
                        <li class="flex items-start">
                            <i class="fas fa-map-marker-alt mr-3 mt-1 text-kampus-primary"></i>
                            <span>Jl. Pendidikan No. 123<br>Jakarta, Indonesia</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-phone mr-3 text-kampus-primary"></i>
                            <span>(021) 1234-5678</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-envelope mr-3 text-kampus-primary"></i>
                            <span>info@kampusmarket.com</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-clock mr-3 text-kampus-primary"></i>
                            <span>Senin - Jumat, 09:00 - 17:00</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-kampus-dark mt-8 pt-8">
                <div class="flex flex-col md:flex-row justify-between items-center text-gray-300 text-sm">
                    <p>&copy; {{ date('Y') }} KampusMarket. All rights reserved.</p>
                    <div class="flex space-x-6 mt-4 md:mt-0">
                        <a href="#" class="hover:text-kampus-primary transition">Syarat & Ketentuan</a>
                        <a href="#" class="hover:text-kampus-primary transition">Kebijakan Privasi</a>
                        <a href="#" class="hover:text-kampus-primary transition">Bantuan</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script>
        function toggleMobileMenu() {
            document.getElementById('mobileMenu').classList.toggle('hidden');
        }
    </script>

    @stack('scripts')
</body>
</html>
