<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            // Adicionando rotas de parâmetros
            Route::middleware('web')
                ->group(base_path('routes/routeParametros.php'));

            // Adicionando rotas de utilidades do sistema
            Route::middleware('web')
                ->group(base_path('routes/routeUtility.php'));

            // Adicionando rotas de Cadastros
            Route::middleware('web')
                ->group(base_path('routes/routeCadastros.php'));

            // Adicionando rotas de Lançamentos
            Route::middleware('web')
                ->group(base_path('routes/routeLancamentos.php'));

            // Adicionando rotas do financeiro
            Route::middleware('web')
                ->group(base_path('routes/routeFinanceiro.php'));

            // Adicionando rotas do financeiro
            Route::middleware('web')
                ->group(base_path('routes/routeFaturamento.php'));
        });
    }
}
