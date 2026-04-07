@props(['settings'])

@php
    $profileName = $settings->profile_name ?? config('app.name');
    $profileBio = $settings->profile_bio ?? 'My Linktree Profile';
    $avatarUrl = $settings->avatar_url ? asset('storage/'.$settings->avatar_url) : null;
    $ogImage = $avatarUrl ?? asset('favicon.ico');
    $themeColor = $settings->primary_color ?? '#6366f1';
    $currentUrl = url()->current();
    $siteName = config('app.name');
    $appLocale = str_replace('_', '-', app()->getLocale());
    $ogLocale = str_replace('-', '_', $appLocale);
    $metaDescription = \Illuminate\Support\Str::limit(strip_tags($profileBio), 160);
    $ogDescription = \Illuminate\Support\Str::limit(strip_tags($profileBio), 200);
    $sameAs = collect($settings->social_links ?? [])
        ->pluck('url')
        ->filter(fn ($url) => filled($url) && filter_var($url, FILTER_VALIDATE_URL))
        ->values()
        ->all();
@endphp

<!DOCTYPE html>
<html lang="{{ $appLocale }}" prefix="og: https://ogp.me/ns#">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- SEO: Primary Meta Tags --}}
    <title>{{ $profileName }} — Links & Profil</title>
    <meta name="description" content="{{ $metaDescription }}">
    <meta name="author" content="{{ $profileName }}">
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1">
    <meta name="theme-color" content="{{ $themeColor }}">
    <meta name="application-name" content="{{ $siteName }}">

    {{-- SEO: Canonical URL --}}
    <link rel="canonical" href="{{ $currentUrl }}">
    <link rel="alternate" hreflang="{{ $appLocale }}" href="{{ $currentUrl }}">
    <link rel="alternate" hreflang="x-default" href="{{ $currentUrl }}">

    {{-- SEO: Sitemap --}}
    <link rel="sitemap" type="application/xml" href="{{ route('sitemap') }}">

    {{-- Open Graph / Facebook / WhatsApp --}}
    <meta property="og:type" content="profile">
    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:url" content="{{ $currentUrl }}">
    <meta property="og:title" content="{{ $profileName }}">
    <meta property="og:description" content="{{ $ogDescription }}">
    <meta property="og:image" content="{{ $ogImage }}">
    <meta property="og:image:secure_url" content="{{ $ogImage }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="Profil {{ $profileName }}">
    <meta property="og:locale" content="{{ $ogLocale }}">

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ $currentUrl }}">
    <meta name="twitter:title" content="{{ $profileName }}">
    <meta name="twitter:description" content="{{ $ogDescription }}">
    <meta name="twitter:image" content="{{ $ogImage }}">
    <meta name="twitter:image:alt" content="Profil {{ $profileName }}">

    {{-- Favicon & Apple Touch Icon --}}
    <link rel="icon" type="image/x-icon" href="{{ $avatarUrl ?? asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ $avatarUrl ?? asset('favicon.ico') }}">

    {{-- Performance: DNS Prefetch & Preconnect --}}
    <link rel="dns-prefetch" href="//fonts.googleapis.com">
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link rel="dns-prefetch" href="//cdnjs.cloudflare.com">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>

    {{-- Google Fonts (semua varian yang dibutuhkan tema) --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Lora:ital,wght@0,400;0,600;1,400&family=JetBrains+Mono:wght@400;500;600&family=Poppins:wght@400;500;600;700&family=Playfair+Display:wght@400;700;900&family=Roboto:wght@400;500;700&family=Raleway:wght@400;500;600;700&family=Ubuntu:wght@400;500;700&family=Quicksand:wght@400;500;600;700&family=Dancing+Script:wght@400;700&display=swap" rel="stylesheet">

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Stack: Halaman bisa push style tambahan --}}
    @stack('styles')

    {{-- Structured Data: JSON-LD (Schema.org ProfilePage) --}}
    @php
        $jsonLd = [
            '@context' => 'https://schema.org',
            '@type' => 'ProfilePage',
            'mainEntity' => array_filter([
                '@type' => 'Person',
                'name' => $profileName,
                'description' => $profileBio,
                'image' => $avatarUrl,
                'url' => url('/'),
                'sameAs' => $sameAs,
            ]),
            'url' => $currentUrl,
            'name' => $profileName,
            'inLanguage' => $appLocale,
            'isPartOf' => [
                '@type' => 'WebSite',
                'name' => $siteName,
                'url' => url('/'),
            ],
        ];
    @endphp
    <script type="application/ld+json">{!! json_encode($jsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}</script>

    @stack('head')
</head>
<body class="antialiased text-gray-800 bg-gray-50">
    {{ $slot }}

    @stack('scripts')
</body>
</html>