<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductReviewResource\Pages;
use App\Filament\Resources\ProductReviewResource\RelationManagers;
use App\Models\ProductReview;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductReviewResource extends Resource
{
    protected static ?string $model = ProductReview::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    
    protected static ?string $navigationLabel = 'Review Produk';
    
    protected static ?string $modelLabel = 'Review';
    
    protected static ?string $pluralModelLabel = 'Review Produk';
    
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Produk')
                    ->schema([
                        Forms\Components\Select::make('product_id')
                            ->label('Produk')
                            ->relationship('product', 'nama_produk')
                            ->searchable()
                            ->required()
                            ->preload(),
                    ]),

                Forms\Components\Section::make('Informasi Pengunjung')
                    ->schema([
                        Forms\Components\TextInput::make('nama_pengunjung')
                            ->label('Nama Pengunjung')
                            ->required()
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('email_pengunjung')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('no_hp_pengunjung')
                            ->label('No. HP')
                            ->tel()
                            ->required()
                            ->maxLength(20),

                        Forms\Components\TextInput::make('provinsi_pengunjung')
                            ->label('Provinsi Pengunjung')
                            ->required()
                            ->maxLength(100),
                        
                        Forms\Components\TextInput::make('kota_pengunjung')
                            ->label('Kota / Kabupaten Pengunjung')
                            ->required()
                            ->maxLength(100),
                        
                        Forms\Components\TextInput::make('ip_address')
                            ->label('IP Address')
                            ->disabled()
                            ->dehydrated(false),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Review')
                    ->schema([
                        Forms\Components\Select::make('rating')
                            ->label('Rating')
                            ->options([
                                1 => '1 - Sangat Buruk',
                                2 => '2 - Buruk',
                                3 => '3 - Cukup',
                                4 => '4 - Baik',
                                5 => '5 - Sangat Baik',
                            ])
                            ->required()
                            ->native(false),
                        
                        Forms\Components\Textarea::make('isi_komentar')
                            ->label('Komentar')
                            ->required()
                            ->rows(4)
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Moderasi')
                    ->schema([
                        Forms\Components\Toggle::make('is_approved')
                            ->label('Disetujui')
                            ->default(true)
                            ->helperText('Hanya review yang disetujui yang tampil di frontend'),
                        
                        Forms\Components\Toggle::make('is_spam')
                            ->label('Tandai Spam')
                            ->default(false),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('product.nama_produk')
                    ->label('Produk')
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->description(fn ($record) => $record->product->seller->nama_toko ?? '-'),
                
                Tables\Columns\TextColumn::make('nama_pengunjung')
                    ->label('Pengunjung')
                    ->searchable()
                    ->description(fn ($record) => $record->email_pengunjung),
                
                Tables\Columns\TextColumn::make('kota_pengunjung')
                    ->label('Lokasi')
                    ->icon('heroicon-o-map-pin')
                    ->formatStateUsing(fn ($record) => collect([$record->kota_pengunjung, $record->provinsi_pengunjung])->filter()->implode(', '))
                    ->toggleable(),
                
                Tables\Columns\TextColumn::make('rating')
                    ->label('Rating')
                    ->sortable()
                    ->badge()
                    ->formatStateUsing(fn ($state) => str_repeat('⭐', $state) . " ({$state}/5)")
                    ->color(fn ($state) => match(true) {
                        $state >= 4 => 'success',
                        $state >= 3 => 'warning',
                        default => 'danger',
                    }),
                
                Tables\Columns\TextColumn::make('isi_komentar')
                    ->label('Komentar')
                    ->limit(50)
                    ->wrap()
                    ->toggleable(),
                
                Tables\Columns\IconColumn::make('is_approved')
                    ->label('Disetujui')
                    ->boolean()
                    ->sortable(),
                
                Tables\Columns\IconColumn::make('is_spam')
                    ->label('Spam')
                    ->boolean()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('rating')
                    ->label('Rating')
                    ->options([
                        5 => '5 Bintang',
                        4 => '4 Bintang',
                        3 => '3 Bintang',
                        2 => '2 Bintang',
                        1 => '1 Bintang',
                    ]),
                
                Tables\Filters\TernaryFilter::make('is_approved')
                    ->label('Status Persetujuan')
                    ->placeholder('Semua')
                    ->trueLabel('Disetujui')
                    ->falseLabel('Belum Disetujui'),
                
                Tables\Filters\TernaryFilter::make('is_spam')
                    ->label('Spam')
                    ->placeholder('Semua')
                    ->trueLabel('Spam')
                    ->falseLabel('Bukan Spam'),
                
                Tables\Filters\SelectFilter::make('product_id')
                    ->label('Produk')
                    ->relationship('product', 'nama_produk')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    
                    Tables\Actions\Action::make('approve')
                        ->label('Setujui')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->visible(fn ($record) => !$record->is_approved)
                        ->action(fn ($record) => $record->update(['is_approved' => true, 'is_spam' => false])),
                    
                    Tables\Actions\Action::make('reject')
                        ->label('Tolak')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->visible(fn ($record) => $record->is_approved)
                        ->action(fn ($record) => $record->update(['is_approved' => false])),
                    
                    Tables\Actions\Action::make('spam')
                        ->label('Tandai Spam')
                        ->icon('heroicon-o-exclamation-triangle')
                        ->color('warning')
                        ->visible(fn ($record) => !$record->is_spam)
                        ->action(fn ($record) => $record->update(['is_spam' => true, 'is_approved' => false])),
                    
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('approve')
                        ->label('Setujui Semua')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(fn ($records) => $records->each->update(['is_approved' => true, 'is_spam' => false])),
                    
                    Tables\Actions\BulkAction::make('reject')
                        ->label('Tolak Semua')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->action(fn ($records) => $records->each->update(['is_approved' => false])),
                    
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProductReviews::route('/'),
            'create' => Pages\CreateProductReview::route('/create'),
            'view' => Pages\ViewProductReview::route('/{record}'),
            'edit' => Pages\EditProductReview::route('/{record}/edit'),
        ];
    }
    
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('is_approved', false)->where('is_spam', false)->count();
    }
    
    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }
}
