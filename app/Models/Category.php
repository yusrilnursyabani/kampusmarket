<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * Model Category
 * Representasi kategori produk di marketplace
 */
class Category extends Model
{
    protected $fillable = [
        'nama_kategori',
        'slug',
        'deskripsi',
        'icon',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Boot model - auto generate slug dari nama kategori
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->nama_kategori);
            }
        });

        static::updating(function ($category) {
            if ($category->isDirty('nama_kategori') && empty($category->slug)) {
                $category->slug = Str::slug($category->nama_kategori);
            }
        });
    }

    /**
     * Relasi: Category hasMany Product
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Scope: Hanya kategori aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
