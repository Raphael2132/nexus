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
        Schema::create('parametros_fin_recebimento_origens', function (Blueprint $table) {
            $table->id('recori_id');
            $table->string('recori_cod',3);//Origem do recebimento NFV - Nota Fiscal de Venda
            $table->string('recori_desc',80);
            $table->timestamps();
            $table->unique('recori_cod', 'ak_parametros_fin_recebimento_origens');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parametros_fin_recebimento_origens');
    }
};
