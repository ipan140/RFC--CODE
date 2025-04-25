<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\AntaresService;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind AntaresService sebagai singleton
        $this->app->singleton(AntaresService::class, function ($app) {
            return new AntaresService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Paksa penggunaan HTTPS jika APP_URL berisi "https://"
        if (Str::contains(config('app.url'), 'https://')) {
            URL::forceScheme('https');
        }
    }
}
