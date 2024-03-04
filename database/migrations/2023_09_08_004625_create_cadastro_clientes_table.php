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
        Schema::create('cadastro_clientes', function (Blueprint $table) {
            $table->id('cliente_id');
            $table->enum('cliente_tipo_cadastro', ['C', 'F']);
            $table->string('cliente_codigo', 10);
            $table->enum('cliente_tipo_pessoa', ['F','J']);
            $table->string('cliente_nome', 80);
            $table->string('cliente_cpf_cnpj', 14);
            $table->date('cliente_data_nascimento')->nullable();
            $table->enum('cliente_sexo', ['M', 'F'])->nullable();
            $table->string('cliente_email', 80)->nullable();
            $table->string('cliente_tel_residencial',10)->nullable();
            $table->string('cliente_tel_celular',11)->nullable();
            $table->string('cliente_tel_comercial',10)->nullable();
            $table->string('cliente_rg', 11)->nullable();
            $table->string('cliente_insc_estadual',14)->nullable();
            $table->string('cliente_insc_municipal', 15)->nullable();
            $table->enum('cliente_tipo_email', ['P', 'C'])->nullable();
            $table->timestamps();
            $table->unique('cliente_codigo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cadastro_clientes');
    }
};
