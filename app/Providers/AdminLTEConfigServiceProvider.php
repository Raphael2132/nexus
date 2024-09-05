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

                    // Obter o nome do host do servidor
                    if(!empty($_SERVER['SERVER_NAME'])){
                        $serverName = $_SERVER['SERVER_NAME'];
                    }else{
                        $serverName = '';
                    }

                    // Verificar se está rodando no localhost
                    if ($serverName == '127.0.0.1' || stripos($serverName, 'localhost') !== false) {

                        $icoLogoPath = public_path($empresa->empresa_cnpj.'/file/img/'.$empresa->empresa_codigo.'_logo_ico.png');

                        if (file_exists($icoLogoPath)) {
                            config([
                                //Admin Panel Logo
                                'adminlte.logo_img' => $empresa->empresa_cnpj.'/file/img/'.$empresa->empresa_codigo.'_logo_ico.png',
                            ]);
                        }

                    } else {

                        $icoLogoPath = '/home/'.$empresa->empresa_cnpj.'/file/img/'.$empresa->empresa_codigo.'_logo_ico.png';

                        if (file_exists($icoLogoPath)) {

                            $logoIcoUrl = route('logoIco.file', ['cnpj' => $empresa->empresa_cnpj, 'filename' => $empresa->empresa_codigo . '_logo_ico.png']);

                            config([
                                //Admin Panel Logo
                                'adminlte.logo_img' => $logoIcoUrl,
                            ]);
                        }
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
