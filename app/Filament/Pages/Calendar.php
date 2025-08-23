<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class Calendar extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static string $view = 'filament.pages.calendar';
    protected static ?string $navigationLabel = 'Calendar';
    protected static ?string $title = 'Calendar';

    public static ?string $navigationGroup = 'Calendar';

    protected static ?int $navigationSort = 4;
}
