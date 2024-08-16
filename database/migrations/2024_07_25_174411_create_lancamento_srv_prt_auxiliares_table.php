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
        Schema::create('lancamento_srv_prt_auxiliares', function (Blueprint $table) {
            $table->id('prtaux_id');
            $table->string('prtaux_emp',6)->unsigned();//Empresa da OS -> tabela empresas.empresa_codigo
            $table->integer('prtaux_nos')->unsigned();//numero da os
            $table->integer('prtaux_req')->unsigned();//codigo da requisição da os -> tabela lancamento_srv_os_requisicoes.req_seq
            $table->integer('prtaux_srv')->unsigned();//sequencia do serviço dentro da requisição 
            $table->integer('prtaux_seq')->unsigned();//sequencia da tabela
            $table->string('prtaux_prt',6);//prestrador auxiliar do serviço da empresa -> tabela cadastro_prestadores.prestador_cod
            $table->date('prtaux_dt_inc');/* Data de inclusão do auxiliar */
            $table->decimal('prtaux_hr_inc', 4,0)->default(0);/* Hora de inclusão do auxiliar */
            $table->timestamps();
            $table->unique(['prtaux_emp','prtaux_nos','prtaux_req','prtaux_srv','prtaux_seq'], 'ak_lancamento_srv_prt_auxiliares');
            $table->foreign(['prtaux_emp', 'prtaux_nos', 'prtaux_req'], 'fk_lancamento_srv_os_requisicoes')->references(['req_emp', 'req_nos', 'req_seq'])->on('lancamento_srv_os_requisicoes')->onDelete('cascade');
            $table->foreign(['prtaux_emp', 'prtaux_nos', 'prtaux_req', 'prtaux_srv'], 'fk_lancamento_srv_os_servicos')->references(['srv_emp', 'srv_nos', 'srv_req', 'srv_seq'])->on('lancamento_srv_os_servicos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lancamento_srv_prt_auxiliares');
    }
};
