<?php

namespace App\Filament\Seller\Pages\Auth;

use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Auth\Login as BaseLogin;
use Illuminate\Validation\ValidationException;

/**
 * Custom Login Page untuk Seller Panel
 * Override field email menjadi email_pic sesuai struktur database
 */
class Login extends BaseLogin
{
    /**
     * Override form untuk menggunakan email_pic sebagai credential
     */
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->getEmailFormComponent()
                    ->label('Email PIC'), // Ubah label
                $this->getPasswordFormComponent(),
                $this->getRememberFormComponent(),
            ])
            ->statePath('data');
    }

    /**
     * Override email component untuk mapping ke email_pic
     */
    protected function getEmailFormComponent(): Component
    {
        return TextInput::make('email_pic') // Ganti dari 'email' ke 'email_pic'
            ->label('Email PIC')
            ->email()
            ->required()
            ->autocomplete()
            ->autofocus()
            ->extraInputAttributes(['tabindex' => 1]);
    }

    /**
     * Override getCredentialsFromFormData untuk mapping email -> email_pic
     */
    protected function getCredentialsFromFormData(array $data): array
    {
        return [
            'email_pic' => $data['email_pic'], // Gunakan email_pic
            'password' => $data['password'],
        ];
    }

    /**
     * Override authenticate untuk custom logic
     */
    public function authenticate(): ?\Filament\Http\Responses\Auth\Contracts\LoginResponse
    {
        try {
            $data = $this->form->getState();

            // Coba authenticate dengan email_pic
            if (! \Illuminate\Support\Facades\Auth::guard('seller')->attempt(
                $this->getCredentialsFromFormData($data),
                $data['remember'] ?? false
            )) {
                $this->throwFailureValidationException();
            }

            $seller = \Illuminate\Support\Facades\Auth::guard('seller')->user();

            // Cek apakah seller sudah diverifikasi
            if ($seller->status_verifikasi !== 'diterima') {
                \Illuminate\Support\Facades\Auth::guard('seller')->logout();
                
                throw ValidationException::withMessages([
                    'data.email_pic' => 'Akun Anda belum diverifikasi oleh Admin.',
                ]);
            }

            // Cek apakah seller aktif
            if (!$seller->is_active) {
                \Illuminate\Support\Facades\Auth::guard('seller')->logout();
                
                throw ValidationException::withMessages([
                    'data.email_pic' => 'Akun Anda tidak aktif. Silakan hubungi Admin.',
                ]);
            }

            session()->regenerate();

            return app(\Filament\Http\Responses\Auth\Contracts\LoginResponse::class);
        } catch (ValidationException $exception) {
            throw $exception;
        }
    }

    /**
     * Throw validation exception untuk kredensial salah
     */
    protected function throwFailureValidationException(): never
    {
        throw ValidationException::withMessages([
            'data.email_pic' => __('filament-panels::pages/auth/login.messages.failed'),
        ]);
    }
}
