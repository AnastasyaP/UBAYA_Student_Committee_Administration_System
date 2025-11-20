<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;

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
        // cuma klo di environment local, nga pas production
        // if (app()->environment('local')) {
        //     $sessionPath = storage_path('framework/sessions');
        //     if (File::exists($sessionPath)) {
        //         File::cleanDirectory($sessionPath);
        //     }
        // }
    }
}
