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
        Schema::table('parametros_fat_nfs_provedores', function (Blueprint $table) {
            $table->integer('provedor_ibge')->nullable();
            $table->string('provedor_uf',2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('parametros_fat_nfs_provedores', function (Blueprint $table) {
            $table->dropColumn('provedor_ibge');
            $table->dropColumn('provedor_uf');
        });
    }
};
