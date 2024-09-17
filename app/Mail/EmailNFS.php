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

class EmailNFS extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Get the message envelope.
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
        $dadosEmp = DB::table('cadastro_empresas')->where('empresa_codigo', $this->dadosNFS->nfs_emp)->first();

        if($this->dadosNFS->nfs_origem == 'ES'){
            $subject = "Emissão da NFS-e Nº ".str_pad($this->dadosNFS->nfs_nnfs, 6, '0', STR_PAD_LEFT);
        }else{
            $subject = "Emissão da NFS-e referente a OS Nº ".str_pad($this->dadosNFS->nfs_nfhdr_num_ped, 6, '0', STR_PAD_LEFT);
        }

        return $this->from($this->emailFrom, $dadosEmp->empresa_nome)
                    ->subject($subject)
                    ->view('email.emailEnvioNFS')
                    ->attach($this->filePath, [
                        'as' => 'NFS_'.$this->dadosNFS->nfs_nnfs.'.pdf',
                        'mime' => 'application/pdf',
                    ])
                    ->with('dadosNFS', $this->dadosNFS);
    }
}
