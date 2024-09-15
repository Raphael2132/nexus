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
        Schema::table('cadastro_empresas', function (Blueprint $table) {
            $table->string('empresa_smtp_host', 80)->nullable();
            $table->integer('empresa_smtp_port')->nullable();
            $table->string('empresa_smtp_username', 60)->nullable();
            $table->string('empresa_smtp_password', 30)->nullable();
            $table->string('empresa_smtp_encryption', 3)->nullable();  // tls, ssl, etc.
            $table->string('empresa_smtp_from_address', 80)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cadastro_empresas', function (Blueprint $table) {
            $table->dropColumn('empresa_smtp_host');
            $table->dropColumn('empresa_smtp_port');
            $table->dropColumn('empresa_smtp_username');
            $table->dropColumn('empresa_smtp_password');
            $table->dropColumn('empresa_smtp_encryption');
            $table->dropColumn('empresa_smtp_from_address');
        });
    }
};
