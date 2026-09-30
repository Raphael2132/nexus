<?php

namespace App\Http\Controllers\Financeiro\Pagamento;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Helpers\Helper;
use App\Models\Financeiro\Pagamento\FinanceiroPagamentoHeader;

class FinanceiroPagamentoHeaderController extends Controller
{
    protected $headerPagamento;
    
    public function __construct(FinanceiroPagamentoHeader $headerPagamento)
    {
        $this->headerPagamento = $headerPagamento;
    }

    //Insere o Header do pagamento
    static function insert($empresa, $origem){

        $usuario = Auth::user()->usuario_codigo;
        $data = date('Y-m-d');

        $dadosUsuFin = DB::table('financeiro_tab_usuarios')->where('tabusu_usuario', $usuario)->where('tabusu_empresa', $empresa)->first();

        $nextval = DB::select("SELECT nextval('sq_financeiro_pagamento_num')")[0]->nextval;

        $dados = [
            'paghdr_emp' => $empresa,
            'paghdr_cod_pag' => $nextval,
            'paghdr_sts' => 'A',
            'paghdr_ori' => $origem,
            'paghdr_dti' => $data,
            'paghdr_usu' => $usuario,
            'paghdr_tip_raz' => $dadosUsuFin->tabusu_tipo_razao,
            'paghdr_raz' => $dadosUsuFin->tabusu_razao
        ];
        
        // Cria o registro e obtém a instância do modelo
        $header = FinanceiroPagamentoHeader::create($dados);
        
        // Retorna o ID do registro criado
        return $header->paghdr_cod_pag;
    }
}
