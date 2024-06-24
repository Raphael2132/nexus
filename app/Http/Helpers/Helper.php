<?php

namespace App\Http\Helpers;

class Helper
{
    public static function mascaraTelCelular(string $telCelular)
    {
        $celularFormatado = "(".substr($telCelular,0,2).") ".substr($telCelular,2,1)." ".substr($telCelular,3,4)."-".substr($telCelular,-4,4);
        return $celularFormatado;
    }

    public static function limpaTelCelular(string $telCelular)
    {
        $replace = array("_", "(", ")", "-", " ");
        $celular = str_replace($replace,"",$telCelular);
        return $celular;
    }

    public static function mascaraTelComercial(string $telComercial)
    {
        $comercialFormatado = "(".substr($telComercial,0,2).") ".substr($telComercial,2,4)."-".substr($telComercial,-4,4);
        return $comercialFormatado;
    }

    public static function mascaraTelResidencial(string $telResidencial)
    {
        $residencialFormatado = "(".substr($telResidencial,0,2).") ".substr($telResidencial,2,4)."-".substr($telResidencial,-4,4);
        return $residencialFormatado;
    }

    public static function limpaTelResidencial(string $telResidencial)
    {
        $replace = array("_", "(", ")", "-", " ");
        $residencial = str_replace($replace,"",$telResidencial);
        return $residencial;
    }

    public static function mascaraCNPJ(string $cnpj)
    {
        $cnpjFormatado = substr($cnpj,0,2).'.'.substr($cnpj,2,3).'.'.substr($cnpj,5,3).'/'.substr($cnpj,8,4).'-'.substr($cnpj,12,2);
        return $cnpjFormatado;
    }

    public static function mascaraCPF(string $cpf)
    {
        $cpfFormatado = substr($cpf,0,3).'.'.substr($cpf,3,3).'.'.substr($cpf,6,3).'-'.substr($cpf,9,2);
        return $cpfFormatado;
    }

    public static function limpaCPF(string $cpf)
    {
        $replace = array("_","/", "-", ".", " ");
        $cpf = str_replace($replace,"",$cpf);
        return $cpf;
    }

    public static function mascaraRG(string $rg)
    {
        $rgFormatado = substr($rg,0,2).'.'.substr($rg,2,3).'.'.substr($rg,5,3).'-'.substr($rg,-1,1);
        return $rgFormatado;
    }

    public static function limpaRG(string $rg)
    {
        $replace = array("_","-", ".", " ");
        $rg = str_replace($replace,"",$rg);
        return $rg;
    }

    public static function mascaraCEP(string $cep)
    {
        $cepFormatado = substr($cep,0,5).'-'.substr($cep,-3,3);
        return $cepFormatado;
    }

    public static function limpaCEP(string $cep)
    {
        $replace = array("_", "-", " ");
        $cep = str_replace($replace,"",$cep);
        return $cep;
    }

    public static function formataDataHora(string $dataHora)
    {
        $dataHoraFormatada = date('d/m/Y H:i:s', strtotime($dataHora));
        return $dataHoraFormatada;
    }

    public static function formataData(string $data)
    {
        $dataFormatada = date('d/m/Y', strtotime($data));
        return $dataFormatada;
    }

    public static function limpaData(string $data)
    {
        $dataLimpa = substr($data,-4).'-'.substr($data,3,2).'-'.substr($data,0,2);
        return $dataLimpa;
    }

    public static function formataHoraMinuto(string $hora)
    {
        $horaFormatada = substr($hora,0,2).':'.substr($hora,2,2);
        return $horaFormatada;
    }

    public static function limpaHoraMinuto(string $hora)
    {
        $horaLimpa = str_replace(":", "", $hora);
        return $horaLimpa;
    }

    public static function formataValorMonetario(string $valor)
    {
        $valorFormatado = number_format($valor,2,",",".");
        return $valorFormatado;
    }

    public static function limpaValorMonetario(string $valor)
    {
        $valorLimpo = str_replace(".","",$valor);
        $valorLimpo = str_replace(",",".",$valorLimpo);

        return $valorLimpo;
    }

    public static function limpaPorcentagem(string $valor)
    {
        $valorLimpo = str_replace(".","",$valor);
        $valorLimpo = str_replace(",",".",$valorLimpo);

        return $valorLimpo;
    }

    /*
    * Remover os acentos de uma string
    * @param string $str
    * @return string
    */
    public static function removerAcento($str, $convertToUpper = 'N'){

        $com_acento = array(
            'Á', 'À', 'Â', 'Ã', 'Ä', 'á', 'à', 'â', 'ã', 'ä',
            'É', 'È', 'Ê', 'Ë', 'é', 'è', 'ê', 'ë',
            'Í', 'Ì', 'Î', 'Ï', 'í', 'ì', 'î', 'ï',
            'Ó', 'Ò', 'Ô', 'Õ', 'Ö', 'ó', 'ò', 'ô', 'õ', 'ö',
            'Ú', 'Ù', 'Û', 'Ü', 'ú', 'ù', 'û', 'ü',
            'Ç', 'ç', 'Ñ', 'ñ'
        );
        $sem_acento = array(
            'A', 'A', 'A', 'A', 'A', 'a', 'a', 'a', 'a', 'a',
            'E', 'E', 'E', 'E', 'e', 'e', 'e', 'e',
            'I', 'I', 'I', 'I', 'i', 'i', 'i', 'i',
            'O', 'O', 'O', 'O', 'O', 'o', 'o', 'o', 'o', 'o',
            'U', 'U', 'U', 'U', 'u', 'u', 'u', 'u',
            'C', 'c', 'N', 'n'
        );
        
        $str = str_replace($com_acento, $sem_acento, $str);
        
        if($convertToUpper == 'S'){
            $str = strtoupper($str);   
        } 

        return $str;
    }
}