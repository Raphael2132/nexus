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
        Schema::table('lancamento_srv_os_servicos', function (Blueprint $table) {
            $table->date('srv_dt_inc')->nullable();/* Data de inclusão da Tarefa */
        });

        // Atualizar os registros existentes
        DB::table('lancamento_srv_os_servicos')->update([
            'srv_dt_inc' => DB::raw('DATE(created_at)')
        ]);

        Schema::table('lancamento_srv_os_servicos', function (Blueprint $table) {
            $table->date('srv_dt_inc')->nullable(false)->change();
            $table->date('srv_dti')->nullable(true)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lancamento_srv_os_servicos', function (Blueprint $table) {
            $table->dropColumn('srv_dt_inc');
        });
    }
};
