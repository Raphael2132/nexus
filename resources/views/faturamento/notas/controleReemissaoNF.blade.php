@extends('adminlte::page')

@section('title', 'Reemissão de NF')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h4 style="margin-bottom: 0px !important;">Faturamento de Notas</h4>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active">Filtro Reemissão de NF</li>
        </ol>
    </div>
</div>
@stop

@section('content')
<div class="d-flex justify-content-center">
    <div class="col-md-8">
        <form method="get" action="{{route('reemissaoNF.consultaReemissaoNF')}}" id="quickForm" novalidate="novalidate">
        @csrf 
        @method('get')
            <x-adminlte-card title="Filtro de Reemissão de NF-e / NFS-e" theme="" theme-mode="" header-class="card-nexus" collapsible maximizable>
                @php
                    $data = DB::table('cadastro_empresas')->select('empresa_codigo', 'empresa_nome')->orderBy('empresa_codigo', 'asc')->get();

                    $new_array1 =[];
                    $new_array2 =[];

                    foreach ($data as $empresa) {
                        $new_array1[] = $empresa->empresa_codigo;
                        $new_array2[] = $empresa->empresa_codigo.' - '.$empresa->empresa_nome;
                    }
                    $emp_sel = '';
                    $array_opt = array_combine($new_array1, $new_array2);

                    //Faz o lookup do campo de clientes 
                    $data_cli = DB::table('cadastro_clientes')->selectRaw('cliente_codigo, cliente_nome')->orderBy('cliente_codigo', 'asc')->get();
                    $html = '<datalist id="clientes">';
                    foreach($data_cli as $cliente){
                        $html .= '<option value="'.$cliente->cliente_codigo.' - '.$cliente->cliente_nome.'">'.$cliente->cliente_codigo.' - '.$cliente->cliente_nome.'</option>';
                    }
                    $html .='</datalist>';
                    //Echo adiciona o html ao campo dos clientes
                    echo $html;
                @endphp
                <div class="row"> 
                    <!-- Empresa do Setor -->
                    <x-adminlte-select name="empresa" label="Empresa" fgroup-class="col-md-12">
                        <x-adminlte-options :options="$array_opt" empty-option="Selecione..." selected="{{$emp_sel}}"/>
                    </x-adminlte-select>
                </div>
                <div class="row">
                    <!-- Cliente da NF -->
                    <x-adminlte-input name="cliente" label="Cliente" type="search" list="clientes" value="" fgroup-class="col-md-12"/>
                </div>
                <div class="row">
                    <!-- Número pedido / OS -->
                    <x-adminlte-input name="numOS" label="Número do Pedido / OS" type="text" value="" fgroup-class="col-md-6"/>
                </div>
                <div class="row">
                    @php
                        $config = Helper::dtRangeDataPtBR();
                    @endphp
                    <!-- Data de Pedido / OS -->
                    <x-adminlte-date-range name="dtIniOS" label="Data Inicial do Pedido / OS" :config="$config" placeholder="de dia/mês/ano" fgroup-class="col-md-6">
                        <x-slot name="prependSlot">
                        <div class="input-group-text x-slot-nexus">
                                <i class="far fa-lg fa-calendar-alt"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-date-range>
                    <x-adminlte-date-range name="dtFinOS" label="Data Final do Pedido / OS" :config="$config" placeholder="até dia/mês/ano" fgroup-class="col-md-6">
                        <x-slot name="prependSlot">
                        <div class="input-group-text x-slot-nexus">
                                <i class="far fa-lg fa-calendar-alt"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-date-range>
                </div>
                <div class="row">
                    <!-- Valor Pedido / OS -->
                    <x-adminlte-input name="vlrIniOS" label="Valor Inicial do Pedido / OS" type="text" value="" placeholder="de 0,00" fgroup-class="col-md-6">
                        <x-slot name="prependSlot">
                            <div class="input-group-text x-slot-nexus">
                                <i class="fa-solid fa-brazilian-real-sign"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>
                    <x-adminlte-input name="vlrFinOS" label="Valor Final do Pedido / OS" type="text" value="" placeholder="até 0,00" fgroup-class="col-md-6">
                        <x-slot name="prependSlot">
                            <div class="input-group-text x-slot-nexus">
                                <i class="fa-solid fa-brazilian-real-sign"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>
                </div>
                <x-slot name="footerSlot">
                    <x-adminlte-button class="btn-nexus" type="submit" label="Pesquisar" theme="" icon="fa-solid fa-magnifying-glass"/>
                </x-slot>
            </x-adminlte-card>
        </form>
    </div>
</div>
@stop

<!-- Chamada dos Plugins usados na app -->
@section('plugins.Sweetalert2', true)
@section('plugins.jqueryValidation', true)
@section('plugins.DateRangePicker', true)

@section('css')
@stop

@section('js')
<script src="https://igorescobar.github.io/jQuery-Mask-Plugin/js/jquery.mask.min.js"></script> 

<!--
|--------------------------------------------------------------------------
| Eventos Inicial da app
|--------------------------------------------------------------------------
-->

<script>
    $(document).ready(function() {

        //Mascaras de campos float
        $('#vlrIniOS').mask('#.##0,00', {reverse: true});
        $('#vlrFinOS').mask('#.##0,00', {reverse: true});        
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

    @if(Session::has('info'))
        Swal.fire({
            confirmButtonColor: "#007bff",
            title: "Aviso!",
            text: "{{ session('info') }}",
            icon: "info",
            customClass: {
                icon: "no-before-icon",
            }
        });
    @endif

    @if(Session::has('success2'))
        Swal.fire({
            confirmButtonColor: "#007bff",
            title: "Sucesso!",
            text: "{{ session('success2') }}",
            icon: "success",
            customClass: {
                icon: "no-before-icon",
            }
        });
    @endif
</script>
@stop
