{{-- Table --}}

<div class="table-responsive">

<table id="{{ $id }}" style="width:100%" {{ $attributes->merge(['class' => $makeTableClass()]) }}>

    {{-- Table head --}}
    <thead @isset($headTheme) class="thead-{{ $headTheme }}" @endisset>
        <tr>
            @foreach($heads as $th)
                <th @isset($th['classes']) class="{{ $th['classes'] }}" @endisset
                    @isset($th['width']) style="width:{{ $th['width'] }}%" @endisset
                    @isset($th['no-export']) dt-no-export @endisset>
                    {{ is_array($th) ? ($th['label'] ?? '') : $th }}
                </th>
            @endforeach
        </tr>
    </thead>

    {{-- Table body --}}
    <tbody>{{ $slot }}</tbody>

    {{-- Table footer --}}
    @isset($withFooter)
        <tfoot @isset($footerTheme) class="thead-{{ $footerTheme }}" @endisset>
            <tr>
                @foreach($heads as $th)
                    <th>{{ is_array($th) ? ($th['label'] ?? '') : $th }}</th>
                @endforeach
            </tr>
        </tfoot>
    @endisset

</table>

</div>

{{-- Add plugin initialization and configuration code --}}

{{-- 
    Original pega diretamente o $config e faz uma conversão simples para JS. 
    Mas se fiser uma function em uma das opções de configuração ele não trata corretamente a conversão.
    Com o novo código ele faz o tratamenmmto correto
@push('js')
<script>

    $(() => {
        $('#{{ $id }}').DataTable( @json($config) );
    })

</script>
@endpush
--}}

@push('js')
<script>

    /*
    |--------------------------------------------------------------------------
    | Alteração do Tratamento de Conversão PHP -> Json
    |--------------------------------------------------------------------------
    |
    | No original pega diretamente o $config e faz uma conversão simples para JS.
    | Assim se fiser uma function em uma das opções de configuração do Datatable ele não trata corretamente a conversão.
    | Com o novo código ele faz o tratamenmmto correto quando converte para String
    |
    */

    /* ***** Alteração descartada por obrigatoriedade de inicializar o datatable por JS
    $(() => {
        @php
            $strConfig = json_encode($config);
            $strConfig = preg_replace('/"(function[\s]?\([^)]+\)[\s]?{.*})"/mU', '${1}', $strConfig);
            $strConfig = str_replace('\r\n', '', $strConfig);
        @endphp
        $('#{{ $id }}').DataTable( {!! $strConfig !!} );
    })
    ***** */
   
</script>
@endpush


{{-- Add CSS styling --}}

@isset($beautify)
    @push('css')
    <style type="text/css">
        #{{ $id }} tr td,  #{{ $id }} tr th {
            vertical-align: middle;
            text-align: center;
        }
    </style>
    @endpush
@endisset
