<?php

namespace App\Http\Controllers;

use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class SellerRegistrationController extends Controller
{
    /**
     * Tampilkan form registrasi seller
     */
    public function showRegistrationForm()
    {
        $provinces = [
            'Aceh', 'Sumatera Utara', 'Sumatera Barat', 'Riau', 'Jambi',
            'Sumatera Selatan', 'Bengkulu', 'Lampung', 'Kepulauan Bangka Belitung',
            'Kepulauan Riau', 'DKI Jakarta', 'Jawa Barat', 'Jawa Tengah',
            'DI Yogyakarta', 'Jawa Timur', 'Banten', 'Bali', 'Nusa Tenggara Barat',
            'Nusa Tenggara Timur', 'Kalimantan Barat', 'Kalimantan Tengah',
            'Kalimantan Selatan', 'Kalimantan Timur', 'Kalimantan Utara',
            'Sulawesi Utara', 'Sulawesi Tengah', 'Sulawesi Selatan',
            'Sulawesi Tenggara', 'Gorontalo', 'Sulawesi Barat', 'Maluku',
            'Maluku Utara', 'Papua', 'Papua Barat', 'Papua Tengah',
            'Papua Pegunungan', 'Papua Selatan', 'Papua Barat Daya'
        ];

        return view('seller.register', compact('provinces'));
    }

    /**
     * Proses registrasi seller baru
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'nama_toko' => 'required|string|max:255',
            'singkatan_toko' => 'required|string|max:50|unique:sellers,singkatan_toko',
            'deskripsi_toko' => 'nullable|string',
            'nama_pic' => 'required|string|max:255',
            'email_pic' => 'required|email|unique:sellers,email_pic',
            'no_hp_pic' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'provinsi' => 'required|string',
            'kota_kabupaten' => 'required|string|max:255',
            'alamat_lengkap' => 'required|string',
            'kode_pos' => 'nullable|string|max:10',
        ], [
            'nama_toko.required' => 'Nama toko wajib diisi',
            'singkatan_toko.required' => 'Singkatan toko wajib diisi',
            'singkatan_toko.unique' => 'Singkatan toko sudah digunakan',
            'email_pic.required' => 'Email PIC wajib diisi',
            'email_pic.email' => 'Format email tidak valid',
            'email_pic.unique' => 'Email sudah terdaftar',
            'no_hp_pic.required' => 'No HP PIC wajib diisi',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'provinsi.required' => 'Provinsi wajib dipilih',
            'kota_kabupaten.required' => 'Kota/Kabupaten wajib diisi',
            'alamat_lengkap.required' => 'Alamat lengkap wajib diisi',
        ]);

        // Buat seller baru dengan status menunggu
        $seller = Seller::create([
            'nama_toko' => $validated['nama_toko'],
            'singkatan_toko' => Str::slug($validated['singkatan_toko']),
            'deskripsi_toko' => $validated['deskripsi_toko'] ?? null,
            'nama_pic' => $validated['nama_pic'],
            'email_pic' => $validated['email_pic'],
            'no_hp_pic' => $validated['no_hp_pic'],
            'password' => Hash::make($validated['password']),
            'provinsi' => $validated['provinsi'],
            'kota_kabupaten' => $validated['kota_kabupaten'],
            'alamat_lengkap' => $validated['alamat_lengkap'],
            'kode_pos' => $validated['kode_pos'] ?? null,
            'status_verifikasi' => 'menunggu',
            'is_active' => false,
        ]);

        // Kirim email notifikasi ke admin
        try {
            Mail::raw(
                "Seller baru telah mendaftar!\n\n" .
                "Nama Toko: {$seller->nama_toko}\n" .
                "PIC: {$seller->nama_pic}\n" .
                "Email: {$seller->email_pic}\n" .
                "Lokasi: {$seller->kota_kabupaten}, {$seller->provinsi}\n\n" .
                "Silakan login ke admin panel untuk verifikasi:\n" .
                url('/admin/sellers/' . $seller->id),
                function($message) {
                    $message->to(config('mail.from.address')) // Email admin
                            ->subject('Seller Baru Menunggu Verifikasi - KampusMarket');
                }
            );
        } catch (\Exception $e) {
            \Log::error('Gagal kirim email notifikasi seller baru: ' . $e->getMessage());
        }

        return redirect()->route('seller.register')
                         ->with('success', 'Registrasi berhasil! Silakan tunggu verifikasi dari Admin. Kami akan mengirim email konfirmasi setelah akun Anda diverifikasi.');
    }
}
