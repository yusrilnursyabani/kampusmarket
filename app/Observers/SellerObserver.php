<?php

namespace App\Observers;

use App\Models\Seller;
use App\Notifications\SellerVerificationNotification;
use Illuminate\Support\Facades\Log;

class SellerObserver
{
    /**
     * Handle the Seller "created" event.
     */
    public function created(Seller $seller): void
    {
        // Kirim email notifikasi pendaftaran berhasil ke seller
        try {
            $seller->notify(new SellerVerificationNotification('menunggu'));
            Log::info("Email pendaftaran dikirim ke seller: {$seller->email_pic}");
        } catch (\Exception $e) {
            Log::error("Gagal kirim email pendaftaran ke seller {$seller->email_pic}: " . $e->getMessage());
        }
    }

    /**
     * Handle the Seller "updated" event.
     */
    public function updated(Seller $seller): void
    {
        // Cek apakah status_verifikasi berubah
        if ($seller->isDirty('status_verifikasi')) {
            $newStatus = $seller->status_verifikasi;
            $oldStatus = $seller->getOriginal('status_verifikasi');
            
            Log::info("Status verifikasi seller {$seller->email_pic} berubah dari {$oldStatus} ke {$newStatus}");
            
            // Kirim email notifikasi hasil verifikasi
            try {
                if ($newStatus === 'diterima') {
                    // Email berisi akun/aktivasi akun
                    $seller->notify(new SellerVerificationNotification('diterima'));
                    Log::info("Email approval dikirim ke seller: {$seller->email_pic}");
                    
                } elseif ($newStatus === 'ditolak') {
                    // Email berisi informasi penolakan
                    $alasanPenolakan = $seller->alasan_penolakan ?? 'Tidak memenuhi persyaratan administrasi.';
                    $seller->notify(new SellerVerificationNotification('ditolak', $alasanPenolakan));
                    Log::info("Email rejection dikirim ke seller: {$seller->email_pic}");
                }
            } catch (\Exception $e) {
                Log::error("Gagal kirim email verifikasi ke seller {$seller->email_pic}: " . $e->getMessage());
            }
        }
    }

    /**
     * Handle the Seller "deleted" event.
     */
    public function deleted(Seller $seller): void
    {
        //
    }

    /**
     * Handle the Seller "restored" event.
     */
    public function restored(Seller $seller): void
    {
        //
    }

    /**
     * Handle the Seller "force deleted" event.
     */
    public function forceDeleted(Seller $seller): void
    {
        //
    }
}
