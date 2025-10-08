<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Illuminate\Support\Facades\Route;
use Filament\Facades\Filament;
use Filament\Panel;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
         Livewire::setUpdateRoute(function ($handle) { return Route::post('/facturas/public/livewire/update', $handle); }); 
         Livewire::setScriptRoute(function ($handle) { return Route::get('/facturas/public/livewire/livewire.js', $handle); });
    }
}
