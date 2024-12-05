<?php

namespace App\Providers;

use Filament\Support\Facades\FilamentColor;
use Filament\Support\Colors\Color;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        FilamentColor::register([
            'blue' => Color::hex('#0ea5e9'),
            'pink' => Color::hex('#f9429e'),
            'red' => Color::hex('#FF0000'),
            'green' => Color::hex('#00FF00'),
            'orange' => Color::hex('#FFA500'),
        ]);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
