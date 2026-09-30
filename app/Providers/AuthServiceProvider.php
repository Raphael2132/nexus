<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\Parametros\Sistema\ParametrosSisModulo;

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
        //Verifica se é usuario MASTER
        Gate::define('is_master', function ($user) {
            return $user->usuario_codigo == 'MASTER' ? true : false;
        });

        //Verifica se o usuario acessa os Parametros Gerais do Sistema
        Gate::define('is_parameter', function ($user) {
            if ($user->usuario_codigo == 'MASTER') {
                return true;
            }else{
                return $user->usuario_acesso_pararametros == 'S' ? true : false;
            }
        });

        //Verifica se o Usuario acessa os Cadastros do Sistema
        Gate::define('is_register', function ($user) {
            return $user->usuario_acesso_cadastros == 'S' ? true : false;
        });

        //Verifica se a Empresa cadastra Prestadores
        Gate::define('is_register_prestador', function ($user) {
            if($user->can('is_register')){
                $modulos = ParametrosSisModulo::where('modulo_empresa_codigo', $user->usuario_empresa)->first();

                if($modulos->modulo_servico == 'S'){
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        });

        //Verifica se a empresa vai acessar os Parâmetros de Faturamento
        Gate::define('is_par_faturamento', function ($user) {

            $modulos = ParametrosSisModulo::where('modulo_empresa_codigo', $user->usuario_empresa)->first();
            
            if($modulos->modulo_emissao_nfs == 'S' || $modulos->modulo_emissao_nfs_simp == 'S'){
                return true;
            }else{
                return false;
            }
        });

        //Verifica se a empresa vai acessar os Parâmetros de Serviço
        Gate::define('is_par_servico', function ($user) {

            $modulos = ParametrosSisModulo::where('modulo_empresa_codigo', $user->usuario_empresa)->first();
            
            if($modulos->modulo_servico == 'S' || $modulos->modulo_emissao_nfs_simp == 'S'){
                return true;
            }else{
                return false;
            }
        });

        //Verifica se a Empresa usa o Módulo de Serviço
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

        //Verifica se a Empresa emite OS e se o Usuário acessa o Módulo
        Gate::define('is_acessa_mod_servico', function ($user) {

            $modulos = ParametrosSisModulo::where('modulo_empresa_codigo', $user->usuario_empresa)->first();
            
            //Verifica se a Empresa acessa Módulo de Serviço
            if($modulos->modulo_servico == 'S'){

                //Verifica se o Usuário acessa o Módulo de Serviço sendo Lançamento de OS ou Controle de Produção
                if($user->usuario_acesso_mod_servicos == 'S' || $modulos->usuario_acesso_mod_cont_prod == 'S'){
                    return true;
                }else{
                    return false;
                }

            }else{
                return false;
            }
        });

        //Verifica se o Usuário pode Lançar OS
        Gate::define('is_lancamento_os', function ($user) {

            if($user->usuario_acesso_mod_servicos == 'S'){
                return true;
            }else{
                return false;
            }
        });

        //Verifica se o Usuário pode Lançar OS
        Gate::define('is_controle_producao', function ($user) {

            if($user->usuario_acesso_mod_cont_prod == 'S'){
                return true;
            }else{
                return false;
            }
        });

        //Verifica se a Empresa Fatura NFS ou faz Emissão Simplificada de NFS e se o Usuário acessa esses Módulos
        Gate::define('is_acessa_mod_nf', function ($user) {

            $modulos = ParametrosSisModulo::where('modulo_empresa_codigo', $user->usuario_empresa)->first();
            
            if($modulos->modulo_emissao_nfs == 'S' || $modulos->modulo_emissao_nfs_simp == 'S'){

                if($user->usuario_acesso_mod_nf == 'S' || $user->usuario_acesso_mod_nf_simp == 'S'){
                    return true;
                }else{
                    return false;
                }

            }else{
                return false;
            }
        });

        //Verifica se o Usuário faz Emissão Simplificada de NF
        Gate::define('is_emissao_simp_nf', function ($user) {

            if($user->usuario_acesso_mod_nf_simp == 'S'){
                return true;
            }else{
                return false;
            }
        });

        //Verifica se o Usuário faz o Lançamento de NF
        Gate::define('is_emissao_nf', function ($user) {

            if($user->usuario_acesso_mod_nf == 'S'){
                return true;
            }else{
                return false;
            }
        });
    }
}
