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
        Schema::table('cadastro_prestadores', function (Blueprint $table) {
            $table->integer('prestador_tur_cod')->nullable();// Código do turno de serviço
            $table->enum('prestador_int_srv', ['S', 'N'])->default('S');// Usa intervalo de serviço
            $table->decimal('prestador_hr_ini_int',4,0)->default(1200); // Horario inicial de Intervalo
            $table->decimal('prestador_hr_fin_int',4,0)->default(1300); // Horario final de Intervalo
            $table->enum('prestador_int_srv_sab', ['S', 'N'])->default('N');// Usa intervalo de serviço no sabado
            $table->decimal('prestador_hr_ini_int_sab',4,0)->default(0); // Horario inicial de Intervalo no sabado
            $table->decimal('prestador_hr_fin_int_sab',4,0)->default(0); // Horario final de Intervalo no sabado
            $table->enum('prestador_int_srv_dom', ['S', 'N'])->default('N');// Usa intervalo de serviço no domingo
            $table->decimal('prestador_hr_ini_int_dom',4,0)->default(0); // Horario inicial de Intervalo no domingo
            $table->decimal('prestador_hr_fin_int_dom',4,0)->default(0); // Horario final de Intervalo no domingo
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cadastro_prestadores', function (Blueprint $table) {
            $table->dropColumn('prestador_tur_cod');
            $table->dropColumn('prestador_int_srv');
            $table->dropColumn('prestador_hr_ini_int');
            $table->dropColumn('prestador_hr_fin_int');
            $table->dropColumn('prestador_int_srv_sab');
            $table->dropColumn('prestador_hr_ini_int_sab');
            $table->dropColumn('prestador_hr_fin_int_sab');
            $table->dropColumn('prestador_int_srv_dom');
            $table->dropColumn('prestador_hr_ini_int_dom');
            $table->dropColumn('prestador_hr_fin_int_dom');
        });
    }
};
