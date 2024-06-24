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
        Schema::create('lancamento_srv_os_servicos', function (Blueprint $table) {
            $table->id('srv_id');
            $table->string('srv_emp',6)->unsigned();//empresa da os -> tabela empresas.empresa_codigo
            $table->integer('srv_nos')->unsigned();//numero da os
            $table->integer('srv_req')->unsigned();//codigo da requisição da os -> tabela lancamento_srv_os_requisicoes.req_seq
            $table->integer('srv_seq');//sequencia do serviço dentro da requisição
            $table->string('srv_prt',6)->nullable();//prestrador do serviço da empresa -> tabela cadastro_prestadores.prestador_cod
            $table->string('srv_set',6);//setor da empresa que presta o serviço -> tabela parametros_srv_setores.setor_codigo
            $table->string('srv_are',3);//areA do setor da empresa -> tabela parametros_sistema_areas.area_codigo
            $table->enum('srv_sts', ['S', 'C', 'F', 'A', 'E'])->default('E');//status da tarefa do serviço - suspenso, finalizado, cancelado, andamento, espera
            $table->string('srv_tmo', 15);//codigo da tarefa de mão de Obra -> tabela parametros_srv_tmos.tmo_cod
            $table->string('srv_dsc', 40);//descricao da tarefa de mão de obra
            $table->string('srv_cmp',80)->nullable();//complemento
            $table->string('srv_ths',1);//tipo da hora servico
            $table->decimal('srv_qhr', 5,2)->default(0);//quantidade de horas da tarefa
            $table->decimal('srv_vhr', 15,2)->default(0);//valor da hora da tarefa
            $table->decimal('srv_vts', 15,2)->default(0);//valor total da tarefa
            $table->decimal('srv_per_des', 15,2)->default(0);//valor total da tarefa
            $table->decimal('srv_val_des', 15,2)->default(0);//valor total da tarefa
            $table->decimal('srv_vtl', 15,2)->default(0);//valor total da líquido
            $table->enum('srv_aut_desc', ['S', 'N'])->default('N');/* Desconto autorizado  */
            $table->string('srv_usu_aut_desc',6)->nullable();/* Usuario da autorização do desconto */
            $table->date('srv_dti');/* Data Inicio de Execucao da Tarefa */
            $table->decimal('srv_hri', 4,0)->default(0);/* Hora Inicio de Execucao da Tarefa */
            $table->date('srv_dtf')->nullable();/* Data Termino de Execucao da Tarefa */
            $table->decimal('srv_hrf', 4,0)->default(0);/* Hora Termino de Execucao da Tarefa */
            $table->string('srv_for', 10)->nullable();/* Fornecedor do Servico de Terceiros -> tabela clientes.fornecedor_codigo*/
            $table->decimal('srv_nft', 9,0)->nullable();/* Numero da NF do Servico de Terceiros */
            $table->string('srv_srt', 5)->nullable();/* Serie da NF do Servico de Terceiros */
            $table->date('srv_dtt')->nullable();/* Data da NF do Servico de Terceiros */
            $table->enum('srv_tcg', ['1', '2'])->default('1');/* Tipo do custo gerencial -> 1 valor / 2 % do valor */
            $table->decimal('srv_pcg', 5,2)->default(0);/* Percentual % custo custo gerencial */
            $table->decimal('srv_vcg', 15,2)->default(0);/* Custo gerencial Servico */
            $table->dateTime('srv_dhc')->nullable();/* Data e hora do Cancelamento */
            $table->string('srv_res_can', 6)->nullable();/* Responsavel pelo Cancelamento -> tabela users.usuario_codigo*/
            $table->enum('srv_flg_apr', ['S', 'N'])->default('N');/* flag tarefa aprovada */
            $table->string('srv_res_apr')->nullable();/* Responsavel pela aprovacao da tarefa -> tabela users.usuario_codigo */
            $table->dateTime('srv_dh_apr')->nullable();/* Data e hora da aprovacao da tarefa */
            $table->string('srv_und',2)->default('HR');/* unidade */
            $table->timestamps();
            $table->unique(['srv_emp','srv_nos','srv_req','srv_seq'], 'ak_lancamento_srv_os_servicos');
            $table->foreign(['srv_emp', 'srv_nos', 'srv_req'], 'fk_lancamento_srv_os_requisicoes')->references(['req_emp', 'req_nos', 'req_seq'])->on('lancamento_srv_os_requisicoes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lancamento_srv_os_servicos');
    }
};
