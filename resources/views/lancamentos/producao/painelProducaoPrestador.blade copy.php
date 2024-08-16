@extends('adminlte::page')

@section('title', 'Painel de Prestadores')

@section('content_header')
<div class="row mb-2">
        <div class="col-sm-6">
            <h4 style="margin-bottom: 0px !important;">Painel de Produção</h4>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item active">Filtro do Painel</li>
                <li class="breadcrumb-item active">
                    <a href="{{route('home.painelProducao')}}">Painel de Prestadores</a>
                </li>
            </ol>
        </div>
    </div>
@stop

@section('content')
<x-adminlte-card title="Painel de Operações da Produção" theme="gray" body-class="bg-dark" maximizable>

    <!-- Linha Principal dos Quadros do Painel -->
    <div class="row">
        <!-- Quadro dos dados da Empresa -->
        <div class="col-md-4">
            <x-adminlte-card theme="dark" class="bg-light" style="height: 200px; overflow: hidden;">
                <div class="d-flex justify-content-between align-items-center h-100">
                    <div class="d-flex justify-content-center align-items-center col-md-6" style="height: 100%;">
                        @php 
                            $logo_emp = $dadosEmp->empresa_cnpj."/file/img/".$dadosEmp->empresa_codigo."_logo.png";
                        @endphp
                        <img src="{{ asset($logo_emp) }}" alt="{{ $dadosEmp->empresa_nome }}" style="max-width: 100%; max-height: 90%; display: block;">
                    </div>
                    <div class="d-flex flex-column justify-content-center align-items-center col-md-6" style="height: 100%;">
                        <!-- Título principal -->
                        <h2 class="w-100 text-center" style="font-weight: bold; color: #000">{{ $dadosEmp->empresa_nome }}</h2>
                        <!-- Conteúdo secundário abaixo do título -->
                        <div class="text-muted w-100">
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
                    <x-adminlte-info-box title="Área" text="{{ $dadosSet->setor_area.' - '.$dadosAre->area_desc }}" icon="fas fa-folder-tree text-dark" theme="gradient-teal"/>
                </div>
                <div class="col-md-6">
                    <x-adminlte-info-box title="Setor" text="{{ $dadosSet->setor_codigo.' - '.$dadosSet->setor_desc }}" icon="fas fa-solid fa-screwdriver-wrench text-dark" theme="gradient-teal"/>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    @php 
                        $horaAtu = date('H:i');
                        //$horaAtu = '17:25';
                    @endphp
                    @if($horaIniEx < $horaAtu && $horaAtu <= $horaFinEx)
                    <x-adminlte-info-box title="Situação do Setor" text="Em Funcionamento" icon="fas fa-business-time" theme="gradient-teal" class="custom-info-box"/>
                    @else 
                    <x-adminlte-info-box title="Situação do Setor" text="Fora do Horário de Expediente" icon="fas fa-building-lock text-dark" theme="gradient-teal" class="custom-info-box"/>
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

                                /* A forma original de alterar é assim mas ele não muda o tema por isso mudamos a forma do update
                                let data = {
                                    text: text,
                                    icon: icon,
                                    description: description,
                                    progress: progress
                                };

                                iBox.update(data);
                                */

                                // Remove o InfoBox antigo e insere o novo com o tema atualizado
                                $('#ibUpdatable').parent().html(`
                                    <x-adminlte-info-box 
                                        title="Tempo do Expediente" 
                                        text="${text}" 
                                        icon="fas fa-lg ${iconClass}" 
                                        theme="${theme}" 
                                        id="ibUpdatable" 
                                        progress="${Math.round(progress)}" 
                                        progress-theme="teal" 
                                        description="${description}"
                                        class="custom-info-box"/>
                                `);
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
            <x-adminlte-card theme="success" body-class="bg-light" icon="" title="" style="height: 200px; overflow: hidden;">
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
            <x-adminlte-card theme="dark" body-class="bg-dark" class="elevation-5">    
                <table class="table table-bordered table-striped table-custom">
                    <thead>
                        <tr>
                            <th>Prestadores</th>
                            @foreach($horarios as $horario)
                                <th data-time="{{ $horario }}">{{ $horario }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dadosPrt as $prestador)
                        <tr>
                            <td>{{ $prestador->prestador_codigo.' - '.$prestador->prestador_nome }}</td>
                            @foreach($horarios as $horario)
                                @php
                                    // Gera o Horario do Intervalo/Almoço do prestador
                                    //$inicioInt = strtotime($prestador->intervalo_inicio);
                                    //$finInt = strtotime($prestador->intervalo_fim);
                                    $inicioInt = strtotime('12:00');
                                    $finInt = strtotime('13:10');

                                    // Horário da coluna
                                    $horaColunaIni = strtotime($horario);
                                    $horaColunaFin = strtotime('+29 minutes', $horaColunaIni);

                                    // Inicializa o background
                                    $backgroundInt = '';
                                    $leftInt = '';
                                    $rightInt = '';

                                    // Verifica se a coluna está dentro do intervalo do prestador
                                    if ($horaColunaFin < $inicioInt || $horaColunaIni > $finInt) {
                                        // Se a coluna está fora do intervalo, não aplica background
                                        $backgroundInt = '';
                                    } else {
                                        // Verifica se o intervalo do prestador cobre totalmente a coluna
                                        if ($inicioInt <= $horaColunaIni && $finInt >= $horaColunaFin) {

                                            $backgroundInt = 'int'; // Coluna inteira
                                            $widthPercent = 100;
                                        }
                                        // Verifica se o intervalo do prestador começa antes e termina dentro da coluna
                                        elseif ($inicioInt <= $horaColunaIni && $finInt > $horaColunaIni && $finInt <= $horaColunaFin) {

                                            $difMin = ($finInt - $horaColunaIni) / 60;
                                            $fracaoCol = (100 / 30) * $difMin;
                                            $backgroundInt = 'dir'; // Metade direita da coluna
                                            $widthPercent = $fracaoCol;
                                        }
                                        // Verifica se o intervalo do prestador começa dentro da coluna e termina depois
                                        elseif ($inicioInt >= $horaColunaIni && $finInt >= $horaColunaFin && $inicioInt < $horaColunaFin) {

                                            $difMin = ($horaColunaFin - $inicioInt) / 60;
                                            $fracaoCol = (100 / 30) * $difMin;
                                            $backgroundInt = 'esq'; // Metade esquerda da coluna
                                            $widthPercent = $fracaoCol;
                                        }
                                        // Verifica se o intervalo do prestador começa dentro da coluna e termina depois
                                        elseif ($inicioInt >= $horaColunaIni && $finInt <= $horaColunaFin) {

                                            $difMinIni = ($inicioInt - $horaColunaIni) / 60;
                                            $difMinFin = ($finInt - $horaColunaIni) / 60;
                                            $totMin = $difMinFin - $difMinIni;
                                            $fracaoCol = (100 / 30) * $totMin;
                                            $backgroundInt = 'center';
                                            $widthPercent = $fracaoCol;
                                            $leftInt = ($difMinIni * (100 / 30)) . '%'; // Conversão para percentual
                                            $rightInt = (($horaColunaFin - $finInt) / 60) * (100 / 30) . '%'; // Conversão para percentual
                                        }
                                    }
                                @endphp
                                <td class="event current-time-flag">
                                    @if($backgroundInt == 'int')
                                    <span class="event-lunch-int" style="width: {{ $widthPercent }}%;">Intervalo</span>
                                    @elseif($backgroundInt == 'esq')
                                    <span class="event-lunch-esq" style="width: {{ $widthPercent }}%;">Intervalo</span>
                                    @elseif($backgroundInt == 'dir')
                                    <span class="event-lunch-dir" style="width: {{ $widthPercent }}%;">Intervalo</span>
                                    @elseif($backgroundInt == 'center')
                                    <span class="event-lunch-center" style="left: {{ $leftInt }}; right: {{ $rightInt }}; width: {{ $widthPercent }}%;">Intervalo</span>
                                    @endif

                                    <!--<span class="event-lunch-bar" style="
                                        display: block;
                                        position: absolute;
                                        top: 50%;
                                        left: 0;
                                        transform: translateY(-50%);
                                        height: 30px; /* Altura da barra */
                                        width: 100%;
                                        background-color: #ff9999;
                                        border-radius: 3px;">
                                    </span>-->
                                </td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </x-adminlte-card>
        </div>
    </div>

    <!-- Linha dos blocos dos resumos do painel -->
    <div class="row">
        <div class="col-md-4">
            <x-adminlte-card title="A card without body"/>
        </div>
        <div class="col-md-4">
            <x-adminlte-card title="A card without body"/>
        </div>
        <div class="col-md-4">
            <x-adminlte-card title="A card without body"/>
        </div>
    </div>
</x-adminlte-card>
@stop

@section('plugins.Fullcalendar', true)
@section('plugins.Moment', true)
@section('plugins.Jquery-ui', true)

@section('css')
<style>
    
/* ********** Estilo para a tabela e colunas ********** */
.table {
    table-layout: fixed; /* Garante que as larguras das colunas sejam fixas */
    width: 100%; /* Define a largura da tabela para preencher o container */
}

.table th, .table td {
    overflow: hidden; /* Oculta o texto que ultrapassa o tamanho da célula */
    white-space: nowrap; /* Impede a quebra de linha dentro das células */
    position: relative; /* Necessário para o posicionamento da linha */
}

/* Define largura fixa para a primeira coluna */
.table th:first-child, .table td:first-child {
    width: 15%; /* Largura da coluna para o nome do prestador */
}

/* Define largura fixa para as colunas das horas */
.table th:nth-child(n+2), .table td:nth-child(n+2) {
    width: calc((100% - 15%) / 20); /* Ajusta o tamanho das colunas das horas */
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
    z-index: 10;
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

</style>
@stop

@section('js')
<script>
    // Atualiza a página a cada 30 segundos
    setInterval(function(){
        //window.location.href = window.location.href;
    }, 30000);

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
                timeBox.style.top = `${table.offsetTop - 25}px`; // Posiciona o quadrado acima da tabela
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
@stop