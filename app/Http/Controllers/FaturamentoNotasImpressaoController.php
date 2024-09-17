<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use stdClass;
//use Barryvdh\DomPDF\Facade\Pdf;
//use PDF;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailRPS;

class FaturamentoNotasImpressaoController extends Controller
{
    public static function nfseGerarPDF($empresa, $numControle, $appOrigem)
    {

        // Opções de configuração
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $options->set('pdfBackend', 'auto');
        //$options->set('dpi', 150);
        
        // Cria uma instância do Dompdf com opções padrão
        $dompdf = new Dompdf($options);

        // Carrega o HTML da View para ser convertido em PDF
        $html = view('/faturamento/notas/impressao/nfsePDF', ['empresa' => $empresa, 'numControle' => $numControle])->render();

        // Carrega o HTML no Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation
        $dompdf->setPaper('A4', 'portrait');

        // Renderiza o PDF (gera o conteúdo do PDF)
        $dompdf->render();

        // Saída do PDF (nome do arquivo) - Baixar o arquivo PDF Automaticamente
        //return $dompdf->stream('exemplo.pdf'); 

        // Saída do PDF (nome do arquivo)
        $output = $dompdf->output();

        //Se for Origem do Email salva o pdf no servidor e devolve o caminho e se for impressão abre o PDF na nova aba
        if($appOrigem == "EMAIL"){

            $dadosNfs = DB::table('faturamento_nfs')->where('nfs_emp', $empresa)->where('nfs_nfhdr_num', $numControle)->first();
            $dadosEmp = DB::table('cadastro_empresas')->where('empresa_codigo', $empresa)->first();

            // Obter o nome do host do servidor
            if(!empty($_SERVER['SERVER_NAME'])){
                $serverName = $_SERVER['SERVER_NAME'];
            }else{
                $serverName = '';
            }

            // Verificar se está rodando no localhost
            if ($serverName != '127.0.0.1') {

                $temporaryDirectory = sys_get_temp_dir() . '/' . $dadosEmp->empresa_cnpj;

                if (!is_dir($temporaryDirectory)) {
                    mkdir($temporaryDirectory, 0755, true);
                }

            } else {

                $temporaryDirectory = $dadosEmp->empresa_cnpj.'/file/doc/tmp';
                $temporaryDirectory = public_path($temporaryDirectory);

                if (!is_dir($temporaryDirectory)) {
                    mkdir($temporaryDirectory, 0755, true);
                }
            } 

            // Caminho onde o PDF será salvo
            $filePath = $temporaryDirectory.'/nfs_' . $dadosNfs->nfs_nnfs . '.pdf';

            // Salva o PDF gerado no servidor
            file_put_contents($filePath, $output);

            return $filePath;

        }else{

            // Retorna a resposta HTTP com o PDF para abrir em uma nova aba
            return response($output, 200)->header('Content-Type', 'application/pdf');
        }
    }

    public function rpsGerarPDF($empresa, $numControle, $appOrigem)
    {
        // Opções de configuração
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $options->set('pdfBackend', 'auto');
        //$options->set('dpi', 150);
        
        // Cria uma instância do Dompdf com opções padrão
        $dompdf = new Dompdf($options);

        // Carrega o HTML da View para ser convertido em PDF
        $html = view('/faturamento/notas/impressao/rpsPDF', ['empresa' => $empresa, 'numControle' => $numControle])->render();

        // Carrega o HTML no Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation
        $dompdf->setPaper('A4', 'portrait');

        // Renderiza o PDF (gera o conteúdo do PDF)
        $dompdf->render();

        // Saída do PDF (nome do arquivo) - Baixar o arquivo PDF Automaticamente
        //return $dompdf->stream('exemplo.pdf'); 

        // Saída do PDF (nome do arquivo)
        $output = $dompdf->output();

        //Verifica a app de origem que está imprimindo o RPS
        if($appOrigem == 'ABERTURA_OS'){

            //Verifica se envia RPS no email do cliente
            $parSrv = DB::table('parametros_srv_empresas')->where('parsrv_emp', $empresa)->first();
            $dadosNfs = DB::table('faturamento_nfs')->where('nfs_emp', $empresa)->where('nfs_nfhdr_num', $numControle)->first();
            $dadosCli = DB::table('cadastro_clientes')->where('cliente_codigo', $dadosNfs->nfs_cli)->first();
            $dadosEmp = DB::table('cadastro_empresas')->where('empresa_codigo', $empresa)->first();

            if($parSrv->parsrv_env_rps_email == 'S' && !empty($dadosCli->cliente_email) && !empty($dadosEmp->empresa_smtp_host) && $dadosNfs->nfs_rps_env_email == 'N'){

                // Obter o nome do host do servidor
                if(!empty($_SERVER['SERVER_NAME'])){
                    $serverName = $_SERVER['SERVER_NAME'];
                }else{
                    $serverName = '';
                }

                // Verificar se está rodando no localhost
                if ($serverName != '127.0.0.1') {

                    $temporaryDirectory = sys_get_temp_dir() . '/' . $dadosEmp->empresa_cnpj;

                    if (!is_dir($temporaryDirectory)) {
                        mkdir($temporaryDirectory, 0755, true);
                    }

                } else {

                    $temporaryDirectory = $dadosEmp->empresa_cnpj.'/file/doc/tmp';
                    $temporaryDirectory = public_path($temporaryDirectory);

                    if (!is_dir($temporaryDirectory)) {
                        mkdir($temporaryDirectory, 0755, true);
                    }
                } 

                // Caminho onde o PDF será salvo
                $filePath = $temporaryDirectory.'/rps_' . $dadosNfs->nfs_nrps . '.pdf';

                // Salva o PDF gerado no servidor
                file_put_contents($filePath, $output);

                // Configuração dinâmica do SMTP para o envio pela empresa
                config([
                    'mail.mailers.smtp_cliente.host' => $dadosEmp->empresa_smtp_host,
                    'mail.mailers.smtp_cliente.port' => $dadosEmp->empresa_smtp_port,
                    'mail.mailers.smtp_cliente.encryption' => $dadosEmp->empresa_smtp_encryption,
                    'mail.mailers.smtp_cliente.username' => $dadosEmp->empresa_smtp_username,
                    'mail.mailers.smtp_cliente.password' => $dadosEmp->empresa_smtp_password,
                ]);

                // Usar o mailer específico da empresa
                Mail::mailer('smtp_cliente')->to($dadosCli->cliente_email)->send(new EmailRPS($dadosNfs, $dadosEmp->empresa_smtp_from_address, $filePath));

                // Atualiza o status para indicar que o e-mail foi enviado
                DB::table('faturamento_nfs')
                ->where('nfs_emp', $empresa)
                ->where('nfs_nfhdr_num', $numControle)
                ->update(['nfs_rps_env_email' => 'S']);
            }
        }

        // Retorna a resposta HTTP com o PDF para abrir em uma nova aba
        return response($output, 200)->header('Content-Type', 'application/pdf');
    }

    public function validaRPS($empresa, $numOS, $appOrigem)
    {
        if($appOrigem == 'ABERTURA_OS'){

            $dadosHDR = DB::table('faturamento_nf_headers')->where('nfhdr_num_ped',$numOS)->where('nfhdr_emp',$empresa)->where('nfhdr_ori','01')->where('nfhdr_tor','S')->first();
            $numControle = $dadosHDR->nfhdr_num;

            if($dadosHDR->nfhdr_sts == 'G'){
                $dadosENV = DB::table('faturamento_nfs_xml_envios')->where('nfsenv_nfhdr_num',$numControle)->where('nfsenv_emp',$empresa)->first();

                if($dadosENV->nfsenv_sts == '3'){
                    return response()->json(['status' => 'error', 'message' => 'A NFS-e da OS já foi emitida!']);
                }
            }

            /* ***** Gera a tabela de NFS para gerar os dados do RPS ***** */

            //Inicia o Database Transaction
            DB::beginTransaction();

            //Gera da tabela de nota fiscal de serviço
            $exec_fn = DB::select("select ret_sts, ret_msg from fn_faturamento_gera_nfs('".$empresa."',".$numControle.");");

            if($exec_fn[0]->ret_sts == '*'){
                //Falha, desfaz as alterações no banco de dados
                DB::rollBack();
                return response()->json(['status' => 'error', 'message' => $exec_fn[0]->ret_msg]);
            }else{
                //Grava as alterações do banco
                DB::commit();
            }
        }

        return response()->json(['status' => 'success', 'message' => '', 'numeroHDR' => $numControle, 'empresa' => $empresa]);
    }
}
