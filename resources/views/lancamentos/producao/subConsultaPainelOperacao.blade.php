<!-- Se Existe Tarefas Aprovadas na OS gera a tabela dos serviços -->
@if(!empty($dadosTMO[0]))
<table class="table nested-table">
    <thead>
        <tr>
            <th>Req.</th>
            <th>Setor</th>
            <th>Auxiliar</th>
            <th>Prestador</th>
            <th>TMO</th>
            <th>Situação</th>
            <th>Tempo</th>
            <th>Hr Ini</th>
            <th>Hr Fin</th>
            <th>Hr Real</th>
            <th>Saldo</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($dadosTMO as $tmo)
            @php 
                if(!empty($tmo->exetrf_prt)){
                    $dadosPrest =  DB::table('cadastro_prestadores')->where('prestador_codigo', $tmo->exetrf_prt)->get();
                    $prestador = $tmo->exetrf_prt.' - '.$dadosPrest[0]->prestador_nome;
                }else{
                    $prestador = 'Não Alocado';
                }
            @endphp
            <tr>
                <td>{{ $tmo->exetrf_req }}</td>
                <td>{{ $tmo->exetrf_set }}</td>
                <td>
                    @php 
                        $cntAux = DB::table('lancamento_srv_prt_auxiliares')->where('prtaux_emp', $tmo->exetrf_emp)->where('prtaux_nos', $tmo->exetrf_nos)->where('prtaux_req', $tmo->exetrf_req)->where('prtaux_srv', $tmo->exetrf_seq)->count('prtaux_prt');
                    @endphp
                    <button type="button" class="btn btn-xl" title="Adicionar Novo Auxiliar" 
                        onclick="openModalAddAux('{{ $tmo->exetrf_emp }}', '{{ $tmo->exetrf_nos }}', '{{ $tmo->exetrf_req }}', '{{ $tmo->exetrf_seq }}')">
                        @if($cntAux == 0)
                        <i class="fa-solid fa-users-slash fa-lg" style="color: #808080;"></i>
                        @else
                        <i class="fa-solid fa-users fa-lg" style="color: #74C0FC;"></i>
                        @endif
                    </button>
                </td>
                <td style="display: flex; align-items: center;">
                    <button type="button" class="btn btn-xl" title="Adicionar Novo Auxiliar"
                        onclick="openModalAddChangePrt('{{ $tmo->exetrf_emp }}', '{{ $tmo->exetrf_nos }}', '{{ $tmo->exetrf_req }}', '{{ $tmo->exetrf_seq }}')">
                        @if(!empty($tmo->exetrf_prt))
                        <i class="fa-solid fa-user-pen fa-lg" style="color: #39cccc;"></i>
                        @else
                        <i class="fa-solid fa-user-plus fa-lg" style="color: #007bff;"></i>
                        @endif
                    </button>
                    <span style="flex-grow: 1; text-align: center;">{{ $prestador }}</span>
                </td>
                <td>{{$tmo->exetrf_tmo.' - '.$tmo->exetrf_desc}}</td>
                <td>
                    @if($tmo->exetrf_sts == 'F')
                    <div class="btn-group" style="width: 100%;"style="width: 100%;">
                        <button type="button" class="btn btn-success btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Finalizada <span class="caret"></span>
                        </button>	
                        <ul class="dropdown-menu">
                            <li><a href="#" class="execucao-tarefa" onclick="openModalReopenService('{{ $tmo->exetrf_emp }}', '{{ $tmo->exetrf_nos }}', '{{ $tmo->exetrf_req }}', '{{ $tmo->exetrf_seq }}')">Reabrir</a></li>
                        </ul>		
                    </div>
                    @elseif($tmo->exetrf_sts == 'C')
                    <div class="btn-group" style="width: 100%;"style="width: 100%;">
                        <button type="button" class="btn btn-danger btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Cancelada <span class="caret"></span>
                        </button>	
                        <ul class="dropdown-menu">
                            <li><a href="#" class="execucao-tarefa" onclick="openModalReopenService('{{ $tmo->exetrf_emp }}', '{{ $tmo->exetrf_nos }}', '{{ $tmo->exetrf_req }}', '{{ $tmo->exetrf_seq }}')">Reabrir</a></li>
                        </ul>		
                    </div>
                    @elseif($tmo->exetrf_sts == 'S')
                    <div class="btn-group" style="width: 100%;"style="width: 100%;">
                        <button type="button" class="btn btn-warning btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Suspensa <span class="caret"></span>
                        </button>	
                        <ul class="dropdown-menu">
                            <li><a href="#" class="execucao-tarefa" onclick="openModalReopenService('{{ $tmo->exetrf_emp }}', '{{ $tmo->exetrf_nos }}', '{{ $tmo->exetrf_req }}', '{{ $tmo->exetrf_seq }}')">Reabrir</a></li>
                            <li><a href="#" class="execucao-tarefa" onclick="openModalCancelService('{{ $tmo->exetrf_emp }}', '{{ $tmo->exetrf_nos }}', '{{ $tmo->exetrf_req }}', '{{ $tmo->exetrf_seq }}')">Cancelar</a></li>
                        </ul>		
                    </div>
                    @elseif($tmo->exetrf_sts == 'A')
                    <div class="btn-group" style="width: 100%;"style="width: 100%;">
                        <button type="button" class="btn btn-info btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Em Andamento <span class="caret"></span>
                        </button>	
                        <ul class="dropdown-menu">
                            <li><a href="#" class="execucao-tarefa" onclick="openModalFinishService('{{ $tmo->exetrf_emp }}', '{{ $tmo->exetrf_nos }}', '{{ $tmo->exetrf_req }}', '{{ $tmo->exetrf_seq }}')">Finalizar</a></li>
                            <li><a href="#" class="execucao-tarefa" onclick="openModalCancelService('{{ $tmo->exetrf_emp }}', '{{ $tmo->exetrf_nos }}', '{{ $tmo->exetrf_req }}', '{{ $tmo->exetrf_seq }}')">Cancelar</a></li>
                            <li><a href="#" class="execucao-tarefa" onclick="openModalSuspendService('{{ $tmo->exetrf_emp }}', '{{ $tmo->exetrf_nos }}', '{{ $tmo->exetrf_req }}', '{{ $tmo->exetrf_seq }}')">Suspender</a></li>
                        </ul>		
                    </div>
                    @elseif($tmo->exetrf_sts == 'E')
                    <div class="btn-group" style="width: 100%;"style="width: 100%;">
                        <button type="button" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Em Espera <span class="caret"></span>
                        </button>	
                        <ul class="dropdown-menu">
                            <li><a href="#" class="execucao-tarefa" onclick="openModalStartService('{{ $tmo->exetrf_emp }}', '{{ $tmo->exetrf_nos }}', '{{ $tmo->exetrf_req }}', '{{ $tmo->exetrf_seq }}')">Iniciar</a></li>
                            <li><a href="#" class="execucao-tarefa" onclick="openModalCancelService('{{ $tmo->exetrf_emp }}', '{{ $tmo->exetrf_nos }}', '{{ $tmo->exetrf_req }}', '{{ $tmo->exetrf_seq }}')">Cancelar</a></li>
                            <li><a href="#" class="execucao-tarefa" onclick="openModalSuspendService('{{ $tmo->exetrf_emp }}', '{{ $tmo->exetrf_nos }}', '{{ $tmo->exetrf_req }}', '{{ $tmo->exetrf_seq }}')">Suspender</a></li>
                        </ul>		
                    </div>
                    @endif
                </td>
                <td>{{ $tmo->exetrf_qhr }}</td>
                <td>{{ $tmo->exetrf_sts != 'E' && !empty($tmo->exetrf_dt_ini_srv) ? Helper::formataHoraMinuto($tmo->exetrf_hr_ini_srv) : '' }}</td>
                <td>{{ $tmo->exetrf_sts == 'F' ? Helper::formataHoraMinuto($tmo->exetrf_hr_fin_srv) : '' }}</td>
                <td>{{ $tmo->exetrf_sts == 'F' ? $tmo->exetrf_qhr_real : '' }}</td>
                <td>
                    @if($tmo->exetrf_sts == 'F')
                        @if($tmo->exetrf_qhr_saldo > 0)
                            <span class="text-primary">{{ $tmo->exetrf_qhr_saldo }} <i class="fa-solid fa-angles-right"></i></span>
                        @elseif($tmo->exetrf_qhr_saldo < 0)
                            <span class="text-danger">{{ $tmo->exetrf_qhr_saldo }} <i class="fa-solid fa-angles-left"></i></span>
                        @else
                            <span class="text-success">{{ $tmo->exetrf_qhr_saldo }} <i class="fa-solid fa-stop"></i></span>
                        @endif
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
@else
<table class="table nested-table">
    <thead>
        <tr>
            <th>Req.</th>
            <th>Setor</th>
            <th>Auxiliar</th>
            <th>Prestador</th>
            <th>TMO</th>
            <th>Situação</th>
            <th>Tempo</th>
            <th>Hr Ini</th>
            <th>Hr Fin</th>
            <th>Hr Real</th>
            <th>Saldo</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td colspan='11'>Ordem de Serviço não tem TMO aprovada</td>
        </tr>
    </tbody>
</table>
@endif