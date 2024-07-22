<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use App\Facades\Empresa;
use Illuminate\Support\Facades\Storage;

class AdminLTEConfigServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        view()->composer('*', function ($view) {
            if (Auth::check()) {
                $empresa = Empresa::getEmpresa();

                if ($empresa) {
                    config([
                        //Title
                        'adminlte.title' => $empresa->empresa_nome,
                    ]);

                    $logoPath = public_path($empresa->empresa_cnpj.'/file/img/'.$empresa->empresa_codigo.'_logo_ico.png');

                    if (file_exists($logoPath)) {
                        config([
                            //Admin Panel Logo
                            'adminlte.logo_img' => $empresa->empresa_cnpj.'/file/img/'.$empresa->empresa_codigo.'_logo_ico.png',
                        ]);
                    }

                    if (!empty($empresa->empresa_nome_logo)) {
                        config([
                            //Admin Panel Logo
                            'adminlte.logo' => $empresa->empresa_nome_logo,
                        ]);
                    }
                }
            }
        });
    }
}
