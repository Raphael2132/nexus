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
        DB::unprepared("CREATE OR REPLACE FUNCTION fn_inicializa_etapa_atendimentos()
        RETURNS trigger
        AS $$
        begin
            INSERT INTO lancamento_srv_etapa_atendimentos (eat_cod, eat_emp, eat_nom, eat_ord, eat_cat, created_at, updated_at)	VALUES (1, NEW.empresa_codigo, 'Solicitação do Cliente', 1, 'C', current_timestamp, current_timestamp);
            INSERT INTO lancamento_srv_etapa_atendimentos (eat_cod, eat_emp, eat_nom, eat_ord, eat_cat, created_at, updated_at)	VALUES (2, NEW.empresa_codigo, 'Solicitação Interna', 1, 'I', current_timestamp, current_timestamp);
            return NEW;
        end;
        $$ LANGUAGE plpgsql;");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP FUNCTION IF EXISTS fn_inicializa_etapa_atendimentos');
    }
};
