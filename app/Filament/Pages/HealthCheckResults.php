<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

use ShuvroRoy\FilamentSpatieLaravelHealth\Pages\HealthCheckResults as BaseHealthCheckResults;

class HealthCheckResults extends BaseHealthCheckResults
{
    protected static ?string $navigationIcon = 'heroicon-o-cpu-chip';

    protected static ?int $navigationSort = 999;
    public function getHeading(): string
    {
        return 'Health Check Results';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Core';
    }
}
