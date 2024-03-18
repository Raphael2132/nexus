<?php

namespace App\Http\Helpers;

class Helper
{
    public static function mascaraTelCelular(string $telCelular)
    {
        $celularFormatado = "(".substr($telCelular,0,2).") ".substr($telCelular,2,1)." ".substr($telCelular,3,4)."-".substr($telCelular,-4,4);
        return $celularFormatado;
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

    public static function mascaraRG(string $rg)
    {
        $rgFormatado = substr($rg,0,2).'.'.substr($rg,2,3).'.'.substr($rg,5,3).'-'.substr($rg,-1,1);
        return $rgFormatado;
    }

    public static function mascaraCEP(string $cep)
    {
        $cepFormatado = substr($cep,0,5).'-'.substr($cep,-3,3);
        return $cepFormatado;
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
}