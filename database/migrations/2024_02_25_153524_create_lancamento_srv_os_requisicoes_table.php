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
            $table->biginteger('req_nos');//numero da os
            $table->biginteger('req_seq');//sequencia da requisão dentro da os
            $table->string('req_dsc', 80);//descrisão da requisição
            $table->string('req_tos', 2);//tipo de serviço da requisição -> tabela lancamento_srv_tipo_servicos.tipsrv_cod
            $table->string('req_cat', 1);//catregoria do atendimento -> table lancamento_srv_categorias.categoria_codigo
            $table->string('req_set', 6);//catregoria do atendimento -> table parametros_srv_setores.setor_codigo
            $table->string('req_are', 3);//area
            $table->biginteger('req_eat');//etapa do atendimento -> table lancamento_srv_etapa_atendimentos.eat_cod
            $table->dateTime('req_dhi');//data e hora da inclusão
            $table->dateTime('req_dhf')->nullable();//data e hora fechamento
            $table->dateTime('req_dhc')->nullable();//data e hora do cancelamento
            $table->date('req_dt_apr')->nullable();//data aprovação requisição
            $table->string('req_res_apr')->nullable();//responsavel da aprovação -> tabela users.usuario_codigo
            $table->decimal('req_vlr')->nullable();//valor req
            $table->decimal('req_vls')->nullable();//valor servico
            $table->decimal('req_vlp')->nullable();//valor pecas
            $table->enum('req_tvd', ['1', '2'])->default('1');/* Tipo do valor de desconto -> 1 valor / 2 % do valor */
            $table->decimal('req_per_des', 5,2)->nullable();/* Percentual % desconto */
            $table->decimal('req_val_des', 15,2)->nullable();/* valor desconto */
            $table->enum('req_sts', ['F', 'A'])->default('A');/* situação finalizado andamento */
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
