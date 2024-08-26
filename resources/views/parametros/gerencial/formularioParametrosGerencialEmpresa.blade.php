@extends('adminlte::page')

@section('title', 'Geral da Empresa')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h4 style="margin-bottom: 0px !important;">Parâmetros Gerais</h4>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active">
                <a href="{{route('home.parametrosGerEmp')}}">Geral da Empresa</a>
            </li>
            <li class="breadcrumb-item active">Manutenção Geral da Empresa</li>
        </ol>
    </div>
</div>
@stop

@section('content')
<div class="col-md-12">
    <div class="card card-tabs">
        <div class="card-header card-nexus p-0 pt-1">
            <ul class="nav nav-tabs" id="custom-tabs-two-tab" role="tablist">
                <li class="pt-2 px-3"><h3 class="card-title">Manutenção da Empresa</h3></li>
                <li class="nav-item">
                    <a class="nav-link active" id="custom-tabs-two-horario-funcionamento-tab" data-toggle="pill" href="#custom-tabs-two-horario-funcionamento" role="tab" aria-controls="custom-tabs-two-horario-funcionamento" aria-selected="true">Horário de Funcionamento</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-two-turnos-tab" data-toggle="pill" href="#custom-tabs-two-turnos" role="tab" aria-controls="custom-tabs-two-turnos" aria-selected="false">Turnos</a>
                </li>
                <!-- Add ml-auto to align card-tools to the right -->
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

                <!-- Aba Horario Funcionamento -->
                <div class="tab-pane fade show active" id="custom-tabs-two-horario-funcionamento" role="tabpanel" aria-labelledby="custom-tabs-two-horario-funcionamento-tab">
                    <!-- Define se o formulario é edição ou novo -->
                    <form method="post" action="{{route('parametrosGerEmp.update', ['empresa' => $parametrosEmp[0]->parger_emp])}}" id="formulario-horario-funcionamento" novalidate="novalidate">
                        @csrf 
                        @method('post')
                        <div class="row">
                            @php
                                $data = DB::table('cadastro_empresas')->where('empresa_codigo', $parametrosEmp[0]->parger_emp)->get();

                                $new_array1 =[];
                                $new_array2 =[];

                                foreach ($data as $empresa) {
                                    $new_array1[] = $empresa->empresa_codigo;
                                    $new_array2[] = $empresa->empresa_codigo.' - '.$empresa->empresa_nome;
                                }
                                $array_opt = array_combine($new_array1, $new_array2);

                            @endphp

                            <!-- Empresa do Setor -->
                            <x-adminlte-select name="empresa" fgroup-class="col-md-6">
                                <x-slot name="label">
                                    Empresa <span style="color:red;">*</span>
                                </x-slot>
                                <x-adminlte-options :options="$array_opt" empty-option="Selecione..." selected="{{$parametrosEmp[0]->parger_emp}}"/>
                            </x-adminlte-select>

                            <x-adminlte-select name="turSrv" fgroup-class="col-md-6">
                                <x-slot name="label">
                                    Realiza Turno Serviço <span style="color:red;">*</span>
                                </x-slot>
                                <x-adminlte-options :options="['S' => 'Sim', 'N' => 'Não']" selected="{{$parametrosEmp[0]->parger_tur_srv}}"/>
                            </x-adminlte-select>
                        </div>

                        <div class="row">
                            <!-- Dias de Funcionamento da empresa -->
                            <x-adminlte-select name="diaFuncionamento" fgroup-class="col-md-2">
                                <x-slot name="label">
                                    Dias de Funcionamento <span style="color:red;">*</span>
                                </x-slot>
                                <x-adminlte-options :options="['1' => 'Segunda à Sexta', '2' => 'Segunda à Sábado', '3' => 'Segunda à Domingo']" selected="{{$parametrosEmp[0]->parger_dia_fun}}"/>
                            </x-adminlte-select>

                            @php
                                $horaIniFun = Helper::formataHoraMinuto($parametrosEmp[0]->parger_hr_ini_fun);

                                $config = Helper::dtRangeHoraPtBR();
                            @endphp
                            <x-adminlte-date-range name="horaIniFun" :config="$config" placeholder="Formato Hora:Minuto" fgroup-class="col-md-2">
                                <x-slot name="label">
                                    Hora Ini. Funcionamento <span style="color:red;">*</span>
                                </x-slot>
                                <x-slot name="appendSlot">
                                    <div class="input-group-text">
                                        <i class="far fa-lg fa-clock"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-date-range>
                            @push('js')<script>$(() => $("#horaIniFun").val('{{ $horaIniFun }}'))</script>@endpush

                            @php
                                $horaFinFun = Helper::formataHoraMinuto($parametrosEmp[0]->parger_hr_fin_fun);

                                $config = Helper::dtRangeHoraPtBR();
                            @endphp
                            <x-adminlte-date-range name="horaFinFun" :config="$config" placeholder="Formato Hora:Minuto" fgroup-class="col-md-2">
                                <x-slot name="label">
                                    Hora Fin. Funcionamento <span style="color:red;">*</span>
                                </x-slot>
                                <x-slot name="appendSlot">
                                    <div class="input-group-text">
                                        <i class="far fa-lg fa-clock"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-date-range>
                            @push('js')<script>$(() => $("#horaFinFun").val('{{ $horaFinFun }}'))</script>@endpush

                            <!-- Utiliza intervalo de funcionamento -->
                            <x-adminlte-select name="usaInt" fgroup-class="col-md-2">
                                <x-slot name="label">
                                    Intervalo Funcionamento <span style="color:red;">*</span>
                                </x-slot>
                                <x-adminlte-options :options="['S' => 'Sim', 'N' => 'Não']" selected="{{$parametrosEmp[0]->parger_int_fun}}"/>
                            </x-adminlte-select>

                            @php
                                $horaIniInt = Helper::formataHoraMinuto($parametrosEmp[0]->parger_hr_ini_int);

                                $config = Helper::dtRangeHoraPtBR();
                            @endphp
                            <x-adminlte-date-range name="horaIniInt" :config="$config" placeholder="Formato Hora:Minuto" fgroup-class="col-md-2">
                                <x-slot name="label">
                                    Hora Ini. Intervalo <span style="color:red;">*</span>
                                </x-slot>
                                <x-slot name="appendSlot">
                                    <div class="input-group-text">
                                        <i class="far fa-lg fa-clock"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-date-range>
                            @push('js')<script>$(() => $("#horaIniInt").val('{{ $horaIniInt }}'))</script>@endpush

                            @php
                                $horaFinInt = Helper::formataHoraMinuto($parametrosEmp[0]->parger_hr_fin_int);

                                $config = Helper::dtRangeHoraPtBR();
                            @endphp
                            <x-adminlte-date-range name="horaFinInt" :config="$config" placeholder="Formato Hora:Minuto" fgroup-class="col-md-2">
                                <x-slot name="label">
                                    Hora Fin. Intervalo <span style="color:red;">*</span>
                                </x-slot>
                                <x-slot name="appendSlot">
                                    <div class="input-group-text">
                                        <i class="far fa-lg fa-clock"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-date-range>
                            @push('js')<script>$(() => $("#horaFinInt").val('{{ $horaFinInt }}'))</script>@endpush
                        </div>

                        <div class="row hora-sabado">

                            <x-adminlte-select name="hrAltSab" fgroup-class="col-md-2">
                                <x-slot name="label">
                                    Horario Alternativo Sábado <span style="color:red;">*</span>
                                </x-slot>
                                <x-adminlte-options :options="['S' => 'Sim', 'N' => 'Não']" selected="{{$parametrosEmp[0]->parger_hr_alt_sab}}"/>
                            </x-adminlte-select>

                            @php
                                $horaIniSab = Helper::formataHoraMinuto($parametrosEmp[0]->parger_hr_ini_sab);

                                $config = Helper::dtRangeHoraPtBR();
                            @endphp
                            <x-adminlte-date-range name="horaIniSab" :config="$config" placeholder="Formato Hora:Minuto" fgroup-class="col-md-2">
                                <x-slot name="label">
                                    Hora Ini. Sábado <span style="color:red;">*</span>
                                </x-slot>
                                <x-slot name="appendSlot">
                                    <div class="input-group-text">
                                        <i class="far fa-lg fa-clock"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-date-range>
                            @push('js')<script>$(() => $("#horaIniSab").val('{{ $horaIniSab }}'))</script>@endpush

                            @php
                                $horaFinSab = Helper::formataHoraMinuto($parametrosEmp[0]->parger_hr_fin_sab);

                                $config = Helper::dtRangeHoraPtBR();
                            @endphp
                            <x-adminlte-date-range name="horaFinSab" :config="$config" placeholder="Formato Hora:Minuto" fgroup-class="col-md-2">
                                <x-slot name="label">
                                    Hora Fin. Sábado <span style="color:red;">*</span>
                                </x-slot>
                                <x-slot name="appendSlot">
                                    <div class="input-group-text">
                                        <i class="far fa-lg fa-clock"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-date-range>
                            @push('js')<script>$(() => $("#horaFinSab").val('{{ $horaFinSab }}'))</script>@endpush

                            <!-- Utiliza intervalo de trabalho no sábado -->
                            <x-adminlte-select name="usaIntSab" fgroup-class="col-md-2">
                                <x-slot name="label">
                                    Intervalo de Sábado <span style="color:red;">*</span>
                                </x-slot>
                                <x-adminlte-options :options="['S' => 'Sim', 'N' => 'Não']" selected="{{$parametrosEmp[0]->parger_int_sab}}"/>
                            </x-adminlte-select>

                            @php
                                $horaIniIntSab = Helper::formataHoraMinuto($parametrosEmp[0]->parger_hr_ini_int_sab);

                                $config = Helper::dtRangeHoraPtBR();
                            @endphp
                            <x-adminlte-date-range name="horaIniIntSab" :config="$config" placeholder="Formato Hora:Minuto" fgroup-class="col-md-2">
                                <x-slot name="label">
                                    Hora Ini. Intervalo Sábado <span style="color:red;">*</span>
                                </x-slot>
                                <x-slot name="appendSlot">
                                    <div class="input-group-text">
                                        <i class="far fa-lg fa-clock"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-date-range>
                            @push('js')<script>$(() => $("#horaIniIntSab").val('{{ $horaIniIntSab }}'))</script>@endpush

                            @php
                                $horaFinIntSab = Helper::formataHoraMinuto($parametrosEmp[0]->parger_hr_fin_int_sab);

                                $config = Helper::dtRangeHoraPtBR();
                            @endphp
                            <x-adminlte-date-range name="horaFinIntSab" :config="$config" placeholder="Formato Hora:Minuto" fgroup-class="col-md-2">
                                <x-slot name="label">
                                    Hora Fin. Intervalo Sábado <span style="color:red;">*</span>
                                </x-slot>
                                <x-slot name="appendSlot">
                                    <div class="input-group-text">
                                        <i class="far fa-lg fa-clock"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-date-range>
                            @push('js')<script>$(() => $("#horaFinIntSab").val('{{ $horaFinIntSab }}'))</script>@endpush
                        </div>

                        <div class="row hora-domingo">

                            <x-adminlte-select name="hrAltDom" fgroup-class="col-md-2">
                                <x-slot name="label">
                                    Horario Alternativo Domingo <span style="color:red;">*</span>
                                </x-slot>
                                <x-adminlte-options :options="['S' => 'Sim', 'N' => 'Não']" selected="{{$parametrosEmp[0]->parger_hr_alt_dom}}"/>
                            </x-adminlte-select>

                            @php
                                $horaIniDom = Helper::formataHoraMinuto($parametrosEmp[0]->parger_hr_ini_dom);
                                
                                $config = Helper::dtRangeHoraPtBR();
                            @endphp
                            <x-adminlte-date-range name="horaIniDom" :config="$config" placeholder="Formato Hora:Minuto" fgroup-class="col-md-2">
                                <x-slot name="label">
                                    Hora Ini. Domingo <span style="color:red;">*</span>
                                </x-slot>
                                <x-slot name="appendSlot">
                                    <div class="input-group-text">
                                        <i class="far fa-lg fa-clock"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-date-range>
                            @push('js')<script>$(() => $("#horaIniDom").val('{{ $horaIniDom }}'))</script>@endpush

                            @php
                                $horaFinDom = Helper::formataHoraMinuto($parametrosEmp[0]->parger_hr_fin_dom);

                                $config = Helper::dtRangeHoraPtBR();
                            @endphp
                            <x-adminlte-date-range name="horaFinDom" :config="$config" placeholder="Formato Hora:Minuto" fgroup-class="col-md-2">
                                <x-slot name="label">
                                    Hora Fin. Domingo <span style="color:red;">*</span>
                                </x-slot>
                                <x-slot name="appendSlot">
                                    <div class="input-group-text">
                                        <i class="far fa-lg fa-clock"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-date-range>
                            @push('js')<script>$(() => $("#horaFinDom").val('{{ $horaFinDom }}'))</script>@endpush

                            <!-- Utiliza intervalo de trabalho no sábado -->
                            <x-adminlte-select name="usaIntDom" fgroup-class="col-md-2">
                                <x-slot name="label">
                                    Intervalo no Domingo <span style="color:red;">*</span>
                                </x-slot>
                                <x-adminlte-options :options="['S' => 'Sim', 'N' => 'Não']" selected="{{$parametrosEmp[0]->parger_int_dom}}"/>
                            </x-adminlte-select>

                            @php
                                $horaIniIntDom = Helper::formataHoraMinuto($parametrosEmp[0]->parger_hr_ini_int_dom);

                                $config = Helper::dtRangeHoraPtBR();
                            @endphp
                            <x-adminlte-date-range name="horaIniIntDom" :config="$config" placeholder="Formato Hora:Minuto" fgroup-class="col-md-2">
                                <x-slot name="label">
                                    Hora Ini. Intervalo Domingo <span style="color:red;">*</span>
                                </x-slot>
                                <x-slot name="appendSlot">
                                    <div class="input-group-text">
                                        <i class="far fa-lg fa-clock"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-date-range>
                            @push('js')<script>$(() => $("#horaIniIntDom").val('{{ $horaIniIntDom }}'))</script>@endpush

                            @php
                                $horaFinIntDom = Helper::formataHoraMinuto($parametrosEmp[0]->parger_hr_fin_int_dom);

                                $config = Helper::dtRangeHoraPtBR();
                            @endphp
                            <x-adminlte-date-range name="horaFinIntDom" :config="$config" placeholder="Formato Hora:Minuto" fgroup-class="col-md-2">
                                <x-slot name="label">
                                    Hora Fin. Intervalo Domingo <span style="color:red;">*</span>
                                </x-slot>
                                <x-slot name="appendSlot">
                                    <div class="input-group-text">
                                        <i class="far fa-lg fa-clock"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-date-range>
                            @push('js')<script>$(() => $("#horaFinIntDom").val('{{ $horaFinIntDom }}'))</script>@endpush
                        </div>

                        <div class="d-flex justify-content-center">
                            <x-adminlte-button type="submit" label="Salvar" theme="" class="btn-nexus" icon="fa-solid fa-share-from-square"/>
                        </div>
                    </form>
                </div>

                <!-- Aba dos dados do turnos do prestador -->
                <div class="tab-pane fade" id="custom-tabs-two-turnos" role="tabpanel" aria-labelledby="custom-tabs-two-turnos-tab">
                    <div class="row d-flex justify-content-center">
                        @php 
                            $dadosTur = DB::table('parametros_ger_turnos')->where('partur_emp', $parametrosEmp[0]->parger_emp)->orderby('partur_cod', 'asc')->get();
                        @endphp
                        @foreach($dadosTur as $turno) 

                            @php 
                                if($turno->partur_dia == 1){
                                    $diaFun = 'Segunda à Sexta';
                                }elseif($turno->partur_dia == 2){
                                    $diaFun = 'Segunda à Sábado';
                                }else{
                                    $diaFun = 'Segunda à Domingo';
                                }
                            @endphp

                            @if($turno->partur_cod == 1)
                                <div class="col-md-3">
                                    <x-adminlte-card theme="" theme-mode="outline" header-class="card-outline-nexus" title="Turno {{$turno->partur_cod}}" icon="fa-solid fa-person-digging">
                                        <div class="text-muted">
                                            <div class="row">
                                                <p class="text-sm col-md-12">Descrição
                                                    <b class="d-block">{{$turno->partur_desc}}</b>
                                                </p>
                                            </div>
                                            <div class="row">
                                                <p class="text-sm col-md-12">Dias de Serviço
                                                    <b class="d-block">{{$diaFun}}</b>
                                                </p>
                                            </div>
                                        </div>
                                    </x-adminlte-card>
                                </div>
                            @else 
                                <div class="col-md-3">
                                    <x-adminlte-card theme="" theme-mode="outline" header-class="card-outline-nexus" title="Turno {{$turno->partur_cod}}" icon="fa-solid fa-person-digging">
                                        <div class="text-muted">
                                            <div class="row">
                                                <p class="text-sm col-md-6">Descrição
                                                    <b class="d-block">{{$turno->partur_desc}}</b>
                                                </p>
                                                <p class="text-sm col-md-6">Dias de Serviço
                                                    <b class="d-block">{{$diaFun}}</b>
                                                </p>
                                            </div>
                                            <div class="row">
                                                <p class="text-sm col-md-6">Hora de Inicio
                                                    <b class="d-block">{{Helper::formataHoraMinuto($turno->partur_hr_ini)}}</b>
                                                </p>
                                                <p class="text-sm col-md-6">Hora de Término
                                                    <b class="d-block">{{Helper::formataHoraMinuto($turno->partur_hr_fin)}}</b>
                                                </p>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-center">
                                            <form method="post" action="{{ route('parametrosGerEmp.destroy', ['turno' => $turno->partur_id, 'empresa' => $turno->partur_emp]) }}" style="float: left;" >
                                                @csrf 
                                                @method('delete')
                                                <x-adminlte-button class="btn-sm" theme="danger" icon="fa fa-lg fa-fw fa-trash" type="submit" style="margin-right: 5px;"/>
                                            </form>
                                        </div>
                                    </x-adminlte-card>
                                </div>
                            @endif
                        @endforeach
                    </div>
                    <!-- Gera o Modal com os campos da inserção dos dados do turno -->
                    <div class="row d-flex justify-content-center">
                        <form method="post" action="{{route('parametrosGerEmp.insertTurno', ['empresa' => $parametrosEmp[0]->parger_emp])}}" id="formulario-turno" novalidate="novalidate">
                        @csrf 
                        @method('post')    
                            <!-- Criação do Modal -->                           
                            <x-adminlte-modal id="modalCustom" title="Novo Turno" size="lg" theme="modal-nexus" icon="fa-solid fa-briefcase" v-centered static-backdrop scrollable>
                                <div class="col-md-12">

                                    <div class="row">
                                        <x-adminlte-select name="diaTurno" fgroup-class="col-md-12">
                                            <x-slot name="label">
                                                Dias do Turno <span style="color:red;">*</span>
                                            </x-slot>
                                            <x-adminlte-options :options="['1' => 'Segunda à Sexta', '2' => 'Segunda à Sábado', '3' => 'Segunda à Domingo']" empty-option="Selecione..."/>
                                        </x-adminlte-select>
                                    </div>

                                    <div class="row">
                                        <x-adminlte-input name="descTurno" type="text" placeholder="Descrição do Turno" fgroup-class="col-md-12">
                                            <x-slot name="label">
                                                Descrição <span style="color:red;">*</span>
                                            </x-slot>
                                        </x-adminlte-input>
                                    </div>

                                    <div class="row">
                                        @php
                                            $horaIniIntDom = Helper::formataHoraMinuto($parametrosEmp[0]->parger_hr_ini_int_dom);

                                            $config = Helper::dtRangeHoraPtBR();
                                        @endphp
                                        <x-adminlte-date-range name="horaInicioTurno" :config="$config" placeholder="Formato Hora:Minuto" fgroup-class="col-md-6">
                                            <x-slot name="label">
                                                Hora Ini. Turno <span style="color:red;">*</span>
                                            </x-slot>
                                            <x-slot name="appendSlot">
                                                <div class="input-group-text">
                                                    <i class="far fa-lg fa-clock"></i>
                                                </div>
                                            </x-slot>
                                        </x-adminlte-date-range>

                                        @php
                                            $horaIniIntDom = Helper::formataHoraMinuto($parametrosEmp[0]->parger_hr_ini_int_dom);

                                            $config = Helper::dtRangeHoraPtBR();
                                        @endphp
                                        <x-adminlte-date-range name="horaFinalTurno" :config="$config" placeholder="Formato Hora:Minuto" fgroup-class="col-md-6">
                                            <x-slot name="label">
                                                Hora Fin. Turno <span style="color:red;">*</span>
                                            </x-slot>
                                            <x-slot name="appendSlot">
                                                <div class="input-group-text">
                                                    <i class="far fa-lg fa-clock"></i>
                                                </div>
                                            </x-slot>
                                        </x-adminlte-date-range>
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
                            @if($parametrosEmp[0]->parger_tur_srv == 'S')
                            <x-adminlte-button label="Novo Turno" data-toggle="modal" data-target="#modalCustom" class="btn-nexus" icon="fa-solid fa-briefcase"/>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="d-flex justify-content-between w-100">
                <x-adminlte-button type="button" onclick="window.location='{{ route('home.parametrosGerEmp') }}'" label="Voltar" theme="" class="btn-nexus" icon=""/>
            </div>
        </div>
    </div>
</div>
@stop

<!-- Chamada dos Plugins usados na app -->
@section('plugins.jqueryValidation', true)
@section('plugins.Sweetalert2', true)
@section('plugins.toastr', true)
@section('plugins.Select2', true)
@section('plugins.DateRangePicker', true)

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

        $("#empresa").attr("disabled", true);

        var diaFun = {!! json_encode($parametrosEmp[0]->parger_dia_fun) !!};

        if(diaFun == 1){
            $(".hora-sabado").hide();
            $(".hora-domingo").hide();
        }else if(diaFun == 2){
            $(".hora-sabado").show();
            $(".hora-domingo").hide();
        }else{
            $(".hora-sabado").show();
            $(".hora-domingo").show();
        }

        //Esconde calendário de data
        $(function() { 
            $('#horaIniFun').on('showCalendar.daterangepicker', function(ev, picker) {

                $('.calendar-table').hide();

            }); 

            $('#horaFinFun').on('showCalendar.daterangepicker', function(ev, picker) {

                $('.calendar-table').hide();

            }); 

            $('#horaIniInt').on('showCalendar.daterangepicker', function(ev, picker) {

                $('.calendar-table').hide();

            }); 
            
            $('#horaFinInt').on('showCalendar.daterangepicker', function(ev, picker) {

                $('.calendar-table').hide();

            }); 
            
            $('#horaIniSab').on('showCalendar.daterangepicker', function(ev, picker) {

                $('.calendar-table').hide();

            }); 
            
            $('#horaFinSab').on('showCalendar.daterangepicker', function(ev, picker) {

                $('.calendar-table').hide();

            }); 
            
            $('#horaIniIntSab').on('showCalendar.daterangepicker', function(ev, picker) {

                $('.calendar-table').hide();

            }); 

            $('#horaFinIntSab').on('showCalendar.daterangepicker', function(ev, picker) {

                $('.calendar-table').hide();

            }); 
            
            $('#horaIniDom').on('showCalendar.daterangepicker', function(ev, picker) {

                $('.calendar-table').hide();

            }); 

            $('#horaFinDom').on('showCalendar.daterangepicker', function(ev, picker) {

                $('.calendar-table').hide();

            }); 

            $('#horaIniIntDom').on('showCalendar.daterangepicker', function(ev, picker) {

                $('.calendar-table').hide();

            }); 

            $('#horaFinIntDom').on('showCalendar.daterangepicker', function(ev, picker) {

                $('.calendar-table').hide();

            }); 

            $('#horaInicioTurno').on('showCalendar.daterangepicker', function(ev, picker) {

                $('.calendar-table').hide();

            }); 

            $('#horaFinalTurno').on('showCalendar.daterangepicker', function(ev, picker) {

                $('.calendar-table').hide();

            }); 
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

        //Evento ao trocar o valor do campo tipo da tarefa
        $("#diaFuncionamento").change(function(){
            if(this.value == 1){
                $(".hora-sabado").hide();
                $(".hora-domingo").hide();
                $('#horaIniSab').val('00:00');
                $('#horaFinSab').val('00:00');
                $('#horaIniIntSab').val('00:00');
                $('#horaFinIntSab').val('00:00');
                $('#horaIniDom').val('00:00');
                $('#horaFinDom').val('00:00');
                $('#horaIniIntDom').val('00:00');
                $('#horaFinIntDom').val('00:00');
                $('#usaIntSab').val('N');
                $('#usaIntDom').val('N');
                $('#hrAltSab').val('N');
                $('#hrAltDom').val('N');
            }else if(this.value == 2){
                $(".hora-sabado").show();
                $(".hora-domingo").hide();
                $('#horaIniDom').val('00:00');
                $('#horaFinDom').val('00:00');
                $('#horaIniIntDom').val('00:00');
                $('#horaFinIntDom').val('00:00');
                $('#usaIntDom').val('N');
                $('#hrAltDom').val('N');
            }else{
                $(".hora-sabado").show();
                $(".hora-domingo").show();
            }
        });
    });
</script>

<!--
|--------------------------------------------------------------------------
| Eventos Validate da app
|--------------------------------------------------------------------------
-->
<script>
$(function () {

    $('#formulario-horario-funcionamento').validate({
        rules: {
            empresa: {
                required: true
            },
            horaIniFun: {
                required: true
            },
            horaFinFun: {
                required: true
            },
            horaIniInt: {
                required: function(element) {
                    let usaInt = $('#usaInt').val();
                    return usaInt === 'S';
                }
            },
            horaFinInt: {
                required: function(element) {
                    let usaInt = $('#usaInt').val();
                    return usaInt === 'S';
                }
            },
            horaIniSab: {
                required: function(element) {
                    let diaFun = $('#diaFuncionamento').val();
                    let altSab = $('#hrAltSab').val();
                    return (diaFun == 2 || diaFun == 3) && altSab == 'S';
                }
            },
            horaFinSab: {
                required: function(element) {
                    let diaFun = $('#diaFuncionamento').val();
                    let altSab = $('#hrAltSab').val();
                    return (diaFun == 2 || diaFun == 3) && altSab == 'S';
                }
            },
            horaIniIntSab: {
                required: function(element) {
                    let usaIntSab = $('#usaIntSab').val();
                    let diaFun = $('#diaFuncionamento').val();
                    return usaIntSab === 'S' && (diaFun == 2 || diaFun == 3);
                }
            },
            horaFinIntSab: {
                required: function(element) {
                    let usaIntSab = $('#usaIntSab').val();
                    let diaFun = $('#diaFuncionamento').val();
                    return usaIntSab === 'S' && (diaFun == 2 || diaFun == 3);
                }
            },
            horaIniDom: {
                required: function(element) {
                    let diaFun = $('#diaFuncionamento').val();
                    let altDom = $('#hrAltDom').val();
                    return diaFun == 3 && altDom == 'S';
                }
            },
            horaFinDom: {
                required: function(element) {
                    let diaFun = $('#diaFuncionamento').val();
                    let altDom = $('#hrAltDom').val();
                    return diaFun == 3 && altDom == 'S';
                }
            },
            horaIniIntDom: {
                required: function(element) {
                    let usaIntDom = $('#usaIntDom').val();
                    let diaFun = $('#diaFuncionamento').val();
                    return usaIntDom === 'S' && diaFun == 3;
                }
            },
            horaFinIntDom: {
                required: function(element) {
                    let usaIntDom = $('#usaIntDom').val();
                    let diaFun = $('#diaFuncionamento').val();
                    return usaIntDom === 'S' && diaFun == 3;
                }
            },
        },
        messages: {
            empresa: {
                required: "Por Favor informe a Empresa"
            },
            horaIniFun: {
                required: "Por Favor informe a hora de inicio do Funcionamento"
            },
            horaFinFun: {
                required: "Por Favor informe a hora do final do Funcionamento"
            },
            horaIniInt: {
                required: "Por Favor informe a hora do inicio do intervalo"
            },
            horaFinInt: {
                required: "Por Favor informe a hora do final do intervalo"
            },
            horaIniSab: {
                required: "Por Favor informe a hora do inicio do sábado"
            },
            horaFinSab: {
                required: "Por Favor informe a hora do final do sabádo"
            },
            horaIniIntSab: {
                required: "Por Favor informe a hora do inicio de intervalo do sábado"
            },
            horaFinIntSab: {
                required: "Por Favor informe a hora do final de intervalo do sábado"
            },
            horaIniDom: {
                required: "Por Favor informe a hora do inicio do domingo"
            },
            horaFinDom: {
                required: "Por Favor informe a hora do final do domingo"
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

    $('#formulario-turno').validate({
        rules: {
            diaTurno: {
                required: true
            },
            descTurno: {
                required: true,
                maxlength: 80
            },
            horaInicioTurno: {
                required: true
            },
            horaFinalTurno: {
                required: true
            },
        },
        messages: {
            diaTurno: {
                required: "Por Favor informe os Dias do Turno"
            },
            descTurno: {
                required: "Por Favor informe a Descrição do Turno",
                maxlength: "Informe no máximo 80 caracteres"
            },
            horaInicioTurno: {
                required: "Por Favor informe a Hora Ini. Turno "
            },
            horaFinalTurno: {
                required: "Por Favor informe a Hora Fin. Turno "
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
