<x-filament-widgets::widget class="fi-wi-attendance-stats">
    <div style="display: flex; align-items: center; width: 100%;">

        {{-- 月間合計稼働時間 --}}
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

        {{-- 月間合計休憩時間 --}}
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

        {{-- 右端へ押し込む --}}
        <div style="margin-left: auto; display: flex; gap: 8px;">

            @if($isManage)
                <x-filament::button
                    color="danger"
                    size="sm"
                    style="min-width: 70px; white-space: nowrap;"
                >
                    却下
                </x-filament::button>
                <x-filament::button
                    color="primary"
                    size="sm"
                    style="min-width: 70px; white-space: nowrap;"
                >
                    承認
                </x-filament::button>



            @else
                <x-filament::button
                    color="gray"
                    size="sm"
                    style="min-width: 90px; white-space: nowrap;"
                >
                    キャンセル
                </x-filament::button>
                <x-filament::button
                    color="primary"
                    size="sm"
                    style="min-width: 70px; white-space: nowrap;"
                >
                    申請
                </x-filament::button>
            @endif

        </div>

    </div>
</x-filament-widgets::widget>