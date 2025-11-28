<?php

namespace App\Http\Controllers;

use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

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
            'nama_pic' => ['required', 'string', 'max:15', 'regex:/^[A-Za-z\s]+$/'],
            'email_pic' => 'required|email|unique:sellers,email_pic',
            'no_hp_pic' => ['required', 'regex:/^[0-9]+$/', 'digits_between:11,12'],
            'password' => 'required|string|min:8|confirmed',
            'alamat_lengkap' => 'required|string|max:100',
            'rt' => ['required', 'regex:/^[0-9]+$/', 'digits_between:1,3'],
            'rw' => ['required', 'regex:/^[0-9]+$/', 'digits_between:1,3'],
            'kelurahan' => ['required', 'string', 'max:50', 'regex:/^[A-Za-z\s]+$/'],
            'kecamatan' => ['required', 'string', 'max:50', 'regex:/^[A-Za-z\s]+$/'],
            'kota_kabupaten' => ['required', 'string', 'max:50', 'regex:/^[A-Za-z\s]+$/'],
            'provinsi' => ['required', 'string', 'max:50', 'regex:/^[A-Za-z\s]+$/'],
            'nomor_ktp' => ['required', 'regex:/^[0-9]{16}$/', 'unique:sellers,nomor_ktp'],
            'foto_seller' => 'required|image|mimes:jpg,jpeg|max:2048',
            'foto_ktp' => 'required|image|mimes:jpg,jpeg|max:2048',
            'kode_pos' => 'nullable|string|max:10',
        ], [
            'nama_toko.required' => 'Nama toko wajib diisi',
            'singkatan_toko.required' => 'Singkatan toko wajib diisi',
            'singkatan_toko.unique' => 'Singkatan toko sudah digunakan',
            'nama_pic.required' => 'Nama lengkap wajib diisi',
            'nama_pic.max' => 'Nama lengkap maksimal 15 karakter',
            'nama_pic.regex' => 'Nama lengkap hanya boleh huruf dan spasi',
            'email_pic.required' => 'Email PIC wajib diisi',
            'email_pic.email' => 'Format email tidak valid',
            'email_pic.unique' => 'Email sudah terdaftar',
            'no_hp_pic.required' => 'No HP wajib diisi',
            'no_hp_pic.regex' => 'No HP hanya boleh angka',
            'no_hp_pic.digits_between' => 'No HP harus 11-12 digit',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'alamat_lengkap.required' => 'Alamat wajib diisi',
            'alamat_lengkap.max' => 'Alamat maksimal 100 karakter',
            'rt.required' => 'RT wajib diisi',
            'rt.regex' => 'RT hanya boleh angka',
            'rt.digits_between' => 'RT maksimal 3 digit',
            'rw.required' => 'RW wajib diisi',
            'rw.regex' => 'RW hanya boleh angka',
            'rw.digits_between' => 'RW maksimal 3 digit',
            'kelurahan.required' => 'Kelurahan wajib diisi',
            'kelurahan.max' => 'Kelurahan maksimal 50 karakter',
            'kelurahan.regex' => 'Kelurahan hanya boleh huruf dan spasi',
            'kecamatan.required' => 'Kecamatan wajib diisi',
            'kecamatan.max' => 'Kecamatan maksimal 50 karakter',
            'kecamatan.regex' => 'Kecamatan hanya boleh huruf dan spasi',
            'kota_kabupaten.required' => 'Kota/Kabupaten wajib diisi',
            'kota_kabupaten.max' => 'Kota/Kabupaten maksimal 50 karakter',
            'kota_kabupaten.regex' => 'Kota/Kabupaten hanya boleh huruf dan spasi',
            'provinsi.required' => 'Provinsi wajib diisi',
            'provinsi.max' => 'Provinsi maksimal 50 karakter',
            'provinsi.regex' => 'Provinsi hanya boleh huruf dan spasi',
            'nomor_ktp.required' => 'Nomor KTP wajib diisi',
            'nomor_ktp.regex' => 'Nomor KTP harus 16 digit angka',
            'nomor_ktp.unique' => 'Nomor KTP sudah terdaftar',
            'foto_seller.required' => 'Foto seller wajib diupload',
            'foto_seller.image' => 'Foto seller harus berupa gambar',
            'foto_seller.mimes' => 'Foto seller harus berformat JPG/JPEG',
            'foto_ktp.required' => 'Foto KTP wajib diupload',
            'foto_ktp.image' => 'Foto KTP harus berupa gambar',
            'foto_ktp.mimes' => 'Foto KTP harus berformat JPG/JPEG',
        ]);

        $fotoSellerPath = $request->file('foto_seller')->store('sellers/foto', 'public');
        $fotoKtpPath = $request->file('foto_ktp')->store('sellers/ktp', 'public');

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
            'rt' => $validated['rt'],
            'rw' => $validated['rw'],
            'kelurahan' => $validated['kelurahan'],
            'kecamatan' => $validated['kecamatan'],
            'kode_pos' => $validated['kode_pos'] ?? null,
            'nomor_ktp' => $validated['nomor_ktp'],
            'foto_seller' => $fotoSellerPath,
            'foto_ktp' => $fotoKtpPath,
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
            Log::error('Gagal kirim email notifikasi seller baru: ' . $e->getMessage());
        }

        return redirect()->route('seller.register')
                         ->with('success', 'Registrasi berhasil! Silakan tunggu verifikasi dari Admin. Kami akan mengirim email konfirmasi setelah akun Anda diverifikasi.');
    }
}
