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
        Schema::table('faturamento_nfs', function (Blueprint $table) {
            $table->decimal('nfs_inss_ret',15,2)->default(0); 
            $table->decimal('nfs_ir_ret',15,2)->default(0); 
            $table->decimal('nfs_csll_ret',15,2)->default(0); 
            $table->decimal('nfs_pis_ret',15,2)->default(0); 
            $table->decimal('nfs_cofins_ret',15,2)->default(0); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('faturamento_nfs', function (Blueprint $table) {
            $table->dropColumn('nfs_inss_ret');
            $table->dropColumn('nfs_ir_ret');
            $table->dropColumn('nfs_csll_ret');
            $table->dropColumn('nfs_pis_ret');
            $table->dropColumn('nfs_cofins_ret');
        });
    }
};
