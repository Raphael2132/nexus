<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use stdClass;
//use Barryvdh\DomPDF\Facade\Pdf;
//use PDF;
//use Mpdf\Mpdf;
//use TCPDF;
use Dompdf\Dompdf;
use Dompdf\Options;

class FaturamentoNotasImpressaoController extends Controller
{
    public function nfseGerarPDF($empresa, $numControle)
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

        // Retorna a resposta HTTP com o PDF para abrir em uma nova aba
        return response($output, 200)->header('Content-Type', 'application/pdf');
    }

    public function rpsGerarPDF($empresa, $numControle)
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
