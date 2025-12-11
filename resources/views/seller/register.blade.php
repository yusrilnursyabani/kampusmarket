@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-amber-50 to-orange-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-2">
                <i class="fas fa-store mr-2 text-amber-600"></i>
                Daftar Menjadi Seller
            </h1>
            <p class="text-gray-600">Bergabunglah dengan KampusMarket dan mulai berjualan produk Anda</p>
        </div>

        <!-- Success Message -->
        @if(session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg">
            <div class="flex">
                <i class="fas fa-check-circle text-green-500 text-xl mr-3"></i>
                <div>
                    <h3 class="text-green-800 font-semibold">Registrasi Berhasil!</h3>
                    <p class="text-green-700 text-sm mt-1">{{ session('success') }}</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Registration Form -->
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <form action="{{ route('seller.register.submit') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Informasi Toko -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-shop text-amber-600 mr-2"></i>
                        Informasi Toko
                    </h2>

                    <div class="space-y-4">
                        <!-- Nama Toko -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Nama Toko <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama_toko" value="{{ old('nama_toko') }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                                   placeholder="Contoh: Toko Elektronik Jaya" required>
                            @error('nama_toko')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Singkatan Toko -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Singkatan Toko (Username) <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="singkatan_toko" value="{{ old('singkatan_toko') }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                                   placeholder="Contoh: elektronikjaya (huruf kecil, tanpa spasi)" required>
                            <p class="text-gray-500 text-xs mt-1">Akan digunakan sebagai URL toko Anda</p>
                            @error('singkatan_toko')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Deskripsi Toko -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Deskripsi Toko (Opsional)
                            </label>
                            <textarea name="deskripsi_toko" rows="3" 
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                                      placeholder="Ceritakan tentang toko Anda...">{{ old('deskripsi_toko') }}</textarea>
                            @error('deskripsi_toko')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Informasi PIC (Penanggung Jawab) -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-user-tie text-amber-600 mr-2"></i>
                        Informasi Penanggung Jawab
                    </h2>

                    <div class="space-y-4">
                        <!-- Nama PIC -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama_pic" value="{{ old('nama_pic') }}" maxlength="15"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                                   placeholder="Maks. 15 karakter huruf" required pattern="[A-Za-z\s]+">
                            @error('nama_pic')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email PIC -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email_pic" value="{{ old('email_pic') }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                                   placeholder="email@example.com" required>
                            <p class="text-gray-500 text-xs mt-1">Email ini akan digunakan untuk login</p>
                            @error('email_pic')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- No HP PIC -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                No HP / WhatsApp <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="no_hp_pic" value="{{ old('no_hp_pic') }}" maxlength="12"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                                   placeholder="11-12 digit angka" required pattern="[0-9]+">
                            @error('no_hp_pic')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nomor KTP -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Nomor KTP Seller <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nomor_ktp" value="{{ old('nomor_ktp') }}" maxlength="16"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                                   placeholder="16 digit angka" required pattern="[0-9]+">
                            @error('nomor_ktp')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Password <span class="text-red-500">*</span>
                                </label>
                                <input type="password" name="password" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                                       placeholder="Min. 8 karakter" required>
                                @error('password')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Konfirmasi Password <span class="text-red-500">*</span>
                                </label>
                                <input type="password" name="password_confirmation" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                                       placeholder="Ulangi password" required>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Lokasi Toko -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-map-marker-alt text-amber-600 mr-2"></i>
                        Lokasi Toko
                    </h2>

                    <div class="space-y-4">
                        <!-- Provinsi & Kota -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Provinsi <span class="text-red-500">*</span>
                                </label>
                                <select name="provinsi" 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent" required>
                                    <option value="">Pilih Provinsi</option>
                                    @foreach($provinces as $province)
                                        <option value="{{ $province }}" {{ old('provinsi') == $province ? 'selected' : '' }}>
                                            {{ $province }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('provinsi')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Kota/Kabupaten <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="kota_kabupaten" value="{{ old('kota_kabupaten') }}" maxlength="50"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                                       placeholder="Contoh: Jakarta Selatan" required pattern="[A-Za-z\s]+">
                                @error('kota_kabupaten')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Kelurahan & Kecamatan -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Kelurahan <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="kelurahan" value="{{ old('kelurahan') }}" maxlength="50"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                                       placeholder="Nama kelurahan" required pattern="[A-Za-z\s]+">
                                @error('kelurahan')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Kecamatan <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="kecamatan" value="{{ old('kecamatan') }}" maxlength="50"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                                       placeholder="Nama kecamatan" required pattern="[A-Za-z\s]+">
                                @error('kecamatan')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Alamat Lengkap -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Alamat (Nama Jalan) <span class="text-red-500">*</span>
                            </label>
                            <textarea name="alamat_lengkap" rows="2" maxlength="100"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                                      placeholder="Nama jalan & nomor rumah" required>{{ old('alamat_lengkap') }}</textarea>
                            @error('alamat_lengkap')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- RT / RW -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    RT <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="rt" value="{{ old('rt') }}" maxlength="3"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                                       placeholder="Contoh: 007" required pattern="[0-9]+">
                                @error('rt')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    RW <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="rw" value="{{ old('rw') }}" maxlength="3"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                                       placeholder="Contoh: 002" required pattern="[0-9]+">
                                @error('rw')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Dokumen Identitas -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-id-card text-amber-600 mr-2"></i>
                        Dokumen Penanggung Jawab
                    </h2>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Foto Seller (JPG/JPEG) <span class="text-red-500">*</span>
                            </label>
                            <input type="file" name="foto_seller" accept="image/jpeg"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                                   required>
                            <p class="text-gray-500 text-xs mt-1">Ukuran ideal 50 mm, maksimal 9cm x 5,5cm.</p>
                            @error('foto_seller')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Upload KTP Seller (JPG/JPEG) <span class="text-red-500">*</span>
                            </label>
                            <input type="file" name="foto_ktp" accept="image/jpeg"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                                   required>
                            @error('foto_ktp')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex items-center justify-between pt-6 border-t">
                    <a href="{{ url('/') }}" class="text-gray-600 hover:text-gray-900 font-medium">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali ke Beranda
                    </a>
                    <button type="submit" 
                            class="bg-gradient-to-r from-amber-500 to-orange-600 text-white px-8 py-3 rounded-lg font-semibold hover:from-amber-600 hover:to-orange-700 transform hover:scale-105 transition-all shadow-lg">
                        <i class="fas fa-paper-plane mr-2"></i>
                        Daftar Sekarang
                    </button>
                </div>
            </form>
        </div>

        <!-- Info Box -->
        <div class="mt-8 bg-blue-50 border border-blue-200 rounded-xl p-6">
            <h3 class="font-bold text-blue-900 mb-2 flex items-center">
                <i class="fas fa-info-circle mr-2"></i>
                Informasi Penting
            </h3>
            <ul class="text-blue-800 text-sm space-y-1">
                <li>• Setelah mendaftar, akun Anda akan diverifikasi oleh Admin</li>
                <li>• Anda akan menerima email konfirmasi setelah akun diverifikasi</li>
                <li>• Pastikan semua data yang diisi sudah benar dan lengkap</li>
                <li>• Proses verifikasi biasanya memakan waktu 1-2 hari kerja</li>
            </ul>
        </div>
    </div>
</div>
@endsection
