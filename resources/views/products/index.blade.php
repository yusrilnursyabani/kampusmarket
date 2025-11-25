@extends('layouts.app')

@section('title', 'Katalog Produk - KampusMarket')

@section('content')
<div class="container mx-auto px-4">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Katalog Produk</h1>
        <p class="text-gray-600">Temukan berbagai produk dari toko-toko kampus di seluruh Indonesia</p>
    </div>

    <!-- Filter & Search Form -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <form method="GET" action="{{ route('products.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Search Produk -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Cari Produk</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                    placeholder="Nama produk..." 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <!-- Kategori -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                <select name="category" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->nama_kategori }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Provinsi -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Provinsi Toko</label>
                <select name="provinsi" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua Provinsi</option>
                    @foreach($provinces as $province)
                        <option value="{{ $province }}" {{ request('provinsi') == $province ? 'selected' : '' }}>
                            {{ $province }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Sorting -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Urutkan</label>
                <select name="sort" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                    <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Terpopuler</option>
                    <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Harga Terendah</option>
                    <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Harga Tertinggi</option>
                </select>
            </div>

            <!-- Buttons -->
            <div class="col-span-full flex gap-2">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-search"></i> Cari
                </button>
                <a href="{{ route('products.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                    <i class="fas fa-redo"></i> Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Products Grid -->
    @if($products->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
            @foreach($products as $product)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition duration-300">
                    <!-- Product Image -->
                    <a href="{{ route('products.show', $product->slug) }}">
                        @if($product->gambar_utama)
                            <img src="{{ Storage::url($product->gambar_utama) }}" alt="{{ $product->nama_produk }}" 
                                class="w-full h-48 object-cover">
                        @else
                            <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                <i class="fas fa-image text-gray-400 text-4xl"></i>
                            </div>
                        @endif
                    </a>

                    <!-- Product Info -->
                    <div class="p-4">
                        <a href="{{ route('products.show', $product->slug) }}" class="block">
                            <h3 class="text-lg font-semibold text-gray-800 mb-2 hover:text-blue-600 transition line-clamp-2">
                                {{ $product->nama_produk }}
                            </h3>
                        </a>

                        <!-- Price -->
                        <div class="text-2xl font-bold text-blue-600 mb-2">
                            {{ $product->formatted_price }}
                        </div>

                        <!-- Seller & Location -->
                        <div class="text-sm text-gray-600 mb-2">
                            <i class="fas fa-store"></i> {{ $product->seller->nama_toko }}
                        </div>
                        <div class="text-sm text-gray-500 mb-3">
                            <i class="fas fa-map-marker-alt"></i> {{ $product->seller->kota_kabupaten }}
                        </div>

                        <!-- Rating & Stock -->
                        <div class="flex items-center justify-between text-sm">
                            <div class="flex items-center text-yellow-500">
                                <i class="fas fa-star"></i>
                                <span class="ml-1 text-gray-700">{{ number_format($product->average_rating, 1) }}</span>
                                <span class="ml-1 text-gray-500">({{ $product->total_reviews }})</span>
                            </div>
                            <div class="text-gray-600">
                                <i class="fas fa-box"></i> Stok: {{ $product->stok }}
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="flex justify-center">
            {{ $products->appends(request()->query())->links() }}
        </div>
    @else
        <div class="bg-white rounded-lg shadow-md p-12 text-center">
            <i class="fas fa-search text-gray-300 text-6xl mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">Produk Tidak Ditemukan</h3>
            <p class="text-gray-500 mb-4">Coba ubah filter atau kata kunci pencarian Anda</p>
            <a href="{{ route('products.index') }}" class="inline-block px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                Lihat Semua Produk
            </a>
        </div>
    @endif
</div>
@endsection
