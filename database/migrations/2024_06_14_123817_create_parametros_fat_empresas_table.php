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
        Schema::create('parametros_fat_empresas', function (Blueprint $table) {
            $table->id('parfat_id');
            $table->string('parfat_emp',6)->unsigned();//Empresa
            $table->enum('parfat_sim',['S','N'])->default('N');//Empresa é do Simples Nacional
            $table->decimal('parfat_alq_iss',5,2)->default(0);//Aliquota municipal de imposto sobre serviços
            $table->timestamps();
            $table->unique(['parfat_emp'], 'ak_parametros_fat_empresas');
            $table->foreign('parfat_emp', 'fk_parametros_fat_empresas')->references('empresa_codigo')->on('cadastro_empresas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parametros_fat_empresas');
    }
};
