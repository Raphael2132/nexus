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
        Schema::table('users', function (Blueprint $table) {
            $table->string('usuario_codigo', 6);
            $table->enum('usuario_status', ['A', 'D'])->default('A');
            $table->enum('usuario_tipo', ['M', 'A', 'P'])->default('P');//Master Administrador Padrão
            $table->enum('usuario_altera_permissoes_acesso', ['S', 'N'])->default('N');
            $table->enum('usuario_acesso_pararametros', ['S', 'N'])->default('N');
            $table->enum('usuario_acesso_cadastros', ['S', 'N'])->default('N');
            $table->string('usuario_cpf',11)->nullable();
            $table->string('usuario_rg', 9)->nullable();
            $table->date('usuario_data_nascimento')->nullable();
            $table->enum('usuario_sexo', ['M', 'F'])->nullable();
            $table->string('usuario_tel_residencial',10)->nullable();
            $table->string('usuario_tel_celular',11)->nullable();
            $table->enum('usuario_tipo_email', ['P', 'C'])->nullable();
            $table->unique('usuario_codigo', 'ak_users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('usuario_codigo');
            $table->dropColumn('usuario_status');
            $table->dropColumn('usuario_tipo');
            $table->dropColumn('usuario_altera_permissoes_acesso');
            $table->dropColumn('usuario_acesso_pararametros');
            $table->dropColumn('usuario_acesso_cadastros');
            $table->dropColumn('usuario_cpf');
            $table->dropColumn('usuario_rg');
            $table->dropColumn('usuario_data_nascimento');
            $table->dropColumn('usuario_sexo');
            $table->dropColumn('usuario_tel_residencial');
            $table->dropColumn('usuario_tel_celular');
            $table->dropColumn('usuario_tipo_email');
        });
    }
};
