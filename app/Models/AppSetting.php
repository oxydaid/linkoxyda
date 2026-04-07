<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class AppSetting extends Model
{
    public const CACHE_KEY = 'app_settings.current';

    protected $fillable = [
        'profile_name',
        'profile_bio',
        'avatar_url',
        'theme_config',
        'social_links',
        'primary_color',
    ];

    protected $casts = [
        'theme_config' => 'array', // Otomatis jadi array PHP saat dipanggil
        'social_links' => 'array',
    ];

    public static function current(): self
    {
        return Cache::rememberForever(self::CACHE_KEY, fn () => self::query()->first() ?? new self);
    }

    public static function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    protected static function booted(): void
    {
        static::saved(fn () => self::clearCache());
        static::deleted(fn () => self::clearCache());
        static::restored(fn () => self::clearCache());
    }

    // Helper function untuk mengambil setting dengan mudah (Opsional tapi berguna)
    public static function getTheme(string $key, mixed $default = null): mixed
    {
        return self::current()->theme_config[$key] ?? $default;
    }
}
