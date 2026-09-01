<x-filament-panels::page>
    <form wire:submit="changePassword">
        {{ $this->form }}

        <div style="margin-top: 2rem; text-align: center;">
            <x-filament::button
                type="submit"
                wire:loading.attr="disabled"
            >
                パスワードを変更
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>