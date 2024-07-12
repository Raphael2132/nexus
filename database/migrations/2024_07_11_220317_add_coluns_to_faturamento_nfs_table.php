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
            $table->enum('nfs_loc_srv',['E','C','O'])->default('E');//Endereço do local da prestação do serviço E - Empresa, C - Cliente, O - Outro
            $table->string('nfs_loc_srv_cep',8)->nullable();
            $table->string('nfs_loc_srv_logradouro', 100)->nullable();
            $table->string('nfs_loc_srv_numero',5)->nullable();
            $table->string('nfs_loc_srv_complemento', 60)->nullable();
            $table->string('nfs_loc_srv_bairro', 60)->nullable();
            $table->string('nfs_loc_srv_cidade', 80)->nullable();
            $table->string('nfs_loc_srv_uf', 2)->nullable();
            $table->string('nfs_loc_srv_pais', 40)->nullable();
            $table->integer('nfs_loc_srv_ibge_cod_mun')->nullable();//Código IBGE do Municipio
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('faturamento_nfs', function (Blueprint $table) {
            $table->dropColumn('nfs_loc_srv');
            $table->dropColumn('nfs_loc_srv_cep');
            $table->dropColumn('nfs_loc_srv_logradouro');
            $table->dropColumn('nfs_loc_srv_numero');
            $table->dropColumn('nfs_loc_srv_complemento');
            $table->dropColumn('nfs_loc_srv_bairro');
            $table->dropColumn('nfs_loc_srv_cidade');
            $table->dropColumn('nfs_loc_srv_uf');
            $table->dropColumn('nfs_loc_srv_pais');
            $table->dropColumn('nfs_loc_srv_ibge_cod_mun');
        });
    }
};
