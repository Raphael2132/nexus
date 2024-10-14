@php
    $dataEmp = null;

    //Verificamos se o usuário está logado por que quando acontece o erro 404 não reconhece mais o usuário logado e talves outros erros aconteçam a mesma coisa
    if (Auth::check()) {
        $dataEmp = DB::table('cadastro_empresas')->where('empresa_codigo', Auth::user()->usuario_empresa)->get();
    }
@endphp

<footer class="main-footer" style="padding: .6rem; !important">
    @yield('footer')
    <div class="float-right d-none d-sm-block">
        <strong>
            Copyright &copy; {{ date('Y') }} 
            <a href="https://fusiontechsystems.com.br/" target="_blank" rel="noopener noreferrer">
                Fusion Tech Systems
            </a>. Todos os direitos reservados.
        </strong> 
    </div>
    <!-- Verificamos se o usuário está logado por que quando acontece o erro 404 não reconhece mais o usuário logado e talves outros erros aconteçam a mesma coisa -->
    @if(Auth::check())
    <strong>Usuário: </strong>{{ Auth::user()->usuario_codigo.' - '.Auth::user()->name }} <strong>Loja: </strong> {{ Auth::user()->usuario_empresa.' - '.$dataEmp[0]->empresa_nome }}
    @endif
</footer>
