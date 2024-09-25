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
        Schema::create('parametros_sis_can_motivos', function (Blueprint $table) {
            $table->id('canmot_id');
            $table->integer('canmot_codigo');
            $table->string('canmot_desc',80)->nullable();
            $table->timestamps();
            $table->unique('canmot_codigo', 'ak_parametros_sis_can_motivos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parametros_sis_can_motivos');
    }
};
