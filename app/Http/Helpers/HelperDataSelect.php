<?php

namespace App\Http\Helpers;
use Illuminate\Support\Facades\DB;
use stdClass;

class HelperDataSelect
{
    /* *****
    |
    |----------------------------------------------------------------------------------------------------
    | Helper de Criação de Objetos de Selects do DB
    |----------------------------------------------------------------------------------------------------
    |
    | Helper destinado a busca de dados do banco de dados comuns dentro de sistema.
    | É retornado o objeto com os dados pesquisados.
    |
    ***** */

    /* *****
    |----------------------------------------------------------------------------------------------------
    | Retorna os dados da Empresa
    |----------------------------------------------------------------------------------------------------
    ***** */
    public static function buscaDadosEmpresa($empresa)
    {
        $dadosEmpresa = DB::table('cadastro_empresas')->where('empresa_codigo',$empresa)->first();          

        return $dadosEmpresa;
    }

    /* *****
    |----------------------------------------------------------------------------------------------------
    | Retorna os dados do Prestador
    |----------------------------------------------------------------------------------------------------
    ***** */
    public static function buscaDadosPrestador($prestador)
    {
        $dadosPrestador = DB::table('cadastro_prestadores')->where('prestador_codigo',$prestador)->first();          

        return $dadosPrestador;
    }

    /* *****
    |----------------------------------------------------------------------------------------------------
    | Retorna os dados do Razão
    |----------------------------------------------------------------------------------------------------
    ***** */
    public static function buscaDadosRazao($razao,$tipo,$ano)
    {
        $dadosRazao = DB::table('financeiro_razoes')->where('razao_codigo',$razao)->where('razao_tipo',$tipo)->where('razao_ano',$ano)->first();          

        return $dadosRazao;
    }
}