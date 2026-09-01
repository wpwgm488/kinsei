<?php

namespace App\Filament\Pages;

use App\Models\Invoice;
use App\Models\User;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;

class InvoiceSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $title = '請求設定';
    protected static ?string $navigationLabel = '請求設定';
    protected static ?int $navigationSort = 6;
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected string $view = 'filament.pages.invoice-settings';

    public ?array $data = [];
    public ?string $previewInvoiceNumber = null;

    public function mount(): void
    {
        $user = auth()->user();

        $this->previewInvoiceNumber = $this->generateInvoiceNumber();

        $this->form->fill([
            'summary_main' => null,
            'summary_contract' => null,
            'billing_type' => $user->billing_type,
            'price_tax_type' => $user->price_tax_type,
            'hourly_rate' => $this->formatNumber($user->hourly_rate),
            'monthly_rate' => $this->formatNumber($user->monthly_rate),
            'settlement_lower_hours' => $this->formatNumber($user->settlement_lower_hours),
            'settlement_upper_hours' => $this->formatNumber($user->settlement_upper_hours),
            'overtime_unit_price' => $this->formatNumber($user->overtime_unit_price),
            'deduction_unit_price' => $this->formatNumber($user->deduction_unit_price),
            'bank_account_id' => null,
            'settlement_method' => null,
        ]);
    }

public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Textarea::make('summary_contract')
                    ->label('摘要(契約内容)')
                    ->rows(2)
                    ->columnSpanFull(),

                Select::make('billing_type')
                    ->label('請求方式(摘要補足(時給/月額・精算))')
                    ->options([
                        'hourly' => '時給',
                        'monthly' => '月額・精算',
                    ])
                    ->required()
                    ->live(),

                Select::make('settlement_method')
                    ->label('精算方式(摘要補足(精算))')
                    ->options([
                        'upper_lower' => '上下割',
                        'middle' => '中割',
                    ])
                    ->helperText('上限・下限それぞれで単価を算出する「上下割」と、中間値で単価を算出する「中割」の2種類があります。')
                    ->visible(fn ($get): bool => $get('billing_type') === 'monthly')
                    ->required(fn ($get): bool => $get('billing_type') === 'monthly'),

                Select::make('price_tax_type')
                    ->label('単価の税区分')
                    ->options([
                        'exclusive' => '税抜',
                        'inclusive' => '税込',
                    ])
                    ->required(),

                TextInput::make('hourly_rate')
                    ->label('時間単価')
                    ->inputMode('numeric')
                    ->suffix('円 / 時間')
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state === null || $state === '') {
                            $set('hourly_rate', null);
                            return;
                        }
                        $normalized = mb_convert_kana($state, 'n');
                        $digits = preg_replace('/[^0-9]/', '', $normalized);
                        $set('hourly_rate', $digits === '' ? null : number_format((int) $digits));
                    })
                    ->rules([
                        'nullable',
                        function ($attribute, $value, $fail) {
                            $value = (string) $value;
                            if ($value === '') {
                                return;
                            }
                            $digits = preg_replace('/[^0-9]/', '', mb_convert_kana($value, 'n'));
                            if (!ctype_digit($digits)) {
                                $fail('数字を入力してください。');
                                return;
                            }
                            if ((int) $digits > 999999999) {
                                $fail('999,999,999以下で入力してください。');
                            }
                        },
                    ])
                    ->visible(fn ($get): bool => $get('billing_type') === 'hourly')
                    ->dehydratedWhenHidden(true),

                TextInput::make('monthly_rate')
                    ->label('月額単価')
                    ->inputMode('numeric')
                    ->suffix('円 / 月')
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state === null || $state === '') {
                            $set('monthly_rate', null);
                            return;
                        }
                        $normalized = mb_convert_kana($state, 'n');
                        $digits = preg_replace('/[^0-9]/', '', $normalized);
                        $set('monthly_rate', $digits === '' ? null : number_format((int) $digits));
                    })
                    ->rules([
                        'nullable',
                        function ($attribute, $value, $fail) {
                            $value = (string) $value;
                            if ($value === '') {
                                return;
                            }
                            $digits = preg_replace('/[^0-9]/', '', mb_convert_kana($value, 'n'));
                            if (!ctype_digit($digits)) {
                                $fail('数字を入力してください。');
                                return;
                            }
                            if ((int) $digits > 999999999) {
                                $fail('999,999,999以下で入力してください。');
                            }
                        },
                    ])
                    ->visible(fn ($get): bool => $get('billing_type') === 'monthly')
                    ->dehydratedWhenHidden(true),

                TextInput::make('settlement_lower_hours')
                    ->label('精算下限')
                    ->inputMode('numeric')
                    ->suffix('時間')
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state === null || $state === '') {
                            $set('settlement_lower_hours', null);
                            return;
                        }
                        $normalized = mb_convert_kana($state, 'n');
                        $digits = preg_replace('/[^0-9]/', '', $normalized);
                        $set('settlement_lower_hours', $digits === '' ? null : number_format((int) $digits));
                    })
                    ->rules([
                        'nullable',
                        function ($attribute, $value, $fail) {
                            $value = (string) $value;
                            if ($value === '') {
                                return;
                            }
                            $digits = preg_replace('/[^0-9]/', '', mb_convert_kana($value, 'n'));
                            if (!ctype_digit($digits)) {
                                $fail('数字を入力してください。');
                                return;
                            }
                            if ((int) $digits > 32767) {
                                $fail('32,767以下で入力してください。');
                            }
                        },
                    ])
                    ->visible(fn ($get): bool => $get('billing_type') === 'monthly')
                    ->dehydratedWhenHidden(true),

                TextInput::make('settlement_upper_hours')
                    ->label('精算上限')
                    ->inputMode('numeric')
                    ->suffix('時間')
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state === null || $state === '') {
                            $set('settlement_upper_hours', null);
                            return;
                        }
                        $normalized = mb_convert_kana($state, 'n');
                        $digits = preg_replace('/[^0-9]/', '', $normalized);
                        $set('settlement_upper_hours', $digits === '' ? null : number_format((int) $digits));
                    })
                    ->rules([
                        'nullable',
                        function ($attribute, $value, $fail) {
                            $value = (string) $value;
                            if ($value === '') {
                                return;
                            }
                            $digits = preg_replace('/[^0-9]/', '', mb_convert_kana($value, 'n'));
                            if (!ctype_digit($digits)) {
                                $fail('数字を入力してください。');
                                return;
                            }
                            if ((int) $digits > 32767) {
                                $fail('32,767以下で入力してください。');
                            }
                        },
                    ])
                    ->visible(fn ($get): bool => $get('billing_type') === 'monthly')
                    ->dehydratedWhenHidden(true),

                TextInput::make('overtime_unit_price')
                    ->label('超過単価')
                    ->inputMode('numeric')
                    ->suffix('円 / 時間')
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state === null || $state === '') {
                            $set('overtime_unit_price', null);
                            return;
                        }
                        $normalized = mb_convert_kana($state, 'n');
                        $digits = preg_replace('/[^0-9]/', '', $normalized);
                        $set('overtime_unit_price', $digits === '' ? null : number_format((int) $digits));
                    })
                    ->rules([
                        'nullable',
                        function ($attribute, $value, $fail) {
                            $value = (string) $value;
                            if ($value === '') {
                                return;
                            }
                            $digits = preg_replace('/[^0-9]/', '', mb_convert_kana($value, 'n'));
                            if (!ctype_digit($digits)) {
                                $fail('数字を入力してください。');
                                return;
                            }
                            if ((int) $digits > 999999999) {
                                $fail('999,999,999以下で入力してください。');
                            }
                        },
                    ])
                    ->visible(fn ($get): bool => $get('billing_type') === 'monthly')
                    ->dehydratedWhenHidden(true),

                TextInput::make('deduction_unit_price')
                    ->label('控除単価')
                    ->inputMode('numeric')
                    ->suffix('円 / 時間')
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state === null || $state === '') {
                            $set('deduction_unit_price', null);
                            return;
                        }
                        $normalized = mb_convert_kana($state, 'n');
                        $digits = preg_replace('/[^0-9]/', '', $normalized);
                        $set('deduction_unit_price', $digits === '' ? null : number_format((int) $digits));
                    })
                    ->rules([
                        'nullable',
                        function ($attribute, $value, $fail) {
                            $value = (string) $value;
                            if ($value === '') {
                                return;
                            }
                            $digits = preg_replace('/[^0-9]/', '', mb_convert_kana($value, 'n'));
                            if (!ctype_digit($digits)) {
                                $fail('数字を入力してください。');
                                return;
                            }
                            if ((int) $digits > 999999999) {
                                $fail('999,999,999以下で入力してください。');
                            }
                        },
                    ])
                    ->visible(fn ($get): bool => $get('billing_type') === 'monthly')
                    ->dehydratedWhenHidden(true),

                Select::make('bank_account_id')
                    ->label('振込先')
                    ->options([])
                    ->placeholder('ユーザープロフィールから設定してください')
                    ->searchable(),
            ])
            ->columns([
                'default' => 1,
                'md' => 2,
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $this->form->validate();

        $data = $this->form->getState();

        /** @var User $user */
        $user = auth()->user();

        $user->update([
            'billing_type' => $data['billing_type'] ?? null,
            'price_tax_type' => $data['price_tax_type'] ?? null,
            'hourly_rate' => isset($data['hourly_rate']) ? (int) str_replace(',', '', $data['hourly_rate']) : null,
            'monthly_rate' => isset($data['monthly_rate']) ? (int) str_replace(',', '', $data['monthly_rate']) : null,
            'settlement_lower_hours' => isset($data['settlement_lower_hours']) ? (int) str_replace(',', '', $data['settlement_lower_hours']) : null,
            'settlement_upper_hours' => isset($data['settlement_upper_hours']) ? (int) str_replace(',', '', $data['settlement_upper_hours']) : null,
            'overtime_unit_price' => isset($data['overtime_unit_price']) ? (int) str_replace(',', '', $data['overtime_unit_price']) : null,
            'deduction_unit_price' => isset($data['deduction_unit_price']) ? (int) str_replace(',', '', $data['deduction_unit_price']) : null,
        ]);

        Notification::make()
            ->title('請求設定を保存しました')
            ->success()
            ->send();
    }

    private function formatNumber($value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        return number_format((int) $value);
    }

    private function parseNumber($value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }

        return (int) str_replace(',', '', $value);
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('保存')
                ->submit('save'),
        ];
    }

    private function generateInvoiceNumber(): string
    {
        $userNumber = str_pad((string) auth()->id(), 4, '0', STR_PAD_LEFT);
        $year = now()->format('Y');

        $count = Invoice::query()
            ->whereHas('customer', function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->whereYear('created_at', now()->year)
            ->count();

        $invoiceNumber = str_pad((string) ($count + 1), 4, '0', STR_PAD_LEFT);

        return $userNumber . $year . $invoiceNumber;
    }
}