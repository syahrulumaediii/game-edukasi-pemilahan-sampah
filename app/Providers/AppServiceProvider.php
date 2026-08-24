<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if (
            (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') ||
            (request()->header('x-forwarded-proto') === 'https') ||
            str_contains(request()->header('host') ?? '', 'ngrok')
        ) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
    }
}
