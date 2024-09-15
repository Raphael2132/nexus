<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\EmailContato;
use Illuminate\Support\Facades\Mail;
use App\Facades\Empresa;
use Illuminate\Support\Facades\DB;
use stdClass;

class ContatoController extends Controller
{
    public function enviarEmailContato(Request $request)
    {
        // Busca o cliente e as configurações de e-mail
        //$empresa = Empresa::getEmpresa();

        // Verifica se o setor informado existe no banco
        $dataEma = DB::table('parametros_sis_email_setores')->where('emaset_cod', $request->setor)->first();

        // Email do destinatário baseado no setor
        //$emailDest = $dataEma->emaset_email;
        $emailDest = config('mail.from.address');

        // Monta as variáveis que compõem o corpo do email
        $details = [
            'titulo' => $dataEma->emaset_desc. ' - ' . $request->assunto,
            'mensagem' => $request->mensagem,
            'emailCliente' => $request->email,
            'assunto' => $request->assunto,
            'setor' => $dataEma->emaset_desc,
            'empresa' => $request->empresa,
        ];

        /*
        // Verifica se o SMTP da empresa está configurado e envia o email
        if ($empresa->empresa_smtp_host) {

            // Configuração dinâmica do cliente
            config([
                'mail.mailers.smtp_cliente.host' => $empresa->empresa_smtp_host,
                'mail.mailers.smtp_cliente.port' => $empresa->empresa_smtp_port,
                'mail.mailers.smtp_cliente.encryption' => $empresa->empresa_smtp_encryption,
                'mail.mailers.smtp_cliente.username' => $empresa->empresa_smtp_username,
                'mail.mailers.smtp_cliente.password' => $empresa->empresa_smtp_password,
            ]);

            // Usar o SMTP dinâmico
            Mail::mailer('smtp_cliente')->to($emailDest)->send(new EmailContato($details));

        } else {
            // Usar o SMTP padrão do .env
            Mail::mailer('smtp_fusiontech')->to($emailDest)->send(new EmailContato($details));
        }
        */
        
        // Usar o SMTP padrão do .env
        Mail::mailer('smtp_fusiontech')->to($emailDest)->send(new EmailContato($details));

        return redirect(route('contato'))->with('success2', 'Email enviado com sucesso!');
    }
}
