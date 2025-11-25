<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use App\Models\Category;
use App\Models\Seller;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    
    protected static ?string $navigationLabel = 'Produk';
    
    protected static ?string $modelLabel = 'Produk';
    
    protected static ?string $pluralModelLabel = 'Produk';
    
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Dasar')
                    ->schema([
                        Forms\Components\Select::make('seller_id')
                            ->label('Penjual/Toko')
                            ->relationship('seller', 'nama_toko')
                            ->searchable()
                            ->required()
                            ->preload(),
                        
                        Forms\Components\Select::make('category_id')
                            ->label('Kategori')
                            ->relationship('category', 'nama_kategori', fn (Builder $query) => $query->where('is_active', true))
                            ->searchable()
                            ->required()
                            ->preload()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('nama_kategori')
                                    ->label('Nama Kategori')
                                    ->required(),
                                Forms\Components\Toggle::make('is_active')
                                    ->label('Aktif')
                                    ->default(true),
                            ]),
                        
                        Forms\Components\TextInput::make('nama_produk')
                            ->label('Nama Produk')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        
                        Forms\Components\RichEditor::make('deskripsi')
                            ->label('Deskripsi Produk')
                            ->required()
                            ->columnSpanFull()
                            ->toolbarButtons([
                                'bold',
                                'bulletList',
                                'orderedList',
                                'italic',
                                'underline',
                                'h2',
                                'h3',
                            ]),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Harga & Stok')
                    ->schema([
                        Forms\Components\TextInput::make('harga')
                            ->label('Harga (Rp)')
                            ->required()
                            ->numeric()
                            ->prefix('Rp')
                            ->minValue(0)
                            ->step(500),
                        
                        Forms\Components\TextInput::make('stok')
                            ->label('Stok')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->default(0),
                        
                        Forms\Components\TextInput::make('berat')
                            ->label('Berat (gram)')
                            ->numeric()
                            ->minValue(0)
                            ->suffix('gr')
                            ->helperText('Opsional, untuk keperluan pengiriman'),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Gambar Produk')
                    ->schema([
                        Forms\Components\FileUpload::make('gambar_utama')
                            ->label('Gambar Utama')
                            ->image()
                            ->directory('products')
                            ->maxSize(2048)
                            ->imageEditor()
                            ->columnSpanFull(),
                        
                        Forms\Components\FileUpload::make('galeri_gambar')
                            ->label('Galeri Gambar (Maksimal 5)')
                            ->image()
                            ->directory('products/gallery')
                            ->maxSize(2048)
                            ->maxFiles(5)
                            ->multiple()
                            ->reorderable()
                            ->imageEditor()
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Status & Metadata')
                    ->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->label('Produk Aktif')
                            ->default(true)
                            ->helperText('Hanya produk aktif yang ditampilkan di frontend'),
                        
                        Forms\Components\TextInput::make('views')
                            ->label('Jumlah View')
                            ->numeric()
                            ->default(0)
                            ->disabled()
                            ->dehydrated(false),
                        
                        Forms\Components\TextInput::make('total_terjual')
                            ->label('Total Terjual')
                            ->numeric()
                            ->default(0)
                            ->helperText('Untuk keperluan statistik'),
                    ])
                    ->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('gambar_utama')
                    ->label('Gambar')
                    ->square()
                    ->defaultImageUrl(fn () => 'https://ui-avatars.com/api/?name=Produk'),
                
                Tables\Columns\TextColumn::make('nama_produk')
                    ->label('Nama Produk')
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->description(fn ($record) => $record->seller->nama_toko ?? '-'),
                
                Tables\Columns\TextColumn::make('category.nama_kategori')
                    ->label('Kategori')
                    ->badge()
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('harga')
                    ->label('Harga')
                    ->money('IDR')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('stok')
                    ->label('Stok')
                    ->sortable()
                    ->alignCenter()
                    ->badge()
                    ->color(fn ($state) => match(true) {
                        $state == 0 => 'danger',
                        $state < 5 => 'warning',
                        default => 'success',
                    }),
                
                Tables\Columns\TextColumn::make('average_rating')
                    ->label('Rating')
                    ->formatStateUsing(fn ($state) => number_format($state, 1) . ' ⭐')
                    ->sortable()
                    ->alignCenter(),
                
                Tables\Columns\TextColumn::make('total_reviews')
                    ->label('Review')
                    ->alignCenter()
                    ->sortable(),
                
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('views')
                    ->label('Views')
                    ->sortable()
                    ->alignCenter()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category_id')
                    ->label('Kategori')
                    ->relationship('category', 'nama_kategori')
                    ->searchable()
                    ->preload(),
                
                Tables\Filters\SelectFilter::make('seller_id')
                    ->label('Penjual')
                    ->relationship('seller', 'nama_toko')
                    ->searchable()
                    ->preload(),
                
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status')
                    ->placeholder('Semua')
                    ->trueLabel('Aktif')
                    ->falseLabel('Tidak Aktif'),
                
                Tables\Filters\Filter::make('stok_rendah')
                    ->label('Stok Rendah (< 5)')
                    ->query(fn (Builder $query): Builder => $query->where('stok', '<', 5))
                    ->toggle(),
                
                Tables\Filters\Filter::make('stok_habis')
                    ->label('Stok Habis')
                    ->query(fn (Builder $query): Builder => $query->where('stok', '=', 0))
                    ->toggle(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'view' => Pages\ViewProduct::route('/{record}'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
