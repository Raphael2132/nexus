@extends('adminlte::page')

@section('title', 'Contato')

@section('content_header')
<div class="row mb-2">
        <div class="col-sm-6">
            <h4 style="margin-bottom: 0px !important;">Área de Contato</h4>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item active">Contato</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
<div class="row d-flex align-items-stretch">
    <div class="col-md-4 d-flex">
        <x-adminlte-card title="Dados de Contato" theme="" theme-mode="outline" header-class="card-outline-nexus" collapsible maximizable>
            <div class="wow fadeInDown delay-03s" style="margin-top: 20px;">
                <div class="row d-flex align-items-center justify-content-center"> 
                    <div class="text-center logo-contato">
                        <img src="{{ asset('img/sistema/fusion_tech_logo_2.png') }}" class="rounded" alt="..." style="max-width:60%;  width: auto; height: auto;">
                    </div>
                </div>
            </div>
            <div class="row d-flex align-items-center justify-content-center" style="margin-top: 20px;">
                <div class="wow fadeInLeft delay-05s">
                    <div class="contact-info-box address clearfix">
                        <h3><i class="fa-solid fa-location-dot"></i></i>Endereço:</h3>
                        <span>Rua Daércio Quero Robles, 120<br>CEP: 13876-341<br>Jardim Almeida<br>São João da Boa Vista, São Paulo</span>
                    </div>
                    <div class="contact-info-box phone clearfix">
                        <h3><i class="fa-solid fa-phone"></i>Telefone:</h3>
                        <span>(19) 9 9230-4154</span>
                    </div>
                    <div class="contact-info-box email clearfix">
                        <h3><i class="fa-solid fa-envelope"></i></i>Email:</h3>
                        <span>suporte@fusiontechsystems.com.br</span>
                    </div>
                    <div class="contact-info-box hours clearfix">
                        <h3><i class="fa-solid fa-clock"></i></i>Horário Atendimento:</h3>
                        <span><strong>Segunda à Sexta: </strong> 09:00 - 18:00<br><strong>Sábado: </strong> 09:00 - 13:00<br><strong>Domingo e Feriados: </strong>Fechado</span>
                    </div>
                    <div class="wow fadeInLeft delay-06s">
                        <ul class="social-link d-flex align-items-center justify-content-center">
                            <li class="linkedin"><a href="#"><i class="fa-brands fa-linkedin"></i></i></a></li>
                            <li class="facebook"><a href="#"><i class="fa-brands fa-facebook-f"></i></a></li>
                            <li class="youtube"><a href="#"><i class="fa-brands fa-youtube"></i></a></li>
                            <li class="instagram"><a href="#"><i class="fa-brands fa-instagram"></i></a></li>
                            <li class="whatsapp"><a href="#"><i class="fa-brands fa-whatsapp"></i></a></li>
                            <li class="site"><a href="https://fusiontechsystems.com.br/" target="_blank"><i class="fa-solid fa-globe"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </x-adminlte-card>
    </div>
    <div class="col-md-8">
        <form method="post" action="{{route('contato.email')}}" id="formulario-contato" novalidate="novalidate">
        @csrf
            <x-adminlte-card title="Formulário de Contato" theme="" theme-mode="" header-class="card-nexus" collapsible maximizable>
                @php
                    $data = DB::table('cadastro_empresas')->orderBy('empresa_codigo', 'asc')->get();

                    $new_array1 =[];
                    $new_array2 =[];

                    foreach ($data as $empresa) {
                        $new_array1[] = $empresa->empresa_codigo.' - '.$empresa->empresa_nome.' (CNPJ - '.$empresa->empresa_cnpj.')';
                        $new_array2[] = $empresa->empresa_codigo.' - '.$empresa->empresa_nome;
                    }
                    $array_opt = array_combine($new_array1, $new_array2);

                    $dataEma = DB::table('parametros_sis_email_setores')->orderBy('emaset_cod', 'asc')->get();

                    $new_array_ema1 =[];
                    $new_array_ema2 =[];

                    foreach ($dataEma as $email) {
                        $new_array_ema1[] = $email->emaset_cod;
                        $new_array_ema2[] = $email->emaset_desc;
                    }
                    $array_opt_ema = array_combine($new_array_ema1, $new_array_ema2);
                @endphp
                <div class="row"> 
                    <!-- Empresa do Setor -->
                    <x-adminlte-select name="empresa" fgroup-class="col-md-12">
                        <x-slot name="label">
                            Empresa <span style="color:red;">*</span>
                        </x-slot>
                        <x-adminlte-options :options="$array_opt" empty-option="Selecione" />
                    </x-adminlte-select>
                </div>
                <div class="row"> 
                    <!-- Setor Atendimento -->
                    <x-adminlte-select name="setor" fgroup-class="col-md-12">
                        <x-slot name="label">
                            Setor de Atendimento <span style="color:red;">*</span>
                        </x-slot>
                        <x-adminlte-options :options="$array_opt_ema" empty-option="Selecione" />
                    </x-adminlte-select>
                </div>
                <div class="row">
                    <x-adminlte-input name="assunto" type="text" placeholder="Informe o assunto" fgroup-class="col-md-12">
                        <x-slot name="label">
                            Assunto <span style="color:red;">*</span>
                        </x-slot>
                        <x-slot name="prependSlot">
                            <div class="input-group-text x-slot-nexus">
                                <i class="fa-solid fa-list-ul"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>
                </div>
                <div class="row">
                    <x-adminlte-input name="email" type="email" placeholder="email@exemplo.com" fgroup-class="col-md-12">
                        <x-slot name="label">
                            Email de Contato <span style="color:red;">*</span>
                        </x-slot>
                        <x-slot name="prependSlot">
                            <div class="input-group-text x-slot-nexus">
                                <i class="fa-solid fa-envelope"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>
                </div>
                <div class="row">
                    {{-- With placeholder, sm size, label and some configuration --}}
                    @php
                    $config = [
                        "height" => "150",
                        "toolbar" => [
                            // [groupName, [list of button]]
                            ['style', ['style']],
                            ['style', ['bold', 'italic', 'underline', 'clear']],
                            ['fontname', ['fontname']],
                            ['font', ['strikethrough', 'superscript', 'subscript']],
                            ['fontsize', ['fontsize']],
                            ['color', ['color']],
                            ['para', ['ul', 'ol', 'paragraph']],
                            ['height', ['height']],
                            ['table', ['table']],
                            ['insert', ['link', 'picture', 'video']],
                            ['view', ['fullscreen', 'codeview', 'help']],
                        ],
                    ]
                    @endphp
                    <x-adminlte-text-editor name="mensagem" igroup-size="sm" placeholder="Informe a sua mensagem aqui..." :config="$config" fgroup-class="col-md-12">
                        <x-slot name="label">
                            Mensagem <span style="color:red;">*</span>
                        </x-slot>
                    </x-adminlte-text-editor>
                </div>
                <x-slot name="footerSlot">
                    <x-adminlte-button class="btn-nexus" type="submit" label="Enviar" theme="info" icon="fa-solid fa-paper-plane"/>
                </x-slot>
            </x-adminlte-card>
        </form>
    </div>
</div>
@stop

<!-- Chamada dos Plugins usados na app -->
@section('plugins.Sweetalert2', true)
@section('plugins.Summernote', true)
@section('plugins.jqueryValidation', true)

@section('css')
<style>
    /* Contact ---------------------------------*/
    .main-section.contact{
        padding:90px 0 100px;
    }

    .main-section.contact{
        background:url(../img/bg-map.png) left 190px no-repeat;
    }
    .contact-info-box{
        font-size:15px;
    }
    .contact-info-box h3{
        font-size: 15px;
        font-weight:400;
        float:left;
        width:102px;
        margin-right:12px;
        line-height:28px;
    }
    .contact-info-box h3 i{
        font-style:normal;
        font-size:18px;
        color:#222222;
        font-family: 'FontAwesome';
        font-weight:normal;
        margin-right:7px;
    }
    .contact-info-box span{
        line-height:28px;
        display:block;
        overflow:hidden;
    }
    .social-link{
        /*padding:35px 0;
        margin:0 0 0 68px;*/
        display:block;
        overflow:hidden;
        list-style:none;
        height: 100px;
    }
    .social-link li{
        float:left;
        margin-right:8px;
    }
    .social-link li a{
        display:block;
        width:50px;
        height:50px;
        text-align:center;
        line-height:50px;
        font-size:25px;
        color:#fff;
        background:#222222;
        border-radius:50%;
        transition:all 0.3s ease-in-out;
    }
    .social-link li a:hover, .social-link li a:focus{
        text-decoration:none;
    }
    .linkedin a:hover {
        background: #0e76a8 ;
        transform: scale(1.2);
    }
    .facebook a:hover {
        background: #3b5998;
        transform: scale(1.2);
    }
    .whatsapp a:hover {
        background: #45c355;
        transform: scale(1.2);
    }
    .instagram a:hover {
        background: linear-gradient(
            45deg,
            #f09433 0%,
            #e6683c 25%,
            #dc2743 50%,
            #cc2366 75%,
            #bc1888 100%
        );
        transform: scale(1.2);
    }
    .youtube a:hover {
        background: #ff0000;
        transform: scale(1.2);
    }
    .site a:hover {
        background: #0000ff;
        transform: scale(1.2);
    }

    .form{
        margin:0 66px 0 30px;
    }
    .input-text{
        padding:15px 16px;
        border:1px solid #ccc;
        width:100%;
        height:50px;
        display:block;
        border-radius:4px;
        font-size:15px;
        color:#aaa;
        font-family: 'Open Sans', sans-serif;
        margin:0 0 15px 0;
        transition:all 0.3s ease-in-out;
        -moz-transition:all 0.3s ease-in-out;
        -webkit-transition:all 0.3s ease-in-out;
    }
    .input-text:focus {
        border: 1px solid #7cc576;
        outline:0;
        -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 8px rgba(124, 197, 118, 0.3);
        -moz-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 8px rgba(124, 197, 118, 0.3);	
        box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 8px rgba(124, 197, 118, 0.3);
    }

    .input-text.text-area{
        height:165px;
        resize:none;
        overflow:auto;
    }
    .input-btn{
        width:175px;
        height:50px;
        background:#7cc576;
        border-radius:4px;
        color:#ffffff;
        font-size:14px;
        text-transform:uppercase;
        font-family: 'Montserrat', sans-serif;
        font-weight:400;
        border:0px;
        transition:all 0.3s ease-in-out;
        -moz-transition:all 0.3s ease-in-out;
        -webkit-transition:all 0.3s ease-in-out;
    }

    .input-btn:hover{
        background: #111;
        color: #fff;
    }

    /* Clear Floated Elements
    ---------------------------------*/

    .clearfix:before,
    .clearfix:after {
    content: '\0020';
    display: block;
    overflow: hidden;
    visibility: hidden;
    width: 0;
    height: 0;
    }

    .clearfix:after {
    clear: both;
    }

    /* Animation Timers
    ---------------------------------*/
    .delay-02s { 
        animation-delay: 0.2s; 
        -webkit-animation-delay: 0.2s; 
    }
    .delay-03s { 
        animation-delay: 0.3s; 
        -webkit-animation-delay: 0.3s; 
    }
    .delay-04s { 
        animation-delay: 0.4s; 
        -webkit-animation-delay: 0.4s; 
    }

    .delay-05s { 
        animation-delay: 0.5s; 
        -webkit-animation-delay: 0.5s; 
    }
    .delay-06s { 
        animation-delay: 0.6s; 
        -webkit-animation-delay: 0.6s; 
    }

    .delay-07s { 
        animation-delay: 0.7s; 
        -webkit-animation-delay: 0.7s; 
    }
    .delay-08s { 
        animation-delay: 0.8s; 
        -webkit-animation-delay: 0.8s; 
    }

    .delay-09s { 
        animation-delay: 0.9s; 
        -webkit-animation-delay: 0.9s; 
    }
    .delay-1s { 
        animation-delay: 1s; 
        -webkit-animation-delay: 1s; 
    }
    .delay-12s { 
        animation-delay: 1.2s; 
        -webkit-animation-delay: 1.2s; 
    }
    .delay-10s { 
        animation-delay: 1.0s; 
        -webkit-animation-delay: 1.0s; 
    }
    .delay-11s { 
        animation-delay: 1.1s; 
        -webkit-animation-delay: 1.1s; 
    }
</style>

<link rel="stylesheet" href="{{ asset('html/contato/css/responsive.css') }}">
<link rel="stylesheet" href="{{ asset('html/contato/css/animate.css') }}">
@stop

@section('js')
<script src="{{asset('html/contato/js/wow.js')}}"></script>

<script>
    wow = new WOW(
        {
        animateClass: 'animated',
        offset:       100
        }
    );
    wow.init();
</script>

<script>
$(function () {
    $('#formulario-contato').validate({
        rules: {
            email: {
                required: true,
                email: true,
            },
            empresa: {
                required: true,
            },
            assunto: {
                required: true
            },
            setor: {
                required: true
            },
            mensagem: {
                required: true
            },
        },
        messages: {
            email: {
                required: "Por Favor Informe o Email",
                email: "Formato do Email inválido",
            },
            empresa: {
                required: "Por Favor Informe a Empresa",
            },
            assunto: {
                required: "Por Favor Informe o Assunto"
            },
            setor: {
                required: "Por Favor Informe o Setor"
            },
            mensagem: {
                required: "Por Favor Informe a Mensagem"
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