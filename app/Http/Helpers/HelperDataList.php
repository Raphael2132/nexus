<?php

namespace App\Http\Helpers;
use Illuminate\Support\Facades\DB;

class HelperDataList
{
    /* *****
    |
    |----------------------------------------------------------------------------------------------------
    | Helper de Montagem de Datalist
    |----------------------------------------------------------------------------------------------------
    |
    | Helper destinado para montagem de campo de formularios do tipo Datalist.
    |
    | Utilizado em campos auto-complete nos formularios e filtros dos sitema
    |
    | São geralmente integrados a campos <x-adminlte-input> onde conforme digitamos é exibido as opções do Datalist que contem a string digitada para seleção rápida de dados
    |
    ***** */

    /* *****
    |----------------------------------------------------------------------------------------------------
    | Datalist dos Municípios da tabela do IBGE
    |----------------------------------------------------------------------------------------------------
    ***** */
    public static function geraDatalistMunicipios($id)
    {
        //Faz o lookup do campo de cidades 
        $dataIBGE = DB::table('ibge_municipios')->select('ibge_mun_codigo', 'ibge_mun_nome')->orderBy('ibge_mun_uf_codigo', 'asc')->orderBy('ibge_mun_codigo', 'asc')->get();

        $html = '<datalist id="'.$id.'">';

        foreach($dataIBGE as $cidade){
            $html .= '<option value="'.$cidade->ibge_mun_nome.'">'.$cidade->ibge_mun_nome.'</option>';
        }
        $html .='</datalist>';

        return $html;
    }

    /* *****
    |----------------------------------------------------------------------------------------------------
    | Datalist dos Prestadores Por Empresa e Setor
    |----------------------------------------------------------------------------------------------------
    ***** */
    public static function geraDatalistPrestadorEmpSet($id, $empresa, $area, $setor)
    {
        $data_pres = DB::table('cadastro_prestadores')
        ->where('prestador_empresa',$empresa)
        ->where('prestador_set',$setor)
        ->where('prestador_are',$area)
        ->where('prestador_status','A')
        ->orderBy('prestador_codigo', 'asc')
        ->get();
                            
        $html = '<datalist id="'.$id.'">';
        
        foreach($data_pres as $prestador){
            $html .= '<option value="'.$prestador->prestador_codigo.' - '.$prestador->prestador_nome.'">'.$prestador->prestador_codigo.' - '.$prestador->prestador_nome.'</option>';
        }

        $html .='</datalist>';

        return $html;
    }

    /* *****
    |----------------------------------------------------------------------------------------------------
    | Datalist dos Fornecedores
    |----------------------------------------------------------------------------------------------------
    ***** */
    public static function geraDatalistFornecedores($id)
    {
        $data_cli = DB::table('cadastro_clientes')->selectRaw('cliente_codigo, cliente_nome')->where('cliente_tipo_cadastro','F')->orderBy('cliente_codigo', 'asc')->get();
                            
        $html = '<datalist id="'.$id.'">';
                            
        foreach($data_cli as $cliente){
            $html .= '<option value="'.$cliente->cliente_codigo.' - '.$cliente->cliente_nome.'">'.$cliente->cliente_codigo.' - '.$cliente->cliente_nome.'</option>';
        }
        
        $html .='</datalist>';

        return $html;
    }

    /* *****
    |----------------------------------------------------------------------------------------------------
    | Datalist Geral dos Clientes
    |----------------------------------------------------------------------------------------------------
    ***** */
    public static function geraDatalistGeralClientes($id)
    {
        $data_cli = DB::table('cadastro_clientes')->selectRaw('cliente_codigo, cliente_nome')->orderBy('cliente_codigo', 'asc')->get();
                            
        $html = '<datalist id="'.$id.'">';
                            
        foreach($data_cli as $cliente){
            $html .= '<option value="'.$cliente->cliente_codigo.' - '.$cliente->cliente_nome.'">'.$cliente->cliente_codigo.' - '.$cliente->cliente_nome.'</option>';
        }
        
        $html .='</datalist>';

        return $html;
    }

    /* *****
    |----------------------------------------------------------------------------------------------------
    | Datalist Geral dos Fornecedores
    |----------------------------------------------------------------------------------------------------
    ***** */
    public static function geraDatalistGeralFornecedores($id)
    {
        $data_cli = DB::table('cadastro_clientes')->where('cliente_tipo_cadastro','F')->selectRaw('cliente_codigo, cliente_nome')->orderBy('cliente_codigo', 'asc')->get();
                            
        $html = '<datalist id="'.$id.'">';
                            
        foreach($data_cli as $cliente){
            $html .= '<option value="'.$cliente->cliente_codigo.' - '.$cliente->cliente_nome.'">'.$cliente->cliente_codigo.' - '.$cliente->cliente_nome.'</option>';
        }
        
        $html .='</datalist>';

        return $html;
    }

    /* *****
    |----------------------------------------------------------------------------------------------------
    | Datalist Geral dos Responsaveis do Ssitema
    |----------------------------------------------------------------------------------------------------
    ***** */
    public static function geraDatalistGeralResponsaveis($id, $cliente='S',$fornecedor='S',$co='N',$cc='N')
    {
         $itens = collect();

        /* ============================
        * CLIENTES / FORNECEDORES
        * ============================ */
        if ($cliente === 'S' || $fornecedor === 'S') {

            $query = DB::table('cadastro_clientes')
                ->selectRaw("
                    cliente_codigo as codigo,
                    cliente_nome as nome,
                    cliente_tipo_cadastro as tipo
                ");

            $query->where(function ($q) use ($cliente, $fornecedor) {
                if ($cliente === 'S') {
                    $q->orWhere('cliente_tipo_cadastro', 'C');
                }
                if ($fornecedor === 'S') {
                    $q->orWhere('cliente_tipo_cadastro', 'F');
                }
            });

            $itens = $itens->merge($query->orderBy('cliente_codigo')->get());
        }

        /* ============================
        * CARTÕES (CC / CO)
        * ============================ */
        if ($cc === 'S' || $co === 'S') {

            $anoAtual = date('Y');

            $query = DB::table('financeiro_razoes')
                ->selectRaw("
                    razao_codigo as codigo,
                    razao_nome as nome,
                    razao_tipo as tipo
                ")
                ->where('razao_ano', $anoAtual);

            $query->where(function ($q) use ($cc, $co) {
                if ($cc === 'S') {
                    $q->orWhere('razao_tipo', 'CC');
                }
                if ($co === 'S') {
                    $q->orWhere('razao_tipo', 'CO');
                }
            });

            $itens = $itens->merge($query->orderBy('razao_codigo')->get());
        }

        /* ============================
        * MONTA O DATALIST
        * ============================ */
        $html = '<datalist id="'.$id.'">';

        foreach ($itens as $item) {
            $html .= '<option value="'.$item->codigo.' - '.$item->nome.'">';
            $html .= $item->codigo.' - '.$item->nome;
            $html .= '</option>';
        }

        $html .= '</datalist>';

        return $html;
    }
}