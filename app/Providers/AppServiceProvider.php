<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

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
        // Stronger password policy for staff and supervisor accounts.
        Password::defaults(function () {
            return $this->app->environment('testing')
                ? Password::min(8)
                : Password::min(10)->mixedCase()->numbers()->symbols();
        });
    }
}
