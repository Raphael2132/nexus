@extends('adminlte::page')

@section('title', 'Geral da Empresa')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h4 style="margin-bottom: 0px !important;">Parâmetros Gerais</h4>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active">
                <a href="{{route('home.parametrosSrvEmp')}}">Geral da Empresa</a>
            </li>
            <li class="breadcrumb-item active">Manutenção Geral da Empresa</li>
        </ol>
    </div>
</div>
@stop

@section('content')
<div class="d-flex justify-content-center">
    <div class="col-md-10">
        <!-- Define se o formulario é edição ou novo -->
        <form method="post" action="{{route('parametrosSrvEmp.update')}}" id="quickForm" novalidate="novalidate">
            @csrf 
            <x-adminlte-card title="Manutenção dos Parâmetros Gerais de Serviço" theme="" theme-mode="" header-class="card-nexus" collapsible maximizable>

                <div class="row">
                    @php
                        $data = DB::table('cadastro_empresas')->where('empresa_codigo', $parametrosEmp[0]->parsrv_emp)->get();

                        $new_array1 =[];
                        $new_array2 =[];

                        foreach ($data as $empresa) {
                            $new_array1[] = $empresa->empresa_codigo;
                            $new_array2[] = $empresa->empresa_codigo.' - '.$empresa->empresa_nome;
                        }
                        $array_opt = array_combine($new_array1, $new_array2);

                    @endphp

                    <!-- Empresa do Setor -->
                    <x-adminlte-select name="empresa" fgroup-class="col-md-12">
                        <x-slot name="label">
                            Empresa <span style="color:red;">*</span>
                        </x-slot>
                        <x-adminlte-options :options="$array_opt" empty-option="Selecione..." selected="{{$parametrosEmp[0]->parsrv_emp}}"/>
                    </x-adminlte-select>
                </div>

                <div class="row">              
                    <!-- Aliquota ISS -->
                    <x-adminlte-input name="aliqISS" type="text" placeholder="0,00" value="{{Helper::formataPorcentagem($parametrosEmp[0]->parsrv_alq_iss)}}" fgroup-class="col-md-6">
                        <x-slot name="label">
                            Aliq. ISS <span style="color:red;">*</span>
                        </x-slot>
                    </x-adminlte-input>

                    <!-- CFOP Serviço -->
                    <x-adminlte-input name="srvCFOP" type="number" placeholder="Informe o CFOP" value="{{$parametrosEmp[0]->parsrv_cfop}}" fgroup-class="col-md-6">
                        <x-slot name="label">
                            CFOP Serviço <span style="color:red;">*</span>
                        </x-slot>
                    </x-adminlte-input>
                </div>

                @php
                    $exISS = DB::table('parametros_sis_exi_iss')->get();

                    $new_array1Iss =[];
                    $new_array2Iss =[];

                    foreach ($exISS as $iss) {
                        $new_array1Iss[] = $iss->exiiss_codigo;
                        $new_array2Iss[] = $iss->exiiss_codigo.' - '.$iss->exiiss_desc;
                    }

                    $array_opt_iss = array_combine($new_array1Iss, $new_array2Iss);

                @endphp
                <div class="row">              
                    <!-- Exigibilidade do ISS -->
                    <x-adminlte-select name="exiISS" fgroup-class="col-md-6">
                        <x-slot name="label">
                            Exigibilidade do ISS <span style="color:red;">*</span>
                        </x-slot>
                        <x-adminlte-options :options="$array_opt_iss" empty-option="Selecione..." selected="{{$parametrosEmp[0]->parsrv_exg_iss}}"/>
                    </x-adminlte-select>

                    <!--  ISS Retido -->
                    <x-adminlte-select name="issRet" fgroup-class="col-md-6">
                        <x-slot name="label">
                            ISS Retido <span style="color:red;">*</span>
                        </x-slot>
                        <x-adminlte-options :options="['1' => 'ISS Retido', '2' => 'Sem ISS Retido']" empty-option="Selecione..." selected="{{$parametrosEmp[0]->parsrv_iss_ret}}"/>
                    </x-adminlte-select>
                </div>  

                @php
                    $dadosGrupoSrv = DB::table('parametros_sistema_servico_grupos')->orderBy('grupo_codigo', 'asc')->get();

                    $new_array1_grp =[];
                    $new_array2_grp =[];

                    foreach ($dadosGrupoSrv as $grupoSrv) {
                        $new_array1_grp[] = $grupoSrv->grupo_codigo;
                        $new_array2_grp[] = $grupoSrv->grupo_codigo.' - '.$grupoSrv->grupo_desc;
                    }
                    $array_opt_grp = array_combine($new_array1_grp, $new_array2_grp);

                    if(!empty($parametrosEmp[0]->parsrv_grp_srv)){
                        $dadosCodSrv = DB::table('parametros_sistema_servicos')->where('servico_grupo', $parametrosEmp[0]->parsrv_grp_srv)->orderBy('servico_codigo', 'asc')->get();

                        $new_array1_srv =[];
                        $new_array2_srv =[];

                        foreach ($dadosCodSrv as $codSrv) {
                            $new_array1_srv[] = $codSrv->servico_codigo;
                            $new_array2_srv[] = $codSrv->servico_codigo.' - '.$codSrv->servico_desc;
                        }
                        $array_opt_srv = array_combine($new_array1_srv, $new_array2_srv);

                        $grupo = $parametrosEmp[0]->parsrv_grp_srv;
                        $codigo = $parametrosEmp[0]->parsrv_cod_srv;
                    }else{
                        $array_opt_srv = null;
                        $grupo = null;
                        $codigo = null;
                    }
                @endphp
                <div class="row">
                    <!-- Grupo do Serviço -->
                    <x-adminlte-select name="grupoSrv" fgroup-class="col-md-6">
                        <x-slot name="label">
                            Grupo do Serviço <span style="color:red;">*</span>
                        </x-slot>
                        <x-adminlte-options :options="$array_opt_grp" empty-option="Selecione..." selected="{{$grupo}}"/>
                    </x-adminlte-select>

                    <!-- Código do Serviço -->
                    <x-adminlte-select name="codigoSrv" fgroup-class="col-md-6">
                        <x-slot name="label">
                            Código de Atividade do Serviço <span style="color:red;">*</span>
                        </x-slot>
                        <x-adminlte-options :options="$array_opt_srv" empty-option="Selecione..." selected="{{$codigo}}"/>
                    </x-adminlte-select>
                </div>

                <!-- /.card -->
                <x-slot name="footerSlot">
                    <div class="d-flex justify-content-between w-100">
                        <x-adminlte-button class="btn-nexus btn_salvar" type="submit" label="Salvar" theme="info" icon="fa-solid fa-share-from-square"/>
                        <x-adminlte-button class="btn-nexus" type="button" onclick="window.location='{{ route('home.parametrosSrvEmp') }}'" label="Voltar" theme="info" icon=""/>
                    </div>
                </x-slot>
            </x-adminlte-card>
        </form>
    </div>
</div>
@stop

<!-- Chamada dos Plugins usados na app -->
@section('plugins.jqueryValidation', true)
@section('plugins.Sweetalert2', true)
@section('plugins.Select2', true)
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

        $("#empresa").attr("disabled", true);

        $('#aliqISS').mask('#.##0,00', {reverse: true});

    });
</script>

<!--
|--------------------------------------------------------------------------
| Eventos onChange da app
|--------------------------------------------------------------------------
-->
<script>
    $(document).ready(function() {

        //Evento de carregamento ajax dos dados dos códigos do serviço do grupo selecionado
        $('#grupoSrv').change(function(){

            if( $(this).val() ) {
                var id = $(this).val();

                var url = "{{ route('parametrosSrvTMO.carregaCodSrvAjax', [':id']) }}";
                url = url.replace(':id', id);

                $.ajax({
                    url: url,
                    dataType: "JSON",
                    type: 'GET',
                    data: {
                        '_token': $('meta[name=csrf-token]').attr("content"),
                        '_method': 'GET',
                        "id": id
                    },
                    success: function (data)
                    {
                        var options = '<option value="">Selecione...</option>';	

						for (var i = 0; i < data.servicos_ajax.length; i++) {

							options += '<option value="' + data.servicos_ajax[i].id + '">' + data.servicos_ajax[i].cod_servico + '</option>';
						}	
						$('#codigoSrv').html(options);
                    }
                });
            } else {
				$('#codigoSrv').html('<option value="">Selecione...</option>');
			}
        });
    });
</script>

<!--
|--------------------------------------------------------------------------
| Eventos Validate da app
|--------------------------------------------------------------------------
-->
<script>
$(function () {

    jQuery.validator.addMethod("maxpercent", function(value, element) {
        return this.optional(element) || /^(\d{1,2}|\d{1,2}\,\d{1,2}|100\,[0]{1,2}|100)$/i.test(value);
    }, "Porcentagem máxima de 100,00 %");

    $('#quickForm').validate({
        rules: {
            empresa: {
                required: true
            },
            aliqISS: {
                required: true,
                maxpercent: true
            },
            srvCFOP: {
                required: true,
                maxlength: 4,
                minlength: 4,
            },
            grupoSrv: {
                required: true
            },
            codigoSrv: {
                required: true
            },
            exiISS: {
                required: true
            },
            issRet: {
                required: true
            },
        },
        messages: {
            empresa: {
                required: "Por Favor informe a Empresa"
            },
            aliqISS: {
                required: "Por Favor informe uma Aliquota de ISS"
            },
            srvCFOP: {
                required: "Por Favor informe um CFOP",
                maxlength: "CFOP deve ter 4 digitos",
                minlength: "CFOP deve ter 4 digitos"
            },
            grupoSrv: {
                required: "Por Favor informe o Grupo do Serviço"
            },
            codigoSrv: {
                required: "Por Favor informe o Código de Atividade do Serviço"
            },
            exiISS: {
                required: "Por Favor informe a Exigibilidade do ISS"
            },
            issRet: {
                required: "Por Favor informe o ISS Retido"
            },
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
            // Ativar campos desativados antes de enviar
            $(':disabled').each(function () {
                $(this).removeAttr('disabled');
            });
            form.submit();
        }
    });
});
</script>

<!--
|--------------------------------------------------------------------------
| Eventos de Messagem da app
|--------------------------------------------------------------------------
-->
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
</script>
@stop
