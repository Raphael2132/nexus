@extends('adminlte::page')

@section('title', 'Painel de Prestadores')

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
                <li class="breadcrumb-item active">Painel de Prestadores</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
<x-adminlte-card title="Painel de Operações da Produção" theme="dark" body-class="bg-dark" header-class="text-uppercase" maximizable>

    <!-- Linha Principal dos Quadros do Painel -->
    <div class="row">
        <!-- Quadro dos dados da Empresa -->
        <div class="col-md-4">
            <x-adminlte-card class="bg-logo-card" style="height: 200px; overflow: hidden;">
                <div class="d-flex justify-content-between align-items-center h-100">
                    <div class="d-flex justify-content-center align-items-center col-md-6" style="height: 100%;">
                        @php 
                            $logo_emp = $dadosEmp->empresa_cnpj."/file/img/".$dadosEmp->empresa_codigo."_logo.png";
                        @endphp
                        <img src="{{ asset($logo_emp) }}" alt="{{ $dadosEmp->empresa_nome }}" style="max-width: 100%; max-height: 90%; display: block;">
                    </div>
                    <div class="d-flex flex-column justify-content-center align-items-center col-md-6" style="height: 100%;">
                        <!-- Título principal -->
                        <h2 class="w-100 text-center" style="font-weight: bold;">{{ $dadosEmp->empresa_nome }}</h2>
                        <!-- Conteúdo secundário abaixo do título -->
                        <div class="w-100">
                            <div class="row">
                                <p class="text-sm col-md-12" style="text-align: center;">Endereço
                                    <b class="d-block">
                                        {{ $dadosEmpEnd->endereco_logradouro.', '.$dadosEmpEnd->endereco_numero}}</br>
                                        {{ $dadosEmpEnd->endereco_bairro}}</br>
                                        {{ $dadosEmpEnd->endereco_cidade.' / '.$dadosEmpEnd->endereco_uf}}
                                    </b>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </x-adminlte-card>
        </div>
        <!-- Quadro dos Dados do Setor da Empresa -->
        <div class="col-md-5">
            <div class="row">
                <div class="col-md-6">
                    <x-adminlte-info-box title="Horario de Funcionamento" text="{{ $horaIniEx.' às '.$horaFinEx }}" icon="fas fa-solid fa-screwdriver-wrench text-light" class="bg-box-funcionamento"/>
                </div>
                <div class="col-md-6">
                    <x-adminlte-info-box title="Setor" text="{{ $dadosSet->setor_codigo.' - '.$dadosSet->setor_desc }}" icon="fas fa-solid fa-screwdriver-wrench text-light" class="bg-box-setor"/>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    @php 
                        $horaAtu = date('H:i');
                        //$horaAtu = '17:25';
                    @endphp
                    @if($horaIniEx < $horaAtu && $horaAtu <= $horaFinEx)
                    <x-adminlte-info-box title="Situação do Setor" text="Em Funcionamento" icon="fas fa-business-time text-light" id="box-funcionamento" class="custom-info-box bg-box-funcionamento"/>
                    @else 
                    <x-adminlte-info-box title="Situação do Setor" text="Fora do Horário de Expediente" icon="fas fa-building-lock text-light" id="box-funcionamento" class="custom-info-box bg-box-funcionamento-danger"/>
                    @endif
                </div>
                <div class="col-md-6">
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
                                
                                if (progress < 25) {
                                    iconClass = 'fa-hourglass-start';
                                    theme = 'info';
                                } else if (progress <= 75) {
                                    iconClass = 'fa-hourglass-half';
                                    theme = 'success';
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
        <div class="col-md-3">
            <x-adminlte-card class="bg-clock-card" icon="" title="" style="height: 200px; overflow: hidden;">
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
            <x-adminlte-card theme="dark" head-theme="dark" body-class="bg-dark" class="elevation-3">    
                <div class="table-wrapper">
                    <div class="table-relative">
                        <table id="prestadores-table" class="table table-bordered table-striped table-custom datatable">
                            <thead>
                                <tr>
                                    <!-- Gera as Label da tabela onde a primeira é os prestadores e as demais vem da hora do expediente -->
                                    <th>Prestadores</th>
                                    @foreach($horarios as $horario)
                                        <th data-time="{{ $horario }}">{{ $horario }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Para cada Prestador geramos sua TR correspondente -->
                                @foreach($dadosPrt as $prestador)
                                <tr data-prestador-id="{{ $prestador->prestador_codigo }}">
                                    <td>{{ $prestador->prestador_codigo.' - '.$prestador->prestador_nome }}</td>
                                    <!-- Gera as colunas dos horarios do expediente -->
                                    @foreach($horarios as $horario)
                                        @php
                                            //Gera o Horario de almoço do prestador
                                            $span = HelperControleProducao::geraSpanIntPrt($horario, $prestador->prestador_codigo, $glo_painel_data, $glo_painel_empresa);
                                        @endphp
                                        <td data-hora="{{ $horario }}" class="event hora-coluna">
                                            {!! $span !!}
                                        </td>
                                    @endforeach
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- Contêiner para os spans dos eventos -->
                    <div class="events-container"></div>
                </div>
            </x-adminlte-card>
        </div>
    </div>

    <!-- Linha dos blocos dos resumos do painel -->
    <div class="row" style="color: #000;">
        <div class="col-md-4">
            <x-adminlte-callout class="bg-callout-card" theme="info" title-class="text-info text-uppercase" icon="fa-solid fa-clock-rotate-left" title="OS em Andamento">
                <div id="os-andamento">
                    <!-- Conteúdo será atualizado via AJAX -->
                </div>
            </x-adminlte-callout>
        </div>
        <div class="col-md-4">
            <x-adminlte-callout class="bg-callout-card" theme="success" title-class="text-success text-uppercase" icon="fa-solid fa-clipboard-check" title="OS Finalizadas">
                <div id="os-finalizadas">
                    <!-- Conteúdo será atualizado via AJAX -->
                </div>
            </x-adminlte-callout>
        </div>
        <div class="col-md-4">
            <x-adminlte-callout class="bg-callout-card" theme="danger" title-class="text-primary text-uppercase" icon="fa-solid fa-hourglass-start" title="Próximas OS">
                <div id="os-proximas">
                    <!-- Conteúdo será atualizado via AJAX -->
                </div>
            </x-adminlte-callout>
        </div>
    </div>

    <!-- Linha da legenda do painel -->
    <div class="row" style="color: #000;">
        <div class="col-md-12">
            <x-adminlte-card class="bg-legend-card">
                <div class="d-flex justify-content-center flex-wrap" style="width: 100%;">
                    <div style="display: inline-flex; align-items: center; margin: 5px 20px;">
                        <div style="width: 20px; height: 20px; background-color: #007bff; margin-right: 10px;"></div>
                        <span style="font-size: 12px;">TMO Em Espera</span>
                    </div>
                    <div style="display: inline-flex; align-items: center; margin: 5px 20px;">
                        <div style="width: 20px; height: 20px; background-color: #17a2b8; margin-right: 10px;"></div>
                        <span style="font-size: 12px;">TMO Em Andamento</span>
                    </div>
                    <div style="display: inline-flex; align-items: center; margin: 5px 20px;">
                        <div style="width: 20px; height: 20px; background-color: #28a745; margin-right: 10px;"></div>
                        <span style="font-size: 12px;">TMO Finalizada</span>
                    </div>
                    <div style="display: inline-flex; align-items: center; margin: 5px 20px;">
                        <div style="width: 20px; height: 20px; background-color: #ffc107; margin-right: 10px;"></div>
                        <span style="font-size: 12px;">TMO Suspensa</span>
                    </div>
                    <div style="display: inline-flex; align-items: center; margin: 5px 20px;">
                        <div style="width: 20px; height: 20px; background-color: #dc3545; margin-right: 10px;"></div>
                        <span style="font-size: 12px;">TMO Cancelada</span>
                    </div>
                </div>
            </x-adminlte-card>
        </div>
    </div>
</x-adminlte-card>
@stop

@section('plugins.Fullcalendar', true)
@section('plugins.Moment', true)
@section('plugins.Jquery-ui', true)
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)

@section('css')
<style>
    
/* ********** Estilo para a tabela e colunas ********** */
.card-body {
    overflow: hidden !important;
}

.card-header {
    background-color: #212529 !important;
}

.table {
    table-layout: fixed; /* Garante que as larguras das colunas sejam fixas */
    width: 100%; /* Define a largura da tabela para preencher o container */
    font-size: 22px; /* Ajuste o valor conforme necessário */
}

.table th, .table td {
    overflow: hidden; /* Oculta o texto que ultrapassa o tamanho da célula */
    white-space: nowrap; /* Impede a quebra de linha dentro das células */
    position: relative; /* Necessário para o posicionamento da linha */
}

/* Define largura fixa para a primeira coluna */
.table th:first-child, .table td:first-child {
    width: 20%; /* Largura da coluna para o nome do prestador */
}

/* Define largura fixa para as colunas das horas */
.table th:nth-child(n+2), .table td:nth-child(n+2) {
    width: calc((100% - 20%) / 20); /* Ajusta o tamanho das colunas das horas */
}

/* Estilos para o cabeçalho da tabela */
.table-custom thead th {
    background-color: #f8f9fa; /* Cabeçalho claro */
    color: #343a40; /* Texto escuro */
    border-color: #dee2e6;
}

/* Estilos para o corpo da tabela */
.table-custom tbody {
    background-color: #343a40; /* Fundo escuro */
    color: #fff; /* Texto claro */
}

.table-custom tbody tr {
    border-color: #dee2e6;
}

.table-custom tbody tr td {
    border-color: #dee2e6;
}

.table-custom tbody tr:nth-child(even) {
    background-color: #3e4551; /* Linha par mais escura */
}

.table-custom tbody tr:nth-child(odd) {
    background-color: #343a40; /* Linha ímpar */
}

/* ********** Estilo para células com tracejado vertical ********** */
.event {
    color: #333;
    text-align: center;
    position: relative; /* Necessário para posicionar o pseudo-elemento */
    padding: 0; /* Remove o padding para ajustar o tracejado corretamente */
}

.event::before {
    content: "";
    position: absolute;
    top: 0;
    left: 50%;
    width: 1px; /* Largura do tracejado */
    height: 100%; /* Altura total da célula */
    border-left: 1px dashed #999; /* Cor e estilo do tracejado */
    transform: translateX(-50%); /* Centraliza horizontalmente */
}

/* ********** Estilo para a linha de flag da hora atual vermelha vertical ********** */
.current-time-line {
    position: absolute;
    top: 0;
    height: calc(100% - 2px); /* Ajuste a altura para evitar ultrapassar o topo/rodapé */
    width: 4px;
    background-color: red;
    z-index: 1000;
    pointer-events: none;
}

.current-time-box {
    width: 50px;
    height: 30px;
    background-image: url('{{ asset('img/sistema/tag_timeline.png') }}');
    background-size: 100% 100%; /* Ajusta a imagem para cobrir o fundo da caixa */
    background-position: center; /* Centraliza a imagem na caixa */
    background-repeat: no-repeat; /* Não repete a imagem */
    color: white;
    text-align: center;
    line-height: 20px;
    font-size: 16px;
    font-weight: bold;
    position: absolute;
    z-index: 1000;
    border-radius: 3px;
    display: flex;
    justify-content: center;
    align-items: center;
    padding-bottom: 10px; /* Espaço para a ponta triangular */
    box-sizing: border-box; /* Inclui padding e border no cálculo da largura e altura */
    margin-left: 2px;
}

/* ********** Estilo para a altura das box da primeira linha ********** */
.custom-info-box {
    height: 105px; /* Ajuste a altura conforme necessário */
}

/* ********** Estilo para o span do intervalo/almoço ********** */
.event-lunch-int {
    display: inline-block; 
    position: absolute; 
    top: 0; 
    left: 0; 
    height: 100%; 
    background-color: rgba(245, 245, 220, 0.75); 
    color: #fff; 
    text-align: center; 
    border-radius: 3px;
    overflow: hidden;
}

.event-lunch-esq {
    display: inline-block; 
    position: absolute; 
    top: 0; 
    right: 0; 
    height: 100%; 
    background-color: rgba(245, 245, 220, 0.75); 
    color: #fff; 
    text-align: center; 
    border-radius: 3px;
    overflow: hidden;
}

.event-lunch-dir {
    display: inline-block; 
    position: absolute; 
    top: 0; 
    left: 0; 
    height: 100%; 
    background-color: rgba(245, 245, 220, 0.75); 
    color: #fff; 
    text-align: center; 
    border-radius: 3px;
    overflow: hidden;
}

.event-lunch-center {
    display: inline-block; 
    position: absolute; 
    top: 0;
    height: 100%; 
    background-color: rgba(245, 245, 220, 0.75); 
    color: #fff; 
    text-align: center; 
    border-radius: 3px;
    overflow: hidden;
}

/* ********** Estilo para o span dos eventos de serviços do prestador ********** */
.table-relative {
    position: relative; /* Necessário para que os spans possam ser posicionados absolutamente */
}

.events-container {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none; /* Evita interferência com outros elementos */
}

.event-bar {
    position: absolute;
    background-color: #ff9999;
    z-index: 10;
    height: 30px; /* Ajuste conforme necessário */
    top: 0; /* Posição vertical a ser ajustada dinamicamente */
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-weight: bold;
    padding: 0 5px;
    box-sizing: border-box;
    border-radius: 5px;
    overflow: hidden;
    font-size: 12px;
    white-space: nowrap;
    text-align: left !important;
}

.event-bar-andamento {
    background-color: #17a2b8;
}

.event-bar-espera {
    background-color: #007bff;
}

.event-bar-finalizada {
    background-color: #28a745;
}

.event-bar-cancelada {
    background-color: #dc3545;
}

.event-bar-suspensa {
    background-color: #ffc107;
}

.table-wrapper {
    position: relative;
}

/* ********** Estilo para fundo dos cards e callout ********** */
.bg-clock-card {
    background: #212529;
}

.bg-logo-card {
    background: #212529;
    color: #fff;
}

.bg-legend-card {
    background: #212529;
    color: #fff;
}

.bg-callout-card {
    background: #212529;
}

.bg-callout-card.callout-danger {
    border-left-color: #007bff !important;
}

.bg-box-funcionamento {
    background: #008282;
}

.bg-box-setor {
    background: #008282;
}

.bg-box-funcionamento-danger {
    background: #dc3545;
}
</style>
@stop

@section('js')

<!--
|--------------------------------------------------------------------------
| Eventos JS da Geração da SPAN dos Serviços do prestador
|--------------------------------------------------------------------------
-->
<script>

// Função para atualizar os eventos na visualização da tabela
function updateEvents(eventos) {

    const linhas = Array.from(document.querySelectorAll('tr[data-prestador-id]'));
    document.querySelector('.events-container').innerHTML = '';

    eventos.forEach(evento => {

        const linha = linhas.find(l => l.dataset.prestadorId === evento.prestadorId);
        
        if (linha) {
      
            const colunas = Array.from(linha.querySelectorAll('.hora-coluna'));

            let startLeft = null;
            let totalWidth = 0;
            let topOffset = 0;
            const linhaRect = linha.getBoundingClientRect();
            const tabelaRect = document.querySelector('.table-wrapper').getBoundingClientRect();

            colunas.forEach(coluna => {

                const horaColunaInicio = convertToMinutes(coluna.dataset.hora);
                const horaColunaFim = horaColunaInicio + 30;

                let minutosInicio = convertToMinutes(evento.inicio);
                let minutosFim = convertToMinutes(evento.fim);

                const dataInicio = new Date(evento.dtInicio);
                const dataFinal = new Date(evento.dtFim);
                const dataPainel = new Date({!! json_encode($glo_painel_data) !!});

                const horaIniEx = {!! json_encode($horaIniEx) !!};
                const minutosInicioExpediente = convertToMinutes(horaIniEx);

                const horaFinEx = {!! json_encode($horaFinEx) !!};
                const minutosFinalExpediente = convertToMinutes(horaFinEx);

                if (dataInicio < dataPainel) {
                    minutosInicio = minutosInicioExpediente;
                }

                if (dataFinal > dataPainel) {
                    minutosFim = minutosFinalExpediente;
                }

                if (minutosInicio < horaColunaFim && minutosFim > horaColunaInicio) {

                    const colunaRect = coluna.getBoundingClientRect();
                    const spanWidth = colunaRect.width;

                    if (startLeft === null) {
                        startLeft = colunaRect.left - tabelaRect.left;
                    }

                    let widthAdjustment = spanWidth;

                    if (minutosInicio > horaColunaInicio && minutosInicio < horaColunaFim) {
                        const fracInicio = (minutosInicio - horaColunaInicio) / 30;
                        startLeft += spanWidth * fracInicio;
                        widthAdjustment = spanWidth * (1 - fracInicio);
                    }

                    if (minutosFim > horaColunaInicio && minutosFim < horaColunaFim) {
                        const fracFim = (minutosFim - horaColunaInicio) / 30;
                        widthAdjustment = spanWidth * fracFim;
                    }

                    totalWidth += widthAdjustment;
                }
            });

            if (startLeft !== null && totalWidth > 0) {
                const span = document.createElement('span');
                span.classList.add('event-bar');

                if(evento.situacao == 'A'){
                    span.classList.add('event-bar-andamento');
                }else if(evento.situacao == 'E'){
                    span.classList.add('event-bar-espera');
                }else if(evento.situacao == 'F'){
                    span.classList.add('event-bar-finalizada');
                }else if(evento.situacao == 'C'){
                    span.classList.add('event-bar-cancelada');
                }else if(evento.situacao == 'S'){
                    span.classList.add('event-bar-suspensa');
                }

                span.style.left = `${startLeft}px`;
                span.style.width = `${totalWidth}px`;

                const linhaHeight = linhaRect.height;
                span.style.top = `${linhaRect.top - tabelaRect.top + (linhaHeight - 30) / 2}px`;

                span.textContent = `OS: ${evento.osNumero} / TMO: ${evento.codTmo}`;

                document.querySelector('.events-container').appendChild(span);
            }
        }
    });
}

// Função para converter hora no formato H:i para minutos
function convertToMinutes(hora) {
    const [h, m] = hora.split(':').map(Number);
    return h * 60 + m;
}


(function() {

    // Variáveis PHP interpoladas no JavaScript
    const empresa = '{{ $glo_painel_empresa }}';
    const setor = '{{ $glo_painel_setor }}';
    const data = '{{ $glo_painel_data }}';

    // Inicializa a busca e atualização dos eventos ao carregar a app
    fetch(`/lancamentos/producao/homePainelProducao/carregaEventosTMO/${empresa}/${setor}/${data}`)
    .then(response => response.json())
    .then(data => {
        updateEvents(data); // Atualiza eventos com os dados recebidos
    })
    .catch(error => {
        console.error('Erro ao buscar eventos:', error); // Exibe erro se ocorrer
    });

    // Monitorar mudanças de tamanho na tabela e no card
    const resizeObserver = new ResizeObserver(() => {
        fetch(`/lancamentos/producao/homePainelProducao/carregaEventosTMO/${empresa}/${setor}/${data}`)
        .then(response => response.json())
        .then(data => {
            updateEvents(data); // Atualiza eventos com os dados recebidos
        })
        .catch(error => {
            console.error('Erro ao buscar eventos:', error); // Exibe erro se ocorrer
        });
    });

    // Seleciona a tabela e observa mudanças de tamanho
    const table = document.querySelector('.table');
    resizeObserver.observe(table);
    resizeObserver.observe(table.parentElement);

    // Atualiza os eventos a cada minuto
    setInterval(() => {
        fetch(`/lancamentos/producao/homePainelProducao/carregaEventosTMO/${empresa}/${setor}/${data}`)
        .then(response => response.json())
        .then(data => {
            updateEvents(data); // Atualiza eventos com os dados recebidos
        })
        .catch(error => {
            console.error('Erro ao buscar eventos:', error); // Exibe erro se ocorrer
        });
    }, 60000); // Atualiza a cada 60 segundos

})();
</script>

<!--
|--------------------------------------------------------------------------
| Eventos JS da Geração da current-time-box e current-time-line Vermelho que percorre os horarios da tabela
|--------------------------------------------------------------------------
-->
<script>

    //Posiciona e movimenta a linha vermelha da data atual
    document.addEventListener('DOMContentLoaded', function() {
        const table = document.querySelector('.table');
        const headerCells = Array.from(table.querySelectorAll('thead th[data-time]'));

        // Cria o quadrado vermelho para exibir a hora atual
        const timeBox = document.createElement('div');
        timeBox.classList.add('current-time-box');

        // Cria a linha vertical que se moverá com o tempo
        const timeLine = document.createElement('div');
        timeLine.classList.add('current-time-line');

        // Adiciona o quadrado e a linha ao contêiner pai da tabela
        table.parentElement.appendChild(timeBox);
        table.parentElement.appendChild(timeLine);

        function updateCurrentTimeLine() {
            const now = new Date();
            const currentHours = now.getHours(); // Hora local
            const currentMinutes = now.getMinutes(); // Minuto local

            //console.log('Data:Hora:', currentHours + ':' + currentMinutes);

            const currentTimeInMinutes = currentHours * 60 + currentMinutes; // Hora atual em minutos

            //console.log('Current Time in Minutes:', currentTimeInMinutes);

            let positionLeft = 0;
            let columnFound = false;

            headerCells.forEach((cell, index) => {
                const cellTime = cell.getAttribute('data-time').split(':');
                const cellHours = parseInt(cellTime[0]);
                const cellMinutes = parseInt(cellTime[1]);
                const cellStartInMinutes = cellHours * 60 + cellMinutes;
                const cellEndInMinutes = cellStartInMinutes + 30; // Intervalo de 30 minutos

                //console.log(`Cell Time: ${cellHours}:${cellMinutes}`);
                //console.log('Cell Start in Minutes:', cellStartInMinutes);
                //console.log('Cell End in Minutes:', cellEndInMinutes);

                if (currentTimeInMinutes >= cellStartInMinutes && currentTimeInMinutes < cellEndInMinutes) {
                    var colunmMinutes = now.getMinutes(); // Minuto local
                    if(colunmMinutes > 30){
                        colunmMinutes = colunmMinutes - 30;
                    }
                    //console.log('Minutos da coluna:', colunmMinutes);

                    // Calcula a posição da linha com base na largura da coluna
                    const columnWidth = cell.offsetWidth; // Largura da coluna
                    //console.log('Tamanho coluna:', columnWidth);
                    positionLeft = ((columnWidth / 30) * colunmMinutes) + cell.offsetLeft;

                    //console.log('Position Left:', positionLeft);

                    columnFound = true;
                }
            });

            // Ajusta a linha e o quadrado com base na posição calculada
            if (columnFound) {
                timeLine.style.left = `${positionLeft}px`;
                timeLine.style.top = `${table.offsetTop}px`; // Alinha a linha vertical ao topo da tabela
                timeLine.style.height = `${table.offsetHeight}px`; // Define a altura para cobrir toda a tabela

                // Posiciona o quadrado logo acima da linha e atualiza o texto com a hora atual
                timeBox.style.left = `${positionLeft - 25}px`; // Centraliza o quadrado com a linha
                timeBox.style.top = `${table.offsetTop - 20}px`; // Posiciona o quadrado acima da tabela
                timeBox.textContent = `${String(currentHours).padStart(2, '0')}:${String(currentMinutes).padStart(2, '0')}`;
            } else {
                timeLine.style.left = '-9999px'; // Esconde a linha se a hora não estiver na tabela
                timeBox.style.left = '-9999px'; // Esconde o quadrado se a hora não estiver na tabela
            }
        }

        updateCurrentTimeLine(); // Atualiza imediatamente ao carregar a página
        setInterval(updateCurrentTimeLine, 60000); // Atualiza a cada minuto

        // Monitorar mudanças de tamanho na tabela e no card
        const resizeObserver = new ResizeObserver(() => {
            updateCurrentTimeLine();
        });

        //Aqui vai reposicionar a linha ao maximizar o card principal
        resizeObserver.observe(table);
        resizeObserver.observe(table.parentElement);
    });

</script>

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
| Eventos JS da Geração das OS Em Andamento, Finalizadas e Proximas 
|--------------------------------------------------------------------------
-->
<script>
    function updateOSData(url, targetElement, theme) {
        $.ajax({
            url: url,
            type: 'GET',
            success: function(data) {
                let osHtml = '';
                data.forEach(function(os) {
                    osHtml += '<span class="badge badge-pill badge-'+theme+' badge-custom">' + os + '</span> ';
                });
                $(targetElement).html(osHtml);
            }
        });
    }

    function updateAllOSData() {
        // Definindo as URLs no script gerando a rota com os parâmetros
        var osAndamentoUrl = "{{ route('painelProducao.carregaOsAndamento', ['empresa' => $glo_painel_empresa, 'setor' => $glo_painel_setor, 'data' => $glo_painel_data]) }}";
        var osFinalizadasUrl = "{{ route('painelProducao.carregaOsFinalizadas', ['empresa' => $glo_painel_empresa, 'setor' => $glo_painel_setor, 'data' => $glo_painel_data]) }}";
        var proximasOsUrl = "{{ route('painelProducao.carregaOsProximas', ['empresa' => $glo_painel_empresa, 'setor' => $glo_painel_setor, 'data' => $glo_painel_data]) }}";

        updateOSData(osAndamentoUrl, '#os-andamento', 'info');
        updateOSData(osFinalizadasUrl, '#os-finalizadas', 'success');
        updateOSData(proximasOsUrl, '#os-proximas', 'primary');
    }

    $(document).ready(function() {
        // Atualiza imediatamente após o carregamento da página
        updateAllOSData();
        
        // Atualiza a cada minuto (60000 ms)
        setInterval(updateAllOSData, 60000);
    });
</script>

<!--
|--------------------------------------------------------------------------
| Eventos JS de troca automática das páginas
|--------------------------------------------------------------------------
-->
<script>
$(document).ready(function() {
    // Inicializa o DataTable e armazena a instância na variável 'table'
    var table = $('#prestadores-table').DataTable({
        "paging": true, // Paginação ainda ativa, mas sem exibir os botões
        "lengthChange": false,
        "searching": false,
        "ordering": false,
        "info": false, // Oculta o contador de informações sobre a tabela
        "autoWidth": false,
        "responsive": true,
        "pageLength": {!! json_encode($linhaPagina) !!}, // Exibe linhas por página
        "order": [], // Define se quer iniciar com ordenação em alguma coluna
        "language": {
            "decimal": '',
            "emptyTable": 'Sem dados disponíveis na tabela',
            "info": '',
            "infoEmpty": '',
            "infoFiltered": '(Filtrado do total de _MAX_ registros)',
            "thousands": ',',
            "loadingRecords": 'Carregando...',
            "search": 'Pesquisar:',
            "zeroRecords": 'Nenhum registro correspondente encontrado',
            "paginate": {
                "first": '',
                "last": '',
                "next": '',
                "previous": ''
            },
            "aria": {
                "sortAscending": ': ativar para classificar a coluna em ordem crescente',
                "sortDescending": ': ativar para classificar a coluna em ordem decrescente'
            }
        },
        "drawCallback": function(settings) {
            // Remove os botões de paginação ao renderizar a tabela
            $('.dataTables_paginate').css('display', 'none');
        }
    });

    // Função para adicionar linhas vazias
    function addEmptyRows() {
        var rowCount = table.rows({ page: 'current' }).count(); // Obtém a contagem de registros na página atual
        var maxRows = {!! json_encode($linhaPagina) !!}; // Número máximo de registros por página
        
        if (rowCount < maxRows) {
            for (var i = 0; i < maxRows - rowCount; i++) {
                $('#prestadores-table tbody').append('<tr class="hidden-row"><td colspan="100%">&nbsp;</td></tr>');
            }
        }
    }

    // Adiciona as linhas vazias ao carregar a página pela primeira vez
    addEmptyRows();

    // Tempo de troca de página (em milissegundos)
    var interval = {!! json_encode($milissegundosPagina) !!};
    var currentPage = 0;  // Começa na página 0 (primeira página)

    // Função para mudar de página
    function changePage() {
        var pageInfo = table.page.info(); // Obtém informações sobre a paginação
        currentPage = (currentPage + 1) % pageInfo.pages; // Calcula a próxima página
        
        table.page(currentPage).draw('page'); // Muda para a próxima página

        // Após a troca da página executa a atualização dos eventos dos prestadores
        const empresa = '{{ $glo_painel_empresa }}';
        const setor = '{{ $glo_painel_setor }}';
        const data = '{{ $glo_painel_data }}';

        // Inicializa a busca e atualização dos eventos
        fetch(`/lancamentos/producao/homePainelProducao/carregaEventosTMO/${empresa}/${setor}/${data}`)
        .then(response => response.json())
        .then(data => {
            updateEvents(data); // Atualiza eventos com os dados recebidos
        })
        .catch(error => {
            console.error('Erro ao buscar eventos:', error); // Exibe erro se ocorrer
        });

        // Adiciona linhas vazias após mudar a página
        addEmptyRows();
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
        const horaAtu = new Date().toTimeString().substr(0, 5);
        
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
            $('#box-funcionamento').attr('class', 'info-box custom-info-box bg-box-funcionamento-danger');
        }
    }
    
    // Atualizar a situação a cada minuto
    atualizarSituacao();
    setInterval(atualizarSituacao, 60000); // 60000 ms = 1 minuto
</script>
@stop