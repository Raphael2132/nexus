<?php

namespace App\Http\Controllers\Nfs\Layouts;

use Illuminate\Support\Facades\DB;
use stdClass;

use Illuminate\Support\Facades\Http;
use Psr\Http\Message\RequestInterface;

use Symfony\Component\HttpKernel\Exception\HttpException;

use App\Http\Controllers\Nfs\Core\Nfsxml;
use App\Http\Controllers\Nfs\Util\Config;
use App\Http\Helpers\Helper;

class SaoJoaoDaBoaVista{
    
    public static function nfsxml(Nfsxml $nfsxml){

        //echo "<br> Entrei no gerarXML <br>";
        
        $cabecalho = $nfsxml->nfs->cabecalho;
        $prestador = $nfsxml->nfs->prestador;
        $tributacao = $nfsxml->nfs->tributacao;
        $tomador = $nfsxml->nfs->tomador;
        $parametros = $nfsxml->nfs->parametros;
        $servico = $nfsxml->nfs->itens;
        
        $dom = new \DOMDocument('1.0', 'utf-8');
        $dom->formatOutput = true;

        $xmlProcessaNFS = $dom->createElement("nfe");

        $xmlProcessaNFS->appendChild($dom->createElement("notaFiscal"));

        //TAG REFERENTE AOS DADOS DO PRESTADOR
        $xmlPrestador = $dom->createElement("dadosPrestador");

        $dataEmi = date("d/m/Y",strtotime($nfsxml->nfs->dataEmissao));

        $xmlPrestador->appendChild($dom->createElement("dataEmissao", $dataEmi));
        $xmlPrestador->appendChild($dom->createElement("im", str_pad($prestador->inscMunicipal, 9, "0", STR_PAD_LEFT)));
        $xmlPrestador->appendChild($dom->createElement("numeroRps", str_pad($nfsxml->nfs->numero, 9, "0", STR_PAD_LEFT)));

        //TAG REFERENTE AO LOCAL DE PRESTAÇÃO DO SERVIÇO
        $xmlServico = $dom->createElement("dadosServico");

        $paisServico = $cabecalho->locServicoPais;

        if(!empty($paisServico)){
            if(strtoupper($cabecalho->locServicoPais) == 'BRASIL'){
                $cidadeSrv = $cabecalho->locServicoCidade;
                $paisSrv = $cabecalho->locServicoPais;
                $ufSrv = $cabecalho->locServicoUF;
            }else{
                $cidadeSrv = "EXTERIOR";
                $paisSrv = $cabecalho->locServicoPais;
                $ufSrv = "EX";
            }
        }else{
            $cidadeSrv = $cabecalho->locServicoCidade;
            $paisSrv = 'BRASIL';
            $ufSrv = $cabecalho->locServicoUF;
        }
        
        $xmlServico->appendChild($dom->createElement("bairro", Helper::removerAcento($cabecalho->locServicoBairro,'S')));
        $xmlServico->appendChild($dom->createElement("cep", Helper::mascaraCEP($cabecalho->locServicoCep)));
        $xmlServico->appendChild($dom->createElement("cidade", Helper::removerAcento($cidadeSrv,'S')));
        $xmlServico->appendChild($dom->createElement("complemento", Helper::removerAcento($cabecalho->locServicoComplemento,'S')));
        $xmlServico->appendChild($dom->createElement("logradouro",Helper::removerAcento($cabecalho->locServicoLogradouro,'S')));
        $xmlServico->appendChild($dom->createElement("numero", $cabecalho->locServicoNumero));
        $xmlServico->appendChild($dom->createElement("pais", Helper::removerAcento($paisSrv,'S')));
        $xmlServico->appendChild($dom->createElement("uf", $ufSrv));

        //TAG REFERENTE AOS DADOS DO TOMADOR
        $xmlTomador = $dom->createElement("dadosTomador");

        $paisTomador = $tomador->pais;

        if(!empty($paisTomador)){
            if(strtoupper($tomador->pais) == 'BRASIL'){
                $cidadeTom = $tomador->cidade;
                $paisTom = $tomador->pais;
                $ufTom = $tomador->uf;
            }else{
                $cidadeTom = "EXTERIOR";
                $paisTom = $tomador->pais;
                $ufTom = "EX";
            }
        }else{
            $cidadeTom = $tomador->cidade;
            $paisTom = 'BRASIL';
            $ufTom = $tomador->uf;
        }

        $xmlTomador->appendChild($dom->createElement("bairro", Helper::removerAcento($tomador->bairro,'S')));
        $xmlTomador->appendChild($dom->createElement("cep", Helper::mascaraCEP($tomador->cep)));
        $xmlTomador->appendChild($dom->createElement("cidade", Helper::removerAcento($cidadeTom,'S')));
        $xmlTomador->appendChild($dom->createElement("complemento", Helper::removerAcento($tomador->complemento,'S')));
        $xmlTomador->appendChild($dom->createElement("documento", $tomador->cpfCnpj));
		$xmlTomador->appendChild($dom->createElement("email", $tomador->email));
		$xmlTomador->appendChild($dom->createElement("ie", $tomador->inscEstadual));
        $xmlTomador->appendChild($dom->createElement("logradouro",Helper::removerAcento($tomador->logradouro,'S')));
        $xmlTomador->appendChild($dom->createElement("nomeTomador",Helper::removerAcento($tomador->nomeTomador,'S')));
        $xmlTomador->appendChild($dom->createElement("numero", $tomador->numero));
        $xmlTomador->appendChild($dom->createElement("pais", Helper::removerAcento($paisTom,'S')));
        $xmlTomador->appendChild($dom->createElement("tipoDoc", $tomador->tipoTomador));
        $xmlTomador->appendChild($dom->createElement("uf", $ufTom));

        //TAG REFERENTE AOS DADOS DO SERVICO
        $xmlDetServico = $dom->createElement("detalheServico");

        if($tributacao->issRetido == 2){
            $issRetido = 0;
        }else{
            $issRetido = 1;
        }

        $xmlDetServico->appendChild($dom->createElement("cofins", $tributacao->valorCOFINS));
        $xmlDetServico->appendChild($dom->createElement("csll", $tributacao->valorCSLL));
        $xmlDetServico->appendChild($dom->createElement("deducaoMaterial", $cabecalho->valorTotalDeducoes));
        $xmlDetServico->appendChild($dom->createElement("descontoIncondicional", "0.00"));
		$xmlDetServico->appendChild($dom->createElement("inss", $tributacao->valorINSS));
		$xmlDetServico->appendChild($dom->createElement("ir", $tributacao->valorIR));
        $xmlDetServico->appendChild($dom->createElement("issRetido", $issRetido));

        //TAG REFERENTE AO ITEM DO SERVICO
        $xmlDetServicoItem = $dom->createElement("item");

        $descriminacao = '';

        /*
        foreach($servico as $item){

            if($item->emiSimplificada == 'N'){
                if(empty($descriminacao)){
                    $descriminacao = $item->discriminacaoServico.' - qtd. horas: '.$item->quantidade.' - valor Liquido: '.$item->valorTotalLiquido;
                }else{
                    $descriminacao .= ' | '.$item->discriminacaoServico.' - qtd. horas: '.$item->quantidade.' - valor Liquido: '.$item->valorTotalLiquido;
                }
            }else{
                if(empty($descriminacao)){
                    $descriminacao = $item->descServico;
                    if(!empty($item->infoComplementar)){
                        $descriminacao .= ' | '.$item->infoComplementar;
                    }
                }else{
                    $descriminacao .= ' | '.$item->descServico;
                    if(!empty($item->infoComplementar)){
                        $descriminacao .= ' | '.$item->infoComplementar;
                    }
                }
            }
        }
        */

        foreach($servico as $item){

            if(empty($descriminacao)){
                $descriminacao = $item->discriminacaoServico;
            }else{
                $descriminacao .= ' | '.$item->discriminacaoServico;
            }
        }

        $xmlDetServicoItem->appendChild($dom->createElement("aliquota", $tributacao->aliqAtividade));
        $xmlDetServicoItem->appendChild($dom->createElement("codigo", str_pad($prestador->codAtividade, 4, "0", STR_PAD_LEFT)));
        $xmlDetServicoItem->appendChild($dom->createElement("descricao", Helper::removerAcento($descriminacao,'S')));
        $xmlDetServicoItem->appendChild($dom->createElement("valor", $cabecalho->valorTotalNota));

        $xmlDetServico->appendChild($xmlDetServicoItem);

        $xmlDetServico->appendChild($dom->createElement("obs", Helper::removerAcento($cabecalho->observacaoNota,'S')));
        $xmlDetServico->appendChild($dom->createElement("pisPasep", $tributacao->valorPIS));

        $xmlProcessaNFS->appendChild($xmlPrestador);

        $xmlProcessaNFS->appendChild($xmlServico);

        $xmlProcessaNFS->appendChild($xmlTomador);

        $xmlProcessaNFS->appendChild($xmlDetServico);

        $dom->appendChild($xmlProcessaNFS);

        //echo "<pre>".htmlentities($dom->saveXML())."</pre>";

        return $dom;
    }

    public static function startConnection(Nfsxml $nfsxml, $documentoXML){

        //echo "<br> Entrei no startConnection <br>";

        //Quando tiver acesso a prefeitura resolver como vai usar os parametros
        $parametros = $nfsxml->nfs->parametros;

        //var_dump($parametros);

        //echo "<pre>".htmlentities($documentoXML->saveHTML())."</pre>";

        try {
            $response = Http::withRequestMiddleware(
                function (RequestInterface $request) {
                    return $request->withHeader('postman-token','bde5f2ea-327a-ee59-1178-2a7b68a738e1')
                    ->withHeader('cache-control' , 'no-cache')
                    ->withHeader('content-type' , 'application/xml')
                    ->withHeader('authorization' , '999991-2EU2TPWLJBP2H57HL605K24778989PPP');            
                }
            )->post('http://webservice.intertecsolucoes.com.br/WSNfsesPsjv/nfseresources/ws/v2/emissao/simula',['body' => $documentoXML]);

            //echo "<pre>".htmlentities(utf8_encode($response->getBody()))."</pre>";
            //echo $response->getStatusCode();
            //echo $response->getHeader('content-type')[0];
            //echo $response->getContents();

            //Se conseguiu fazer a comunicação trata o retorno
            if($response->getStatusCode() == 401){//trocar para 200 quando tiver acesso a prefeitura

                
                //Estamos simulando um retorno por falta de acesso a prefeitura depois que tiver excluir isso
                $xmlRetorno = '<nfeResposta>
                        <notaFiscal>
                            <numeroNota>0</numeroNota>
                            <numeroRps>5</numeroRps>
                            <codigoVerificacao>45963RGOP0X</codigoVerificacao>
                            <statusEmissao>200</statusEmissao>
                            <messages code="200" message="NFSE emitida com sucesso"/>
                        </notaFiscal>
                    </nfeResposta>';
                
                
                /*$xmlRetorno = '
                    <?xml version="1.0" encoding="ISO-8859-1" standalone="yes"?>
                    <nfeResposta>
                        <notaFiscal>
                            <numeroNota>0</numeroNota>
                            <numeroRps>5</numeroRps>
                            <codigoVerificacao>45963RGOP0X</codigoVerificacao>
                            <statusEmissao>500</statusEmissao>
                            <messages code="500" message="O número de RPS 4 já existe."/>
                        </notaFiscal>
                    </nfeResposta>';
                */

                // Carregar o XML
                $dom = new \DOMDocument('1.0', 'utf-8');
                $dom->formatOutput = true;
                $dom->loadXML(trim($xmlRetorno));

                //echo "<pre>".htmlentities($dom->saveXML())."</pre>";//Terminamos a simulação de retorno, excluir até aki quando tiver acesso

                //Salva o xml de retorno da prefeitura
                $nomeArquivoRetorno = Config::gerarNomeArquivoRetorno($nfsxml);
                $path = $nfsxml->layout->config->pathDownloadRetorno.$nomeArquivoRetorno;
                $dom->save($path);

                self::processaRetorno($nfsxml, $dom, 200, '', $path, $nomeArquivoRetorno);//quando tiver acesso a prefeitura mudar o terceiro parametro para o status correto da conexao

            }else{

                $stsConexao = $response->getStatusCode();
                $msgConexao = Helper::removerAcento(utf8_encode($response->getBody()),'N');

                self::processaRetorno($nfsxml, '', $stsConexao, $msgConexao, '', '');

            }

        } catch (HttpException $ex) {
            echo $ex;
        }
    }

    public static function processaRetorno($nfsxml, $retorno, $stsConexao, $msgErroConexao, $pathRetorno, $nomeArquivoRetorno){
        
        //echo "<br> Entrei no processaRetorno <br>";

        //echo "<br>Sts Con: ".$stsConexao." / Erro: ".$msgErroConexao."<br>";

        //Verifica se a nota ja existe na tabela de envios
        $cntEnv = DB::table('faturamento_nfs_xml_envios')->where('nfsenv_emp', $nfsxml->empresa)->where('nfsenv_num', $nfsxml->nfs->numero)->count();

        $xmlEnv = file_get_contents($nfsxml->layout->config->pathDownload.$nfsxml->nomeArquivo);
        $dataIns = date('Y-m-d H:i:s');

        //Verifica se conseguiu a comunicação ou não
        if($stsConexao == 200){
            
            // Acessar os valores das tags
            $notaFiscal = $retorno->getElementsByTagName('notaFiscal')->item(0);

            $numeroNota = $notaFiscal->getElementsByTagName('numeroNota')->item(0)->nodeValue;
            $numeroRps = $notaFiscal->getElementsByTagName('numeroRps')->item(0)->nodeValue;
            $codigoVerificacao = $notaFiscal->getElementsByTagName('codigoVerificacao')->item(0)->nodeValue;
            $statusEmissao = $notaFiscal->getElementsByTagName('statusEmissao')->item(0)->nodeValue;

            $messages = $notaFiscal->getElementsByTagName('messages')->item(0);
            $messageCode = $messages->getAttribute('code');
            $messageText = Helper::removerAcento(utf8_decode($messages->getAttribute('message')),'N');

            if($statusEmissao == 200){
                $status = '3';
            }else{
                $status = '2';
            }

            /*
            if($numeroNota == 0){
                $numeroNota = $numeroRps;
            }*/

            $xmlRet = file_get_contents($pathRetorno);

            // Exibir os valores
            //echo "Numero Nota: $numeroNota<br>";
            //echo "Numero RPS: $numeroRps<br>";
            //echo "Codigo Verificacao: $codigoVerificacao<br>";
            //echo "Status Emissao: $statusEmissao<br>";
            //echo "Mensagem: $messageText<br>";
            //echo "Codigo: $messageCode<br> <br>";

            //Se ja existe faz update se não existe faz insert
            if($cntEnv == 0){

                DB::table('faturamento_nfs_xml_envios')->insert([
                    'nfsenv_emp' => $nfsxml->empresa,
                    'nfsenv_num' => $nfsxml->nfs->numero,
                    'nfsenv_nfhdr_num' => $nfsxml->numControle,
                    'nfsenv_cnpj' => $nfsxml->nfs->cabecalho->cnpjEmp,
                    'nfsenv_cod_ver' => $codigoVerificacao,
                    'nfsenv_sts' => $status,
                    'nfsenv_xml_env' => $xmlEnv,
                    'nfsenv_xml_ret' => $xmlRet,
                    'nfsenv_dt_atu' => $dataIns,
                    'nfsenv_dt_inc' => $dataIns,
                    'nfsenv_num_nfs' => $numeroNota,
                    'nfsenv_sts_emi' => $statusEmissao,
                    'nfsenv_obs' => $messageText,
                    'nfsenv_nom_arq_env' => $nfsxml->nomeArquivo,
                    'nfsenv_nom_arq_ret' => $nomeArquivoRetorno,
                ]);

            }else{

                DB::table('faturamento_nfs_xml_envios')
                ->where('nfsenv_emp', $nfsxml->empresa)
                ->where('nfsenv_num', $nfsxml->nfs->numero)
                ->update([
                    'nfsenv_cod_ver' => $codigoVerificacao,
                    'nfsenv_sts' => $status,
                    'nfsenv_xml_env' => $xmlEnv,
                    'nfsenv_xml_ret' => $xmlRet,
                    'nfsenv_dt_atu' => $dataIns,
                    'nfsenv_num_nfs' => $numeroNota,
                    'nfsenv_sts_emi' => $statusEmissao,
                    'nfsenv_obs' => $messageText,
                    'nfsenv_nom_arq_env' => $nfsxml->nomeArquivo,
                    'nfsenv_nom_arq_ret' => $nomeArquivoRetorno,
                ]);

            }

            //Atualiza o número da NFS-e Emitida pela prefeitura na tabela faturamento_nfs
            DB::table('faturamento_nfs')
            ->where('nfs_emp', $nfsxml->empresa)
            ->where('nfs_nfhdr_num', $nfsxml->numControle)
            ->where('nfs_nrps', $nfsxml->nfs->numero)
            ->update([
                'nfs_nnfs' => $numeroNota
            ]);

        }else{

            //Se ja existe faz update se não existe faz insert
            if($cntEnv == 0){

                DB::table('faturamento_nfs_xml_envios')->insert([
                    'nfsenv_emp' => $nfsxml->empresa,
                    'nfsenv_num' => $nfsxml->nfs->numero,
                    'nfsenv_nfhdr_num' => $nfsxml->numControle,
                    'nfsenv_cnpj' => $nfsxml->nfs->cabecalho->cnpjEmp,
                    'nfsenv_sts' => '1',
                    'nfsenv_xml_env' => $xmlEnv,
                    'nfsenv_dt_atu' => $dataIns,
                    'nfsenv_dt_inc' => $dataIns,
                    'nfsenv_sts_emi' => $stsConexao,
                    'nfsenv_obs' => $msgErroConexao,
                    'nfsenv_nom_arq_env' => $nfsxml->nomeArquivo,
                    'nfsenv_nom_arq_ret' => $nomeArquivoRetorno,
                ]);

            }else{

                DB::table('faturamento_nfs_xml_envios')
                ->where('nfsenv_emp', $nfsxml->empresa)
                ->where('nfsenv_num', $nfsxml->nfs->numero)
                ->update([
                    'nfsenv_cod_ver' => null,
                    'nfsenv_sts' => '1',
                    'nfsenv_xml_env' => $xmlEnv,
                    'nfsenv_xml_ret' => null,
                    'nfsenv_dt_atu' => $dataIns,
                    'nfsenv_num_nfs' => null,
                    'nfsenv_sts_emi' => $stsConexao,
                    'nfsenv_obs' => $msgErroConexao,
                    'nfsenv_nom_arq_env' => $nfsxml->nomeArquivo,
                    'nfsenv_nom_arq_ret' => $nomeArquivoRetorno,
                ]);
            }
        }
    }
}
