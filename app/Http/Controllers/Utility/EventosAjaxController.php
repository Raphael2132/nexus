<?php

namespace App\Http\Controllers\Utility;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Helpers\Helper;
use App\Http\Helpers\HelperFormatSelect;

class EventosAjaxController extends Controller
{
    /*
    |----------------------------------------------------------------------------------------------------
    | Executa o AJAX de carregamento de Cidades do IBGE para Campo Auto-Complet de Filtros e Formulários
    |----------------------------------------------------------------------------------------------------
    */
    public function getCidadeIbgeAjax($uf)
    {  
        $ibgeUF = DB::table('ibge_estados')->where('ibge_sigla', $uf)->first();
        $cidades = DB::table('ibge_municipios')->select('ibge_mun_codigo', 'ibge_mun_nome')->where('ibge_mun_uf_codigo', $ibgeUF->ibge_codigo)->orderBy('ibge_mun_codigo', 'asc')->get();

        if(!empty($cidades[0])){

            foreach($cidades as $cidade) {
                $cidade_ajax[] = array(
                    'ibge'	=> $cidade->ibge_mun_codigo,
                    'cidade' => $cidade->ibge_mun_nome,
                );
            }  

            return response()->json(['success' => true, 'cidade_ajax' => $cidade_ajax, 'cidade_ajax_existe' => 'S']);

        }else{

            return response()->json(['success' => true, 'cidade_ajax' => null, 'cidade_ajax_existe' => 'N']);
        }
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Executa o AJAX de carregamento de Serviços da NFS-e do Grupo Selecionado para campo Select de Filtros e Formulários
    |----------------------------------------------------------------------------------------------------
    */
    public function getServicoNfsAjax($codigo)
    {  
        $servicos = DB::table('parametros_sis_servicos')->select('servico_codigo', 'servico_desc')->where('servico_grupo', $codigo)->orderby('servico_codigo', 'asc')->get();
       
        foreach($servicos as $servico) {
            
            $servicos_ajax[] = array(
                'id'	=> $servico->servico_codigo,
                'cod_servico' => $servico->servico_codigo.' - '.$servico->servico_desc,
            );
        }  

        return response()->json(['success' => true, 'servicos_ajax' => $servicos_ajax]);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Executa o AJAX de carregamento dos Setores da Empresa e Área Selecionada para campos Select de Filtros e Formulários
    |----------------------------------------------------------------------------------------------------
    */
    public function getEmpAreaSetoresAjax($area, $empresa)
    {  
        $setores = DB::table('parametros_sis_setores')->select('setor_codigo', 'setor_desc')->where('setor_empresa', $empresa)->where('setor_area', $area)->orderby('setor_codigo', 'asc')->get();

        if(!empty($setores[0])){

            foreach($setores as $setor) {
                $setores_ajax[] = array(
                    'id'	=> $setor->setor_codigo,
                    'cod_setor' => $setor->setor_codigo.' - '.$setor->setor_desc,
                );
            }  

            return response()->json(['success' => true, 'setores_ajax' => $setores_ajax, 'setores_ajax_existe' => 'S']);

        }else{

            return response()->json(['success' => true, 'setores_ajax' => null, 'setores_ajax_existe' => 'N']);
        }
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Executa o AJAX de carregamento dos Prestadores do Setor Selecionado para campos Select de Filtros e Formulários
    |----------------------------------------------------------------------------------------------------
    */
    public function getPrestadorSetorAjax($area, $setor, $empresa)
    {  
        $prestadores = DB::table('cadastro_prestadores')->select('prestador_codigo', 'prestador_nome')->where('prestador_empresa',$empresa)->where('prestador_set',$setor)->where('prestador_are',$area)->where('prestador_status','A')->orderBy('prestador_codigo', 'asc')->get();

        if(!empty($prestadores[0])){

            foreach($prestadores as $prestador) {
                $prestadores_ajax[] = array(
                    'id' => $prestador->prestador_codigo.' - '.$prestador->prestador_nome,
                );
            }  

            return response()->json(['success' => true, 'prestadores_ajax' => $prestadores_ajax, 'prestadores_ajax_existe' => 'S']);

        }else{
            
            return response()->json(['error' => true, 'prestadores_ajax' => null, 'prestadores_ajax_existe' => 'N']);
        }
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Executa o AJAX de carregamento dos Grupos de CNAE da Divisão Selecionada para campos Select de Filtros e Formulários
    |----------------------------------------------------------------------------------------------------
    */
    public function getCnaeGrupos($divisao)
    {  
        $cnaeGrp = DB::table('cnae_grupos')->where('cnaegrp_div', $divisao)->orderby('cnaegrp_grp', 'asc')->get();
       
        foreach($cnaeGrp as $grupo) {
            
            $grupos_ajax[] = array(
                'id'	=> $grupo->cnaegrp_grp,
                'cod_grupo' => $grupo->cnaegrp_grp.' - '.$grupo->cnaegrp_desc,
            );
        }  

        return response()->json(['success' => true, 'grupos_ajax' => $grupos_ajax]);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Executa o AJAX de carregamento dos Códigos CNAE pela Divisão e Grupo Selecionada para campos Select de Filtros e Formulários
    |----------------------------------------------------------------------------------------------------
    */
    public function getCnaeCodigos($divisao,$grupo)
    {  
        $cnaeCod = DB::table('cnae_codigos')->where('cnaesub_div', $divisao)->where('cnaesub_grp', $grupo)->orderby('cnaesub_cod', 'asc')->get();
       
        foreach($cnaeCod as $codigo) {
            
            $codigos_ajax[] = array(
                'id'	=> $codigo->cnaesub_cod,
                'cod_cnae' => $codigo->cnaesub_cod.' - '.$codigo->cnaesub_desc,
            );
        }  

        return response()->json(['success' => true, 'codigos_ajax' => $codigos_ajax]);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Executa o AJAX de carregamento dos Usuários para campos Select de Filtros e Formulários
    |----------------------------------------------------------------------------------------------------
    */
    public function getUsuarios()
    {  
        $usuarios = DB::table('users')->orderBy('usuario_codigo', 'asc')->get();

        foreach($usuarios as $usuario) {
            $usuarios_ajax[] = array(
                'codigo'	=> $usuario->usuario_codigo,
                'descricao' => $usuario->usuario_codigo.' - '.$usuario->name,
            );
        }  

        if(empty($usuarios_ajax)){
            $usuarios_ajax = '';
        }

        return response()->json(['success' => true, 'usuarios_ajax' => $usuarios_ajax]);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Executa o AJAX de carregamento dos Turnos da Empresa para campos Select de Filtros e Formulários
    |----------------------------------------------------------------------------------------------------
    */
    public function getTurnosEmpresa($empresa)
    {  
        $dataGerEmp = DB::table('parametros_ger_empresas')->where('parger_emp', $empresa)->get();

        if($dataGerEmp[0]->parger_tur_srv == 'N'){
            $dataTur = DB::table('parametros_ger_turnos')->where('partur_emp', $empresa)->where('partur_cod', '1')->orderBy('partur_cod', 'asc')->get();
        }else{
            $dataTur = DB::table('parametros_ger_turnos')->where('partur_emp', $empresa)->orderBy('partur_cod', 'asc')->get();
        }

        foreach($dataTur as $turno) {
            $turnos_ajax[] = array(
                'codigo'	=> $turno->partur_cod,
                'descricao' => $turno->partur_cod.' - '.$turno->partur_desc,
            );
        }  

        if(empty($turnos_ajax)){
            $turnos_ajax = '';
        }

        return response()->json(['success' => true, 'turnos_ajax' => $turnos_ajax]);
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Executa o AJAX de carregamento do Dia de Funcionamento da Empresa para campos Select de Filtros e Formulários
    |----------------------------------------------------------------------------------------------------
    */
    public function getDiaFuncionamentoEmpresa($empresa)
    {  
        $dataGerEmp = DB::table('parametros_ger_empresas')->where('parger_emp', $empresa)->get();

        $funcionamento = $dataGerEmp[0]->parger_dia_fun;

        return response()->json(['success' => true, 'funcionamento' => $funcionamento]);
    }
    
    /*
    |----------------------------------------------------------------------------------------------------
    | Executa o AJAX de carregamento dos Razões pelo Tipo para campos Select de Filtros e Formulários
    |----------------------------------------------------------------------------------------------------
    */
    public function getRazoes($razao, $empresa)
    {  
        $query = "
            SELECT distinct 
                razao_codigo, 
                razao_nome
            FROM financeiro_razoes
            WHERE 
                razao_empresa = '".$empresa."' AND 
                razao_tipo = '".$razao."'
            ORDER BY 
                razao_codigo ASC
        ";
        $dataRazao = DB::select($query);

        if(!empty($dataRazao[0])){

            foreach($dataRazao as $razao) {
                $razoes_ajax[] = array(
                    'cod'	=> $razao->razao_codigo,
                    'desc_razao' => $razao->razao_codigo.' - '.$razao->razao_nome,
                );
            }  

            return response()->json(['success' => true, 'razoes_ajax' => $razoes_ajax, 'razoes_ajax_existe' => 'S']);

        }else{

            return response()->json(['success' => true, 'razoes_ajax' => null, 'razoes_ajax_existe' => 'N']);
        }
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Executa o AJAX de verificação se a condição de pagamento utiliza valor de entrada
    |----------------------------------------------------------------------------------------------------
    */
    public function getValEntCondPgt($condPgt)
    {  
        $dataPgt = DB::table('faturamento_tab_cond_pagamento_parcelas')->where('tabcpg_pcl_codigo', $condPgt)->where('tabcpg_pcl_parcela', 1)->first();

        $dataCond = DB::table('faturamento_tab_cond_pagamentos')->where('tabcpg_codigo', $condPgt)->first();

        if($dataPgt->tabcpg_pcl_prazo > 0){

            return response()->json(['success' => true, 'inf_val_ent' => 'N', 'tip_val_ent' => $dataCond->tabcpg_tip_ent, 'qtd_parcela' => $dataCond->tabcpg_qtd_pcl]);

        }else{

            return response()->json(['success' => true, 'inf_val_ent' => 'S', 'tip_val_ent' => $dataCond->tabcpg_tip_ent, 'qtd_parcela' => $dataCond->tabcpg_qtd_pcl]);
        }
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Executa o AJAX de carregamento das parcelas do Cartão de Crédito
    |----------------------------------------------------------------------------------------------------
    */
    public function getParcelasCartaoCredito($adm, $empresa, $valor)
    {  
        $ano = date('Y');

        $data = DB::table('financeiro_razoes')
        ->leftJoin('cadastro_cartao_administradoras', 'cadastro_cartao_administradoras.administradora_codigo', '=', 'financeiro_razoes.razao_adm_cod')
        ->where('financeiro_razoes.razao_tipo', 'CC')
        ->where('financeiro_razoes.razao_codigo', $adm)
        ->where('financeiro_razoes.razao_ano', $ano)
        ->where('financeiro_razoes.razao_empresa', $empresa)
        ->select('cadastro_cartao_administradoras.administradora_parcela')
        ->first();

        if(!empty($data)){

            $parcelaMax = $data->administradora_parcela; // Número máximo de parcelas permitidas

            $valor = str_replace(['.', ','], ['', '.'], $valor); // Remove milhares e troca vírgula por ponto
            $valor = floatval($valor); // Converte para número

            // Gerar opções do select com os valores parcelados
            for ($i = 1; $i <= $parcelaMax; $i++) {

                $valPar = $valor / $i; // Calcula o valor de cada parcela
                $valPar = Helper::formataValorMonetario($valPar); // Formata para moeda

                $parcelas_ajax[] = array(
                    'qtd'	=> $i,
                    'qtd_valor' => $i . 'x (R$' . $valPar . ')',
                );
            }

            return response()->json(['success' => true, 'parcelas_ajax' => $parcelas_ajax, 'parcelas_ajax_existe' => 'S']);

        }else{

            return response()->json(['success' => true, 'parcelas_ajax' => null, 'parcelas_ajax_existe' => 'N']);
        }
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Executa o AJAX de carregamento do tipo de crédito de pequenas despesas / receitas do financeiro
    |----------------------------------------------------------------------------------------------------
    */
    public function getTipoCreditoPagRec($tabela)
    {  
        $data = DB::table('financeiro_tab_tipo_creditos')->where('tabtcr_tab', $tabela)->orderBy('tabtcr_cod', 'asc')->get();

        if(!$data->isEmpty()){

            foreach($data as $credito) {

                $credito_ajax[] = array(
                    'cod' => $credito->tabtcr_cod,
                    'desc' => $credito->tabtcr_cod.' - '.$credito->tabtcr_des,
                );
            }  

            return response()->json(['success' => true, 'credito_ajax' => $credito_ajax, 'credito_ajax_existe' => 'S']);

        }else{

            return response()->json(['success' => true, 'credito_ajax' => null, 'credito_ajax_existe' => 'N']);
        }
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Executa o AJAX de carregamento das parcelas do Cartão Corpotativo
    |----------------------------------------------------------------------------------------------------
    */
    public function getParcelasCartaoCorporativo($adm, $empresa, $valor)
    {  
        $ano = date('Y');

        $data = DB::table('parametros_fin_corporativo_cartoes')
        ->where('parcco_adm', $adm)
        ->where('parcco_emp', $empresa)
        ->select('parcco_par')
        ->first();

        if(!empty($data)){

            $parcelaMax = $data->parcco_par; // Número máximo de parcelas permitidas

            $valor = str_replace(['.', ','], ['', '.'], $valor); // Remove milhares e troca vírgula por ponto
            $valor = floatval($valor); // Converte para número

            // Gerar opções do select com os valores parcelados
            for ($i = 1; $i <= $parcelaMax; $i++) {

                $valPar = $valor / $i; // Calcula o valor de cada parcela
                $valPar = Helper::formataValorMonetario($valPar); // Formata para moeda

                $parcelas_ajax[] = array(
                    'qtd'	=> $i,
                    'qtd_valor' => $i . 'x (R$' . $valPar . ')',
                );
            }

            return response()->json(['success' => true, 'parcelas_ajax' => $parcelas_ajax, 'parcelas_ajax_existe' => 'S']);

        }else{

            return response()->json(['success' => true, 'parcelas_ajax' => null, 'parcelas_ajax_existe' => 'N']);
        }
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Executa o AJAX de carregamento dos dados do Cartão Corpotativo
    |----------------------------------------------------------------------------------------------------
    */
    public function getDadosCartaoCorporativo($adm, $empresa)
    {  
        $ano = date('Y');

        $data = DB::table('parametros_fin_corporativo_cartoes')
        ->where('parcco_adm', $adm)
        ->where('parcco_emp', $empresa)
        ->first();

        if(!empty($data)){

            $cartao_ajax[] = array(
                'num'	 => $data->parcco_num,
                'diaFec' => $data->parcco_dif,
                'diaVct' => $data->parcco_div,
            );

            return response()->json(['success' => true, 'cartao_ajax' => $cartao_ajax, 'cartao_ajax_existe' => 'S']);

        }else{

            return response()->json(['success' => true, 'cartao_ajax' => null, 'cartao_ajax_existe' => 'N']);
        }
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Executa o AJAX de carregamento dos dados do Banco do Razão
    |----------------------------------------------------------------------------------------------------
    */
    public function getDadosBanco($banco, $empresa)
    {  
        $ano = date('Y');

        $dadosRaz = DB::table('financeiro_razoes')
        ->where('razao_codigo', $banco)
        ->where('razao_empresa', $empresa)
        ->where('razao_ano', $ano)
        ->where('razao_tipo', 'BA')
        ->first();
        
        if(!empty($dadosRaz)){

            $cnc = $dadosRaz->razao_bco_cod.' - '.$dadosRaz->razao_bco_nom;
            
            // Agência
            $age = $dadosRaz->razao_bco_age;
            if (!empty($dadosRaz->razao_bco_age_dv)) {
                $age .= '-' . $dadosRaz->razao_bco_age_dv;
            }

            // Conta Corrente
            $conta = $dadosRaz->razao_bco_ncc;
            if (!empty($dadosRaz->razao_bco_ncc_dv)) {
                $conta .= '-' . $dadosRaz->razao_bco_ncc_dv;
            }
            
            $emitente = HelperFormatSelect::formataEmpresaCodigoNome($dadosRaz->razao_empresa);

            $banco_ajax[] = array(
                'cnc'	 => $cnc,
                'age' => $age,
                'conta' => $conta,
                'emi' => $emitente,
            );

            return response()->json(['success' => true, 'banco_ajax' => $banco_ajax, 'banco_ajax_existe' => 'S']);

        }else{

            return response()->json(['success' => true, 'banco_ajax' => null, 'banco_ajax_existe' => 'N']);
        }
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Executa o AJAX de carregamento dos dados dos Caixas da Empresa
    |----------------------------------------------------------------------------------------------------
    */
    public function getCaixasEmpAjax($empresa)
    {  
        $ano = date('Y');

        $dadosRaz = DB::table('financeiro_razoes')
        ->where('razao_empresa', $empresa)
        ->where('razao_ano', $ano)
        ->whereIn('razao_tipo', ['CX','TE'])
        ->first();
        
        if(!empty($dadosRaz)){

            $caixas_ajax[] = array(
                'id'	 => $dadosRaz->razao_codigo,
                'caixa' => $dadosRaz->razao_codigo.' - '.$dadosRaz->razao_nome,
            );

            return response()->json(['success' => true, 'caixas_ajax' => $caixas_ajax, 'caixas_ajax_existe' => 'S']);

        }else{

            return response()->json(['success' => true, 'caixas_ajax' => null, 'caixas_ajax_existe' => 'N']);
        }
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Executa o AJAX de verificação se existe transferência em aberto do caixa de origem
    |----------------------------------------------------------------------------------------------------
    */
    public function getTransfCaixaAbertoAjax($origem)
    {  
        $existe = DB::table('financeiro_transferencias')
        ->where('transf_tipo_ori', 'CX')
        ->where('transf_codigo_ori', $origem)
        ->where('transf_situacao', 'M')
        ->exists();
        
        return response()->json([
            'success' => true,
            'transf_cx_ajax' => $existe ? 'S' : 'N'
        ]);
    }
}
