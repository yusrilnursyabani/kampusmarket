<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SellerResource\Pages;
use App\Filament\Resources\SellerResource\RelationManagers;
use App\Mail\SellerApprovedMail;
use App\Mail\SellerRejectedMail;
use App\Models\Seller;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class SellerResource extends Resource
{
    protected static ?string $model = Seller::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';
    
    protected static ?string $navigationLabel = 'Penjual/Toko';
    
    protected static ?string $modelLabel = 'Penjual';
    
    protected static ?string $pluralModelLabel = 'Penjual';
    
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Toko')
                    ->schema([
                        Forms\Components\TextInput::make('nama_toko')
                            ->label('Nama Toko')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        
                        Forms\Components\TextInput::make('singkatan_toko')
                            ->label('Singkatan/Username Toko')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->helperText('Username unik untuk toko (huruf kecil, tanpa spasi)')
                            ->rules(['alpha_dash'])
                            ->columnSpan(1),
                        
                        Forms\Components\FileUpload::make('logo_toko')
                            ->label('Logo Toko')
                            ->image()
                            ->directory('sellers/logos')
                            ->maxSize(2048)
                            ->columnSpan(1),
                        
                        Forms\Components\Textarea::make('deskripsi_toko')
                            ->label('Deskripsi Toko')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Informasi PIC (Penanggung Jawab)')
                    ->schema([
                        Forms\Components\TextInput::make('nama_pic')
                            ->label('Nama PIC')
                            ->required()
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('email_pic')
                            ->label('Email PIC')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('no_hp_pic')
                            ->label('No. HP PIC')
                            ->tel()
                            ->required()
                            ->maxLength(20),
                        
                        Forms\Components\TextInput::make('password')
                            ->label('Password')
                            ->password()
                            ->required(fn ($context) => $context === 'create')
                            ->dehydrateStateUsing(fn ($state) => filled($state) ? Hash::make($state) : null)
                            ->dehydrated(fn ($state) => filled($state))
                            ->helperText('Kosongkan jika tidak ingin mengubah password'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Lokasi Toko')
                    ->schema([
                        Forms\Components\TextInput::make('provinsi')
                            ->label('Provinsi')
                            ->required()
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('kota_kabupaten')
                            ->label('Kota/Kabupaten')
                            ->required()
                            ->maxLength(255),
                        
                        Forms\Components\Textarea::make('alamat_lengkap')
                            ->label('Alamat Lengkap')
                            ->required()
                            ->rows(3)
                            ->columnSpanFull(),
                        
                        Forms\Components\TextInput::make('kode_pos')
                            ->label('Kode Pos')
                            ->maxLength(10),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Status & Verifikasi')
                    ->schema([
                        Forms\Components\Select::make('status_verifikasi')
                            ->label('Status Verifikasi')
                            ->options([
                                'menunggu' => 'Menunggu Verifikasi',
                                'diterima' => 'Diterima',
                                'ditolak' => 'Ditolak',
                            ])
                            ->required()
                            ->default('menunggu')
                            ->reactive(),
                        
                        Forms\Components\Textarea::make('alasan_penolakan')
                            ->label('Alasan Penolakan')
                            ->rows(3)
                            ->visible(fn ($get) => $get('status_verifikasi') === 'ditolak')
                            ->required(fn ($get) => $get('status_verifikasi') === 'ditolak')
                            ->columnSpanFull(),
                        
                        Forms\Components\Toggle::make('is_active')
                            ->label('Status Aktif')
                            ->helperText('Hanya toko aktif yang bisa login dan mengelola produk')
                            ->default(false),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('logo_toko')
                    ->label('Logo')
                    ->circular()
                    ->defaultImageUrl(fn () => 'https://ui-avatars.com/api/?name=Toko'),
                
                Tables\Columns\TextColumn::make('nama_toko')
                    ->label('Nama Toko')
                    ->searchable()
                    ->sortable()
                    ->description(fn ($record) => '@' . $record->singkatan_toko),
                
                Tables\Columns\TextColumn::make('nama_pic')
                    ->label('PIC')
                    ->searchable()
                    ->description(fn ($record) => $record->email_pic),
                
                Tables\Columns\TextColumn::make('full_location')
                    ->label('Lokasi')
                    ->sortable(['kota_kabupaten', 'provinsi']),
                
                Tables\Columns\BadgeColumn::make('status_verifikasi')
                    ->label('Status')
                    ->colors([
                        'warning' => 'menunggu',
                        'success' => 'diterima',
                        'danger' => 'ditolak',
                    ])
                    ->icons([
                        'heroicon-o-clock' => 'menunggu',
                        'heroicon-o-check-circle' => 'diterima',
                        'heroicon-o-x-circle' => 'ditolak',
                    ]),
                
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('products_count')
                    ->label('Produk')
                    ->counts('products')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Terdaftar')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status_verifikasi')
                    ->label('Status Verifikasi')
                    ->options([
                        'menunggu' => 'Menunggu',
                        'diterima' => 'Diterima',
                        'ditolak' => 'Ditolak',
                    ]),
                
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status Aktif')
                    ->placeholder('Semua')
                    ->trueLabel('Aktif')
                    ->falseLabel('Tidak Aktif'),
                
                Tables\Filters\Filter::make('provinsi')
                    ->form([
                        Forms\Components\TextInput::make('provinsi')
                            ->label('Provinsi'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['provinsi'],
                            fn (Builder $query, $provinsi): Builder => $query->where('provinsi', 'like', "%{$provinsi}%"),
                        );
                    }),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    
                    // Aksi Terima Seller
                    Tables\Actions\Action::make('approve')
                        ->label('Terima')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->visible(fn ($record) => $record->status_verifikasi === 'menunggu')
                        ->requiresConfirmation()
                        ->modalHeading('Terima Pendaftaran Seller')
                        ->modalDescription('Seller akan mendapat email konfirmasi dan dapat login ke panel.')
                        ->action(function (Seller $record) {
                            $record->update([
                                'status_verifikasi' => 'diterima',
                                'is_active' => true,
                                'verified_at' => now(),
                                'alasan_penolakan' => null,
                            ]);
                            
                            // Kirim email
                            Mail::to($record->email_pic)->send(new SellerApprovedMail($record));
                            
                            Notification::make()
                                ->title('Seller Disetujui')
                                ->success()
                                ->body("Seller {$record->nama_toko} telah disetujui dan email telah dikirim.")
                                ->send();
                        }),
                    
                    // Aksi Tolak Seller
                    Tables\Actions\Action::make('reject')
                        ->label('Tolak')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->visible(fn ($record) => $record->status_verifikasi === 'menunggu')
                        ->form([
                            Forms\Components\Textarea::make('alasan_penolakan')
                                ->label('Alasan Penolakan')
                                ->required()
                                ->rows(4)
                                ->placeholder('Jelaskan alasan penolakan agar seller bisa memperbaiki data mereka...'),
                        ])
                        ->action(function (Seller $record, array $data) {
                            $record->update([
                                'status_verifikasi' => 'ditolak',
                                'is_active' => false,
                                'alasan_penolakan' => $data['alasan_penolakan'],
                            ]);
                            
                            // Kirim email
                            Mail::to($record->email_pic)->send(new SellerRejectedMail($record));
                            
                            Notification::make()
                                ->title('Seller Ditolak')
                                ->warning()
                                ->body("Seller {$record->nama_toko} telah ditolak dan email telah dikirim.")
                                ->send();
                        }),
                    
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            // Relation manager bisa ditambahkan nanti jika diperlukan
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSellers::route('/'),
            'create' => Pages\CreateSeller::route('/create'),
            'view' => Pages\ViewSeller::route('/{record}'),
            'edit' => Pages\EditSeller::route('/{record}/edit'),
        ];
    }
    
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status_verifikasi', 'menunggu')->count();
    }
    
    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }
}
