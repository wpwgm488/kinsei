<x-filament-panels::page>

    <x-filament::section>

        <x-slot name="heading">
            顧客登録
        </x-slot>

        <p class="mb-6 text-sm text-gray-500">
            請求書を発行する顧客の情報を登録してください。
        </p>

        {{ $this->form }}

        <div class="mt-6 flex justify-end">
            <x-filament::button
                wire:click="save"
                icon="heroicon-o-check"
            >
                顧客を登録
            </x-filament::button>
        </div>

    </x-filament::section>

</x-filament-panels::page>
