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


class EmailRPS extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $dadosNFS;
    public $emailFrom;
    public $filePath;

    public function __construct($dadosNFS, $emailFrom, $filePath)
    {
        $this->dadosNFS = $dadosNFS;
        $this->emailFrom = $emailFrom;
        $this->filePath = $filePath;
    }

    public function build()
    {
        $os = str_pad($this->dadosNFS->nfs_nfhdr_num_ped, 6, '0', STR_PAD_LEFT);
        $dadosEmp = DB::table('cadastro_empresas')->where('empresa_codigo', $this->dadosNFS->nfs_emp)->first();

        return $this->from($this->emailFrom, $dadosEmp->empresa_nome)
                    ->subject('Emissão do RPS referente a OS Nº ' . $os)
                    ->view('email.emailEnvioRPS')
                    ->attach($this->filePath, [
                        'as' => 'RPS_'.$this->dadosNfs->nfs_nrps.'.pdf',
                        'mime' => 'application/pdf',
                    ])
                    ->with('dadosNFS', $this->dadosNFS);
    }
}
