@php
    // --- PREPARE DYNAMIC STYLES ---
    $theme = $settings->theme_config ?? [];
    
    // Background Logic
    $bgType = $theme['background_type'] ?? 'flat';
    $bgStyle = '';
    $bgClass = '';
    $hasBgImage = false;
    
    if ($bgType === 'flat') {
        $bgStyle = "background-color: " . ($theme['background_color'] ?? '#f3f4f6');
    } elseif ($bgType === 'gradient') {
        if (($theme['background_gradient'] ?? '') === 'custom' && !empty($theme['background_gradient_style'])) {
            $bgStyle = $theme['background_gradient_style'];
        } else {
            $bgClass = $theme['background_gradient'] ?? 'bg-gradient-to-br from-gray-100 to-gray-300';
        }
    } elseif ($bgType === 'image' && !empty($theme['background_image'])) {
        $bgStyle = "background-image: url('" . asset('storage/'.$theme['background_image']) . "'); background-size: cover; background-position: center; background-attachment: fixed;";
        $hasBgImage = true;
    }

    // Font Logic - Map ke Google Fonts
    $fontFamily = $theme['font_family'] ?? 'font-sans';
    $fontClasses = [
        'font-sans' => 'font-inter',
        'font-serif' => 'font-lora',
        'font-mono' => 'font-jetbrains',
        'font-poppins' => 'font-poppins',
        'font-playfair' => 'font-playfair',
        'font-roboto' => 'font-roboto',
        'font-raleway' => 'font-raleway',
        'font-ubuntu' => 'font-ubuntu',
        'font-quicksand' => 'font-quicksand',
        'font-dancing' => 'font-dancing',
    ];
    $appliedFontClass = $fontClasses[$fontFamily] ?? 'font-inter';
    
    // Text Color Logic
    $globalTextColor = $theme['text_color'] ?? '#ffffff';
    $globalTextSecondary = $theme['text_secondary_color'] ?? '#e5e7eb';
    
    // Button Logic
    $btnStyle = $theme['button_style'] ?? 'rounded-md';
    $globalBtnBg = $theme['button_bg_color'] ?? '#ffffff';
    $globalBtnText = $theme['button_text_color'] ?? '#1f2937';
@endphp

{{-- Push page-specific font styles ke layout @stack('styles') --}}
@push('styles')
<style>
    .font-inter { font-family: 'Inter', sans-serif; }
    .font-lora { font-family: 'Lora', serif; }
    .font-jetbrains { font-family: 'JetBrains Mono', monospace; }
    .font-poppins { font-family: 'Poppins', sans-serif; }
    .font-playfair { font-family: 'Playfair Display', serif; }
    .font-roboto { font-family: 'Roboto', sans-serif; }
    .font-raleway { font-family: 'Raleway', sans-serif; }
    .font-ubuntu { font-family: 'Ubuntu', sans-serif; }
    .font-quicksand { font-family: 'Quicksand', sans-serif; }
    .font-dancing { font-family: 'Dancing Script', cursive; }
</style>
@endpush

<div class="min-h-screen w-full flex flex-col items-center py-12 px-4 transition-colors duration-500 {{ $bgClass }} {{ $appliedFontClass }}"
     style="{{ $bgStyle }}">

    {{-- Background Image Overlay (kontras teks) --}}
    @if($hasBgImage)
        <div class="absolute inset-0 bg-black/30 z-0 pointer-events-none" aria-hidden="true"></div>
    @endif

    {{-- Main Container --}}
        <main x-data="{ loaded: false }"
            x-init="$nextTick(() => loaded = true)"
          class="w-full max-w-lg mx-auto z-10 flex flex-col items-center"
          itemscope 
          itemtype="https://schema.org/Person">

        {{-- 1. PROFILE SECTION --}}
        <header x-cloak class="text-center mb-8 transition-all duration-1000 ease-out"
                :class="loaded ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6'">
            
            {{-- Avatar --}}
            @if($settings->avatar_url)
                <div class="relative w-28 h-28 mx-auto mb-4 group">
                    <img src="{{ asset('storage/'.$settings->avatar_url) }}" 
                         alt="Foto profil {{ $settings->profile_name }}"
                         itemprop="image"
                         loading="eager"
                         decoding="async"
                         width="112"
                         height="112"
                         class="w-full h-full rounded-full object-cover border-4 border-white/20 shadow-xl transition-transform duration-300 group-hover:scale-105">
                </div>
            @endif

            {{-- Nama --}}
            <h1 class="text-2xl font-bold tracking-tight drop-shadow-sm mb-2" 
                style="color: {{ $globalTextColor }}"
                itemprop="name">
                {{ $settings->profile_name }}
            </h1>

            {{-- Bio --}}
            @if($settings->profile_bio)
                <p class="text-sm font-medium opacity-90 max-w-xs mx-auto leading-relaxed" 
                   style="color: {{ $globalTextSecondary }}"
                   itemprop="description">
                    {{ $settings->profile_bio }}
                </p>
            @endif
        </header>

        {{-- 2. LINKS SECTION (Navigasi utama - penting untuk SEO) --}}
        <nav class="w-full space-y-4 flex flex-col" aria-label="Link profil {{ $settings->profile_name }}">
            @forelse($links as $index => $link)
                     <a href="{{ $link->url }}"
                   wire:click.prevent="handleClick({{ $link->id }})"
                   {!! $link->open_new_tab ? 'target="_blank" rel="noopener noreferrer"' : '' !!}
                   title="{{ $link->label }}"
                   aria-label="Kunjungi {{ $link->label }}"
                   itemprop="url"
                   class="group relative w-full p-4 flex items-center justify-center transition-all duration-500 ease-out transform hover:scale-[1.02] active:scale-95 shadow-md hover:shadow-lg backdrop-blur-sm {{ $btnStyle }}"
                   style="background-color: {{ $link->bg_color ?: $globalBtnBg }}; color: {{ $link->text_color ?: $globalBtnText }}; border: {{ ($btnStyle === 'border-2') ? '2px solid currentColor' : 'none' }};"
                         x-cloak
                   x-show="loaded"
                   x-transition:enter="transition ease-out duration-700"
                   x-transition:enter-start="opacity-0 translate-y-6"
                   x-transition:enter-end="opacity-100 translate-y-0"
                   :style="{ transitionDelay: ({{ $index }} * 75) + 'ms' }">
                    
                    {{-- Icon/Image Left --}}
                    @if($link->image_url)
                        {{-- Custom Image --}}
                        <span class="absolute left-4 transition-transform group-hover:scale-110" aria-hidden="true">
                            <img src="{{ asset('storage/'.$link->image_url) }}" 
                                 alt="{{ $link->label }}"
                                 class="w-8 h-8 rounded-{{ $btnStyle === 'rounded-full' ? 'full' : ($btnStyle === 'rounded-md' ? 'md' : 'none') }} object-cover"
                                 loading="lazy">
                        </span>
                    @elseif($link->icon)
                        {{-- Font Awesome Icon --}}
                        <span class="absolute left-4 text-2xl opacity-80 transition-transform group-hover:rotate-12" aria-hidden="true">
                            <i class="fa-fw {{ $link->icon }}"></i>
                        </span>
                    @endif

                    {{-- Label --}}
                    <span class="font-semibold text-center truncate px-8">
                        {{ $link->label }}
                    </span>

                    {{-- External Link Indicator (hover) --}}
                    <span class="absolute right-4 opacity-0 -translate-x-2 group-hover:opacity-100 group-hover:translate-x-0 transition-all duration-300" aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                    </span>
                </a>
            @empty
                <div class="text-center py-8 opacity-50 font-mono text-sm " 
                     style="color: {{ $globalTextSecondary }}"
                     role="status">
                    Belum ada link yang ditambahkan.
                </div>
            @endforelse
        </nav>

        {{-- 3. SOCIAL LINKS --}}
        @if(!empty($settings->social_links))
                <footer x-cloak class="mt-12 flex justify-center gap-6 transition-all duration-1000 delay-300"
                    :class="loaded ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'"
                    aria-label="Media sosial">
                @foreach($settings->social_links as $social)
                    <a href="{{ $social['url'] }}" 
                       target="_blank"
                       rel="noopener noreferrer me"
                       title="{{ $social['platform'] ?? 'Social Media' }}"
                       aria-label="Kunjungi {{ $social['platform'] ?? 'Social Media' }}"
                       itemprop="sameAs"
                       class="text-2xl hover:scale-110 transition-transform duration-300 hover:opacity-80 drop-shadow-md"
                       style="color: {{ $social['color'] ?? $globalBtnText }}">
                        <i class="fa-fw {{ $social['icon'] ?? 'fas fa-link' }}" aria-hidden="true"></i>
                    </a>
                @endforeach
            </footer>
        @endif
        
        {{-- Branding --}}
        <p class="mt-8 text-xs font-light opacity-80" style="color: {{ $globalTextSecondary }}">
            Powered by <span itemprop="brand">{{ config('app.name') }}</span>
        </p>

    </main>
</div>