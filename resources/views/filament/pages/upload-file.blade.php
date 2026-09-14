<x-filament-panels::page>
    <x-filament-panels::form wire:submit="submit">
        {{ $this->form }}

        <x-filament-panels::form-actions>
            <x-filament::button type="submit" size="lg" color="success" icon="heroicon-o-arrow-up-circle">
                Upload Files
            </x-filament::button>
        </x-filament-panels::form-actions>
    </x-filament-panels::form>
</x-filament-panels::page>
