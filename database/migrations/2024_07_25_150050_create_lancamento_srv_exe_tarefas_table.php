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
        Schema::create('lancamento_srv_exe_tarefas', function (Blueprint $table) {
            $table->id('exetrf_id');
            $table->string('exetrf_emp',6)->unsigned();//Empresa da OS -> tabela empresas.empresa_codigo
            $table->integer('exetrf_nos')->unsigned();//numero da os
            $table->integer('exetrf_req')->unsigned();//codigo da requisição da os -> tabela lancamento_srv_os_requisicoes.req_seq
            $table->integer('exetrf_seq')->unsigned();//sequencia do serviço dentro da requisição 
            $table->string('exetrf_tmo', 15);//codigo da tarefa de mão de Obra -> tabela parametros_srv_tmos.tmo_cod
            $table->string('exetrf_desc', 40);//descricao da tarefa de mão de obra
            $table->string('exetrf_cmp',80)->nullable();//complemento
            $table->string('exetrf_are',3);//areA do setor da empresa -> tabela parametros_sis_areas.area_codigo
            $table->string('exetrf_set',6);//setor da empresa que presta o serviço -> tabela parametros_sis_setores.setor_codigo
            $table->string('exetrf_ths',1);//tipo da hora servico
            $table->decimal('exetrf_qhr', 5,2)->default(0);//quantidade de horas da tarefa
            $table->string('exetrf_prt',6)->nullable();//prestrador do serviço da empresa -> tabela cadastro_prestadores.prestador_cod
            $table->enum('exetrf_sts', ['S', 'C', 'F', 'A', 'E'])->default('E');//status da tarefa do serviço - suspenso, finalizado, cancelado, andamento, espera
            $table->date('exetrf_dt_inc');/* Data de inclusão da tmo */
            $table->date('exetrf_dt_ini_srv')->nullable();/* Data de inicio do serviço */
            $table->decimal('exetrf_hr_ini_srv', 4,0)->default(0);/* Hora Inicio de Execucao do serviço */
            $table->date('exetrf_dt_fin_srv')->nullable();/* Data de termino do serviço */
            $table->decimal('exetrf_hr_fin_srv', 4,0)->default(0);/* Hora termino da Execucao do serviço */
            $table->date('exetrf_dt_prv_fin_srv')->nullable();/* Data de previsão de termino do serviço */
            $table->decimal('exetrf_hr_prv_fin_srv', 4,0)->default(0);/* Hora de previsão de termino da Execucao do serviço */
            $table->decimal('exetrf_qhr_real', 5,2)->default(0);//quantidade de horas da trabalhadas
            $table->decimal('exetrf_qhr_saldo', 5,2)->default(0);//quantidade de horas de saldo
            $table->date('exetrf_dt_can_srv')->nullable();/* Data de cancelamento do serviço */
            $table->decimal('exetrf_hr_can_srv', 4,0)->default(0);/* Hora cancelamento do serviço */
            $table->string('exetrf_mot_can_srv',2)->nullable();//motivo de cancelamento do serviço
            $table->date('exetrf_dt_sus_srv')->nullable();/* Data de suspensão do serviço */
            $table->decimal('exetrf_hr_sus_srv', 4,0)->default(0);/* Hora suspensão do serviço */
            $table->string('exetrf_mot_sus_srv',2)->nullable();//motivo de suspensão do serviço
            $table->string('exetrf_obs',255)->nullable();//Observações do prestador
            $table->string('exetrf_usu',6)->nullable();//Consultor Abertura da OS
            $table->date('exetrf_dt_prev_ent')->nullable();/* Data de previsão de entrega da OS */
            $table->decimal('exetrf_hr_prev_ent', 4,0)->default(0);/* Hora de previsão de entrega da OS */
            $table->enum('exetrf_age', ['S', 'N'])->default('N');//TMO Agendada
            $table->date('exetrf_dt_age_tmo')->nullable();/* Data de agendamento de inicio do serviço */
            $table->decimal('exetrf_hr_age_tmo', 4,0)->default(0);/* Hora de agendamento de inicio do serviço */
            $table->date('exetrf_dt_age_fin_tmo')->nullable();/* Data de agendamento encerramento do serviço */
            $table->decimal('exetrf_hr_age_fin_tmo', 4,0)->default(0);/* Hora de agendamento de encerramento do serviço */
            $table->timestamps();
            $table->unique(['exetrf_emp','exetrf_nos','exetrf_req','exetrf_seq'], 'ak_lancamento_srv_exe_tarefas');
            $table->foreign(['exetrf_emp', 'exetrf_nos', 'exetrf_req'], 'fk_lancamento_srv_os_requisicoes')->references(['req_emp', 'req_nos', 'req_seq'])->on('lancamento_srv_os_requisicoes')->onDelete('cascade');
            $table->foreign(['exetrf_emp', 'exetrf_nos', 'exetrf_req', 'exetrf_seq'], 'fk_lancamento_srv_os_servicos')->references(['srv_emp', 'srv_nos', 'srv_req', 'srv_seq'])->on('lancamento_srv_os_servicos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lancamento_srv_exe_tarefas');
    }
};
