@extends('adminlte::master')

@php( $dashboard_url = View::getSection('dashboard_url') ?? config('adminlte.dashboard_url', 'home') )

@if (config('adminlte.use_route_url', false))
    @php( $dashboard_url = $dashboard_url ? route($dashboard_url) : '' )
@else
    @php( $dashboard_url = $dashboard_url ? url($dashboard_url) : '' )
@endif

@section('adminlte_css')
    @stack('css')
    @yield('css')
@stop

@section('classes_body'){{ ($auth_type ?? 'login') . '-page' }}@stop

<style>
body {
    background:url(html/index/img/pw_maze_black_2X.png) left top repeat !important;
    background-size: auto !important;
}
.container {
    display: flex;
    justify-content: center; /* Centraliza horizontalmente */
    align-items: center; /* Centraliza verticalmente */
    height: 100vh; /* Altura total da viewport, ajuste conforme necessário */
}
.header{
    width: 100%; 
    height: 100%;
    background:url(/img/sistema/fundo3.png) center top no-repeat !important;
    background-size: contain !important;
}
</style>

@section('body')
<div class="d-flex justify-content-center header">
<div class="col-md-2">
    <div class="{{ $auth_type ?? 'login' }}-box container" style="width: 100%;">


        {{-- Card Box --}}
        <div class="card {{ config('adminlte.classes_auth_card', 'card-outline card-primary') }} " style="">

            {{-- Card Header --}}
            @hasSection('auth_header')
                <div class="card-header {{ config('adminlte.classes_auth_header', '') }}" >
                    <h1 class="card-title float-none text-center" style="font-size: 20pt;">
                        @yield('auth_header')
                    </h1>
                </div>
            @endif

            {{-- Logo --}}
        <div class="{{ $auth_type ?? 'login' }}-logo" style="padding: 0px 30px 0px 30px;">
            <a href="{{ $dashboard_url }}">

                {{-- Logo Image --}}
                @if (config('adminlte.auth_logo.enabled', false))
                    <img src="{{ asset(config('adminlte.auth_logo.img.path')) }}"
                         alt="{{ config('adminlte.auth_logo.img.alt') }}"
                         @if (config('adminlte.auth_logo.img.class', null))
                            class="{{ config('adminlte.auth_logo.img.class') }}"
                         @endif
                         @if (config('adminlte.auth_logo.img.width', null))
                            width="{{ config('adminlte.auth_logo.img.width') }}"
                         @endif
                         @if (config('adminlte.auth_logo.img.height', null))
                            height="{{ config('adminlte.auth_logo.img.height') }}"
                         @endif>
                @else
                    <img src="{{ asset(config('adminlte.logo_img')) }}"
                         alt="{{ config('adminlte.logo_img_alt') }}" height="50">
                @endif

                {{-- Logo Label --}}
                <!--{!! config('adminlte.logo', '<b>Admin</b>LTE') !!}-->

            </a>
        </div>

            {{-- Card Body --}}
            <div class="card-body {{ $auth_type ?? 'login' }}-card-body {{ config('adminlte.classes_auth_body', '') }}">
                @yield('auth_body')
            </div>

            {{-- Card Footer --}}
            @hasSection('auth_footer')
                <div class="card-footer {{ config('adminlte.classes_auth_footer', '') }}">
                    @yield('auth_footer')
                </div>
            @endif

        </div>

    </div>
    </div>
    </div>
@stop

@section('adminlte_js')
    @stack('js')
    @yield('js')
@stop
