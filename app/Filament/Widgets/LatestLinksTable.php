<?php

namespace App\Filament\Widgets;

use App\Models\Link;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestLinksTable extends BaseWidget
{
    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = 'full'; // Agar lebar memenuhi layar

    public function table(Table $table): Table
    {
        return $table
            ->query(Link::query()->latest()->limit(5)) // 5 Link terbaru
            ->columns([
                Tables\Columns\TextColumn::make('label')->label('Nama Link')->weight('bold'),
                Tables\Columns\TextColumn::make('url')->label('URL')->limit(30),
                Tables\Columns\TextColumn::make('clicks')->label('Total Klik')->badge()->color('success'),
                Tables\Columns\IconColumn::make('is_active')->boolean()->label('Aktif'),
            ]);
    }
}
