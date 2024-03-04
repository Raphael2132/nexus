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
        Schema::create('cadastro_empresa_enderecos', function (Blueprint $table) {
            $table->id('endereco_id');
            $table->biginteger('endereco_seq');
            $table->string('endereco_empresa_codigo', 6)->unsigned();
            $table->enum('endereco_principal', ['S', 'N']);
            $table->string('endereco_cep',8)->nullable();
            $table->string('endereco_logradouro', 100)->nullable();
            $table->integer('endereco_numero')->nullable();
            $table->string('endereco_complemento', 60)->nullable();
            $table->string('endereco_bairro', 60)->nullable();
            $table->string('endereco_cidade', 80)->nullable();
            $table->string('endereco_uf', 2)->nullable();
            $table->string('endereco_pais', 40)->nullable();
            $table->timestamps();
            $table->foreign('endereco_empresa_codigo', 'fk_cadastro_empresa_enderecos')->references('empresa_codigo')->on('cadastro_empresas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cadastro_empresa_enderecos');
    }
};
