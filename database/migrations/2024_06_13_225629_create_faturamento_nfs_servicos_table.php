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
        Schema::create('faturamento_nfs_servicos', function (Blueprint $table) {
            $table->id('nfssrv_id');
            $table->string('nfssrv_emp',6);//Empresa emitente da nfs
            $table->integer('nfssrv_num');//Número de controle da NF -> faturamento_nf_headers.nfhdr_num
            $table->integer('nfssrv_req');//requisiçao da nfs
            $table->integer('nfssrv_seq');//sequencia do item na requisiçao da nfs
            $table->decimal('nfssrv_nnfs',9,0);//Número da nota fiscal de serviços
            $table->string('nfssrv_snfs',5);//Série da nota fiscal de serviços
            $table->string('nfssrv_req_dsc',80)->nullable(); // descrição da requisição
            $table->string('nfssrv_tmo',15)->nullable(); // Código da Tarefa (Serviço)
            $table->string('nfssrv_tmo_dsc',40)->nullable();//descriçao da tmo
            $table->decimal('nfssrv_qtd_hr', 5,2)->default(0);//quantidade de horas da tarefa
            $table->decimal('nfssrv_vlr_hr', 15,2)->default(0);//valor da hora da tarefa
            $table->decimal('nfssrv_vlr_tot', 15,2)->default(0);//valor total da tmo
            $table->decimal('nfssrv_vlr_dsc', 15,2)->default(0);//valor desconto da tmo
            $table->decimal('nfssrv_vlr_liq', 15,2)->default(0);//valor liquido da tmo
            $table->timestamps();
            $table->unique(['nfssrv_emp','nfssrv_num','nfssrv_req','nfssrv_seq'], 'ak_faturamento_nfs_servicos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faturamento_nfs_servicos');
    }
};
