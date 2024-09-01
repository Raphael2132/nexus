@extends('adminlte::page')

@section('title', 'Emissão Simplificada de NFS-e')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h4 style="margin-bottom: 0px !important;">Faturamento de Notas</h4>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active"><a href="{{route('home.emissaoSimpNFS')}}">Emissão Simplificada</a></li>
            <li class="breadcrumb-item active">Formulario Emissão Simplificada</li>
        </ol>
    </div>
</div>
@stop

@section('content')
<div class="d-flex justify-content-center">
    <div class="col-md-12">
        <form method="post" action="{{ route('emissaoSimpNFS.emitirNFS', ['empresa' => $empresa, 'cliente' => $cliente, 'enderecoCli' => $enderecoCli, 'enderecoLocSrv'=> $enderecoLocSrv]) }}" id="quickForm" novalidate="novalidate">
            @csrf 
            @method('post')
            <x-adminlte-card title="Emissão Simplificada de NFS-e" theme="" theme-mode="" header-class="card-nexus" collapsible maximizable>
                @php
                    //Dados da Empresa
                    $data_emp = DB::table('cadastro_empresas')->where('empresa_codigo', $empresa)->get();

                    $empresa_os = $empresa.' - '.$data_emp[0]->empresa_nome;
                    $cnpj = Helper::mascaraCNPJ($data_emp[0]->empresa_cnpj);

                    if(!empty($data_emp[0]->empresa_insc_estadual)){
                        $inscEstadual = $data_emp[0]->empresa_insc_estadual;
                    }else{
                        $inscEstadual = '';
                    }

                    if(!empty($data_emp[0]->empresa_insc_municipal)){
                        $inscMunicipal = $data_emp[0]->empresa_insc_municipal;
                    }else{
                        $inscMunicipal = '';
                    }

                    $dataParFatEmp = DB::table('parametros_fat_empresas')->where('parfat_emp', $empresa)->get();

                    if($dataParFatEmp[0]->parfat_sim == "S"){
                        $optSimples = 'Sim';
                    }else{
                        $optSimples = 'Não';
                    }

                    //Dados do Cliente
                    $data_cli = DB::table('cadastro_clientes')->where('cliente_codigo', $cliente)->get();

                    $cliente_os = $cliente.' - '.$data_cli[0]->cliente_nome;
                    $inscEst_cli = '';
                    $inscMun_cli = '';
                    $cnpj_cli = '';
                    $rg_cli = '';
                    $sexo_cli = '';
                    $telRes_cli = '';
                    $telCel_cli = '';
                    $telCom_cli = '';
                    $tipEma_cli = '';
                    $email_cli = '';

                    if($data_cli[0]->cliente_tipo_cadastro == 'C'){

                        $tipoCad = 'Cliente';

                        if($data_cli[0]->cliente_tipo_pessoa == 'J'){

                            $tipoPes = 'Jurídica';

                            $cnpj_cli = Helper::mascaraCNPJ($data_cli[0]->cliente_cpf_cnpj);

                            if(!empty($data_cli[0]->cliente_insc_estadual)){
                                $inscEst_cli = $data_cli[0]->cliente_insc_estadual;
                            }
                            if(!empty($data_cli[0]->cliente_insc_municipal)){
                                $inscMun_cli = $data_cli[0]->cliente_insc_municipal;
                            }
                        }else{

                            $tipoPes = 'Fisíca';

                            if(!empty($data_cli[0]->cliente_rg)){
                                $rg_cli = Helper::mascaraRG($data_cli[0]->cliente_rg);
                            }

                            $cpf_cli = Helper::mascaraCPF($data_cli[0]->cliente_cpf_cnpj);

                            if(!empty($data_cli[0]->cliente_sexo)){
                                if($data_cli[0]->cliente_sexo == 'M'){
                                    $sexo_cli = "Masculino";
                                }else{
                                    $sexo_cli = "Feminino";
                                }
                            }
                        }
                    }else{
                        $tipoCad = 'Fornecedor';
                        $tipoPes = 'Jurídica';

                        $cnpj_cli = Helper::mascaraCNPJ($data_cli[0]->cliente_cpf_cnpj);
                    }

                    if(!empty($data_cli[0]->cliente_tel_residencial)){
                        $telRes_cli = Helper::mascaraTelResidencial($data_cli[0]->cliente_tel_residencial);
                    }else{
                        $telRes_cli = "Não Informado";
                    }
                    
                    if(!empty($data_cli[0]->cliente_tel_celular)){
                        $telCel_cli = Helper::mascaraTelCelular($data_cli[0]->cliente_tel_celular);
                    }else{
                        $telCel_cli = "Não Informado";
                    }

                    if(!empty($data_cli[0]->cliente_tel_comercial)){
                        $telCom_cli = Helper::mascaraTelComercial($data_cli[0]->cliente_tel_comercial);
                    }else{
                        $telCom_cli = "Não Informado";
                    }

                    if(!empty($data_cli[0]->cliente_tipo_email)){
                        if($data_cli[0]->cliente_tipo_email == 'P'){
                            $tipEma_cli = "Pessoal";
                        }else{
                            $tipEma_cli = "Comercial";
                        }
                    }
                    if(!empty($data_cli[0]->cliente_email)){
                        $email_cli = $data_cli[0]->cliente_email;
                    }
                @endphp
                <div class="row">
                    <div class="col-md-6">
                        <!-- Dados da Empresa -->
                        <x-adminlte-callout title="Dados da Empresa" title-class="text-uppercase" icon="fa-solid fa-building" theme="" class="callout-nexus">
                            <div class="text-muted">
                                <div class="row quebra-linha">
                                    <p class="text-sm col-md-4">Empresa
                                        <b class="d-block">{{ $empresa_os }}</b>
                                    </p>
                                    <p class="text-sm col-md-4">CNPJ
                                        <b class="d-block">{{ $cnpj }}</b>
                                    </p>
                                </div>
                                <div class="row quebra-linha">
                                    <p class="text-sm col-md-4">Simples Nacional
                                        <b class="d-block">{{ $optSimples }}</b>
                                    </p>
                                    <p class="text-sm col-md-4">Inscrição Estadual
                                        <b class="d-block">{{ $inscEstadual }}</b>
                                    </p>
                                    <p class="text-sm col-md-4">Inscrição Municipal
                                        <b class="d-block">{{ $inscMunicipal }}</b>
                                    </p>
                                </div>
                            </div>
                        </x-adminlte-callout>
                        <!-- Local da Prestação do Serviço -->
                        <x-adminlte-callout title="Local da Prestação do Serviço" title-class="text-uppercase" icon="fa-solid fa-location-dot" theme="" class="callout-nexus">
                            @php
                                if($enderecoLocSrv == 1){
                                    $dadosLocSrv = DB::table('cadastro_empresa_enderecos')->where('endereco_empresa_codigo', $empresa)->where('endereco_principal', 'S')->first();
                                    $endereco = $dadosLocSrv->endereco_logradouro.', '.$dadosLocSrv->endereco_numero;
                                    if(!empty($dadosLocSrv->endereco_complemento)){
                                        $complemento = $dadosLocSrv->endereco_complemento;
                                    }else{
                                        $complemento = '';
                                    }
                                    $cep = Helper::mascaraCEP($dadosLocSrv->endereco_cep);
                                    $bairro = $dadosLocSrv->endereco_bairro;
                                    $cidUF = $dadosLocSrv->endereco_cidade.' / '.$dadosLocSrv->endereco_uf;
                                }elseif($enderecoLocSrv == 2){
                                    $dadosLocSrv = DB::table('cadastro_cliente_enderecos')->where('endereco_cliente_codigo', $cliente)->where('endereco_seq', $enderecoCli)->first();
                                    $endereco = $dadosLocSrv->endereco_logradouro.', '.$dadosLocSrv->endereco_numero;
                                    if(!empty($dadosLocSrv->endereco_complemento)){
                                        $complemento = $dadosLocSrv->endereco_complemento;
                                    }else{
                                        $complemento = '';
                                    }
                                    $cep = Helper::mascaraCEP($dadosLocSrv->endereco_cep);
                                    $bairro = $dadosLocSrv->endereco_bairro;
                                    $cidUF = $dadosLocSrv->endereco_cidade.' / '.$dadosLocSrv->endereco_uf;
                                }else{
                                    $endereco = $outEndLocSrv['logradouro'].', '.$outEndLocSrv['numero'];
                                    if(!empty($outEndLocSrv['complemento'])){
                                        $complemento = $outEndLocSrv['complemento'];
                                    }else{
                                        $complemento = '';
                                    }
                                    $cep =  $outEndLocSrv['cep'];
                                    $bairro = $outEndLocSrv['bairro'];
                                    $cidUF = $outEndLocSrv['cidade'].' / '.$outEndLocSrv['uf'];
                                }
                            @endphp
                            <div class="text-muted">
                                <div class="row quebra-linha">
                                    <p class="text-sm col-md-3">Endereço
                                        <b class="d-block">{{ $endereco }}</b>
                                    </p>
                                    <p class="text-sm col-md-2">Complemento
                                        <b class="d-block">{{ $complemento }}</b>
                                    </p>
                                    <p class="text-sm col-md-2">CEP
                                        <b class="d-block">{{ $cep }}</b>
                                    </p>
                                    <p class="text-sm col-md-2">Bairro
                                        <b class="d-block">{{ $bairro }}</b>
                                    </p>
                                    <p class="text-sm col-md-3">Cidade / UF
                                        <b class="d-block">{{ $cidUF }}</b>
                                    </p>
                                </div>
                            </div>
                        </x-adminlte-callout>
                    </div>
                    <div class="col-md-6 mb-3">
                        <!-- Dados Do Cliente -->
                        <x-adminlte-callout title="Dados do Cliente" title-class="text-uppercase" icon="fa-solid fa-user-tie" theme="" class="callout-nexus h-100">
                            <div class="text-muted">
                                <div class="row quebra-linha">
                                    <p class="text-sm col-md-3">Tipo de Cadastro
                                        <b class="d-block">{{ $tipoCad }}</b>
                                    </p>
                                    <p class="text-sm col-md-3">Tipo de Pessoa
                                        <b class="d-block">{{ $tipoPes }}</b>
                                    </p>
                                    <p class="text-sm col-md-3">Cliente
                                        <b class="d-block">{{ $cliente_os }}</b>
                                    </p>
                                    @if($data_cli[0]->cliente_tipo_pessoa == 'J')
                                    <p class="text-sm col-md-3">CNPJ
                                        <b class="d-block">{{ $cnpj_cli }}</b>
                                    </p>
                                    @else
                                    <p class="text-sm col-md-3">CPF
                                        <b class="d-block">{{ $cpf_cli }}</b>
                                    </p>
                                    @endif
                                </div>
                                <div class="row quebra-linha">
                                    @if($data_cli[0]->cliente_tipo_pessoa == 'J')
                                    <p class="text-sm col-md-3">Inscrição Estadual
                                        <b class="d-block">{{ $inscEst_cli }}</b>
                                    </p>
                                    <p class="text-sm col-md-3">Inscrição Municipal
                                        <b class="d-block">{{ $inscMun_cli }}</b>
                                    </p>
                                    <p class="text-sm col-md-3">RG
                                        <b class="d-block">Não Informado</b>
                                    </p>
                                    <p class="text-sm col-md-3">Sexo
                                        <b class="d-block">Não Informado</b>
                                    </p>
                                    @else
                                    <p class="text-sm col-md-3">Inscrição Estadual
                                        <b class="d-block">Não Informado</b>
                                    </p>
                                    <p class="text-sm col-md-3">Inscrição Municipal
                                        <b class="d-block">Não Informado</b>
                                    </p>
                                    <p class="text-sm col-md-3">RG
                                        <b class="d-block">{{ $rg_cli }}</b>
                                    </p>
                                    <p class="text-sm col-md-3">Sexo
                                        <b class="d-block">{{ $sexo_cli }}</b>
                                    </p>
                                    @endif
                                </div>
                                <div class="row quebra-linha">
                                    <p class="text-sm col-md-3">Telefone Residencial
                                        <b class="d-block">{{ $telRes_cli }}</b>
                                    </p>
                                    <p class="text-sm col-md-3">Telefone Celular
                                        <b class="d-block">{{ $telCel_cli }}</b>
                                    </p>
                                    <p class="text-sm col-md-3">Telefone Comercial
                                        <b class="d-block">{{ $telCom_cli }}</b>
                                    </p>
                                    <p class="text-sm col-md-3">Email
                                        <b class="d-block">{{ $email_cli }}</b>
                                    </p>
                                </div>
                                @php
                                    //Busca os dados dos endereços cadastrados do cliente
                                    $dadosEndCli = DB::table('cadastro_cliente_enderecos')->where('endereco_cliente_codigo', $cliente)->where('endereco_seq', $enderecoCli)->get();

                                    if(!empty($dadosEndCli[0]->endereco_complemento)){
                                        $complemento = $dadosEndCli[0]->endereco_complemento;
                                    }else{
                                        $complemento = '';
                                    }
                                @endphp
                                <div class="row quebra-linha">
                                    <p class="text-sm col-md-3">Endereço
                                        <b class="d-block">{{ $dadosEndCli[0]->endereco_logradouro.', '.$dadosEndCli[0]->endereco_numero }}</b>
                                    </p>
                                    <p class="text-sm col-md-2">Complemento
                                        <b class="d-block">{{ $complemento }}</b>
                                    </p>
                                    <p class="text-sm col-md-2">CEP
                                        <b class="d-block">{{ Helper::mascaraCEP($dadosEndCli[0]->endereco_cep) }}</b>
                                    </p>
                                    <p class="text-sm col-md-2">Bairro
                                        <b class="d-block">{{ $dadosEndCli[0]->endereco_bairro }}</b>
                                    </p>
                                    <p class="text-sm col-md-3">Cidade / UF
                                        <b class="d-block">{{ $dadosEndCli[0]->endereco_cidade.' / '.$dadosEndCli[0]->endereco_uf }}</b>
                                    </p>
                                </div>
                            </div>
                        </x-adminlte-callout>
                    </div>
                </div>
                <!-- Dados do Serviço -->
                <x-adminlte-card title="Dados do Serviço" icon="fa-solid fa-file-invoice-dollar fa-lg" theme="" theme-mode="outline" header-class="card-outline-nexus" collapsible maximizable>
                    @php
                        $dataParSrvEmp = DB::table('parametros_srv_empresas')->where('parsrv_emp', $empresa)->get();
                        $dadosGrupoSrv = DB::table('parametros_sistema_servico_grupos')->orderBy('grupo_codigo', 'asc')->get();

                        $new_array1 =[];
                        $new_array2 =[];

                        foreach ($dadosGrupoSrv as $grupoSrv) {
                            $new_array1[] = $grupoSrv->grupo_codigo;
                            $new_array2[] = $grupoSrv->grupo_codigo.' - '.$grupoSrv->grupo_desc;
                        }
                        $array_opt = array_combine($new_array1, $new_array2);

                        if(!empty($dataParSrvEmp[0]->parsrv_cod_srv) && $dataParSrvEmp[0]->parsrv_cod_srv != 0){
                            
                            $dadosCodSrv = DB::table('parametros_sistema_servicos')->where('servico_grupo', $dataParSrvEmp[0]->parsrv_grp_srv)->orderBy('servico_codigo', 'asc')->get();

                            $new_array1 =[];
                            $new_array2 =[];

                            foreach ($dadosCodSrv as $codigoSrv) {
                                $new_array1[] = $codigoSrv->servico_codigo;
                                $new_array2[] = $codigoSrv->servico_codigo.' - '.$codigoSrv->servico_desc;
                            }
                            $array_opt2 = array_combine($new_array1, $new_array2);
                            
                            $grupo = $dataParSrvEmp[0]->parsrv_grp_srv;
                            $codigo = $dataParSrvEmp[0]->parsrv_cod_srv;
                        }else{
                            
                            $array_opt2 = null;
                            $grupo = null;
                            $codigo = null;
                        }
                    @endphp
                    <div class="row">
                        <!-- Grupo do Serviço -->
                        <x-adminlte-select name="grupoSrv" fgroup-class="col-md-6">
                            <x-slot name="label">
                                Grupo do Serviço <span style="color:red;">*</span>
                            </x-slot>
                            <x-adminlte-options :options="$array_opt" empty-option="Selecione..." selected="{{$grupo}}"/>
                        </x-adminlte-select>

                        <!-- Código do Serviço -->
                        <x-adminlte-select name="codigoSrv" fgroup-class="col-md-6">
                            <x-slot name="label">
                                Código do Serviço <span style="color:red;">*</span>
                            </x-slot>
                            <x-adminlte-options :options="$array_opt2" empty-option="Selecione..." selected="{{$codigo}}"/>
                        </x-adminlte-select>
                    </div>
                    <div class="row">
                        <!-- Descrição do serviço -->
                        <x-adminlte-textarea name="descSrv" rows=4 label-class="text-dark" placeholder="Escreva a descrição do serviço..." fgroup-class="col-md-4">
                            <x-slot name="label">
                                Descrição dos Serviços Prestados <span style="color:red;">*</span>
                            </x-slot>
                            <x-slot name="prependSlot">
                                <div class="input-group-text x-slot-nexus">
                                    <i class="fas fa-lg fa-file-alt"></i>
                                </div>
                            </x-slot>
                        </x-adminlte-textarea>
                        
                        <!-- Informações Complementares -->
                        <x-adminlte-textarea name="infoCmp" label="Informações Complementares" rows=4 label-class="text-dark" placeholder="Escreva as informações complementares..." fgroup-class="col-md-4">
                            <x-slot name="prependSlot">
                                <div class="input-group-text x-slot-nexus">
                                    <i class="fas fa-lg fa-file-alt"></i>
                                </div>
                            </x-slot>
                        </x-adminlte-textarea>
                        
                        <!-- Informações Complementares -->
                        <x-adminlte-textarea name="obsNFS" label="Observações da NFS-e" rows=4 label-class="text-dark" placeholder="Escreva as observações..." fgroup-class="col-md-4">
                            <x-slot name="prependSlot">
                                <div class="input-group-text x-slot-nexus">
                                    <i class="fas fa-lg fa-file-alt"></i>
                                </div>
                            </x-slot>
                        </x-adminlte-textarea>
                    </div>
                    <div class="row">
                        <!-- Valor da NFS -->
                        <x-adminlte-input name="vlrNFS" type="text" placeholder="0,00" fgroup-class="col-md-4">
                            <x-slot name="label">
                                Valor da Nota Fiscal <span style="color:red;">*</span>
                            </x-slot>
                            <x-slot name="prependSlot">
                                <div class="input-group-text x-slot-nexus">
                                    <i class="fa-solid fa-brazilian-real-sign"></i>
                                </div>
                            </x-slot>
                        </x-adminlte-input>
                    </div>
                    <div class="row">
                        <!-- Valor da NFS -->
                        <x-adminlte-input name="inssRet" label="Valor do INSS Retido" type="text" placeholder="0,00" fgroup-class="col-md-2">
                            <x-slot name="prependSlot">
                                <div class="input-group-text x-slot-nexus">
                                    <i class="fa-solid fa-brazilian-real-sign"></i>
                                </div>
                            </x-slot>
                        </x-adminlte-input>
                        <!-- Valor da NFS -->
                        <x-adminlte-input name="irrfRet" label="Valor do IRRF Retido" type="text" placeholder="0,00" fgroup-class="col-md-2">
                            <x-slot name="prependSlot">
                                <div class="input-group-text x-slot-nexus">
                                    <i class="fa-solid fa-brazilian-real-sign"></i>
                                </div>
                            </x-slot>
                        </x-adminlte-input>
                        <!-- Valor da NFS -->
                        <x-adminlte-input name="csllRet" label="Valor do CSLL Retido" type="text" placeholder="0,00" fgroup-class="col-md-2">
                            <x-slot name="prependSlot">
                                <div class="input-group-text x-slot-nexus">
                                    <i class="fa-solid fa-brazilian-real-sign"></i>
                                </div>
                            </x-slot>
                        </x-adminlte-input>
                        <!-- Valor da NFS -->
                        <x-adminlte-input name="pisRet" label="Valor do PIS Retido" type="text" placeholder="0,00" fgroup-class="col-md-2">
                            <x-slot name="prependSlot">
                                <div class="input-group-text x-slot-nexus">
                                    <i class="fa-solid fa-brazilian-real-sign"></i>
                                </div>
                            </x-slot>
                        </x-adminlte-input>
                        <!-- Valor da NFS -->
                        <x-adminlte-input name="cofinsRet" label="Valor do COFINS Retido" type="text" placeholder="0,00" fgroup-class="col-md-2">
                            <x-slot name="prependSlot">
                                <div class="input-group-text x-slot-nexus">
                                    <i class="fa-solid fa-brazilian-real-sign"></i>
                                </div>
                            </x-slot>
                        </x-adminlte-input>
                        <!-- Valor da NFS -->
                        <!-- por hora não vai usar
                        <x-adminlte-input name="outRet" label="Outras Retenções" type="text" placeholder="0,00" value="0.00" fgroup-class="col-md-2">
                            <x-slot name="prependSlot">
                                <div class="input-group-text x-slot-nexus">
                                    <i class="fa-solid fa-brazilian-real-sign"></i>
                                </div>
                            </x-slot>
                        </x-adminlte-input>
                        -->
                    </div>
                    <div class="row">
                        <!-- Valor da NFS -->
                        <x-adminlte-input name="vlrBase" type="text" placeholder="0,00" value="0.00" fgroup-class="col-md-4">
                            <x-slot name="label">
                                Valor da Base de Cálculo <span style="color:red;">*</span>
                            </x-slot>
                            <x-slot name="prependSlot">
                                <div class="input-group-text x-slot-nexus">
                                    <i class="fa-solid fa-brazilian-real-sign"></i>
                                </div>
                            </x-slot>
                        </x-adminlte-input>
                        <!-- Valor da NFS -->
                        <x-adminlte-input name="aliqISS" type="text" placeholder="0,00" value="{{$dataParSrvEmp[0]->parsrv_alq_iss}}" fgroup-class="col-md-4">
                            <x-slot name="label">
                                Aliquota de ISS <span style="color:red;">*</span>
                            </x-slot>
                            <x-slot name="prependSlot">
                                <div class="input-group-text x-slot-nexus">
                                    <i class="fa-solid fa-percent"></i>
                                </div>
                            </x-slot>
                        </x-adminlte-input>
                        <!-- Valor da NFS -->
                        <x-adminlte-input name="vlrImpRec" label="Valor do Imposto a Recolher" type="text" placeholder="0,00" value="0.00" fgroup-class="col-md-4">
                            <x-slot name="prependSlot">
                                <div class="input-group-text x-slot-nexus">
                                    <i class="fa-solid fa-brazilian-real-sign"></i>
                                </div>
                            </x-slot>
                        </x-adminlte-input>
                    </div>
                </x-adminlte-card>
                <x-slot name="footerSlot">
                    <div class="d-flex justify-content-between w-100">
                        <x-adminlte-button class="btn-nexus" type="submit" label="Emitir NFS-e" theme="info" icon="fa-solid fa-share-from-square"/>
                        <x-adminlte-button class="btn-nexus" type="button" onclick="window.location='{{ route('emissaoSimpNFS.inicioGet', ['empresa' => $empresa, 'cliente' => $cliente]) }}'" label="Voltar" theme="info" icon=""/>
                    </div>
                </x-slot>
            </x-adminlte-card>
            <!-- Gera campos escondidos para passar array para o controler -->
            @if($enderecoLocSrv == 3)
            <input type="hidden" name="enderecoLocSrv" value="{{ $enderecoLocSrv }}">
            <input type="hidden" name="ibgeCodMun" value="{{ $outEndLocSrv['ibgeCodMun'] }}">
            <input type="hidden" name="cep" value="{{ $outEndLocSrv['cep'] }}">
            <input type="hidden" name="logradouro" value="{{ $outEndLocSrv['logradouro'] }}">
            <input type="hidden" name="numero" value="{{ $outEndLocSrv['numero'] }}">
            <input type="hidden" name="complemento" value="{{ $outEndLocSrv['complemento'] }}">
            <input type="hidden" name="bairro" value="{{ $outEndLocSrv['bairro'] }}">
            <input type="hidden" name="cidade" value="{{ $outEndLocSrv['cidade'] }}">
            <input type="hidden" name="uf" value="{{ $outEndLocSrv['uf'] }}">
            <input type="hidden" name="pais" value="{{ $outEndLocSrv['pais'] }}">
            @else 
            <input type="hidden" name="enderecoLocSrv" value="{{ $enderecoLocSrv }}">
            <input type="hidden" name="ibgeCodMun" value="">
            <input type="hidden" name="cep" value="">
            <input type="hidden" name="logradouro" value="">
            <input type="hidden" name="numero" value="">
            <input type="hidden" name="complemento" value="">
            <input type="hidden" name="bairro" value="">
            <input type="hidden" name="cidade" value="">
            <input type="hidden" name="uf" value="">
            <input type="hidden" name="pais" value="">
            @endif
        </form>
    </div>
</div>
@stop

<!-- Chamada dos Plugins usados na app -->
@section('plugins.Sweetalert2', true)
@section('plugins.toastr', true)
@section('plugins.jqueryValidation', true)
@section('plugins.icheckBootstrap', true)
@section('plugins.Inputmask', true)

@section('css')
@stop

@section('js')
<script src="https://igorescobar.github.io/jQuery-Mask-Plugin/js/jquery.mask.min.js"></script> 

<!--
|--------------------------------------------------------------------------
| Eventos Iniciais da app
|--------------------------------------------------------------------------
-->
<script>
    $(document).ready(function() {

        //Mascaras de campos float
        $('#vlrNFS').mask('#.##0,00', {reverse: true});
        $('#inssRet').mask('#.##0,00', {reverse: true});
        $('#irrfRet').mask('#.##0,00', {reverse: true});
        $('#csllRet').mask('#.##0,00', {reverse: true});
        $('#pisRet').mask('#.##0,00', {reverse: true});
        $('#cofinsRet').mask('#.##0,00', {reverse: true});
        //$('#outRet').mask('#.##0,00', {reverse: true});
        $('#vlrBase').mask('#.##0,00', {reverse: true});
        $('#aliqISS').mask('#.##0,00', {reverse: true});
        $('#vlrImpRec').mask('#.##0,00', {reverse: true});

        // Init input mask on the target element.
        $('#cep').inputmask({
            "mask": "99999-999",
            // Specify other options...
        });

        //Bloco ao iniciar app fica escondido
        $('.bloco-endereco').hide();

        //Bloco ao iniciar app fica escondido
        $('#vlrImpRec').prop('disabled', true);
        $('#vlrBase').prop('disabled', true);

        // Busca os dados do CEP informado
        $("#cep").blur(function(){

            // Remove tudo o que não é número para fazer a pesquisa
            var cep = this.value.replace(/[^0-9]/, "");
            
            // Validação do CEP; caso o CEP não possua 8 números, então cancela
            // a consulta
            if(cep.length != 8){
                return false;
            }
            
            // A url de pesquisa consiste no endereço do webservice + o cep que
            // o usuário informou + o tipo de retorno desejado (entre "json",
            // "jsonp", "xml", "piped" ou "querty")
            var url = "https://viacep.com.br/ws/"+cep+"/json/";
            
            // Faz a pesquisa do CEP, tratando o retorno com try/catch para que
            // caso ocorra algum erro (o cep pode não existir, por exemplo) a
            // usabilidade não seja afetada, assim o usuário pode continuar//
            // preenchendo os campos normalmente
            $.getJSON(url, function(dadosRetorno){
                try{
                    //console.log(dadosRetorno);
                    // Preenche os campos de acordo com o retorno da pesquisa
                    $("#logradouro").val(dadosRetorno.logradouro);
                    $("#bairro").val(dadosRetorno.bairro);
                    $("#cidade").val(dadosRetorno.localidade);
                    $("#uf").val(dadosRetorno.uf);
                    $("#complemento").val(dadosRetorno.complemento);
                    $("#ibgeCodMun").val(dadosRetorno.ibge);
                    $("#numero").focus();
                }catch(ex){}
            });
        });
    });
</script>

<!--
|--------------------------------------------------------------------------
| Eventos onClick da app
|--------------------------------------------------------------------------
-->
<script>
    $(document).ready(function() {

        $("#radio_empresa").click(function(){
            $('.bloco-endereco').hide();

            $('#cep').val('');
            $('#logradouro').val('');
            $('#numero').val('');
            $('#complemento').val('');
            $('#bairro').val('');
            $('#cidade').val('');
            $('#uf').val('');
            $('#pais').val('');
            $('#ibgeCodMun').val('');
        });

        $("#radio_cliente").click(function(){
            $('.bloco-endereco').hide();

            $('#cep').val('');
            $('#logradouro').val('');
            $('#numero').val('');
            $('#complemento').val('');
            $('#bairro').val('');
            $('#cidade').val('');
            $('#uf').val('');
            $('#pais').val('');
            $('#ibgeCodMun').val('');
        });

        $("#radio_outro").click(function(){
            $('.bloco-endereco').show();
        });
    });
</script>

<!--
|--------------------------------------------------------------------------
| Eventos onChange da app
|--------------------------------------------------------------------------
-->
<script>
    $(document).ready(function() {

        //Evento de carregamento ajax dos dados dos códigos do serviço do grupo selecionado
        $('#grupoSrv').change(function(){

            if( $(this).val() ) {
                var id = $(this).val();

                var url = "{{ route('parametrosSrvTMO.carregaCodSrvAjax', [':id']) }}";
                url = url.replace(':id', id);

                $.ajax({
                    url: url,
                    dataType: "JSON",
                    type: 'GET',
                    data: {
                        '_token': $('meta[name=csrf-token]').attr("content"),
                        '_method': 'GET',
                        "id": id
                    },
                    success: function (data)
                    {
                        var options = '<option value="">Selecione...</option>';	

						for (var i = 0; i < data.servicos_ajax.length; i++) {

							options += '<option value="' + data.servicos_ajax[i].id + '">' + data.servicos_ajax[i].cod_servico + '</option>';
						}	
						$('#codigoSrv').html(options);
                    }
                });
            } else {
				$('#codigoSrv').html('<option value="">Selecione...</option>');
			}
        });

        //Carrega campos apos alteração no valor da NF
        $('#vlrNFS').change(function(){

            $("#vlrBase").val(this.value);

            //Calcula o imposto di ISS
            var valBase = this.value;
            valBase = valBase.replaceAll('.', '').replaceAll(',', '.');
            valBase = parseFloat(valBase);

            var aliqISS = $("#aliqISS").val();
            aliqISS = aliqISS.replaceAll('.', '').replaceAll(',', '.');
            aliqISS = parseFloat(aliqISS);

            var valImp = (valBase / 100) * aliqISS;

            //Prepara demais valores de retenção para calculo
            var inssRet = $("#inssRet").val();
            inssRet = inssRet.replaceAll('.', '').replaceAll(',', '.');
            inssRet = parseFloat(inssRet) || 0;

            var irrfRet = $("#irrfRet").val();
            irrfRet = irrfRet.replaceAll('.', '').replaceAll(',', '.');
            irrfRet = parseFloat(irrfRet) || 0;

            var csllRet = $("#csllRet").val();
            csllRet = csllRet.replaceAll('.', '').replaceAll(',', '.');
            csllRet = parseFloat(csllRet) || 0;

            var pisRet = $("#pisRet").val();
            pisRet = pisRet.replaceAll('.', '').replaceAll(',', '.');
            pisRet = parseFloat(pisRet) || 0;

            var cofinsRet = $("#cofinsRet").val();
            cofinsRet = cofinsRet.replaceAll('.', '').replaceAll(',', '.');
            cofinsRet = parseFloat(cofinsRet) || 0;

            /*var outRet = $("#outRet").val();
            outRet = outRet.replaceAll('.', '').replaceAll(',', '.');
            outRet = parseFloat(outRet) || 0;*/

            //Calcula o valor final do imposto
            /*valImp = valImp + inssRet + irrfRet + csllRet + pisRet + cofinsRet + outRet;*/
            valImp = valImp + inssRet + irrfRet + csllRet + pisRet + cofinsRet;
            valImp = new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 2,  maximumFractionDigits: 2}).format(valImp);

            $('#vlrImpRec').val(valImp);
        });

        //Carrega campos apos alteração no valor da base de calculo da NF
        $('#vlrBase').change(function(){

            //Calcula o imposto di ISS
            var valBase = this.value;
            valBase = valBase.replaceAll('.', '').replaceAll(',', '.');
            valBase = parseFloat(valBase);

            var aliqISS = $("#aliqISS").val();
            aliqISS = aliqISS.replaceAll('.', '').replaceAll(',', '.');
            aliqISS = parseFloat(aliqISS);

            var valImp = (valBase / 100) * aliqISS;

            //Prepara demais valores de retenção para calculo
            var inssRet = $("#inssRet").val();
            inssRet = inssRet.replaceAll('.', '').replaceAll(',', '.');
            inssRet = parseFloat(inssRet) || 0;

            var irrfRet = $("#irrfRet").val();
            irrfRet = irrfRet.replaceAll('.', '').replaceAll(',', '.');
            irrfRet = parseFloat(irrfRet) || 0;

            var csllRet = $("#csllRet").val();
            csllRet = csllRet.replaceAll('.', '').replaceAll(',', '.');
            csllRet = parseFloat(csllRet) || 0;

            var pisRet = $("#pisRet").val();
            pisRet = pisRet.replaceAll('.', '').replaceAll(',', '.');
            pisRet = parseFloat(pisRet) || 0;

            var cofinsRet = $("#cofinsRet").val();
            cofinsRet = cofinsRet.replaceAll('.', '').replaceAll(',', '.');
            cofinsRet = parseFloat(cofinsRet) || 0;

            /*var outRet = $("#outRet").val();
            outRet = outRet.replaceAll('.', '').replaceAll(',', '.');
            outRet = parseFloat(outRet) || 0;*/

            //Calcula o valor final do imposto
            /*valImp = valImp + inssRet + irrfRet + csllRet + pisRet + cofinsRet + outRet;*/
            valImp = valImp + inssRet + irrfRet + csllRet + pisRet + cofinsRet;
            valImp = new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 2,  maximumFractionDigits: 2}).format(valImp);

            $('#vlrImpRec').val(valImp);
        });

        //Carrega campos apos alteração no valor da aliquota de iss
        $('#aliqISS').change(function(){

            //Calcula o imposto di ISS
            var valBase = $("#vlrBase").val();
            valBase = valBase.replaceAll('.', '').replaceAll(',', '.');
            valBase = parseFloat(valBase);

            var aliqISS = $("#aliqISS").val();
            aliqISS = aliqISS.replaceAll('.', '').replaceAll(',', '.');
            aliqISS = parseFloat(aliqISS);

            var valImp = (valBase / 100) * aliqISS;

            //Prepara demais valores de retenção para calculo
            var inssRet = $("#inssRet").val();
            inssRet = inssRet.replaceAll('.', '').replaceAll(',', '.');
            inssRet = parseFloat(inssRet) || 0;

            var irrfRet = $("#irrfRet").val();
            irrfRet = irrfRet.replaceAll('.', '').replaceAll(',', '.');
            irrfRet = parseFloat(irrfRet) || 0;

            var csllRet = $("#csllRet").val();
            csllRet = csllRet.replaceAll('.', '').replaceAll(',', '.');
            csllRet = parseFloat(csllRet) || 0;

            var pisRet = $("#pisRet").val();
            pisRet = pisRet.replaceAll('.', '').replaceAll(',', '.');
            pisRet = parseFloat(pisRet) || 0;

            var cofinsRet = $("#cofinsRet").val();
            cofinsRet = cofinsRet.replaceAll('.', '').replaceAll(',', '.');
            cofinsRet = parseFloat(cofinsRet) || 0;

            /*var outRet = $("#outRet").val();
            outRet = outRet.replaceAll('.', '').replaceAll(',', '.');
            outRet = parseFloat(outRet) || 0;*/

            //Calcula o valor final do imposto
            /*valImp = valImp + inssRet + irrfRet + csllRet + pisRet + cofinsRet + outRet;*/
            valImp = valImp + inssRet + irrfRet + csllRet + pisRet + cofinsRet;
            valImp = new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 2,  maximumFractionDigits: 2}).format(valImp);

            $('#vlrImpRec').val(valImp);
        });

        //Carrega campos apos alteração no valor do inss retido
        $('#inssRet').change(function(){

            //Calcula o imposto di ISS
            var valBase = $("#vlrBase").val();
            valBase = valBase.replaceAll('.', '').replaceAll(',', '.');
            valBase = parseFloat(valBase);

            var aliqISS = $("#aliqISS").val();
            aliqISS = aliqISS.replaceAll('.', '').replaceAll(',', '.');
            aliqISS = parseFloat(aliqISS);

            var valImp = (valBase / 100) * aliqISS;

            //Prepara demais valores de retenção para calculo
            var inssRet = $("#inssRet").val();
            inssRet = inssRet.replaceAll('.', '').replaceAll(',', '.');
            inssRet = parseFloat(inssRet) || 0;

            var irrfRet = $("#irrfRet").val();
            irrfRet = irrfRet.replaceAll('.', '').replaceAll(',', '.');
            irrfRet = parseFloat(irrfRet) || 0;

            var csllRet = $("#csllRet").val();
            csllRet = csllRet.replaceAll('.', '').replaceAll(',', '.');
            csllRet = parseFloat(csllRet) || 0;

            var pisRet = $("#pisRet").val();
            pisRet = pisRet.replaceAll('.', '').replaceAll(',', '.');
            pisRet = parseFloat(pisRet) || 0;

            var cofinsRet = $("#cofinsRet").val();
            cofinsRet = cofinsRet.replaceAll('.', '').replaceAll(',', '.');
            cofinsRet = parseFloat(cofinsRet) || 0;

            /*var outRet = $("#outRet").val();
            outRet = outRet.replaceAll('.', '').replaceAll(',', '.');
            outRet = parseFloat(outRet) || 0;*/

            //Calcula o valor final do imposto
            /*valImp = valImp + inssRet + irrfRet + csllRet + pisRet + cofinsRet + outRet;*/
            valImp = valImp + inssRet + irrfRet + csllRet + pisRet + cofinsRet;
            valImp = new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 2,  maximumFractionDigits: 2}).format(valImp);

            $('#vlrImpRec').val(valImp);
        });

        //Carrega campos apos alteração no valor do irrf retido
        $('#irrfRet').change(function(){

            //Calcula o imposto di ISS
            var valBase = $("#vlrBase").val();
            valBase = valBase.replaceAll('.', '').replaceAll(',', '.');
            valBase = parseFloat(valBase);

            var aliqISS = $("#aliqISS").val();
            aliqISS = aliqISS.replaceAll('.', '').replaceAll(',', '.');
            aliqISS = parseFloat(aliqISS);

            var valImp = (valBase / 100) * aliqISS;

            //Prepara demais valores de retenção para calculo
            var inssRet = $("#inssRet").val();
            inssRet = inssRet.replaceAll('.', '').replaceAll(',', '.');
            inssRet = parseFloat(inssRet) || 0;

            var irrfRet = $("#irrfRet").val();
            irrfRet = irrfRet.replaceAll('.', '').replaceAll(',', '.');
            irrfRet = parseFloat(irrfRet) || 0;

            var csllRet = $("#csllRet").val();
            csllRet = csllRet.replaceAll('.', '').replaceAll(',', '.');
            csllRet = parseFloat(csllRet) || 0;

            var pisRet = $("#pisRet").val();
            pisRet = pisRet.replaceAll('.', '').replaceAll(',', '.');
            pisRet = parseFloat(pisRet) || 0;

            var cofinsRet = $("#cofinsRet").val();
            cofinsRet = cofinsRet.replaceAll('.', '').replaceAll(',', '.');
            cofinsRet = parseFloat(cofinsRet) || 0;

            /*var outRet = $("#outRet").val();
            outRet = outRet.replaceAll('.', '').replaceAll(',', '.');
            outRet = parseFloat(outRet) || 0;*/

            //Calcula o valor final do imposto
            /*valImp = valImp + inssRet + irrfRet + csllRet + pisRet + cofinsRet + outRet;*/
            valImp = valImp + inssRet + irrfRet + csllRet + pisRet + cofinsRet;
            valImp = new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 2,  maximumFractionDigits: 2}).format(valImp);

            $('#vlrImpRec').val(valImp);
        });

        //Carrega campos apos alteração no valor do csll retido
        $('#csllRet').change(function(){

            //Calcula o imposto di ISS
            var valBase = $("#vlrBase").val();
            valBase = valBase.replaceAll('.', '').replaceAll(',', '.');
            valBase = parseFloat(valBase);

            var aliqISS = $("#aliqISS").val();
            aliqISS = aliqISS.replaceAll('.', '').replaceAll(',', '.');
            aliqISS = parseFloat(aliqISS);

            var valImp = (valBase / 100) * aliqISS;

            //Prepara demais valores de retenção para calculo
            var inssRet = $("#inssRet").val();
            inssRet = inssRet.replaceAll('.', '').replaceAll(',', '.');
            inssRet = parseFloat(inssRet) || 0;

            var irrfRet = $("#irrfRet").val();
            irrfRet = irrfRet.replaceAll('.', '').replaceAll(',', '.');
            irrfRet = parseFloat(irrfRet) || 0;

            var csllRet = $("#csllRet").val();
            csllRet = csllRet.replaceAll('.', '').replaceAll(',', '.');
            csllRet = parseFloat(csllRet) || 0;

            var pisRet = $("#pisRet").val();
            pisRet = pisRet.replaceAll('.', '').replaceAll(',', '.');
            pisRet = parseFloat(pisRet) || 0;

            var cofinsRet = $("#cofinsRet").val();
            cofinsRet = cofinsRet.replaceAll('.', '').replaceAll(',', '.');
            cofinsRet = parseFloat(cofinsRet) || 0;

            /*var outRet = $("#outRet").val();
            outRet = outRet.replaceAll('.', '').replaceAll(',', '.');
            outRet = parseFloat(outRet) || 0;*/

            //Calcula o valor final do imposto
            /*valImp = valImp + inssRet + irrfRet + csllRet + pisRet + cofinsRet + outRet;*/
            valImp = valImp + inssRet + irrfRet + csllRet + pisRet + cofinsRet;
            valImp = new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 2,  maximumFractionDigits: 2}).format(valImp);

            $('#vlrImpRec').val(valImp);
        });

        //Carrega campos apos alteração no valor do pis retido
        $('#pisRet').change(function(){

            //Calcula o imposto di ISS
            var valBase = $("#vlrBase").val();
            valBase = valBase.replaceAll('.', '').replaceAll(',', '.');
            valBase = parseFloat(valBase);

            var aliqISS = $("#aliqISS").val();
            aliqISS = aliqISS.replaceAll('.', '').replaceAll(',', '.');
            aliqISS = parseFloat(aliqISS);

            var valImp = (valBase / 100) * aliqISS;

            //Prepara demais valores de retenção para calculo
            var inssRet = $("#inssRet").val();
            inssRet = inssRet.replaceAll('.', '').replaceAll(',', '.');
            inssRet = parseFloat(inssRet) || 0;

            var irrfRet = $("#irrfRet").val();
            irrfRet = irrfRet.replaceAll('.', '').replaceAll(',', '.');
            irrfRet = parseFloat(irrfRet) || 0;

            var csllRet = $("#csllRet").val();
            csllRet = csllRet.replaceAll('.', '').replaceAll(',', '.');
            csllRet = parseFloat(csllRet) || 0;

            var pisRet = $("#pisRet").val();
            pisRet = pisRet.replaceAll('.', '').replaceAll(',', '.');
            pisRet = parseFloat(pisRet) || 0;

            var cofinsRet = $("#cofinsRet").val();
            cofinsRet = cofinsRet.replaceAll('.', '').replaceAll(',', '.');
            cofinsRet = parseFloat(cofinsRet) || 0;

            /*var outRet = $("#outRet").val();
            outRet = outRet.replaceAll('.', '').replaceAll(',', '.');
            outRet = parseFloat(outRet) || 0;*/

            //Calcula o valor final do imposto
            /*valImp = valImp + inssRet + irrfRet + csllRet + pisRet + cofinsRet + outRet;*/
            valImp = valImp + inssRet + irrfRet + csllRet + pisRet + cofinsRet;
            valImp = new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 2,  maximumFractionDigits: 2}).format(valImp);

            $('#vlrImpRec').val(valImp);
        });

        //Carrega campos apos alteração no valor do cofins retido
        $('#cofinsRet').change(function(){

            //Calcula o imposto di ISS
            var valBase = $("#vlrBase").val();
            valBase = valBase.replaceAll('.', '').replaceAll(',', '.');
            valBase = parseFloat(valBase);

            var aliqISS = $("#aliqISS").val();
            aliqISS = aliqISS.replaceAll('.', '').replaceAll(',', '.');
            aliqISS = parseFloat(aliqISS);

            var valImp = (valBase / 100) * aliqISS;

            //Prepara demais valores de retenção para calculo
            var inssRet = $("#inssRet").val();
            inssRet = inssRet.replaceAll('.', '').replaceAll(',', '.');
            inssRet = parseFloat(inssRet) || 0;

            var irrfRet = $("#irrfRet").val();
            irrfRet = irrfRet.replaceAll('.', '').replaceAll(',', '.');
            irrfRet = parseFloat(irrfRet) || 0;

            var csllRet = $("#csllRet").val();
            csllRet = csllRet.replaceAll('.', '').replaceAll(',', '.');
            csllRet = parseFloat(csllRet) || 0;

            var pisRet = $("#pisRet").val();
            pisRet = pisRet.replaceAll('.', '').replaceAll(',', '.');
            pisRet = parseFloat(pisRet) || 0;

            var cofinsRet = $("#cofinsRet").val();
            cofinsRet = cofinsRet.replaceAll('.', '').replaceAll(',', '.');
            cofinsRet = parseFloat(cofinsRet) || 0;

            /*var outRet = $("#outRet").val();
            outRet = outRet.replaceAll('.', '').replaceAll(',', '.');
            outRet = parseFloat(outRet) || 0;*/

            //Calcula o valor final do imposto
            /*valImp = valImp + inssRet + irrfRet + csllRet + pisRet + cofinsRet + outRet;*/
            valImp = valImp + inssRet + irrfRet + csllRet + pisRet + cofinsRet;
            valImp = new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 2,  maximumFractionDigits: 2}).format(valImp);

            $('#vlrImpRec').val(valImp);
        });
    });
</script>

<script>
$(function () {

    jQuery.validator.addMethod("maxpercent", function(value, element) {
        return this.optional(element) || /^(\d{1,2}|\d{1,2}\,\d{1,2}|100\,[0]{1,2}|100)$/i.test(value);
    }, "Porcentagem máxima de 100,00 %");

    // Adiciona método de validação para verificar se o valor é maior que 0
    jQuery.validator.addMethod("greaterThanZero", function(value, element) {
        // Remove pontos e substitui vírgulas por pontos
        var val = value.replaceAll('.', '').replaceAll(',', '.');
        return parseFloat(val) > 0;
    }, "O valor deve ser maior que 0,00");

    $('#quickForm').validate({
        rules: {
            grupoSrv: {
                required: true
            },
            codigoSrv: {
                required: true
            },
            descSrv: {
                required: true,
                maxlength: 255
            },
            vlrNFS: {
                required: true,
                maxlength: 20,
                greaterThanZero: true
            },
            vlrBase: {
                required: true,
                maxlength: 20,
                greaterThanZero: true
            },
            aliqISS: {
                required: true,
                maxpercent: true
            },
            infoCmp: {
                maxlength: 255
            },
            cep: {
                required: {
                    depends: function(element) {
                        return $("input[name='enderecoLocSrv']:checked").val() == "3";
                    }
                },
                minlength: 9,
                maxlength: 9
            },
            logradouro: {
                required: {
                    depends: function(element) {
                        return $("input[name='enderecoLocSrv']:checked").val() == "3";
                    }
                },
                maxlength: 100
            },
            numero: {
                required: {
                    depends: function(element) {
                        return $("input[name='enderecoLocSrv']:checked").val() == "3";
                    }
                },
                maxlength: 5
            },
            complemento: {
                maxlength: 60
            },
            bairro: {
                required: {
                    depends: function(element) {
                        return $("input[name='enderecoLocSrv']:checked").val() == "3";
                    }
                },
                maxlength: 60
            },
            cidade: {
                required: {
                    depends: function(element) {
                        return $("input[name='enderecoLocSrv']:checked").val() == "3";
                    }
                },
                maxlength: 80
            },
            uf: {
                required: {
                    depends: function(element) {
                        return $("input[name='enderecoLocSrv']:checked").val() == "3";
                    }
                },
            },
            pais: {
                required: {
                    depends: function(element) {
                        return $("input[name='enderecoLocSrv']:checked").val() == "3";
                    }
                },
                maxlength: 40
            },
            obsNFS: {
                maxlength: 255
            },
        },
        messages: {
            grupoSrv: {
                required: "Por Favor informe o Grupo do Serviço"
            },
            codigoSrv: {
                required: "Por Favor informe o Código do Serviço"
            },
            descSrv: {
                required: "Por Favor informe a Descrição do Serviço",
                maxlength: "Limite máximo da Descrição do Serviço é 255 Caracteres"
            },
            vlrNFS: {
                required: "Por Favor informe o Valor da NFS-e",
                maxlength: "Limite máximo do Valor da NFS-e é de 15 digitos"
            },
            vlrBase: {
                required: "Por Favor informe o Valor da Base Cálculo",
                maxlength: "Limite máximo do Valor da Base Cálculo é de 15 digitos"
            },
            aliqISS: {
                required: "Por Favor informe a Aliquota de ISS"
            },
            infoCmp: {
                maxlength: "Limite máximo das Informações Complementares é 255 Caracteres"
            },
            cep: {
                required: "Por Favor informe o CEP do Local da Prestação do Serviço",
                minlength: "O CEP deve ter 8 dígitos",
                maxlength: "O CEP deve ter 8 dígitos"
            },
            logradouro: {
                required: "Por Favor informe o Logradouro do Local da Prestação do Serviço",
                maxlength: "Limite máximo do Logradouro é de 100 caracteres"
            }, 
            numero: {
                required: "Por Favor informe o Número do Local da Prestação do Serviço",
                maxlength: "Limite máximo do numero é de 5 caracteres"
            }, 
            complemento: {
                maxlength: "Limite máximo do Complemento é de 60 caracteres"
            },        
            bairro: {
                required: "Por Favor informe o Bairro do Local da Prestação do Serviço",
                maxlength: "Limite máximo do Bairro é de 60 caracteres"
            }, 
            cidade: {
                required: "Por Favor informe a Cidade do Local da Prestação do Serviço",
                maxlength: "Limite máximo do Bairro é de 80 caracteres"
            },
            uf: {
                required: "Por Favor informe a UF do Local da Prestação do Serviço",
            },
            pais: {
                required: "Por Favor informe o País do Local da Prestação do Serviço",
                maxlength: "Limite máximo do País é de 40 caracteres"
            },
            obsNFS: {
                maxlength: "Limite máximo das Observações é 255 Caracteres"
            },      
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        },
        // Ao submeter o formulário, reativar os campos desativados
        submitHandler: function (form) {
            // Ativar campos desativados antes de enviar
            $(':disabled').each(function () {
                $(this).removeAttr('disabled');
            });
            form.submit();
        }
    });
});
</script>

<script>
    @if(Session::has('success'))
        const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 2500,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });
        Toast.fire({
            icon: "success",
            title: "{{ session('success') }}"
        });
    @endif

    @if(Session::has('error'))
        Swal.fire({
        confirmButtonColor: "#007bff",
        title: "Erro!!!",
        text: "{{ session('error') }}",
        icon: "error"
    });
    @endif
</script>
@stop
