<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * Model Product
 * Representasi produk yang dijual di marketplace
 */
class Product extends Model
{
    protected $fillable = [
        'seller_id',
        'category_id',
        'nama_produk',
        'slug',
        'deskripsi',
        'harga',
        'stok',
        'berat',
        'gambar_utama',
        'galeri_gambar',
        'is_active',
        'total_terjual',
        'views',
    ];

    protected $casts = [
        'harga' => 'decimal:2',
        'stok' => 'integer',
        'berat' => 'integer',
        'galeri_gambar' => 'array', // Cast JSON ke array
        'is_active' => 'boolean',
        'total_terjual' => 'integer',
        'views' => 'integer',
    ];

    /**
     * Boot model - auto generate slug dari nama produk
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->nama_produk) . '-' . Str::random(6);
            }
        });
    }

    /**
     * Relasi: Product belongsTo Seller
     */
    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class);
    }

    /**
     * Relasi: Product belongsTo Category
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relasi: Product hasMany ProductReview
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(ProductReview::class);
    }

    /**
     * Accessor: Hitung rating rata-rata produk
     */
    public function getAverageRatingAttribute()
    {
        return $this->reviews()->where('is_approved', true)->avg('rating') ?? 0;
    }

    /**
     * Accessor: Total review yang approved
     */
    public function getTotalReviewsAttribute()
    {
        return $this->reviews()->where('is_approved', true)->count();
    }

    /**
     * Scope: Hanya produk aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Produk dengan stok tersedia
     */
    public function scopeInStock($query)
    {
        return $query->where('stok', '>', 0);
    }

    /**
     * Scope: Produk dengan stok rendah (< threshold)
     */
    public function scopeLowStock($query, $threshold = 2)
    {
        return $query->where('stok', '<', $threshold)->where('stok', '>=', 0);
    }

    /**
     * Method: Format harga ke Rupiah
     */
    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }

    /**
     * Method: Increment views counter
     */
    public function incrementViews()
    {
        $this->increment('views');
    }
}
