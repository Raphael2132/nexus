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
        DB::unprepared('CREATE OR REPLACE FUNCTION fn_inicializa_modulos()
        RETURNS trigger
        AS $$
        begin
            INSERT INTO parametros_sistema_modulos (modulo_empresa_codigo,created_at,updated_at) VALUES (NEW.empresa_codigo,current_timestamp,current_timestamp);
            return NEW;
        end;
        $$ LANGUAGE plpgsql;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP FUNCTION IF EXISTS fn_inicializa_modulos');
    }
};
