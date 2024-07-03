<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\LancamentoSrvOs;
use App\Models\LancamentoSrvOsRequisicoes;
use stdClass;
use DateTime;
use Dompdf\Dompdf;
use Dompdf\Options;
//use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

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

        session(['glo_os_dadosRequisicoes' => $dadosRequisicao]);
        session(['glo_os_dadosServico' => $dadosServico]);
        session(['glo_os_estagioAPP' => $estagioAPP]);
        session(['glo_os_eat_cod' => $dadosRequisicao[0]->req_eat]);
        session(['glo_os_dadosServicoSelecionado' => '']);
        session(['glo_os_dadosTMO' => '']);
        session(['glo_os_dadosTmoSelecionada' => '']);
        session(['glo_os_subEstagioRequisicao' => '']);

        return view('/lancamentos/servico/painelAberturaOS');
    }

    //Metodo de abertura de um novo servico para a requisição
    public function abrirServicoRequisicao($empresa, $area, $setor, $estagioAPP, $subEstagioRequisicao)
    {
        $dadosTMO = DB::table('parametros_srv_tmos')->where('tmo_emp', $empresa)->where('tmo_are', $area)->where('tmo_set', $setor)->where('tmo_sts', 'A')->orderby('tmo_cod','asc')->get();

        session(['glo_os_estagioAPP' => $estagioAPP]);
        session(['glo_os_subEstagioRequisicao' => $subEstagioRequisicao]);
        session(['glo_os_dadosTMO' => $dadosTMO]);
        session(['glo_os_dadosTmoSelecionada' => '']);
        
        return view('/lancamentos/servico/painelAberturaOS');
    }

    //Metodo de seleção da TMO do serviço para inclusão na requisição
    public function selecionarTMO($empresa, $area, $setor, $codigo, $estagioAPP, $subEstagioRequisicao)
    {
        $dadosTmoSelecionada = DB::table('parametros_srv_tmos')->where('tmo_emp', $empresa)->where('tmo_are', $area)->where('tmo_set', $setor)->where('tmo_cod', $codigo)->orderby('tmo_cod','asc')->get();

        session(['glo_os_estagioAPP' => $estagioAPP]);
        session(['glo_os_subEstagioRequisicao' => $subEstagioRequisicao]);
        session(['glo_os_dadosTmoSelecionada' => $dadosTmoSelecionada]);
        
        return view('/lancamentos/servico/painelAberturaOS');
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
        
        return view('/lancamentos/servico/painelAberturaOS');
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

        $atualizaOS = DB::table('lancamento_srv_os')
            ->where('os_emp', $empresa)
            ->where('os_nos', $numOS)
            ->update(['os_qtd_hr' => $sum_valores[0]->req_qtd_hr,
                'os_vlt' => $valLiquido,
                'os_vlr' => $sum_valores[0]->req_vlr,
                'os_vls' => $sum_valores[0]->req_vls,
                'os_vlp' => $sum_valores[0]->req_vlp,
                'os_val_des_srv' => $sum_valores[0]->req_val_des_srv]);  
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
        $resulOS = DB::table('lancamento_srv_os')->select('os_qtd_hr','os_dha')->where('os_emp',$empresa)->where('os_nos',$numOS)->get();

        //Verifica se a os foi aberta fora do expediente da empresa
        if($resulOS[0]->os_dha < date('Y-m-d',strtotime($resulOS[0]->os_dha)).' 18:00:00' && $resulOS[0]->os_dha >= date('Y-m-d',strtotime($resulOS[0]->os_dha)).' 09:00:00'){

            //Busca a diferença em minutos da data e hora da abertura da os com o final do expediente da empresa
            $dataIniOS = new DateTime($resulOS[0]->os_dha);
            $dataFinalDia = new DateTime(date('Y-m-d',strtotime($resulOS[0]->os_dha)).' 18:00:00');

            $diff = $dataIniOS->diff($dataFinalDia);
            $horas = $diff->h + ($diff->days * 24);
            $min = $diff->i;

            $minDif = $min + ($horas * 60);

        }else{

            //Verifica se foi antes ou depois do expediente a abertura da os
            if($resulOS[0]->os_dha < date('Y-m-d',strtotime($resulOS[0]->os_dha)).' 09:00:00'){

                //Busca a diferença em minutos da data e hora da abertura da os com o final do expediente da empresa
                $dataIniOS = new DateTime(date('Y-m-d',strtotime($resulOS[0]->os_dha)).' 09:00:00');
                $dataFinalDia = new DateTime(date('Y-m-d',strtotime($resulOS[0]->os_dha)).' 18:00:00');
    
                $diff = $dataIniOS->diff($dataFinalDia);
                $horas = $diff->h + ($diff->days * 24);
                $min = $diff->i;
    
                $minDif = $min + ($horas * 60);
    
            }else{

                $minDif = 0;    
            }
            
        }

        //Calcula os minutos do tempo de serviço da os
        $minSrv = $resulOS[0]->os_qtd_hr * 60;

        //Verifica se o tempo do serviço da os é maior que o tempo restante de expediente do dia da os
        if($minSrv < $minDif){

            //Monta a data da previsão de entrega com a data e hora da os mais o tempo do serviço
            $dtPrevEnt = date('Y-m-d', strtotime($resulOS[0]->os_dha));

            //Veriica se foi antes ou durante o expediente
            if($resulOS[0]->os_dha < date('Y-m-d',strtotime($resulOS[0]->os_dha)).' 09:00:00'){
                $hrPrevEnt = date('Hi', strtotime($dtPrevEnt.' 09:00:00 +'.$minSrv.' minutes'));
            }else{
                $hrPrevEnt = date('Hi', strtotime($resulOS[0]->os_dha.' +'.$minSrv.' minutes'));
            }

        }else{

            //Pega o tempo de serviço restante
            $minSrv = $minSrv - $minDif;

            $cnt_dias = 1;

            //Verifica a quantidade de minutos do espediente do dia da empresa
            //Até o momento ainda não existe parametro para isso e no futuro adicionar isso e assim está fixo entre 09:00 e 18:00
            $dhIni = new DateTime(date('Y-m-d').' 09:00:00');
            $dhFinal = new DateTime(date('Y-m-d').' 18:00:00');

            $diff = $dhIni->diff($dhFinal);
            $horas = $diff->h + ($diff->days * 24);
            $minDia = $horas * 60;

            //Verifica se o tempo restante de serviço é maior que o tempo de expediente do dia
            if($minSrv > $minDia){

                //Verifica quantos dias inteiros ainda será necessário o o tempo restante descontado esses dias
                while($minSrv > $minDia){
                    $cnt_dias += 1;
                    $minSrv = $minSrv - $minDia;
                }
            }

            //Monta a data de previsão de entrega pela data e hora de abertura da OS e o tempo dos serviços adicionados
            $dtPrevEnt = date('Y-m-d', strtotime($resulOS[0]->os_dha.' +'.$cnt_dias.' day'));
            $hrPrevEnt = date('Hi', strtotime($dtPrevEnt.' 09:00:00 +'.$minSrv.' minutes'));
        }

        DB::table('lancamento_srv_os')
            ->where('os_emp', $empresa)
            ->where('os_nos', $numOS)
            ->update(['os_dpe' => $dtPrevEnt,
                'os_hpe' => $hrPrevEnt]);  
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

    //Encerrar OS
    public function encerraOS($empresa, $numOS)
    {

        $data_req = DB::table('lancamento_srv_os_requisicoes')->where('req_emp', $empresa)->where('req_nos', $numOS)->orderby('req_seq', 'asc')->get();

        foreach($data_req as $requisicao){
            if($requisicao->req_sts != 'F'){
                return redirect()->back()->with('error', 'Não é possível finalizar a OS! Existem requisições em aberto!');
            }
        }

        //Inicia a transação
        DB::beginTransaction();

        $data_op = date('Y-m-d');
        
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
}
