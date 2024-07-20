<?php

namespace App\Providers;
use Illuminate\Support\Facades\URL;    //この行を追加
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

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        URL::forceScheme('https');          //この行を追加
    }
}
