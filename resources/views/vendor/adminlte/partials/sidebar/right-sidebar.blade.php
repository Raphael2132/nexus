<aside class="control-sidebar control-sidebar-{{ config('adminlte.right_sidebar_theme') }}">
    @yield('right-sidebar')

    <!-- Adiciona conteúdo personalizado ao Control Sidebar -->
    <div class="p-3 text-center">
        <!-- Nome do usuário centralizado -->
        <h6><strong>{{ Auth::user()->name }}</strong></h6>
        <hr class="mb-2">

        <!-- Botão de logout com fundo verde retangular e efeito hover -->
        <a href="{{ route('logout') }}" 
        onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
        style="background-color: #008f8f; width: 90%; padding: 10px; margin: 0 auto; display: block; color: white; text-align: center; text-decoration: none; transition: background-color 0.3s; border-radius: 5px;"
        onmouseover="this.style.backgroundColor='#007373'"
        onmouseout="this.style.backgroundColor='#008f8f'">
            <i class="fas fa-sign-out-alt fa-2x"></i> <!-- Ícone branco sobre fundo verde -->
        </a>

        <!-- Formulário para o logout -->
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    </div>

    <div class="p-3">
        <h5><strong>Configurações</strong></h5>
        <hr class="mb-2">
        @php 
            $data = DB::table('cadastro_empresas')->orderBy('empresa_codigo', 'asc')->get();

            $new_array1 =[];
            $new_array2 =[];

            foreach ($data as $empresa) {
                $new_array1[] = $empresa->empresa_codigo;
                $new_array2[] = $empresa->empresa_codigo.' - '.$empresa->empresa_nome;
            }
            $array_opt = array_combine($new_array1, $new_array2);

            // Buscar o plano da empresa
            $empresaPlano = DB::table('parametros_sis_modulos')
                ->where('modulo_empresa_codigo', session('glo_empresa_exibicao_home'))
                ->value('modulo_plano');

            // Criar um array com base no plano da empresa
            if ($empresaPlano == 'BS01') {
                $array_plano = [
                    'homeNFSeSimplificada' => 'Emissão Simplificada de NFS-e'
                ];
            } elseif ($empresaPlano == 'BE02') {
                $array_plano = [
                    'homeNFSe' => 'Emissão OS / NFS-e'
                ];
            } else {
                $array_plano = [
                    'home' => 'Resumo Geral',
                    'homeNFSe' => 'Emissão OS / NFS-e',
                    'homeNFSeSimplificada' => 'Emissão Simplificada de NFS-e',
                ];
            }
        @endphp

        <!-- Empresa das Homes -->
        <x-adminlte-select name="empresaHome" id="empresaHome" label="Visualizar Loja" igroup-size="sm">
            <x-adminlte-options :options="$array_opt" selected="{{ session('glo_empresa_exibicao_home') }}"/>
        </x-adminlte-select>

        <x-adminlte-select name="tipoHome" id="tipoHome" label="Tipo da Home" igroup-size="sm">
            <x-adminlte-options :options="$array_plano" selected="{{ session('glo_tipo_home') }}"/>
        </x-adminlte-select>
    </div>


</aside>

@push('js')
<script>
    $('#empresaHome').on('change', function() {
        let empresaHome = $('#empresaHome').val();
        
        $.post('/empresaVisualizacao', {
            _token: '{{ csrf_token() }}',
            empresaHome: empresaHome
        }, function(response) {
            if (response.success) {
                location.reload(true); // Recarregar para aplicar as mudanças
            }
        });
    });

    $('#tipoHome').on('change', function() {
        let tipoHome = $('#tipoHome').val();
        
        $.post('/tipoHome', {
            _token: '{{ csrf_token() }}',
            tipoHome: tipoHome,
        }, function(response) {
            if (response.success) {
                location.reload(true); // Recarregar para aplicar as mudanças
            }
        });
    });
</script>
@endpush