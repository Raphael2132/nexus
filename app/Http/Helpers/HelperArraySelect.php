<?php

namespace App\Http\Helpers;
use Illuminate\Support\Facades\DB;
use stdClass;

class HelperArraySelect
{
    /* *****
    |
    |----------------------------------------------------------------------------------------------------
    | Helper de Arrays
    |----------------------------------------------------------------------------------------------------
    |
    | Helper destinado para montagem de arrays de selects do DB para campos de formulários e filtros comuns dentro de sistema.
    |
    | Arrys utilizados nos campos criados com <x-adminlte-select> passando o valor retornado para o atributo ":options".
    |
    | Parâmetros de entrada padrão:
    | 
    | var $value (Tipo do retorno do "value" da option)
    | Valores Padrão permitidos: 1 - Valor / 2 - Valor + Label / 3 - Label
    |
    | var $label (Tipo do retorno da Label para exibição do campo)
    | Valores Padrão permitidos: 1 - Valor + Label / 2 - Label / 3 - Valor
    |
    ***** */

    /* *****
    |----------------------------------------------------------------------------------------------------
    | Array de Empresas
    |----------------------------------------------------------------------------------------------------
    ***** */
    public static function arrayEmpresas($value = 1, $label = 1)
    {
        $data = DB::table('cadastro_empresas')->orderBy('empresa_codigo', 'asc')->get();

        $new_array1 =[];
        $new_array2 =[];

        foreach ($data as $empresa) {

            if($value == 1){
                $new_array1[] = $empresa->empresa_codigo;
            }else{
                $new_array1[] = $empresa->empresa_codigo.' - '.$empresa->empresa_nome;
            }

            if($label == 1){
                $new_array2[] = $empresa->empresa_codigo.' - '.$empresa->empresa_nome;
            }else if($label == 2){
                $new_array2[] = $empresa->empresa_nome;
            }else{
                $new_array2[] = $empresa->empresa_codigo;
            }
        }

        $array_opt = array_combine($new_array1, $new_array2);     

        return $array_opt;
    }

    /* *****
    |----------------------------------------------------------------------------------------------------
    | Array de Provedores
    |----------------------------------------------------------------------------------------------------
    ***** */
    public static function arrayProvedores($value = 1, $label = 1)
    {
        $data = DB::table('parametros_fat_nfs_provedores')->orderBy('provedor_codigo', 'asc')->get();

        $new_array1 =[];
        $new_array2 =[];

        foreach ($data as $provedor) {

            if($value == 1){
                $new_array1[] = $provedor->provedor_codigo;
            }else{
                $new_array1[] = $provedor->provedor_codigo.' - '.$provedor->provedor_desc;
            }

            if($label == 1){
                $new_array2[] = $provedor->provedor_codigo.' - '.$provedor->provedor_desc;
            }else if($label == 2){
                $new_array2[] = $provedor->provedor_desc;
            }else{
                $new_array2[] = $provedor->provedor_codigo;
            }
        }

        $array_opt = array_combine($new_array1, $new_array2);     

        return $array_opt;
    }

    /* *****
    |
    |----------------------------------------------------------------------------------------------------
    | Array de Estados
    |----------------------------------------------------------------------------------------------------
    |
    ***** */
    public static function arrayEstados($value = 1, $label = 1)
    {
        $data = DB::table('ibge_estados')->orderBy('ibge_sigla', 'asc')->get();

        $new_array1 =[];
        $new_array2 =[];

        foreach ($data as $estado) {

            if($value == 1){
                $new_array1[] = $estado->ibge_sigla;
            }else{
                $new_array1[] = $estado->ibge_sigla.' - '.$estado->ibge_nome;
            }

            if($label == 1){
                $new_array2[] = $estado->ibge_sigla.' - '.$estado->ibge_nome;
            }else if($label == 2){
                $new_array2[] = $estado->ibge_nome;
            }else{
                $new_array2[] = $estado->ibge_sigla;
            }
        }

        $array_opt = array_combine($new_array1, $new_array2);     

        return $array_opt;
    }

    /* *****
    |
    |----------------------------------------------------------------------------------------------------
    | Array de Paises
    |----------------------------------------------------------------------------------------------------
    |
    ***** */
    public static function arrayPaises($value = 1, $label = 1)
    {
        $data = DB::table('ibge_paises')->orderby('ibge_pais_nome')->get();

        $new_array1 =[];
        $new_array2 =[];

        foreach ($data as $pais) {

            if($value == 1){
                $new_array1[] = $pais->ibge_pais_codigo;
            }else{
                $new_array1[] = $pais->ibge_pais_codigo.' - '.$pais->ibge_pais_nome;
            }

            if($label == 1){
                $new_array2[] = $pais->ibge_pais_codigo.' - '.$pais->ibge_pais_nome;
            }else if($label == 2){
                $new_array2[] = $pais->ibge_pais_nome;
            }else{
                $new_array2[] = $pais->ibge_pais_codigo;
            }
        }

        $array_opt = array_combine($new_array1, $new_array2);     

        return $array_opt;
    }

    /* *****
    |
    |----------------------------------------------------------------------------------------------------
    | Array de Exigibilidade do ISS 
    |----------------------------------------------------------------------------------------------------
    |
    ***** */
    public static function arrayExigibilidadeISS($value = 1, $label = 1)
    {
        $data = DB::table('parametros_sis_exi_iss')->orderby('exiiss_codigo', 'asc')->get();

        $new_array1 =[];
        $new_array2 =[];

        foreach ($data as $exiISS) {

            if($value == 1){
                $new_array1[] = $exiISS->exiiss_codigo;
            }else{
                $new_array1[] = $exiISS->exiiss_codigo.' - '.$exiISS->exiiss_desc;
            }

            if($label == 1){
                $new_array2[] = $exiISS->exiiss_codigo.' - '.$exiISS->exiiss_desc;
            }else if($label == 2){
                $new_array2[] = $exiISS->exiiss_desc;
            }else{
                $new_array2[] = $exiISS->exiiss_codigo;
            }
        }

        $array_opt = array_combine($new_array1, $new_array2);     

        return $array_opt;
    }

    /* *****
    |
    |----------------------------------------------------------------------------------------------------
    | Array de Categorias de Atendimento
    |----------------------------------------------------------------------------------------------------
    |
    ***** */
    public static function arrayCategoriaAtend($value = 1, $label = 1)
    {
        $data = DB::table('lancamento_srv_categorias')->orderby('categoria_codigo', 'asc')->get();

        $new_array1 =[];
        $new_array2 =[];

        foreach ($data as $categoria) {

            if($value == 1){
                $new_array1[] = $categoria->categoria_codigo;
            }else{
                $new_array1[] = $categoria->categoria_codigo.' - '.$categoria->categoria_desc;
            }

            if($label == 1){
                $new_array2[] = $categoria->categoria_codigo.' - '.$categoria->categoria_desc;
            }else if($label == 2){
                $new_array2[] = $categoria->categoria_desc;
            }else{
                $new_array2[] = $categoria->categoria_codigo;
            }
        }

        $array_opt = array_combine($new_array1, $new_array2);     

        return $array_opt;
    }

    /* *****
    |
    |----------------------------------------------------------------------------------------------------
    | Array de Setores
    |----------------------------------------------------------------------------------------------------
    |
    ***** */
    public static function arraySetor($value = 1, $label = 1)
    {
        $data = DB::table('parametros_sis_setores')->orderby('setor_codigo','asc')->get();

        $new_array1 =[];
        $new_array2 =[];

        foreach ($data as $setor) {

            if($value == 1){
                $new_array1[] = $setor->setor_codigo;
            }else{
                $new_array1[] = $setor->setor_codigo.' - '.$setor->setor_desc;
            }

            if($label == 1){
                $new_array2[] = $setor->setor_codigo.' - '.$setor->setor_desc;
            }else if($label == 2){
                $new_array2[] = $setor->setor_desc;
            }else{
                $new_array2[] = $setor->setor_codigo;
            }
        }

        $array_opt = array_combine($new_array1, $new_array2);     

        return $array_opt;
    }

    /* *****
    |
    |----------------------------------------------------------------------------------------------------
    | Array de Setores por Empresa e Área Selecionada
    |----------------------------------------------------------------------------------------------------
    |
    ***** */
    public static function arraySetorPorEmpArea($empresa, $area, $value = 1, $label = 1)
    {
        $data = DB::table('parametros_sis_setores')->where('setor_empresa',$empresa)->where('setor_area',$area)->orderby('setor_codigo','asc')->get();

        $new_array1 =[];
        $new_array2 =[];

        foreach ($data as $setor) {

            if($value == 1){
                $new_array1[] = $setor->setor_codigo;
            }else{
                $new_array1[] = $setor->setor_codigo.' - '.$setor->setor_desc;
            }

            if($label == 1){
                $new_array2[] = $setor->setor_codigo.' - '.$setor->setor_desc;
            }else if($label == 2){
                $new_array2[] = $setor->setor_desc;
            }else{
                $new_array2[] = $setor->setor_codigo;
            }
        }

        $array_opt = array_combine($new_array1, $new_array2);     

        return $array_opt;
    }

    /* *****
    |
    |----------------------------------------------------------------------------------------------------
    | Array de Areas
    |----------------------------------------------------------------------------------------------------
    |
    ***** */
    public static function arrayArea($value = 1, $label = 1)
    {
        $data = DB::table('parametros_sis_areas')->orderBy('area_codigo', 'asc')->get();

        $new_array1 =[];
        $new_array2 =[];

        foreach ($data as $area) {

            if($value == 1){
                $new_array1[] = $area->area_codigo;
            }else{
                $new_array1[] = $area->area_codigo.' - '.$area->area_desc;
            }

            if($label == 1){
                $new_array2[] = $area->area_codigo.' - '.$area->area_desc;
            }else if($label == 2){
                $new_array2[] = $area->area_desc;
            }else{
                $new_array2[] = $area->area_codigo;
            }
        }

        $array_opt = array_combine($new_array1, $new_array2);     

        return $array_opt;
    }

    /* *****
    |
    |----------------------------------------------------------------------------------------------------
    | Array de Areas com Setores Cadastrados
    |----------------------------------------------------------------------------------------------------
    |
    ***** */
    public static function arrayAreaSetCadastrado($value = 1, $label = 1)
    {
        $data = DB::table('parametros_sis_areas')->join('parametros_sis_setores', 'area_codigo', '=', 'setor_area')->orderBy('area_codigo', 'asc')->get();

        $new_array1 =[];
        $new_array2 =[];

        foreach ($data as $area) {

            if($value == 1){
                $new_array1[] = $area->area_codigo;
            }else{
                $new_array1[] = $area->area_codigo.' - '.$area->area_desc;
            }

            if($label == 1){
                $new_array2[] = $area->area_codigo.' - '.$area->area_desc;
            }else if($label == 2){
                $new_array2[] = $area->area_desc;
            }else{
                $new_array2[] = $area->area_codigo;
            }
        }

        $array_opt = array_combine($new_array1, $new_array2);     

        return $array_opt;
    }

    /* *****
    |
    |----------------------------------------------------------------------------------------------------
    | Array de Planos 
    |----------------------------------------------------------------------------------------------------
    |
    ***** */
    public static function arrayPlanos($value = 1, $label = 1)
    {
        $data = DB::table('parametros_sis_planos')->orderBy('plano_codigo', 'asc')->get();

        $new_array1 =[];
        $new_array2 =[];

        foreach ($data as $plano) {

            if($value == 1){
                $new_array1[] = $plano->plano_codigo;
            }else{
                $new_array1[] = $plano->plano_codigo.' - '.$plano->plano_nome;
            }

            if($label == 1){
                $new_array2[] = $plano->plano_codigo.' - '.$plano->plano_nome;
            }else if($label == 2){
                $new_array2[] = $plano->plano_nome;
            }else{
                $new_array2[] = $plano->plano_codigo;
            }
        }

        $array_opt = array_combine($new_array1, $new_array2);     

        return $array_opt;
    }

    /* *****
    |
    |----------------------------------------------------------------------------------------------------
    | Array de Grupos de Serviço 
    |----------------------------------------------------------------------------------------------------
    |
    ***** */
    public static function arrayGruposServico($value = 1, $label = 1)
    {
        $data = DB::table('parametros_sis_servico_grupos')->orderBy('grupo_codigo', 'asc')->get();

        $new_array1 =[];
        $new_array2 =[];

        foreach ($data as $grupo) {

            if($value == 1){
                $new_array1[] = $grupo->grupo_codigo;
            }else{
                $new_array1[] = $grupo->grupo_codigo.' - '.$grupo->grupo_desc;
            }

            if($label == 1){
                $new_array2[] = $grupo->grupo_codigo.' - '.$grupo->grupo_desc;
            }else if($label == 2){
                $new_array2[] = $grupo->grupo_desc;
            }else{
                $new_array2[] = $grupo->grupo_codigo;
            }
        }

        $array_opt = array_combine($new_array1, $new_array2);     

        return $array_opt;
    }

    /* *****
    |
    |----------------------------------------------------------------------------------------------------
    | Array de Serviço da NFS-e
    |----------------------------------------------------------------------------------------------------
    |
    ***** */
    public static function arrayServicosNfs($grupo, $value = 1, $label = 1)
    {
        $data = DB::table('parametros_sis_servicos')->where('servico_grupo', $grupo)->orderBy('servico_codigo', 'asc')->get();

        $new_array1 =[];
        $new_array2 =[];

        foreach ($data as $servico) {

            if($value == 1){
                $new_array1[] = $servico->servico_codigo;
            }else{
                $new_array1[] = $servico->servico_codigo.' - '.$servico->servico_desc;
            }

            if($label == 1){
                $new_array2[] = $servico->servico_codigo.' - '.$servico->servico_desc;
            }else if($label == 2){
                $new_array2[] = $servico->servico_desc;
            }else{
                $new_array2[] = $servico->servico_codigo;
            }
        }

        $array_opt = array_combine($new_array1, $new_array2);     

        return $array_opt;
    }

    /* *****
    |
    |----------------------------------------------------------------------------------------------------
    | Array de Divisões do CNAE
    |----------------------------------------------------------------------------------------------------
    |
    ***** */
    public static function arrayCNAEDivisoes($value = 1, $label = 1)
    {
        $data = DB::table('cnae_divisoes')->orderby('cnaediv_div', 'asc')->get();

        $new_array1 =[];
        $new_array2 =[];

        foreach ($data as $div) {

            if($value == 1){
                $new_array1[] = $div->cnaediv_div;
            }else{
                $new_array1[] = $div->cnaediv_div.' - '.$div->cnaediv_desc;
            }

            if($label == 1){
                $new_array2[] = $div->cnaediv_div.' - '.$div->cnaediv_desc;
            }else if($label == 2){
                $new_array2[] = $div->cnaediv_desc;
            }else{
                $new_array2[] = $div->cnaediv_div;
            }
        }

        $array_opt = array_combine($new_array1, $new_array2);     

        return $array_opt;
    }

    /* *****
    |
    |----------------------------------------------------------------------------------------------------
    | Array de Grupos do CNAE
    |----------------------------------------------------------------------------------------------------
    |
    ***** */
    public static function arrayCNAEGrupos($codSub, $value = 1, $label = 1)
    {
        $data = DB::table('cnae_grupos')->where('cnaegrp_div', $codSub)->orderby('cnaegrp_grp', 'asc')->get();

        $new_array1 =[];
        $new_array2 =[];

        foreach ($data as $grupo) {

            if($value == 1){
                $new_array1[] = $grupo->cnaegrp_grp;
            }else{
                $new_array1[] = $grupo->cnaegrp_grp.' - '.$grupo->cnaegrp_desc;
            }

            if($label == 1){
                $new_array2[] = $grupo->cnaegrp_grp.' - '.$grupo->cnaegrp_desc;
            }else if($label == 2){
                $new_array2[] = $grupo->cnaegrp_desc;
            }else{
                $new_array2[] = $grupo->cnaegrp_grp;
            }
        }

        $array_opt = array_combine($new_array1, $new_array2);     

        return $array_opt;
    }

    /* *****
    |
    |----------------------------------------------------------------------------------------------------
    | Array de Códigos do CNAE
    |----------------------------------------------------------------------------------------------------
    |
    ***** */
    public static function arrayCNAECodigos($divCNAE, $grpCNAE, $value = 1, $label = 1)
    {
        $data = DB::table('cnae_codigos')->where('cnaesub_div', $divCNAE)->where('cnaesub_grp', $grpCNAE)->orderby('cnaesub_cod', 'asc')->get();

        $new_array1 =[];
        $new_array2 =[];

        foreach ($data as $grupo) {

            if($value == 1){
                $new_array1[] = $grupo->cnaesub_cod;
            }else{
                $new_array1[] = $grupo->cnaesub_cod.' - '.$grupo->cnaesub_desc;
            }

            if($label == 1){
                $new_array2[] = $grupo->cnaesub_cod.' - '.$grupo->cnaesub_desc;
            }else if($label == 2){
                $new_array2[] = $grupo->cnaesub_desc;
            }else{
                $new_array2[] = $grupo->cnaesub_cod;
            }
        }

        $array_opt = array_combine($new_array1, $new_array2);     

        return $array_opt;
    }

    /* *****
    |
    |----------------------------------------------------------------------------------------------------
    | Array de Códigos de Usuarios
    |----------------------------------------------------------------------------------------------------
    |
    ***** */
    public static function arrayUsuarios($value = 1, $label = 1)
    {
        $data = DB::table('users')->select('usuario_codigo', 'name')->orderBy('usuario_codigo', 'asc')->get();

        $new_array1 =[];
        $new_array2 =[];

        foreach ($data as $usuario) {

            if($value == 1){
                $new_array1[] = $usuario->usuario_codigo;
            }else{
                $new_array1[] = $usuario->usuario_codigo.' - '.$usuario->name;
            }

            if($label == 1){
                $new_array2[] = $usuario->usuario_codigo.' - '.$usuario->name;
            }else if($label == 2){
                $new_array2[] = $usuario->name;
            }else{
                $new_array2[] = $usuario->usuario_codigo;
            }
        }

        $array_opt = array_combine($new_array1, $new_array2);     

        return $array_opt;
    }

    /* *****
    |
    |----------------------------------------------------------------------------------------------------
    | Array de Códigos de Tipos de Razão
    |----------------------------------------------------------------------------------------------------
    |
    ***** */
    public static function arrayTipoRazao($value = 1, $label = 1)
    {
        $data = DB::table('financeiro_tab_contas')->wherein('tabcon_codigo', ['CC', 'BA', 'CX', 'TE', 'CO'])->orderBy('tabcon_codigo', 'asc')->get();

        $new_array1 =[];
        $new_array2 =[];

        foreach ($data as $razao) {

            if($value == 1){
                $new_array1[] = $razao->tabcon_codigo;
            }else if($value == 2){
                $new_array1[] = $razao->tabcon_codigo.' - '.$razao->tabcon_nome;
            }else{
                $new_array1[] = $razao->tabcon_nome;
            }

            if($label == 1){
                $new_array2[] = $razao->tabcon_codigo.' - '.$razao->tabcon_nome;
            }else if($label == 2){
                $new_array2[] = $razao->tabcon_nome;
            }else{
                $new_array2[] = $razao->tabcon_codigo;
            }
        }

        $array_opt = array_combine($new_array1, $new_array2);     

        return $array_opt;
    }

    /* *****
    |
    |----------------------------------------------------------------------------------------------------
    | Array de Códigos CNC / COMPE dos Bancos
    |----------------------------------------------------------------------------------------------------
    |
    ***** */
    public static function arrayCncBancos($value = 1, $label = 1)
    {
        $data = DB::table('financeiro_tab_bancos')->orderBy('tabban_codigo', 'asc')->get();

        $new_array1 =[];
        $new_array2 =[];

        foreach ($data as $banco) {

            if($value == 1){
                $new_array1[] = $banco->tabban_codigo;
            }else{
                $new_array1[] = $banco->tabban_codigo.' - '.$banco->tabban_nome.' ('.$banco->tabban_nome_fan.')';
            }

            if($label == 1){
                $new_array2[] = $banco->tabban_codigo.' - '.$banco->tabban_nome.' ('.$banco->tabban_nome_fan.')';
            }else if($label == 2){
                $new_array2[] = $banco->tabban_nome.' ('.$banco->tabban_nome_fan.')';
            }else{
                $new_array2[] = $banco->tabban_codigo;
            }
        }

        $array_opt = array_combine($new_array1, $new_array2);     

        return $array_opt;
    }

    /* *****
    |
    |----------------------------------------------------------------------------------------------------
    | Array de Códigos De Razões por Tipo
    |----------------------------------------------------------------------------------------------------
    |
    ***** */
    public static function arrayRazoesPorTipo($value = 1, $label = 1, $empresa, $tipo)
    {

        $ano = date('Y');

        if(empty($empresa)){
            $query = "
                SELECT distinct 
                    razao_codigo, 
                    razao_nome
                FROM financeiro_razoes
                WHERE 
                    razao_tipo = '".$tipo."' AND 
                    razao_ano = ".$ano."
                ORDER BY 
                    razao_codigo ASC
            ";
        }else{
            $query = "
                SELECT distinct 
                    razao_codigo, 
                    razao_nome
                FROM financeiro_razoes
                WHERE 
                    razao_empresa = '".$empresa."' AND 
                    razao_tipo = '".$tipo."' AND 
                    razao_ano = ".$ano."
                ORDER BY 
                    razao_codigo ASC
            ";
        }
        $data = DB::select($query);

        $new_array1 =[];
        $new_array2 =[];

        foreach ($data as $razao) {

            if($value == 1){
                $new_array1[] = $razao->razao_codigo;
            }else{
                $new_array1[] = $razao->razao_codigo.' - '.$razao->razao_nome;
            }

            if($label == 1){
                $new_array2[] = $razao->razao_codigo.' - '.$razao->razao_nome;
            }else if($label == 2){
                $new_array2[] = $razao->razao_nome;
            }else{
                $new_array2[] = $razao->razao_codigo;
            }
        }

        $array_opt = array_combine($new_array1, $new_array2);     

        return $array_opt;
    }

    /* *****
    |
    |----------------------------------------------------------------------------------------------------
    | Array de Códigos De Razões CC - Administrador de Cartão
    |----------------------------------------------------------------------------------------------------
    |
    ***** */
    public static function arrayRazoesAdmCC($value = 1, $label = 1, $empresa, $tipo)
    {
        $ano = date('Y');
        $query = "
            SELECT distinct 
                razao_codigo, 
                razao_nome
            FROM financeiro_razoes
            WHERE 
                razao_empresa = '".$empresa."' AND 
                razao_tipo = 'CC' AND 
                razao_tip_card = '".$tipo."' AND 
                razao_ano = ".$ano." 
            ORDER BY 
                razao_codigo ASC
        ";
        $data = DB::select($query);

        $new_array1 =[];
        $new_array2 =[];

        foreach ($data as $razao) {

            if($value == 1){
                $new_array1[] = $razao->razao_codigo;
            }else{
                $new_array1[] = $razao->razao_codigo.' - '.$razao->razao_nome;
            }

            if($label == 1){
                $new_array2[] = $razao->razao_codigo.' - '.$razao->razao_nome;
            }else if($label == 2){
                $new_array2[] = $razao->razao_nome;
            }else{
                $new_array2[] = $razao->razao_codigo;
            }
        }

        $array_opt = array_combine($new_array1, $new_array2);     

        return $array_opt;
    }

    /* *****
    |
    |----------------------------------------------------------------------------------------------------
    | Array de Quantidade de Parcelas no Crédito - Administrador de Cartão
    |----------------------------------------------------------------------------------------------------
    |
    ***** */
    public static function arrayQtdParcelaCartao($value = 1, $label = 1, $empresa, $adm, $valor)
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

        // Se não houver administradora, retorna array vazio
        if (!$data || !isset($data->administradora_parcela)) {
            return [];
        }

        $parcelaMax = $data->administradora_parcela; // Número máximo de parcelas permitidas

        $new_array1 = [];
        $new_array2 = [];

        // Gerar opções do select com os valores parcelados
        for ($i = 1; $i <= $parcelaMax; $i++) {
            $valPar = $valor / $i; // Calcula o valor de cada parcela
            $valPar = Helper::formataValorMonetario($valPar); // Formata para moeda

            // Definição das opções do select
            if ($value == 1) {
                $new_array1[] = $i;
            } else {
                $new_array1[] = $i . 'x (R$' . $valPar . ')';
            }

            if ($label == 1) {
                $new_array2[] = $i . 'x (R$' . $valPar . ')';
            } elseif ($label == 2) {
                $new_array2[] = $i;
            }
        }

        $array_opt = array_combine($new_array1, $new_array2);     

        return $array_opt;
    }

    /*
    |----------------------------------------------------------------------------------------------------
    | Executa o AJAX de carregamento das parcelas do Cartão Corpotativo
    |----------------------------------------------------------------------------------------------------
    */
    public static function arrayQtdParcelaCartaoCorporativo($value = 1, $label = 1, $adm, $empresa, $valor)
    {  
        $ano = date('Y');

        $data = DB::table('parametros_fin_corporativo_cartoes')
        ->where('parcco_adm', $adm)
        ->where('parcco_emp', $empresa)
        ->first();
        
        // Se não houver administradora, retorna array vazio
        if (!$data || !isset($data->parcco_par)) {
            return [];
        }

        $parcelaMax = $data->parcco_par; // Número máximo de parcelas permitidas

        $new_array1 = [];
        $new_array2 = [];

        // Gerar opções do select com os valores parcelados
        for ($i = 1; $i <= $parcelaMax; $i++) {
            $valPar = $valor / $i; // Calcula o valor de cada parcela
            $valPar = Helper::formataValorMonetario($valPar); // Formata para moeda

            // Definição das opções do select
            if ($value == 1) {
                $new_array1[] = $i;
            } else {
                $new_array1[] = $i . 'x (R$' . $valPar . ')';
            }

            if ($label == 1) {
                $new_array2[] = $i . 'x (R$' . $valPar . ')';
            } elseif ($label == 2) {
                $new_array2[] = $i;
            }
        }

        $array_opt = array_combine($new_array1, $new_array2);     

        return $array_opt;
    }

    /* *****
    |
    |----------------------------------------------------------------------------------------------------
    | Array de Códigos das Formas de Pagamento
    |----------------------------------------------------------------------------------------------------
    |
    ***** */
    public static function arrayFormaPagamento($value = 1, $label = 1)
    {
        $data = DB::table('faturamento_tab_forma_pagamentos')->orderby('tabfpg_codigo')->get();

        $new_array1 =[];
        $new_array2 =[];

        foreach ($data as $forma) {

            if($value == 1){
                $new_array1[] = $forma->tabfpg_codigo;
            }else{
                $new_array1[] = $forma->tabfpg_codigo.' - '.$forma->tabfpg_desc;
            }

            if($label == 1){
                $new_array2[] = $forma->tabfpg_codigo.' - '.$forma->tabfpg_desc;
            }else if($label == 2){
                $new_array2[] = $forma->tabfpg_desc;
            }else{
                $new_array2[] = $forma->tabfpg_codigo;
            }
        }

        $array_opt = array_combine($new_array1, $new_array2);     

        return $array_opt;
    }

    /* *****
    |
    |----------------------------------------------------------------------------------------------------
    | Array de Códigos das Condições de Pagamento
    |----------------------------------------------------------------------------------------------------
    |
    ***** */
    public static function arrayCondPagamento($value = 1, $label = 1)
    {
        $data = DB::table('faturamento_tab_cond_pagamentos')->where('tabcpg_codigo', '<>', '00')->orderby('tabcpg_codigo')->get();

        $new_array1 =[];
        $new_array2 =[];

        foreach ($data as $cond) {

            if($value == 1){
                $new_array1[] = $cond->tabcpg_codigo;
            }else{
                $new_array1[] = $cond->tabcpg_codigo.' - '.$cond->tabcpg_desc;
            }

            if($label == 1){
                $new_array2[] = $cond->tabcpg_codigo.' - '.$cond->tabcpg_desc;
            }else if($label == 2){
                $new_array2[] = $cond->tabcpg_desc;
            }else{
                $new_array2[] = $cond->tabcpg_codigo;
            }
        }

        $array_opt = array_combine($new_array1, $new_array2);     

        return $array_opt;
    }

    /* *****
    |
    |----------------------------------------------------------------------------------------------------
    | Array de Códigos dos Tipos de Nota
    |----------------------------------------------------------------------------------------------------
    |
    ***** */
    public static function arrayTipoNF($value = 1, $label = 1)
    {
        $data = DB::table('faturamento_tab_tipo_notas')->orderby('tabtnf_codigo')->get();

        $new_array1 =[];
        $new_array2 =[];

        foreach ($data as $tipo) {

            if($value == 1){
                $new_array1[] = $tipo->tabtnf_codigo;
            }else{
                $new_array1[] = $tipo->tabtnf_codigo.' - '.$tipo->tabtnf_desc;
            }

            if($label == 1){
                $new_array2[] = $tipo->tabtnf_codigo.' - '.$tipo->tabtnf_desc;
            }else if($label == 2){
                $new_array2[] = $tipo->tabtnf_desc;
            }else{
                $new_array2[] = $tipo->tabtnf_codigo;
            }
        }

        $array_opt = array_combine($new_array1, $new_array2);     

        return $array_opt;
    }

    /* *****
    |
    |----------------------------------------------------------------------------------------------------
    | Array das Bandeiras de Cartão
    |----------------------------------------------------------------------------------------------------
    |
    ***** */
    public static function arrayBandeirasCartao($value = 1, $label = 1)
    {
        $data = DB::table('financeiro_tab_bandeiras')->orderby('tabbnd_codigo')->get();

        $new_array1 =[];
        $new_array2 =[];

        foreach ($data as $bandeira) {

            if($value == 1){
                $new_array1[] = $bandeira->tabbnd_codigo;
            }else{
                $new_array1[] = $bandeira->tabbnd_codigo.' - '.$bandeira->tabbnd_nome;
            }

            if($label == 1){
                $new_array2[] = $bandeira->tabbnd_codigo.' - '.$bandeira->tabbnd_nome;
            }else if($label == 2){
                $new_array2[] = $bandeira->tabbnd_nome;
            }else{
                $new_array2[] = $bandeira->tabbnd_codigo;
            }
        }

        $array_opt = array_combine($new_array1, $new_array2);     

        return $array_opt;
    }

    /* *****
    |----------------------------------------------------------------------------------------------------
    | Array de Administradoras de Cartão
    |----------------------------------------------------------------------------------------------------
    ***** */
    public static function arrayAdministradoras($value = 1, $label = 1)
    {
        $data = DB::table('cadastro_cartao_administradoras')->orderBy('administradora_codigo', 'asc')->get();

        $new_array1 =[];
        $new_array2 =[];

        foreach ($data as $adm) {

            if($value == 1){
                $new_array1[] = $adm->administradora_codigo;
            }else{
                $new_array1[] = $adm->administradora_codigo.' - '.$adm->administradora_nome;
            }

            if($label == 1){
                $new_array2[] = $adm->administradora_codigo.' - '.$adm->administradora_nome;
            }else if($label == 2){
                $new_array2[] = $adm->administradora_nome;
            }else{
                $new_array2[] = $adm->administradora_codigo;
            }
        }

        $array_opt = array_combine($new_array1, $new_array2);     

        return $array_opt;
    }

    /* *****
    |----------------------------------------------------------------------------------------------------
    | Array de Origens do sistema
    |----------------------------------------------------------------------------------------------------
    ***** */
    public static function arrayOrigens($value = 1, $label = 1)
    {
        $data = DB::table('parametros_sis_origens')->orderBy('origem_codigo', 'asc')->get();

        $new_array1 =[];
        $new_array2 =[];

        foreach ($data as $origem) {

            if($value == 1){
                $new_array1[] = $origem->origem_codigo;
            }else{
                $new_array1[] = $origem->origem_codigo.' - '.$origem->origem_desc;
            }

            if($label == 1){
                $new_array2[] = $origem->origem_codigo.' - '.$origem->origem_desc;
            }else if($label == 2){
                $new_array2[] = $origem->origem_desc;
            }else{
                $new_array2[] = $origem->origem_codigo;
            }
        }

        $array_opt = array_combine($new_array1, $new_array2);     

        return $array_opt;
    }

    /* *****
    |----------------------------------------------------------------------------------------------------
    | Array de Subtipos de Conta Corrente
    |----------------------------------------------------------------------------------------------------
    ***** */
    public static function arraySubtipo($value = 1, $label = 1, $tipo)
    {
        $data = DB::table('financeiro_tab_subtipo_contas_correntes')->where('tabscc_tcc', $tipo)->orderBy('tabscc_cod', 'asc')->get();

        $new_array1 =[];
        $new_array2 =[];

        foreach ($data as $subtipo) {

            if($value == 1){
                $new_array1[] = $subtipo->tabscc_cod;
            }else{
                $new_array1[] = $subtipo->tabscc_cod.' - '.$subtipo->tabscc_nom;
            }

            if($label == 1){
                $new_array2[] = $subtipo->tabscc_cod.' - '.$subtipo->tabscc_nom;
            }else if($label == 2){
                $new_array2[] = $subtipo->tabscc_nom;
            }else{
                $new_array2[] = $subtipo->tabscc_cod;
            }
        }

        $array_opt = array_combine($new_array1, $new_array2);     

        return $array_opt;
    }

    /* *****
    |----------------------------------------------------------------------------------------------------
    | Array do Tipo de Créditos do Financeiro
    |----------------------------------------------------------------------------------------------------
    ***** */
    public static function arrayTipoCreditoFin($value = 1, $label = 1, $tipo)
    {
        $data = DB::table('financeiro_tab_tipo_creditos')->where('tabtcr_tab', $tipo)->orderBy('tabtcr_cod', 'asc')->get();

        $new_array1 =[];
        $new_array2 =[];

        foreach ($data as $tipo) {

            if($value == 1){
                $new_array1[] = $tipo->tabtcr_cod;
            }else{
                $new_array1[] = $tipo->tabtcr_cod.' - '.$tipo->tabtcr_des;
            }

            if($label == 1){
                $new_array2[] = $tipo->tabtcr_cod.' - '.$tipo->tabtcr_des;
            }else if($label == 2){
                $new_array2[] = $tipo->tabtcr_des;
            }else{
                $new_array2[] = $tipo->tabtcr_cod;
            }
        }

        $array_opt = array_combine($new_array1, $new_array2);     

        return $array_opt;
    }

    /* *****
    |
    |----------------------------------------------------------------------------------------------------
    | Array de Códigos de Tipos de Razão
    |----------------------------------------------------------------------------------------------------
    |
    ***** */
    public static function arrayTipoContasPagamento($value = 1, $label = 1)
    {
        //$data = DB::table('financeiro_tab_contas')->wherein('tabcon_codigo', ['GD','AF','NF','NP','CP','DV','AC','IS','ID','CO','VU','GV','VC'])->orderBy('tabcon_codigo', 'asc')->get();
        $data = DB::table('financeiro_tab_contas')->wherein('tabcon_codigo', ['GD','AF','NF','NP','CP','DV','AC','IS','ID','CO','GV'])->orderBy('tabcon_codigo', 'asc')->get();

        $new_array1 =[];
        $new_array2 =[];

        foreach ($data as $razao) {

            if($value == 1){
                $new_array1[] = $razao->tabcon_codigo;
            }else{
                $new_array1[] = $razao->tabcon_codigo.' - '.$razao->tabcon_nome;
            }

            if($label == 1){
                $new_array2[] = $razao->tabcon_codigo.' - '.$razao->tabcon_nome;
            }else if($label == 2){
                $new_array2[] = $razao->tabcon_nome;
            }else{
                $new_array2[] = $razao->tabcon_codigo;
            }
        }

        $array_opt = array_combine($new_array1, $new_array2);     

        return $array_opt;
    }

    /* *****
    |
    |----------------------------------------------------------------------------------------------------
    | Array de Códigos de Subtipo da Conta Corrente
    |----------------------------------------------------------------------------------------------------
    |
    ***** */
    public static function arraySubTipoCC($value = 1, $label = 1, $tipo)
    {
        $dadosScc = DB::table('financeiro_tab_subtipo_contas_correntes')->where('tabscc_tcc', $tipo)->orderby('tabscc_cod', 'asc')->get();

        $new_array1 =[];
        $new_array2 =[];

        foreach ($dadosScc as $scc) {

            if($value == 1){
                $new_array1[] = $scc->tabscc_cod;
            }else{
                $new_array1[] = $scc->tabscc_cod.' - '.$scc->tabscc_nom;
            }

            if($label == 1){
                $new_array2[] = $scc->tabscc_cod.' - '.$scc->tabscc_nom;
            }else if($label == 2){
                $new_array2[] = $scc->tabscc_nom;
            }else{
                $new_array2[] = $scc->tabscc_cod;
            }
        }

        $array_opt = array_combine($new_array1, $new_array2);     

        return $array_opt;
    }

    /* *****
    |
    |----------------------------------------------------------------------------------------------------
    | Array de Códigos De Todos os Razões de Cartão Corporativo do Sistema
    |----------------------------------------------------------------------------------------------------
    |
    ***** */
    public static function arrayTodosRazoesCardCorp($value = 1, $label = 1)
    {
        $ano = date('Y');
        $query = "
            SELECT distinct 
                razao_codigo, 
                razao_nome
            FROM financeiro_razoes
            WHERE 
                razao_tipo = 'CO' AND 
                razao_ano = ".$ano." 
            ORDER BY 
                razao_codigo ASC
        ";
        $data = DB::select($query);

        $new_array1 =[];
        $new_array2 =[];

        foreach ($data as $razao) {

            if($value == 1){
                $new_array1[] = $razao->razao_codigo;
            }else{
                $new_array1[] = $razao->razao_codigo.' - '.$razao->razao_nome;
            }

            if($label == 1){
                $new_array2[] = $razao->razao_codigo.' - '.$razao->razao_nome;
            }else if($label == 2){
                $new_array2[] = $razao->razao_nome;
            }else{
                $new_array2[] = $razao->razao_codigo;
            }
        }

        $array_opt = array_combine($new_array1, $new_array2);     

        return $array_opt;
    }

    /* *****
    |
    |----------------------------------------------------------------------------------------------------
    | Array de Cartões Corporativos
    |----------------------------------------------------------------------------------------------------
    |
    ***** */
    public static function arrayCartaoCorporativo($value = 1, $label = 1, $empresa)
    {
        $dados = DB::table('parametros_fin_corporativo_cartoes')->where('parcco_emp', $empresa)->where('parcco_sts', 'A')->orderby('parcco_adm', 'asc')->get();

        $new_array1 =[];
        $new_array2 =[];

        foreach ($dados as $card) {

            if($value == 1){
                $new_array1[] = $card->parcco_adm;
            }else{
                $new_array1[] = $card->parcco_adm.' - '.$card->parcco_nom;
            }

            if($label == 1){
                $new_array2[] = $card->parcco_adm.' - '.$card->parcco_nom;
            }else if($label == 2){
                $new_array2[] = $card->parcco_nom;
            }else{
                $new_array2[] = $card->parcco_adm;
            }
        }

        $array_opt = array_combine($new_array1, $new_array2);     

        return $array_opt;
    }
}