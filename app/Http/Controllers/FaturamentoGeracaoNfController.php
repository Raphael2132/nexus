<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use stdClass;
use App\Http\Controllers\Nfs\Core\Nfsxml;
use File;
class FaturamentoGeracaoNfController extends Controller
{
    //private $nfsxml;

    public function gerarNF($empresa, $cliente, $nfSelecionada)
    {

        
        //Inicia o Database Transaction
        DB::beginTransaction();

        //Gera da tabela de nota fiscal de serviço
        $exec_fn = DB::select("select ret_sts, ret_msg from fn_faturamento_gera_nfs('".$empresa."',".$nfSelecionada.");");

        if($exec_fn[0]->ret_sts == '*'){
            //Falha, desfaz as alterações no banco de dados
            DB::rollBack();
            return redirect()->back()->with('error', $exec_fn[0]->ret_msg);
        }else{
            //Grava as alterações do banco
            DB::commit();
        }

        //Gera o xml de envio
        //echo "<br> iniciando o Nfsxml <br>";
        $nfsxml = new Nfsxml($empresa, $nfSelecionada);

        //envia o xml
        //echo "<br> Finalizei a criação do xml na instanciação da classe Nfsxml <br>";
        
        //echo "<br> iniciando a emissão da nfs <br>";

        $nfsxml->emitirNFS();

        $pathXML = $nfsxml->nomeArquivo;

        $dadosNF = DB::table('faturamento_nf_headers')->where('nfhdr_emp', $empresa)->where('nfhdr_num', $nfSelecionada)->get();
        $dadosEmi = DB::table('cadastro_empresas')->where('empresa_codigo', $empresa)->get();

        $dataHoraGeracaoNF = date('Y-m-d H:i:s');
        $dataGeracaoNF = date('Y-m-d');
        $horaGeracaoNF = date('Hi');

        //Atualiza os dados da NF
        DB::table('faturamento_nf_headers')
                ->where('nfhdr_emp', $empresa)
                ->where('nfhdr_num', $nfSelecionada)
                ->update(['nfhdr_sts' => 'G',
                'nfhdr_dt_nf' => $dataGeracaoNF]);
        
        DB::table('faturamento_nfs')
        ->where('nfs_emp', $empresa)
        ->where('nfs_nfhdr_num', $nfSelecionada)
        ->update(['nfs_sts' => 'G',
            'nfs_dt_emi' => $dataGeracaoNF,
            'nfs_hr_emi' => $horaGeracaoNF]);

        return view('/faturamento/notas/controleGeracaoNF', ['empresa' => $empresa, 'numControle' => $nfSelecionada, 'pathXML' => $pathXML]);
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
