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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('usuario_acesso_mod_servicos',['S','N'])->default('N');//Acesso ao modulo de serviços
            $table->enum('usuario_acesso_mod_nf',['S','N'])->default('N');//Acesso ao modulo de emissão de NF / Emissão Simplificada NF
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('usuario_acesso_mod_servicos');
            $table->dropColumn('usuario_acesso_mod_nf');
        });
    }
};
