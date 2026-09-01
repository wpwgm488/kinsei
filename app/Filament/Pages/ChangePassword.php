<?php

namespace App\Filament\Pages;

use Filament\Facades\Filament;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;

class ChangePassword extends Page
{
    protected static ?string $title = 'パスワード変更';

    protected static string|\BackedEnum|null $navigationIcon = null;

    protected static bool $shouldRegisterNavigation = false;

    protected string $view = 'filament.pages.change-password';

    public ?array $data = [];

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('current_password')
                    ->label('現在のパスワード')
                    ->password()
                    ->revealable()
                    ->required(),

                TextInput::make('password')
                    ->label('新しいパスワード')
                    ->password()
                    ->revealable()
                    ->required()
                    ->minLength(8),

                TextInput::make('password_confirmation')
                    ->label('新しいパスワード（確認）')
                    ->password()
                    ->revealable()
                    ->required()
                    ->same('password'),
            ])
            ->statePath('data');
    }

    public function changePassword(): void
    {
        $data = $this->form->getState();

        $guard = Filament::auth();

        $user = $guard->user();

        if (! $user) {
            $this->addError(
                'data.current_password',
                'ログイン情報を取得できません。'
            );

            return;
        }

        if (! Hash::check($data['current_password'], $user->password)) {
            $this->addError(
                'data.current_password',
                '現在のパスワードが正しくありません。'
            );

            return;
        }

        $user->update([
            'password' => Hash::make($data['password']),
        ]);

        $this->form->fill();

        Notification::make()
            ->title('パスワードを変更しました')
            ->success()
            ->send();
    }
}
