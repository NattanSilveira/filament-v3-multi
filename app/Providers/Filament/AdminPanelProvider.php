<?php

namespace App\Providers\Filament;

use App\Filament\App\Pages\Subscription;
use Filament\Forms\Components\Field;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Tables\Columns\Column;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->bootUsing(function () {
                Field::configureUsing(function (Field $field) {
                    $field->translateLabel();
                });

                Column::configureUsing(function (Column $column) {
                    $column->translateLabel();
                });
            })
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->profile()
            ->colors([
                'primary' => Color::Amber,
            ])
//            ->userMenuItems([
////                if ($panel->getId() === 'admin') {
////            return $this->hasRole(config('filament-shield.super_admin.name'));
//                MenuItem::make()
//                    ->label('App Panel')
//                    ->visible(fn (): bool => auth()->user()->hasRole(config('filament-shield.super_admin.name')))
//                    ->icon('heroicon-o-home')
//                    ->url(url('/')),
//
//                MenuItem::make()
//                    ->label('Dashboard')
//                    ->visible(fn (): bool => auth()->user()->hasRole(config('filament-shield.super_admin.name')))
//                    ->icon('heroicon-o-home')
//                    ->url(url('/admin'))
//            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
                Subscription::class
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
//                Widgets\AccountWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->plugins([
                \BezhanSalleh\FilamentShield\FilamentShieldPlugin::make()
            ])
            ->databaseNotifications();
    }
}
