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
        Schema::table('faturamento_nfs_simplificadas', function (Blueprint $table) {
            $table->string('nfssim_obs',255)->nullable(); // Observações
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('faturamento_nfs_simplificadas', function (Blueprint $table) {
            $table->dropColumn('nfssim_obs');
        });
    }
};
