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
        Schema::table('parametros_srv_empresas', function (Blueprint $table) {
            $table->enum('parsrv_env_rps_email',['S','N'])->default('N');//Envio automático do RPS no email do cliente
            $table->enum('parsrv_enc_os_email',['S','N'])->default('S');//Avisa o encerramento da OS no Email do cliente
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('parametros_srv_empresas', function (Blueprint $table) {
            $table->dropColumn('parsrv_env_rps_email');
            $table->dropColumn('parsrv_enc_os_email');
        });
    }
};
