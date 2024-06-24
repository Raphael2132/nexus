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
}
