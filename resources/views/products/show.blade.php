@extends('layouts.app')

@section('title', $product->nama_produk . ' - KampusMarket')

@section('content')
<div class="container mx-auto px-4">
    <!-- Back Button -->
    <div class="mb-4">
        <a href="{{ route('products.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-700">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Katalog
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Product Image & Gallery -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                @if($product->gambar_utama)
                    <img src="{{ Storage::url($product->gambar_utama) }}" alt="{{ $product->nama_produk }}" 
                        class="w-full h-96 object-cover">
                @else
                    <div class="w-full h-96 bg-gray-200 flex items-center justify-center">
                        <i class="fas fa-image text-gray-400 text-6xl"></i>
                    </div>
                @endif
            </div>
        </div>

        <!-- Product Info -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h1 class="text-2xl font-bold text-gray-800 mb-4">{{ $product->nama_produk }}</h1>

                <!-- Price -->
                <div class="text-3xl font-bold text-blue-600 mb-4">
                    {{ $product->formatted_price }}
                </div>

                <!-- Rating -->
                <div class="flex items-center mb-4">
                    <div class="flex items-center text-yellow-500 text-xl">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= floor($averageRating))
                                <i class="fas fa-star"></i>
                            @elseif($i - $averageRating < 1)
                                <i class="fas fa-star-half-alt"></i>
                            @else
                                <i class="far fa-star"></i>
                            @endif
                        @endfor
                    </div>
                    <span class="ml-2 text-gray-700 font-semibold">{{ number_format($averageRating, 1) }}</span>
                    <span class="ml-2 text-gray-500">({{ $totalReviews }} review)</span>
                </div>

                <!-- Stock -->
                <div class="mb-4">
                    <span class="text-gray-700">Stok:</span>
                    @if($product->stok > 0)
                        <span class="ml-2 px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-semibold">
                            {{ $product->stok }} tersedia
                        </span>
                    @else
                        <span class="ml-2 px-3 py-1 bg-red-100 text-red-700 rounded-full text-sm font-semibold">
                            Habis
                        </span>
                    @endif
                </div>

                <!-- Category -->
                <div class="mb-4">
                    <span class="text-gray-700">Kategori:</span>
                    <span class="ml-2 px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm">
                        {{ $product->category->nama_kategori }}
                    </span>
                </div>

                <hr class="my-4">

                <!-- Seller Info -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">Informasi Toko</h3>
                    <div class="space-y-2">
                        <div class="flex items-center">
                            <i class="fas fa-store text-gray-500 w-6"></i>
                            <span class="text-gray-700">{{ $product->seller->nama_toko }}</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-map-marker-alt text-gray-500 w-6"></i>
                            <span class="text-gray-700">{{ $product->seller->kota_kabupaten }}, {{ $product->seller->provinsi }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Description -->
    <div class="mt-8 bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Deskripsi Produk</h2>
        <div class="prose max-w-none text-gray-700">
            {!! $product->deskripsi !!}
        </div>
    </div>

    <!-- Reviews Section -->
    <div class="mt-8 bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Review Pelanggan</h2>

        <!-- Rating Summary -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
            <!-- Overall Rating -->
            <div class="text-center">
                <div class="text-5xl font-bold text-gray-800 mb-2">{{ number_format($averageRating, 1) }}</div>
                <div class="flex items-center justify-center text-yellow-500 text-2xl mb-2">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= floor($averageRating))
                            <i class="fas fa-star"></i>
                        @elseif($i - $averageRating < 1)
                            <i class="fas fa-star-half-alt"></i>
                        @else
                            <i class="far fa-star"></i>
                        @endif
                    @endfor
                </div>
                <div class="text-gray-600">Dari {{ $totalReviews }} review</div>
            </div>

            <!-- Rating Distribution -->
            <div>
                @foreach($ratingDistribution as $rating => $count)
                    <div class="flex items-center mb-2">
                        <span class="text-sm text-gray-600 w-12">{{ $rating }} ⭐</span>
                        <div class="flex-1 mx-2">
                            <div class="bg-gray-200 rounded-full h-2">
                                @php
                                    $percentage = $totalReviews > 0 ? ($count / $totalReviews) * 100 : 0;
                                @endphp
                                <div class="bg-yellow-500 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                        <span class="text-sm text-gray-600 w-12 text-right">{{ $count }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Review List -->
        @if($product->reviews->count() > 0)
            <div class="space-y-6 mb-8">
                @foreach($product->reviews as $review)
                    <div class="border-b border-gray-200 pb-6">
                        <div class="flex items-center mb-2">
                            <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-semibold">
                                {{ substr($review->nama_pengunjung, 0, 1) }}
                            </div>
                            <div class="ml-3">
                                <div class="font-semibold text-gray-800">{{ $review->nama_pengunjung }}</div>
                                <div class="text-sm text-gray-500">{{ $review->created_at->format('d M Y') }}</div>
                            </div>
                        </div>
                        <div class="flex items-center text-yellow-500 mb-2">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $review->rating)
                                    <i class="fas fa-star"></i>
                                @else
                                    <i class="far fa-star"></i>
                                @endif
                            @endfor
                        </div>
                        <p class="text-gray-700">{{ $review->isi_komentar }}</p>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-comments text-4xl mb-2"></i>
                <p>Belum ada review untuk produk ini</p>
            </div>
        @endif

        <!-- Add Review Form -->
        <div class="mt-8 border-t border-gray-200 pt-8">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Tulis Review Anda</h3>
            
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('products.review.store', $product->slug) }}" class="space-y-4">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Anda *</label>
                        <input type="text" name="nama_pengunjung" value="{{ old('nama_pengunjung') }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                        <input type="email" name="email_pengunjung" value="{{ old('email_pengunjung') }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">No. HP *</label>
                        <input type="text" name="no_hp_pengunjung" value="{{ old('no_hp_pengunjung') }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Rating *</label>
                    <div class="flex space-x-2">
                        @for($i = 1; $i <= 5; $i++)
                            <label class="cursor-pointer">
                                <input type="radio" name="rating" value="{{ $i }}" {{ old('rating') == $i ? 'checked' : '' }} 
                                    class="hidden peer" required>
                                <div class="peer-checked:text-yellow-500 text-gray-300 text-3xl hover:text-yellow-400 transition">
                                    <i class="fas fa-star"></i>
                                </div>
                            </label>
                        @endfor
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Komentar Anda *</label>
                    <textarea name="isi_komentar" rows="4" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Ceritakan pengalaman Anda dengan produk ini...">{{ old('isi_komentar') }}</textarea>
                </div>

                <button type="submit" class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-paper-plane"></i> Kirim Review
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
