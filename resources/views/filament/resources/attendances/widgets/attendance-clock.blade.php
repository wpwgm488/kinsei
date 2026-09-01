<x-filament-widgets::widget>
    <div class="flex flex-col items-center gap-3 py-2">
        <div class="text-base font-medium">
            {{ $today->format('Y/m/d') }}
        </div>

        <div class="flex gap-2">
            <x-filament::button
                wire:click="clockIn"
                :disabled="$isWorking"
            >
                出勤
            </x-filament::button>

            <x-filament::button
                wire:click="clockOut"
                :disabled="! $isWorking"
            >
                退勤
            </x-filament::button>
        </div>

        <div class="text-base">
            {{ $timeText ?: '本日の勤怠なし' }}
        </div>
    </div>
</x-filament-widgets::widget>
