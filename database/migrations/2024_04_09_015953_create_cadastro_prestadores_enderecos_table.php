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
        Schema::create('cadastro_prestadores_enderecos', function (Blueprint $table) {
            $table->id('endereco_id');
            $table->biginteger('endereco_seq');
            $table->string('endereco_prestador_codigo', 6)->unsigned();
            $table->enum('endereco_principal', ['S', 'N']);
            $table->string('endereco_cep',8)->nullable();
            $table->string('endereco_logradouro', 100)->nullable();
            $table->string('endereco_numero',5)->nullable();
            $table->string('endereco_complemento', 60)->nullable();
            $table->string('endereco_bairro', 60)->nullable();
            $table->string('endereco_cidade', 80)->nullable();
            $table->string('endereco_uf', 2)->nullable();
            $table->string('endereco_pais', 40)->nullable();
            $table->timestamps();
            $table->foreign('endereco_prestador_codigo', 'fk_cadastro_prestadores_enderecos')->references('prestador_codigo')->on('cadastro_prestadores')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cadastro_prestadores_enderecos');
    }
};
