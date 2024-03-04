<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::define('is_master', function ($user) {
            return $user->usuario_codigo == 'MASTER'
                        ? true
                        : false;
        });

        Gate::define('is_parameter', function ($user) {
            if ($user->usuario_codigo == 'MASTER') {
                return true;
            }else{
                return $user->usuario_acesso_pararametros == 'S'
                            ? true
                            : false;
            }
        });

        Gate::define('is_register', function ($user) {
            return $user->usuario_acesso_cadastros == 'S'
                        ? true
                        : false;
        });
    }
}
