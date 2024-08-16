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
        Schema::create('parametros_ger_empresas', function (Blueprint $table) {
            $table->id('parger_id');
            $table->string('parger_emp',6)->unsigned();
            $table->enum('parger_dia_fun', ['1', '2', '3'])->default('1');// Dias de funcionamento 1 - seg a sexta, 2 - segunda a sabado, 3 - segunda a domingo
            $table->decimal('parger_hr_ini_fun',4,0)->default(900);//Horario de Inicio de Funcionamento
            $table->decimal('parger_hr_fin_fun',4,0)->default(1800);//Horario Final de Funcionamento
            $table->enum('parger_int_fun', ['S', 'N'])->default('S');// Usa intervalo de funcionamento
            $table->decimal('parger_hr_ini_int',4,0)->default(1200); // Horario inicial de Intervalo
            $table->decimal('parger_hr_fin_int',4,0)->default(1300); // Horario final de Intervalo
            $table->enum('parger_tur_srv', ['S', 'N'])->default('N');// Usa Turnos Serviço
            $table->enum('parger_hr_alt_sab', ['S', 'N'])->default('N');// Horario Alternativo Sabado
            $table->decimal('parger_hr_ini_sab',4,0)->default(0); // Horario inicial aos sabados
            $table->decimal('parger_hr_fin_sab',4,0)->default(0); // Horario final aos sabados
            $table->enum('parger_int_sab', ['S', 'N'])->default('N');// Usa intervalo de sabado
            $table->decimal('parger_hr_ini_int_sab',4,0)->default(0); // Horario inicial de Intervalo aos sabados
            $table->decimal('parger_hr_fin_int_sab',4,0)->default(0); // Horario final de Intervalo aos sabados
            $table->enum('parger_hr_alt_dom', ['S', 'N'])->default('N');// Horario Alternativo Domingo
            $table->decimal('parger_hr_ini_dom',4,0)->default(0); // Horario inicial aos domingos
            $table->decimal('parger_hr_fin_dom',4,0)->default(0); // Horario final aos domingos
            $table->enum('parger_int_dom', ['S', 'N'])->default('N');// Usa intervalo de domingo
            $table->decimal('parger_hr_ini_int_dom',4,0)->default(0); // Horario inicial de Intervalo aos domingos
            $table->decimal('parger_hr_fin_int_dom',4,0)->default(0); // Horario final de Intervalo aos domingos
            $table->timestamps();
            $table->unique('parger_emp', 'ak_parametros_ger_empresas');
            $table->foreign('parger_emp', 'fk_parametros_ger_empresas')->references(['empresa_codigo'])->on('cadastro_empresas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parametros_ger_empresas');
    }
};
