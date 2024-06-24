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
        Schema::create('faturamento_nfs_xml_enviados', function (Blueprint $table) {
            $table->id('nfsenv_id');
            $table->string('nfsenv_emp',6);//Empresa emissora da nfs
            $table->decimal('nfsenv_num',9,0);//Número da nfs emitida
            $table->integer('nfsenv_nfhdr_num');//Número de controle da NF -> faturamento_nf_headers.nfhdr_num
            $table->string('nfsenv_cnpj',14);//Cnpj do emissor da nfs
            $table->string('nfsenv_pro',64);//NA FASE DE ENVIO DE LOTE RECEBE O PROTOCOLO DO RECEBIMENTO DO LOTE, NA FASE FINAL DA EMISSAO RECEBE O CODIGO DE VALIDACAO DA NOTA EMITIDA
            $table->enum('nfsenv_sts',['1','2','3'])->default('1');//Status da emissão '1': EM EMISSAO '2':ERRO NA EMISSAO '3': EMISSAO BEM SUCEDIDA
            $table->binary('nfsenv_xml_env')->nullable();//TEXTO DO XML ORIGINAL (EVENTUALMENTE ASSINADO) E ENVIADO
            $table->binary('nfsenv_xml_rcb')->nullable();//TEXTO DA RESPOSTA RECEBIDA (PODE SER FINAL OU CONTER APENAS PROTOCOLO DO LOTE)
            $table->binary('nfsenv_xml_cns')->nullable();//TEXTO DA RESPOSTA A CONSULTA COM O PROTOCOLO DO LOTE
            $table->dateTime('nfsenv_dt_atu');//DATA DA ATUALIZACAO DO LINHA EM CADA FASE DO PROCESSO
            $table->dateTime('nfsenv_dt_inc');//DATA DA INCLUSAO DA LINHA NO BANCO DE DADOS
            $table->decimal('nfsenv_num_nfs',9,0);//NUMERO DA NOTA FISCAL DE SERVICOS ATRIBUIDA A NFS
            $table->string('nfsenv_obs',4000)->nullable();//Observações do retorno do webservice
            $table->timestamps();
            $table->unique(['nfsenv_emp','nfsenv_num'], 'ak_faturamento_nfs_xml_retornos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faturamento_nfs_xml_enviados');
    }
};
