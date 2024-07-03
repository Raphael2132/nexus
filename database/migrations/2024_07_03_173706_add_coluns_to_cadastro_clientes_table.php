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
        Schema::table('cadastro_clientes', function (Blueprint $table) {
            $table->date('cliente_dt_inc')->nullable();
        });

        DB::statement('UPDATE cadastro_clientes SET cliente_dt_inc = created_at');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cadastro_clientes', function (Blueprint $table) {
            $table->dropColumn('cliente_dt_inc');
        });
    }
};
