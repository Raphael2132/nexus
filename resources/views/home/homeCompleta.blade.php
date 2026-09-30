@php
    $headsOS = [
        'OS',
        'OS / Data',
        'Empresa',
        'Cliente',
        'Situação',
        'Valor',
    ];

    $headsNFS = [
        'Data Oculto',
        'Hora Oculto',
        'Data',
        'Ped. / OS',
        'RPS / Série',
        'Empresa',
        'Cliente',
        'Situação',
        'Valor',
    ];

    $headsNFSSimp = [
        'Data Oculto',
        'Hora Oculto',
        'Data',
        'ES',
        'RPS / Série',
        'Empresa',
        'Cliente',
        'Situação',
        'Valor',
    ];

    //Vamos definir a empresa da visualização da Home
    $empresa = session('glo_empresa_exibicao_home');

    /*
    |--------------------------------------------------------------------------
    | Gráfico
    |--------------------------------------------------------------------------
    |
    | Variaveis destinadas aos Gráficos.
    |
    */

    /* *************** Gráfico das NFS-e Geradas na última semana *************** */
    //Data da semana atual
    $dt1 = date('Y-m-d');
    $dt2 = date('Y-m-d', strtotime('-6 days'));

    //Data da semana passada
    $dt3 = date('Y-m-d', strtotime('-7 days'));
    $dt4 = date('Y-m-d', strtotime('-14 days'));

    $qtdNFSfin = DB::table('faturamento_nfs')->where('nfs_emp', $empresa)->where('nfs_sts', 'G')->whereBetween('nfs_dt_emi', [$dt2, $dt1])->count();
    $qtdNFSini = DB::table('faturamento_nfs')->where('nfs_emp', $empresa)->where('nfs_sts', 'G')->whereBetween('nfs_dt_emi', [$dt4, $dt3])->count();

    if($qtdNFSini > $qtdNFSfin){
        if(!empty($qtdNFSfin)){
            $perNfsSemana = (($qtdNFSfin - $qtdNFSini) / $qtdNFSfin) * 100;
        }else{
            $perNfsSemana = -100;
        }
    }else{
        if(!empty($qtdNFSini)){
            $perNfsSemana = (($qtdNFSfin - $qtdNFSini) / $qtdNFSini) * 100;
        }else{
            $perNfsSemana = 0;
        }
    }

    //Seta a data para português
    setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese'); 
    //date_default_timezone_set('America/Sao_Paulo');

    $diasSemana = "[";
    $qtdSemAtu = "[";
    $qtdSemPas = "[";

    $semAtual = 6;
    $semPassada = 13;

    for($i = 1; $i < 8; $i++){

        $dtAtu = date('Y-m-d', strtotime('-'.$semAtual.' days'));
        $dtPas = date('Y-m-d', strtotime('-'.$semPassada.' days'));

        //$diaSem = utf8_encode(ucfirst(strftime("%A", strtotime($dtAtu))));
        $diaSem = ucfirst(strftime("%A", strtotime($dtAtu)));

        $nfsSemAtu = DB::table('faturamento_nfs')->where('nfs_emp', $empresa)->where('nfs_sts', 'G')->where('nfs_dt_emi', $dtAtu)->count();
        $nfsSemPas = DB::table('faturamento_nfs')->where('nfs_emp', $empresa)->where('nfs_sts', 'G')->where('nfs_dt_emi', $dtPas)->count();

        if($semAtual == 0){
            $diasSemana .= "'".$diaSem."'";
            $qtdSemAtu .= "'".$nfsSemAtu."'";
            $qtdSemPas .= "'".$nfsSemPas."'";
        }else{
            $diasSemana .= "'".$diaSem."',";
            $qtdSemAtu .= "'".$nfsSemAtu."',";
            $qtdSemPas .= "'".$nfsSemPas."',";
        }

        $semAtual -= 1;
        $semPassada -= 1;
    }

    $diasSemana .= "]";
    $qtdSemAtu .= "]";
    $qtdSemPas .= "]";
    /* *************** Final das variaveis do gráfico das NFS-e Geradas na última semana *************** */
    
    /*
    |--------------------------------------------------------------------------
    | Quadro Geral
    |--------------------------------------------------------------------------
    |
    | Variaveis destinadas aos Quadros Gerais no Mês.
    |
    */
    
    /* *************** Resumo Geral da Empresa no Mês *************** */
    $dtAtuFin = date('Y-m-d');
    $dtAtuIni = date('Y-m-01');

    $dtPasIni = date('Y-m-01', strtotime("-1 month"));
    $dtPasFin = date("Y-m-t", strtotime("-1 month"));

    $vlrNfsAtu = DB::table('faturamento_nfs')->where('nfs_emp', $empresa)->where('nfs_sts', 'G')->whereBetween('nfs_dt_emi', [$dtAtuIni, $dtAtuFin])->sum('nfs_vlr_tot');
    $vlrNfsPas = DB::table('faturamento_nfs')->where('nfs_emp', $empresa)->where('nfs_sts', 'G')->whereBetween('nfs_dt_emi', [$dtPasIni, $dtPasFin])->sum('nfs_vlr_tot');

    if($vlrNfsPas > $vlrNfsAtu){
        if(!empty($vlrNfsAtu)){
            $perVlrNfsMes = (($vlrNfsAtu - $vlrNfsPas) / $vlrNfsAtu) * 100;
        }else{
            $perVlrNfsMes = -100;
        }
    }else{
        if(!empty($vlrNfsPas)){
            $perVlrNfsMes = (($vlrNfsAtu - $vlrNfsPas) / $vlrNfsPas) * 100;
        }else{
            $perVlrNfsMes = 0;
        }
    }

    $qtdOsAtu = DB::table('lancamento_srv_os')->where('os_emp', $empresa)->where('os_sts', 'F')->whereBetween('os_dha', [$dtAtuIni.' 00:00:00', $dtAtuFin.' 23:59:59'])->count();
    $qtdOsPas = DB::table('lancamento_srv_os')->where('os_emp', $empresa)->where('os_sts', 'F')->whereBetween('os_dha', [$dtPasIni.' 00:00:00', $dtPasFin.' 23:59:59'])->count();

    if($qtdOsPas > $qtdOsAtu){
        if(!empty($qtdOsAtu)){
            $qtdOsMes = (($qtdOsAtu - $qtdOsPas) / $qtdOsAtu) * 100;
        }else{
            $qtdOsMes = -100;
        }
    }else{
        if(!empty($qtdOsPas)){
            $qtdOsMes = (($qtdOsAtu - $qtdOsPas) / $qtdOsPas) * 100;
        }else{
            $qtdOsMes = 0;
        }
    }

    $qtdNfsAtu = DB::table('faturamento_nfs')->where('nfs_emp', $empresa)->where('nfs_sts', 'G')->whereBetween('nfs_dt_emi', [$dtAtuIni, $dtAtuFin])->count();
    $qtdNfsPas = DB::table('faturamento_nfs')->where('nfs_emp', $empresa)->where('nfs_sts', 'G')->whereBetween('nfs_dt_emi', [$dtPasIni, $dtPasFin])->count();

    if($qtdNfsPas > $qtdNfsAtu){
        if(!empty($qtdNfsAtu)){
            $qtdNfsMes = (($qtdNfsAtu - $qtdNfsPas) / $qtdNfsAtu) * 100;
        }else{
            $qtdNfsMes = -100;
        }
    }else{
        if(!empty($qtdNfsPas)){
            $qtdNfsMes = (($qtdNfsAtu - $qtdNfsPas) / $qtdNfsPas) * 100;
        }else{
            $qtdNfsMes = 0;
        }
    }

    $qtdCliAtu = DB::table('cadastro_clientes')->whereBetween('cliente_dt_inc', [$dtAtuIni, $dtAtuFin])->count();
    $qtdCliPas = DB::table('cadastro_clientes')->whereBetween('cliente_dt_inc', [$dtPasIni, $dtPasFin])->count();

    if($qtdCliPas > $qtdCliAtu){
        if(!empty($qtdCliAtu)){
            $qtdCliMes = (($qtdCliAtu - $qtdCliPas) / $qtdCliAtu) * 100;
        }else{
            $qtdCliMes = -100;
        }
    }else{
        if(!empty($qtdCliPas)){
            $qtdCliMes = (($qtdCliAtu - $qtdCliPas) / $qtdCliPas) * 100;
        }else{
            $qtdCliMes = 0;
        }
    }
    /* *************** Final das variaveis do Resumo Geral da Empresa no Mês *************** */
@endphp

<div class="col-md-12">
    <div class="row">
        <div class="col-md-8">
            <x-adminlte-card title="Relatório de NFS-e Geradas na Última Semana" icon="fa-solid fa-chart-line" theme="" theme-mode="outline" header-class="card-outline-nexus" collapsible maximizable>
                <div class="d-flex">
                    <p class="d-flex flex-column">
                        <span class="text-bold text-lg">{{$qtdNFSfin}}</span>
                        <span>Geradas essa semana</span>
                    </p>
                    <p class="ml-auto d-flex flex-column text-right">
                        @if($perNfsSemana > 0)<span class="text-success"><i class="fas fa-arrow-up"></i>
                        @elseif($perNfsSemana < 0)<span class="text-danger"><i class="fas fa-arrow-down"></i>@php $perNfsSemana *= -1;@endphp
                        @else<span class="text-warning"><i class="fas fa-square fa-2xs"></i>
                        @endif
                        {{Helper::formataPorcentagem($perNfsSemana)}}%
                        </span>
                        <span class="text-muted">Desde a semana passada</span>
                    </p>
                </div>

                <div class="chart">
                    <canvas id="visitors-chart" height="140" width="639" style="display: block; width: 639px; height: 140px;" class="chartjs-render-monitor"></canvas>
                </div>
                <div class="d-flex flex-row justify-content-end">
                    <span class="mr-2">
                        <i class="fas fa-square text-primary"></i> Esta Semana
                    </span>
                    <span>
                        <i class="fas fa-square text-gray"></i> Semana Passada
                    </span>
                </div>
            </x-adminlte-card>
            <x-adminlte-card title="Últimas OS Abertas" theme="" theme-mode="" header-class="card-nexus" collapsible maximizable>
                <x-adminlte-datatable id="table-os" :heads="$headsOS" theme="light" striped hoverable compressed>
                    @foreach($dadosOS as $os)
                        @php 
                            $dataEmp = DB::table('cadastro_empresas')->where('empresa_codigo', $os->os_emp)->get();
                            $dataCli = DB::table('cadastro_clientes')->where('cliente_codigo', $os->os_cli_fatura)->get();
                        @endphp
                        <tr>
                            <td>{{$os->os_nos}}</td>
                            <td><a href="{{route('situacaoOS.carregaOS', ['empresa' => $os->os_emp, 'cliente' => $os->os_cli, 'nos' => $os->os_nos, 'estagioAPP' => 'PRINCIPAL'])}}">{{$os->os_nos.' - '.Helper::formataDataHoraParaData($os->os_dha)}}</a></td>
                            <td>{{$os->os_emp.' - '.$dataEmp[0]->empresa_nome}}</td>
                            <td>{{$os->os_cli_fatura.' - '.$dataCli[0]->cliente_nome}}</td>
                            @if($os->os_sts == 'F')
                            <td><span class="badge badge-success">Finalizada</span></td>
                            @elseif($os->os_sts == 'C')
                            <td><span class="badge badge-danger">Cancelada</span></td>
                            @else
                            <td><span class="badge badge-info">Andamento</span></td>
                            @endif
                            <td style="text-align: right;">{{Helper::formataValorMonetario($os->os_vlt)}}</td>
                        </tr>
                    @endforeach
                </x-adminlte-datatable>

                @can('is_acessa_mod_servico')
                    @can('is_lancamento_os')
                    <x-slot name="footerSlot">
                        <form method="get" action="{{ route('home.emissaoOS') }}" style="float: left;">
                            <x-adminlte-button class="btn-nexus" label="Nova OS" theme="" icon="fa-solid fa-plus" type="submit"/>
                        </form>
                        <form method="get" action="{{ route('situacaoOS.consulta', ['statusOS' => 'T']) }}" style="float: right;">
                            <x-adminlte-button class="btn-nexus" label="Consultar OS" theme="" icon="fa-regular fa-eye" type="submit"/>
                        </form>
                    </x-slot>
                    @endcan
                @endcan
            </x-adminlte-card>
            <x-adminlte-card title="Últimas NFS-e Geradas" theme="" theme-mode="" header-class="card-nexus" collapsible maximizable>
                <x-adminlte-datatable id="table-nfs" :heads="$headsNFS" theme="light" striped hoverable compressed>
                    @foreach($dadosNFS as $nfs)
                        @php 
                            $dataEmp = DB::table('cadastro_empresas')->where('empresa_codigo', $nfs->nfs_emp)->get();
                        @endphp
                        <tr>
                            <td>{{$nfs->nfs_dt_emi}}</td>
                            <td>{{$nfs->nfs_hr_emi}}</td>
                            <td>{{Helper::formataData($nfs->nfs_dt_emi)}}</td>
                            <td>{{$nfs->nfs_nfhdr_num_ped}}</td>
                            <td>{{$nfs->nfs_nrps.'-'.$nfs->nfs_srps}}</td>
                            <td>{{$nfs->nfs_emp.' - '.$dataEmp[0]->empresa_nome}}</td>
                            <td>{{$nfs->nfs_cli.' - '.$nfs->nfs_nom_tom}}</td>
                            @if($nfs->nfs_sts == 'G')
                            <td><span class="badge badge-success">NFS-e Gerada</span></td>
                            @elseif($nfs->nfs_sts == 'C')
                            <td><span class="badge badge-danger">NFS-e Cancelada</span></td>
                            @elseif($nfs->nfs_sts == 'E')
                            <td><span class="badge badge-warning" style="color: #fff !important;">NFS-e Erro</span></td>
                            @else
                            <td><span class="badge badge-info">NFS-e Iniciada</span></td>
                            @endif
                            <td style="text-align: right;">{{Helper::formataValorMonetario($nfs->nfs_vlr_tot)}}</td>
                        </tr>
                    @endforeach
                </x-adminlte-datatable>
                @can('is_acessa_mod_nf')
                    @can('is_emissao_nf')
                    <x-slot name="footerSlot">
                        <form method="get" action="{{route('reemissaoNF.consultaReemissaoNF', ['appOrigem' => 'HOME_TOTAL'])}}" style="float: right;">
                            <x-adminlte-button class="btn-nexus" label="Consultar NFS-e" theme="" icon="fa-regular fa-eye" type="submit"/>
                        </form>
                    </x-slot>
                    @endcan
                @endcan
            </x-adminlte-card>
            <x-adminlte-card title="Últimas NFS-e Simplificadas Geradas" theme="" theme-mode="" header-class="card-nexus" collapsible maximizable>
                <x-adminlte-datatable id="table-nfs-simp" :heads="$headsNFSSimp" theme="light" striped hoverable compressed>
                    @foreach($dadosNFSSimp as $nfsSimp)
                        @php 
                            $dataEmp = DB::table('cadastro_empresas')->where('empresa_codigo', $nfsSimp->nfs_emp)->get();
                        @endphp
                        <tr>
                            <td>{{$nfsSimp->nfs_dt_emi}}</td>
                            <td>{{$nfsSimp->nfs_hr_emi}}</td>
                            <td>{{Helper::formataData($nfsSimp->nfs_dt_emi)}}</td>
                            <td>{{$nfsSimp->nfs_nfhdr_num_ped}}</td>
                            <td>{{$nfsSimp->nfs_nrps.'-'.$nfsSimp->nfs_srps}}</td>
                            <td>{{$nfsSimp->nfs_emp.' - '.$dataEmp[0]->empresa_nome}}</td>
                            <td>{{$nfsSimp->nfs_cli.' - '.$nfsSimp->nfs_nom_tom}}</td>
                            @if($nfsSimp->nfs_sts == 'G')
                            <td><span class="badge badge-success">NFS-e Gerada</span></td>
                            @elseif($nfsSimp->nfs_sts == 'C')
                            <td><span class="badge badge-danger">NFS-e Cancelada</span></td>
                            @elseif($nfsSimp->nfs_sts == 'E')
                            <td><span class="badge badge-warning" style="color: #fff !important;">NFS-e Erro</span></td>
                            @else
                            <td><span class="badge badge-info">NFS-e Iniciada</span></td>
                            @endif
                            <td style="text-align: right;">{{Helper::formataValorMonetario($nfsSimp->nfs_vlr_tot)}}</td>
                        </tr>
                    @endforeach
                </x-adminlte-datatable>
                @can('is_acessa_mod_nf')
                    @can('is_emissao_nf')
                    <x-slot name="footerSlot">
                        <form method="get" action="{{route('reemissaoSimpNF.consultaReemissaoSimpNF', ['appOrigem' => 'HOME_TOTAL'])}}" style="float: right;">
                            <x-adminlte-button class="btn-nexus" label="Consultar NFS-e" theme="" icon="fa-regular fa-eye" type="submit"/>
                        </form>
                    </x-slot>
                    @endcan
                @endcan
            </x-adminlte-card>
        </div>
        <div class="col-md-4"> 
            <x-adminlte-small-box :title="$cliMes" text="Novos Clientes" icon="fas fa-user" theme="gradient-lightblue" url="{{ route('cadastroCliente.show',['cadastroCliente' => 'M']) }}" url-text="Novos Clientes no Mês"/>
            <x-adminlte-small-box :title="$osMes" text="OS Emitidas" icon="fas fa-file-invoice" theme="gradient-info" url="{{ route('situacaoOS.consulta', ['statusOS' => 'M']) }}" url-text="OS Finalizadas no Mês"/>
            <div class="small-box bg-gradient-teal">
                <div class="inner">
                    <h3>{{$nfsMes}}</h3>
                    <h5>NFS-e Faturadas</h5>
                </div>
                <div class="icon">
                    <i>
                        <img src="{{ asset('img/sistema/nfse-icone_220x245.png') }}" style="max-width: 30%; max-height: 30%; float: right; filter: grayscale(100%) brightness(30%) opacity(0.25);" />
                    </i>
                </div>
                <a href="{{route('reemissaoNF.consultaReemissaoNF', ['appOrigem' => 'HOME'])}}" class="small-box-footer">Notas Emitidas no Mês
                    <i class="fas fa-lg fa-arrow-circle-right"></i>
                </a>
                <div class="overlay d-none">
                    <i class="fas fa-2x fa-spin fa-sync-alt text-gray"></i>
                </div>
            </div>
            <div class="small-box bg-gradient-olive">
                <div class="inner">
                    <h3>{{$nfsSimpMes}}</h3>
                    <h5>NFS-e Simplificada</h5>
                </div>
                <div class="icon">
                    <i>
                        <img src="{{ asset('img/sistema/nfse-simp-icone_220x245.png') }}" style="max-width: 30%; max-height: 30%; float: right; filter: grayscale(100%) brightness(30%) opacity(0.25);" />
                    </i>
                </div>
                <a href="{{route('reemissaoSimpNF.consultaReemissaoSimpNF',['appOrigem' => 'HOME'])}}" class="small-box-footer">Emissões Simplificadas no Mês
                    <i class="fas fa-lg fa-arrow-circle-right"></i>
                </a>
                <div class="overlay d-none">
                    <i class="fas fa-2x fa-spin fa-sync-alt text-gray"></i>
                </div>
            </div>
            <x-adminlte-card title="Geral da Empresa no Mês" icon="fa-solid fa-arrow-trend-up" theme="" theme-mode="outline" header-class="card-outline-nexus" collapsible maximizable>
                <div class="d-flex justify-content-between align-items-center border-bottom mb-3">
                    <p class="text-teal text-xl">
                        <i class="fa-solid fa-money-bill-1"></i>
                    </p>
                    <p class="d-flex flex-column text-right">
                    @if($perVlrNfsMes > 0)<span class="font-weight-bold text-success"><i class="fa-solid fa-arrow-up"></i>
                    @elseif($perVlrNfsMes < 0)<span class="font-weight-bold text-danger"><i class="fa-solid fa-arrow-down"></i>@php $perVlrNfsMes *= -1;@endphp
                    @else<span class="font-weight-bold text-warning"><i class="fas fa-square fa-2xs"></i>
                    @endif
                    {{Helper::formataPorcentagem($perVlrNfsMes)}}%
                    </span>
                    <span class="text-muted">Valor de NFS-e Emitidas</span>
                    </p>
                </div>
                <div class="d-flex justify-content-between align-items-center border-bottom mb-3">
                    <p class="text-info text-xl">
                    <i class="fa-solid fa-file-invoice"></i>
                    </p>
                    <p class="d-flex flex-column text-right">
                    @if($qtdOsMes > 0)<span class="font-weight-bold text-success"><i class="fa-solid fa-arrow-up"></i>
                    @elseif($qtdOsMes < 0)<span class="font-weight-bold text-danger"><i class="fa-solid fa-arrow-down"></i>@php $qtdOsMes *= -1;@endphp
                    @else<span class="font-weight-bold text-warning"><i class="fas fa-square fa-2xs"></i>
                    @endif
                    {{Helper::formataPorcentagem($qtdOsMes)}}%
                    </span>
                    <span class="text-muted">OS Geradas</span>
                    </p>
                </div>
                <div class="d-flex justify-content-between align-items-center border-bottom mb-3">
                    <p class="text-olive text-xl">
                    <i class="fa-solid fa-file-invoice-dollar"></i>
                    </p>
                    <p class="d-flex flex-column text-right">
                    @if($qtdNfsMes > 0)<span class="font-weight-bold text-success"><i class="fa-solid fa-arrow-up"></i>
                    @elseif($qtdNfsMes < 0)<span class="font-weight-bold text-danger"><i class="fa-solid fa-arrow-down"></i>@php $qtdNfsMes *= -1;@endphp
                    @else<span class="font-weight-bold text-warning"><i class="fas fa-square fa-2xs"></i>
                    @endif
                    {{Helper::formataPorcentagem($qtdNfsMes)}}%
                    </span>
                    <span class="text-muted">NFS-e Geradas</span>
                    </p>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-0">
                    <p class="text-lightblue text-xl">
                    <i class="fa-solid fa-users"></i>
                    </p>
                    <p class="d-flex flex-column text-right">
                    @if($qtdCliMes > 0)<span class="font-weight-bold text-success"><i class="fa-solid fa-arrow-up"></i>
                    @elseif($qtdCliMes < 0)<span class="font-weight-bold text-danger"><i class="fa-solid fa-arrow-down"></i>@php $qtdCliMes *= -1;@endphp
                    @else<span class="font-weight-bold text-warning"><i class="fas fa-square fa-2xs"></i>
                    @endif
                    {{Helper::formataPorcentagem($qtdCliMes)}}%
                    </span>
                    <span class="text-muted">Novos Clientes</span>
                    </p>
                </div>
            </x-adminlte-card>
        </div>
    </div>
</div>

@section('plugins.Chartjs', true)
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)

@section('css')
@stop

@section('js')
<script>
    /* *****
    |----------------------------------------------------------------------------------------------------
    | Eventos da Inicialização do Datatable
    |----------------------------------------------------------------------------------------------------
    |
    | Adicionamos aqui todos os eventos relacionados a criação e manipulação de eventos do datatable
    |
    ***** */
    $(() => {
        
        /* ********** Inicializa o Datatable ********** */
        var tableOS = $('#table-os').DataTable({
            language: dataTableLangPtBR,
            lengthChange: false, 
            paging: false,
            searching: false,
            pageLength: 5,
            info: false,
            order: [
                [0, 'desc']
            ],
            columns: [
                { orderable: false, visible: false }, // Esconder primeira coluna
                { orderable: false }, 
                { orderable: false },
                { orderable: false },
                { orderable: false },
                { orderable: false },
            ],
        });

        /* ********** Inicializa o Datatable ********** */
        var tableNFS = $('#table-nfs').DataTable({
            language: dataTableLangPtBR,
            lengthChange: false, 
            paging: false,
            searching: false,
            pageLength: 5,
            info: false,
            order: [
                [0, 'desc'],
                [1, 'desc']
            ],
            columns: [
                { orderable: false, visible: false }, // Esconder primeira coluna
                { orderable: false, visible: false }, // Esconder segunda coluna
                { orderable: false },
                { orderable: false },
                { orderable: false },
                { orderable: false },
                { orderable: false },
                { orderable: false },
                { orderable: false },
            ],
        });

        /* ********** Inicializa o Datatable ********** */
        var tableNFSSimp = $('#table-nfs-simp').DataTable({
            language: dataTableLangPtBR,
            lengthChange: false, 
            paging: false,
            searching: false,
            pageLength: 5,
            info: false,
            order: [
                [0, 'desc'],
                [1, 'desc']
            ],
            columns: [
                { orderable: false, visible: false }, // Esconder primeira coluna
                { orderable: false, visible: false }, // Esconder segunda coluna
                { orderable: false },
                { orderable: false },
                { orderable: false },
                { orderable: false },
                { orderable: false },
                { orderable: false },
                { orderable: false },
            ],
        });

    });

    /* ------------------------------ Final da Inicialização do Datatable ------------------------------ */
</script>

<script>
    $(function () {

        //-------------
        //- LINE CHART -
        //--------------

        var lineChartOptions = {
            maintainAspectRatio : false,
            responsive : true,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            legend: {
                display: false
            },
            scales: {
                xAxes: [{
                gridLines : {
                    display : false,
                }
                }],
                yAxes: [{
                gridLines : {
                    display : false,
                }
                }]
            }
        }

        var lineChartData = {
            labels  :  {!! $diasSemana !!},
            datasets: [{
                data: {!! $qtdSemAtu !!},
                backgroundColor: 'transparent',
                borderColor: '#007bff',
                pointBorderColor: '#007bff',
                pointBackgroundColor: '#007bff',
                fill: false
                // pointHoverBackgroundColor: '#007bff',
                // pointHoverBorderColor    : '#007bff'
            },
            {
                data: {!! $qtdSemPas !!},
                backgroundColor: 'tansparent',
                borderColor: '#ced4da',
                pointBorderColor: '#ced4da',
                pointBackgroundColor: '#ced4da',
                fill: false
                // pointHoverBackgroundColor: '#ced4da',
                // pointHoverBorderColor    : '#ced4da'
            }]
        }

        var lineChartCanvas = $('#visitors-chart').get(0).getContext('2d');
        var lineChartOptions = $.extend(true, {}, lineChartOptions);
        var lineChartData = $.extend(true, {}, lineChartData);
        lineChartData.datasets[0].fill = false;
        lineChartData.datasets[1].fill = false;
        lineChartOptions.datasetFill = false;

        var lineChart = new Chart(lineChartCanvas, {
        type: 'line',
        data: lineChartData,
        options: lineChartOptions
        });
    });
</script>
@stop
