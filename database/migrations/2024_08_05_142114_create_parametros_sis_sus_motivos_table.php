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
        Schema::create('parametros_sis_sus_motivos', function (Blueprint $table) {
            $table->id('susmot_id');
            $table->integer('susmot_codigo');
            $table->string('susmot_desc',80)->nullable();
            $table->timestamps();
            $table->unique('susmot_codigo', 'ak_parametros_sis_sus_motivos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parametros_sis_sus_motivos');
    }
};
