<?php

namespace App\Http\Helpers;
use Illuminate\Support\Facades\DB;
use stdClass;

class HelperFormatSelect
{
    /* *****
    |
    |----------------------------------------------------------------------------------------------------
    | Helper de Formatação de Campos com Selects do DB
    |----------------------------------------------------------------------------------------------------
    |
    | Helper destinado a formatação de campos por SQL do banco de dados comuns dentro de sistema.
    | Retorna a formatação de campos (Código - Descrição).
    |
    ***** */

    /* *****
    |----------------------------------------------------------------------------------------------------
    | Retorna a Empresa Formatada (Código - Nome)
    |----------------------------------------------------------------------------------------------------
    ***** */
    public static function formataEmpresaCodigoNome($empresa)
    {
        $dadosEmpresa = DB::table('cadastro_empresas')->where('empresa_codigo',$empresa)->first();          

        $empresa = $dadosEmpresa->empresa_codigo.' - '.$dadosEmpresa->empresa_nome;

        return $empresa;
    }

    /* *****
    |----------------------------------------------------------------------------------------------------
    | Retorna o Usuário Formatado (Código - Nome)
    |----------------------------------------------------------------------------------------------------
    ***** */
    public static function formataUsuarioCodigoNome($usuario, $empresa)
    {
        $dadosUsuario = DB::table('users')->where('usuario_empresa',$empresa)->where('usuario_codigo',$usuario)->first();          

        $usuario = $dadosUsuario->usuario_codigo.' - '.$dadosUsuario->name;

        return $usuario;
    }

    /* *****
    |
    |----------------------------------------------------------------------------------------------------
    | Retorna a Área Formatada (Código - Desc)
    |----------------------------------------------------------------------------------------------------
    |
    ***** */
    public static function formataAreaCodigoDesc($codigo)
    {
        $dadosArea = DB::table('parametros_sis_areas')->where('area_codigo', $codigo)->first();

        $area = $dadosArea->area_codigo.' - '.$dadosArea->area_desc;

        return $area;
    }

    /* *****
    |
    |----------------------------------------------------------------------------------------------------
    | Retorna a Setor Formatado (Código - Desc)
    |----------------------------------------------------------------------------------------------------
    |
    ***** */
    public static function formataSetorCodigoDesc($empresa, $area, $codigo)
    {
        $dataSetor = DB::table('parametros_sis_setores')->where('setor_empresa',$empresa)->where('setor_area', $area)->where('setor_codigo',$codigo)->first();

        $setor = $dataSetor->setor_codigo.' - '.$dataSetor->setor_desc;

        return $setor;
    }

    /* *****
    |
    |----------------------------------------------------------------------------------------------------
    | Retorna Estado Formatado (UF - Desc)
    |----------------------------------------------------------------------------------------------------
    |
    ***** */
    public static function formataEstadoDesc($uf)
    {
        $estado = DB::table('ibge_estados')->where('ibge_sigla', $uf)->get();

        $estadoFormatado = $uf.' - '.$estado[0]->ibge_nome;

        return $estadoFormatado;
    }

    /* *****
    |
    |----------------------------------------------------------------------------------------------------
    | Retorna o Cliente Formatado (Código - Nome)
    |----------------------------------------------------------------------------------------------------
    |
    ***** */
    public static function formataClientes($codigo)
    {
        $cliente = DB::table('cadastro_clientes')->where('cliente_codigo', $codigo)->first();

        $clienteFormatado = $codigo.' - '.$cliente->cliente_nome;

        return $clienteFormatado;
    }

    /* *****
    |
    |----------------------------------------------------------------------------------------------------
    | Retorna o Tipo da Nota (Descrição)
    |----------------------------------------------------------------------------------------------------
    |
    ***** */
    public static function formataTipoNF($codigo)
    {
        $tipNF = DB::table('faturamento_tab_tipo_notas')->where('tabtnf_codigo', $codigo)->first();

        $notaFormatada = $tipNF->tabtnf_desc;

        return $notaFormatada;
    }

    /* *****
    |
    |----------------------------------------------------------------------------------------------------
    | Retorna a Forma de Pagamento (Descrição)
    |----------------------------------------------------------------------------------------------------
    |
    ***** */
    public static function formataFormaPgt($codigo)
    {
        $forma = DB::table('faturamento_tab_forma_pagamentos')->where('tabfpg_codigo', $codigo)->first();

        $formaPgt = $forma->tabfpg_desc;

        return $formaPgt;
    }

    /* *****
    |
    |----------------------------------------------------------------------------------------------------
    | Retorna a Condição de Pagamento (Descrição)
    |----------------------------------------------------------------------------------------------------
    |
    ***** */
    public static function formataCondPgt($codigo)
    {
        $cond = DB::table('faturamento_tab_cond_pagamentos')->where('tabcpg_codigo', $codigo)->first();

        $condPgt = $cond->tabcpg_desc;

        return $condPgt;
    }

    /* *****
    |
    |----------------------------------------------------------------------------------------------------
    | Retorna o Subtipo da Conta Corrente (Código - Nome)
    |----------------------------------------------------------------------------------------------------
    |
    ***** */
    public static function formataSubTipoCC($tipo, $codigo)
    {
        $scc = DB::table('financeiro_tab_subtipo_contas_correntes')->where('tabscc_tcc', $tipo)->where('tabscc_cod', $codigo)->first();

        if(!empty($scc)){
            $sccFormatado = $codigo.' - '.$scc->tabscc_nom;
        }else{
            $sccFormatado = '';
        }

        return $sccFormatado;
    }

    /* *****
    |
    |----------------------------------------------------------------------------------------------------
    | Retorna o Tipo da Conta Corrente (Código - Nome)
    |----------------------------------------------------------------------------------------------------
    |
    ***** */
    public static function formataTipoCC($codigo)
    {
        $conta = DB::table('financeiro_tab_contas')->where('tabcon_codigo', $codigo)->first();

        if(!empty($conta)){
            $contaFormatada = $codigo.' - '.$conta->tabcon_nome;
        }else{
            $contaFormatada = '';
        }

        return $contaFormatada;
    }

    /* *****
    |
    |----------------------------------------------------------------------------------------------------
    | Retorna o Nome do Tipo do Razão
    |----------------------------------------------------------------------------------------------------
    |
    ***** */
    public static function formataNomeTipoRazao($codigo)
    {
        $data = DB::table('financeiro_tab_contas')->where('tabcon_codigo', $codigo)->first();

        if(!empty($data)){
            $nomeRaz = $data->tabcon_nome;
        }else{
            $nomeRaz = '';
        }

        return $nomeRaz;
    }

    /* *****
    |
    |----------------------------------------------------------------------------------------------------
    | Retorna o Codigo e Nome do Razão
    |----------------------------------------------------------------------------------------------------
    |
    ***** */
    public static function formataRazaoCodNom($codigo)
    {
        $data = DB::table('financeiro_razoes')->where('razao_codigo', $codigo)->where('razao_ano', date('Y'))->first();

        if(!empty($data)){
            $nomeRaz = $data->razao_codigo.' - '.$data->razao_nome;
        }else{
            $nomeRaz = '';
        }

        return $nomeRaz;
    }

    /* *****
    |
    |----------------------------------------------------------------------------------------------------
    | Retorna o Codigo e Nome do CNC do banco
    |----------------------------------------------------------------------------------------------------
    |
    ***** */
    public static function formataBancoCncCodNom($codigo)
    {
        $data = DB::table('financeiro_tab_bancos')->where('tabban_codigo', $codigo)->first();

        if(!empty($data)){
            $nomeCNC = $data->tabban_codigo.' - '.$data->tabban_nome_fan;
        }else{
            $nomeCNC = '';
        }

        return $nomeCNC;
    }
}