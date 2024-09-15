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
        Schema::create('parametros_sis_email_setores', function (Blueprint $table) {
            $table->id('emaset_id');
            $table->string('emaset_cod',2);
            $table->string('emaset_desc');
            $table->string('emaset_email', 80);
            $table->timestamps();
            $table->unique('emaset_cod', 'ak_parametros_sis_email_setores');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parametros_sis_email_setores');
    }
};
