<?php

namespace App\Filament\Pages;

use App\Models\AppSetting;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class AppSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationLabel = 'App Settings';

    protected static ?string $title = 'Konfigurasi Aplikasi';

    protected static string $view = 'filament.pages.app-settings';

    // Variabel penampung data form
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(AppSetting::current()->toArray());
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // --- SECTION 1: PROFIL PENGGUNA ---
                Section::make('Profil Halaman')
                    ->description('Atur identitas utama halaman bio link Anda.')
                    ->schema([
                        FileUpload::make('avatar_url')
                            ->label('Foto Profil')
                            ->avatar() // Mode bulat
                            ->image()
                            ->directory('avatars')
                            ->imageEditor()
                            ->imageCropAspectRatio('1:1')
                            ->imageResizeTargetWidth('500')
                            ->imageResizeTargetHeight('500')
                            ->columnSpanFull()
                            ->alignCenter(),

                        Grid::make(2)->schema([
                            TextInput::make('profile_name')
                                ->label('Nama Tampilan')
                                ->required(),

                            TextInput::make('profile_bio')
                                ->label('Bio Singkat')
                                ->placeholder('Programmer | Content Creator'),
                        ]),
                    ]),

                // --- SECTION 2: TEMA & TAMPILAN (Dinamis via JSON) ---
                Section::make('Kustomisasi Tema')
                    ->description('Ubah tampilan background, tombol, dan warna.')
                    ->schema([
                        // TEMA PRESET
                        Select::make('theme_preset')
                            ->label('Pilih Tema Preset')
                            ->options([
                                'light' => '☀️ Light Mode - Bersih dan Terang',
                                'dark' => '🌙 Dark Mode - Elegan dan Gelap',
                                'ocean' => '🌊 Ocean - Biru Menenangkan',
                                'sunset' => '🌅 Sunset - Oren Hangat',
                                'forest' => '🌲 Forest - Hijau Alam',
                                'purple' => '💜 Purple - Ungu Mewah',
                                'neon' => '⚡ Neon - Vibrant & Modern',
                            ])
                            ->placeholder('Pilih tema preset...')
                            ->helperText('Pilih tema siap pakai atau customize sendiri di bawah')
                            ->live()
                            ->afterStateUpdated(function ($state, \Filament\Forms\Set $set) {
                                if (! $state) {
                                    return;
                                }

                                $presets = [
                                    'light' => [
                                        'background_type' => 'flat',
                                        'background_color' => '#f3f4f6',
                                        'button_bg_color' => '#ffffff',
                                        'button_text_color' => '#1f2937',
                                    ],
                                    'dark' => [
                                        'background_type' => 'flat',
                                        'background_color' => '#1f2937',
                                        'button_bg_color' => '#374151',
                                        'button_text_color' => '#f3f4f6',
                                    ],
                                    'ocean' => [
                                        'background_type' => 'gradient',
                                        'background_gradient' => 'bg-gradient-to-br from-blue-400 via-blue-500 to-blue-600',
                                        'button_bg_color' => '#ffffff',
                                        'button_text_color' => '#0369a1',
                                    ],
                                    'sunset' => [
                                        'background_type' => 'gradient',
                                        'background_gradient' => 'bg-gradient-to-br from-orange-400 via-red-500 to-pink-600',
                                        'button_bg_color' => '#ffffff',
                                        'button_text_color' => '#ea580c',
                                    ],
                                    'forest' => [
                                        'background_type' => 'gradient',
                                        'background_gradient' => 'bg-gradient-to-br from-green-400 via-teal-500 to-green-700',
                                        'button_bg_color' => '#ffffff',
                                        'button_text_color' => '#059669',
                                    ],
                                    'purple' => [
                                        'background_type' => 'gradient',
                                        'background_gradient' => 'bg-gradient-to-br from-purple-500 via-pink-500 to-red-500',
                                        'button_bg_color' => '#ffffff',
                                        'button_text_color' => '#9333ea',
                                    ],
                                    'neon' => [
                                        'background_type' => 'flat',
                                        'background_color' => '#0a0e27',
                                        'button_bg_color' => '#00ff88',
                                        'button_text_color' => '#0a0e27',
                                    ],
                                ];

                                $preset = $presets[$state] ?? [];
                                foreach ($preset as $key => $value) {
                                    $set("theme_config.$key", $value);
                                }
                            }),

                        Grid::make(3)->schema([
                            // Pengaturan Background
                            Select::make('theme_config.background_type')
                                ->label('Tipe Background')
                                ->options([
                                    'flat' => 'Warna Polos',
                                    'gradient' => 'Gradasi',
                                    'image' => 'Gambar/Wallpaper',
                                ])
                                ->default('flat')
                                ->live(),

                            // Muncul jika tipe FLAT
                            ColorPicker::make('theme_config.background_color')
                                ->label('Warna Background')
                                ->visible(fn ($get) => $get('theme_config.background_type') === 'flat'),

                            // Muncul jika tipe GRADIENT
                            Select::make('theme_config.background_gradient')
                                ->label('Pilihan Gradasi')
                                ->options([
                                    'preset' => '📚 Pilih Preset',
                                    'custom' => '🎨 Gradasi Custom',
                                    'bg-gradient-to-r from-cyan-500 to-blue-500' => 'Cyan to Blue',
                                    'bg-gradient-to-r from-purple-500 to-pink-500' => 'Purple to Pink',
                                    'bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900' => 'Dark Galaxy',
                                    'bg-gradient-to-br from-blue-400 via-blue-500 to-blue-600' => 'Ocean Blue',
                                    'bg-gradient-to-br from-orange-400 via-red-500 to-pink-600' => 'Sunset Fire',
                                    'bg-gradient-to-br from-green-400 via-teal-500 to-green-700' => 'Forest Green',
                                    'bg-gradient-to-br from-yellow-400 via-red-500 to-pink-500' => 'Warm Flame',
                                    'bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500' => 'Vibrant Mix',
                                    'bg-gradient-to-br from-slate-700 via-slate-800 to-slate-900' => 'Dark Slate',
                                    'bg-gradient-to-r from-red-500 to-yellow-500' => 'Red Yellow',
                                ])
                                ->visible(fn ($get) => $get('theme_config.background_type') === 'gradient')
                                ->live(),

                            // Muncul jika tipe IMAGE
                            FileUpload::make('theme_config.background_image')
                                ->label('Upload Wallpaper')
                                ->image()
                                ->imagePreviewHeight('200')
                                ->directory('backgrounds')
                                ->visible(fn ($get) => $get('theme_config.background_type') === 'image'),
                        ]),

                        // Custom Gradient Settings
                        Grid::make(2)->schema([
                            ColorPicker::make('theme_config.gradient_color_start')
                                ->label('Warna Awal Gradasi')
                                ->default('#0369a1')
                                ->visible(fn ($get) => $get('theme_config.background_type') === 'gradient' && $get('theme_config.background_gradient') === 'custom'),

                            ColorPicker::make('theme_config.gradient_color_end')
                                ->label('Warna Akhir Gradasi')
                                ->default('#3b82f6')
                                ->visible(fn ($get) => $get('theme_config.background_type') === 'gradient' && $get('theme_config.background_gradient') === 'custom'),

                            Select::make('theme_config.gradient_direction')
                                ->label('Arah Gradasi')
                                ->options([
                                    'to right' => '→ Ke Kanan',
                                    'to bottom' => '↓ Ke Bawah',
                                    '135deg' => '↘ Diagonal',
                                    'to left' => '← Ke Kiri',
                                    'to top' => '↑ Ke Atas',
                                ])
                                ->default('to right')
                                ->visible(fn ($get) => $get('theme_config.background_type') === 'gradient' && $get('theme_config.background_gradient') === 'custom'),
                        ]),

                        Grid::make(3)->schema([
                            // Pengaturan Bentuk Tombol
                            Select::make('theme_config.button_style')
                                ->label('Bentuk Tombol')
                                ->options([
                                    'rounded-none' => 'Kotak (Square)',
                                    'rounded-md' => 'Rounded (Sedikit Lengkung)',
                                    'rounded-full' => 'Pill (Bulat Penuh)',
                                    'shadow-lg' => 'Shadow (Bayangan)',
                                    'border-2' => 'Outline Only',
                                ])
                                ->default('rounded-md'),

                            ColorPicker::make('theme_config.button_bg_color')
                                ->label('Warna Dasar Tombol')
                                ->default('#ffffff'),

                            ColorPicker::make('theme_config.button_text_color')
                                ->label('Warna Teks Tombol')
                                ->default('#000000'),

                            Select::make('theme_config.font_family')
                                ->label('Font Halaman')
                                ->options([
                                    'font-sans' => 'Inter (Sans - Modern)',
                                    'font-serif' => 'Lora (Serif - Elegan)',
                                    'font-mono' => 'JetBrains Mono (Monospace)',
                                    'font-poppins' => 'Poppins (Bold & Modern)',
                                    'font-playfair' => 'Playfair Display (Premium)',
                                    'font-roboto' => 'Roboto (Clean & Solid)',
                                    'font-raleway' => 'Raleway (Geometric)',
                                    'font-ubuntu' => 'Ubuntu (Humanist)',
                                    'font-quicksand' => 'Quicksand (Rounded)',
                                    'font-dancing' => 'Dancing Script (Elegant)',
                                ])
                                ->default('font-sans'),
                        ]),

                        Grid::make(2)->schema([
                            ColorPicker::make('theme_config.text_color')
                                ->label('Warna Teks Halaman (Judul, Bio, dll)')
                                ->default('#ffffff')
                                ->helperText('Pilih warna yang kontras dengan background Anda'),

                            ColorPicker::make('theme_config.text_secondary_color')
                                ->label('Warna Teks Sekunder')
                                ->default('#e5e7eb')
                                ->helperText('Untuk teks yang lebih kecil/kurang penting'),
                        ]),
                    ]),

                // --- SECTION: FILAMENT ADMIN THEME ---
                Section::make('Tema Admin Panel')
                    ->description('Atur warna primary theme untuk panel admin Filament.')
                    ->schema([
                        ColorPicker::make('primary_color')
                            ->label('Warna Primary Admin')
                            ->helperText('Warna ini akan digunakan sebagai warna primary di seluruh panel admin Filament.')
                            ->default('#f59e0b')
                            ->nullable(),
                    ]),

                Section::make('Sosial Media Footer')
                    ->schema([
                        Repeater::make('social_links')
                            ->label('Daftar Ikon Sosial Media')
                            ->schema([
                                Grid::make(2)->schema([
                                    // Menggunakan Font Awesome Free
                                    TextInput::make('icon')
                                        ->label('Icon Code (Font Awesome Free)')
                                        ->placeholder('fab fa-instagram')
                                        ->helperText('Gunakan kode Font Awesome Free. Contoh: fab fa-instagram, fab fa-tiktok, fab fa-twitter | Brand Icons: fab fa-*, Solid: fas fa-*'),

                                    TextInput::make('url')
                                        ->label('Link URL')
                                        ->url()
                                        ->required(),

                                    ColorPicker::make('color')
                                        ->label('Warna Icon (Opsional)'),
                                ]),
                            ])
                            ->columns(1)
                            ->grid(2) // Tampilan grid di admin
                            ->defaultItems(0),
                    ]),
            ])
            ->statePath('data'); // Binding form ke variabel $data
    }

    public function submit(): void
    {
        // Validasi dan Ambil Data
        $formState = $this->form->getState();

        // Jika menggunakan custom gradient, generate inline style
        if ($formState['theme_config']['background_type'] === 'gradient' &&
            $formState['theme_config']['background_gradient'] === 'custom') {

            $colorStart = $formState['theme_config']['gradient_color_start'] ?? '#0369a1';
            $colorEnd = $formState['theme_config']['gradient_color_end'] ?? '#3b82f6';
            $direction = $formState['theme_config']['gradient_direction'] ?? 'to right';

            // Simpan sebagai inline style
            $formState['theme_config']['background_gradient_style'] =
                "background: linear-gradient($direction, $colorStart, $colorEnd);";
        }

        $settings = AppSetting::current();

        // Jika primary_color kosong, gunakan default
        if (empty($formState['primary_color'])) {
            $formState['primary_color'] = '#f59e0b';
        }

        // Update data
        $settings->fill($formState);
        $settings->save();

        Notification::make()
            ->title('Pengaturan berhasil disimpan!')
            ->success()
            ->send();
    }
}
