<?php

namespace App\Http\Helpers;
use Illuminate\Support\Facades\DB;
use stdClass;

class HelperArrayFixo
{
    /* *****
    |
    |----------------------------------------------------------------------------------------------------
    | Helper de Arrays Fixos
    |----------------------------------------------------------------------------------------------------
    |
    | Helper destinado para montagem de arrays de valores fixos para campos de formulários e filtros comuns dentro de sistema.
    |
    | Arrys utilizados nos campos criados com <x-adminlte-select> passando o valor retornado para o atributo ":options".
    |
    | Parâmetros de entrada padrão:
    | 
    | var $value (Tipo do retorno do "value" da option)
    | Valores Padrão permitidos: 1 - Valor / 2 - Valor + Label
    |
    | var $label (Tipo do retorno da Label para exibição do campo)
    | Valores Padrão permitidos: 1 - Valor + Label / 2 - Label / 3 - Valor
    |
    ***** */

    /* *****
    |----------------------------------------------------------------------------------------------------
    | Array de Dias de Funcionamento da Empresa
    |----------------------------------------------------------------------------------------------------
    ***** */
    public static function arrayDiaFuncionamentoEmpresa($value = 1, $label = 1)
    {
        $new_array1 =[];
        $new_array2 =[];

        if($value == 1){
            $new_array1 = ['1','2','3'];
        }else{
            $new_array1 = ['1 - Segunda à Sexta','2 - Segunda à Sábado','3 - Segunda à Domingo'];
        }

        if($label == 1){
            $new_array2 = ['1 - Segunda à Sexta','2 - Segunda à Sábado','3 - Segunda à Domingo'];
        }else if($label == 2){
            $new_array2 = ['Segunda à Sexta','Segunda à Sábado','Segunda à Domingo'];
        }else{
            $new_array2 = ['1','2','3'];
        }

        $array_opt = array_combine($new_array1, $new_array2);     

        return $array_opt;
    }

    /* *****
    |----------------------------------------------------------------------------------------------------
    | Array do Tipo da Administradora de cartão
    |----------------------------------------------------------------------------------------------------
    ***** */
    public static function arrayTipoAdministradora($value = 1, $label = 1)
    {
        $new_array1 =[];
        $new_array2 =[];

        if($value == 1){
            $new_array1 = ['C','D','T'];
        }else{
            $new_array1 = ['Crédito','Débito','Crédito / Débito'];
        }

        if($label == 1){
            $new_array2 = ['C - Crédito','D - Débito','T - Crédito / Débito'];
        }else if($label == 2){
            $new_array2 = ['Crédito','Débito','Crédito / Débito'];
        }else{
            $new_array2 = ['C','D','T'];
        }

        $array_opt = array_combine($new_array1, $new_array2);     

        return $array_opt;
    }
}