<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use stdClass;

class EmailEncerraOS extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $ordemServico;
    public $emailFrom;

    public function __construct($ordemServico, $emailFrom)
    {
        $this->ordemServico = $ordemServico;
        $this->emailFrom = $emailFrom;
    }

    public function build()
    {
        $os = str_pad($this->ordemServico->os_nos, 6, '0', STR_PAD_LEFT);
        $dadosEmp = DB::table('cadastro_empresas')->where('empresa_codigo', $this->ordemServico->os_emp)->first();

        return $this->from($this->emailFrom, $dadosEmp->empresa_nome)
                    ->subject('Encerramento da Ordem de Serviço Nº ' . $os)
                    ->view('email.emailEncerramentoOS')
                    ->with('ordemServico', $this->ordemServico);
    }
}
