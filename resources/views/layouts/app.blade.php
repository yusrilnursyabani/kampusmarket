<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'KampusMarket - Marketplace Kampus')</title>
    
    <!-- Tailwind CSS CDN untuk styling cepat -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome untuk icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    @stack('styles')
</head>
<body class="bg-gray-50">
    <!-- Navigation Bar -->
    <nav class="bg-white shadow-md sticky top-0 z-50">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <!-- Logo/Brand -->
                <a href="{{ route('products.index') }}" class="text-2xl font-bold text-blue-600">
                    <i class="fas fa-store"></i> KampusMarket
                </a>
                
                <!-- Navigation Links -->
                <div class="flex items-center space-x-6">
                    <a href="{{ route('products.index') }}" class="text-gray-700 hover:text-blue-600 transition">
                        <i class="fas fa-home"></i> Beranda
                    </a>
                    <a href="{{ route('seller.register') }}" class="text-white bg-gradient-to-r from-amber-500 to-orange-600 px-4 py-2 rounded-lg hover:from-amber-600 hover:to-orange-700 transition font-semibold">
                        <i class="fas fa-user-plus"></i> Daftar Seller
                    </a>
                    <a href="{{ url('/admin') }}" class="text-gray-700 hover:text-blue-600 transition">
                        <i class="fas fa-user-shield"></i> Admin
                    </a>
                    <a href="{{ url('/seller') }}" class="text-gray-700 hover:text-blue-600 transition">
                        <i class="fas fa-store-alt"></i> Seller
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="container mx-auto px-4 mt-4">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Berhasil!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="container mx-auto px-4 mt-4">
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Error!</strong>
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <main class="py-8">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white mt-16">
        <div class="container mx-auto px-4 py-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-xl font-bold mb-4">KampusMarket</h3>
                    <p class="text-gray-400">Marketplace khusus produk dari toko-toko kampus di seluruh Indonesia.</p>
                </div>
                <div>
                    <h3 class="text-xl font-bold mb-4">Menu</h3>
                    <ul class="space-y-2">
                        <li><a href="{{ route('products.index') }}" class="text-gray-400 hover:text-white transition">Katalog Produk</a></li>
                        <li><a href="{{ url('/seller') }}" class="text-gray-400 hover:text-white transition">Daftar Sebagai Penjual</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-xl font-bold mb-4">Kontak</h3>
                    <p class="text-gray-400">
                        <i class="fas fa-envelope"></i> info@kampusmarket.com<br>
                        <i class="fas fa-phone"></i> +62 123 4567 890
                    </p>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} KampusMarket. Tugas Besar Proyek Perangkat Lunak.</p>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
