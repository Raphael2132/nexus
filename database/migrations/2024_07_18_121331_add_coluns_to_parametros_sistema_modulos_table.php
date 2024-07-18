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
        Schema::table('parametros_sistema_modulos', function (Blueprint $table) {
            $table->enum('modulo_emissao_nfs_simp', ['S', 'N'])->default('N');//Emissão de NFS Simplificada
            $table->enum('modulo_servico', ['S', 'N'])->default('N');//Modulo de Serviços
            $table->enum('modulo_emissao_rps', ['S', 'N'])->default('N');//Modulo de Emissão de RPS
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('parametros_sistema_modulos', function (Blueprint $table) {
            $table->dropColumn('modulo_emissao_nfs_simp');
            $table->dropColumn('modulo_servico');
            $table->dropColumn('modulo_emissao_rps');
        });
    }
};
