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
        Schema::table('lancamento_srv_exe_tarefas', function (Blueprint $table) {
            $table->string('exetrf_res_can',6)->nullable();//Responsavel do cancelamento da TMO
            $table->string('exetrf_res_sus',6)->nullable();//Responsavel da suspensão da TMO
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lancamento_srv_exe_tarefas', function (Blueprint $table) {
            $table->dropColumn('exetrf_res_can');
            $table->dropColumn('exetrf_res_sus');
        });
    }
};
