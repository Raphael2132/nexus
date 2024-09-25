<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\ParametrosSisModulo;

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

        Gate::define('is_par_faturamento', function ($user) {

            $modulos = ParametrosSisModulo::where('modulo_empresa_codigo', $user->usuario_empresa)->first();
            
            if($user->usuario_acesso_pararametros == 'S' && ($modulos->modulo_emissao_nfs == 'S' || $modulos->modulo_emissao_nfs_simp == 'S')){
                return true;
            }else{
                return false;
            }
        });

        Gate::define('is_par_servico', function ($user) {

            $modulos = ParametrosSisModulo::where('modulo_empresa_codigo', $user->usuario_empresa)->first();
            
            if($user->usuario_acesso_pararametros == 'S' && ($modulos->modulo_servico == 'S' || $modulos->modulo_emissao_nfs_simp == 'S')){
                return true;
            }else{
                return false;
            }
        });

        Gate::define('is_mod_servico', function ($user) {

            $modulos = ParametrosSisModulo::where('modulo_empresa_codigo', $user->usuario_empresa)->first();
            
            return $modulos->modulo_servico == 'S'
                        ? true
                        : false;
        });

        Gate::define('is_mod_nfs', function ($user) {

            $modulos = ParametrosSisModulo::where('modulo_empresa_codigo', $user->usuario_empresa)->first();
            
            return $modulos->modulo_emissao_nfs == 'S'
                        ? true
                        : false;
        });

        Gate::define('is_mod_nfs_simp', function ($user) {

            $modulos = ParametrosSisModulo::where('modulo_empresa_codigo', $user->usuario_empresa)->first();
            
            return $modulos->modulo_emissao_nfs_simp == 'S'
                        ? true
                        : false;
        });

        Gate::define('is_emite_os', function ($user) {

            $modulos = ParametrosSisModulo::where('modulo_empresa_codigo', $user->usuario_empresa)->first();
            
            if($user->usuario_acesso_mod_servicos == 'S' && $modulos->modulo_servico == 'S'){
                return true;
            }else{
                return false;
            }
        });

        Gate::define('is_emite_nf', function ($user) {

            $modulos = ParametrosSisModulo::where('modulo_empresa_codigo', $user->usuario_empresa)->first();
            
            if($user->usuario_acesso_mod_nf == 'S' && ($modulos->modulo_emissao_nfs == 'S' || $modulos->modulo_emissao_nfs_simp == 'S')){
                return true;
            }else{
                return false;
            }
        });
    }
}
