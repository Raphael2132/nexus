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
            $table->integer('parsrv_grp_srv')->nullable();
            $table->integer('parsrv_cod_srv')->nullable();
            $table->enum('parsrv_exg_iss',['1','2','3','4','5','6','7','8'])->default('1');//Exigibilidade do ISS 1 - Exigível, 2 - Não Incidência, 3 - Isenção, 4 - Exportação, 5 - Imunidade, 6 - Suspensa por Decisão Judicial, 7 - Suspensa por Processo Administrativo.
            $table->enum('parsrv_iss_ret',['1','2'])->default('2');//ISS Retido 1 - ISS Retido, 2 - Sem ISS Retido
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('parametros_srv_empresas', function (Blueprint $table) {
            $table->dropColumn('parsrv_grp_srv');
            $table->dropColumn('parsrv_cod_srv');
            $table->dropColumn('parsrv_exg_iss');
            $table->dropColumn('parsrv_iss_ret');
        });
    }
};
