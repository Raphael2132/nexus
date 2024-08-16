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
        Schema::create('parametros_ger_turnos', function (Blueprint $table) {
            $table->id('partur_id');
            $table->string('partur_emp',6)->unsigned();
            $table->integer('partur_cod');
            $table->string('partur_desc',80);
            $table->decimal('partur_hr_ini',4,0)->default(0);
            $table->decimal('partur_hr_fin',4,0)->default(0);
            $table->enum('partur_dia', ['1', '2', '3'])->default('1');// Dias de Serviço 1 - seg a sexta, 2 - segunda a sabado, 3 - segunda a domingo
            $table->timestamps();
            $table->unique(['partur_emp','partur_cod'], 'ak_parametros_ger_turnos');
            $table->foreign('partur_emp', 'fk_parametros_ger_turnos')->references(['empresa_codigo'])->on('cadastro_empresas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parametros_ger_turnos');
    }
};
