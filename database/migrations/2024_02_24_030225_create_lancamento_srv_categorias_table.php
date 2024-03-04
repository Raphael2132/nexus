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
        Schema::create('lancamento_srv_categorias', function (Blueprint $table) {
            $table->id('categoria_id');
            $table->string('categoria_codigo', 1);
            $table->string('categoria_desc', 80);
            $table->timestamps();
            $table->unique(['categoria_codigo'], 'ak_lancamento_srv_categorias');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lancamento_srv_categorias');
    }
};
