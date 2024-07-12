<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use stdClass;
use App\Http\Helpers\Helper;
use App\Http\Controllers\FaturamentoNfsSimplificadaController;
use App\Http\Controllers\Nfs\Core\Nfsxml;
use File;

class EmissaoSimplificadaNFSController extends Controller
{
    //Chama a app de controle de pré abertura de OS
    public function inicio(Request $request)
    {
        if(!empty($request->cliente)){
            $cliente = substr($request->cliente, 0, 10);

            if(strlen($cliente) < 10){
                return redirect()->back()->with('error', 'Código do cliente '.$cliente.' é inválido!');
            }

            $cnt_cli = DB::table('cadastro_clientes')->where('cliente_codigo', $cliente)->count();
        
            if($cnt_cli == 0){
                return redirect()->back()->with('error', 'Código do cliente '.$cliente.' não existe!');
            }
        
        }else{
            return redirect()->back()->with('error', 'É obrigátorio informar o cliente!');
        }

        return view('/faturamento/notas/formularioEmissaoSimplificadaNFS',['empresa'=>$request->empresa,'cliente'=>$cliente]);
    }

    //Chama a app de controle de pré abertura de OS
    public function etapa2(Request $request, $empresa, $cliente)
    {
        //Verifica se o endereço foi do cliente foi informado
        if(empty(trim($request->enderecoCli))){
            return redirect()->route('emissaoSimpNFS.inicioErro',['empresa'=>$request->empresa,'cliente'=>$cliente])->with('error', 'Informe o endereço do cliente!');
        }
        
        return view('/faturamento/notas/formularioEmissaoSimplificadaNFSEtapa2',['empresa'=>$request->empresa,'cliente'=>$cliente,'enderecoCli'=>$request->enderecoCli]);
    }
    
    //Retorna os códigos de serviço do grupo selecionado
    public function carregaCodSrvAjax($codigo)
    {  
        $servicos = DB::table('parametros_sistema_servicos')->select('servico_codigo', 'servico_desc')->where('servico_grupo', $codigo)->orderby('servico_codigo', 'asc')->get();
       
        foreach($servicos as $servico) {
            
            $servicos_ajax[] = array(
                'id'	=> $servico->servico_codigo,
                'cod_servico' => $servico->servico_codigo.' - '.$servico->servico_desc,
            );
        }  

        return response()->json(['success' => true, 'servicos_ajax' => $servicos_ajax]);
    }

    //Faz a emissão da NFS-e simplificada
    public function emitirNFS(Request $request, $empresa, $cliente, $enderecoCli)
    {        
        //Caso utilizar outro endereço para o local da prestação do serviço validar o cep
        if($request->enderecoLocSrv == 3){
            $cep = str_replace('-', '', $request->cep);
            $cep = str_replace('_', '', $cep);

            if(strlen($cep) < 8 || strlen($cep) > 8){
                return redirect()->route('emissaoSimpNFS.etapa2Erro',['empresa' => $empresa, 'cliente' => $cliente, 'enderecoCli' => $enderecoCli])->with('error', 'Formato do CEP é inválido!');
            }
        }

        //Inicia o Database Transaction
        DB::beginTransaction();

        //Pega os dados do request
        $dados = $request->all();
        
        //Gera a tabela da NFS simplificada
        $numeroNFSimp = FaturamentoNfsSimplificadaController::insert($dados, $empresa, $cliente, $enderecoCli);

        //Chama a procedure de geração das tabelas NF Header e suas filhas
        $exec_fn = DB::select("select ret_sts, ret_msg, ret_num from fn_faturamento_gera_nf_simp('".$empresa."',".$numeroNFSimp.");");

        if($exec_fn[0]->ret_sts == '*'){
            //Falha, desfaz as alterações no banco de dados
            DB::rollBack();
            return redirect()->route('emissaoSimpNFS.etapa2Erro',['empresa' => $empresa, 'cliente' => $cliente, 'enderecoCli' => $enderecoCli])->with('error', $exec_fn[0]->ret_msg);
        }else{
            //Grava as alterações do banco
            DB::commit();
            $numControle = $exec_fn[0]->ret_num;
        }

        //Faz a geração da NFS-e depois de gerar as tabelas da NF

        //Inicia o Database Transaction
        DB::beginTransaction();

        //Gera da tabela de nota fiscal de serviço
        $exec_fn = DB::select("select ret_sts, ret_msg from fn_faturamento_gera_nfs('".$empresa."',".$numControle.");");

        if($exec_fn[0]->ret_sts == '*'){
            //Falha, desfaz as alterações no banco de dados
            DB::rollBack();
            return redirect()->route('emissaoSimpNFS.etapa2Erro',['empresa' => $empresa, 'cliente' => $cliente, 'enderecoCli' => $enderecoCli])->with('error', $exec_fn[0]->ret_msg);
        }else{
            //Grava as alterações do banco
            DB::commit();
        }
        
        //Gera o xml de envio
        $nfsxml = new Nfsxml($empresa, $numControle);
        $nfsxml->emitirNFS();

        $pathXML = $nfsxml->nomeArquivo;

        $dadosNF = DB::table('faturamento_nf_headers')->where('nfhdr_emp', $empresa)->where('nfhdr_num', $numControle)->get();

        $dataGeracaoNF = date('Y-m-d');
        $horaGeracaoNF = date('Hi');

        $stsEnvio = DB::table('faturamento_nfs_xml_envios')->where('nfsenv_emp', $empresa)->where('nfsenv_nfhdr_num', $numControle)->get();

        if($stsEnvio[0]->nfsenv_sts == 3){
            $status = 'G';
        }else{
            $status = 'E';
        }

        //Atualiza os dados da NF
        DB::table('faturamento_nf_headers')
        ->where('nfhdr_emp', $empresa)
        ->where('nfhdr_num', $numControle)
        ->update(['nfhdr_sts' => $status,
            'nfhdr_dt_nf' => $dataGeracaoNF,
            'nfhdr_hr_nf' => $horaGeracaoNF]);
        
        DB::table('faturamento_nfs')
        ->where('nfs_emp', $empresa)
        ->where('nfs_nfhdr_num', $numControle)
        ->update(['nfs_sts' => $status,
            'nfs_dt_emi' => $dataGeracaoNF,
            'nfs_hr_emi' => $horaGeracaoNF]);
        
        return view('/faturamento/notas/controleGeracaoNF', ['empresa' => $empresa, 'numControle' => $numControle, 'pathXML' => $pathXML]);
    }
}
