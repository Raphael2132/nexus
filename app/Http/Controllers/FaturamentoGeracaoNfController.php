<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use stdClass;
use App\Http\Controllers\Nfs\Core\Nfsxml;
use File;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailNFS;

class FaturamentoGeracaoNfController extends Controller
{
    //private $nfsxml;

    public function gerarNF($empresa, $nfReemissao, $origem)
    {

        if($origem == 'EMISSAO'){//Gera dados da Emissão de Notas

            $idRecebimento = session('glo_id_recebimento');
            $where = session('glo_where_emissao_nf_completo');
            $where_semi = session('glo_where_emissao_nf_semi');
            $where_reemissao = '';

            //Busca as notas do recebimento realizado
            $notasReceb = DB::table('financeiro_recebimento_notas')->where('recnf_cod_rec', $idRecebimento)->where('recnf_emp', $empresa)->orderby('recnf_num')->get();

            //Gera um array das NF para utilizar no controleGeracaoNF
            $where_hdr = [];

            foreach($notasReceb as $nota){
                $where_hdr[] = $nota->recnf_num;
            }

        }elseif($origem == 'REEMISSAO' || $origem == 'REEMISSAO_SIMP'){//Gera dados da Reemissão de Notas

            $idRecebimento = '';
            $where = '';
            $where_semi = '';
            $where_reemissao = session('glo_where_reemissao_nf');

            //Gera um array das NF para utilizar no controleGeracaoNF
            $where_hdr = [];
            $where_hdr[] = $nfReemissao;
        }

        $notasHDR = DB::table('faturamento_nf_headers')->where('nfhdr_emp', $empresa)->wherein('nfhdr_num', $where_hdr)->orderby('nfhdr_num')->get();
        
        foreach($notasHDR as $nota){

            //Inicia o Database Transaction
            DB::beginTransaction();

            //Gera da tabela de nota fiscal de serviço
            $exec_fn = DB::select("select ret_sts, ret_msg from fn_faturamento_gera_nfs('".$empresa."',".$nota->nfhdr_num.");");

            if($exec_fn[0]->ret_sts == '*'){
                //Falha, desfaz as alterações no banco de dados
                DB::rollBack();
                return redirect()->back()->with('error', $exec_fn[0]->ret_msg);
            }else{

                //Atualiza os dados da NF para Geração I - Iniciada
                DB::table('faturamento_nf_headers')
                ->where('nfhdr_emp', $empresa)
                ->where('nfhdr_num', $nota->nfhdr_num)
                ->update(['nfhdr_sts' => 'I']);

                //Grava as alterações do banco
                DB::commit();
            }

            //Gera o xml de envio
            $nfsxml = new Nfsxml($empresa, $nota->nfhdr_num);
            $nfsxml->emitirNFS();

            $dadosNF = DB::table('faturamento_nf_headers')->where('nfhdr_emp', $empresa)->where('nfhdr_num', $nota->nfhdr_num)->get();

            $dataGeracaoNF = date('Y-m-d');
            $horaGeracaoNF = date('Hi');

            $stsEnvio = DB::table('faturamento_nfs_xml_envios')->where('nfsenv_emp', $empresa)->where('nfsenv_nfhdr_num', $nota->nfhdr_num)->get();

            if($stsEnvio[0]->nfsenv_sts == 3){
                $status = 'G';
            }else{
                $status = 'E';
            }

            //Atualiza os dados da NF
            DB::table('faturamento_nf_headers')
                    ->where('nfhdr_emp', $empresa)
                    ->where('nfhdr_num', $nota->nfhdr_num)
                    ->update(['nfhdr_sts' => $status,
                    'nfhdr_dt_nf' => $dataGeracaoNF,
                    'nfhdr_hr_nf' => $horaGeracaoNF]);
            
            DB::table('faturamento_nfs')
            ->where('nfs_emp', $empresa)
            ->where('nfs_nfhdr_num', $nota->nfhdr_num)
            ->update(['nfs_sts' => $status,
                'nfs_dt_emi' => $dataGeracaoNF,
                'nfs_hr_emi' => $horaGeracaoNF]);

            //Verifica a Origem da NF
            if($origem == 'EMISSAO'){
    
                //Busca a nota do faturamento para pegar o numero e serie da NF
                $notaHDR = DB::table('faturamento_nf_headers')
                ->where('nfhdr_emp', $empresa)
                ->where('nfhdr_num', $nota->nfhdr_num)
                ->first();

                DB::table('financeiro_recebimento_notas')
                ->where('recnf_cod_rec', $idRecebimento)
                ->where('recnf_emp', $empresa)
                ->where('recnf_num', $nota->nfhdr_num)
                ->update(['recnf_num_nf' => $notaHDR->nfhdr_num_nf,
                    'recnf_ser_nf' => $notaHDR->nfhdr_ser_nf,
                    'recnf_dt_nf' => $notaHDR->nfhdr_dt_nf]);
            }
        }

        //Verifica a Origem da NF
        if($origem == 'EMISSAO'){

            DB::table('financeiro_recebimento_headers')
            ->where('rechdr_id', $idRecebimento)
            ->where('rechdr_emp', $empresa)
            ->update(['rechdr_sts' => 'F']);
        }

        if($stsEnvio[0]->nfsenv_sts == 3){

            //Verifica se envia a NFS-e no email do cliente
            $parFat = DB::table('parametros_fat_empresas')->where('parfat_emp', $empresa)->first();
            $dadosNfs = DB::table('faturamento_nfs')->where('nfs_emp', $empresa)->where('nfs_nfhdr_num', $nota->nfhdr_num)->first();
            $dadosCli = DB::table('cadastro_clientes')->where('cliente_codigo', $dadosNfs->nfs_cli)->first();
            $dadosEmp = DB::table('cadastro_empresas')->where('empresa_codigo', $empresa)->first();

            if($parFat->parfat_env_nfs_email == 'S' && !empty($dadosCli->cliente_email) && !empty($dadosEmp->empresa_smtp_host)){
            
                $filePath = FaturamentoNotasImpressaoController::nfseGerarPDF($empresa, $nota->nfhdr_num, 'EMAIL');

                // Configuração dinâmica do SMTP para o envio pela empresa
                config([
                    'mail.mailers.smtp_cliente.host' => $dadosEmp->empresa_smtp_host,
                    'mail.mailers.smtp_cliente.port' => $dadosEmp->empresa_smtp_port,
                    'mail.mailers.smtp_cliente.encryption' => $dadosEmp->empresa_smtp_encryption,
                    'mail.mailers.smtp_cliente.username' => $dadosEmp->empresa_smtp_username,
                    'mail.mailers.smtp_cliente.password' => $dadosEmp->empresa_smtp_password,
                ]);

                // Usar o mailer específico da empresa
                Mail::mailer('smtp_cliente')->to($dadosCli->cliente_email)->send(new EmailNFS($dadosNfs, $dadosEmp->empresa_smtp_from_address, $filePath));

                // Atualiza o status para indicar que o e-mail foi enviado
                DB::table('faturamento_nfs')
                ->where('nfs_emp', $empresa)
                ->where('nfs_nfhdr_num', $nota->nfhdr_num)
                ->update(['nfs_nfs_env_email' => 'S']);                

            }
        }
        
        return view('/faturamento/notas/controleGeracaoNF', [
            'empresa' => $empresa, 
            'origem' => $origem,
            'where_hdr' => $where_hdr,
            'where_completo' => $where, 
            'where_semi' => $where_semi,
            'glo_where_reemissao_nf' => $where_reemissao
        ]);
    }

    public function abrirXml($xml,$nf,$empresa)
    {
        $xmlContent = base64_decode($xml);

        // Crie o nome do arquivo
        $fileName = 'xml_'.$empresa .'_'.$nf.'.xml';

        // Crie o cabeçalho para a resposta
        $headers = [
            'Content-Type' => 'application/xml',
            'Content-Disposition' => 'inline; filename="' . $fileName . '"',
        ];

        // Retorne o XML como resposta
        return response($xmlContent, 200)->withHeaders($headers);
    }
}
