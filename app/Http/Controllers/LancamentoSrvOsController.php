<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\LancamentoSrvOs;
use App\Models\LancamentoSrvOsRequisicoes;
use Illuminate\Support\Facades\Auth;
use stdClass;
use App\Http\Helpers\Helper;
use App\Http\Helpers\HelperControleProducao;
use App\Http\Controllers\PainelAberturaOSController;

class LancamentoSrvOsController extends Controller
{
    protected $lancamentoOS;
    protected $requisicoesOS;
    
    public function __construct(LancamentoSrvOs $lancamentoOS, LancamentoSrvOsRequisicoes $requisicoesOS)
    {
        $this->lancamentoOS = $lancamentoOS;
        $this->requisicoesOS = $requisicoesOS;
    }

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

        return view('/lancamentos/servico/controleAberturaOS',['empresa'=>$request->empresa,'cliente'=>$cliente]);
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

    //Cria a OS e chama a app de controle de pré abertura de OS
    public function abreOS(Request $request, $empresa, $cliente)
    {
        //Verifica se o endereço foi do cliente foi informado
        if(empty(trim($request->enderecoCliOS))){
            return redirect()->route('emissaoOS.inicioErro',['empresa'=>$request->empresa,'cliente'=>$cliente])->with('error', 'Informe o endereço do cliente!');
        }
        
        //Caso utilizar outro endereço para o local da prestação do serviço validar o cep
        if($request->enderecoLocSrv == 3){
            $cep = str_replace('-', '', $request->cep);
            $cep = str_replace('_', '', $cep);

            if(strlen($cep) < 8 || strlen($cep) > 8){
                return redirect()->route('emissaoOS.inicioErro',['empresa' => $empresa, 'cliente' => $cliente])->with('error', 'Formato do CEP é inválido!');
            }
        }

        if($request->enderecoLocSrv == 3){

            $enderecoLocSrv = 'O';
            $loc_srv_cep = Helper::limpaCEP($request->cep);
            $loc_srv_logradouro = $request->logradouro;
            $loc_srv_numero = $request->numero;
            $loc_srv_complemento = $request->complemento;
            $loc_srv_bairro = $request->bairro;
            $loc_srv_cidade = $request->cidade;
            $loc_srv_uf = $request->uf;
            $loc_srv_pais = $request->pais;
            $loc_srv_ibge_cod_mun = $request->ibgeCodMun;

        }elseif($request->enderecoLocSrv == 2){

            $enderecoLocSrv = 'C';
            $loc_srv_cep = null;
            $loc_srv_logradouro = null;
            $loc_srv_numero = null;
            $loc_srv_complemento = null;
            $loc_srv_bairro = null;
            $loc_srv_cidade = null;
            $loc_srv_uf = null;
            $loc_srv_pais = null;
            $loc_srv_ibge_cod_mun = null;

        }else{

            $enderecoLocSrv = 'E';
            $loc_srv_cep = null;
            $loc_srv_logradouro = null;
            $loc_srv_numero = null;
            $loc_srv_complemento = null;
            $loc_srv_bairro = null;
            $loc_srv_cidade = null;
            $loc_srv_uf = null;
            $loc_srv_pais = null;
            $loc_srv_ibge_cod_mun = null;
        }

        $data_abertura = date('Y-m-d H:i:s');
        $horaPrev = date('Hi');
        $dataHj = date('Y-m-d');

        //Busca os horários de expediente e intervalo/almoço da empresa
        $businessHours = HelperControleProducao::geraBusinessHoursEmpPHP($empresa);
        $lunchBreaks = HelperControleProducao::geraLunchHoursEmpPHP($empresa);

        //Busca horas de inicio e termino de expediente da empresa no dia
        $hrIni = Helper::buscaHoraIniEx($dataHj,$businessHours);
        $hrFin = Helper::buscaHoraFinEx($dataHj,$businessHours);

        if(!empty($hrIni) && !empty($hrFin)){

            $hrIni = Helper::limpaHoraMinuto($hrIni);
            $hrFin = Helper::limpaHoraMinuto($hrFin);

            if($horaPrev >= $hrIni && $horaPrev <= $hrFin){
                $dataPrev = date('Y-m-d');
            }else{
                if($horaPrev < $hrIni){
                    $dataPrev = date('Y-m-d');
                    $horaPrev = $hrIni;
                }else{

                    $dataHoraPrev = HelperControleProducao::calculaPrevTerminoSrv($dataHj, Helper::formataHoraMinuto($horaPrev), '00:00:00', $businessHours, $lunchBreaks);

                    $dataPrev = date('Y-m-d', strtotime($dataHoraPrev));
                    $horaPrev = date('Hi', strtotime($dataHoraPrev));
                }
            }

        }else{

            $dataHoraPrev = HelperControleProducao::calculaPrevTerminoSrv($dataHj, Helper::formataHoraMinuto($horaPrev), '00:00:00', $businessHours, $lunchBreaks);

            $dataPrev = date('Y-m-d', strtotime($dataHoraPrev));
            $horaPrev = date('Hi', strtotime($dataHoraPrev));
        }

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
            'os_sts' => 'A',
            'os_cli_fatura' => $cliente,
            'os_dpe' => $dataPrev,
            'os_hpe' => $horaPrev,
            'os_loc_srv' => $enderecoLocSrv,
            'os_loc_srv_cep' => $loc_srv_cep,
            'os_loc_srv_logradouro' => $loc_srv_logradouro,
            'os_loc_srv_numero' => $loc_srv_numero,
            'os_loc_srv_complemento' => $loc_srv_complemento,
            'os_loc_srv_bairro' => $loc_srv_bairro,
            'os_loc_srv_cidade' => $loc_srv_cidade,
            'os_loc_srv_uf' => $loc_srv_uf,
            'os_loc_srv_pais' => $loc_srv_pais,
            'os_loc_srv_ibge_cod_mun' => $loc_srv_ibge_cod_mun,
        ];
        
        $novaOS = LancamentoSrvOs::create($dados);

        return redirect(route('situacaoOS.carregaOS', ['empresa' => $request->empresa, 'cliente' => $request->cliente, 'nos' => $numOS, 'estagioAPP' => 'PRINCIPAL']))->with('success', 'OS Aberta com sucesso!');
    }

    //Metodo de controle de carregamento da OS na pagina principal
    public function carregaOS($empresa, $cliente, $nos, $estagioAPP)
    {
        $dadosOS = $this->lancamentoOS->where('os_emp', $empresa)->where('os_cli', $cliente)->where('os_nos', $nos)->get();

        $dadosRequisicoes = $this->requisicoesOS->where('req_emp', $empresa)->where('req_nos', $nos)->orderby('req_seq')->get();

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

    //Metodo de controle de carregamento da OS na pagina principal
    public function abrirOrcamento(Request $request, $empresa, $numOS, $stsOS, $stsOrc, $cliente, $dtOS)
    {
        if($stsOS == 'C' && $request->novoOrcamento == 'S'){
            return redirect()->back()->with('info', 'Não é possível gerar um novo orçamento! OS já foi cancelada!');        
        }else if($stsOS == 'F' && $request->novoOrcamento == 'S'){
            return redirect()->back()->with('info', 'Não é possível gerar um novo orçamento! OS já foi finalizada!');        
        }

        if($request->novoOrcamento == 'S' || $stsOrc == 'N'){
            $nextval=DB::select("SELECT nextval('sq_num_orcamento')")[0]->nextval;
            $num = $nextval;

            $data = date('Y-m-d');

            DB::table('lancamento_srv_os')
            ->where('os_emp', $empresa)
            ->where('os_nos', $numOS)
            ->update(['os_dt_orc' => $data,
            'os_num_orc' => $num]);  

            if($stsOrc == 'N'){
                DB::table('lancamento_srv_os_orcamentos')
                ->where('orc_emp', $empresa)
                ->where('orc_nos', $numOS)
                ->insert(['orc_emp' => $empresa,
                'orc_nos' => $numOS,
                'orc_dt_orc' => $data,
                'orc_num_orc' => $num,
                'orc_cli' => $cliente,
                'orc_dha' => $dtOS]);
            }else{
                DB::table('lancamento_srv_os_orcamentos')
                ->where('orc_emp', $empresa)
                ->where('orc_nos', $numOS)
                ->update(['orc_dt_orc' => $data,
                'orc_num_orc' => $num]);  
            }
        }
        
        $empresaEndereco = DB::table('cadastro_empresa_enderecos')->where('endereco_empresa_codigo', $empresa)->where('endereco_principal', 'S')->get();
        $requisicoesOS = DB::table('lancamento_srv_os_requisicoes')->where('req_emp', $empresa)->where('req_nos', $numOS)->orderby('req_seq', 'asc')->get();
        $servicosOS = DB::table('lancamento_srv_os_servicos')->where('srv_emp', $empresa)->where('srv_nos', $numOS)->orderby('srv_req', 'asc')->orderby('srv_seq', 'asc')->get();
        $dadosOS = DB::table('lancamento_srv_os')->where('os_emp', $empresa)->where('os_nos', $numOS)->get();

        session(['glo_os_dadosEmpresaEndereco' => $empresaEndereco]);
        session(['glo_os_dadosRequisicoes' => $requisicoesOS]);
        session(['glo_os_dadosOS' => $dadosOS]);
        session(['glo_os_dadosServicos' => $servicosOS]);
        session(['glo_os_estagioAPP' => 'ORCAMENTO_OS_IMPRESSAO']);
        session(['glo_os_subEstagioRequisicao' => '']);
        session(['glo_os_dadosServicoSelecionado' => '']);
        session(['glo_os_dadosTMO' => '']);
        session(['glo_os_dadosTmoSelecionada' => '']);
        
        return view('/lancamentos/servico/painelAberturaOS');
    }

    //Metodo de controle de carregamento da OS na pagina principal
    public function atualizaPrevEntrega(Request $request, $empresa, $numOS, $cliente)
    {

        $data = Helper::limpaData($request->dataPrevEnt);
        $hora = Helper::limpaHoraMinuto($request->horaPrevEnt);

        if($request->calcAut == true){
            $calAut = 'S';
        }else{
            $calAut = 'N';
        }

        DB::table('lancamento_srv_os')
        ->where('os_emp', $empresa)
        ->where('os_nos', $numOS)
        ->update(['os_qtd_hr_pre_ent' => $request->qtdHoraOS,
        'os_dpe' => $data,
        'os_hpe' => $hora,
        'os_cli_agr' => $request->clienteAguardaTermino,
        'os_cli_avs' => $request->avisaClienteTermino,
        'os_cal_aut_pre_ent' => $calAut]);  

        DB::table('lancamento_srv_exe_tarefas')
        ->where('exetrf_emp', $empresa)
        ->where('exetrf_nos', $numOS)
        ->update(['exetrf_dt_prev_ent' => $data,
            'exetrf_hr_prev_ent' => $hora]);  
        
        return redirect(route('situacaoOS.carregaOS', ['empresa' => $empresa, 'cliente' => $cliente, 'nos' => $numOS, 'estagioAPP' => 'PRINCIPAL']))->with('success', 'Previsão de Entrega Atualizada com Sucesso!');
    }

    //Atualiza a observação da os
    public function atualizaObservacao(Request $request, $empresa, $numOS)
    {
        $atualiaServico = DB::table('lancamento_srv_os')
        ->where('os_emp', $empresa)
        ->where('os_nos', $numOS)
        ->update(['os_observacao' => $request->observacaoOS]);  
        
        return redirect(route('painelOS.totalOS', ['empresa' => $empresa, 'numOS' => $numOS]))->with('success', 'Observações da OS Atualizada com Sucesso!');
    }

    //Atualiza a observação da os
    public function atualizaCliFatura(Request $request, $empresa, $numOS)
    {

        if(!empty($request->cliFatura)){
            $cliente = substr($request->cliFatura, 0, 10);

            if(strlen($cliente) < 10){
                return redirect()->back()->with('error', 'Código do cliente '.$cliente.' é inválido!');
            }

            $cnt_cli = DB::table('cadastro_clientes')->where('cliente_codigo', $cliente)->count();
        
            if($cnt_cli == 0){
                return redirect()->back()->with('error', 'Código do cliente '.$cliente.' não existe!');
            }
        
        }else{
            $cliente = null;
        }

        $atualiaServico = DB::table('lancamento_srv_os')
        ->where('os_emp', $empresa)
        ->where('os_nos', $numOS)
        ->update(['os_cli_fatura' => $cliente]);  
        
        return redirect(route('painelOS.totalOS', ['empresa' => $empresa, 'numOS' => $numOS]))->with('success', 'Troca do Cliente da Fatura Realizada com Sucesso!');
    }

    //Atualiza a observação da os
    public function atualizaDescontoOS(Request $request, $empresa, $numOS)
    {

        $percentual = Helper::limpaPorcentagem($request->perDescontoOS);
        $valor = Helper::limpaValorMonetario($request->valDescontoOS);

        $atualiaServico = DB::table('lancamento_srv_os')
        ->where('os_emp', $empresa)
        ->where('os_nos', $numOS)
        ->update(['os_per_des' => $percentual,
            'os_val_des' => $valor]);  

        PainelAberturaOSController::atualizaValorOS($empresa, $numOS);
        
        return redirect(route('painelOS.totalOS', ['empresa' => $empresa, 'numOS' => $numOS]))->with('success', 'Desconto da OS Realizado com Sucesso!');
    }
}
