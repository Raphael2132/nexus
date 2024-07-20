@extends('adminlte::page')

@section('title', 'Cadastro de Clientes')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h4 style="margin-bottom: 0px !important;">Cadastros</h4>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active">
                <a href="{{route('home.clientes')}}">Clientes</a>
            </li>
            <li class="breadcrumb-item active">Cadastro de Cliente</li>
        </ol>
    </div>
</div>
@stop

@section('content')

<form method="post" action="{{route('cliente.inserir')}}" id="quickForm" novalidate="novalidate">
    @csrf 
    @method('post')
    <x-adminlte-card title="Cadastro de Novo Cliente" theme="navy">
        <div class="row"> 
            <!-- Tipo de Cadastro -->
            <x-adminlte-select name="tipoCadastro" fgroup-class="col-md-6">
                <x-slot name="label">
                    Tipo de Cadastro <span style="color:red;">*</span>
                </x-slot>
                <x-adminlte-options :options="['C' => 'Cliente', 'F' => 'Fornecedor']" empty-option="Selecione..."/>
            </x-adminlte-select>

            <!-- Tipo de Pessoa -->
            <x-adminlte-select name="tipoPessoa" fgroup-class="col-md-6">
                <x-slot name="label">
                    Tipo de Pessoa <span style="color:red;">*</span>
                </x-slot>
                <x-adminlte-options :options="['F' => 'Física', 'J' => 'Jurídica']" empty-option="Selecione..."/>
            </x-adminlte-select>
        </div>

        <div class="row">
            <!-- Nome -->
            <x-adminlte-input name="nome" type="text" placeholder="Nome Completo" fgroup-class="col-md-8">
                <x-slot name="label">
                    Nome <span style="color:red;">*</span>
                </x-slot>
            </x-adminlte-input>

            <!-- CPF / CNPJ -->
            <x-adminlte-input name="cpfCnpj" type="text" fgroup-class="col-md-4">
                <x-slot name="label">
                    CPF / CNPJ <span style="color:red;">*</span>
                </x-slot>
            </x-adminlte-input>
        </div>

        <div id="dadosPessoal">
            <div class="row">
                <!-- RG -->
                <x-adminlte-input name="rg" type="text" label="RG" fgroup-class="col-md-4"></x-adminlte-input>

                @php
                $config = [
                    "singleDatePicker" => true,
                    "showDropdowns" => true,
                    "startDate" => "js:moment()",
                    "minYear" => 1900,
                    "maxYear" => "js:parseInt(moment().format('YYYY'),10)",
                    "timePicker" => false,
                    "timePicker24Hour" => false,
                    "timePickerSeconds" => false,
                    "cancelButtonClasses" => "btn-danger",
                    "locale" => ["format" => "DD/MM/YYYY"],
                ];
                @endphp
                <!-- Data de Nascimento -->
                <x-adminlte-date-range name="dataNascimento" label="Data de Nascimento" :config="$config" placeholder="Formato dia/mês/ano" fgroup-class="col-md-4">
                    <x-slot name="prependSlot">
                        <div class="input-group-text">
                            <i class="far fa-lg fa-calendar-alt"></i>
                        </div>
                    </x-slot>
                </x-adminlte-date-range>
                @push('js')<script>$(() => $("#dataNascimento").val(''))</script>@endpush

                <!-- Sexo -->
                <x-adminlte-select name="sexo" label="Sexo" fgroup-class="col-md-4">
                    <x-adminlte-options :options="['M' => 'Masculino', 'F' => 'Feminino']" empty-option="Selecione..."/>
                </x-adminlte-select>
            </div>
        </div>

        <div id="dadosJuridicos">
            <div class="row">
                <!-- Inscrição Estadual -->
                <x-adminlte-input name="insEstadual" type="number" label="Inscrição Estadual" fgroup-class="col-md-6"></x-adminlte-input>

                <!-- Inscrição Municipal -->
                <x-adminlte-input name="insMunicipal" type="number" label="Inscrição Municipal" fgroup-class="col-md-6"></x-adminlte-input>
            </div>
        </div>
        <!-- /.card -->
        <x-slot name="footerSlot">
            <div class="d-flex justify-content-between w-100">
                <x-adminlte-button type="submit" label="Salvar" theme="info" icon="fa-solid fa-share-from-square"/>
                <x-adminlte-button type="button" onclick="window.location='{{ route('home.clientes') }}'" label="Voltar" theme="info" icon=""/>
            </div>
        </x-slot>
    </x-adminlte-card>
</form>
@stop

@section('css')
@stop

<!-- Chamada dos Plugins usados na app -->  
@section('plugins.Select2', true)
@section('plugins.DateRangePicker', true)
@section('plugins.Inputmask', true)
@section('plugins.jqueryValidation', true)
@section('plugins.Sweetalert2', true)
@section('plugins.toastr', true)

@section('js')
<script>
    $(document).ready(function() {

        // Init input mask on the target element.

        $('#rg').inputmask({
            "mask": "99.999.999-9",
            // Specify other options...
        });

        if($("#tipoPessoa").val() == ''){

            $("#cpfCnpj").hide();
            $('label[for="cpfCnpj"]').css("display","none");

            $("#nome").hide();
            $('label[for="nome"]').css("display","none");

            $("#dadosPessoal").hide();
            $("#dadosJuridicos").hide();
        }

        $("#tipoCadastro").change(function(){
        
            if(this.value == 'F'){
                $("#tipoPessoa").prop('disabled', true);
                $("#tipoPessoa").val('J');
                $("#rg").val('');
                $("#nome").val('');
                $("#cpfCnpj").val('');
                $("#sexo").val('');
                $("#dataNascimento").val('');
                $("#insEstadual").val('');
                $("#insMunicipal").val('');

                $("#dadosPessoal").hide();
                $("#dadosJuridicos").show();

                $("#cpfCnpj").show();
                $('label[for="cpfCnpj"]').show();
                
                $("#nome").show();
                $('label[for="nome"]').show();

                $('#cpfCnpj').inputmask({
                    "mask": " 99.999.999/9999-99",
                    // Specify other options...
                });
            }else{
                $("#tipoPessoa").val('');
                $("#tipoPessoa").prop('disabled', false);
                $("#rg").val('');
                $("#nome").val('');
                $("#cpfCnpj").val('');
                $("#sexo").val('');
                $("#dataNascimento").val('');
                $("#insEstadual").val('');
                $("#insMunicipal").val('');

                $("#dadosPessoal").hide();
                $("#dadosJuridicos").hide();

                $("#cpfCnpj").hide();
                $('label[for="cpfCnpj"]').css("display","none");

                $("#nome").hide();
                $('label[for="nome"]').css("display","none");
            }
        });
    });

    $("#tipoPessoa").change(function(){
        
        if(this.value == 'F'){
            $("#rg").val('');
            $("#nome").val('');
            $("#cpfCnpj").val('');
            $("#sexo").val('');
            $("#dataNascimento").val('');
            $("#insEstadual").val('');
            $("#insMunicipal").val('');

            $("#dadosPessoal").show();
            $("#dadosJuridicos").hide();

            $("#cpfCnpj").show();
            $('label[for="cpfCnpj"]').show();

            $("#nome").show();
            $('label[for="nome"]').show();

            $('#cpfCnpj').inputmask({
                "mask": "999.999.999-99",
                // Specify other options...
            });

        }else if(this.value == 'J'){
            $("#rg").val('');
            $("#nome").val('');
            $("#cpfCnpj").val('');
            $("#sexo").val('');
            $("#dataNascimento").val('');
            $("#insEstadual").val('');
            $("#insMunicipal").val('');

            $("#dadosPessoal").hide();
            $("#dadosJuridicos").show();

            $("#cpfCnpj").show();
            $('label[for="cpfCnpj"]').show();
            
            $("#nome").show();
            $('label[for="nome"]').show();

            $('#cpfCnpj').inputmask({
                "mask": " 99.999.999/9999-99",
                // Specify other options...
            });

        }else{
            $("#rg").val('');
            $("#nome").val('');
            $("#cpfCnpj").val('');
            $("#sexo").val('');
            $("#dataNascimento").val('');
            $("#insEstadual").val('');
            $("#insMunicipal").val('');
            
            $("#dadosPessoal").hide();
            $("#dadosJuridicos").hide();

            $("#cpfCnpj").hide();
            $('label[for="cpfCnpj"]').css("display","none");

            $("#nome").hide();
            $('label[for="nome"]').css("display","none");
        }
    });
</script>

<script>
$(function () {
    $('#quickForm').validate({
        rules: {
            tipoCadastro: {
                required: true
            },
            tipoPessoa: {
                required: true
            },
            nome: {
                required: true,
                minlength: 5
            },
            cpfCnpj: {
                required: true
            },
        },
        messages: {
            tipoCadastro: {
                required: "Por Favor informe um Tipo de Cadastro"
            },
            tipoPessoa: {
                required: "Por Favor informe um Tipo de Pessoa"
            },
            nome: {
                required: "Por Favor informe o Nome do Cliente",
                minlength: "Infome no mínimo 5 caracteres"
            },
            cpfCnpj: {
                required: "Por Favor informe um CPF / CNPJ"
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

<script>
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
