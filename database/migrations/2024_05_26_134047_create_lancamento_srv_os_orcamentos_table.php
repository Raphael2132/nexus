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
        Schema::create('lancamento_srv_os_orcamentos', function (Blueprint $table) {
            $table->id('orc_id');
            $table->string('orc_emp', 6);//empresa da os -> tabela cadastro_empresas.empresa_codigo
            $table->integer('orc_nos');//numero da os -> sequencia sq_lancamento_srv_numero_os
            $table->date('orc_dt_orc');//data orcamento
            $table->integer('orc_num_orc')->default(0);//numero orcamento
            $table->string('orc_cli', 10);//cliente da os -> tabela cadastro_clientes.cliente_codigo
            $table->dateTime('orc_dha');//data e hora da abertura da os
            $table->timestamps();
            $table->unique(['orc_emp','orc_nos'], 'ak_lancamento_srv_os_orcamentos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lancamento_srv_os_orcamentos');
    }
};
