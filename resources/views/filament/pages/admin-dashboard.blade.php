<x-filament-panels::page>
    <div class="grid gap-6 md:grid-cols-2">

        <a
            href="{{ \App\Filament\Resources\Users\UserResource::getUrl('index', [], 'admin') }}"
            class="rounded-xl bg-white p-6 shadow transition hover:shadow-md"
        >
            <div class="text-xl font-bold">
                ユーザー管理
            </div>

            <p class="mt-2 text-sm text-gray-500">
                ユーザーの登録・編集・権限管理
            </p>
        </a>

        <a
            href="{{ \App\Filament\Resources\Attendances\AttendanceResource::getUrl('index', [], 'admin') }}"
            class="rounded-xl bg-white p-6 shadow transition hover:shadow-md"
        >
            <div class="text-xl font-bold">
                勤怠管理
            </div>

            <p class="mt-2 text-sm text-gray-500">
                全ユーザーの勤怠を確認・編集
            </p>
        </a>

    </div>
</x-filament-panels::page>