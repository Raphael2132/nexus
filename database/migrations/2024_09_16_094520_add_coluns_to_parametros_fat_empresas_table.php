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
        Schema::table('parametros_fat_empresas', function (Blueprint $table) {
            $table->enum('parfat_env_nfs_email',['S','N'])->default('N');//Envio automático da NFS-e no email do cliente
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('parametros_fat_empresas', function (Blueprint $table) {
            $table->dropColumn('parfat_env_nfs_email');
        });
    }
};
