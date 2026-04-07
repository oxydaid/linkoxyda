<?php

namespace App\Filament\Widgets;

use App\Models\AppSetting;
use Filament\Widgets\Widget;

class ProfileInfoWidget extends Widget
{
    // Pastikan string view ini sesuai dengan lokasi file blade yang dibuat
    protected static string $view = 'filament.widgets.profile-info-widget';

    protected static ?int $sort = 2;

    // Agar widget ini mengambil 1 kolom grid (tidak full width)
    protected int|string|array $columnSpan = 1;

    public ?AppSetting $settings;

    public function mount()
    {
        $this->settings = AppSetting::current();
    }
}
