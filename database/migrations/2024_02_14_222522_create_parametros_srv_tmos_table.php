<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /*
    |--------------------------------------------------------------------------
    | Parametrização das Tarefas de Mão de Obra do Serviço
    |--------------------------------------------------------------------------
    |
    | Área destina as rotas envolvidas no cadastramento de informações de empresa, clientes, usuarios, bancos e produtos.
    |
    */

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('parametros_srv_tmos', function (Blueprint $table) {
            $table->id('tmo_id');
            $table->string('tmo_emp',6)->unsigned();//empresa
            $table->string('tmo_are',3);//area FK tabela areas - parametros_sis_areas
            $table->string('tmo_set',6);//Setor FK tabela setores - parametros_srv_sets
            $table->string('tmo_cod',15);//codigo tarefa
            $table->string('tmo_dsc',40);//descriçao
            $table->string('tmo_cmp',80)->nullable();//complemento
            $table->enum('tmo_tip',['P', 'I', 'T', 'R', 'F'])->default('P');/* Tipo da tarefa P-hora padrao I- hora informada R-hora real T-terceiros F-valor fixo */
            $table->decimal('tmo_qtd_hr',5,2);//quantidade horas
            $table->decimal('tmo_val_hr',15,2);//valor da hora
            $table->decimal('tmo_val_tot',15,2);//valor total
            $table->string('tmo_for_cgt',10)->nullable();//Fornecedor terceiro
            $table->enum('tmo_tip_val_cgt', ['1', '2'])->default('1');//tipo custo gerencial da tarefa 1 valor 2 porcentagem
            $table->decimal('tmo_val_cgt',15,2);//valor Custo gerencial da tarefa
            $table->decimal('tmo_per_cgt',5,2);//percentual custo gerencial tarefa
            $table->string('tmo_res', 6)->nullable();//responsavel
            $table->enum('tmo_sts', ['A', 'D'])->default('A');//status Ativo Desativado
            $table->timestamps();
            $table->unique(['tmo_emp','tmo_are','tmo_set','tmo_cod'], 'ak_parametros_srv_tmos');
            $table->foreign('tmo_emp', 'fk_parametros_srv_tmos')->references('empresa_codigo')->on('cadastro_empresas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parametros_srv_tmos');
    }
};
