@extends('adminlte::page')

@section('title', 'Painel de OS')

@section('content_header')
<div class="row mb-2">
        <div class="col-sm-6">
            <h4 style="margin-bottom: 0px !important;">Painel de Produção</h4>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item active">
                    <a href="{{route('home.painelProducao')}}">Filtro Painel</a>
                </li>
                <li class="breadcrumb-item active">Painel de OS</li>
            </ol>
        </div>
    </div>
@stop

@section('content')

@php
//Gera Cabeçalho e Config da Tabela Principal das OS
$heads = [
    'Linha Oculta',
    'Linha Oculta 2',
    'Situação',
    'OS',
    'Prestador Responsável',
    'Cliente',
    'Abertura da OS',
    'Fechamento da OS',
    'Previsão de Entrega'
];
$config = [
    'paging' => true,
    'pageLength' => $linhaPagina, 
    'searching' => false,
    'autoWidth' => true,
    'info' => false, // Oculta o contador de informações sobre a tabela
    'lengthChange' => false, // Desativa a seleção de itens por página
    'language' => [
        'decimal' =>        '',
        'emptyTable' =>     'Sem dados disponíveis na tabela',
        'info' =>           'Mostrando _START_ a _END_ de _TOTAL_ registros',
        'infoEmpty' =>      'Mostrando 0 a 0 de 0 registros',
        'infoFiltered' =>   '(Filtrado do total de _MAX_ registros)',
        'infoPostFix' =>    '',
        'thousands' =>      ',',
        'lengthMenu' =>     'Mostrar _MENU_ registros',
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
    'order' => [
        [0, 'asc'],
        [1, 'asc']
    ],
    'columns' => [
        ['orderable' => false, 'visible' => false], // Esconder primeira coluna
        ['orderable' => false, 'visible' => false], // Esconder segunda coluna
        ['orderable' => false],
        ['orderable' => false],
        ['orderable' => false],
        ['orderable' => false],
        ['orderable' => false],
        ['orderable' => false],
        ['orderable' => false]
    ],
];
@endphp

<x-adminlte-card title="Painel de Ordens de Serviço em Andamento" theme="gray" body-class="bg-dark" class="card-principal" maximizable>

    <!-- Linha Principal dos Quadros do Painel -->
    <div class="row">
        <!-- Quadro dos dados da Empresa -->
        <div class="col-md-2">
            <x-adminlte-card class="bg-logo-card" style="height: 180px; overflow: hidden;">
                <div class="d-flex justify-content-between align-items-center h-100">
                    <div class="d-flex justify-content-center align-items-center col-md-12" style="height: 100%;">
                        @php 
                            $logo_emp = $dadosEmp->empresa_cnpj."/file/img/".$dadosEmp->empresa_codigo."_logo.png";
                        @endphp
                        <img src="{{ asset($logo_emp) }}" alt="{{ $dadosEmp->empresa_nome }}" style="max-width: 100%; max-height: 90%; display: block;">
                    </div>
                </div>
            </x-adminlte-card>
        </div>
        
        <!-- Quadro dos Dados do Setor da Empresa -->
        <div class="col-md-3">
            <div class="row">
                <div class="col-md-12">
                    <x-adminlte-info-box title="Horario de Funcionamento" text="{{ $horaIniEx.' às '.$horaFinEx }}" icon="fas fa-solid fa-screwdriver-wrench text-light" class="bg-box-funcionamento"/>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    @php 
                        $horaAtu = date('H:i');
                    @endphp
                    @if($horaIniEx < $horaAtu && $horaAtu <= $horaFinEx)
                    <x-adminlte-info-box title="Situação da Empresa" text="Em Funcionamento" icon="fas fa-business-time text-light" id="box-funcionamento" class="custom-info-box2 bg-box-funcionamento"/>
                    @else 
                    <x-adminlte-info-box title="Situação da Empresa" text="Fora do Horário de Expediente" icon="fas fa-building-lock text-light" id="box-funcionamento" class="custom-info-box2 bg-box-danger"/>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="row">
                <div class="col-md-12">
                    <x-adminlte-info-box 
                        title="Tempo do Expediente" 
                        text="0/{{$horaExpediente}} Horas" 
                        icon="fas fa-hourglass-start text-dark" 
                        theme="info" 
                        id="ibUpdatable" 
                        progress="0" 
                        progress-theme="teal" 
                        description="0% do expediente completado"
                        class="custom-info-box"/>

                    @push('js')
                    <script>
                        $(document).ready(function() {

                            function parseTimeToMinutes(time) {
                                const [hours, minutes] = time.split(':').map(Number);
                                return hours * 60 + minutes;
                            }

                            function updateIBox() {
                                const startHour = {!! json_encode($horaIniEx) !!}; // Hora de início do expediente
                                const endHour = {!! json_encode($horaFinEx) !!}; // Hora de término do expediente

                                const startMinutes = parseTimeToMinutes(startHour);
                                const endMinutes = parseTimeToMinutes(endHour);
                                const totalMinutes = endMinutes - startMinutes;

                                const now = new Date();
                                const currentMinutes = now.getHours() * 60 + now.getMinutes();

                                // Calcula o progresso em minutos
                                let elapsedMinutes = Math.max(0, currentMinutes - startMinutes);
                                let progress = Math.min(100, (elapsedMinutes / totalMinutes) * 100);

                                // Atualiza o texto e a barra de progresso
                                let elapsedHours = Math.floor(elapsedMinutes / 60);
                                let totalHours = Math.floor(totalMinutes / 60);
                                let text = `${Math.min(elapsedHours, totalHours)}/${totalHours} Horas`;

                                let description = `${Math.round(progress)}% do expediente completado`;

                                // Define o ícone e o tema baseado na porcentagem de progresso
                                let iconClass;
                                let theme;
                                
                                progress = 70;

                                if (progress < 25) {
                                    iconClass = 'fa-hourglass-start';
                                    theme = 'info';
                                } else if (progress <= 80) {
                                    iconClass = 'fa-hourglass-half';
                                    theme = 'custom';
                                } else if (progress <= 99) {
                                    iconClass = 'fa-hourglass-end';
                                    theme = 'warning';
                                } else {
                                    iconClass = 'fa-hourglass-end';
                                    theme = 'danger';
                                }

                                // Atualiza diretamente os elementos do InfoBox
                                $('#ibUpdatable .info-box-number').text(text);
                                $('#ibUpdatable .progress-bar').css('width', `${progress}%`);
                                $('#ibUpdatable .progress-description').text(description);
                                $('#ibUpdatable .info-box-icon i').attr('class', `fas fa-lg ${iconClass}`);
                                $('#ibUpdatable').attr('class', `info-box bg-${theme} custom-info-box`);
                            }

                            updateIBox(); // Atualiza imediatamente ao carregar a página
                            setInterval(updateIBox, 60000); // Atualiza a cada minuto
                        });
                    </script>
                    @endpush
                </div>
            </div>
        </div>
        <!-- Quadro das Informações de Data e Hora -->
        <div class="col-md-4">
            <x-adminlte-card class="bg-clock-card" icon="" title="" style="height: 180px; overflow: hidden;">
                <div class="d-flex justify-content-center align-items-center flex-column h-100">
                    <div id="clock" style="font-size: 72px; font-weight: bold;"></div>
                    <div id="date" style="font-size: 36px;"></div>
                </div>
            </x-adminlte-card>
        </div>
    </div>

    <!-- Linha da tabela dos prestadores -->
    <div class="row">
        <div class="col-md-12">
            <!-- Tabela Principal da OS -->
            <x-adminlte-datatable class="table-scroll" id="table-os" :heads="$heads" :config="$config" head-theme="dark" theme="dark" striped hoverable compressed beautify>
                @foreach ($dadosOS as $os)
                @php 
                    $dataHrAtu = date('Y-m-d H:i');

                    if(!empty($os->data_prev_ent)){
                        $dataHrPrev = $os->data_prev_ent.' '.$os->hora_prev_ent;
                    }else{
                        $dataHrPrev = '';
                    }

                    $dadosPrt = DB::table('lancamento_srv_exe_tarefas')
                    ->where('exetrf_emp', $os->empresa)
                    ->where('exetrf_nos', $os->num_os)
                    ->whereNotNull('exetrf_prt')
                    ->distinct()
                    ->pluck('exetrf_prt');

                    $prestadores = '';

                    foreach($dadosPrt as $prestador){
                        $nomePrt = DB::table('cadastro_prestadores')->where('prestador_empresa',$os->empresa)->where('prestador_codigo',$prestador)->first();
                        if(empty($prestadores)){
                            $prestadores = $prestador.' - '.$nomePrt->prestador_nome;
                        } else {
                            $prestadores .= '</br>' . $prestador.' - '.$nomePrt->prestador_nome;
                        }
                    }

                    if(empty($prestadores)){
                        $prestadores = "Não Alocado";
                    }
                @endphp
                <tr>
                    <td>{{$os->tipo_situacao}}</td>
                    <td>{{$os->dt_hr_abertura}}</td>
                    @if($os->tipo_situacao == 'A')
                    <td class="bg-info font-weight-bold">Em Andamento</td>
                    @elseif($os->tipo_situacao == 'F')
                    <td class="bg-success font-weight-bold">Finalizada</td>
                    @else
                    <td class="bg-secondary font-weight-bold">Aberta</td>
                    @endif
                    <td>{{$os->num_os}}</td>
                    <td class="text-left">{!! $prestadores !!}</td>
                    <td class="text-left">{{$os->cliente.' - '.$os->cliente_nome}}</td>
                    <td>{{Helper::formataDataHora($os->dt_hr_abertura)}}</td>
                    <td>{{$os->dt_hr_fechamento == '' ? '' : Helper::formataDataHora($os->dt_hr_fechamento)}}</td>
                    @if(!empty($dataHrPrev) && $dataHrPrev > $dataHrAtu)
                    <td><span class="badge badge-pill badge-success badge-custom">{{Helper::formataData($os->data_prev_ent).' '.Helper::formataHoraMinuto($os->hora_prev_ent)}}</span></td>
                    @elseif(!empty($dataHrPrev) && $dataHrPrev <= $dataHrAtu)
                    <td><span class="badge badge-pill badge-danger badge-custom">{{Helper::formataData($os->data_prev_ent).' '.Helper::formataHoraMinuto($os->hora_prev_ent)}}</span></td>
                    @else 
                    <td>Não Informada</td>
                    @endif
                </tr>
                @endforeach
            </x-adminlte-datatable>
        </div>
    </div>
</x-adminlte-card>
@stop

@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)

@section('css')
<style>
/* ********** Estilo para a altura das box da primeira linha ********** */
.custom-info-box {
    height: 180px; /* Ajuste a altura conforme necessário */
}
.custom-info-box2 {
    height: 84px; /* Ajuste a altura conforme necessário */
}
/* Oculta a barra de paginação */
.dataTables_wrapper .dataTables_paginate {
    display: none !important;
}
#table-os {
    font-size: 22px; /* Ajuste o valor conforme necessário */
}
.badge {
    font-size: 20px; /* Ajuste o valor conforme necessário */
}

.bg-clock-card {
    background: #212529;
}

.bg-logo-card {
    background: #212529;
}

.bg-custom {
    background: #008282;
}

.bg-box-funcionamento {
    background: #008282;
}

.bg-box-danger {
    background: #dc3545;
}
</style>
@stop

@section('js')
<!--
|--------------------------------------------------------------------------
| Eventos JS da Geração do relógio do painel
|--------------------------------------------------------------------------
-->
<script>

    //Gera o Relógio do Painel
    function updateClock() {
        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');
        const timeString = `${hours}:${minutes}:${seconds}`;
        const daysOfWeek = ['Domingo', 'Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sábado'];
        const dayName = daysOfWeek[now.getDay()];
        const dateString = `${dayName}, ${now.getDate()}/${now.getMonth()+1}/${now.getFullYear()}`;
        
        document.getElementById('clock').textContent = timeString;
        document.getElementById('date').textContent = dateString;
    }

    setInterval(updateClock, 1000); // Atualiza o relógio a cada segundo
    updateClock(); // Chama a função para exibir o relógio imediatamente

</script>

<!--
|--------------------------------------------------------------------------
| Eventos JS de troca automática das páginas
|--------------------------------------------------------------------------
-->
<script>
$(document).ready(function() {
    var table = $('#table-os').DataTable(); // Assumindo que a tabela já está inicializada

    // Tempo de troca de página (em milissegundos)
    var interval = {!! json_encode($milissegundosPagina) !!};
    var currentPage = 0;  // Começa na página 0 (primeira página)

    // Função para mudar de página
    function changePage() {
        var pageInfo = table.page.info(); // Obtém informações sobre a paginação
        currentPage = (currentPage + 1) % pageInfo.pages; // Calcula a próxima página
        
        table.page(currentPage).draw('page'); // Muda para a próxima página
    }

    // Inicia o intervalo de mudança de página
    setInterval(changePage, interval);
});
</script>

<!--
|--------------------------------------------------------------------------
| Eventos JS de troca do card da Situação de Funcionamento
|--------------------------------------------------------------------------
-->
<script>
    function atualizarSituacao() {
        // Obtendo a hora atual
        const horaAtu = '10:00';//new Date().toTimeString().substr(0, 5);
        
        // Defina os horários de início e fim de expediente (no formato HH:mm)
        const horaIniEx = {!! json_encode($horaIniEx) !!}; 
        const horaFinEx = {!! json_encode($horaFinEx) !!};
        
        // Verificando a situação com base nos horários
        if (horaIniEx < horaAtu && horaAtu <= horaFinEx) {
            // Atualiza diretamente os elementos do InfoBox
            $('#box-funcionamento .info-box-number').text('Em Funcionamento');
            $('#box-funcionamento .info-box-icon i').attr('class', 'fas fa-business-time text-light');
            $('#box-funcionamento').attr('class', 'info-box custom-info-box2 bg-box-funcionamento');
        } else {
            // Atualiza diretamente os elementos do InfoBox
            $('#box-funcionamento .info-box-number').text('Fora do Horário de Expediente');
            $('#box-funcionamento .info-box-icon i').attr('class', 'fas fa-building-lock text-light');
            $('#box-funcionamento').attr('class', 'info-box custom-info-box2 bg-box-danger');
        }
    }
    
    // Atualizar a situação a cada minuto
    atualizarSituacao();
    setInterval(atualizarSituacao, 60000); // 60000 ms = 1 minuto
</script>
@stop