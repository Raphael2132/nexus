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
        Schema::create('cadastro_cliente_enderecos', function (Blueprint $table) {
            $table->id('endereco_id');
            $table->biginteger('endereco_seq');
            $table->string('endereco_cliente_codigo', 10)->unsigned();
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
            $table->foreign('endereco_cliente_codigo', 'fk_cadastro_cliente_enderecos')->references('cliente_codigo')->on('cadastro_clientes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cadastro_cliente_enderecos');
    }
};
