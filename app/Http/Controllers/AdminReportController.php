<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Seller;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class AdminReportController extends Controller
{
    /**
     * SRS-09: Laporan daftar akun penjual berdasarkan status (aktif/tidak aktif)
     */
    public function sellerAccounts()
    {
        $sellers = Seller::orderBy('is_active', 'desc')
            ->orderBy('nama_toko', 'asc')
            ->get();

        $pdf = Pdf::loadView('reports.admin.seller-accounts', [
            'generatedAt' => now(),
            'generatedBy' => Auth::user()->name ?? 'Admin',
            'sellers' => $sellers,
        ])->setPaper('a4', 'portrait');

        return $pdf->download('SRS-MartPlace-09_Laporan-Akun-Penjual-' . date('Ymd') . '.pdf');
    }

    /**
     * SRS-10: Laporan daftar toko berdasarkan lokasi propinsi
     */
    public function storesByProvince()
    {
        $sellers = Seller::orderBy('provinsi', 'asc')
            ->orderBy('nama_toko', 'asc')
            ->get()
            ->groupBy('provinsi');

        $pdf = Pdf::loadView('reports.admin.stores-by-province', [
            'generatedAt' => now(),
            'generatedBy' => Auth::user()->name ?? 'Admin',
            'sellers' => $sellers,
        ])->setPaper('a4', 'portrait');

        return $pdf->download('SRS-MartPlace-10_Laporan-Toko-Per-Provinsi-' . date('Ymd') . '.pdf');
    }

    /**
     * SRS-11: Laporan daftar produk berdasarkan rating (menurun)
     */
    public function productRatings()
    {
        $products = Product::with([
                'seller',
                'category',
                'reviews' => fn ($query) => $query->where('is_approved', true),
            ])
            ->where('is_active', true)
            ->get()
            ->map(function ($product) {
                $average = $product->reviews->avg('rating') ?? 0;

                return [
                    'nama_produk' => $product->nama_produk,
                    'kategori' => optional($product->category)->nama_kategori ?? '-',
                    'harga' => $product->harga,
                    'rating' => number_format($average, 2),
                    'nama_toko' => optional($product->seller)->nama_toko ?? '-',
                    'provinsi' => optional($product->seller)->provinsi ?? '-',
                ];
            })
            ->sortByDesc('rating')
            ->values();

        $pdf = Pdf::loadView('reports.admin.product-ratings', [
            'generatedAt' => now(),
            'generatedBy' => Auth::user()->name ?? 'Admin',
            'products' => $products,
        ])->setPaper('a4', 'landscape');

        return $pdf->download('SRS-MartPlace-11_Laporan-Produk-Rating-' . date('Ymd') . '.pdf');
    }
}
