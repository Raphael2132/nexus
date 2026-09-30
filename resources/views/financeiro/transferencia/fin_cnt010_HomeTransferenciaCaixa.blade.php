@extends('adminlte::page')

@section('title', 'Transferência')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h4 style="margin-bottom: 0px !important;">Transfência de Caixa</h4>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active">Filtro</li>
        </ol>
    </div>
</div>
@stop

@section('content')
<div class="d-flex justify-content-center">
    <div class="col-md-6">
        <form method="post" action="{{route('transferencia.iniciaTransfCaixa',['opcao' => 'Novo'])}}" id="form-transf" novalidate="novalidate">
            @csrf 
            @method('post')
            <x-adminlte-card title="Trasferência de Caixa" theme="" theme-mode="" header-class="card-nexus" collapsible maximizable>
                @php
                    $array_cx = HelperArraySelect::arrayRazoesPorTipo(1,1,'','CX');

                    $caixaUsuario = DB::table('financeiro_tab_usuarios')->where('tabusu_usuario', Auth::user()->usuario_codigo)->where('tabusu_empresa',  Auth::user()->usuario_empresa)->first();

                    $tipoCaixa = $caixaUsuario->tabusu_tipo_razao;
                    $codCaixa = $caixaUsuario->tabusu_razao;

                    $ano = date('Y');

                    $query = "
                        SELECT distinct 
                            razao_codigo, 
                            razao_nome
                        FROM financeiro_razoes
                        WHERE 
                            razao_tipo in('CX','TE') AND 
                            razao_ano = ".$ano." AND 
                            razao_codigo <> '".$codCaixa."' 
                        ORDER BY 
                            razao_codigo ASC
                    ";
                    $data = DB::select($query);

                    $new_array1 =[];
                    $new_array2 =[];

                    foreach ($data as $razao) {
                        $new_array1[] = $razao->razao_codigo;
                        $new_array2[] = $razao->razao_codigo.' - '.$razao->razao_nome;
                    }

                    $array_opt = array_combine($new_array1, $new_array2);
                @endphp
                <div class="row"> 
                    <x-adminlte-select name="cxOrigem" fgroup-class="col-md-12" igroup-size="sm" disabled>
                        <x-slot name="label">
                            Caixa de Origem <span style="color:red;">*</span>
                        </x-slot>
                        <x-adminlte-options :options="$array_cx" empty-option="Selecione..." selected="{{ $codCaixa }}"/>
                    </x-adminlte-select>
                </div>

                <div class="row"> 
                    <x-adminlte-select name="cxteDestino" fgroup-class="col-md-12" igroup-size="sm">
                        <x-slot name="label">
                            Caixa / Tesouraria de Destino <span style="color:red;">*</span>
                        </x-slot>
                        <x-adminlte-options :options="$array_opt" empty-option="Selecione..." selected=""/>
                    </x-adminlte-select>
                </div>
                
                <x-slot name="footerSlot">
                    <x-adminlte-button id="btn-confirmar" class="btn-nexus" type="submit" label="Confirmar" theme="info" icon="fa-solid fa-check"/>
                </x-slot>
            </x-adminlte-card>
        </form>
    </div>
</div>
@stop

<!-- Chamada dos Plugins usados na app -->
@section('plugins.Sweetalert2', true)
@section('plugins.jqueryValidation', true)
@section('plugins.Select2', true)
@section('plugins.BootstrapSelect', true)

@section('css')
@stop

@section('js')
<!--
|--------------------------------------------------------------------------
| Eventos Iniciais da app
|--------------------------------------------------------------------------
-->
<script>
    $(document).ready(function() { 

        // Variáveis vindas do PHP
        const tipoCaixa = @json($tipoCaixa);
        const codCaixa  = @json($codCaixa);

        let bloquear = false;
        let mensagem = '';

        if (tipoCaixa === 'TE') {
            bloquear = true;
            mensagem = 'Seu usuário está vinculado a uma Tesouraria.';
        }

        if (!codCaixa || codCaixa === '') {
            bloquear = true;
            mensagem = 'Seu usuário não possui uma razão associada. Entre em contato com o administrador.';
        }

        if (bloquear) {

            $('#btn-confirmar').prop('disabled', true).addClass('disabled');

            Swal.fire({
                title: 'Aviso!',
                text: mensagem,
                icon: 'info',
                confirmButtonColor: "#007bff",
            });

        }

    });

    function alterarRotaEEnviar(form, opcao) {
        let actionAtual = form.action;
        let novaAction = actionAtual.replace(/(Novo|Editar|Excluir)$/, opcao);
        form.action = novaAction;

        $(':disabled').each(function () {
            $(this).removeAttr('disabled');
        });

        form.submit();
    }

</script>

<!--
|--------------------------------------------------------------------------
| Eventos Validate da app
|--------------------------------------------------------------------------
-->
<script>
$(function () {

    //Inserção / Atualização Recebimento com Cartão de Débito
    $('#form-transf').validate({
        rules: {
            cxOrigem: {
                required: true
            },
            cxteDestino: {
                required: true
            }
        },
        messages: {
            cxOrigem: {
                required:  "Por Favor informe o Caixa de Origem"
            },
            cxteDestino: {
                required:  "Por Favor informe um Caixa / Tesouraria de Destino"
            }
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

            let origem = $('#cxOrigem').val();

            // monta rota dinâmica
            var url = "{{ route('ajax.getTransfCaixaAbertoAjax', [':origem']) }}";
            url = url.replace(':origem', origem);

            $.ajax({
                url: url,
                dataType: "JSON",
                type: 'GET',
                success: function (response) {

                    if (response.success) {

                        if (response.transf_cx_ajax === 'N') {

                            $(':disabled').each(function () {
                                $(this).removeAttr('disabled');
                            });

                            form.submit();

                        } else if (response.transf_cx_ajax === 'S') {

                            Swal.fire({
                                icon: 'info',
                                title: 'Atenção!',
                                html: 'Já existe uma transferência em aberto para este caixa.<br><br>Deseja editar a transferência existente, excluí-la e iniciar uma nova, ou cancelar a operação?',
                                showCancelButton: true,
                                showDenyButton: true,
                                confirmButtonText: 'Editar',
                                denyButtonText: 'Excluir',
                                cancelButtonText: 'Cancelar',
                                confirmButtonColor: "#007bff",
                                denyButtonColor: "#dc3545",
                            }).then((result) => {

                                if (result.isConfirmed) {
                                    // EDITAR
                                    alterarRotaEEnviar(form, 'Editar');
                                } 
                                else if (result.isDenied) {
                                    // EXCLUIR
                                    alterarRotaEEnviar(form, 'Excluir');
                                } 
                                // Cancelar -> não faz nada

                            });
                        }
                    }

                },
                error: function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro!',
                        text: 'Erro na comunicação com o servidor.',
                        confirmButtonColor: "#007bff",
                    });
                }

            });

            return false; // impede envio automático
        }
    });
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
