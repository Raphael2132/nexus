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
        Schema::table('parametros_srv_empresas', function (Blueprint $table) {
            $table->decimal('parsrv_hr_ini_ex',4,0)->default(900);
            $table->decimal('parsrv_hr_fin_ex',4,0)->default(1800);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('parametros_srv_empresas', function (Blueprint $table) {
            $table->dropColumn('parsrv_hr_ini_ex');
            $table->dropColumn('parsrv_hr_fin_ex');
        });
    }
};
