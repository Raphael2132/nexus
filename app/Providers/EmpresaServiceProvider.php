<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\EmpresaService;

class EmpresaServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton('empresa', function ($app) {
            return new EmpresaService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
