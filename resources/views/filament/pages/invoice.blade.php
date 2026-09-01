<x-filament-panels::page>

    <x-filament::section>

        <x-slot name="heading">
            請求書
        </x-slot>

        <p class="text-sm text-gray-500">
            {{ $monthLabel }} の請求書を作成できます。
        </p>

        <div class="mt-4">
            <x-filament::button
                tag="a"
                href="/mypage/customer-create"
                icon="heroicon-o-user-plus"
            >
                顧客を登録
            </x-filament::button>
        </div>

        <div class="mt-6 grid gap-4 md:grid-cols-2">

            @forelse ($customers as $customer)

                <div class="rounded-xl border border-gray-200 p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-base font-semibold">
                                {{ $customer->name }}
                            </div>

                            <div class="mt-1 text-sm text-gray-500">
                                {{ $monthLabel }}
                            </div>
                        </div>

                        <x-filament::badge color="warning">
                            未作成
                        </x-filament::badge>
                    </div>

                    <div class="mt-5 flex items-center justify-between">
                        <x-filament::button
                            tag="a"
                            href="/mypage/invoice-settings"
                            icon="heroicon-o-pencil-square"
                        >
                            請求書を作成
                        </x-filament::button>

                        <x-filament::button
                            tag="a"
                            color="gray"
                            href="{{ route('user.invoice.pdf', ['month' => $month]) }}"
                            icon="heroicon-o-document-arrow-down"
                        >
                            請求書PDFを出力
                        </x-filament::button>
                    </div>
                </div>

            @empty

                <div class="col-span-full rounded-xl border border-gray-200 p-6 text-center">
                    <p class="text-sm text-gray-500">
                        登録されている顧客がありません。
                    </p>
                </div>

            @endforelse

        </div>

    </x-filament::section>

    {{-- 請求書プレビュー --}}
    <x-filament::section class="mt-6">
        <x-slot name="heading">
            請求書プレビュー
        </x-slot>

        <div class="mx-auto max-w-5xl bg-white text-sm shadow-sm ring-1 ring-gray-200" style="padding: 48px;">
            {{-- タイトル --}}
            <div class="mb-8 text-center">
                <h1 class="text-xl font-bold tracking-widest">
                    御 請 求 書
                </h1>
            </div>

            {{-- 請求先・請求情報 --}}
            <div class="mb-6">
                <div class="text-lg font-semibold">
                    {{ $customers->first()?->name ?? '請求先会社名' }}<span class="ml-1">御中</span>
                </div>

                <div class="mt-2">{{ $monthLabel }}の作業<br>下記の通り、ご請求申し上げます。</div>

                <div class="mt-3" style="width: 100%; display: flex; justify-content: flex-end;">
                    <div style="display: grid; grid-template-columns: 150px 20px auto; text-align: left;">
                        <div>請求番号</div><div>：</div><div>{{ $invoice?->invoice_number ?? '0000000001' }}</div>
                        <div class="mt-1">請求日</div><div class="mt-1">：</div><div class="mt-1">{{ now()->format('Y/m/d') }}</div>
                        <div class="mt-1">登録番号</div><div class="mt-1">：</div><div class="mt-1">{{ $customers->first()?->registration_number ?? 'T' }}</div>
                        <div class="mt-1">請求元</div><div class="mt-1">：</div><div class="mt-1">{{ auth()->user()->name ?? 'ユーザープロフィールから設定' }}</div>
                        <div class="mt-1">住所</div><div class="mt-1">：</div><div class="mt-1">{{ auth()->user()->address ?? 'ユーザープロフィールから設定' }}</div>
                        <div class="mt-1">電話番号</div><div class="mt-1">：</div><div class="mt-1">{{ auth()->user()->phone_number ?? 'ユーザープロフィールから設定' }}</div>
                        <div class="mt-4 font-bold">合計金額（税込）</div><div class="mt-4 font-bold">：</div><div class="mt-4 font-bold">¥ {{ number_format($total ?? 0) }}</div>
                        <div class="mt-1">お支払期限</div><div class="mt-1">：</div><div class="mt-1">{{ $invoice?->due_date?->format('Y/m/d') ?? now()->addMonth()->format('Y/m/d') }}</div>
                    </div>
                </div>
            </div>

            {{-- 明細・集計・備考 --}}
            <div class="mt-12">
                <br>

                <table class="w-full border-collapse" style="border: 1px solid #9ca3af;">
                    <thead>
                        <tr style="background-color: #f9fafb;">
                            <th class="px-3 py-2" style="border: 1px solid #9ca3af;">NO</th>
                            <th class="px-3 py-2" style="border: 1px solid #9ca3af;">摘要</th>
                            <th class="px-3 py-2" style="border: 1px solid #9ca3af;">摘要補足<br>(時給/月額・精算)</th>
                            <th class="px-3 py-2" style="border: 1px solid #9ca3af;">合計作業時間</th>
                            <th class="px-3 py-2" style="border: 1px solid #9ca3af;">金額</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="px-3 py-3 text-center" style="border: 1px solid #9ca3af;">1</td>
                            <td class="px-3 py-3" style="border: 1px solid #9ca3af;">{{ $invoice?->summary_contract ?? '1.サイトデザインおよび保守<br>2.1.に関わる付随業務' }}</td>
                            <td class="px-3 py-3 text-center" style="border: 1px solid #9ca3af;">{{ $customers->first()?->billing_type === 'monthly' ? '月額' : '時給' }}</td>
                            <td class="px-3 py-3 text-center" style="border: 1px solid #9ca3af;">{{ $workingHours ?? 0 }}時間</td>
                            <td class="px-3 py-3 text-right" style="border: 1px solid #9ca3af;">¥ {{ number_format($subtotal ?? 0) }}</td>
                        </tr>
                    </tbody>
                </table>

                <div class="ml-auto max-w-sm" style="border-left: 1px solid #9ca3af; border-right: 1px solid #9ca3af; border-bottom: 1px solid #9ca3af;">
                    <div class="flex justify-between px-3 py-2" style="border-bottom: 1px solid #9ca3af;">
                        <span>小計</span>
                        <span>¥ {{ number_format($subtotal ?? 0) }}</span>
                    </div>

                    <div class="flex justify-between px-3 py-2" style="border-bottom: 1px solid #9ca3af;">
                        <span>消費税(10%)</span>
                        <span>¥ {{ number_format($tax ?? 0) }}</span>
                    </div>

                    <div class="flex justify-between px-3 py-3 text-lg font-bold">
                        <span>合計</span>
                        <span>¥ {{ number_format($total ?? 0) }}</span>
                    </div>
                </div>

                <br>

                <div class="mt-8" style="border: 1px solid #9ca3af;">
                    <div class="px-3 py-2 font-semibold" style="border-bottom: 1px solid #9ca3af; background-color: #f9fafb;">備考</div>
                    <div style="min-height: 80px; padding: 12px;"></div>
                </div>

                <br>

                <div class="mt-8" style="border: 1px solid #9ca3af;">
                    <div class="px-3 py-2 font-semibold" style="border-bottom: 1px solid #9ca3af; background-color: #f9fafb;">振込先</div>
                    <div style="padding: 16px;">
                        <div>銀行名：{{ auth()->user()->bank_name ?? '' }}</div>
                        <div>支店名：{{ auth()->user()->bank_branch ?? '' }}</div>
                        <div>科目：{{ auth()->user()->bank_account_type ?? '' }}</div>
                        <div>口座番号：{{ auth()->user()->bank_account_number ?? '' }}</div>
                        <div>口座名義：{{ auth()->user()->bank_account_holder ?? '' }}</div>
                    </div>
                </div>
            </div>
        </div>

    </x-filament::section>

</x-filament-panels::page>