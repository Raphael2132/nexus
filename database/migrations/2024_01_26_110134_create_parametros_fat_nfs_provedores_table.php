<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * provedor_id -> Código do Provedor da NFS-e no Sistema.
     * provedor_desc -> Descrição do Provedor da NFS-e no Sistema.
     */
    public function up(): void
    {
        Schema::create('parametros_fat_nfs_provedores', function (Blueprint $table) {
            $table->id('provedor_id');
            $table->string('provedor_desc',80);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parametros_fat_nfs_provedores');
    }
};
