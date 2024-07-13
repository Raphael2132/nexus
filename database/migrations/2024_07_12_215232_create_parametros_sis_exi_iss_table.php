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
        Schema::create('parametros_sis_exi_iss', function (Blueprint $table) {
            $table->id('exiiss_id');
            $table->integer('exiiss_codigo');
            $table->string('exiiss_desc',80);
            $table->timestamps();
            $table->unique('exiiss_codigo', 'ak_parametros_sis_exi_iss');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parametros_sis_exi_iss');
    }
};
