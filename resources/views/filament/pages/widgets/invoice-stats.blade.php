<x-filament-widgets::widget class="fi-wi-invoice-stats">
    <div style="display: flex; align-items: center; width: 100%;">

        <x-filament::section
            style="background-color: white; width: 200px; flex-shrink: 0;"
        >
            <div>
                <div class="text-sm font-medium text-gray-500">
                    月間合計稼働時間
                </div>

                <div class="text-2xl font-bold mt-1">
                    {{ $workingHours }}
                </div>
            </div>
        </x-filament::section>

        <x-filament::section
            style="background-color: white; width: 200px; flex-shrink: 0; margin-left: 20px;"
        >
            <div>
                <div class="text-sm font-medium text-gray-500">
                    月間合計休憩時間
                </div>

                <div class="text-2xl font-bold mt-1">
                    {{ $breakHours }}
                </div>
            </div>
        </x-filament::section>

    </div>
</x-filament-widgets::widget>
