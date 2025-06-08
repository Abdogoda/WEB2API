<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Blade::if('hasPermissionTo', function (string $permission) {
            return Auth::user() && Auth::user()->hasPermissionTo($permission);
        });
    }

}
