<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\DB;

class InicializacaoFinanceiraAfterLogin
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
        DB::beginTransaction();

        try {
            $data = now()->toDateString();

            $exec_fn = DB::select(
                "SELECT ret_sts_pro001, ret_msg_pro001
                 FROM fn_financeiro_pro001(:empresa, :data, :usuario, :app)",
                [
                    'empresa' => $event->user->usuario_empresa,
                    'data'    => $data,
                    'usuario' => $event->user->usuario_codigo,
                    'app'     => 'LoginSistema',
                ]
            );

            if (!empty($exec_fn) && $exec_fn[0]->ret_sts_pro001 === '*') {

                DB::rollBack();

                Log::error('Falha na inicialização financeira no login', [
                    'user' => $event->user->usuario_codigo,
                    'msg'  => $exec_fn[0]->ret_msg_pro001 ?? 'Sem mensagem',
                ]);

                return; // não bloqueia login
            }

            DB::commit();

        } catch (\Throwable $e) {

            DB::rollBack();

            Log::critical('Erro inesperado na inicialização financeira no login', [
                'user' => $event->user->usuario_codigo ?? null,
                'erro' => $e->getMessage(),
            ]);
        }
    }
}
