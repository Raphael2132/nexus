<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * empresa -> Empresa da NFS-e
     * utiliza_nfs  -> Empresa utiliza NFS-e
     * provedor -> Provedor da geração da NFS-e
     * numeracao -> Numeração utilizada
     * serie -> Série da NFS-e
     * impressao_nfs -> Gera impressão da NFS-e
     * impressao_rps -> Gera a impressão de RPS
     */
    public function up(): void
    {
        Schema::create('parametros_fat_nfs', function (Blueprint $table) {
            $table->string('parnfs_empresa',6)->unsigned()->primary();
            $table->enum('parnfs_utiliza_nfs', ['S', 'N'])->default('N');
            $table->integer('parnfs_provedor')->nullable();
            $table->integer('parnfs_numeracao')->nullable();
            $table->string('parnfs_serie',5)->nullable();
            $table->enum('parnfs_impressao_nfs', ['S', 'N'])->default('N');
            $table->enum('parnfs_impressao_rps', ['S', 'N'])->default('N');
            $table->timestamps();
            $table->foreign('parnfs_empresa', 'fk_parametros_fat_nfs_empresa')->references('empresa_codigo')->on('cadastro_empresas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parametros_fat_nfs');
    }
};
