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
            $table->enum('nfs_rps_env_email',['S','N'])->default('N');//Envio do RPS no email do cliente realizado
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('faturamento_nfs', function (Blueprint $table) {
            $table->dropColumn('nfs_rps_env_email');
        });
    }
};
