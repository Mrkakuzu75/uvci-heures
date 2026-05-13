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
        $this->app->singleton(\App\Services\VolumeHoraireService::class);
    }

    /**
     
     */
    public function boot(): void
    {
        //
    }
}
