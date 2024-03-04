<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * empresa_nfs -> Empresa que utiliza a conexão da NFS-e
     * provedor_nfs -> Provedor utilizado na emissão da NFS-e
     * usuario_con -> Usuário da Conexão
     * senha_con -> Senha da Conexão
     * token_con -> Token da conexão
     * ambiente_con -> Ambiente da conexão [Homologação, Produção]
     */
    public function up(): void
    {
        Schema::create('parametros_fat_nfs_conexoes', function (Blueprint $table) {
            $table->string('conexao_empresa',6)->unsigned()->primary();
            $table->integer('conexao_provedor')->nullable();
            $table->string('conexao_usuario',80)->nullable();
            $table->string('conexao_senha',80)->nullable();
            $table->string('conexao_token',80)->nullable();
            $table->string('conexao_wsdl',100)->nullable();
            $table->enum('conexao_ambiente', ['H', 'P'])->default('H');
            $table->timestamps();
            $table->foreign('conexao_empresa', 'fk_empresa_fat_nfs_conexoes')->references('empresa_codigo')->on('cadastro_empresas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parametros_fat_nfs_conexoes');
    }
};
