<x-filament-panels::page>
    <form wire:submit="submit" class="">
        {{ $this->form }}

        <div class="mt-8 flex justify-end gap-3 pt-6 border-gray-200">
            <x-filament::button type="submit">
                Simpan Perubahan
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>