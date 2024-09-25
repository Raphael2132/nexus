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
        Schema::create('lancamento_srv_tipo_servicos', function (Blueprint $table) {
            $table->id('tipsrv_id');
            $table->string('tipsrv_emp', 6)->unsigned();
            $table->string('tipsrv_cod', 2);
            $table->string('tipsrv_nom', 80);
            $table->string('tipsrv_are', 3);
            $table->string('tipsrv_cat', 1);
            $table->enum('tipsrv_pmt_des', ['S','N'])->default('N');//Permite desconto no lancamento da TMO
            $table->decimal('tipsrv_pmd', 4,2)->nullable();//percentual máximo de de desconto
            $table->decimal('tipsrv_vmd', 15,2)->nullable();//valor máximo de de desconto
            $table->enum('tipsrv_ahs', ['S','N'])->default('N');//altera hora serviço
            $table->enum('tipsrv_avs', ['S','N'])->default('N');//altera valor serviço
            $table->enum('tipsrv_sts', ['A','D'])->default('A');
            $table->timestamps();
            $table->unique(['tipsrv_emp','tipsrv_cod'], 'ak_lancamento_srv_tipo_servicos');
            $table->foreign('tipsrv_emp', 'fk_lancamento_srv_tipo_servicos')->references('empresa_codigo')->on('cadastro_empresas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lancamento_srv_tipo_servicos');
    }
};
