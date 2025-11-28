<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Seller;
use App\Models\ProductReview;
use App\Mail\ReviewThankYouMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    /**
     * Halaman katalog produk dengan fitur pencarian & filter
     */
    public function index(Request $request)
    {
        $query = Product::with(['seller', 'category'])
            ->where('is_active', true);

        // Filter berdasarkan pencarian nama produk
        if ($request->filled('search')) {
            $query->where('nama_produk', 'like', '%' . $request->search . '%');
        }

        // Filter berdasarkan kategori
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Filter berdasarkan nama toko
        if ($request->filled('seller')) {
            $query->whereHas('seller', function ($q) use ($request) {
                $q->where('nama_toko', 'like', '%' . $request->seller . '%');
            });
        }

        // Filter berdasarkan provinsi toko
        if ($request->filled('provinsi')) {
            $query->whereHas('seller', function ($q) use ($request) {
                $q->where('provinsi', $request->provinsi);
            });
        }

        // Filter berdasarkan kota/kabupaten toko
        if ($request->filled('kota')) {
            $query->whereHas('seller', function ($q) use ($request) {
                $q->where('kota_kabupaten', 'like', '%' . $request->kota . '%');
            });
        }

        // Sorting
        $sortBy = $request->get('sort', 'latest');
        switch ($sortBy) {
            case 'price_low':
                $query->orderBy('harga', 'asc');
                break;
            case 'price_high':
                $query->orderBy('harga', 'desc');
                break;
            case 'popular':
                $query->orderBy('views', 'desc');
                break;
            case 'latest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $products = $query->paginate(12);
        $categories = Category::where('is_active', true)->get();
        $provinces = Seller::select('provinsi')->distinct()->pluck('provinsi');
        $sellerNames = Seller::select('nama_toko')
            ->where('status_verifikasi', 'diterima')
            ->where('is_active', true)
            ->orderBy('nama_toko')
            ->pluck('nama_toko');

        return view('products.index', compact('products', 'categories', 'provinces', 'sellerNames'));
    }

    /**
     * Halaman detail produk dengan review dan form rating
     */
    public function show($slug)
    {
        $product = Product::with(['seller', 'category', 'reviews' => function ($query) {
            $query->where('is_approved', true)
                ->where('is_spam', false)
                ->orderBy('created_at', 'desc');
        }])->where('slug', $slug)->firstOrFail();

        // Increment views
        $product->incrementViews();

        // Hitung rating rata-rata
        $averageRating = $product->average_rating;
        $totalReviews = $product->total_reviews;

        // Distribusi rating (1-5)
        $ratingDistribution = [];
        for ($i = 5; $i >= 1; $i--) {
            $ratingDistribution[$i] = $product->reviews()
                ->where('is_approved', true)
                ->where('rating', $i)
                ->count();
        }

        $reviewProvinces = Seller::select('provinsi')
            ->whereNotNull('provinsi')
            ->where('provinsi', '!=', '')
            ->distinct()
            ->orderBy('provinsi')
            ->pluck('provinsi');

        $reviewCities = Seller::select('kota_kabupaten')
            ->whereNotNull('kota_kabupaten')
            ->where('kota_kabupaten', '!=', '')
            ->distinct()
            ->orderBy('kota_kabupaten')
            ->pluck('kota_kabupaten');

        return view('products.show', compact(
            'product',
            'averageRating',
            'totalReviews',
            'ratingDistribution',
            'reviewProvinces',
            'reviewCities'
        ));
    }

    /**
     * Submit review produk (dari pengunjung tanpa login)
     */
    public function storeReview(Request $request, $slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();

        $validated = $request->validate([
            'nama_pengunjung' => 'required|string|max:255',
            'email_pengunjung' => 'required|email|max:255',
            'no_hp_pengunjung' => 'required|string|max:20',
            'provinsi_pengunjung' => 'required|string|max:100',
            'kota_pengunjung' => 'required|string|max:100',
            'rating' => 'required|integer|min:1|max:5',
            'isi_komentar' => 'required|string|min:10',
        ], [
            'nama_pengunjung.required' => 'Nama wajib diisi',
            'email_pengunjung.required' => 'Email wajib diisi',
            'email_pengunjung.email' => 'Format email tidak valid',
            'no_hp_pengunjung.required' => 'Nomor HP wajib diisi',
            'provinsi_pengunjung.required' => 'Provinsi asal wajib diisi',
            'kota_pengunjung.required' => 'Kota/Kabupaten asal wajib diisi',
            'rating.required' => 'Rating wajib dipilih',
            'rating.min' => 'Rating minimal 1',
            'rating.max' => 'Rating maksimal 5',
            'isi_komentar.required' => 'Komentar wajib diisi',
            'isi_komentar.min' => 'Komentar minimal 10 karakter',
        ]);

        $review = ProductReview::create([
            'product_id' => $product->id,
            'nama_pengunjung' => $validated['nama_pengunjung'],
            'email_pengunjung' => $validated['email_pengunjung'],
            'no_hp_pengunjung' => $validated['no_hp_pengunjung'],
            'provinsi_pengunjung' => $validated['provinsi_pengunjung'],
            'kota_pengunjung' => $validated['kota_pengunjung'],
            'rating' => $validated['rating'],
            'isi_komentar' => $validated['isi_komentar'],
            'is_approved' => true, // Auto approve (atau bisa di-moderasi dulu)
            'is_spam' => false,
            'ip_address' => $request->ip(),
        ]);

        // Kirim email ucapan terima kasih
        try {
            Mail::to($validated['email_pengunjung'])->send(new ReviewThankYouMail($review));
        } catch (\Exception $e) {
            // Log error tapi tetap lanjut (jangan sampai gagal karena email)
            Log::error('Failed to send review thank you email: ' . $e->getMessage());
        }

        return redirect()->route('products.show', $slug)
            ->with('success', 'Terima kasih atas review Anda! Email konfirmasi telah dikirim.');
    }
}

