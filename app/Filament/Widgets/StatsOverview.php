<?php

namespace App\Filament\Widgets;

use App\Models\Link;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Link', Link::count())
                ->description('Jumlah semua link yang dibuat')
                ->descriptionIcon('heroicon-m-link')
                ->color('primary'),

            Stat::make('Total Klik', Link::sum('clicks'))
                ->description('Total pengunjung mengklik tombol')
                ->descriptionIcon('heroicon-m-cursor-arrow-rays')
                ->chart([7, 2, 10, 3, 15, 4, 17]) // Dummy chart atau ambil dari history
                ->color('success'),

            Stat::make('Link Aktif', Link::query()->active()->count())
                ->description('Link yang tampil di publik')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('info'),
        ];
    }
}
