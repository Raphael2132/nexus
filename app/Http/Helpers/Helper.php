<?php

namespace App\Http\Helpers;
use Illuminate\Support\Facades\DB;
use stdClass;
use DateTime;
use DateTimeZone;

class Helper
{
    public static function mascaraTelCelular(string $telCelular)
    {
        $celularFormatado = "(".substr($telCelular,0,2).") ".substr($telCelular,2,1)." ".substr($telCelular,3,4)."-".substr($telCelular,-4,4);
        return $celularFormatado;
    }

    public static function limpaTelCelular(string $telCelular)
    {
        $replace = array("_", "(", ")", "-", " ");
        $celular = str_replace($replace,"",$telCelular);
        return $celular;
    }

    public static function mascaraTelComercial(string $telComercial)
    {
        $comercialFormatado = "(".substr($telComercial,0,2).") ".substr($telComercial,2,4)."-".substr($telComercial,-4,4);
        return $comercialFormatado;
    }

    public static function mascaraTelResidencial(string $telResidencial)
    {
        $residencialFormatado = "(".substr($telResidencial,0,2).") ".substr($telResidencial,2,4)."-".substr($telResidencial,-4,4);
        return $residencialFormatado;
    }

    public static function limpaTelResidencial(string $telResidencial)
    {
        $replace = array("_", "(", ")", "-", " ");
        $residencial = str_replace($replace,"",$telResidencial);
        return $residencial;
    }

    public static function mascaraCNPJ(string $cnpj)
    {
        $cnpjFormatado = substr($cnpj,0,2).'.'.substr($cnpj,2,3).'.'.substr($cnpj,5,3).'/'.substr($cnpj,8,4).'-'.substr($cnpj,12,2);
        return $cnpjFormatado;
    }

    public static function mascaraCPF(string $cpf)
    {
        $cpfFormatado = substr($cpf,0,3).'.'.substr($cpf,3,3).'.'.substr($cpf,6,3).'-'.substr($cpf,9,2);
        return $cpfFormatado;
    }

    public static function limpaCPF(string $cpf)
    {
        $replace = array("_","/", "-", ".", " ");
        $cpf = str_replace($replace,"",$cpf);
        return $cpf;
    }

    public static function mascaraRG(string $rg)
    {
        $rgFormatado = substr($rg,0,2).'.'.substr($rg,2,3).'.'.substr($rg,5,3).'-'.substr($rg,-1,1);
        return $rgFormatado;
    }

    public static function limpaRG(string $rg)
    {
        $replace = array("_","-", ".", " ");
        $rg = str_replace($replace,"",$rg);
        return $rg;
    }

    public static function mascaraCEP(string $cep)
    {
        $cepFormatado = substr($cep,0,5).'-'.substr($cep,-3,3);
        return $cepFormatado;
    }

    public static function limpaCEP(string $cep)
    {
        $replace = array("_", "-", " ");
        $cep = str_replace($replace,"",$cep);
        return $cep;
    }

    public static function formataDataHora(string $dataHora)
    {
        $dataHoraFormatada = date('d/m/Y H:i:s', strtotime($dataHora));
        return $dataHoraFormatada;
    }

    public static function formataData(string $data)
    {
        $dataFormatada = date('d/m/Y', strtotime($data));
        return $dataFormatada;
    }

    public static function limpaData(string $data)
    {
        $dataLimpa = substr($data,-4).'-'.substr($data,3,2).'-'.substr($data,0,2);
        return $dataLimpa;
    }

    public static function formataDataHoraParaData(string $data)
    {
        $dataLimpa = date('d/m/Y', strtotime($data));
        return $dataLimpa;
    }

    public static function formataHoraMinuto(string $hora)
    {
        $hora = str_pad($hora, 4, "0", STR_PAD_LEFT);
        $horaFormatada = substr($hora,0,2).':'.substr($hora,2,2);
        return $horaFormatada;
    }

    public static function limpaHoraMinuto(string $hora)
    {
        $horaLimpa = str_replace(":", "", $hora);
        return $horaLimpa;
    }

    public static function formataValorMonetario(string $valor)
    {
        $valorFormatado = number_format($valor,2,",",".");
        return $valorFormatado;
    }

    public static function limpaValorMonetario(string $valor)
    {
        $valorLimpo = str_replace(".","",$valor);
        $valorLimpo = str_replace(",",".",$valorLimpo);

        return $valorLimpo;
    }

    public static function limpaPorcentagem(string $valor)
    {
        $valorLimpo = str_replace(".","",$valor);
        $valorLimpo = str_replace(",",".",$valorLimpo);

        return $valorLimpo;
    }

    public static function formataPorcentagem(string $valor)
    {
        $valorFormatado = number_format($valor,2,",",".");
        return $valorFormatado;
    }

    /*
    * Remover os acentos de uma string
    * @param string $str
    * @return string
    */
    public static function removerAcento($str, $convertToUpper = 'N'){

        $com_acento = array(
            'Á', 'À', 'Â', 'Ã', 'Ä', 'á', 'à', 'â', 'ã', 'ä',
            'É', 'È', 'Ê', 'Ë', 'é', 'è', 'ê', 'ë',
            'Í', 'Ì', 'Î', 'Ï', 'í', 'ì', 'î', 'ï',
            'Ó', 'Ò', 'Ô', 'Õ', 'Ö', 'ó', 'ò', 'ô', 'õ', 'ö',
            'Ú', 'Ù', 'Û', 'Ü', 'ú', 'ù', 'û', 'ü',
            'Ç', 'ç', 'Ñ', 'ñ'
        );
        $sem_acento = array(
            'A', 'A', 'A', 'A', 'A', 'a', 'a', 'a', 'a', 'a',
            'E', 'E', 'E', 'E', 'e', 'e', 'e', 'e',
            'I', 'I', 'I', 'I', 'i', 'i', 'i', 'i',
            'O', 'O', 'O', 'O', 'O', 'o', 'o', 'o', 'o', 'o',
            'U', 'U', 'U', 'U', 'u', 'u', 'u', 'u',
            'C', 'c', 'N', 'n'
        );
        
        $str = str_replace($com_acento, $sem_acento, $str);
        
        if($convertToUpper == 'S'){
            $str = strtoupper($str);   
        } 

        return $str;
    }

    public static function buscaEstadoUF(string $uf)
    {
        $estado = DB::table('ibge_estados')->where('ibge_sigla', $uf)->get();

        $estadoFormatado = $uf.' - '.$estado[0]->ibge_nome;
        return $estadoFormatado;
    }

    public static function formataSimNao(string $valor)
    {
        if($valor == 'S'){
            $valorFormatado = 'Sim';
        }else{
            $valorFormatado = 'Não';
        }
        return $valorFormatado;
    }

    public static function convertHrCentToHrSexa(string $horaCentesimal)
    {
        // Separar a parte inteira (horas) e a parte fracionária (minutos centesimais)
        $horas = floor($horaCentesimal);
        $minutosCentesimais = $horaCentesimal - $horas;

        // Converter a parte fracionária de horas centesimais para minutos sexagesimais
        $minutosSexagesimais = $minutosCentesimais * 60;

        // Separar a parte inteira (minutos) e a parte fracionária (segundos)
        $minutos = floor($minutosSexagesimais);
        $segundos = ($minutosSexagesimais - $minutos) * 60;

        // Formatar a saída para hh:mm:ss
        return sprintf("%02d:%02d:%02d", $horas, $minutos, round($segundos));
    }

    public static function convertHrCentToMinutes($horaCentesimal)
    {
        $hours = floor($horaCentesimal);
        $minutes = ($horaCentesimal - $hours) * 60;
        return ($hours * 60) + $minutes;
    }

    //Pega a hora de inicio de expediente do dia informado
    public static function buscaHoraIniEx($data,$businessHours)
    {
        // Crie um objeto DateTime a partir da data fornecida
        $date = new DateTime($data);

        // Obtenha o dia da semana (0 para domingo até 6 para sábado)
        $currentDayOfWeek = $date->format('w'); 

        // Obtenha o horário comercial para o dia da semana atual
        $currentBusinessHours = $businessHours[$currentDayOfWeek] ?? null;

        if(!empty($currentBusinessHours)){
            $currentBusinessHours = $currentBusinessHours['start'];
        }

        return $currentBusinessHours;
    }

    //Pega a hora do final de expediente do dia informado
    public static function buscaHoraFinEx($data,$businessHours)
    {
        // Crie um objeto DateTime a partir da data fornecida
        $date = new DateTime($data);

        // Obtenha o dia da semana (0 para domingo até 6 para sábado)
        $currentDayOfWeek = $date->format('w'); 

        // Obtenha o horário comercial para o dia da semana atual
        $currentBusinessHours = $businessHours[$currentDayOfWeek] ?? null;

        if(!empty($currentBusinessHours)){
            $currentBusinessHours = $currentBusinessHours['end'];
        }

        return $currentBusinessHours;
    }

    //Gera o array com a tradução para pt-BR do datatable
    public static function dataTableLangPtBR()
    {
        $lang_pt_br = [
            "emptyTable" => "Nenhum registro encontrado",
            "info" => "Mostrando de _START_ até _END_ de _TOTAL_ registros",
            "infoFiltered" => "(Filtrados de _MAX_ registros)",
            "infoThousands" => ".",
            "loadingRecords" => "Carregando...",
            "zeroRecords" => "Nenhum registro encontrado",
            "search" => "Pesquisar",
            "paginate" => [
                "first" => "<i class='fas fa-angle-double-left'></i>",
                "previous" => "<i class='fas fa-angle-left'></i>",
                "next" => "<i class='fas fa-angle-right'></i>",
                "last" => "<i class='fas fa-angle-double-right'></i>"
            ],
            "aria" => [
                "sortAscending" => " => Ordenar colunas de forma ascendente",
                "sortDescending" => " => Ordenar colunas de forma descendente"
            ],
            "select" => [
                "rows" => [
                    "_" => "Selecionado %d linhas",
                    "1" => "Selecionado 1 linha"
                ],
                "cells" => [
                    "1" => "1 célula selecionada",
                    "_" => "%d células selecionadas"
                ],
                "columns" => [
                    "1" => "1 coluna selecionada",
                    "_" => "%d colunas selecionadas"
                ]
            ],
            "buttons" => [
                "copySuccess" => [
                    "1" => "Uma linha copiada com sucesso",
                    "_" => "%d linhas copiadas com sucesso"
                ],
                "collection" => "Coleção  <span class=\"ui-button-icon-primary ui-icon ui-icon-triangle-1-s\"><\/span>",
                "colvis" => "Visibilidade da Coluna",
                "colvisRestore" => "Restaurar Visibilidade",
                "copy" => "Copiar",
                "copyKeys" => "Pressione ctrl ou u2318 + C para copiar os dados da tabela para a área de transferência do sistema. Para cancelar, clique nesta mensagem ou pressione Esc..",
                "copyTitle" => "Copiar para a Área de Transferência",
                "csv" => "CSV",
                "excel" => "Excel",
                "pageLength" => [
                    "-1" => "Mostrar todos os registros",
                    "_" => "Mostrar %d registros"
                ],
                "pdf" => "PDF",
                "print" => "Imprimir",
                "createState" => "Criar estado",
                "removeAllStates" => "Remover todos os estados",
                "removeState" => "Remover",
                "renameState" => "Renomear",
                "savedStates" => "Estados salvos",
                "stateRestore" => "Estado %d",
                "updateState" => "Atualizar"
            ],
            "autoFill" => [
                "cancel" => "Cancelar",
                "fill" => "Preencher todas as células com",
                "fillHorizontal" => "Preencher células horizontalmente",
                "fillVertical" => "Preencher células verticalmente"
            ],
            "lengthMenu" => "Exibir _MENU_ resultados por página",
            "searchBuilder" => [
                "add" => "Adicionar Condição",
                "button" => [
                    "0" => "Construtor de Pesquisa",
                    "_" => "Construtor de Pesquisa (%d)"
                ],
                "clearAll" => "Limpar Tudo",
                "condition" => "Condição",
                "conditions" => [
                    "date" => [
                        "after" => "Depois",
                        "before" => "Antes",
                        "between" => "Entre",
                        "empty" => "Vazio",
                        "equals" => "Igual",
                        "not" => "Não",
                        "notBetween" => "Não Entre",
                        "notEmpty" => "Não Vazio"
                    ],
                    "number" => [
                        "between" => "Entre",
                        "empty" => "Vazio",
                        "equals" => "Igual",
                        "gt" => "Maior Que",
                        "gte" => "Maior ou Igual a",
                        "lt" => "Menor Que",
                        "lte" => "Menor ou Igual a",
                        "not" => "Não",
                        "notBetween" => "Não Entre",
                        "notEmpty" => "Não Vazio"
                    ],
                    "string" => [
                        "contains" => "Contém",
                        "empty" => "Vazio",
                        "endsWith" => "Termina Com",
                        "equals" => "Igual",
                        "not" => "Não",
                        "notEmpty" => "Não Vazio",
                        "startsWith" => "Começa Com",
                        "notContains" => "Não contém",
                        "notStartsWith" => "Não começa com",
                        "notEndsWith" => "Não termina com"
                    ],
                    "array" => [
                        "contains" => "Contém",
                        "empty" => "Vazio",
                        "equals" => "Igual à",
                        "not" => "Não",
                        "notEmpty" => "Não vazio",
                        "without" => "Não possui"
                    ]
                ],
                "data" => "Data",
                "deleteTitle" => "Excluir regra de filtragem",
                "logicAnd" => "E",
                "logicOr" => "Ou",
                "title" => [
                    "0" => "Construtor de Pesquisa",
                    "_" => "Construtor de Pesquisa (%d)"
                ],
                "value" => "Valor",
                "leftTitle" => "Critérios Externos",
                "rightTitle" => "Critérios Internos"
            ],
            "searchPanes" => [
                "clearMessage" => "Limpar Tudo",
                "collapse" => [
                    "0" => "Painéis de Pesquisa",
                    "_" => "Painéis de Pesquisa (%d)"
                ],
                "count" => "[total]",
                "countFiltered" => "[shown] ([total])",
                "emptyPanes" => "Nenhum Painel de Pesquisa",
                "loadMessage" => "Carregando Painéis de Pesquisa...",
                "title" => "Filtros Ativos",
                "showMessage" => "Mostrar todos",
                "collapseMessage" => "Fechar todos"
            ],
            "thousands" => ".",
            "datetime" => [
                "previous" => "Anterior",
                "next" => "Próximo",
                "hours" => "Hora",
                "minutes" => "Minuto",
                "seconds" => "Segundo",
                "amPm" => [
                    "am",
                    "pm"
                ],
                "unknown" => "-",
                "months" => [
                    "0" => "Janeiro",
                    "1" => "Fevereiro",
                    "10" => "Novembro",
                    "11" => "Dezembro",
                    "2" => "Março",
                    "3" => "Abril",
                    "4" => "Maio",
                    "5" => "Junho",
                    "6" => "Julho",
                    "7" => "Agosto",
                    "8" => "Setembro",
                    "9" => "Outubro"
                ],
                "weekdays" => [
                    "Dom",
                    "Seg",
                    "Ter",
                    "Qua",
                    "Qui",
                    "Sex",
                    "Sáb"
                ]
            ],
            "editor" => [
                "close" => "Fechar",
                "create" => [
                    "button" => "Novo",
                    "submit" => "Criar",
                    "title" => "Criar novo registro"
                ],
                "edit" => [
                    "button" => "Editar",
                    "submit" => "Atualizar",
                    "title" => "Editar registro"
                ],
                "error" => [
                    "system" => "Ocorreu um erro no sistema (<a target=\"\\\" rel=\"nofollow\" href=\"\\\">Mais informações<\/a>)."
                ],
                "multi" => [
                    "noMulti" => "Essa entrada pode ser editada individualmente, mas não como parte do grupo",
                    "restore" => "Desfazer alterações",
                    "title" => "Multiplos valores",
                    "info" => "Os itens selecionados contêm valores diferentes para esta entrada. Para editar e definir todos os itens para esta entrada com o mesmo valor, clique ou toque aqui, caso contrário, eles manterão seus valores individuais."
                ],
                "remove" => [
                    "button" => "Remover",
                    "confirm" => [
                        "_" => "Tem certeza que quer deletar %d linhas?",
                        "1" => "Tem certeza que quer deletar 1 linha?"
                    ],
                    "submit" => "Remover",
                    "title" => "Remover registro"
                ]
            ],
            "decimal" => ",",
            "stateRestore" => [
                "creationModal" => [
                    "button" => "Criar",
                    "columns" => [
                        "search" => "Busca de colunas",
                        "visible" => "Visibilidade da coluna"
                    ],
                    "name" => "Nome:",
                    "order" => "Ordernar",
                    "paging" => "Paginação",
                    "scroller" => "Posição da barra de rolagem",
                    "search" => "Busca",
                    "searchBuilder" => "Mecanismo de busca",
                    "select" => "Selecionar",
                    "title" => "Criar novo estado",
                    "toggleLabel" => "Inclui:"
                ],
                "emptyStates" => "Nenhum estado salvo",
                "removeConfirm" => "Confirma remover %s?",
                "removeJoiner" => "e",
                "removeSubmit" => "Remover",
                "removeTitle" => "Remover estado",
                "renameButton" => "Renomear",
                "renameLabel" => "Novo nome para %s:",
                "renameTitle" => "Renomear estado",
                "duplicateError" => "Já existe um estado com esse nome!",
                "emptyError" => "Não pode ser vazio!",
                "removeError" => "Falha ao remover estado!"
            ],
            "infoEmpty" => "Mostrando 0 até 0 de 0 registro(s)",
            "processing" => "Carregando...",
            "searchPlaceholder" => "Buscar registros"
        ]; 

        return $lang_pt_br;
    }

    //Gera o array com a tradução de um campo hora com DateRangePicker em pt-BR
    public static function dtRangeHoraPtBR()
    {
        $configHR = [
            "singleDatePicker" => true,
            "showDropdowns" => false, // Desativa dropdowns de ano e mês, já que só a hora e minuto são necessários
            "timePicker" => true,
            "timePicker24Hour" => true,
            "timePickerSeconds" => false,
            "cancelButtonClasses" => "btn-danger",
            "applyButtonClasses" => "btn-nexus",
            "locale" => [
                "format" => "HH:mm", // Formato apenas para horas e minutos
                "applyLabel" => "Aplicar",
                "cancelLabel" => "Cancelar",
                "customRangeLabel" => "Personalizado"
            ],
        ];        

        return $configHR;
    }

    //Gera o array com a tradução de um campo data com DateRangePicker em pt-BR
    public static function dtRangeDataPtBR()
    {
        $configHR = [
            "singleDatePicker" => true,
            "showDropdowns" => true, // Exibe dropdowns para seleção de mês e ano
            "startDate" => "js:moment()", // Inicia no mês e ano atual
            "minYear" => "js:moment().subtract(100, 'years').year()", // Define o ano mínimo como 100 anos atrás
            "maxYear" => "js:moment().add(10, 'years').year()", // Define o ano máximo como 10 anos à frente
            "timePicker" => false,
            "timePicker24Hour" => false,
            "timePickerSeconds" => false,
            "cancelButtonClasses" => "btn-danger",
            "applyButtonClasses" => "btn-nexus",
            "locale" => [
                "format" => "DD/MM/YYYY", // Formato de data
                "separator" => " - ",
                "applyLabel" => "Aplicar",
                "cancelLabel" => "Cancelar",
                "fromLabel" => "De",
                "toLabel" => "Até",
                "customRangeLabel" => "Personalizado",
                "weekLabel" => "Sm",
                "daysOfWeek" => ["Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "Sáb"],
                "monthNames" => ["Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"],
                "firstDay" => 0 // Começa a semana no domingo
            ],
        ];             

        return $configHR;
    }

    //Gera o array com a tradução de um campo data com DateRangePicker em pt-BR
    public static function formataTipoUsuario($tipo)
    {
        if($tipo == 'M'){
            $usuario = "Master";
        }elseif($tipo == 'ADM'){
            $usuario = "Administrador";
        }elseif($tipo == 'PR'){
            $usuario = "Prestador";
        }elseif($tipo == 'CO'){
            $usuario = "Consultor";
        }elseif($tipo == 'VE'){
            $usuario = "Vendedor";
        }elseif($tipo == 'CX'){
            $usuario = "Caixa";
        }else{
            $usuario = "Tesouraria";
        }                

        return $usuario;
    }

    //Gera o array com a tradução de um campo data com DateRangePicker em pt-BR
    public static function buscaDadosEmpresa($empresa)
    {
        $dadosEmpresa = DB::table('cadastro_empresas')->where('empresa_codigo',$empresa)->first();          

        return $dadosEmpresa;
    }
}