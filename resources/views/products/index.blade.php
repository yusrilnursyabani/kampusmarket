@extends('layouts.app')

@section('title', 'Katalog Produk - KampusMarket')

@section('content')
<!-- Breadcrumb -->
<div class="bg-gray-100 py-4">
    <div class="container mx-auto px-4">
        <div class="flex items-center text-sm text-gray-600">
            <a href="{{ url('/') }}" class="hover:text-kampus-primary transition"><i class="fas fa-home mr-1"></i>Beranda</a>
            <i class="fas fa-chevron-right mx-2 text-xs"></i>
            <span class="text-kampus-dark font-medium">Katalog Produk</span>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col lg:flex-row gap-6">
        
        <!-- Sidebar Filter (Desktop & Mobile Toggle) -->
        <aside class="lg:w-80 flex-shrink-0">
            <div class="bg-white rounded-lg shadow-md border border-gray-200 sticky top-24">
                <!-- Filter Header -->
                <div class="bg-gradient-to-r from-kampus-primary to-kampus-dark px-6 py-4 rounded-t-lg">
                    <h2 class="text-white font-bold text-lg flex items-center">
                        <i class="fas fa-sliders-h mr-2"></i> Filter Produk
                    </h2>
                </div>

                <!-- Filter Form -->
                <form method="GET" action="{{ route('products.index') }}" class="p-6 space-y-6">
                    
                    <!-- Search -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">
                            <i class="fas fa-search text-kampus-primary mr-2"></i>Cari Produk
                        </label>
                        <input type="text" name="search" value="{{ request('search') }}" 
                            placeholder="Masukkan nama produk..." 
                            class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-kampus-primary focus:border-kampus-primary transition">
                    </div>

                    <!-- Category Filter -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">
                            <i class="fas fa-tags text-kampus-primary mr-2"></i>Kategori
                        </label>
                        <div class="space-y-2 max-h-48 overflow-y-auto pr-2">
                            <label class="flex items-center p-2 rounded hover:bg-gray-50 transition cursor-pointer">
                                <input type="radio" name="category" value="" {{ !request('category') ? 'checked' : '' }} 
                                    class="mr-3 text-kampus-primary focus:ring-kampus-primary">
                                <span class="text-sm">Semua Kategori</span>
                            </label>
                            @foreach($categories as $category)
                                <label class="flex items-center p-2 rounded hover:bg-gray-50 transition cursor-pointer {{ request('category') == $category->id ? 'bg-green-50' : '' }}">
                                    <input type="radio" name="category" value="{{ $category->id }}" 
                                        {{ request('category') == $category->id ? 'checked' : '' }}
                                        class="mr-3 text-kampus-primary focus:ring-kampus-primary">
                                    <span class="text-sm">{{ $category->nama_kategori }}</span>
                                    <span class="ml-auto text-xs text-gray-500">({{ $category->products->count() }})</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Seller Name -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">
                            <i class="fas fa-store text-kampus-primary mr-2"></i>Nama Toko
                        </label>
                        <input type="text" name="seller" value="{{ request('seller') }}"
                            placeholder="Cari nama toko..."
                            list="seller-options"
                            class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-kampus-primary focus:border-kampus-primary transition">
                        <datalist id="seller-options">
                            @foreach($sellerNames as $sellerName)
                                <option value="{{ $sellerName }}"></option>
                            @endforeach
                        </datalist>
                    </div>

                    <!-- Location Filter -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">
                            <i class="fas fa-map-marker-alt text-kampus-primary mr-2"></i>Lokasi Toko
                        </label>
                        
                        <!-- Province -->
                        <select name="provinsi" class="w-full px-4 py-2.5 mb-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-kampus-primary focus:border-kampus-primary transition">
                            <option value="">Semua Provinsi</option>
                            @foreach($provinces as $province)
                                <option value="{{ $province }}" {{ request('provinsi') == $province ? 'selected' : '' }}>
                                    {{ $province }}
                                </option>
                            @endforeach
                        </select>

                        <!-- City -->
                        <input type="text" name="kota" value="{{ request('kota') }}"
                            placeholder="Kota/Kabupaten..."
                            class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-kampus-primary focus:border-kampus-primary transition">
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-3 pt-4 border-t">
                        <button type="submit" class="flex-1 py-2.5 bg-kampus-primary text-white font-semibold rounded-lg hover:bg-kampus-dark transition shadow-lg">
                            <i class="fas fa-search mr-2"></i>Terapkan
                        </button>
                        <a href="{{ route('products.index') }}" class="px-4 py-2.5 bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 transition">
                            <i class="fas fa-redo"></i>
                        </a>
                    </div>
                </form>
            </div>
        </aside>

        <!-- Main Product Grid -->
        <main class="flex-1">
            <!-- Results Header -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 px-6 py-4 mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800 mb-1">Katalog Produk</h1>
                    <p class="text-sm text-gray-600">
                        Menampilkan <span class="font-semibold text-kampus-dark">{{ $products->total() }}</span> produk
                        @if(request()->hasAny(['search', 'category', 'seller', 'provinsi', 'kota']))
                            <span class="mx-2">•</span>
                            <span class="text-kampus-primary font-medium">Filter aktif</span>
                        @endif
                    </p>
                </div>
                
                <!-- Active Filters Tags -->
                @if(request()->hasAny(['search', 'category', 'seller', 'provinsi', 'kota']))
                    <div class="flex flex-wrap gap-2">
                        @if(request('search'))
                            <span class="inline-flex items-center px-3 py-1 bg-kampus-primary/10 text-kampus-dark text-sm rounded-full">
                                <i class="fas fa-search mr-1 text-xs"></i>{{ request('search') }}
                            </span>
                        @endif
                        @if(request('category'))
                            <span class="inline-flex items-center px-3 py-1 bg-kampus-primary/10 text-kampus-dark text-sm rounded-full">
                                <i class="fas fa-tag mr-1 text-xs"></i>{{ $categories->find(request('category'))->nama_kategori }}
                            </span>
                        @endif
                        @if(request('seller'))
                            <span class="inline-flex items-center px-3 py-1 bg-kampus-primary/10 text-kampus-dark text-sm rounded-full">
                                <i class="fas fa-store mr-1 text-xs"></i>{{ request('seller') }}
                            </span>
                        @endif
                        @if(request('provinsi'))
                            <span class="inline-flex items-center px-3 py-1 bg-kampus-primary/10 text-kampus-dark text-sm rounded-full">
                                <i class="fas fa-map-marker-alt mr-1 text-xs"></i>{{ request('provinsi') }}
                            </span>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Products Grid -->
            @if($products->count() > 0)
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-4 gap-4 md:gap-6">
                    @foreach($products as $product)
                        @php
                            $avgRating = $product->reviews->avg('rating') ?? 0;
                            $reviewCount = $product->reviews->count();
                        @endphp
                        <div class="group bg-white rounded-lg shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden">
                            <!-- Product Image -->
                            <div class="relative overflow-hidden bg-gray-100 aspect-square">
                                @if($product->gambar_produk)
                                    <img src="{{ Storage::url($product->gambar_produk) }}" 
                                         alt="{{ $product->nama_produk }}"
                                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <i class="fas fa-image text-gray-400 text-4xl"></i>
                                    </div>
                                @endif
                                
                                @if($product->stok < 10)
                                    <div class="absolute top-2 right-2 px-2 py-1 bg-red-500 text-white text-xs font-bold rounded-full">
                                        <i class="fas fa-fire mr-1"></i>Terbatas
                                    </div>
                                @endif

                                <!-- Quick View -->
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <a href="{{ route('products.show', $product->slug) }}" 
                                       class="px-4 py-2 bg-white text-kampus-dark font-semibold rounded-lg hover:bg-kampus-primary hover:text-white transition">
                                        <i class="fas fa-eye mr-2"></i>Lihat
                                    </a>
                                </div>
                            </div>

                            <!-- Product Info -->
                            <div class="p-4">
                                <!-- Category Badge -->
                                <span class="inline-block px-2 py-1 bg-green-50 text-kampus-dark text-xs font-medium rounded mb-2">
                                    {{ $product->category->nama_kategori }}
                                </span>

                                <!-- Product Name -->
                                <h3 class="font-semibold text-gray-800 mb-2 line-clamp-2 group-hover:text-kampus-primary transition min-h-[2.5rem]">
                                    {{ $product->nama_produk }}
                                </h3>

                                <!-- Rating -->
                                <div class="flex items-center mb-2">
                                    <div class="flex text-yellow-400 text-sm">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= floor($avgRating))
                                                <i class="fas fa-star"></i>
                                            @elseif($i == ceil($avgRating) && $avgRating - floor($avgRating) >= 0.5)
                                                <i class="fas fa-star-half-alt"></i>
                                            @else
                                                <i class="far fa-star"></i>
                                            @endif
                                        @endfor
                                    </div>
                                    <span class="ml-2 text-xs text-gray-500">({{ $reviewCount }})</span>
                                </div>

                                <!-- Price -->
                                <div class="text-xl font-bold text-kampus-dark mb-3">
                                    Rp {{ number_format($product->harga, 0, ',', '.') }}
                                </div>

                                <!-- Seller & Location -->
                                <div class="text-xs text-gray-600 mb-3 pb-3 border-b">
                                    <div class="flex items-center mb-1">
                                        <i class="fas fa-store mr-2 text-kampus-primary"></i>
                                        <span class="truncate">{{ $product->seller->nama_toko }}</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span><i class="fas fa-map-marker-alt mr-1"></i>{{ $product->seller->kota_kabupaten }}</span>
                                        <span><i class="fas fa-box mr-1"></i>{{ $product->stok }}</span>
                                    </div>
                                </div>

                                <!-- Action Button -->
                                <a href="{{ route('products.show', $product->slug) }}" 
                                   class="block w-full py-2.5 bg-kampus-primary text-white text-center font-semibold rounded-lg hover:bg-kampus-dark transition text-sm">
                                    <i class="fas fa-shopping-cart mr-2"></i>Lihat Produk
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $products->appends(request()->query())->links() }}
                </div>
            @else
                <!-- Empty State -->
                <div class="bg-white rounded-lg shadow-md border border-gray-200 p-12 text-center">
                    <div class="w-24 h-24 mx-auto mb-6 bg-gray-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-search text-gray-400 text-4xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-2">Produk Tidak Ditemukan</h3>
                    <p class="text-gray-600 mb-6">Maaf, produk yang Anda cari tidak tersedia. Coba ubah filter atau kata kunci pencarian.</p>
                    <a href="{{ route('products.index') }}" class="inline-block px-6 py-3 bg-kampus-primary text-white font-semibold rounded-lg hover:bg-kampus-dark transition">
                        <i class="fas fa-redo mr-2"></i>Reset Filter
                    </a>
                </div>
            @endif
        </main>
    </div>
</div>
@endsection
