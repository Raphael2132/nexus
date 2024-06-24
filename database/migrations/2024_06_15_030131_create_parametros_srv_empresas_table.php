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
        Schema::create('parametros_srv_empresas', function (Blueprint $table) {
            $table->id('parsrv_id');
            $table->string('parsrv_emp',6)->unsigned();//Empresa
            $table->decimal('parsrv_alq_iss',5,2)->default(0);//Aliquota municipal de imposto sobre serviços
            $table->decimal('parsrv_cfop',4,0)->default(0);// Código Fiscal de Operações e de Prestações para OS
            $table->timestamps();
            $table->unique(['parsrv_emp'], 'ak_parametros_srv_empresas');
            $table->foreign('parsrv_emp', 'fk_parametros_srv_empresas')->references('empresa_codigo')->on('cadastro_empresas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parametros_srv_empresas');
    }
};
