<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellerReportController extends Controller
{
    /**
     * SRS-12: Laporan stock produk diurutkan berdasarkan stock (menurun)
     */
    public function stockReport()
    {
        $seller = Auth::guard('seller')->user();
        
        if (!$seller) {
            abort(403, 'Unauthorized');
        }

        $products = Product::with(['category', 'reviews' => fn($q) => $q->where('is_approved', true)])
            ->where('seller_id', $seller->id)
            ->orderBy('stok', 'desc')
            ->get()
            ->map(function ($product) {
                return [
                    'nama_produk' => $product->nama_produk,
                    'kategori' => optional($product->category)->nama_kategori ?? '-',
                    'harga' => $product->harga,
                    'stok' => $product->stok,
                    'rating' => number_format($product->reviews->avg('rating') ?? 0, 2),
                ];
            });

        $pdf = Pdf::loadView('reports.seller.srs-12-stock', [
            'generatedAt' => now(),
            'seller' => $seller,
            'products' => $products,
        ])->setPaper('a4', 'landscape');

        return $pdf->download('SRS-MartPlace-12_Laporan-Stock-' . date('Ymd') . '.pdf');
    }

    /**
     * SRS-13: Laporan stock produk diurutkan berdasarkan rating (menurun)
     */
    public function ratingReport()
    {
        $seller = Auth::guard('seller')->user();
        
        if (!$seller) {
            abort(403, 'Unauthorized');
        }

        $products = Product::with(['category', 'reviews' => fn($q) => $q->where('is_approved', true)])
            ->where('seller_id', $seller->id)
            ->get()
            ->map(function ($product) {
                return [
                    'nama_produk' => $product->nama_produk,
                    'kategori' => optional($product->category)->nama_kategori ?? '-',
                    'harga' => $product->harga,
                    'stok' => $product->stok,
                    'rating' => $product->reviews->avg('rating') ?? 0,
                    'rating_display' => number_format($product->reviews->avg('rating') ?? 0, 2),
                ];
            })
            ->sortByDesc('rating')
            ->values();

        $pdf = Pdf::loadView('reports.seller.srs-13-rating', [
            'generatedAt' => now(),
            'seller' => $seller,
            'products' => $products,
        ])->setPaper('a4', 'landscape');

        return $pdf->download('SRS-MartPlace-13_Laporan-Rating-' . date('Ymd') . '.pdf');
    }

    /**
     * SRS-14: Laporan stock barang yang harus segera dipesan (stock < 2)
     * Diurutkan berdasarkan kategori dan produk
     */
    public function lowStockReport()
    {
        $seller = Auth::guard('seller')->user();
        
        if (!$seller) {
            abort(403, 'Unauthorized');
        }

        $products = Product::with(['category'])
            ->where('seller_id', $seller->id)
            ->where('stok', '<', 2)
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->orderBy('categories.nama_kategori', 'asc')
            ->orderBy('products.nama_produk', 'asc')
            ->select('products.*')
            ->get()
            ->map(function ($product) {
                return [
                    'nama_produk' => $product->nama_produk,
                    'kategori' => optional($product->category)->nama_kategori ?? '-',
                    'harga' => $product->harga,
                    'stok' => $product->stok,
                ];
            });

        $pdf = Pdf::loadView('reports.seller.srs-14-lowstock', [
            'generatedAt' => now(),
            'seller' => $seller,
            'products' => $products,
        ])->setPaper('a4', 'landscape');

        return $pdf->download('SRS-MartPlace-14_Laporan-Low-Stock-' . date('Ymd') . '.pdf');
    }
}
