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
        Schema::table('parametros_sis_modulos', function (Blueprint $table) {
            $table->integer('modulo_qtd_usuarios')->default(1);//Quantidade de Usuários do Sistema
            $table->date('modulo_dt_validade')->default(DB::raw('NOW()'));//Data de validade da licença da empresa
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('parametros_sis_modulos', function (Blueprint $table) {
            $table->dropColumn('modulo_qtd_usuarios');
            $table->dropColumn('modulo_dt_validade');
        });
    }
};
