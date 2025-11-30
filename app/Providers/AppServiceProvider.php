<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL; 

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
    public function boot()
    {
        
        
        // O específicamente para Railway:
        if (env('APP_ENV') === 'production' || strpos(env('APP_URL'), 'https') !== false) {
            URL::forceScheme('https');
        }
    }
}
