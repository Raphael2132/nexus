<!-- Modal Content -->
<div class="text-muted">
    <div class="row">
        <p class="text-sm col-md-3">TMO
            <b class="d-block">{{ $dadosSrv->exetrf_tmo.' - '.$dadosSrv->exetrf_desc }}</b>
        </p>
        <p class="text-sm col-md-3">Complemento
            <b class="d-block">{{ $dadosSrv->exetrf_cmp }}</b>
        </p>
        <p class="text-sm col-md-3">Setor
            @php 
                $dadosSet = DB::table('parametros_sis_setores')->where('setor_codigo', $dadosSrv->exetrf_set)->where('setor_area', $dadosSrv->exetrf_are)->where('setor_empresa', $dadosSrv->exetrf_emp)->first();
            @endphp
            <b class="d-block">{{ $dadosSrv->exetrf_set.' - '.$dadosSet->setor_desc }}</b>
        </p>
        <p class="text-sm col-md-3">Prestador
            @if(!empty($dadosSrv->exetrf_prt))
            <b class="d-block">{{ $dadosSrv->exetrf_prt.' - '.$dadosPrtTMO->prestador_nome }}</b>
            @else
            <b class="d-block">Não Alocado</b>
            @endif
        </p>
    </div>
    <div class="row">
        <p class="text-sm col-md-3">Situação da TMO
            @if($dadosSrv->exetrf_sts == 'F')
            <b class="d-block"><span class="badge badge-pill badge-success badge-custom">Finalizado</span></b>
            @elseif($dadosSrv->exetrf_sts == 'C')
            <b class="d-block"><span class="badge badge-pill badge-danger badge-custom">Cancelado</span></b>
            @elseif($dadosSrv->exetrf_sts == 'S')
            <b class="d-block"><span class="badge badge-pill badge-warning badge-custom">Suspenso</span></b>
            @elseif($dadosSrv->exetrf_sts == 'A')
            <b class="d-block"><span class="badge badge-pill badge-info badge-custom">Em Andamento</span></b>
            @else
            <b class="d-block"><span class="badge badge-pill badge-primary badge-custom">Em Espera</span></b>
            @endif
        </p>
        @if($dadosSrv->exetrf_age == 'S')
            @php 
                $dtHrAge = $dadosSrv->exetrf_dt_age_tmo.' '.Helper::formataHoraMinuto($dadosSrv->exetrf_hr_age_tmo).':00';
                $dtHrAgeFin = $dadosSrv->exetrf_dt_age_fin_tmo.' '.Helper::formataHoraMinuto($dadosSrv->exetrf_hr_age_fin_tmo).':00';
                $dtHrHoje = date('Y-m-d H:i:s');
                $dtHrFin = $dadosOS->os_dhf;
            @endphp
            <p class="text-sm col-md-3">Agendamento Ini
                @if(($dadosSrv->exetrf_sts == 'A' || $dadosSrv->exetrf_sts == 'F')  && $dadosSrv->exetrf_dt_ini_srv.' '.Helper::formataHoraMinuto($dadosSrv->exetrf_hr_ini_srv).':00' <= $dtHrAge)
                <b class="d-block"><span class="badge badge-pill badge-success badge-custom">{{Helper::formataDataHora($dtHrAge)}}</span></b>
                @elseif(($dadosSrv->exetrf_sts == 'A' || $dadosSrv->exetrf_sts == 'F') && $dadosSrv->exetrf_dt_ini_srv.' '.Helper::formataHoraMinuto($dadosSrv->exetrf_hr_ini_srv).':00' > $dtHrAge)
                <b class="d-block"><span class="badge badge-pill badge-danger badge-custom">{{Helper::formataDataHora($dtHrAge)}}</span></b>
                @elseif($dadosSrv->exetrf_sts == 'E' && $dtHrAge > $dtHrHoje)
                <b class="d-block"><span class="badge badge-pill badge-danger badge-custom">{{Helper::formataDataHora($dtHrAge)}}</span></b>
                @elseif($dadosSrv->exetrf_sts == 'E' && $dtHrAge <= $dtHrHoje)
                <b class="d-block"><span class="badge badge-pill badge-success badge-custom">{{Helper::formataDataHora($dtHrAge)}}</span></b>
                @else
                <b class="d-block"><span class="badge badge-pill badge-warning badge-custom">{{Helper::formataDataHora($dtHrAge)}}</span></b>
                @endif
            </p>
            <p class="text-sm col-md-3">Agendamento Fin
                @if($dadosSrv->exetrf_sts == 'F' && $dtHrAgeFin >= $dadosSrv->exetrf_dt_fin_srv.' '.Helper::formataHoraMinuto($dadosSrv->exetrf_hr_fin_srv).':00')
                <b class="d-block"><span class="badge badge-pill badge-success badge-custom">{{Helper::formataDataHora($dtHrAgeFin)}}</span></b>
                @elseif($dadosSrv->exetrf_sts == 'F' && $dtHrAgeFin < $dadosSrv->exetrf_dt_fin_srv.' '.Helper::formataHoraMinuto($dadosSrv->exetrf_hr_fin_srv).':00')
                <b class="d-block"><span class="badge badge-pill badge-danger badge-custom">{{Helper::formataDataHora($dtHrAgeFin)}}</span></b>
                @elseif(($dadosSrv->exetrf_sts == 'E' || $dadosSrv->exetrf_sts == 'A') && $dtHrHoje > $dtHrAgeFin)
                <b class="d-block"><span class="badge badge-pill badge-danger badge-custom">{{Helper::formataDataHora($dtHrAgeFin)}}</span></b>
                @elseif(($dadosSrv->exetrf_sts == 'E' || $dadosSrv->exetrf_sts == 'A') && $dtHrHoje <= $dtHrAgeFin)
                <b class="d-block"><span class="badge badge-pill badge-success badge-custom">{{Helper::formataDataHora($dtHrAgeFin)}}</span></b>
                @else
                <b class="d-block"><span class="badge badge-pill badge-warning badge-custom">{{Helper::formataDataHora($dtHrAgeFin)}}</span></b>
                @endif
            </p>
        @else
            <p class="text-sm col-md-3">Agendamento Ini
                <b class="d-block">Não Agendado</b>
            </p>
            <p class="text-sm col-md-3">Agendamento Fin
                <b class="d-block">Não Agendado</b>
            </p>
        @endif
        <p class="text-sm col-md-3">Consultor
            <b class="d-block">{{$dadosSrv->exetrf_usu.' - '.$dadosUsu->name}}</b>
        </p>
    </div>
    <div class="row">
        <p class="text-sm col-md-2">Data / Hora de Início
            @if(!empty($dadosSrv->exetrf_dt_ini_srv))
            <b class="d-block">{{Helper::formataData($dadosSrv->exetrf_dt_ini_srv).' - '.Helper::formataHoraMinuto($dadosSrv->exetrf_hr_ini_srv)}}</b>
            @else
            <b class="d-block">Não Iniciado</b>
            @endif
        </p>
        <p class="text-sm col-md-2">Data / Hora de Final
            @if(!empty($dadosSrv->exetrf_dt_fin_srv))
            <b class="d-block">{{Helper::formataData($dadosSrv->exetrf_dt_fin_srv).' - '.Helper::formataHoraMinuto($dadosSrv->exetrf_hr_fin_srv)}}</b>
            @else
            <b class="d-block">Não Iniciado</b>
            @endif
        </p>
        <p class="text-sm col-md-2">Tipo Hora Serviço
            @if($dadosSrv->exetrf_ths == 'P')
            <b class="d-block">Padrão</b>
            @elseif($dadosSrv->exetrf_ths == 'I')
            <b class="d-block">Informada</b>
            @elseif($dadosSrv->exetrf_ths == 'F')
            <b class="d-block">Fixa</b>
            @elseif($dadosSrv->exetrf_ths == 'R')
            <b class="d-block">Real</b>
            @else
            <b class="d-block">Terceiros</b>
            @endif
        </p>
        <p class="text-sm col-md-2">Tempo Previsto
            <b class="d-block">{{$dadosSrv->exetrf_qhr}}</b>
        </p>
        <p class="text-sm col-md-2">Tempo Trabalhado
        @if(!empty($dadosSrv->exetrf_dt_fin_srv))
            <b class="d-block">{{$dadosSrv->exetrf_qhr_real}}</b>
        @else
            <b class="d-block"></b>
        @endif
        </p>
        <p class="text-sm col-md-2">Tempo Saldo
        @if(!empty($dadosSrv->exetrf_dt_fin_srv))
            @if($dadosSrv->exetrf_qhr_saldo > 0)
                <b class="d-block"><span class="text-primary">{{ $dadosSrv->exetrf_qhr_saldo }} <i class="fa-solid fa-angles-right"></i></span></b>
            @elseif($dadosSrv->exetrf_qhr_saldo < 0)
                <b class="d-block"><span class="text-danger">{{ $dadosSrv->exetrf_qhr_saldo }} <i class="fa-solid fa-angles-left"></i></span></b>
            @else
                <b class="d-block"><span class="text-success">{{ $dadosSrv->exetrf_qhr_saldo }} <i class="fa-solid fa-stop"></i></span></b>
            @endif
        @else
            <b class="d-block"></b>
        @endif
        </p>
    </div>
</div>
@php
$heads = [
    'Prestador',
    'Ultimas OS',
    'Data/Hora Ini',
    'Data/Hora Fin',
    'Situação Prestador',
    'Próxima OS',
    'Hora Ini',
    ['label' => '', 'no-export' => true, 'width' => '5%'],
];
$config = [
    'lengthMenu' => [ 5, 10, 25, 50],
    'paging' => false,
    'searching' => false,
    'language' => [
        'decimal' =>        '',
        'emptyTable' =>     'Sem dados disponíveis na tabela',
        'info' =>           '',
        'infoEmpty' =>      '',
        'infoFiltered' =>   '(Filtrado do total de _MAX_ registros)',
        'infoPostFix' =>    '',
        'thousands' =>      ',',
        'lengthMenu' =>     '',
        'loadingRecords' => 'Carregando...',
        'processing' =>     '',
        'search' =>         'Pesquisar:',
        'zeroRecords' =>    'Nenhum registro correspondente encontrado',
        'paginate' => [
            'first' =>      'Primeiro',
            'last' =>       'Último',
            'next' =>       'Próximo',
            'previous' =>   'Anterior'
        ],
        'aria' => [
            'sortAscending' =>  ': ativar para classificar a coluna em ordem crescente',
            'sortDescending' => ': ativar para classificar a coluna em ordem decrescente'
        ],
    ],
    'order' => [[0, 'asc']],
    'columns' => [['orderable' => false],['orderable' => false],['orderable' => false],['orderable' => false],['orderable' => false],['orderable' => false],['orderable' => false],['orderable' => false]],
];
@endphp
<x-adminlte-datatable id="tabelaModalAddChangePrt" :heads="$heads" :config="$config" theme="light" head-theme="dark" striped hoverable beautify>
    @foreach($dadosListaPrt as $prestador)
        @php
            //Ver de fazer uma view depois ou solução melhor com todas as tabelas juntas
            $tarPrincipal = DB::table('vi_lancamento_os_atual_prt')
                                ->where('empresa',$prestador->prestador_empresa)
                                ->where('prestador',$prestador->prestador_codigo)
                                ->where('situacao','A')
                                ->get();
            $osAtiva = '';
            $osAge = '';
            $dataHoraIni = '';
            $dataHoraFin = '';
            $dataHoraAge = '';
            $status = 'Disponível';

            foreach($tarPrincipal as $tarefa){

                $status = 'Trabalhando';

                if(empty($osAtiva)){
                    $osAtiva = $tarefa->num_os.' - Em Andamento';
                }else{
                    $osAtiva .= '<br>'.$tarefa->num_os.' - Em Andamento';
                }

                if(empty($dataHoraIni)){
                    $dataHoraIni = $tarefa->menor_data.' '.Helper::formataHoraMinuto($tarefa->menor_hora).':00';
                }else{
                    if($tarefa->menor_data.' '.Helper::formataHoraMinuto($tarefa->menor_hora).':00' < $dataHoraIni){
                        $dataHoraIni = $tarefa->menor_data.' '.Helper::formataHoraMinuto($tarefa->menor_hora).':00';
                    }
                }
            }

            if(empty($osAtiva)){
                $tarefaFin = DB::table('vi_lancamento_os_atual_prt')
                                ->where('empresa',$prestador->prestador_empresa)
                                ->where('prestador',$prestador->prestador_codigo)
                                ->where('situacao','F')
                                ->orderby('maior_data', 'asc')
                                ->orderby('maior_hora', 'asc')
                                ->first();
                
                if(!empty($tarefaFin)){
                    $osAtiva = $tarefaFin->num_os.' - Finalizada';
                    $dataHoraIni = $tarefaFin->menor_data.' '.Helper::formataHoraMinuto($tarefaFin->menor_hora).':00';
                    $dataHoraFin = $tarefaFin->maior_data.' '.Helper::formataHoraMinuto($tarefaFin->maior_hora).':00';
                }
            }

            $tarefaAge = DB::table('vi_lancamento_os_atual_prt')
                                ->where('empresa',$prestador->prestador_empresa)
                                ->where('prestador',$prestador->prestador_codigo)
                                ->where('situacao','E')
                                ->orderby('menor_data_age', 'asc')
                                ->orderby('menor_hora_age', 'asc')
                                ->orderby('num_os', 'asc')
                                ->first();
                
            if(!empty($tarefaAge)){
                $osAge = $tarefaAge->num_os.' - Finalizada';

                if(!empty($tarefaAge->menor_data_age)){
                    $dataHoraAge = $tarefaAge->menor_data_age.' '.Helper::formataHoraMinuto($tarefaAge->menor_hora_age).':00';
                }
            }
        @endphp
        <tr>
            <td>{{$prestador->prestador_codigo.' - '.$prestador->prestador_nome}}</td>
            <td>{!! $osAtiva !!}</td>
            <td>{{!empty($dataHoraIni) ? Helper::formataDataHora($dataHoraIni) : ''}}</td>
            <td>{{!empty($dataHoraFin) ? Helper::formataDataHora($dataHoraFin) : ''}}</td>
            <td>
                @if($status == 'Trabalhando')
                <span class="badge badge-pill badge-danger badge-custom">Trabalhando</span>
                @else
                <span class="badge badge-pill badge-success badge-custom">Disponível</span>
                @endif
            </td>
            <td>{{ $osAge }}</td>
            <td>{{!empty($dataHoraAge) ? Helper::formataDataHora($dataHoraAge) : ''}}</td>
            <td>
                <input type="radio" name="selected_prestador" value="{{$prestador->prestador_codigo}}">
            </td>
        </tr>
    @endforeach
</x-adminlte-datatable>