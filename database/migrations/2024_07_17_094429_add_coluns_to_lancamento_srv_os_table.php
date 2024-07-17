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
        Schema::table('lancamento_srv_os', function (Blueprint $table) {
            $table->decimal('os_qtd_hr_pre_ent', 5,2)->default(0);//quantidade de horas da previsão de entrega
            $table->enum('os_cal_aut_pre_ent', ['S', 'N'])->default('S');//Calcula Automaticamente a Previsão de Entrega S - Sim, N - Não
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lancamento_srv_os', function (Blueprint $table) {
            $table->dropColumn('os_cal_aut_pe');
        });
    }
};
