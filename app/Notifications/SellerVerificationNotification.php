<?php

namespace App\Notifications;

use App\Models\Seller;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SellerVerificationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public string $status;
    public ?string $alasanPenolakan;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $status, ?string $alasanPenolakan = null)
    {
        $this->status = $status;
        $this->alasanPenolakan = $alasanPenolakan;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        /** @var Seller $notifiable */
        
        if ($this->status === 'diterima') {
            return $this->buildApprovedEmail($notifiable);
        } else if ($this->status === 'ditolak') {
            return $this->buildRejectedEmail($notifiable);
        }
        
        return $this->buildPendingEmail($notifiable);
    }

    /**
     * Email untuk seller yang diterima (approved)
     */
    private function buildApprovedEmail(Seller $seller): MailMessage
    {
        return (new MailMessage)
            ->subject('Selamat! Akun Seller Anda Telah Diverifikasi - KampusMarket')
            ->greeting('Halo, ' . $seller->nama_pic . '!')
            ->line('Selamat! Pendaftaran toko **' . $seller->nama_toko . '** telah diverifikasi dan disetujui oleh tim KampusMarket.')
            ->line('**Informasi Akun Anda:**')
            ->line('• Email: ' . $seller->email_pic)
            ->line('• Nama Toko: ' . $seller->nama_toko)
            ->line('• Lokasi: ' . $seller->kota_kabupaten . ', ' . $seller->provinsi)
            ->line('')
            ->line('Anda sekarang dapat login ke Seller Panel untuk:')
            ->line('✓ Mengelola produk')
            ->line('✓ Melihat statistik penjualan')
            ->line('✓ Mengelola review pelanggan')
            ->line('✓ Update profil toko')
            ->action('Login ke Seller Panel', url('/seller/login'))
            ->line('**Catatan Penting:**')
            ->line('Gunakan email (' . $seller->email_pic . ') dan password yang Anda daftarkan untuk login.')
            ->line('Jika Anda lupa password, silakan gunakan fitur "Lupa Password" di halaman login.')
            ->line('')
            ->line('Terima kasih telah bergabung dengan KampusMarket!')
            ->salutation('Salam hangat, Tim KampusMarket');
    }

    /**
     * Email untuk seller yang ditolak (rejected)
     */
    private function buildRejectedEmail(Seller $seller): MailMessage
    {
        return (new MailMessage)
            ->subject('Informasi Verifikasi Akun Seller - KampusMarket')
            ->greeting('Halo, ' . $seller->nama_pic)
            ->line('Terima kasih telah mendaftar sebagai seller di KampusMarket dengan nama toko **' . $seller->nama_toko . '**.')
            ->line('Setelah kami review, saat ini pendaftaran Anda **belum dapat kami setujui** dengan alasan sebagai berikut:')
            ->line('')
            ->line('**Alasan Penolakan:**')
            ->line($this->alasanPenolakan ?? 'Tidak memenuhi persyaratan administrasi.')
            ->line('')
            ->line('**Apa yang bisa Anda lakukan?**')
            ->line('• Perbaiki dokumen atau informasi sesuai alasan di atas')
            ->line('• Daftar ulang dengan data yang benar dan lengkap')
            ->line('• Hubungi kami jika ada pertanyaan')
            ->action('Daftar Ulang Sekarang', url('/seller/register'))
            ->line('Jika Anda membutuhkan bantuan atau penjelasan lebih lanjut, silakan hubungi tim support kami.')
            ->line('')
            ->line('Kami berharap dapat bekerja sama dengan Anda di masa mendatang.')
            ->salutation('Hormat kami, Tim KampusMarket');
    }

    /**
     * Email untuk seller yang masih menunggu
     */
    private function buildPendingEmail(Seller $seller): MailMessage
    {
        return (new MailMessage)
            ->subject('Pendaftaran Seller Berhasil - Menunggu Verifikasi')
            ->greeting('Halo, ' . $seller->nama_pic . '!')
            ->line('Terima kasih telah mendaftar sebagai seller di KampusMarket!')
            ->line('**Data Pendaftaran Anda:**')
            ->line('• Nama Toko: ' . $seller->nama_toko)
            ->line('• Email: ' . $seller->email_pic)
            ->line('• Lokasi: ' . $seller->kota_kabupaten . ', ' . $seller->provinsi)
            ->line('')
            ->line('Pendaftaran Anda saat ini sedang dalam proses verifikasi oleh tim kami.')
            ->line('Kami akan mengirimkan email notifikasi setelah proses verifikasi selesai.')
            ->line('')
            ->line('**Perkiraan waktu verifikasi:** 1-3 hari kerja')
            ->line('')
            ->line('Terima kasih atas kesabaran Anda!')
            ->salutation('Salam, Tim KampusMarket');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'status' => $this->status,
            'alasan_penolakan' => $this->alasanPenolakan,
        ];
    }
}
