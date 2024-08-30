<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('financeiro_recebimento_notas', function (Blueprint $table) {
            $table->id('recnf_id');
            $table->integer('recnf_id_rec');// ID do recebimento -> financeiro_recebimento_headers.rechdr_id
            $table->string('recnf_emp',6);//Empresa
            $table->integer('recnf_num');// Numero de Controle -> faturamento_nf_headers.nfhdr_num
            $table->integer('recnf_num_ped')->default(0);// Numero do Pedido / OS / Emissão Simplificada -> faturamento_nf_headers.nfhdr_num_ped
            $table->decimal('recnf_num_nf',9,0)->default(0);// Numero da NF / Cupom 
            $table->string('recnf_ser_nf',5)->nullable();// Serie da NF / Cupom
            $table->date('recnf_dt_nf')->nullable();// Data da NF / Cupom
            $table->decimal('recnf_hr_nf',4,0)->default(0);// Hora da NF
            $table->decimal('recnf_cfop',4,0)->default(0);// Código Fiscal de Operações e de Prestações
            $table->decimal('recnf_cme',3,0)->default(0);// Codigo do CME
            $table->enum('recnf_tor', ['P', 'S']);// Tipo da Origem da NF ( P - Produtos, S - Serviços )
            $table->string('recnf_ori',2);//Origem da NF 
            $table->decimal('recnf_vlr_tot',15,2)->default(0);// Valor Total da NF
            $table->timestamps();
            $table->unique(['recnf_id_rec','recnf_emp','recnf_num'], 'ak_financeiro_recebimento_notas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('financeiro_recebimento_notas');
    }
};
