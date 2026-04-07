<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LinkResource\Pages;
use App\Models\Link;
use Filament\Forms;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class LinkResource extends Resource
{
    protected static ?string $model = Link::class;

    protected static ?string $navigationIcon = 'heroicon-o-link';

    protected static ?string $navigationLabel = 'Manajemen Link';

    protected static ?string $modelLabel = 'Link';

    protected static ?int $navigationSort = 1; // Agar muncul paling atas

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Link')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('label')
                                ->label('Judul Tombol')
                                ->placeholder('Contoh: Join Discord Kami')
                                ->required(),

                            TextInput::make('url')
                                ->label('URL Tujuan')
                                ->placeholder('https://discord.gg/...')
                                ->url() // Validasi format URL
                                ->required()
                                ->suffixIcon('heroicon-m-globe-alt'),
                        ]),

                        Select::make('display_type')
                            ->label('Jenis Tampilan')
                            ->options([
                                'icon' => '🎨 Icon (Font Awesome)',
                                'image' => '🖼️ Gambar Custom',
                            ])
                            ->default('icon')
                            ->required()
                            ->live(),

                        Grid::make(2)->schema([
                            // Input untuk Icon (Manual ketik nama icon FontAwesome)
                            TextInput::make('icon')
                                ->label('Icon Class (Font Awesome Free)')
                                ->placeholder('fas fa-home')
                                ->helperText('Contoh: fas fa-home, fab fa-instagram, fas fa-heart')
                                ->visible(fn (\Filament\Forms\Get $get) => $get('display_type') === 'icon'),

                            // Upload Gambar
                            FileUpload::make('image_url')
                                ->label('Upload Gambar')
                                ->image()
                                ->directory('link-images')
                                ->imageEditor()
                                ->imageCropAspectRatio('1:1')
                                ->imageResizeTargetWidth('500')
                                ->imageResizeTargetHeight('500')
                                ->helperText('Format: JPG, PNG, GIF (max 2MB) - Gambar akan di-crop 1:1')
                                ->visible(fn (\Filament\Forms\Get $get) => $get('display_type') === 'image'),
                        ]),
                    ]),

                Section::make('Kustomisasi Tampilan (Opsional)')
                    ->description('Jika kosong, akan mengikuti tema global dari App Settings.')
                    ->schema([
                        Grid::make(2)->schema([
                            ColorPicker::make('text_color')
                                ->label('Warna Teks Khusus')
                                ->nullable(),

                            ColorPicker::make('bg_color')
                                ->label('Warna Background Khusus')
                                ->nullable(),
                        ]),

                        // Tombol Reset
                        Forms\Components\Actions::make([
                            Forms\Components\Actions\Action::make('reset_colors')
                                ->label('Reset ke Warna Default')
                                ->icon('heroicon-m-arrow-path')
                                ->action(function (Forms\Set $set) {
                                    $set('text_color', null);
                                    $set('bg_color', null);
                                })
                                ->color('warning'),
                        ]),
                    ])
                    ->collapsible(), // Bisa ditutup agar rapi

                Section::make('Pengaturan Tambahan')
                    ->schema([
                        Grid::make(2)->schema([
                            Toggle::make('open_new_tab')
                                ->label('Buka di Tab Baru')
                                ->default(true),

                            Toggle::make('is_active')
                                ->label('Aktifkan Link')
                                ->default(true)
                                ->helperText('Jika dimatikan, link tidak akan muncul di halaman depan.'),
                        ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Kolom Icon Preview (Jika ada)
                Tables\Columns\TextColumn::make('icon')
                    ->label('Icon')
                    ->formatStateUsing(fn ($state) => $state ? "<i class='{$state}'></i> $state" : '-')
                    ->html(), // Render HTML agar icon muncul di tabel admin (jika load FA di admin)

                Tables\Columns\TextColumn::make('label')
                    ->label('Judul')
                    ->searchable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('url')
                    ->label('URL')
                    ->limit(30)
                    ->icon('heroicon-m-link')
                    ->copyable(), // Bisa dicopy langsung

                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Aktif?'),
            ])
            ->filters([
                // Filter link aktif/non-aktif
                Tables\Filters\Filter::make('active')
                    ->query(fn ($query) => $query->where('is_active', true))
                    ->label('Hanya Link Aktif'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('sort_order', 'asc') // Urutkan berdasarkan sort_order
            ->reorderable('sort_order'); // FITUR PENTING: Drag & Drop Reordering
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
            'index' => Pages\ListLinks::route('/'),
            'create' => Pages\CreateLink::route('/create'),
            'edit' => Pages\EditLink::route('/{record}/edit'),
        ];
    }
}
