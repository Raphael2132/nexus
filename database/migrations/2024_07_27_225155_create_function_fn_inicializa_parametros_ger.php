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
        DB::unprepared("CREATE OR REPLACE FUNCTION fn_inicializa_parametros_ger()
        RETURNS trigger
        AS $$
        begin
            INSERT INTO parametros_ger_empresas (parger_emp,created_at,updated_at) VALUES (NEW.empresa_codigo,current_timestamp,current_timestamp);
            INSERT INTO parametros_ger_turnos (partur_emp,partur_cod,partur_desc,created_at,updated_at) VALUES (NEW.empresa_codigo,1,'Funcionamento Padrão da Empresa',current_timestamp,current_timestamp);
            return NEW;
        end;
        $$ LANGUAGE plpgsql;");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP FUNCTION IF EXISTS fn_inicializa_parametros_ger');
    }
};
