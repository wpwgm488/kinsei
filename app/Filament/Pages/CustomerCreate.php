<?php

namespace App\Filament\Pages;

use App\Models\Customer;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;

class CustomerCreate extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $title = '顧客登録';

    protected static ?string $navigationLabel = '顧客登録';
    protected static ?int $navigationSort = 99;

    protected static string|BackedEnum|null $navigationIcon =
        'heroicon-o-user-plus';

    protected string $view = 'filament.pages.customer-create';

    public ?array $data = [];

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('顧客名')
                    ->required()
                    ->maxLength(255),

                TextInput::make('postal_code')
                    ->label('郵便番号')
                    ->placeholder('000-0000')
                    ->maxLength(20),

                TextInput::make('address')
                    ->label('住所')
                    ->maxLength(255),

                TextInput::make('registration_number')
                    ->label('インボイス登録番号')
                    ->placeholder('T0000000000000')
                    ->maxLength(20),
            ])
            ->columns(2)
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        Customer::create([
            'user_id' => auth()->id(),
            'name' => $data['name'],
            'postal_code' => $data['postal_code'] ?? null,
            'address' => $data['address'] ?? null,
            'registration_number' => $data['registration_number'] ?? null,
        ]);

        Notification::make()
            ->title('顧客を登録しました')
            ->success()
            ->send();

        $this->redirect('/mypage/invoice');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('顧客を登録')
                ->submit('save'),
        ];
    }
}
