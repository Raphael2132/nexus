<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use stdClass;
use App\Models\FaturamentoNfsXmlEnviados;
use App\Http\Controllers\Nfs\Core\Nfsxml;

class FaturamentoGeracaoNfController extends Controller
{
    //private $nfsxml;

    public function gerarNF($empresa, $cliente, $nfSelecionada)
    {

        /*
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
        */

        //Gera o xml de envio
        //echo $_SERVER['DOCUMENT_ROOT'];exit;
        //include_once 'App/Http/Controllers/Nfs/Core/Nfsxml.php';
        echo "<br> iniciando o Nfsxml <br>";
        $nfsxml = new Nfsxml($empresa, $nfSelecionada, 1);
        //envia o xml

        $dadosNF = DB::table('faturamento_nf_headers')->where('nfhdr_emp', $empresa)->where('nfhdr_num', $nfSelecionada)->get();
        $dadosEmi = DB::table('cadastro_empresas')->where('empresa_codigo', $empresa)->get();

        $dataHoraGeracaoNF = date('Y-m-d H:i:s');
        $dataGeracaoNF = date('Y-m-d');
        $horaGeracaoNF = date('Hi');

        //Gera dados do envio e retorno da prefeitura
        $dados = [
            'nfsenv_emp' => $empresa,
            'nfsenv_num' => $dadosNF[0]->nfhdr_num_nf,
            'nfsenv_nfhdr_num' => $nfSelecionada,
            'nfsenv_cnpj' => $dadosEmi[0]->empresa_cnpj,
            'nfsenv_pro' => '00000000',
            'nfsenv_sts' => '3',
            'nfsenv_dt_atu' => $dataHoraGeracaoNF,
            'nfsenv_dt_inc' => $dataHoraGeracaoNF,
            'nfsenv_num_nfs' => $dadosNF[0]->nfhdr_num_nf,
            'nfsenv_obs' => 'NFS-e Gerada com sucesso'
        ];

        FaturamentoNfsXmlEnviados::create($dados);

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

        return view('/faturamento/notas/controleGeracaoNF');
    }
}
