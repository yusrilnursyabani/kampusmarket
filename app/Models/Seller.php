<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Model Seller
 * Representasi penjual/toko yang bisa login dan kelola produk
 * Extends Authenticatable agar bisa digunakan untuk authentication
 */
class Seller extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'nama_toko',
        'singkatan_toko',
        'deskripsi_toko',
        'logo_toko',
        'nama_pic',
        'email_pic',
        'no_hp_pic',
        'password',
        'provinsi',
        'kota_kabupaten',
        'alamat_lengkap',
        'rt',
        'rw',
        'kelurahan',
        'kecamatan',
        'kode_pos',
        'nomor_ktp',
        'foto_seller',
        'foto_ktp',
        'status_verifikasi',
        'alasan_penolakan',
        'is_active',
        'verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'verified_at' => 'datetime',
    ];

    /**
     * Relasi: Seller hasMany Product
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Scope: Hanya seller yang sudah diverifikasi (diterima)
     */
    public function scopeVerified($query)
    {
        return $query->where('status_verifikasi', 'diterima');
    }

    /**
     * Scope: Hanya seller yang aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Seller yang menunggu verifikasi
     */
    public function scopePending($query)
    {
        return $query->where('status_verifikasi', 'menunggu');
    }

    /**
     * Get nama untuk display di Filament
     */
    public function getNameAttribute()
    {
        return $this->nama_pic;
    }

    /**
     * Override: Tentukan kolom yang digunakan untuk username/email saat login
     * Laravel default mencari kolom 'email', kita override ke 'email_pic'
     */
    public function getAuthIdentifierName()
    {
        return 'id'; // Tetap gunakan 'id' untuk session storage
    }

    /**
     * Override: Get auth identifier (ID untuk session)
     * Return primary key (id) bukan email
     */
    public function getAuthIdentifier()
    {
        return $this->getKey(); // Return ID integer
    }

    /**
     * Override: Get email untuk password reset & notifikasi
     */
    public function getEmailForPasswordReset()
    {
        return $this->email_pic;
    }

    /**
     * Accessor: Get lokasi lengkap untuk display
     */
    public function getFullLocationAttribute()
    {
        return "{$this->kota_kabupaten}, {$this->provinsi}";
    }
    
    /**
     * Accessor: Alias email untuk compatibility dengan sistem auth
     */
    public function getEmailAttribute()
    {
        return $this->email_pic;
    }

    /**
     * Untuk Filament: cek apakah seller bisa login (harus verified & active)
     */
    public function canAccessPanel(\Filament\Panel $panel): bool
    {
        if ($panel->getId() === 'seller') {
            return $this->status_verifikasi === 'diterima' && $this->is_active;
        }
        
        return false;
    }
}
