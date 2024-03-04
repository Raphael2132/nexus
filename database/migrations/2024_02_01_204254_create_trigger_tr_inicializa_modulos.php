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
        DB::unprepared('CREATE TRIGGER tr_inicializa_modulos AFTER INSERT ON cadastro_empresas FOR EACH ROW EXECUTE PROCEDURE fn_inicializa_modulos();');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS tr_inicializa_modulos ON cadastro_empresas');
    }
};
