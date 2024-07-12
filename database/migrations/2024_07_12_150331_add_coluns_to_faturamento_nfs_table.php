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
        Schema::table('faturamento_nfs', function (Blueprint $table) {
            $table->enum('nfs_origem',['OS','ES'])->default('OS');//Origem da NFS S - Ordem de Serviço, E - Emissão Simplificada
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('faturamento_nfs', function (Blueprint $table) {
            $table->dropColumn('nfs_origem');
        });
    }
};
