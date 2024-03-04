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
        Schema::create('lancamento_srv_os', function (Blueprint $table) {
            $table->id('os_id');
            $table->biginteger('os_nos');//numero da os -> sequencia sq_lancamento_srv_numero_os
            $table->string('os_emp', 6);//empresa da os -> tabela cadastro_empresas.empresa_codigo
            $table->string('os_cli', 10);//cliente da os -> tabela cadastro_clientes.cliente_codigo
            $table->biginteger('os_cli_end');//endereço do cliente da os -> tabela cadastro_clientes.cliente_codigo
            $table->dateTime('os_dha');//data e hora da abertura da os
            $table->string('os_res_abr', 6);//responsavel abertura da os -> tabela users.usuario_codigo
            $table->dateTime('os_dhf')->nullable();//data e hora do fechamento da os
            $table->string('os_res_fec', 6)->nullable();//responsavel fechamento da os -> tabela users.usuario_codigo
            $table->enum('os_apr', ['S', 'N'])->default('N');//flag os aprovada
            $table->date('os_dt_apr')->nullable();//data aprovação da os
            $table->string('os_res_apr', 6)->nullable();//responsavel aprovação da os -> tabela users.usuario_codigo
            $table->date('os_dt_orc')->nullable();//data orcamento
            $table->biginteger('os_num_orc')->nullable();//numero orcamento
            $table->date('os_dtc')->nullable();//data camcelamento os
            $table->string('os_res_can', 6)->nullable();//responsavel cancelamewnto -> tabela users.usuario_codigo
            $table->decimal('os_vlt', 15,2)->nullable();//valor total - com desconto
            $table->decimal('os_vos', 15,2)->nullable();//valor os - serviço + produtos
            $table->decimal('os_vls', 15,2)->nullable();//valor valor servico
            $table->decimal('os_vlp', 15,2)->nullable();//valor produtos
            $table->enum('os_tip_des', ['1', '2'])->default('1');/* Tipo do valor de desconto -> 1 valor / 2 % do valor */
            $table->decimal('os_per_des', 3,2)->nullable();//percentual desconto
            $table->decimal('os_val_des', 15,2)->nullable();//valor desconto
            $table->enum('os_sts', ['C', 'F', 'A'])->default('A');//status da tarefa do serviço - finalizado, cancelado, aberto
            $table->timestamps();
            $table->unique(['os_emp','os_nos'], 'ak_lancamento_srv_os');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lancamento_srv_os');
    }
};
