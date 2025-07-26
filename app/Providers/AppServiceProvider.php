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

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Set application locale based on session
        if (session()->has('locale')) {
            app()->setLocale(session('locale'));
        } else {
            // Set default locale to English
            app()->setLocale('en');
            session(['locale' => 'en']);
        }
    }
}
