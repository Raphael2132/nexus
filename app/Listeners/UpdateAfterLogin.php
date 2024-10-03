<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class UpdateAfterLogin
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        $user = $event->user;
        
        //Vamos definir qual a Home Inicial que o usuário vai usar de acordo com o plano da empresa
        $empresaPlano = DB::table('parametros_sis_modulos')
            ->where('modulo_empresa_codigo', $user->usuario_empresa)
            ->value('modulo_plano');

        if ($empresaPlano == 'BS01') {
            $home = 'homeNFSeSimplificada';
        } elseif ($empresaPlano == 'BE02') {
            $home = 'homeNFSe';
        }else{
            $home = 'home'; 
        }

        // Definir valores padrão da sessão após login
        session([
            'glo_empresa_exibicao_home' => $user->usuario_empresa,
            'glo_tipo_home' => $home
        ]);
    }
}
