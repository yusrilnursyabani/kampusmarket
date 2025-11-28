<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model ProductReview
 * Representasi review/komentar dari pengunjung (tanpa login)
 */
class ProductReview extends Model
{
    protected $fillable = [
        'product_id',
        'nama_pengunjung',
        'email_pengunjung',
        'no_hp_pengunjung',
        'provinsi_pengunjung',
        'kota_pengunjung',
        'isi_komentar',
        'rating',
        'is_approved',
        'is_spam',
        'ip_address',
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_approved' => 'boolean',
        'is_spam' => 'boolean',
    ];

    /**
     * Relasi: ProductReview belongsTo Product
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Scope: Hanya review yang approved
     */
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true)->where('is_spam', false);
    }

    /**
     * Scope: Review berdasarkan rating tertentu
     */
    public function scopeByRating($query, $rating)
    {
        return $query->where('rating', $rating);
    }

    /**
     * Accessor: Get rating stars (untuk display)
     */
    public function getRatingStarsAttribute()
    {
        return str_repeat('⭐', $this->rating);
    }

    /**
     * Accessor: Format tanggal review yang user-friendly
     */
    public function getFormattedDateAttribute()
    {
        return $this->created_at->diffForHumans();
    }
}
