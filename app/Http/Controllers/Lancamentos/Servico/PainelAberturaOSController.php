<?php

namespace App\Http\Controllers\Lancamentos\Servico;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Lancamentos\Servico\LancamentoSrvOs;
use App\Models\LancamentoSrvOsRequisicoes;
use stdClass;
use DateTime;
use Dompdf\Dompdf;
use Dompdf\Options;
//use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use App\Http\Helpers\Helper;
use App\Http\Helpers\HelperControleProducao;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailEncerraOS;

class PainelAberturaOSController extends Controller
{
    /* *****
        |--------------------------------------------------------------------------
        | Controler responsavel pelos comandos de navegação do painel de abertura de OS
        |--------------------------------------------------------------------------
    ***** */

    protected $requisicaoOS;
    
    public function __construct(LancamentoSrvOsRequisicoes $requisicaoOS)
    {
        $this->requisicaoOS = $requisicaoOS;
    }

    /* *************** Área dos comandos de navegação da abertura/consulta da Requisição *************** */

    //Metodo de carregamento da etapa de inclusão de nova requisição
    public function abreRequisicao($empresa, $nos, $estagioAPP, $glo_eat_cod, $glo_eat_ord)
    {
        if($glo_eat_cod != 0){
            $etapa = DB::table('lancamento_srv_etapa_atendimentos')->where('eat_emp', $empresa)->where('eat_cod', $glo_eat_cod)->where('eat_ord', $glo_eat_ord)->get();
            
            $glo_eat_ord = $etapa[0]->eat_ord;
            $glo_eat_cat = $etapa[0]->eat_cat;
        }else{
            $glo_eat_ord = '';
            $glo_eat_cat = '';
        }
        
        session(['glo_os_estagioAPP' => $estagioAPP]);
        session(['glo_os_req_eat_cod' => $glo_eat_cod]);
        session(['glo_os_req_eat_cat' => $glo_eat_cat]);
        session(['glo_os_req_eat_ord' => $glo_eat_ord]);

        return view('/lancamentos/servico/painelAberturaOS');
    }

    //Metodo de carregamento da etapa de consulta dos dados da requisição
    public function consultaRequisicao($empresa, $nos, $estagioAPP, $requisicao)
    {
        $dadosRequisicao = $this->requisicaoOS->where('req_emp', $empresa)->where('req_seq', $requisicao)->where('req_nos', $nos)->get();

        $dadosServico = DB::table('lancamento_srv_os_servicos')->where('srv_emp',$empresa)->where('srv_nos',$nos)->where('srv_req',$requisicao)->orderby('srv_seq','asc')->get();
        $dadosSrvIni = DB::table('lancamento_srv_os_servicos')->where('srv_emp',$empresa)->where('srv_nos',$nos)->where('srv_req',$requisicao)->where('srv_sts','E')->where('srv_flg_apr','S')->orderby('srv_seq','asc')->get();
        $dadosSrvFin = DB::table('lancamento_srv_os_servicos')->where('srv_emp',$empresa)->where('srv_nos',$nos)->where('srv_req',$requisicao)->where('srv_sts','A')->orderby('srv_seq','asc')->get();
        $dadosSrvApr = DB::table('lancamento_srv_os_servicos')->where('srv_emp',$empresa)->where('srv_nos',$nos)->where('srv_req',$requisicao)->where('srv_flg_apr','N')->orderby('srv_seq','asc')->get();

        session(['glo_os_dadosRequisicoes' => $dadosRequisicao]);
        session(['glo_os_dadosServico' => $dadosServico]);
        session(['glo_os_estagioAPP' => $estagioAPP]);
        session(['glo_os_eat_cod' => $dadosRequisicao[0]->req_eat]);
        session(['glo_os_dadosServicoSelecionado' => '']);
        session(['glo_os_dadosTMO' => '']);
        session(['glo_os_dadosTmoSelecionada' => '']);
        session(['glo_os_subEstagioRequisicao' => '']);
        session(['glo_os_dadosSrvIni' => $dadosSrvIni]);
        session(['glo_os_dadosSrvFin' => $dadosSrvFin]);
        session(['glo_os_dadosSrvApr' => $dadosSrvApr]);

        return view('/lancamentos/servico/painelAberturaOS');
    }

    //Metodo de abertura de um novo servico para a requisição
    public function abrirServicoRequisicao($empresa, $area, $setor, $estagioAPP, $subEstagioRequisicao, $requisicao)
    {
        $dadosTMO = DB::table('parametros_srv_tmos')->where('tmo_emp', $empresa)->where('tmo_are', $area)->where('tmo_set', $setor)->where('tmo_sts', 'A')->orderby('tmo_cod','asc')->get();

        session(['glo_os_estagioAPP' => $estagioAPP]);
        session(['glo_os_subEstagioRequisicao' => $subEstagioRequisicao]);
        session(['glo_os_dadosTMO' => $dadosTMO]);
        session(['glo_os_dadosTmoSelecionada' => '']);
        
        return view('/lancamentos/servico/painelAberturaOS',['requisicaoBTN' => $requisicao]);
    }

    //Metodo de seleção da TMO do serviço para inclusão na requisição
    public function selecionarTMO($empresa, $area, $setor, $codigo, $estagioAPP, $subEstagioRequisicao, $requisicao)
    {
        $dadosTmoSelecionada = DB::table('parametros_srv_tmos')->where('tmo_emp', $empresa)->where('tmo_are', $area)->where('tmo_set', $setor)->where('tmo_cod', $codigo)->orderby('tmo_cod','asc')->get();

        session(['glo_os_estagioAPP' => $estagioAPP]);
        session(['glo_os_subEstagioRequisicao' => $subEstagioRequisicao]);
        session(['glo_os_dadosTmoSelecionada' => $dadosTmoSelecionada]);
        
        return view('/lancamentos/servico/painelAberturaOS',['requisicaoBTN' => $requisicao]);
    }

    //Metodo de abertura da edição do serviço da requisição
    public function consultaServicoRequisicao($empresa, $numOS, $requisicao, $sequencia, $codTMO, $estagioAPP, $subEstagioRequisicao)
    {
        $dadosServico = DB::table('lancamento_srv_os_servicos')->where('srv_emp', $empresa)->where('srv_nos', $numOS)->where('srv_req', $requisicao)->where('srv_seq', $sequencia)->where('srv_tmo', $codTMO)->get();

        session(['glo_os_estagioAPP' => $estagioAPP]);
        session(['glo_os_subEstagioRequisicao' => $subEstagioRequisicao]);
        session(['glo_os_dadosServicoSelecionado' => $dadosServico]);
        session(['glo_os_dadosTMO' => '']);
        session(['glo_os_dadosTmoSelecionada' => '']);
        
        return view('/lancamentos/servico/painelAberturaOS',['requisicaoBTN' => $requisicao]);
    }

    //Metodo de abertura da edição do serviço da requisição
    public function previsaoEntregaOS($empresa, $numOS)
    {
        $empresaEndereco = DB::table('cadastro_empresa_enderecos')->where('endereco_empresa_codigo', $empresa)->where('endereco_principal', 'S')->get();
        $requisicoesOS = DB::table('lancamento_srv_os_requisicoes')->where('req_emp', $empresa)->where('req_nos', $numOS)->orderby('req_seq', 'asc')->get();
        $servicosOS = DB::table('lancamento_srv_os_servicos')->where('srv_emp', $empresa)->where('srv_nos', $numOS)->orderby('srv_req', 'asc')->orderby('srv_seq', 'asc')->get();
        $dadosOS = DB::table('lancamento_srv_os')->where('os_emp', $empresa)->where('os_nos', $numOS)->get();

        session(['glo_os_dadosEmpresaEndereco' => $empresaEndereco]);
        session(['glo_os_dadosRequisicoes' => $requisicoesOS]);
        session(['glo_os_dadosOS' => $dadosOS]);
        session(['glo_os_dadosServicos' => $servicosOS]);
        session(['glo_os_estagioAPP' => 'PREVISAO_ENTREGA']);
        session(['glo_os_subEstagioRequisicao' => '']);
        session(['glo_os_dadosServicoSelecionado' => '']);
        session(['glo_os_dadosTMO' => '']);
        session(['glo_os_dadosTmoSelecionada' => '']);
        
        return view('/lancamentos/servico/painelAberturaOS');
    }

    //Metodo de abertura da edição do serviço da requisição
    public function orcamentoOS($empresa, $numOS)
    {
        $empresaEndereco = DB::table('cadastro_empresa_enderecos')->where('endereco_empresa_codigo', $empresa)->where('endereco_principal', 'S')->get();
        $requisicoesOS = DB::table('lancamento_srv_os_requisicoes')->where('req_emp', $empresa)->where('req_nos', $numOS)->orderby('req_seq', 'asc')->get();
        $servicosOS = DB::table('lancamento_srv_os_servicos')->where('srv_emp', $empresa)->where('srv_nos', $numOS)->orderby('srv_req', 'asc')->orderby('srv_seq', 'asc')->get();
        $dadosOS = DB::table('lancamento_srv_os')->where('os_emp', $empresa)->where('os_nos', $numOS)->get();

        session(['glo_os_dadosEmpresaEndereco' => $empresaEndereco]);
        session(['glo_os_dadosRequisicoes' => $requisicoesOS]);
        session(['glo_os_dadosOS' => $dadosOS]);
        session(['glo_os_dadosServicos' => $servicosOS]);
        session(['glo_os_estagioAPP' => 'ORCAMENTO_OS']);
        session(['glo_os_subEstagioRequisicao' => '']);
        session(['glo_os_dadosServicoSelecionado' => '']);
        session(['glo_os_dadosTMO' => '']);
        session(['glo_os_dadosTmoSelecionada' => '']);
        
        return view('/lancamentos/servico/painelAberturaOS');
    }

    public function orcamentoGerarPDF($empresa, $numOS)
    {
        $empresaEndereco = DB::table('cadastro_empresa_enderecos')->where('endereco_empresa_codigo', $empresa)->where('endereco_principal', 'S')->get();
        $requisicoesOS = DB::table('lancamento_srv_os_requisicoes')->where('req_emp', $empresa)->where('req_nos', $numOS)->orderby('req_seq', 'asc')->get();
        $servicosOS = DB::table('lancamento_srv_os_servicos')->where('srv_emp', $empresa)->where('srv_nos', $numOS)->orderby('srv_req', 'asc')->orderby('srv_seq', 'asc')->get();
        $dadosOS = DB::table('lancamento_srv_os')->where('os_emp', $empresa)->where('os_nos', $numOS)->get();
        $dadosEmpresa = DB::table('cadastro_empresas')->where('empresa_codigo',$empresa)->get();
        $dadosCliente = DB::table('cadastro_clientes')->where('cliente_codigo',$dadosOS[0]->os_cli)->get();
        $dadosClienteEndereco = DB::table('cadastro_cliente_enderecos')->where('endereco_cliente_codigo',$dadosOS[0]->os_cli)->where('endereco_seq',$dadosOS[0]->os_cli_end)->get();

        /* Código original utilizando a lib Barryvdh\DomPDF
        // Carregar a string com o HTML/conteúdo e determinar a orientação e o tamanho do arquivo
        //$pdf = PDF::loadView('/lancamentos/servico/impressao/orcamentoPDF', ['glo_os_empresa' => $empresa, 'glo_os_dadosClienteEndereco' => $dadosClienteEndereco, 'glo_os_dadosCliente' => $dadosCliente, 'glo_os_dadosEmpresa' => $dadosEmpresa, 'glo_os_dadosEmpresaEndereco' => $empresaEndereco, 'glo_os_dadosRequisicoes' => $requisicoesOS, 'glo_os_dadosOS' => $dadosOS, 'glo_os_dadosServicos' => $servicosOS])->setPaper('a4', 'portrait');
        $html = response()->view('/lancamentos/servico/impressao/orcamentoPDF', ['glo_os_empresa' => $empresa, 'glo_os_dadosClienteEndereco' => $dadosClienteEndereco, 'glo_os_dadosCliente' => $dadosCliente, 'glo_os_dadosEmpresa' => $dadosEmpresa, 'glo_os_dadosEmpresaEndereco' => $empresaEndereco, 'glo_os_dadosRequisicoes' => $requisicoesOS, 'glo_os_dadosOS' => $dadosOS, 'glo_os_dadosServicos' => $servicosOS])->getContent();

        // pass html to pdf
        $pdf = PDF::loadHTML($html)->setPaper('a4', 'portrait');

        // Fazer o download do arquivo
        //return $pdf->download('orcamento.pdf');
        return $pdf->stream(); 
        */
        
        // Opções de configuração
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $options->set('pdfBackend', 'auto');
        //$options->set('dpi', 150);
        
        // Cria uma instância do Dompdf com opções padrão
        $dompdf = new Dompdf($options);

        // Carrega o HTML da View para ser convertido em PDF
        $html = view('/lancamentos/servico/impressao/orcamentoPDF', ['glo_os_empresa' => $empresa, 'glo_os_dadosClienteEndereco' => $dadosClienteEndereco, 'glo_os_dadosCliente' => $dadosCliente, 'glo_os_dadosEmpresa' => $dadosEmpresa, 'glo_os_dadosEmpresaEndereco' => $empresaEndereco, 'glo_os_dadosRequisicoes' => $requisicoesOS, 'glo_os_dadosOS' => $dadosOS, 'glo_os_dadosServicos' => $servicosOS])->render();

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

    //Função que atualiza o valor total da OS, executada ao inserir, atualizar, excluir, reabrir, suspender, cancelar e excluir serviços e excluir requisição
    static function atualizaValorOS($empresa, $numOS)
    {
        $sum_valores = DB::table('lancamento_srv_os_requisicoes')->selectRaw('coalesce(sum(req_qtd_hr),0) as req_qtd_hr, 
                                                                                coalesce(sum(req_vlr),0) as req_vlr, 
                                                                                coalesce(sum(req_vls),0) as req_vls, 
                                                                                coalesce(sum(req_vlp),0) as req_vlp, 
                                                                                coalesce(sum(req_vlt),0) as req_vlt, 
                                                                                coalesce(sum(req_val_des_srv),0) as req_val_des_srv')->where('req_emp',$empresa)->where('req_nos',$numOS)->get();

        $descontoOS = DB::table('lancamento_srv_os')->select('os_val_des')->where('os_emp',$empresa)->where('os_nos',$numOS)->get();

        $valLiquido = $sum_valores[0]->req_vlt - $descontoOS[0]->os_val_des;

        //Busca os dados da OS
        $resulOS = DB::table('lancamento_srv_os')->where('os_emp',$empresa)->where('os_nos',$numOS)->get();

        //Se o cálculo automático estiver desligado manter a qtd. de horas da previsão
        if($resulOS[0]->os_cal_aut_pre_ent == 'N'){
            $qtd_hr_pe = $resulOS[0]->os_qtd_hr_pre_ent;
        }else{
            $qtd_hr_pe = $sum_valores[0]->req_qtd_hr;
        }

        $atualizaOS = DB::table('lancamento_srv_os')
            ->where('os_emp', $empresa)
            ->where('os_nos', $numOS)
            ->update(['os_qtd_hr' => $sum_valores[0]->req_qtd_hr,
                'os_vlt' => $valLiquido,
                'os_vlr' => $sum_valores[0]->req_vlr,
                'os_vls' => $sum_valores[0]->req_vls,
                'os_vlp' => $sum_valores[0]->req_vlp,
                'os_val_des_srv' => $sum_valores[0]->req_val_des_srv,
                'os_qtd_hr_pre_ent' => $qtd_hr_pe]);  
    }

    //Função que atualiza o valor total da requisição, executada ao inserir, atualizar, excluir, reabrir, suspender, cancelar e excluir serviços e excluir requisição
    static function atualizaValorRequisicao($empresa, $numOS, $requisicao)
    {
        $sum_valores = DB::table('lancamento_srv_os_servicos')->selectRaw('coalesce(sum(srv_qhr),0) as srv_qhr, coalesce(sum(srv_vts),0) as srv_vts, coalesce(sum(srv_vtl),0) as srv_vtl, coalesce(sum(srv_val_des),0) as srv_val_des')->where('srv_emp',$empresa)->where('srv_nos',$numOS)->where('srv_req',$requisicao)->whereIn('srv_sts',['A','F','E'])->get();

        //$dadosReq = DB::table('lancamento_srv_os_requisicoes')->where('req_emp',$empresa)->where('req_seq',$requisicao)->where('req_nos',$numOS)->get();

        $atualizaReq = DB::table('lancamento_srv_os_requisicoes')
            ->where('req_emp', $empresa)
            ->where('req_nos', $numOS)
            ->where('req_seq', $requisicao)
            ->update(['req_qtd_hr' => $sum_valores[0]->srv_qhr,
                'req_vlr' => $sum_valores[0]->srv_vts,
                'req_vls' => $sum_valores[0]->srv_vts,
                'req_vlt' => $sum_valores[0]->srv_vtl,
                'req_val_des' => $sum_valores[0]->srv_val_des,
                'req_val_des_srv' => $sum_valores[0]->srv_val_des ]);  
    }

    //Função que atualiza a previsão de entrega do serviço da OS
    static function atualizaPrevEntrega($empresa, $numOS)
    {
        //Busca os dados da OS
        $resulOS = DB::table('lancamento_srv_os')->where('os_emp',$empresa)->where('os_nos',$numOS)->get();

        //Se o cálculo automático estiver desligado não deve calcular ao alterar uma requisição
        if($resulOS[0]->os_cal_aut_pre_ent == 'N'){
            return;
        }

        //Busca horas de inicio e termino de expediente
        /*$hrIni = DB::table('parametros_srv_empresas')->select('parsrv_hr_ini_ex')->where('parsrv_emp',$empresa)->get();
        $hrFin = DB::table('parametros_srv_empresas')->select('parsrv_hr_fin_ex')->where('parsrv_emp',$empresa)->get();

        //Monta a data e hora de inicio e final de expediente com a data e hora da abertura da os
        $hrInicio = Helper::formataHoraMinuto($hrIni[0]->parsrv_hr_ini_ex).':00';
        $hrFinal = Helper::formataHoraMinuto($hrFin[0]->parsrv_hr_fin_ex).':00';
        $hrAbe = date('H:i:s', strtotime($resulOS[0]->os_dha));
        $dtAbe = date('Y-m-d', strtotime($resulOS[0]->os_dha));

        //Monta a data e hora inicial da previsão de entrega para calculo com o tempo total da os
        if($hrAbe >= $hrInicio && $hrAbe <= $hrFinal){

            $hrPrev = $hrAbe;
            $dtPrev = $dtAbe;

        }else{

            if($hrAbe < $hrInicio){
                $hrPrev = $hrInicio;
                $dtPrev = $dtAbe;
            }else{
                $dtPrev = date('Y-m-d', strtotime($dtAbe.' +1 day'));
                $hrPrev = $hrInicio;
            }
        }

        //Busca a diferença em minutos da hora da previsão inicial para o final do expediente
        $dataIniDif = new DateTime($dtPrev.' '.$hrPrev);
        $dataFinDif = new DateTime($dtPrev.' '.$hrFinal);

        $diff = $dataIniDif->diff($dataFinDif);
        $horas = $diff->h + ($diff->days * 24);
        $min = $diff->i;

        $minDif = $min + ($horas * 60);

        //Calcula os minutos do tempo de serviço da os
        $minSrv = $resulOS[0]->os_qtd_hr * 60;

        //Calcula a nova hora de previsão de entrega
        if($minDif >= $minSrv){
            $novaHrPrevEnt = date('Hi', strtotime($dtPrev.' '.$hrPrev.' +'.$minSrv.' minutes'));
            $novaDtPrevEnt = $dtPrev;
        }else{

            $minRest = $minSrv - $minDif;

            $novaDtPrevEnt = date('Y-m-d', strtotime($dtPrev.' +1 day'));

            //Busca a diferença em minutos da hora de inicio e final de expediente
            $dataIniDif = new DateTime($novaDtPrevEnt.' '.$hrInicio);
            $dataFinDif = new DateTime($novaDtPrevEnt.' '.$hrFinal);

            $diff = $dataIniDif->diff($dataFinDif);
            $horas = $diff->h + ($diff->days * 24);
            $min = $diff->i;

            $tempoExp = $min + ($horas * 60);

            //Calcula a nova data e hora enquanto existir minutos restantes
            while($minRest > 0){
                
                if($tempoExp >= $minRest){
                    $novaHrPrevEnt = date('Hi', strtotime($novaDtPrevEnt.' '.$hrInicio.' +'.$minRest.' minutes'));
                    $minRest = 0;
                }else{
                    $minRest = $minRest - $tempoExp;
                    $novaDtPrevEnt = date('Y-m-d', strtotime($novaDtPrevEnt.' +1 day'));
                }
            }
        }
        */

        //Pega Data/Hora Abertura OS
        $hrAbe = date('H:i:s', strtotime($resulOS[0]->os_dha));
        $dtAbe = date('Y-m-d', strtotime($resulOS[0]->os_dha));

        //Busca os horários de expediente e intervalo/almoço da empresa
        $businessHours = HelperControleProducao::geraBusinessHoursEmpPHP($empresa);
        $lunchBreaks = HelperControleProducao::geraLunchHoursEmpPHP($empresa);

        $tempoSrv = Helper::convertHrCentToHrSexa($resulOS[0]->os_qtd_hr);

        $dataHoraPrev = HelperControleProducao::calculaPrevTerminoSrv($dtAbe, $hrAbe, $tempoSrv, $businessHours, $lunchBreaks);
        
        $novaDtPrevEnt = date('Y-m-d', strtotime($dataHoraPrev));
        $novaHrPrevEnt = date('Hi', strtotime($dataHoraPrev));

        DB::table('lancamento_srv_os')
        ->where('os_emp', $empresa)
        ->where('os_nos', $numOS)
        ->update(['os_dpe' => $novaDtPrevEnt,
            'os_hpe' => $novaHrPrevEnt]);  

        DB::table('lancamento_srv_exe_tarefas')
        ->where('exetrf_emp', $empresa)
        ->where('exetrf_nos', $numOS)
        ->update(['exetrf_dt_prev_ent' => $novaDtPrevEnt,
            'exetrf_hr_prev_ent' => $novaHrPrevEnt]);  
    }

    //Função que atualiza a previsão de entrega do serviço da OS via Ajax
    public function atualizaPrevEntregaAjax($empresa, $numOS, $qtdHoras)
    {
        //Busca os dados da OS
        $resulOS = DB::table('lancamento_srv_os')->select('os_dha')->where('os_emp',$empresa)->where('os_nos',$numOS)->get();

        /*
        //Busca horas de inicio e termino de expediente
        $hrIni = DB::table('parametros_srv_empresas')->select('parsrv_hr_ini_ex')->where('parsrv_emp',$empresa)->get();
        $hrFin = DB::table('parametros_srv_empresas')->select('parsrv_hr_fin_ex')->where('parsrv_emp',$empresa)->get();

        //Monta a data e hora de inicio e final de expediente com a data e hora da abertura da os
        $hrInicio = Helper::formataHoraMinuto($hrIni[0]->parsrv_hr_ini_ex).':00';
        $hrFinal = Helper::formataHoraMinuto($hrFin[0]->parsrv_hr_fin_ex).':00';
        $hrAbe = date('H:i:s', strtotime($resulOS[0]->os_dha));
        $dtAbe = date('Y-m-d', strtotime($resulOS[0]->os_dha));

        //Monta a data e hora inicial da previsão de entrega para calculo com o tempo total da os
        if($hrAbe >= $hrInicio && $hrAbe <= $hrFinal){

            $hrPrev = $hrAbe;
            $dtPrev = $dtAbe;

        }else{

            if($hrAbe < $hrInicio){
                $hrPrev = $hrInicio;
                $dtPrev = $dtAbe;
            }else{
                $dtPrev = date('Y-m-d', strtotime($dtAbe.' +1 day'));
                $hrPrev = $hrInicio;
            }
        }

        //Busca a diferença em minutos da hora da previsão inicial para o final do expediente
        $dataIniDif = new DateTime($dtPrev.' '.$hrPrev);
        $dataFinDif = new DateTime($dtPrev.' '.$hrFinal);

        $diff = $dataIniDif->diff($dataFinDif);
        $horas = $diff->h + ($diff->days * 24);
        $min = $diff->i;

        $minDif = $min + ($horas * 60);

        //Calcula os minutos do tempo de serviço da os
        $minSrv = $qtdHoras * 60;

        //Calcula a nova hora de previsão de entrega
        if($minDif >= $minSrv){
            $novaHrPrevEnt = date('Hi', strtotime($dtPrev.' '.$hrPrev.' +'.$minSrv.' minutes'));
            $novaDtPrevEnt = $dtPrev;
        }else{

            $minRest = $minSrv - $minDif;

            $novaDtPrevEnt = date('Y-m-d', strtotime($dtPrev.' +1 day'));

            //Busca a diferença em minutos da hora de inicio e final de expediente
            $dataIniDif = new DateTime($novaDtPrevEnt.' '.$hrInicio);
            $dataFinDif = new DateTime($novaDtPrevEnt.' '.$hrFinal);

            $diff = $dataIniDif->diff($dataFinDif);
            $horas = $diff->h + ($diff->days * 24);
            $min = $diff->i;

            $tempoExp = $min + ($horas * 60);

            //Calcula a nova data e hora enquanto existir minutos restantes
            while($minRest > 0){
                
                if($tempoExp >= $minRest){
                    $novaHrPrevEnt = date('Hi', strtotime($novaDtPrevEnt.' '.$hrInicio.' +'.$minRest.' minutes'));
                    $minRest = 0;
                }else{
                    $minRest = $minRest - $tempoExp;
                    $novaDtPrevEnt = date('Y-m-d', strtotime($novaDtPrevEnt.' +1 day'));
                }
            }
        }
        */

        //Pega Data/Hora Abertura OS
        $hrAbe = date('H:i:s', strtotime($resulOS[0]->os_dha));
        $dtAbe = date('Y-m-d', strtotime($resulOS[0]->os_dha));

        //Busca os horários de expediente e intervalo/almoço da empresa
        $businessHours = HelperControleProducao::geraBusinessHoursEmpPHP($empresa);
        $lunchBreaks = HelperControleProducao::geraLunchHoursEmpPHP($empresa);

        $tempoSrv = Helper::convertHrCentToHrSexa($qtdHoras);

        $dataHoraPrev = HelperControleProducao::calculaPrevTerminoSrv($dtAbe, $hrAbe, $tempoSrv, $businessHours, $lunchBreaks);
        
        $novaDtPrevEnt = date('d/m/Y', strtotime($dataHoraPrev));
        $novaHrPrevEnt = date('H:i', strtotime($dataHoraPrev));

        /*$novaDtPrevEnt = Helper::formataData($novaDtPrevEnt);
        $novaHrPrevEnt = Helper::formataHoraMinuto($novaHrPrevEnt);*/

        $prevEnt_ajax[] = array(
            'hora'	=> $novaHrPrevEnt,
            'data' => $novaDtPrevEnt,
        );

        return response()->json(['success' => true, 'prevEnt_ajax' => $prevEnt_ajax]);
    }

    //Metodo de abertura da edição do serviço da requisição
    public function totalOS($empresa, $numOS)
    {
        $empresaEndereco = DB::table('cadastro_empresa_enderecos')->where('endereco_empresa_codigo', $empresa)->where('endereco_principal', 'S')->get();
        $requisicoesOS = DB::table('lancamento_srv_os_requisicoes')->where('req_emp', $empresa)->where('req_nos', $numOS)->orderby('req_seq', 'asc')->get();
        $servicosOS = DB::table('lancamento_srv_os_servicos')->where('srv_emp', $empresa)->where('srv_nos', $numOS)->orderby('srv_req', 'asc')->orderby('srv_seq', 'asc')->get();
        $dadosOS = DB::table('lancamento_srv_os')->where('os_emp', $empresa)->where('os_nos', $numOS)->get();

        session(['glo_os_dadosEmpresaEndereco' => $empresaEndereco]);
        session(['glo_os_dadosRequisicoes' => $requisicoesOS]);
        session(['glo_os_dadosOS' => $dadosOS]);
        session(['glo_os_dadosServicos' => $servicosOS]);
        session(['glo_os_estagioAPP' => 'TOTAIS_OS']);
        session(['glo_os_subEstagioRequisicao' => '']);
        session(['glo_os_dadosServicoSelecionado' => '']);
        session(['glo_os_dadosTMO' => '']);
        session(['glo_os_dadosTmoSelecionada' => '']);
        
        return view('/lancamentos/servico/painelAberturaOS');
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Executa o encerramento da OS
    |----------------------------------------------------------------------------------------------------
    */
    public function encerraOS($empresa, $numOS)
    {

        $dadosTS = DB::table('lancamento_srv_os_requisicoes')
        ->select(
            'req_tos',
            'req_cat',
            DB::raw('SUM(req_vls) as valor_servico'),
            DB::raw('SUM(req_vlp) as valor_pecas'),
            DB::raw('sum(req_vlp) + sum(req_vls) as valor_total'),
            DB::raw('SUM(req_val_des) as valor_desconto'),
            DB::raw('SUM(req_vlt) as valor_liquido'),
            DB::raw("CASE 
                        WHEN BOOL_OR(req_sts <> 'F') THEN 'Andamento' 
                        ELSE 'Finalizado' 
                    END as ts_status")
        )
        ->where('req_emp', 'E00001')
        ->where('req_nos', 65)
        ->groupBy('req_tos', 'req_cat')
        ->get();

        $empresaEndereco = DB::table('cadastro_empresa_enderecos')->where('endereco_empresa_codigo', $empresa)->where('endereco_principal', 'S')->get();
        $requisicoesOS = DB::table('lancamento_srv_os_requisicoes')->where('req_emp', $empresa)->where('req_nos', $numOS)->orderby('req_seq', 'asc')->get();
        $servicosOS = DB::table('lancamento_srv_os_servicos')->where('srv_emp', $empresa)->where('srv_nos', $numOS)->orderby('srv_req', 'asc')->orderby('srv_seq', 'asc')->get();
        $dadosOS = DB::table('lancamento_srv_os')->where('os_emp', $empresa)->where('os_nos', $numOS)->get();

        session(['glo_os_dadosEmpresaEndereco' => $empresaEndereco]);
        session(['glo_os_dadosRequisicoes' => $requisicoesOS]);
        session(['glo_os_dadosOS' => $dadosOS]);
        session(['glo_os_dadosServicos' => $servicosOS]);
        session(['glo_os_estagioAPP' => 'FECHAMENTO_OS']);
        session(['glo_os_subEstagioRequisicao' => '']);
        session(['glo_os_dadosServicoSelecionado' => '']);
        session(['glo_os_dadosTMO' => '']);
        session(['glo_os_dadosTmoSelecionada' => '']);
        session(['glo_os_dadosTS_fechamento_os' => $dadosTS]);


        return view('/lancamentos/servico/painelAberturaOS');
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Executa a Geração da NF e finaliza a OS
    |----------------------------------------------------------------------------------------------------
    */
    public function geraNF(Request $request, $empresa, $numOS)
    {

        $data_req = DB::table('lancamento_srv_os_requisicoes')->where('req_emp', $empresa)->where('req_nos', $numOS)->orderby('req_seq', 'asc')->get();

        foreach($data_req as $requisicao){
            if($requisicao->req_sts != 'F'){
                return redirect()->back()->with('info', 'Não é possível finalizar a OS! Existem requisições em aberto!');
            }
        }

        // Se for a prazo e não for condição 98 nem 99 verificamos se tem valor de entrada
        if($request->formaPgt == 2 && $request->condPgt != '98' && $request->condPgt != '99'){

            $prazo = DB::table('faturamento_tab_cond_pagamento_parcelas')->where('tabcpg_pcl_codigo', $request->condPgt)->where('tabcpg_pcl_parcela', 1)->first();

            if($prazo->tabcpg_pcl_prazo == 0){

                $valEntrada = Helper::limpaValorMonetario($request->valEntrada);

                if($valEntrada == 0){
                    return redirect()->back()->with('info', 'Valor de Entrada é obrigatório para a condição de pagamento selecionada!');
                }

                $valTot = Helper::limpaValorMonetario($request->valTot);

                if($valEntrada > $valTot){
                    return redirect()->back()->with('info', 'Valor de Entrada não pode ser maior que o Valor Líquido da OS!');
                }
            }
        }

        /* ***** Formata dados para inserção ***** */
        if(!empty($request->perDesTot)){
            $perDesTot = Helper::limpaValorMonetario($request->perDesTot);
        }else{
            $perDesTot = 0;
        }

        if(!empty($request->valDesTot)){
            $valDesTot = Helper::limpaValorMonetario($request->valDesTot);
        }else{
            $valDesTot = 0;
        }

        if(!empty($request->valTot)){
            $valTot = Helper::limpaValorMonetario($request->valTot);
        }else{
            $valTot = 0;
        }

        if(!empty($request->valDesSrvPro)){
            $valDesSrvPro = Helper::limpaValorMonetario($request->valDesSrvPro);
        }else{
            $valDesSrvPro = 0;
        }

        if(!empty($request->valDesPec)){
            $valDesPec = Helper::limpaValorMonetario($request->valDesPec);
        }else{
            $valDesPec = 0;
        }

        if(!empty($request->valDAC)){
            $valDAC = Helper::limpaValorMonetario($request->valDAC);
        }else{
            $valDAC = 0;
        }

        if(!empty($request->valAju)){
            $valAju = Helper::limpaValorMonetario($request->valAju);
        }else{
            $valAju = 0;
        }

        if(!empty($request->valEntrada)){
            $valEntrada = Helper::limpaValorMonetario($request->valEntrada);
        }else{
            $valEntrada = 0;
        }

        /*
        if($request->formaPgt == 1){
            $valEntrada = $valTot;
        }
        */

        if(!empty($request->condPgt)){
            $condPgt = $request->condPgt;
        }else{
            $condPgt = '00';
        }

        $valorOS = DB::table('lancamento_srv_os')->where('os_emp', $empresa)->where('os_nos', $numOS)->first();

        //Calcula o total de desconto no fechamento da OS
        $valDesconto = $valorOS->os_val_des_srv + $valorOS->os_val_des_pro + $valDesTot + $valDesSrvPro + $valDesPec;

        $valBruto = ($valorOS->os_vls + $valorOS->os_vlp + $valDAC) - $valAju;

        $data_op = date('Y-m-d');

        //Inicia a transação
        DB::beginTransaction();

        /* ***** Atualiza os dados do Fechamento da OS ***** */
        DB::table('lancamento_srv_os')
        ->where('os_emp', $empresa)
        ->where('os_nos', $numOS)
        ->update([
            'os_per_des' => $perDesTot,
            'os_val_des' => $valDesconto,
            'os_vlt' => $valTot,
            'os_vlr' => $valBruto,
            'os_val_fin_des_srv' => $valDesSrvPro,
            'os_val_fin_des_pro' => $valDesPec,
            'os_val_fin_des' => $valDesTot,
            'os_val_dac' => $valDAC,
            'os_val_aju' => $valAju,
            'os_tipo_nf' => $request->tipoNF,
            'os_forma_pgt' => $request->formaPgt,
            'os_cond_pgt' => $condPgt,
            'os_val_ent' => $valEntrada,
        ]);
        
        $exec_fn = DB::select("select ret_sts, ret_msg from fn_lancamento_encerra_os('".$empresa."',".$numOS.",'".Auth::user()->usuario_codigo."','".$data_op."');");

        if($exec_fn[0]->ret_sts == '*'){
            //Falha, desfaz as alterações no banco de dados
            DB::rollBack();
            return redirect()->back()->with('error', $exec_fn[0]->ret_msg);
        }else{
            //Grava as alterações do banco
            DB::commit();
        }

        $empresaEndereco = DB::table('cadastro_empresa_enderecos')->where('endereco_empresa_codigo', $empresa)->where('endereco_principal', 'S')->get();
        $requisicoesOS = DB::table('lancamento_srv_os_requisicoes')->where('req_emp', $empresa)->where('req_nos', $numOS)->orderby('req_seq', 'asc')->get();
        $servicosOS = DB::table('lancamento_srv_os_servicos')->where('srv_emp', $empresa)->where('srv_nos', $numOS)->orderby('srv_req', 'asc')->orderby('srv_seq', 'asc')->get();
        $dadosOS = DB::table('lancamento_srv_os')->where('os_emp', $empresa)->where('os_nos', $numOS)->get();

        //Fazer a verificação se avia o cliente do encerramento da OS
        if($dadosOS[0]->os_cli_avs == "S"){

            $dadosEmp = DB::table('cadastro_empresas')->where('empresa_codigo', $dadosOS[0]->os_emp)->first();
            $dadosCli = DB::table('cadastro_clientes')->where('cliente_codigo', $dadosOS[0]->os_cli)->first();

             // Verificar se a empresa tem configurações de e-mail específicas
            if (!empty($dadosEmp->empresa_smtp_host) && !empty($dadosCli->cliente_email)) {

                // Configuração dinâmica do SMTP para o envio pela empresa
                config([
                    'mail.mailers.smtp_cliente.host' => $dadosEmp->empresa_smtp_host,
                    'mail.mailers.smtp_cliente.port' => $dadosEmp->empresa_smtp_port,
                    'mail.mailers.smtp_cliente.encryption' => $dadosEmp->empresa_smtp_encryption,
                    'mail.mailers.smtp_cliente.username' => $dadosEmp->empresa_smtp_username,
                    'mail.mailers.smtp_cliente.password' => $dadosEmp->empresa_smtp_password,
                ]);

                // Usar o mailer específico da empresa
                Mail::mailer('smtp_cliente')->to($dadosCli->cliente_email)->send(new EmailEncerraOS($dadosOS[0],$dadosEmp->empresa_smtp_from_address));
            }
        }

        session(['glo_os_dadosEmpresaEndereco' => $empresaEndereco]);
        session(['glo_os_dadosRequisicoes' => $requisicoesOS]);
        session(['glo_os_dadosOS' => $dadosOS]);
        session(['glo_os_dadosServicos' => $servicosOS]);
        session(['glo_os_estagioAPP' => 'TOTAIS_OS']);
        session(['glo_os_subEstagioRequisicao' => '']);
        session(['glo_os_dadosServicoSelecionado' => '']);
        session(['glo_os_dadosTMO' => '']);
        session(['glo_os_dadosTmoSelecionada' => '']);

        return redirect(route('painelOS.totalOS', ['empresa' => $empresa, 'numOS' => $numOS]))->with('success2', 'OS '.$numOS.' encerrada com sucesso!');
    }

    //Metodo de cancelamento da os
    public function cancelarOS(Request $request, $empresa, $numOS)
    {

        $cnt_nfs = DB::table('faturamento_nf_headers')->where('nfhdr_emp', $empresa)->where('nfhdr_num_ped', $numOS)->where('nfhdr_ori', '01')->count();

        if($cnt_nfs > 0){
            $nfs = DB::table('faturamento_nf_headers')->where('nfhdr_emp', $empresa)->where('nfhdr_num_ped', $numOS)->where('nfhdr_ori', '01')->get();

            if($nfs[0]->nfhdr_sts == 'G'){
                return redirect()->back()->with('error', 'Não é possível cancelar a OS! A NFS-e referente a OS já foi emitida!');
            }
        }

        $dadosOS = DB::table('lancamento_srv_os')->where('os_emp', $empresa)->where('os_nos', $numOS)->get();
        
        $data = date('Y-m-d');
        $hora = date('Hi');
        $dataHora = date('Y-m-d H:i:s');

        $usuario = Auth::user()->usuario_codigo;

        DB::table('lancamento_srv_os')
        ->where('os_emp', $empresa)
        ->where('os_nos', $numOS)
        ->update(['os_sts' => 'C',
            'os_dtc' => $data,
            'os_hrc' => $hora,
            'os_mot_can' => $request->canMot,
            'os_obs_can' => $request->obsMot,
            'os_res_can' => $usuario]);  

        DB::table('lancamento_srv_os_servicos')
        ->where('srv_emp', $empresa)
        ->where('srv_nos', $numOS)
        ->whereIn('srv_sts', ['A','E','S'])
        ->update(['srv_sts' => 'C',
            'srv_dhc' => $dataHora,
            'srv_res_can' => $usuario,
            'srv_mot_can' => $request->canMot]); 

        DB::table('lancamento_srv_exe_tarefas')
        ->where('exetrf_emp', $empresa)
        ->where('exetrf_nos', $numOS)
        ->whereIn('exetrf_sts', ['A','E','S'])
        ->update(['exetrf_sts' => 'C',
            'exetrf_dt_can_srv' => $data,
            'exetrf_hr_can_srv' => $hora,
            'exetrf_mot_can_srv' => $request->canMot,
            'exetrf_res_can' => $usuario]); 
        
        if($cnt_nfs > 0){

            //Atualiza os dados da NF
            DB::table('faturamento_nf_headers')
            ->where('nfhdr_emp', $empresa)
            ->where('nfhdr_num', $nfs[0]->nfhdr_num)
            ->where('nfhdr_ori', '01')
            ->update(['nfhdr_sts' => 'C']);

            DB::table('faturamento_nfs')
            ->where('nfs_emp', $empresa)
            ->where('nfs_nfhdr_num', $nfs[0]->nfhdr_num)
            ->where('nfs_origem', 'OS')
            ->update(['nfs_sts' => 'C']);
        }
        
        return redirect(route('situacaoOS.carregaOS', ['empresa' => $empresa, 'cliente' => $dadosOS[0]->os_cli, 'nos' => $numOS, 'estagioAPP' => 'PRINCIPAL']))->with('success2', 'OS '.$numOS.' cancelada com sucesso!');
    }

    //Metodo da troca do local de prestação do serviço da OS
    public function trocaLocSrv($empresa, $numOS)
    {
        $empresaEndereco = DB::table('cadastro_empresa_enderecos')->where('endereco_empresa_codigo', $empresa)->where('endereco_principal', 'S')->get();
        $dadosOS = DB::table('lancamento_srv_os')->where('os_emp', $empresa)->where('os_nos', $numOS)->get();

        session(['glo_os_dadosEmpresaEndereco' => $empresaEndereco]);
        session(['glo_os_dadosRequisicoes' => '']);
        session(['glo_os_dadosOS' => $dadosOS]);
        session(['glo_os_dadosServicos' => '']);
        session(['glo_os_estagioAPP' => 'TROCA_LOCAL_SERVICO']);
        session(['glo_os_subEstagioRequisicao' => '']);
        session(['glo_os_dadosServicoSelecionado' => '']);
        session(['glo_os_dadosTMO' => '']);
        session(['glo_os_dadosTmoSelecionada' => '']);
        
        return view('/lancamentos/servico/painelAberturaOS');
    }
}
