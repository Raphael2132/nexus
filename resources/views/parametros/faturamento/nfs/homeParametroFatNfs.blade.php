@extends('adminlte::page')

@section('title', 'Parâmetros da NFS-e')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h4 style="margin-bottom: 0px !important;">Parâmetros Gerais</h4>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active">Parâmetros da NFS-e</li>
        </ol>
    </div>
</div>
@stop

@section('content')
<div class="row">
    <div class="esquerdo col-md-9">
        {{-- Setup data for datatables --}}
        @php
        $heads = [
            'Empresa',
            'Gera NFS-e',
            'Provedor',
            ['label' => 'Editar', 'no-export' => true, 'width' => 5],
        ];

        $config = [
            'searching' => false,
            'lengthChange' => false,
            'pageLength' => 5,
            'language' => Helper::dataTableLangPtBR(),
            'order' => [[0, 'asc']],
            'columns' => [null, null, null, ['orderable' => false]],
        ];
        @endphp
        <x-adminlte-card title="Parâmetros da Emissão de NFS-e" theme="" theme-mode="" header-class="card-nexus" collapsible maximizable>
            <x-adminlte-datatable id="table1" :heads="$heads" :config="$config" theme="light" striped hoverable>
                @foreach ($emiNfs as $nfs)
                    @php
                        $data_emp = DB::table('cadastro_empresas')->where('empresa_codigo','=',$nfs->parnfs_empresa)->get();
                        $empresa = $nfs->parnfs_empresa.' - '.$data_emp[0]->empresa_nome;

                        if(!empty($nfs->parnfs_provedor)){
                            $data_prov = DB::table('parametros_fat_nfs_provedores')->where('provedor_codigo', $nfs->parnfs_provedor)->get();
                            $provedor = $nfs->parnfs_provedor.' - '.$data_prov[0]->provedor_desc;
                        }else{
                            $provedor = 'Não Cadastrado';
                        }

                        if($nfs->parnfs_utiliza_nfs == 'S'){
                            $utilizaNFS = 'Sim';
                        }else{
                            $utilizaNFS = 'Não';
                        }
                    @endphp
                    <tr>
                        <td>{{ $empresa }}</td>
                        <td>{{ $utilizaNFS }}</td>
                        <td>{{ $provedor }}</td>
                        <td>
                            <nobr>
                                <form method="get" action="{{ route('parmetrosNfsEmi.editarCadastro', ['dadosEmissao' => $nfs->parnfs_empresa, 'appOrigem' => 'homeParametrosFatNfs']) }}" style="float: left;">
                                    @csrf
                                    <button class="btn btn-xs btn-default text-primary mx-1 shadow" title="Edit" value="Edit" type="submit">
                                        <i class="fa fa-lg fa-fw fa-pen"></i>
                                    </button>
                                </form>
                            </nobr>
                        </td>                
                    </tr>
                @endforeach
            </x-adminlte-datatable>
        </x-adminlte-card>

        {{-- Setup data for datatables --}}
        @php
        $heads2 = [
            'Empresa',
            'Provedor',
            'Ambiente',
            ['label' => 'Editar', 'no-export' => true, 'width' => 5],
        ];

        $config2 = [
            'searching' => false,
            'lengthChange' => false,
            'pageLength' => 5,
            'language' => Helper::dataTableLangPtBR(),
            'order' => [[0, 'asc']],
            'columns' => [null, null, null, ['orderable' => false]],
        ];
        @endphp
        <x-adminlte-card title="Parâmetros de Conexões da NFS-e" theme="" theme-mode="" header-class="card-nexus" collapsible maximizable>
            <x-adminlte-datatable id="table2" :heads="$heads2" :config="$config2" theme="light" striped hoverable>
                @foreach ($conNfs as $nfsCon)
                    @php
                        $data_emp2 = DB::table('cadastro_empresas')->where('empresa_codigo','=',$nfsCon->conexao_empresa)->get();
                        $empresa2 = $nfsCon->conexao_empresa.' - '.$data_emp2[0]->empresa_nome;

                        if(!empty($nfsCon->conexao_provedor)){
                            $data_prov2 = DB::table('parametros_fat_nfs_provedores')->where('provedor_codigo','=',$nfsCon->conexao_provedor)->get();
                            $provedor2 = $nfsCon->conexao_provedor.' - '.$data_prov2[0]->provedor_desc;
                        }else{
                            $provedor2 = 'Não Cadastrado';
                        }
                        if($nfsCon->conexao_ambiente == 'H'){
                            $ambiente = 'Homologação';
                        }else{
                            $ambiente = 'Produção';
                        }
                    @endphp
                    <tr>
                        <td>{{ $empresa2 }}</td>
                        <td>{{ $provedor2 }}</td>
                        <td>{{ $ambiente }}</td>
                        <td>
                            <nobr>
                                <form method="get" action="{{ route('parmetrosNfsCon.editarCadastro', ['dadosConexao' => $nfsCon->conexao_empresa, 'appOrigem' => 'homeParametrosFatNfs']) }}" style="float: left;">
                                    @csrf
                                    <button class="btn btn-xs btn-default text-primary mx-1 shadow" title="Edit" value="Edit" type="submit">
                                        <i class="fa fa-lg fa-fw fa-pen"></i>
                                    </button>
                                </form>
                            </nobr>
                        </td>                
                    </tr>
                @endforeach
            </x-adminlte-datatable>
        </x-adminlte-card>
    </div>
    <div class="direito col-md-3">
        <x-adminlte-small-box title="NFS-e" text="Emissão" icon="fas fa-file-export" theme="primary" url="{{ route('parametrosNfsEmissao') }}" url-text="Detalhes"/>
        <x-adminlte-small-box title="NFS-e" text="Conexão" icon="fas fa-wifi" theme="success" url="{{ route('parametrosNfsConexao') }}" url-text="Detalhes"/>
        <x-adminlte-small-box title="NFS-e" text="Provedor" icon="fas fa-server" theme="danger" url="{{ route('parametrosNfsProvedor') }}" url-text="Cadastrar"/>
    </div>
</div>
@stop

@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)

@section('css')
<style>
    .esquerdo {
        float: left;
    }

    .direito {
        float: right;
    }
</style>
@stop

@section('js')
@stop
