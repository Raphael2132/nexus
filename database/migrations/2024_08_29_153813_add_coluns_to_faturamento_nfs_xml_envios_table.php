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
        Schema::table('faturamento_nfs_xml_envios', function (Blueprint $table) {
            $table->string('nfsenv_nom_arq_env',80)->nullable();//Nome do arquivo de xml de envio
            $table->string('nfsenv_nom_arq_ret',80)->nullable();//Nome do arquivo de xml de retorno
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('faturamento_nfs_xml_envios', function (Blueprint $table) {
            $table->dropColumn('nfsenv_nom_arq_env');
            $table->dropColumn('nfsenv_nom_arq_ret');
        });
    }
};
