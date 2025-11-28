<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Seller;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminReportController extends Controller
{
    public function sellerAccounts()
    {
        $sellers = Seller::orderBy('created_at', 'desc')->get();

        $pdf = Pdf::loadView('reports.admin.seller-accounts', [
            'generatedAt' => now(),
            'sellers' => $sellers,
        ])->setPaper('a4', 'landscape');

        return $pdf->download('laporan-akun-penjual.pdf');
    }

    public function storesByProvince()
    {
        $provinceSummaries = Seller::select('provinsi')
            ->selectRaw('COUNT(*) as total_toko')
            ->selectRaw('SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) as toko_aktif')
            ->selectRaw('SUM(CASE WHEN is_active = 0 THEN 1 ELSE 0 END) as toko_tidak_aktif')
            ->whereNotNull('provinsi')
            ->groupBy('provinsi')
            ->orderBy('provinsi')
            ->get();

        $groupedSellers = Seller::orderBy('provinsi')
            ->orderBy('nama_toko')
            ->get()
            ->groupBy('provinsi');

        $pdf = Pdf::loadView('reports.admin.stores-by-province', [
            'generatedAt' => now(),
            'provinceSummaries' => $provinceSummaries,
            'groupedSellers' => $groupedSellers,
        ])->setPaper('a4', 'portrait');

        return $pdf->download('laporan-toko-per-provinsi.pdf');
    }

    public function productRatings()
    {
        $products = Product::with([
                'seller',
                'category',
                'reviews' => fn ($query) => $query->where('is_approved', true),
            ])
            ->where('is_active', true)
            ->orderBy('nama_produk')
            ->get()
            ->map(function ($product) {
                $average = round($product->reviews->avg('rating') ?? 0, 2);

                return [
                    'nama_produk' => $product->nama_produk,
                    'nama_toko' => optional($product->seller)->nama_toko ?? '-',
                    'kategori' => optional($product->category)->nama_kategori ?? '-',
                    'stok' => $product->stok,
                    'rata_rating' => $average,
                    'total_review' => $product->reviews->count(),
                ];
            });

        $pdf = Pdf::loadView('reports.admin.product-ratings', [
            'generatedAt' => now(),
            'products' => $products,
        ])->setPaper('a4', 'landscape');

        return $pdf->download('laporan-produk-rating.pdf');
    }
}
