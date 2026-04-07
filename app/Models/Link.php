<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    protected $fillable = [
        'label',
        'url',
        'clicks',
        'display_type',
        'icon',
        'image_url',
        'text_color',
        'bg_color',
        'is_active',
        'open_new_tab',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'open_new_tab' => 'boolean',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order');
    }

    public function scopeTopClicks(Builder $query, int $limit = 5): Builder
    {
        return $query->orderByDesc('clicks')->limit($limit);
    }
}
