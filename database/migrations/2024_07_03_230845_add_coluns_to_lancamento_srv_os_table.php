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
        Schema::table('lancamento_srv_os', function (Blueprint $table) {
            $table->integer('os_mot_can')->nullable();//motivo do cancelamento
            $table->decimal('os_hrc', 4,0)->default(0);//hora cancelamento
            $table->string('os_obs_can', 255)->nullable();//observações do cancelamento
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lancamento_srv_os', function (Blueprint $table) {
            $table->dropColumn('os_mot_can');
            $table->dropColumn('os_hrc');
            $table->dropColumn('os_obs_can');
        });
    }
};
