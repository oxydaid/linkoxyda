<x-filament::widget>
    <x-filament::section>
        <div class="space-y-6">
            
            {{-- Profile Header Section --}}
            <div class="flex flex-col items-center text-center">
                {{-- Foto Profil --}}
                <div class="relative mb-4">
                    @if($settings?->avatar_url)
                        <img src="{{ asset('storage/'.$settings->avatar_url) }}" 
                             class="w-20 h-20 rounded-full object-cover border-4 border-primary-500 shadow-md">
                    @else
                        <div class="w-20 h-20 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center border-4 border-gray-300 dark:border-gray-600">
                            <span class="text-2xl text-gray-400">?</span>
                        </div>
                    @endif
                </div>

                {{-- Nama & Bio --}}
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                    {{ $settings?->profile_name ?? 'Belum ada Nama' }}
                </h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                    {{ $settings?->profile_bio ?? 'Silakan atur profil Anda di menu Settings' }}
                </p>
            </div>

            {{-- Divider --}}
            <div class="border-t border-gray-200 dark:border-gray-700"></div>

            {{-- QR Code & Link Section --}}
            <div class="space-y-3">
                <div class="flex items-start justify-center gap-3">
                    {{-- QR Code --}}
                    <div class="flex-shrink-0 bg-white dark:bg-gray-800 p-1.5 rounded-lg border border-gray-200 dark:border-gray-700">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=120x120&data={{ url('/') }}" 
                             alt="QR Code" 
                             class="w-[120px] h-[120px] rounded">
                    </div>

                    {{-- Link Info --}}
                    <div class="flex flex-col justify-center">
                        <span class="text-xs uppercase font-semibold text-gray-500 dark:text-gray-400 tracking-wide mb-2">Public Link</span>
                        
                        <a href="{{ url('/') }}" target="_blank" 
                           class="text-sm font-medium text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 hover:underline break-all mb-3">
                            {{ str_replace('https://', '', url('/')) }}
                        </a>

                        {{-- Action Buttons --}}
                        <div class="flex gap-2">
                            <x-filament::button
                                tag="a"
                                href="{{ url('/') }}"
                                target="_blank"
                                size="sm"
                                icon="heroicon-m-arrow-top-right-on-square"
                                icon-position="before"
                                class="flex-1"
                            >
                                Buka
                            </x-filament::button>

                            {{-- Tombol Copy --}}
                            <button
                                type="button"
                                class="flex-1 px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition flex items-center justify-center gap-1"
                                @click="
                                    navigator.clipboard.writeText('{{ url('/') }}');
                                    const notif = document.createElement('div');
                                    notif.className = 'fixed top-5 right-5 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg text-sm z-50';
                                    notif.textContent = 'Link disalin!';
                                    document.body.appendChild(notif);
                                    setTimeout(() => notif.remove(), 2000);
                                "
                            >
                                📋 Salin
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Settings Link --}}
            <div class="pt-2 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('filament.admin.pages.app-settings') }}" 
                   class="text-xs text-center block text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition">
                    ⚙️ Ubah profil di Settings
                </a>
            </div>

        </div>
    </x-filament::section>
</x-filament::widget>