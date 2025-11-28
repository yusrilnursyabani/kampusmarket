<x-filament::page>
    <div class="space-y-6">
        <div class="rounded-xl bg-white/60 p-6 shadow-sm ring-1 ring-gray-100">
            <h2 class="text-lg font-semibold text-gray-900">Unduh Laporan PDF</h2>
            <p class="mt-1 text-sm text-gray-600">
                Pilih salah satu laporan di bawah untuk mengunduh PDF terbaru sesuai kebutuhan analitik kampus market.
            </p>
        </div>

        <div class="grid gap-6 md:grid-cols-2">
            <div class="rounded-xl border border-gray-100 bg-white/80 p-6 shadow-sm">
                <div class="flex items-center gap-3">
                    <x-filament::icon icon="heroicon-o-users" class="h-6 w-6 text-primary-500" />
                    <div>
                        <h3 class="text-base font-semibold text-gray-900">Akun Penjual</h3>
                        <p class="text-sm text-gray-600">Daftar lengkap penjual beserta status verifikasi & keaktifan.</p>
                    </div>
                </div>
                <x-filament::button tag="a" href="{{ route('admin.reports.sellers') }}" target="_blank" class="mt-4" icon="heroicon-o-arrow-down-tray">
                    Unduh Laporan
                </x-filament::button>
            </div>

            <div class="rounded-xl border border-gray-100 bg-white/80 p-6 shadow-sm">
                <div class="flex items-center gap-3">
                    <x-filament::icon icon="heroicon-o-map" class="h-6 w-6 text-primary-500" />
                    <div>
                        <h3 class="text-base font-semibold text-gray-900">Toko per Provinsi</h3>
                        <p class="text-sm text-gray-600">Ringkasan persebaran toko aktif & tidak aktif tiap provinsi.</p>
                    </div>
                </div>
                <x-filament::button tag="a" href="{{ route('admin.reports.stores') }}" target="_blank" class="mt-4" color="warning" icon="heroicon-o-arrow-down-tray">
                    Unduh Laporan
                </x-filament::button>
            </div>

            <div class="rounded-xl border border-gray-100 bg-white/80 p-6 shadow-sm md:col-span-2">
                <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                    <div class="flex items-center gap-3">
                        <x-filament::icon icon="heroicon-o-chart-bar" class="h-6 w-6 text-primary-500" />
                        <div>
                            <h3 class="text-base font-semibold text-gray-900">Produk & Rating</h3>
                            <p class="text-sm text-gray-600">Performa rating rata-rata dan total review setiap produk aktif.</p>
                        </div>
                    </div>
                    <x-filament::button tag="a" href="{{ route('admin.reports.products') }}" target="_blank" color="success" icon="heroicon-o-arrow-down-tray">
                        Unduh Laporan
                    </x-filament::button>
                </div>
            </div>
        </div>
    </div>
</x-filament::page>
