<x-filament-panels::page>
    {{-- 請求設定 --}}
    <div class="max-w-5xl">
        <x-filament::section>
            <x-slot name="heading">請求設定</x-slot>
            <form wire:submit="save" id="invoice-settings-form">
                {{ $this->form }}
                <div class="mt-4"><x-filament::button type="submit">保存</x-filament::button></div>
            </form>
        </x-filament::section>
    </div>

    {{-- 請求書プレビュー --}}
    <x-filament::section class="mt-6">
        <x-slot name="heading">請求書プレビュー</x-slot>

        <div class="mx-auto max-w-5xl bg-white text-sm shadow-sm ring-1 ring-gray-200" style="padding: 48px;">
            {{-- タイトル --}}
            <div class="mb-8 text-center">
                <h1 class="text-xl font-bold tracking-widest">御 請 求 書</h1>
            </div>

            {{-- 請求先 --}}
            <div class="mb-6">
                <div class="text-lg font-semibold">請求先会社名<span class="ml-1">御中</span></div>

                <div class="mt-2">{{ now()->format('Y/m') }}月分の作業<br>下記の通り、ご請求申し上げます。</div>

                {{-- 請求情報 --}}
                <div class="mt-3" style="width: 100%; display: flex; justify-content: flex-end;">
                    <div style="display: grid; grid-template-columns: 150px 20px auto; text-align: left;">
                        <div>請求番号</div><div>：</div><div>000220260003</div>
                        <div class="mt-1">請求日</div><div class="mt-1">：</div><div class="mt-1">{{ now()->format('Y/m/d') }}</div>
                        <div class="mt-1">登録番号</div><div class="mt-1">：</div><div class="mt-1">T</div>
                        <div class="mt-1">請求元</div><div class="mt-1">：</div><div class="mt-1">ユーザープロフィールから設定</div>
                        <div class="mt-1">住所</div><div class="mt-1">：</div><div class="mt-1">ユーザープロフィールから設定</div>
                        <div class="mt-1">電話番号</div><div class="mt-1">：</div><div class="mt-1">ユーザープロフィールから設定</div>
                        <div class="mt-4 font-bold">合計金額（税込）</div><div class="mt-4 font-bold">：</div><div class="mt-4 font-bold">¥ 880,000</div>
                        <div class="mt-1">お支払期限</div><div class="mt-1">：</div><div class="mt-1">{{ now()->addMonth()->format('Y/m/d') }}</div>
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
                            <td class="px-3 py-3" style="border: 1px solid #9ca3af;">1.サイトデザインおよび保守<br>2.1.に関わる付随業務</td>
                            <td class="px-3 py-3 text-center" style="border: 1px solid #9ca3af;">月額</td>
                            <td class="px-3 py-3 text-center" style="border: 1px solid #9ca3af;">160時間</td>
                            <td class="px-3 py-3 text-right" style="border: 1px solid #9ca3af;">¥ 800,000</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-3 text-center" style="border: 1px solid #9ca3af;">1</td>
                            <td class="px-3 py-3" style="border: 1px solid #9ca3af;"></td>
                            <td class="px-3 py-3 text-center" style="border: 1px solid #9ca3af;">精算・上下割</td>
                            <td class="px-3 py-3 text-center" style="border: 1px solid #9ca3af;">1時間</td>
                            <td class="px-3 py-3 text-right" style="border: 1px solid #9ca3af;">- ¥ 1,000</td>
                        </tr>
                    </tbody>
                </table>

                <div class="ml-auto max-w-sm" style="border-left: 1px solid #9ca3af; border-right: 1px solid #9ca3af; border-bottom: 1px solid #9ca3af;">
                    <div class="flex justify-between px-3 py-2" style="border-bottom: 1px solid #9ca3af;"><span>小計</span><span>¥ 800,000</span></div>
                    <div class="flex justify-between px-3 py-2" style="border-bottom: 1px solid #9ca3af;"><span>消費税(10%)</span><span>¥ 80,000</span></div>
                    <div class="flex justify-between px-3 py-3 text-lg font-bold"><span>合計</span><span>¥ 880,000</span></div>
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
                        <div>銀行名：</div>
                        <div>支店名：</div>
                        <div>科目：</div>
                        <div>口座番号：</div>
                        <div>口座名義：</div>
                    </div>
                </div>
            </div>
        </div>
    </x-filament::section>

<script>
document.addEventListener('livewire:init', () => {
    const fields = [
        'hourly_rate',
        'monthly_rate',
        'settlement_lower_hours',
        'settlement_upper_hours',
        'overtime_unit_price',
        'deduction_unit_price',
    ];

    const getInputs = (field) => {
        return document.querySelectorAll(`input[name*="[${field}]"]`);
    };

    const normalizeDigits = (value) => {
        return value
            .replace(/[０-９]/g, (char) => {
                return String.fromCharCode(char.charCodeAt(0) - 0xfee0);
            })
            .replace(/[^0-9]/g, '');
    };

    const formatNumber = (value) => {
        const digits = normalizeDigits(value);

        if (digits === '') {
            return '';
        }

        return Number(digits).toLocaleString('ja-JP');
    };

    const updateInput = (input) => {
        const formatted = formatNumber(input.value);

        if (input.value !== formatted) {
            input.value = formatted;
        }

        input.dispatchEvent(new Event('change', {
            bubbles: true,
        }));
    };

    const setupInputs = () => {
        fields.forEach((field) => {
            getInputs(field).forEach((input) => {
                if (input.dataset.numberFormatInitialized) {
                    return;
                }

                input.dataset.numberFormatInitialized = 'true';
                input.type = 'text';
                input.setAttribute('inputmode', 'numeric');

                input.addEventListener('input', () => {
                    updateInput(input);
                });

                input.addEventListener('blur', () => {
                    updateInput(input);
                });
            });
        });
    };

    const formatAllInputs = () => {
        fields.forEach((field) => {
            getInputs(field).forEach((input) => {
                if (input.value !== '') {
                    input.value = formatNumber(input.value);
                }
            });
        });
    };

    setupInputs();
    formatAllInputs();

    Livewire.hook('morph.updated', () => {
        setupInputs();
        formatAllInputs();
    });

    const form = document.getElementById('invoice-settings-form');

    if (form) {
        form.addEventListener('submit', () => {
            fields.forEach((field) => {
                getInputs(field).forEach((input) => {
                    input.value = normalizeDigits(input.value);

                    input.dispatchEvent(new Event('change', {
                        bubbles: true,
                    }));
                });
            });
        }, true);
    }
});
</script>
</x-filament-panels::page>