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
        Schema::create('lancamento_srv_os_requisicoes', function (Blueprint $table) {
            $table->id('req_id');
            $table->string('req_emp',6);//empresa da os -> tabela empresas.empresa_codigo
            $table->integer('req_nos');//numero da os
            $table->integer('req_seq');//sequencia da requisão dentro da os
            $table->string('req_dsc', 80);//descrisão da requisição
            $table->string('req_tos', 2);//tipo de serviço da requisição -> tabela lancamento_srv_tipo_servicos.tipsrv_cod
            $table->string('req_cat', 1);//catregoria do atendimento -> table lancamento_srv_categorias.categoria_codigo
            $table->string('req_set', 6);//catregoria do atendimento -> table parametros_srv_setores.setor_codigo
            $table->string('req_are', 3);//area
            $table->integer('req_eat');//etapa do atendimento -> table lancamento_srv_etapa_atendimentos.eat_cod
            $table->dateTime('req_dhi');//data e hora da inclusão
            $table->dateTime('req_dhf')->nullable();//data e hora fechamento
            $table->dateTime('req_dhc')->nullable();//data e hora do cancelamento
            $table->date('req_dt_apr')->nullable();//data aprovação requisição
            $table->string('req_res_apr',6)->nullable();//responsavel da aprovação -> tabela users.usuario_codigo
            $table->decimal('req_qtd_hr',5,2)->default(0);//tempo da requisição
            $table->decimal('req_vlr',15,2)->default(0);//valor bruto
            $table->decimal('req_vls',15,2)->default(0);//valor servico
            $table->decimal('req_vlp',15,2)->default(0);//valor pecas
            $table->decimal('req_vlt',15,2)->default(0);//valor total liquido com desconto
            $table->decimal('req_per_des', 5,2)->default(0);/* Percentual total de desconto da requisição (Desativado) */
            $table->decimal('req_val_des', 15,2)->default(0);/* valor total de desconto da requisição */
            $table->enum('req_sts', ['F', 'A'])->default('A');/* situação finalizado andamento */
            $table->enum('req_aut_desc', ['S', 'N'])->default('N');/* Desconto autorizado (Desativado) */
            $table->string('req_aut_user', 6)->nullable();//Usuario que liberou o desconto
            $table->decimal('req_val_des_srv', 15,2)->default(0);/* valor desconto de serviços */
            $table->timestamps();
            $table->unique(['req_emp','req_nos','req_seq'], 'ak_lancamento_srv_os_requisicoes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lancamento_srv_os_requisicoes');
    }
};
