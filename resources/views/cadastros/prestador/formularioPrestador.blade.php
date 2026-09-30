@extends('adminlte::page')

@section('title', 'Cadastro de Prestadores')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h4 style="margin-bottom: 0px !important;">Cadastros</h4>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active">
                <a href="{{route('cadastroPrestador.index')}}">Prestadores</a>
            </li>
            @if($acao == 'N')
                <li class="breadcrumb-item active">Cadastro de Prestadores</li>
            @else
                @if($tipo != 'H')
                <li class="breadcrumb-item active">
                    <a href="{{route('cadastroPrestador.show', ['cadastroPrestador' => $tipo])}}">Prestadores Cadastrados</a>
                </li>
                @endif
                <li class="breadcrumb-item active">Manutenção de Prestadores</li>
            @endif
        </ol>
    </div>
</div>
@stop

@section('content')

@if($acao == 'N')
<div class="d-flex justify-content-center">
    <div class="col-md-8">
        <form method="post" action="{{route('cadastroPrestador.store')}}" id="formulario-novo" novalidate="novalidate">
            @csrf 
            <x-adminlte-card title="Cadastro de Novo Prestador" theme="" theme-mode="" header-class="card-nexus" collapsible maximizable>

                <div class="row">
                    @php
                        $diaFunEmp = '';
                        //Variavel usada na edição do prestador nos eventos ini da app
                        $usuarioSis = '';

                        $array_opt = HelperArraySelect::arrayEmpresas(1,1);
                    @endphp
                    <!-- Empresa -->
                    <x-adminlte-select name="empresaPrestador" fgroup-class="col-md-4">
                        <x-slot name="label">
                            Empresa <span style="color:red;">*</span>
                        </x-slot>
                        <x-adminlte-options :options="$array_opt" empty-option="Selecione..."/>
                    </x-adminlte-select>

                    <!-- Código -->
                    <x-adminlte-input name="nomePrestador" type="text" placeholder="Nome Completo" value="" fgroup-class="col-md-4">
                        <x-slot name="label">
                            Nome do Prestador <span style="color:red;">*</span>
                        </x-slot>
                        <x-slot name="prependSlot">
                            <div class="input-group-text x-slot-nexus">
                                <i class="fa-solid fa-id-badge fa-lg"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>
                    
                    <!-- Descrição -->
                    <x-adminlte-input name="cpfPrestador" type="text" value="" fgroup-class="col-md-4">
                        <x-slot name="label">
                            CPF <span style="color:red;">*</span>
                        </x-slot>
                        <x-slot name="prependSlot">
                            <div class="input-group-text x-slot-nexus">
                                <i class="fa-regular fa-id-card"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>
                </div>

                <div class="row">
                    @php 
                        $array_opt_are = HelperArraySelect::arrayAreaSetCadastrado(1,1);

                        $array_opt_set = null;
                    @endphp
                    <!-- area -->
                    <x-adminlte-select name="areaPrestador" fgroup-class="col-md-6">
                        <x-slot name="label">
                            Área <span style="color:red;">*</span>
                        </x-slot>
                        <x-adminlte-options :options="$array_opt_are" empty-option="Selecione..."/>
                    </x-adminlte-select>
                    
                    <!-- Setor -->
                    <x-adminlte-select name="setPrestador" fgroup-class="col-md-6">
                        <x-slot name="label">
                            Setor <span style="color:red;">*</span>
                        </x-slot>
                        <x-adminlte-options :options="$array_opt_set" empty-option="Selecione..."/>
                    </x-adminlte-select>
                </div>

                <div class="row">
                    @php 
                        $array_opt_tur = null;
                    @endphp
                    <!-- turno -->
                    <x-adminlte-select name="turPrestador" fgroup-class="col-md-6">
                        <x-slot name="label">
                            Turno de Serviço <span style="color:red;">*</span>
                        </x-slot>
                        <x-adminlte-options :options="$array_opt_tur" empty-option="Selecione..."/>
                    </x-adminlte-select>
                </div>

                <div class="row bloco-semana">

                    @php 
                        $config = Helper::dtRangeHoraPtBR();
                    @endphp
                    <x-adminlte-select name="usaInt" fgroup-class="col-md-4">
                        <x-slot name="label">
                            Intervalo Expediente <span style="color:red;">*</span>
                        </x-slot>
                        <x-adminlte-options :options="['S' => 'Sim', 'N' => 'Não']" selected="N"/>
                    </x-adminlte-select>

                    <x-adminlte-date-range name="horaIniInt" :config="$config" placeholder="Formato Hora:Minuto" fgroup-class="col-md-4">
                        <x-slot name="label">
                            Hora Ini. Intervalo <span style="color:red;">*</span>
                        </x-slot>
                        <x-slot name="appendSlot">
                            <div class="input-group-text x-slot-nexus">
                                <i class="far fa-lg fa-clock"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-date-range>

                    <x-adminlte-date-range name="horaFinInt" :config="$config" placeholder="Formato Hora:Minuto" fgroup-class="col-md-4">
                        <x-slot name="label">
                            Hora Fin. Intervalo <span style="color:red;">*</span>
                        </x-slot>
                        <x-slot name="appendSlot">
                            <div class="input-group-text x-slot-nexus">
                                <i class="far fa-lg fa-clock"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-date-range>
                </div>

                <div class="row bloco-sabado">
                    <!-- Utiliza intervalo de trabalho no sábado -->
                    <x-adminlte-select name="usaIntSab" fgroup-class="col-md-4">
                        <x-slot name="label">
                            Intervalo de Sábado <span style="color:red;">*</span>
                        </x-slot>
                        <x-adminlte-options :options="['S' => 'Sim', 'N' => 'Não']" selected="N"/>
                    </x-adminlte-select>
                    
                    <x-adminlte-date-range name="horaIniIntSab" :config="$config" placeholder="Formato Hora:Minuto" fgroup-class="col-md-4">
                        <x-slot name="label">
                            Hora Ini. Intervalo Sábado <span style="color:red;">*</span>
                        </x-slot>
                        <x-slot name="appendSlot">
                            <div class="input-group-text x-slot-nexus">
                                <i class="far fa-lg fa-clock"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-date-range>
                    
                    <x-adminlte-date-range name="horaFinIntSab" :config="$config" placeholder="Formato Hora:Minuto" fgroup-class="col-md-4">
                        <x-slot name="label">
                            Hora Fin. Intervalo Sábado <span style="color:red;">*</span>
                        </x-slot>
                        <x-slot name="appendSlot">
                            <div class="input-group-text x-slot-nexus">
                                <i class="far fa-lg fa-clock"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-date-range>
                </div>

                <div class="row bloco-domingo">

                    <!-- Utiliza intervalo de trabalho no sábado -->
                    <x-adminlte-select name="usaIntDom" fgroup-class="col-md-4">
                        <x-slot name="label">
                            Intervalo de Domingo <span style="color:red;">*</span>
                        </x-slot>
                        <x-adminlte-options :options="['S' => 'Sim', 'N' => 'Não']" selected="N"/>
                    </x-adminlte-select>
                    
                    <x-adminlte-date-range name="horaIniIntDom" :config="$config" placeholder="Formato Hora:Minuto" fgroup-class="col-md-4">
                        <x-slot name="label">
                            Hora Ini. Intervalo Domingo <span style="color:red;">*</span>
                        </x-slot>
                        <x-slot name="appendSlot">
                            <div class="input-group-text x-slot-nexus">
                                <i class="far fa-lg fa-clock"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-date-range>
                            
                    
                    <x-adminlte-date-range name="horaFinIntDom" :config="$config" placeholder="Formato Hora:Minuto" fgroup-class="col-md-4">
                        <x-slot name="label">
                            Hora Fin. Intervalo Domingo <span style="color:red;">*</span>
                        </x-slot>
                        <x-slot name="appendSlot">
                            <div class="input-group-text x-slot-nexus">
                                <i class="far fa-lg fa-clock"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-date-range>
                </div>

                <!-- /.card -->
                <x-slot name="footerSlot">
                    <div class="d-flex justify-content-between w-100">
                        <x-adminlte-button class="btn-nexus vbtn_salvar" type="submit" label="Salvar" theme="" icon="fa-solid fa-share-from-square"/>
                        <x-adminlte-button class="btn-nexus" type="button" onclick="window.location='{{ route('cadastroPrestador.index') }}'" label="Voltar" theme="" icon=""/>
                    </div>
                </x-slot>
            </x-adminlte-card>
        </form>
    </div>
</div>
@else
<div class="col-12 col-sm-12">
    <div class="card card-tabs">
        <div class="card-header card-nexus p-0 pt-1">
            <ul class="nav nav-tabs" id="custom-tabs-two-tab" role="tablist">
                <li class="pt-2 px-3"><h3 class="card-title">Manutenção do Prestador</h3></li>
                <li class="nav-item">
                    <a class="nav-link active" id="custom-tabs-two-dados-pessoais-tab" data-toggle="pill" href="#custom-tabs-two-dados-pessoais" role="tab" aria-controls="custom-tabs-two-dados-pessoais" aria-selected="true">Dados Pessoais</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-two-servico-tab" data-toggle="pill" href="#custom-tabs-two-servico" role="tab" aria-controls="custom-tabs-two-servico" aria-selected="false">Serviço</a>
                 </li>
                <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-two-contato-tab" data-toggle="pill" href="#custom-tabs-two-contato" role="tab" aria-controls="custom-tabs-two-contato" aria-selected="false">Contato</a>
                 </li>
                <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-two-endereco-tab" data-toggle="pill" href="#custom-tabs-two-endereco" role="tab" aria-controls="custom-tabs-two-endereco" aria-selected="false">Endereço</a>
                </li>
                <div class="card-tools ml-auto">          
                    <button type="button" class="btn btn-tool" data-card-widget="maximize">
                        <i class="fas fa-lg fa-expand"></i>     
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-lg fa-minus"></i>    
                    </button>
                </div>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="custom-tabs-two-tabContent">

                <!-- Aba Dados Pessoais -->
                <div class="tab-pane fade show active" id="custom-tabs-two-dados-pessoais" role="tabpanel" aria-labelledby="custom-tabs-two-dados-pessoais-tab">
                    <form method="post" action="{{route('cadastroPrestador.update', ['cadastroPrestador' => $dadosPrestador, 'atualiza' => 'dados', 'tipo' => $tipo])}}" id="formulario-dados" novalidate="novalidate">
                    @csrf 
                    @method('put')

                        <div class="post">
                            <h5 class="text-secondary font-weight-bold">Dados do Pessoais</h5>
                        </div>

                        <div class="row">
                            @php
                                $array_opt = HelperArraySelect::arrayEmpresas(1,1);
                            @endphp
                            <!-- Empresa -->
                            <x-adminlte-select name="empresaPrestador" fgroup-class="col-md-4">
                                <x-slot name="label">
                                    Empresa <span style="color:red;">*</span>
                                </x-slot>
                                <x-adminlte-options :options="$array_opt" empty-option="Selecione..." selected="{{$dadosPrestador->prestador_empresa}}"/>
                            </x-adminlte-select>

                            <!-- Nome -->
                            <x-adminlte-input name="nomePrestador" type="text" placeholder="Nome Completo" fgroup-class="col-md-6" value="{{$dadosPrestador->prestador_nome }}">
                                <x-slot name="label">
                                    Nome <span style="color:red;">*</span>
                                </x-slot>
                                <x-slot name="prependSlot">
                                    <div class="input-group-text x-slot-nexus">
                                        <i class="fa-solid fa-id-badge fa-lg"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-input>

                            <!-- CPF / CNPJ -->
                            <x-adminlte-input name="cpfPrestador" type="text" fgroup-class="col-md-2" value="{{$dadosPrestador->prestador_cpf }}">
                                <x-slot name="label">
                                    CPF <span style="color:red;">*</span>
                                </x-slot>
                                <x-slot name="prependSlot">
                                    <div class="input-group-text x-slot-nexus">
                                        <i class="fa-regular fa-id-card"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-input>
                        </div>

                        <div class="row">
                            <!-- RG -->
                            <x-adminlte-input name="rgPrestador" type="text" label="RG" fgroup-class="col-md-4" value="{{$dadosPrestador->prestador_rg }}">
                                <x-slot name="prependSlot">
                                    <div class="input-group-text x-slot-nexus">
                                        <i class="fa-solid fa-id-card"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-input>

                            @php
                                $config = Helper::dtRangeDataPtBR();

                                if(!empty($dadosPrestador->prestador_data_nascimento)){
                                    $data_nascimento = date('d/m/Y', strtotime($dadosPrestador->prestador_data_nascimento));
                                }else{
                                    $data_nascimento = '';
                                }
                            @endphp
                            <!-- Data de Nascimento -->
                            <x-adminlte-date-range name="dataNascimento" label="Data de Nascimento" :config="$config" placeholder="Formato dia/mês/ano" fgroup-class="col-md-4">
                                <x-slot name="prependSlot">
                                <div class="input-group-text x-slot-nexus">
                                        <i class="far fa-lg fa-calendar-alt"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-date-range>
                            @push('js')<script>$(() => $("#dataNascimento").val('{{ $data_nascimento }}'))</script>@endpush

                            <!-- Sexo -->
                            <x-adminlte-select name="sexoPrestador" label="Sexo" fgroup-class="col-md-4">
                                <x-adminlte-options :options="['M' => 'Masculino', 'F' => 'Feminino']" empty-option="Selecione..." selected="{{$dadosPrestador->prestador_sexo }}" />
                            </x-adminlte-select>
                        </div>

                        <div class="post">
                            <h5 class="text-secondary font-weight-bold">Detalhes do Contrato de Serviço</h5>
                        </div>

                        <div class="row">
                            @php
                                $config = Helper::dtRangeDataPtBR();

                                if(!empty($dadosPrestador->prestador_data_admissao)){
                                    $data_admissao = date('d/m/Y', strtotime($dadosPrestador->prestador_data_admissao));
                                }else{
                                    $data_admissao = '';
                                }

                                if(!empty($dadosPrestador->prestador_data_demissao)){
                                    $data_demissao = date('d/m/Y', strtotime($dadosPrestador->prestador_data_demissao));
                                }else{
                                    $data_demissao = '';
                                }
                            @endphp

                            <!-- Status -->
                            <x-adminlte-select name="statusPrestador" fgroup-class="col-md-4">
                                <x-slot name="label">
                                    Situação <span style="color:red;">*</span>
                                </x-slot>
                                <x-adminlte-options :options="['A' => 'Ativo', 'D' => 'Demitido']" selected="{{$dadosPrestador->prestador_status}}" />
                            </x-adminlte-select>

                            <!-- Data de Admissão -->
                            <x-adminlte-date-range name="dataAdmissao" :config="$config" placeholder="Formato dia/mês/ano" fgroup-class="col-md-4">
                                <x-slot name="label">
                                    Data de Admissão <span style="color:red;">*</span>
                                </x-slot>
                                <x-slot name="prependSlot">
                                <div class="input-group-text x-slot-nexus">
                                        <i class="far fa-lg fa-calendar-alt"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-date-range>
                            @push('js')<script>$(() => $("#dataAdmissao").val('{{ $data_admissao }}'))</script>@endpush
                            
                            <!-- Data de Demissao -->
                            <x-adminlte-date-range name="dataDemissao" label="Data de Demissão" :config="$config" placeholder="Formato dia/mês/ano" fgroup-class="col-md-4">
                                <x-slot name="prependSlot">
                                <div class="input-group-text x-slot-nexus">
                                        <i class="far fa-lg fa-calendar-alt"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-date-range>
                            @push('js')<script>$(() => $("#dataDemissao").val('{{ $data_demissao }}'))</script>@endpush
                        </div>
                        
                        <div class="post">
                            <h5 class="text-secondary font-weight-bold">Informações do Sistema</h5>
                        </div>

                        <div class="row">
                            @php 
                                if($dadosPrestador->prestador_acesso_sis == 'S'){
                                    $array_opt_usu = HelperArraySelect::arrayUsuarios(1,1);
                                    $usuario = $dadosPrestador->prestador_usuario_cod;
                                }else{
                                    $array_opt_usu = null;
                                    $usuario = null;
                                }

                                //Variavel usada na edição do prestador nos eventos ini da app
                                $usuarioSis = $dadosPrestador->prestador_acesso_sis;
                            @endphp

                            <!-- Prestador acessa o sistema -->
                            <x-adminlte-select name="prestadorUsuSis" fgroup-class="col-md-6">
                                <x-slot name="label">
                                    Usuário do Sistema <span style="color:red;">*</span>
                                </x-slot>
                                <x-adminlte-options :options="['S' => 'Sim', 'N' => 'Não']" empty-option="Selecione..." selected="{{$dadosPrestador->prestador_acesso_sis}}" />
                            </x-adminlte-select>
                            
                            <!-- Codigo de Usuario -->
                            <x-adminlte-select name="codUsuPrestador" fgroup-class="col-md-6">
                                <x-slot name="label">
                                    Código de Usuário do Sistema <span style="color:red;">*</span>
                                </x-slot>
                                <x-adminlte-options :options="$array_opt_usu" empty-option="Selecione..." selected="{{$usuario}}"/>
                                <x-slot name="prependSlot">
                                    <div class="input-group-text x-slot-nexus">
                                        <i class="fa-solid fa-passport fa-lg"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-select>
                        </div>

                        <div class="d-flex justify-content-center">
                            <x-adminlte-button class="btn-nexus btn_salvar" type="submit" label="Salvar" theme="" icon="fa-solid fa-share-from-square"/>
                        </div>
                    </form>
                </div>

                <!-- Aba dos dados do serviço do prestador -->
                <div class="tab-pane fade" id="custom-tabs-two-servico" role="tabpanel" aria-labelledby="custom-tabs-two-servico-tab">
                    <form method="post" action="{{route('cadastroPrestador.update', ['cadastroPrestador' => $dadosPrestador, 'atualiza' => 'servico', 'tipo' => $tipo])}}" id="formulario-servico" novalidate="novalidate">
                    @csrf 
                    @method('put')

                        <div class="row">
                            @php 
                                $array_opt_are = HelperArraySelect::arrayAreaSetCadastrado(1,1);
                                $array_opt_set = HelperArraySelect::arraySetorPorEmpArea($dadosPrestador->prestador_empresa,$dadosPrestador->prestador_are,1,1);

                                $config = Helper::dtRangeHoraPtBR();
                            @endphp
                            <!-- area -->
                            <x-adminlte-select name="areaPrestador" fgroup-class="col-md-6">
                                <x-slot name="label">
                                    Área <span style="color:red;">*</span>
                                </x-slot>
                                <x-adminlte-options :options="$array_opt_are" empty-option="Selecione..." selected="{{$dadosPrestador->prestador_are}}"/>
                            </x-adminlte-select>
                            <!-- Setor -->
                            <x-adminlte-select name="setPrestador" fgroup-class="col-md-6">
                                <x-slot name="label">
                                    Setor <span style="color:red;">*</span>
                                </x-slot>
                                <x-adminlte-options :options="$array_opt_set" empty-option="Selecione..." selected="{{$dadosPrestador->prestador_set}}" />
                            </x-adminlte-select>
                        </div>

                        <div class="row">
                            @php 

                                $dataGerEmp = DB::table('parametros_ger_empresas')->where('parger_emp', $dadosPrestador->prestador_empresa)->get();

                                $diaFunEmp = $dataGerEmp[0]->parger_dia_fun;

                                if($dataGerEmp[0]->parger_tur_srv == 'N'){
                                    $dataTur = DB::table('parametros_ger_turnos')->where('partur_emp', $dadosPrestador->prestador_empresa)->where('partur_cod', '1')->orderBy('partur_cod', 'asc')->get();
                                }else{
                                    $dataTur = DB::table('parametros_ger_turnos')->where('partur_emp', $dadosPrestador->prestador_empresa)->orderBy('partur_cod', 'asc')->get();
                                }

                                $new_array1_tur =[];
                                $new_array2_tur =[];

                                foreach ($dataTur as $turno) {
                                    $new_array1_tur[] = $turno->partur_cod;
                                    $new_array2_tur[] = $turno->partur_cod.' - '.$turno->partur_desc;
                                }
                                $array_opt_tur = array_combine($new_array1_tur, $new_array2_tur);
                            @endphp
                            <!-- turno -->
                            <x-adminlte-select name="turPrestador" fgroup-class="col-md-6">
                                <x-slot name="label">
                                    Turno de Serviço <span style="color:red;">*</span>
                                </x-slot>
                                <x-adminlte-options :options="$array_opt_tur" empty-option="Selecione..." selected="{{$dadosPrestador->prestador_tur_cod}}"/>
                            </x-adminlte-select>
                        </div>
                        <div class="row bloco-semana">
                            <!-- Utiliza intervalo de trabalho no sábado -->
                            <x-adminlte-select name="usaInt" fgroup-class="col-md-4">
                                <x-slot name="label">
                                    Intervalo Expediente <span style="color:red;">*</span>
                                </x-slot>
                                <x-adminlte-options :options="['S' => 'Sim', 'N' => 'Não']" selected="{{$dadosPrestador->prestador_int_ex}}"/>
                            </x-adminlte-select>

                            @php
                                $horaIniInt = Helper::formataHoraMinuto($dadosPrestador->prestador_hr_ini_int);
                            @endphp
                            <x-adminlte-date-range name="horaIniInt" :config="$config" placeholder="Formato Hora:Minuto" fgroup-class="col-md-4">
                                <x-slot name="label">
                                    Hora Ini. Intervalo <span style="color:red;">*</span>
                                </x-slot>
                                <x-slot name="appendSlot">
                                    <div class="input-group-text x-slot-nexus">
                                        <i class="far fa-lg fa-clock"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-date-range>
                            @push('js')<script>$(() => $("#horaIniInt").val('{{ $horaIniInt }}'))</script>@endpush

                            @php
                                $horaFinInt = Helper::formataHoraMinuto($dadosPrestador->prestador_hr_fin_int);
                            @endphp
                            <x-adminlte-date-range name="horaFinInt" :config="$config" placeholder="Formato Hora:Minuto" fgroup-class="col-md-4">
                                <x-slot name="label">
                                    Hora Fin. Intervalo <span style="color:red;">*</span>
                                </x-slot>
                                <x-slot name="appendSlot">
                                    <div class="input-group-text x-slot-nexus">
                                        <i class="far fa-lg fa-clock"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-date-range>
                            @push('js')<script>$(() => $("#horaFinInt").val('{{ $horaFinInt }}'))</script>@endpush
                        </div>

                        <div class="row bloco-sabado">
                            <!-- Utiliza intervalo de trabalho no sábado -->
                            <x-adminlte-select name="usaIntSab" fgroup-class="col-md-4">
                                <x-slot name="label">
                                    Intervalo de Sábado <span style="color:red;">*</span>
                                </x-slot>
                                <x-adminlte-options :options="['S' => 'Sim', 'N' => 'Não']" selected="{{$dadosPrestador->prestador_int_srv_sab}}"/>
                            </x-adminlte-select>

                            @php
                                $horaIniIntSab = Helper::formataHoraMinuto($dadosPrestador->prestador_hr_ini_int_sab);
                            @endphp
                            <x-adminlte-date-range name="horaIniIntSab" :config="$config" placeholder="Formato Hora:Minuto" fgroup-class="col-md-4">
                                <x-slot name="label">
                                    Hora Ini. Intervalo Sábado <span style="color:red;">*</span>
                                </x-slot>
                                <x-slot name="appendSlot">
                                    <div class="input-group-text x-slot-nexus">
                                        <i class="far fa-lg fa-clock"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-date-range>
                            @push('js')<script>$(() => $("#horaIniIntSab").val('{{ $horaIniIntSab }}'))</script>@endpush

                            @php
                                $horaFinIntSab = Helper::formataHoraMinuto($dadosPrestador->prestador_hr_fin_int_sab);
                            @endphp
                            <x-adminlte-date-range name="horaFinIntSab" :config="$config" placeholder="Formato Hora:Minuto" fgroup-class="col-md-4">
                                <x-slot name="label">
                                    Hora Fin. Intervalo Sábado <span style="color:red;">*</span>
                                </x-slot>
                                <x-slot name="appendSlot">
                                    <div class="input-group-text x-slot-nexus">
                                        <i class="far fa-lg fa-clock"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-date-range>
                            @push('js')<script>$(() => $("#horaFinIntSab").val('{{ $horaFinIntSab }}'))</script>@endpush
                        </div>
                        <div class="row bloco-domingo">
                            <!-- Utiliza intervalo de trabalho no sábado -->
                            <x-adminlte-select name="usaIntDom" fgroup-class="col-md-4">
                                <x-slot name="label">
                                    Intervalo de Domingo <span style="color:red;">*</span>
                                </x-slot>
                                <x-adminlte-options :options="['S' => 'Sim', 'N' => 'Não']" selected="{{$dadosPrestador->prestador_int_srv_dom}}"/>
                            </x-adminlte-select>

                            @php
                                $horaIniIntDom = Helper::formataHoraMinuto($dadosPrestador->prestador_hr_ini_int_dom);
                            @endphp
                            <x-adminlte-date-range name="horaIniIntDom" :config="$config" placeholder="Formato Hora:Minuto" fgroup-class="col-md-4">
                                <x-slot name="label">
                                    Hora Ini. Intervalo Domingo <span style="color:red;">*</span>
                                </x-slot>
                                <x-slot name="appendSlot">
                                    <div class="input-group-text x-slot-nexus">
                                        <i class="far fa-lg fa-clock"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-date-range>
                            @push('js')<script>$(() => $("#horaIniIntDom").val('{{ $horaIniIntDom }}'))</script>@endpush

                            @php
                                $horaFinIntDom = Helper::formataHoraMinuto($dadosPrestador->prestador_hr_fin_int_dom);
                            @endphp
                            <x-adminlte-date-range name="horaFinIntDom" :config="$config" placeholder="Formato Hora:Minuto" fgroup-class="col-md-4">
                                <x-slot name="label">
                                    Hora Fin. Intervalo Domingo <span style="color:red;">*</span>
                                </x-slot>
                                <x-slot name="appendSlot">
                                    <div class="input-group-text x-slot-nexus">
                                        <i class="far fa-lg fa-clock"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-date-range>
                            @push('js')<script>$(() => $("#horaFinIntDom").val('{{ $horaFinIntDom }}'))</script>@endpush
                        </div>
                        <div class="d-flex justify-content-center">
                            <x-adminlte-button class="btn-nexus btn_salvar" type="submit" label="Salvar" theme="" icon="fa-solid fa-share-from-square"/>
                        </div>
                    </form>
                </div>

                <!-- Aba dos dados do Contato do prestador -->
                <div class="tab-pane fade" id="custom-tabs-two-contato" role="tabpanel" aria-labelledby="custom-tabs-two-contato-tab">
                    <form method="post" action="{{route('cadastroPrestador.update', ['cadastroPrestador' => $dadosPrestador, 'atualiza' => 'contato', 'tipo' => $tipo])}}" id="formulario-contato" novalidate="novalidate">
                    @csrf 
                    @method('put')
                        <div class="row">
                            <!-- Tipo do Email -->
                            <x-adminlte-select name="tipoEmail" fgroup-class="col-md-4">
                                <x-slot name="label">
                                    Tipo do Email <span style="color:red;">*</span>
                                </x-slot>
                                <x-adminlte-options :options="['P' => 'Pessoal', 'C' => 'Comercial']" empty-option="Selecione..." selected="{{$dadosPrestador->prestador_tipo_email}}"/>
                            </x-adminlte-select>

                            <!-- Email -->
                            <x-adminlte-input name="emailPrestador" type="email" placeholder="email@exemplo.com" fgroup-class="col-md-8" value="{{$dadosPrestador->prestador_email }}">
                                <x-slot name="label">
                                    Email <span style="color:red;">*</span>
                                </x-slot>
                                <x-slot name="prependSlot">
                                    <div class="input-group-text x-slot-nexus">
                                        <i class="fa-solid fa-envelope"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-input>
                        </div>

                        <div class="row">
                            <!-- Telefone Residencial -->
                            <x-adminlte-input name="telResidencial" type="text" label="Telefone Residencial" fgroup-class="col-md-4" value="{{$dadosPrestador->prestador_tel_residencial }}">
                                <x-slot name="prependSlot">
                                    <div class="input-group-text x-slot-nexus">
                                        <i class="fas fa-phone"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-input>

                            <!-- Telefone Celular -->
                            <x-adminlte-input name="telCelular" type="text" label="Telefone Celular" fgroup-class="col-md-4" value="{{$dadosPrestador->prestador_tel_celular }}">
                                <x-slot name="prependSlot">
                                    <div class="input-group-text x-slot-nexus">
                                        <i class="fa-solid fa-mobile-retro"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-input>
                        </div>
                        <div class="d-flex justify-content-center">
                            <x-adminlte-button class="btn-nexus btn_salvar" type="submit" label="Salvar" theme="" icon="fa-solid fa-share-from-square"/>
                        </div>
                    </form>
                </div>

                <!-- Aba dos dados do Endereço do prestador -->
                <div class="tab-pane fade" id="custom-tabs-two-endereco" role="tabpanel" aria-labelledby="custom-tabs-two-endereco-tab">
                    <div class="main col-md-12" style="display: flex;flex-direction: column;"> 
                        @php
                            //Busca os dados dos endereços cadastrados do prestador
                            $data = DB::table('cadastro_prestadores_enderecos')->where('endereco_prestador_codigo','=',$dadosPrestador->prestador_codigo)->orderby('endereco_seq')->get();
                                
                            if(empty($data[0])){
                        @endphp
                        <!-- Se ainda não foi cadastrado endereço para o prestador cria card vazio -->
                        <div class="col-md-4">
                            <x-adminlte-card title="Endereço" theme="" theme-mode="outline" header-class="card-outline-nexus">
                                <i>Registros não encontrados</i>
                            </x-adminlte-card>
                        </div>
                        @php
                            }else{
                                $cnt_end = 0;
                        @endphp
                        <!-- Cria os cards com os endereços cadastrados -->
                        <div class="col-md-12">
                            @foreach ($data as $endereco)

                                @php
                                    $cnt_end += 1;
                                
                                    if($endereco->endereco_principal == "S"){
                                        $titulo = "Endereço ".$cnt_end." - Principal";
                                        $icone = 'fa-solid fa-location-dot';
                                    }else{
                                        $titulo = "Endereço ".$cnt_end;
                                        $icone = '';
                                    }

                                    $cep = substr($endereco->endereco_cep,0,5).'-'.substr($endereco->endereco_cep,-3,3);
                                @endphp
                                <div class="col-md-4" style="float: left;">
                                    <!-- Card do Endereço do prestador -->
                                    <x-adminlte-card :title="$titulo" :icon="$icone" theme="" theme-mode="outline" header-class="card-outline-nexus">
                                        <i>{{ $endereco->endereco_logradouro }}, {{ $endereco->endereco_numero }}</br>
                                            @php
                                                if(!empty($endereco->endereco_complemento)){
                                            @endphp
                                            Complemento: {{ $endereco->endereco_complemento }}</br>
                                            @php
                                                }
                                            @endphp
                                            {{ $cep }}</br>
                                            {{ $endereco->endereco_bairro }}</br>
                                            {{ $endereco->endereco_cidade }} - {{ $endereco->endereco_uf }}</br>
                                            {{ $endereco->endereco_pais }}
                                        </i>
                                        <!-- Gera a div dos botões do card -->
                                        <div style="padding: 10px; height:30px;">
                                            <form method="post" action="{{ route('prestadorEndereco.destroy', ['prestadorEndereco' => $endereco->endereco_id, 'tipo' => $tipo]) }}" style="float: left;" >
                                            @csrf 
                                            @method('delete')
                                                <x-adminlte-button title="Excluir Endereço" class="btn-sm" theme="danger" icon="fa fa-lg fa-fw fa-trash" type="submit" style="margin-right: 5px;"/>
                                            </form>
                                            @php
                                                if($endereco->endereco_principal == "N"){
                                                    $endPrincipal = json_encode($endereco);
                                            @endphp
                                            <form method="post" action="{{ route('prestadorEndereco.update', ['prestadorEndereco' => $endereco->endereco_id, 'tipo' => $tipo]) }}" style="float: left;">
                                            @csrf 
                                            @method('put')
                                                <x-adminlte-button title="Tornar Principal" class="btn-sm" label="Tornar Principal" theme="success" icon="fa-solid fa-location-dot" type="submit"/>
                                            </form>
                                            @php 
                                                }
                                            @endphp
                                        </div>
                                    </x-adminlte-card>
                                </div>
                            @endforeach
                        </div>
                        <!-- Fecha o else da montagem dos cards do endereço -->
                        @php
                            }
                        @endphp
                           
                        <!-- Gera o Modal com os campos da inserção dos dados do endereço do prestador -->
                        <div>
                            <form method="post" action="{{route('prestadorEndereco.store')}}" id="formularioEndereco" novalidate="novalidate">
                            @csrf 
                            @method('post')    
                                <!-- Criação do Modal -->                           
                                <x-adminlte-modal id="modalCustom" title="Novo Endereço" size="lg" theme="modal-nexus" icon="fa-solid fa-address-book" v-centered static-backdrop scrollable>
                                    <div style="height:400px;">
                                        <!-- Campos escondidos com o id e codigo do prestador para o request -->  
                                        <input id="prestador_codigo" type="hidden" value="{{ $dadosPrestador->prestador_codigo }}" name="prestador_codigo">
                                        <input id="ibgeCodMun" type="hidden" name="ibgeCodMun">
                                        <input id="tipo" type="hidden" value="{{$tipo}}" name="tipo">
                                    
                                        <!-- CEP -->
                                        <x-adminlte-input name="cep" type="text" fgroup-class="col-md-4">
                                            <x-slot name="label">
                                                CEP <span style="color:red;">*</span>
                                            </x-slot>
                                            <x-slot name="prependSlot">
                                                <div class="input-group-text x-slot-nexus">
                                                    <i class="fa-solid fa-location-dot"></i>
                                                </div>
                                            </x-slot>
                                        </x-adminlte-input>

                                        <div class="row">
                                            <!-- Logradouro -->
                                            <x-adminlte-input name="logradouro" type="text" fgroup-class="col-md-9">
                                                <x-slot name="label">
                                                    Logradouro <span style="color:red;">*</span>
                                                </x-slot>
                                                <x-slot name="prependSlot">
                                                    <div class="input-group-text x-slot-nexus">
                                                        <i class="fa-solid fa-address-book"></i>
                                                    </div>
                                                </x-slot>
                                            </x-adminlte-input>

                                            <!-- Numero -->
                                            <x-adminlte-input name="numero" type="text" fgroup-class="col-md-3">
                                                <x-slot name="label">
                                                    Número <span style="color:red;">*</span>
                                                </x-slot>
                                                <x-slot name="prependSlot">
                                                    <div class="input-group-text x-slot-nexus">
                                                        <i class="fa-solid fa-hashtag"></i>
                                                    </div>
                                                </x-slot>
                                            </x-adminlte-input>
                                        </div>
                                            
                                        <div class="row">
                                            <!-- Complemento -->
                                            <x-adminlte-input name="complemento" type="text" label="Complemento" placeholder="Exe.: Apto 1002, Casa A ou Chácara" fgroup-class="col-md-6"></x-adminlte-input>

                                            <!-- Bairro -->
                                            <x-adminlte-input name="bairro" type="text" fgroup-class="col-md-6">
                                                <x-slot name="label">
                                                    Bairro <span style="color:red;">*</span>
                                                </x-slot>
                                            </x-adminlte-input>
                                        </div>

                                        <div class="row">
                                            <!-- Cidade -->
                                            <x-adminlte-input name="cidade" type="text" fgroup-class="col-md-6">
                                                <x-slot name="label">
                                                    Cidade <span style="color:red;">*</span>
                                                </x-slot>
                                                <x-slot name="prependSlot">
                                                    <div class="input-group-text x-slot-nexus">
                                                        <i class="fa-solid fa-city"></i>
                                                    </div>
                                                </x-slot>
                                            </x-adminlte-input>

                                            @php
                                                $dados_ibge = DB::table('ibge_estados')->orderby('ibge_sigla')->get();

                                                $new_array1 =[];
                                                $new_array2 =[];

                                                foreach ($dados_ibge as $ibge) {
                                                    $new_array1[] = $ibge->ibge_sigla;
                                                    $new_array2[] = $ibge->ibge_sigla.' - '.$ibge->ibge_nome;
                                                }
                                                $array_opt = array_combine($new_array1, $new_array2);

                                                $dadosPais = DB::table('ibge_paises')->orderby('ibge_pais_nome')->get();

                                                $new_array_pais =[];
                                                $new_array_pais2 =[];

                                                foreach ($dadosPais as $pais) {
                                                    $new_array_pais[] = $pais->ibge_pais_codigo;
                                                    $new_array_pais2[] = $pais->ibge_pais_nome;
                                                }
                                                $array_opt_pais = array_combine($new_array_pais, $new_array_pais2);
                                            @endphp

                                            <!-- Estado -->
                                            <x-adminlte-select name="uf" fgroup-class="col-md-3">
                                                <x-slot name="label">
                                                    UF <span style="color:red;">*</span>
                                                </x-slot>
                                                <x-adminlte-options :options="$array_opt" empty-option="Selecione..."/>
                                            </x-adminlte-select>

                                            <!-- Pais -->
                                            <x-adminlte-select name="pais" fgroup-class="col-md-3">
                                                <x-slot name="label">
                                                    País <span style="color:red;">*</span>
                                                </x-slot>
                                                <x-adminlte-options :options="$array_opt_pais" empty-option="Selecione..."/>
                                            </x-adminlte-select>
                                        </div>
                                        <!-- Criação dos botões do Modal -->  
                                        <x-slot name="footerSlot">
                                            <x-adminlte-button class="btn-nexus mr-auto" theme="" label="Salvar" icon="fa-solid fa-share-from-square" type="submit"/>
                                            <x-adminlte-button class="btn-nexus" theme="" label="Voltar" data-dismiss="modal"/>
                                        </x-slot>
                                    </div>
                                </x-adminlte-modal>
                            </form>
                            <!-- Botão de chamada do Modal -->  
                            <div class="d-flex justify-content-center">
                                <x-adminlte-button class="btn-nexus" label="Novo Endereço" data-toggle="modal" theme="" data-target="#modalCustom" icon="fa-solid fa-address-book"/>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="d-flex justify-content-between w-100">
                <div class="d-flex">
                    <form method="get" action="{{ route('cadastroPrestador.create') }}" style="float: left; margin-right: 2px;">
                    @csrf 
                        <x-adminlte-button class="btn-nexus" label="Novo Prestador" theme="" icon="fa-solid fa-plus" type="submit"/>
                    </form>
                    <form method="post" action="{{ route('cadastroPrestador.destroy', ['cadastroPrestador' => $dadosPrestador]) }}" style="float: left;margin-left: 2px;">
                    @csrf 
                    @method('delete')
                        <x-adminlte-button class="btn-nexus" label="Excluir Prestador" theme="" icon="fa-solid fa-trash" type="submit"/>
                    </form>
                </div>
                <div class="d-flex">
                    @if($tipo != "H")
                    <x-adminlte-button class="btn-nexus" type="button" onclick="window.location='{{ route('cadastroPrestador.show', ['cadastroPrestador' => $tipo]) }}'" label="Voltar" theme="" icon=""/>
                    @else
                    <x-adminlte-button class="btn-nexus" type="button" onclick="window.location='{{ route('cadastroPrestador.index') }}'" label="Voltar" theme="" icon=""/>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@stop

<!-- Chamada dos Plugins usados na app -->
@section('plugins.jqueryValidation', true)
@section('plugins.Sweetalert2', true)
@section('plugins.DateRangePicker', true)
@section('plugins.Inputmask', true)

@section('css')
@stop

@section('js')
<!--
|--------------------------------------------------------------------------
| Eventos Inicial da app
|--------------------------------------------------------------------------
-->
<script>
    $(document).ready(function() {    

        //Verifica de onde veio a app, cadastro ou edição
        var acao = {!! json_encode($acao) !!};

        $('#cpfPrestador').inputmask({
            "mask": "999.999.999-99",
            // Specify other options...
        });

        $('#rgPrestador').inputmask({
            "mask": "99.999.999-9",
            // Specify other options...
        });

        $('#telCelular').inputmask({
            "mask": "(99) 9 9999-9999",
             // Specify other options...
        });
        
        // Init input mask on the target element.

        $('#telResidencial').inputmask({
            "mask": "(99) 9999-9999",
            // Specify other options...
        });

        $('#cep').inputmask({
            "mask": "99999-999",
            // Specify other options...
        });

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

        //Verifica de onde veio a app, cadastro ou edição
        var usuarioSis = {!! json_encode($usuarioSis) !!};

        if(usuarioSis == 'S'){
            $("#codUsuPrestador").prop('disabled', false);
        }else{
            $("#codUsuPrestador").prop('disabled', true);
        }

        //Esconde calendário de data
        $(function() { 
            $('#horaIniInt').on('showCalendar.daterangepicker', function(ev, picker) {

                $('.calendar-table').hide();

            }) 
        });

        //Esconde calendário de data
        $(function() { 
            $('#horaFinInt').on('showCalendar.daterangepicker', function(ev, picker) {

                $('.calendar-table').hide();

            }) 
        });

        //Esconde calendário de data
        $(function() { 
            $('#horaIniIntSab').on('showCalendar.daterangepicker', function(ev, picker) {

                $('.calendar-table').hide();

            }) 
        });

        //Esconde calendário de data
        $(function() { 
            $('#horaFinIntSab').on('showCalendar.daterangepicker', function(ev, picker) {

                $('.calendar-table').hide();

            }) 
        });

        //Esconde calendário de data
        $(function() { 
            $('#horaIniIntDom').on('showCalendar.daterangepicker', function(ev, picker) {

                $('.calendar-table').hide();

            }) 
        });

        //Esconde calendário de data
        $(function() { 
            $('#horaFinIntDom').on('showCalendar.daterangepicker', function(ev, picker) {

                $('.calendar-table').hide();

            }) 
        });

        //Verifica de onde veio a app, cadastro ou edição
        var acao = {!! json_encode($acao) !!};

        if(acao == 'N'){
            $(".bloco-semana").hide();
            $(".bloco-sabado").hide();
            $(".bloco-domingo").hide();
        }else{
            if( $("#turPrestador").val() == '1' ){

                var diaFunEmp = {!! json_encode($diaFunEmp) !!};

                if (diaFunEmp == 1) {
                    $(".bloco-semana").show();
                    $(".bloco-sabado").hide();
                    $(".bloco-domingo").hide();
                }else if(diaFunEmp == 2) {
                    $(".bloco-semana").show();
                    $(".bloco-sabado").show();
                    $(".bloco-domingo").hide();
                }else{
                    $(".bloco-semana").show();
                    $(".bloco-sabado").show();
                    $(".bloco-domingo").show();
                }

            }else if( $("#turPrestador").val() ){
                $(".bloco-semana").show();
                $(".bloco-sabado").hide();
                $(".bloco-domingo").hide();
            }else{
                $(".bloco-semana").hide();
                $(".bloco-sabado").hide();
                $(".bloco-domingo").hide();
            }
        }
    });
</script>

<!--
|--------------------------------------------------------------------------
| Eventos onClick da app
|--------------------------------------------------------------------------
-->
<script>
    $(document).ready(function() {

        //Ao clicar no botão salvar retira o disabled do campo para não ter problema no request do update do campo
        $(".btn_salvar").click(function(){
            $("#codUsuPrestador").prop('disabled', false);
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
        $('#areaPrestador').change(function(){

            if( $(this).val() ) {
                var area = $(this).val();
                var empresa = $('#empresaPrestador').val();

                var url = "{{ route('ajax.carregaSetoresEmpAreAjax', [':area',':empresa']) }}";
                url = url.replace(':area', area);
                url = url.replace(':empresa', empresa);

                $.ajax({
                    url: url,
                    dataType: "JSON",
                    type: 'GET',
                    data: {
                        '_token': $('meta[name=csrf-token]').attr("content"),
                        '_method': 'GET',
                        "area": area,
                        "empresa": empresa
                    },
                    success: function (data)
                    {
                        if(data.setores_ajax_existe == 'S'){

                            var options = '<option value="">Selecione...</option>';	

                            for (var i = 0; i < data.setores_ajax.length; i++) {

                                options += '<option value="' + data.setores_ajax[i].id + '">' + data.setores_ajax[i].cod_setor + '</option>';
                            }	
                            $('#setPrestador').html(options);

                        }else{
                            $('#setPrestador').html('<option value="">Selecione...</option>');
                        }
                    }
                });
            } else {
				$('#setPrestador').html('<option value="">Selecione...</option>');
			}
        });

        //Evento de carregamento ajax dos dados dos códigos do serviço do grupo selecionado
        $('#prestadorUsuSis').change(function(){

            if( $(this).val() == 'S') {

                var url = "{{ route('ajax.carregaUsuariosAjax') }}";

                $.ajax({
                    url: url,
                    dataType: "JSON",
                    type: 'GET',
                    data: {
                        '_token': $('meta[name=csrf-token]').attr("content"),
                        '_method': 'GET'
                    },
                    success: function (data)
                    {
                        var options = '<option value="">Selecione...</option>';	

                        for (var i = 0; i < data.usuarios_ajax.length; i++) {

                            options += '<option value="' + data.usuarios_ajax[i].codigo + '">' + data.usuarios_ajax[i].descricao + '</option>';
                        }	
                        $('#codUsuPrestador').html(options);
                        $("#codUsuPrestador").prop('disabled', false);
                    }
                });
            } else {
                $('#codUsuPrestador').html('<option value="">Selecione...</option>');
                $("#codUsuPrestador").attr("disabled", true);
            }
        });

        //Verifica de onde veio a app, cadastro ou edição
        var acao = {!! json_encode($acao) !!};

        if(acao == 'N'){
            //Evento de carregamento ajax dos dados dos turnos
            $('#empresaPrestador').change(function(){

                if( $(this).val()) {

                    var empresa = $(this).val();

                    var url = "{{ route('ajax.carregaTurnosEmpresaAjax', [':empresa']) }}";
                    url = url.replace(':empresa', empresa);

                    $.ajax({
                        url: url,
                        dataType: "JSON",
                        type: 'GET',
                        data: {
                            '_token': $('meta[name=csrf-token]').attr("content"),
                            '_method': 'GET',
                            "empresa": empresa
                        },
                        success: function (data)
                        {
                            var options = '<option value="">Selecione...</option>';	

                            for (var i = 0; i < data.turnos_ajax.length; i++) {

                                options += '<option value="' + data.turnos_ajax[i].codigo + '">' + data.turnos_ajax[i].descricao + '</option>';
                            }	
                            $('#turPrestador').html(options);
                        }
                    });
                } else {
                    $('#turPrestador').html('<option value="">Selecione...</option>');
                    $(".bloco-semana").hide();
                    $(".bloco-sabado").hide();
                    $(".bloco-domingo").hide();

                    $("#usaInt").val('N'); 
                    $("#horaIniInt").val(''); 
                    $("#horaFinInt").val(''); 
                    $("#usaIntSab").val('N'); 
                    $("#horaIniIntSab").val(''); 
                    $("#horaFinIntSab").val(''); 
                    $("#usaIntDom").val('N');  
                    $("#horaIniIntDom").val('');  
                    $("#horaFinIntDom").val('');  
                }
            });

            //Verifica o turno do prestador
            $('#turPrestador').change(function(){

                if( $(this).val() == 1) {

                    var empresa = $('#empresaPrestador').val();

                    var url = "{{ route('ajax.carregaDiaFunEmpresaAjax', [':empresa']) }}";
                    url = url.replace(':empresa', empresa);

                    $.ajax({
                        url: url,
                        dataType: "JSON",
                        type: 'GET',
                        data: {
                            '_token': $('meta[name=csrf-token]').attr("content"),
                            '_method': 'GET',
                            "empresa": empresa
                        },
                        success: function (data)
                        {
                            if (data.funcionamento == 1) {
                                $(".bloco-semana").show();
                                $(".bloco-sabado").hide();
                                $(".bloco-domingo").hide();
                                $("#usaIntSab").val('N'); 
                                $("#horaIniIntSab").val(''); 
                                $("#horaFinIntSab").val(''); 
                                $("#usaIntDom").val('N');  
                                $("#horaIniIntDom").val('');  
                                $("#horaFinIntDom").val('');  
                            }else if(data.funcionamento == 2) {
                                $(".bloco-semana").show();
                                $(".bloco-sabado").show();
                                $(".bloco-domingo").hide();
                                $("#usaIntDom").val('N');  
                                $("#horaIniIntDom").val('');  
                                $("#horaFinIntDom").val('');  
                            }else{
                                $(".bloco-semana").show();
                                $(".bloco-sabado").show();
                                $(".bloco-domingo").show();
                            }	
                        }
                    });
                } else if( $(this).val() ) {
                    $(".bloco-semana").show();
                    $(".bloco-domingo").hide();
                    $(".bloco-sabado").hide();

                    $("#usaIntSab").val('N'); 
                    $("#horaIniIntSab").val(''); 
                    $("#horaFinIntSab").val(''); 
                    $("#usaIntDom").val('N');  
                    $("#horaIniIntDom").val('');  
                    $("#horaFinIntDom").val(''); 
                }else{
                    $(".bloco-semana").hide();
                    $(".bloco-sabado").hide();
                    $(".bloco-domingo").hide();

                    $("#usaInt").val('N'); 
                    $("#horaIniInt").val(''); 
                    $("#horaFinInt").val(''); 
                    $("#usaIntSab").val('N'); 
                    $("#horaIniIntSab").val(''); 
                    $("#horaFinIntSab").val(''); 
                    $("#usaIntDom").val('N');  
                    $("#horaIniIntDom").val('');  
                    $("#horaFinIntDom").val('');  
                }
            });
        }else{
            //Verifica o turno do prestador
            $('#turPrestador').change(function(){

                if( $(this).val() == 1) {

                    var diaFunEmp = {!! json_encode($diaFunEmp) !!};

                    if (diaFunEmp == 1) {
                        $(".bloco-semana").show();
                        $(".bloco-sabado").hide();
                        $(".bloco-domingo").hide();
                        $("#usaIntSab").val('N'); 
                        $("#horaIniIntSab").val(''); 
                        $("#horaFinIntSab").val(''); 
                        $("#usaIntDom").val('N');  
                        $("#horaIniIntDom").val('');  
                        $("#horaFinIntDom").val('');  
                    }else if(diaFunEmp == 2) {
                        $(".bloco-semana").show();
                        $(".bloco-sabado").show();
                        $(".bloco-domingo").hide();
                        $("#usaIntDom").val('N');  
                        $("#horaIniIntDom").val('');  
                        $("#horaFinIntDom").val('');  
                    }else{
                        $(".bloco-semana").show();
                        $(".bloco-sabado").show();
                        $(".bloco-domingo").show();
                    }	
                } else if( $(this).val() ) {
                    $(".bloco-semana").show();
                    $(".bloco-domingo").hide();
                    $(".bloco-sabado").hide();

                    $("#usaIntSab").val('N'); 
                    $("#horaIniIntSab").val(''); 
                    $("#horaFinIntSab").val(''); 
                    $("#usaIntDom").val('N');  
                    $("#horaIniIntDom").val('');  
                    $("#horaFinIntDom").val(''); 
                }else{
                    $(".bloco-semana").hide();
                    $(".bloco-sabado").hide();
                    $(".bloco-domingo").hide();

                    $("#usaInt").val('N'); 
                    $("#horaIniInt").val(''); 
                    $("#horaFinInt").val(''); 
                    $("#usaIntSab").val('N'); 
                    $("#horaIniIntSab").val(''); 
                    $("#horaFinIntSab").val(''); 
                    $("#usaIntDom").val('N');  
                    $("#horaIniIntDom").val('');  
                    $("#horaFinIntDom").val('');  
                }
            });
        }
    });
</script>

<!--
|--------------------------------------------------------------------------
| Eventos Validate da app
|--------------------------------------------------------------------------
-->
<script>
$(function () {

    $('#formulario-novo').validate({
        rules: {
            empresaPrestador: {
                required: true
            },
            nomePrestador: {
                required: true,
                maxlength: 80
            },
            cpfPrestador: {
                required: true
            },
            areaPrestador: {
                required: true
            },
            setPrestador: {
                required: true
            },
            horaIniInt: {
                required: function(element) {
                    let usaInt = $('#usaInt').val();
                    return usaInt == 'S';
                }
            },
            horaFinInt: {
                required: function(element) {
                    let usaInt = $('#usaInt').val();
                    return usaInt == 'S';
                }
            },
            horaIniIntSab: {
                required: function(element) {
                    let usaIntSab = $('#usaIntSab').val();
                    return usaIntSab == 'S';
                }
            },
            horaFinIntSab: {
                required: function(element) {
                    let usaIntSab = $('#usaIntSab').val();
                    return usaIntSab == 'S';
                }
            },
            horaIniIntDom: {
                required: function(element) {
                    let usaIntDom = $('#usaIntDom').val();
                    return usaIntDom == 'S';
                }
            },
            horaFinIntDom: {
                required: function(element) {
                    let usaIntDom = $('#usaIntDom').val();
                    return usaIntDom == 'S';
                }
            },
        },
        messages: {
            empresaPrestador: {
                required: "Por Favor informe a Empresa"
            },
            nomePrestador: {
                required: "Por Favor informe o Nome",
                maxlength: "Infome no máximo 80 caracteres"
            },
            cpfPrestador: {
                required: "Por Favor informe o CPF"
            },
            areaPrestador: {
                required: "Por Favor informe a Área"
            },
            setPrestador: {
                required: "Por Favor informe o Setor"
            },
            horaIniInt: {
                required: "Por Favor informe a hora do inicio do intervalo"
            },
            horaFinInt: {
                required: "Por Favor informe a hora do final do intervalo"
            },
            horaIniIntSab: {
                required: "Por Favor informe a hora do inicio de intervalo do sábado"
            },
            horaFinIntSab: {
                required: "Por Favor informe a hora do final de intervalo do sábado"
            },
            horaIniIntDom: {
                required: "Por Favor informe a hora do inicio de intervalo do domingo"
            },
            horaFinIntDom: {
                required: "Por Favor informe a hora do final de intervalo do domingo"
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

    $('#formulario-dados').validate({
        rules: {
            empresaPrestador: {
                required: true
            },
            nomePrestador: {
                required: true,
                maxlength: 80
            },
            cpfPrestador: {
                required: true
            },
            dataAdmissao: {
                required: true
            },
            prestadorUsuSis: {
                required: true
            },
            codUsuPrestador: {
                required: function(element) {
                    let usuSis = $('#prestadorUsuSis').val();
                    return usuSis === 'S';
                }
            },
        },
        messages: {
            empresaPrestador: {
                required: "Por Favor informe a Empresa"
            },
            nomePrestador: {
                required: "Por Favor informe o Nome",
                maxlength: "Infome no máximo 80 caracteres"
            },
            cpfPrestador: {
                required: "Por Favor informe o CPF"
            },
            dataAdmissao: {
                required: "Por Favor informe a Data de Admissão"
            },
            prestadorUsuSis: {
                required: "Por Favor informe se o Prestador tem acesso ao sistema"
            },
            codUsuPrestador: {
                required: "Por Favor informe o Código de Usuário do Sistema"
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
        }
    });

    $('#formulario-servico').validate({
        rules: {
            areaPrestador: {
                required: true
            },
            setPrestador: {
                required: true
            },
            turPrestador: {
                required: true
            },
            horaIniInt: {
                required: function(element) {
                    let usaInt = $('#usaInt').val();
                    return usaInt == 'S';
                }
            },
            horaFinInt: {
                required: function(element) {
                    let usaInt = $('#usaInt').val();
                    return usaInt == 'S';
                }
            },
            horaIniIntSab: {
                required: function(element) {
                    let usaIntSab = $('#usaIntSab').val();
                    return usaIntSab == 'S';
                }
            },
            horaFinIntSab: {
                required: function(element) {
                    let usaIntSab = $('#usaIntSab').val();
                    return usaIntSab == 'S';
                }
            },
            horaIniIntDom: {
                required: function(element) {
                    let usaIntDom = $('#usaIntDom').val();
                    return usaIntDom == 'S';
                }
            },
            horaFinIntDom: {
                required: function(element) {
                    let usaIntDom = $('#usaIntDom').val();
                    return usaIntDom == 'S';
                }
            },
        },
        messages: {
            areaPrestador: {
                required: "Por Favor informe a Área"
            },
            setPrestador: {
                required: "Por Favor informe o Setor"
            },
            turPrestador: {
                required: "Por Favor informe o Turno"
            },
            horaIniInt: {
                required: "Por Favor informe a hora do inicio do intervalo"
            },
            horaFinInt: {
                required: "Por Favor informe a hora do final do intervalo"
            },
            horaIniIntSab: {
                required: "Por Favor informe a hora do inicio de intervalo do sábado"
            },
            horaFinIntSab: {
                required: "Por Favor informe a hora do final de intervalo do sábado"
            },
            horaIniIntDom: {
                required: "Por Favor informe a hora do inicio de intervalo do domingo"
            },
            horaFinIntDom: {
                required: "Por Favor informe a hora do final de intervalo do domingo"
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

    $('#formulario-contato').validate({
        rules: {
            tipoEmail: {
                required: true
            },
            emailPrestador: {
                email: true,
                maxlength: 80,
                required: true
            },
        },
        messages: {
            tipoEmail: {
                required: "Por Favor informe o Tipo do Email"
            },
            emailPrestador: {
                email: "Formato do Email inválido",
                required: "Por Favor informe o Email",
                maxlength: "Informe no máximo 80 caracteres no Email"
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
        }
    });

    $('#formularioEndereco').validate({
        rules: {
            cep: {
                required: true
            },
            logradouro: {
                required: true,
                maxlength: 100
            },
            numero: {
                required: true,
                maxlength: 5
            },
            complemento: {
                maxlength: 60
            },
            bairro: {
                required: true,
                maxlength: 60
            },
            cidade: {
                required: true,
                maxlength: 80
            },
            uf: {
                required: true
            },
            pais: {
                required: true,
                maxlength: 40
            },
        },
        messages: {
            cep: {
                required: "Por Favor informe um CEP para o Endereço"
            },
            logradouro: {
                required: "Por Favor informe um Logradouro para o Endereço",
                maxlength: "Informe no máximo 100 caracteres para o Logradouro"
            },
            numero: {
                required: "Por Favor informe o Número do Endereço",
                maxlength: "Informe no máximo 5 caracteres no Número"
            },
            complemento: {
                maxlength: "Informe no máximo 60 caracteres no Complemento"
            },
            bairro: {
                required: "Por Favor informe um Bairro para o Endereço",
                maxlength: "Informe no máximo 60 caracteres no Email"
            },
            cidade: {
                required: "Por Favor informe uma Cidade para o Endereço",
                maxlength: "Informe no máximo 80 caracteres no Email"
            },
            uf: {
                required: "Por Favor informe uma UF para o Endereço"
            },
            pais: {
                required: "Por Favor informe um País para o Endereço",
                maxlength: "Informe no máximo 40 caracteres no Email"
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
        }
    });
});
</script>

<!--
|--------------------------------------------------------------------------
| Eventos de Messagem da app
|--------------------------------------------------------------------------
-->
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
