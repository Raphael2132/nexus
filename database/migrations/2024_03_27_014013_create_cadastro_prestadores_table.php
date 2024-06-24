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
        Schema::create('cadastro_prestadores', function (Blueprint $table) {
            $table->id('prestador_id');
            $table->string('prestador_codigo',6);
            $table->string('prestador_nome',80);
            $table->string('prestador_cpf', 11);
            $table->string('prestador_empresa', 6);
            $table->date('prestador_data_nascimento')->nullable();
            $table->enum('prestador_sexo', ['M', 'F'])->nullable();
            $table->enum('prestador_tipo_email', ['P', 'C'])->nullable();
            $table->string('prestador_email', 80)->nullable();
            $table->string('prestador_tel_residencial',10)->nullable();
            $table->string('prestador_tel_celular',11)->nullable();
            $table->string('prestador_rg', 11)->nullable();
            $table->enum('prestador_status', ['A', 'D'])->default('A');
            $table->date('prestador_data_admissao')->nullable();
            $table->date('prestador_data_demissao')->nullable();
            $table->string('prestador_set', 6);
            $table->string('prestador_are', 3);
            $table->enum('prestador_acesso_sis', ['S', 'N'])->default('N');
            $table->string('prestador_usuario_cod',6)->nullable();
            $table->timestamps();
            $table->unique(['prestador_codigo'], 'ak_cadastro_prestadores');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cadastro_prestadores');
    }
};
