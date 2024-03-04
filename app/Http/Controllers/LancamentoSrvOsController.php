<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\LancamentoSrvOs;
use App\Models\LancamentoSrvOsRequisicoes;
use Illuminate\Support\Facades\Auth;
use stdClass;

class LancamentoSrvOsController extends Controller
{
    protected $lancamentoOS;
    
    public function __construct(LancamentoSrvOs $lancamentoOS, LancamentoSrvOsRequisicoes $requisicoesOS)
    {
        $this->lancamentoOS = $lancamentoOS;
        $this->requisicoesOS = $requisicoesOS;
    }

    //Chama a app de controle de pré abertura de OS
    public function inicio(Request $request)
    {
        return view('/lancamentos/servico/controleAberturaOS',['empresa'=>$request->empresa,'cliente'=>$request->cliente]);
    }

    //Chama a app de controle de pré abertura de OS
    public function consultaSituacaoOS($statusOS)
    {
        if($statusOS == 'T'){
            //$dadosOS = $this->lancamentoOS->reorder('os_nos', 'desc')->all();
            $dadosOS = DB::table('lancamento_srv_os')->orderby('os_nos', 'desc')->get();
        }elseif($statusOS == 'A'){
            //$dadosOS = $this->lancamentoOS->where('os_sts','A')->reorder('os_nos', 'desc')->get();
            $dadosOS = DB::table('lancamento_srv_os')->where('os_sts','A')->orderby('os_nos', 'desc')->get();
        }elseif($statusOS == 'F'){
            //$dadosOS = $this->lancamentoOS->where('os_sts','F')->reorder('os_nos', 'desc')->get();
            $dadosOS = DB::table('lancamento_srv_os')->where('os_sts','F')->orderby('os_nos', 'desc')->get();
        }else{
            //$dadosOS = $this->lancamentoOS->where('os_sts','C')->reorder('os_nos', 'desc')->get();
            $dadosOS = DB::table('lancamento_srv_os')->where('os_sts','C')->orderby('os_nos', 'desc')->get();
        }

        return view('/lancamentos/servico/consultaSituacaoOS',['dadosOS'=>$dadosOS,'statusOS'=>$statusOS]);
    }

    //Chama a app de controle de pré abertura de OS
    public function abreOS(Request $request, $empresa, $cliente)
    {
        $data_abertura = date('Y-m-d H:i:s');

        $usuario = Auth::user()->usuario_codigo;

        $nextval=DB::select("SELECT nextval('sq_lancamento_srv_numero_os')")[0]->nextval;
        $numOS = $nextval;

        $dados = [

            'os_nos' => $numOS,
            'os_emp' => $empresa,
            'os_cli' => $cliente,
            'os_cli_end' => $request->enderecoCliOS,
            'os_dha' => $data_abertura,
            'os_res_abr' => $usuario,
            'os_vlt' => '0',
            'os_vos' => '0',
            'os_vls' => '0',
            'os_vlp' => '0',
            'os_per_des' => '0',
            'os_val_des' => '0',
            'os_sts' => 'A'
        ];
        
        $novaOS = LancamentoSrvOs::create($dados);

        return redirect(route('situacaoOS.carregaOS', ['empresa' => $request->empresa, 'cliente' => $request->cliente, 'nos' => $numOS, 'estagioAPP' => 'PRINCIPAL']))->with('success', 'OS Aberta com sucesso!');
    }

    //Metodo de controle de carregamento da OS na pagina principal
    public function carregaOS($empresa, $cliente, $nos, $estagioAPP)
    {
        $dadosOS = $this->lancamentoOS->where('os_emp', $empresa)->where('os_cli', $cliente)->where('os_nos', $nos)->get();

        $dadosRequisicoes = $this->requisicoesOS->where('req_emp', $empresa)->where('req_nos', $nos)->get();

        $dadosEmpresa = DB::table('cadastro_empresas')->where('empresa_codigo',$empresa)->get();

        $dadosCliente = DB::table('cadastro_clientes')->where('cliente_codigo',$cliente)->get();

        $dadosClienteEndereco = DB::table('cadastro_cliente_enderecos')->where('endereco_cliente_codigo',$cliente)->where('endereco_seq',$dadosOS[0]['os_cli_end'])->get();

        //Gera as variaveis globais do painel de OS
        session(['glo_os_dadosOS' => $dadosOS]);
        session(['glo_os_dadosRequisicoes' => $dadosRequisicoes]);
        session(['glo_os_dadosEmpresa' => $dadosEmpresa]);
        session(['glo_os_dadosCliente' => $dadosCliente]);
        session(['glo_os_dadosClienteEndereco' => $dadosClienteEndereco]);
        session(['glo_os_empresa' => $empresa]);
        session(['glo_os_cliente' => $cliente]);
        session(['glo_os_nos' => $nos]);
        session(['glo_os_estagioAPP' => $estagioAPP]);

        return view('/lancamentos/servico/painelAberturaOS');
    }
}
