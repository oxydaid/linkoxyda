<?php

namespace App\Filament\Widgets;

use App\Models\Link;
use Filament\Widgets\ChartWidget;

class TopLinksChart extends ChartWidget
{
    protected static ?string $heading = 'Link Terpopuler';

    protected static ?int $sort = 3; // Urutan widget di bawah StatsOverview

    protected function getData(): array
    {
        // Ambil 5 link dengan klik terbanyak
        $data = Link::query()->topClicks(5)->get();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Klik',
                    'data' => $data->pluck('clicks'),
                    'backgroundColor' => '#36A2EB',
                    'borderColor' => '#36A2EB',
                ],
            ],
            'labels' => $data->pluck('label'), // Nama Link sebagai label bawah
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
