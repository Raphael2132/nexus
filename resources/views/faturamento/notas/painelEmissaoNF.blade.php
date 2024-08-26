@extends('adminlte::page')

@section('title', 'Painel Emissão de NF')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h4 style="margin-bottom: 0px !important;">Faturamento de Notas</h4>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active">
                <a href="{{route('home.emissaoNF')}}">Filtro Emissão de NF</a>
            </li>
            <li class="breadcrumb-item active">
                <a href="{{ route('emissaoNF.consultaNF') }}">Consulta Emissão de NF</a>
            </li>
            <li class="breadcrumb-item active">Painel de Emissãode de NF</li>
        </ol>
    </div>
</div>
@stop


@section('content')

<div class="col-md-12">

    <!-- ********** Painel Principal da Emissão de NF ********** -->
    <x-adminlte-card title="Painel de Emissão de Notas Fiscais" theme="" theme-mode="" header-class="card-nexus" collapsible maximizable>
        @php 
            $data = DB::table('cadastro_empresas')->where('empresa_codigo', $empresaNF)->get();

            $data_cli = DB::table('cadastro_clientes')->where('cliente_codigo', $clienteNF)->get();
        @endphp
        <div class="row">
            <div style="width:100%; margin: 10px;">
                <table style="width:100%; font-style: normal; color:#343232; border-collapse: separate; border-spacing: 5px 5px;">
                    <tbody>
                        <tr style="text-align: center;">
                            <td style="background-color: #DCDCDC; border-radius: 8px; border: 1px solid #C0C0C0;"><strong>Empresa</strong></td>
                            <td style="background-color: #DCDCDC; border-radius: 8px; border: 1px solid #C0C0C0;"><strong>Cliente</strong></td>
                        </tr>
                        <tr style="border: 1px solid #DDD; background-color: #fff; text-align: center;">
                            <td style="border: 1px solid #C0C0C0; border-radius: 8px; text-align: center;">{{$empresaNF.' - '.$data[0]->empresa_nome}}</td>
                            <td style="border: 1px solid #C0C0C0; border-radius: 8px; text-align: center;">{{$clienteNF.' - '.$data_cli[0]->cliente_nome}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Posiciona os blocos do lado esquerdo e direito na mesma linha -->
        <div class="row">

            <!-- ************************************************** Bloco do Lado Esquerdo do Painel Principal ************************************************** -->
            <div class="col-md-6">
                @php
                // Monta os dados da tabela do bloco
                $heads = [
                    ['label' => '', 'no-export' => true, 'width' => 10],
                    'Pedido / OS',
                    'Data',
                    'Origem',
                    'Tipo NF',
                    'Valor'
                ];
                
                $config = [
                    'searching' => false,
                    'lengthChange' => false,
                    'pageLength' => 10,
                    'language' => Helper::dataTableLangPtBR(),
                    'order' => [[1, 'asc']],
                    'columns' => [['orderable' => false], ['orderable' => false], ['orderable' => false], ['orderable' => false], ['orderable' => false], ['orderable' => false]],
                ];
                @endphp
                <x-adminlte-card title="Lista de Notas Disponíveis" theme="" theme-mode="outline" header-class="card-outline-nexus" collapsible maximizable>
                    <x-adminlte-datatable id="tabelaGeral" :heads="$heads" :config="$config" theme="light" striped hoverable>
                        @foreach($dadosHeader as $header)
                            @php 
                                $data = DB::table('cadastro_empresas')->where('empresa_codigo', $header->nfhdr_emp)->get();

                                $data_cli = DB::table('cadastro_clientes')->where('cliente_codigo', $header->nfhdr_cli)->get();

                                if($header->nfhdr_tor == 'S'){
                                    $origem = "Serviços";
                                }else{
                                    $origem = "Peças";
                                }
                            @endphp
                            <tr>
                                <td>
                                    <nobr class="d-flex justify-content-center">
                                        @if($nfSelecionada != $header->nfhdr_num)
                                        <a class="btn btn-nexus btn-sm" title="Selecionar NF" href="{{route('emissaoNF.painelNF',['empresa' => $header->nfhdr_emp, 'cliente' => $header->nfhdr_cli, 'nfSelecionada' => $header->nfhdr_num])}}">Selecionar</a>
                                        @else
                                        <a class="text-muted" title="NF Selecionada" href="">
                                            <i class="fa-solid fa-circle-check fa-lg text-success"></i>
                                        </a>
                                        @endif
                                    </nobr>
                                </td>
                                <td>{{$header->nfhdr_num_ped}}</td>
                                <td>{{Helper::formataData($header->nfhdr_dt_ped)}}</td>
                                <td>{{$origem}}</td>
                                <td>NFS-e</td>
                                <td>{{Helper::formataValorMonetario($header->nfhdr_vlr_tot_nf)}}</td>
                            </tr>
                        @endforeach
                    </x-adminlte-datatable>
                </x-adminlte-card>
            </div>

            <!-- ************************************************** Bloco do Lado Direito do Painel Principal ************************************************** -->
            <div class="col-md-6">
                <x-adminlte-card title="Geração de Notas" theme="" theme-mode="outline" header-class="card-outline-nexus" collapsible maximizable>
                    @if(!empty(trim($nfSelecionada)))
                    <div style="text-align: center">
                        <a class="btn btn-nexus" title="Gerar NF-e / NFS-e" href="{{route('emissaoNF.gerarNF',['empresa' => $header->nfhdr_emp, 'cliente' => $header->nfhdr_cli, 'nfSelecionada' => $nfSelecionada, 'origem' => 'EMISSAO'])}}">Gerar NF-e / NFS-e</a>
                    </div>
                    @endif
                </x-adminlte-card>
            </div>

        </div>
        <x-slot name="footerSlot">
            <div style="float: right;">
                <x-adminlte-button class="btn-nexus" type="button" onclick="window.location='{{ route('emissaoNF.consultaNF') }}'" label="Voltar" theme="" icon=""/>
            </div>
        </x-slot>
    </x-adminlte-card>

</div>
@stop

<!-- Chamada dos Plugins usados na app -->
@section('plugins.Sweetalert2', true)
@section('plugins.jqueryValidation', true)
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)

@section('css')
@stop

@section('js')
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

