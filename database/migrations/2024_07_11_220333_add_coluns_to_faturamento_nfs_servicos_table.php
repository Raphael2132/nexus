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
        Schema::table('faturamento_nfs_servicos', function (Blueprint $table) {
            $table->enum('nfssrv_emi_simp', ['S', 'N'])->default('N'); // Emissão Simplificada
            $table->string('nfssrv_srv_desc',255)->nullable(); // descrição do serviço
            $table->string('nfssrv_inf_com',255)->nullable(); // Informações Complementares
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('faturamento_nfs_servicos', function (Blueprint $table) {
            $table->dropColumn('nfssrv_emi_simp');
            $table->dropColumn('nfssrv_srv_desc');
            $table->dropColumn('nfssrv_inf_com');
        });
    }
};
