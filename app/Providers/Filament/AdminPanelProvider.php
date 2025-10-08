<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Panel\Navigation\Layout;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
          ->login(\Filament\Pages\Auth\Login::class)
              ->favicon(asset('imagenes/logo2.png'))
             ->brandLogo(asset('imagenes/logo.png'))
            ->colors([
             'primary' => [
    '50' => '#E0F7F7',
    '100' => '#B3EAEA',
    '200' => '#80DDDD',
    '300' => '#4DCFCF',
    '400' => '#26C2C2',
    '500' => '#00B5B5', // color principal similar a #008080
    '600' => '#009E9E',
    '700' => '#008787',
    '800' => '#006F6F',
    '900' => '#005858',
],

            ])
    ->topNavigation()  
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                //Widgets\AccountWidget::class,
                  //\App\Filament\Widgets\ValorTotalPieChart::class,  
                  //\App\Filament\Widgets\TotalesPorMesWidget::class,
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
            ]);
    }
}
