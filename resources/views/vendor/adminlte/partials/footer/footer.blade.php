@php 
$dataEmp = DB::table('cadastro_empresas')->where('empresa_codigo', Auth::user()->usuario_empresa)->get();
@endphp

<footer class="main-footer">
    @yield('footer')
    <div class="float-right d-none d-sm-block">
        <strong>
            Copyright &copy; {{ date('Y') }} 
            <a href="" target="_blank" rel="noopener noreferrer">
                Fusion Tech Systems
            </a>. Todos os direitos reservados.
        </strong> 
    </div>
    <strong>Usuário: </strong>{{ Auth::user()->name }} <strong>Loja: </strong> {{ Auth::user()->usuario_empresa.' - '.$dataEmp[0]->empresa_nome }}
</footer>
