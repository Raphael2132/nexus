<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\ModulosService;
use Illuminate\Support\Facades\Gate;

class ModulosServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton('modulos', function ($app) {
            return new ModulosService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        /*Gate::define('is_modulo_nfs', function ($modulos) {

            $modulo = $modulos->modulos;
            
            return $modulo->modulo_emissao_nfs == 'S'
                        ? true
                        : false;
        });*/
    }
}
