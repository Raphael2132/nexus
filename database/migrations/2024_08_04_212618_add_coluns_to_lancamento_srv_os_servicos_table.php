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
        Schema::table('lancamento_srv_os_servicos', function (Blueprint $table) {
            $table->dateTime('srv_dhs')->nullable();/* Data e hora da Suspensão */
            $table->string('srv_res_sus', 6)->nullable();/* Responsavel pela Suspensão -> tabela users.usuario_codigo*/
            $table->string('srv_mot_can',2)->nullable();//motivo de cancelamento do serviço
            $table->string('srv_mot_sus',2)->nullable();//motivo de suspensão do serviço
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lancamento_srv_os_servicos', function (Blueprint $table) {
            $table->dropColumn('srv_dhs');
            $table->dropColumn('srv_res_sus');
            $table->dropColumn('srv_mot_can');
            $table->dropColumn('srv_mot_sus');
        });
    }
};
