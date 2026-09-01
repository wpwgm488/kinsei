<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Pages\Page;

class About extends Page
{
    protected string $view = 'filament.pages.about';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-information-circle';

    protected static ?string $navigationLabel = 'About';

    protected static ?string $title = 'KinSeiについて';
}