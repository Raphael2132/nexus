/**
 * Integração dos dados padrões da inicialização do DataTable no sistema Nexus.
 *
 * Este arquivo define os padrões e adiciona opções ao DataTables para estilizar a consulta do sistema Nexus
 * Acesse http://datatables.net/manual/ para mais informações sobre a API.
 * Lembrando que o AdminLTE 3.2 não utiliza a versão mais recente do Datatable
 * Algumas funções podem não funcionar aqui da mesma maneira
 * 
 */

/* ********** Define a exibição de até 5 páginas na paginação como padrão ********** */
//$.fn.DataTable.ext.pager.numbers_length = 5;

// Função para alterar o valor da paginação com base na largura da janela
function ajustarPaginacaoDataTable() {
    var larguraTela = $(window).width();

    if (larguraTela <= 481) { // Para telas pequenas (como dispositivos móveis)
        $.fn.DataTable.ext.pager.numbers_length = 5;
    } else if (larguraTela <= 1921) { // Para telas médias (como tablets)
        $.fn.DataTable.ext.pager.numbers_length = 7;
    } else { // Para telas grandes (como desktops)
        $.fn.DataTable.ext.pager.numbers_length = 9;
    }
}

// Chama a função ao carregar a página
ajustarPaginacaoDataTable();

// Monitora alterações no tamanho da tela e ajusta a paginação dinamicamente
$(window).resize(function() {
    ajustarPaginacaoDataTable();
});

/* ********** Função para retornar o DOM padrão do DataTable ********** */
function getDatatableDom() {
    return '<"row" <"col-md-6 btn-datatable-esq mb-2" B> <"col-md-6 btn-datatable-dir" <"col-md-12" <"row tool-bar d-flex flex-wrap justify-content-end align-items-end" f>>>>' +
           '<"row" <"col-12 linha-cards-menu" F>>' +
           '<"row" <"col-12 body-datatable" tr>>' +
           '<"row footer-datatable" <"col-md-5 footer-datatable-esq" i> <"col-md-7 footer-datatable-dir" p>>';
}

/* ********** Função para retornar os Botões padrão do DataTable ********** */
function getDatatableButtons() {
    return {
        dom: {
            button: {
                className: "btn btn-default" // Classe padrão para os botões
            }
        },
        buttons: [
            {
                extend: "pageLength",
                className: "btn-nexus"
            },
            {
                extend: "print",
                className: "btn-nexus",
                text: "<i class='fas fa-fw fa-lg fa-print'></i>",
                titleAttr: "Imprimir",
                exportOptions: {
                    columns: ":not([dt-no-export])"
                }
            },
            {
                extend: "csv",
                className: "btn-nexus",
                text: "<i class='fas fa-fw fa-lg fa-file-csv'></i>",
                titleAttr: "Exportar para CSV",
                exportOptions: {
                    columns: ":not([dt-no-export])"
                }
            },
            {
                extend: "excel",
                className: "btn-nexus",
                text: "<i class='fa-solid fa-file-xls fa-fw fa-lg'></i>",
                titleAttr: "Exportar para Excel",
                exportOptions: {
                    columns: ":not([dt-no-export])"
                }
            },
            {
                extend: "pdf",
                className: "btn-nexus",
                text: "<i class='fas fa-fw fa-lg fa-file-pdf'></i>",
                titleAttr: "Exportar para PDF",
                exportOptions: {
                    columns: ":not([dt-no-export])"
                }
            }
        ]
    };
}

/* ********** Função genérica para renderizar datas no DataTable ********** */
function getRenderDateFunction(dateFormat) {
    return function(data, type) {
        if (type === 'display' || type === 'filter') {
            return data ? moment(data).format(dateFormat) : ''; // Formata a data conforme o formato especificado
        }
        return data; // Retorna o dado original se não for para exibição ou filtro
    };
}

/* ********** Função genérica para fazer agrupamento de linhas no DataTable ********** */
function applyRowGrouping(api, groupColumns, colspan) {
    var rows = api.rows({ page: 'current' }).nodes();
    var lastGroupValues = [];

    // Verifica se há colunas para agrupar
    if (groupColumns.length > 0) {
        groupColumns.forEach(function(groupColumn, index) {
            lastGroupValues[index] = null; // Inicializa os valores anteriores

            api.column(groupColumn, { page: 'current' })
                .data()
                .each(function(group, i) {
                    if (lastGroupValues[index] !== group) {
                        // Obtenha a label da coluna correspondente
                        var label = $(api.column(groupColumn).header()).text();

                        // Crie a linha de agrupamento com a label e o grupo
                        let groupId = 'group-' + index + '-' + i;

                        $(rows).eq(i).before(
                            `<tr class="group group-${index}" data-level="${index}">
                                <td colspan="${colspan}">
                                    <i class="fas fa-chevron-down mr-2"></i>
                                    ${label}: ${group}
                                </td>
                            </tr>`
                        );
                        lastGroupValues[index] = group; // Atualiza o último valor
                    }
                });
        });
    }
}

/* ***** Customiza o botão pesquisar original do datatable ***** */
function customSearchingField(tableFilterId) {

    // Seleciona o elemento input dentro do filtro da tabela
    var $input = $(tableFilterId + ' input');

    // Remove a classe 'form-control-sm' do input
    $input.removeClass('form-control-sm');

    // Cria a nova estrutura HTML para agrupar o input existente
    var $newWrapper = $(`
        <div class="ml-2 mb-2 flex-grow-1 flex-md-grow-0">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text pesquisa-slot-nexus">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </span>
                </div>
            </div>
        </div>
    `);

    // Insere o input original dentro da nova estrutura sem recriá-lo
    $newWrapper.find('.input-group').append($input);

    // Substitui o conteúdo da div de filtro sem remover o input original
    $(tableFilterId).html($newWrapper);
}

/* ********** Função retornar os botões que serão utilizados na barra de ferramentas ********** */
function getButtonsTollBar(options) {

    // Início do HTML da barra de ferramentas
    let toolbarHTML = `
        <div class="d-flex flex-wrap justify-content-end align-items-end">
    `;

    /* ********** Verifica as opções do array e adiciona os botões correspondentes ********** */

    // Monta botão de quebra
    if (options.includes('quebra')) {
        toolbarHTML += `
            <div class="ml-2 mb-2">
                <button type="button" class="btn btn-outline-nexus btn-quebra-datatable" title="Quebra de Consulta">
                    Quebras 
                    <i class="fa-solid fa-bars-staggered"></i>
                </button>
            </div>
        `;
    }

    // Monta botão de colunas
    if (options.includes('colunas')) {
        toolbarHTML += `
            <div class="ml-2 mb-2">
                <button type="button" class="btn btn-outline-nexus btn-visualizar-datatable" title="Visualizar Colunas">
                    Colunas 
                    <i class="fa-regular fa-eye"></i>
                </button>
            </div>
        `;
    }

    // Monta botão de filtro
    if (options.includes('filtro')) {
        toolbarHTML += `
            <div class="ml-2 mb-2">
                <button type="button" class="btn btn-outline-nexus btn-filtro-datatable" title="Filtrar Registros">
                    Filtro 
                    <i class="fa-regular fa-filter-list fa-lg"></i>
                </button>
            </div>
        `;
    }

    // Monta botão de pesquisa
    if (options.includes('pesquisa')) {
        toolbarHTML += `
            <div class="ml-2 mb-2 flex-grow-1 flex-md-grow-0">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text x-slot-nexus">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </span>
                    </div>
                    <input type="text" class="form-control" name="filter-input" id="custom-search" placeholder="Pesquisa rápida">
                </div>
            </div>
        `;
    }

    // Fechamento da div principal
    toolbarHTML += `</div>`;

    // Retorna o HTML final
    return toolbarHTML;
}

/* ********** Função retornar o Card dos botões de quebra ********** */
function getCardQuebras(htmlBotoes) {

    // Início do Card
    let cardHTML = `
        <div class="row visualizar-quebras" style="">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row d-flex justify-content-center align-items-center">
                            <h5 style="color: #00abab;"><i class="fa-solid fa-bars-staggered"></i> Quebra de Consulta</h5>
                        </div>
                        <div class="row">
                            <hr style="width: 100%;">
                        </div>
                        <div class="row d-flex justify-content-center align-items-center">
    `;

    // Adiciona os botões
    cardHTML += htmlBotoes;

    // Finaliza o card
    cardHTML += `
                        </div>
                        <div class="row">
                            <hr style="width: 100%;">
                        </div>
                        <div class="row d-flex justify-content-center align-items-center">
                            <a class="btn btn-nexus removeGroupCol mr-2" href="#"><i class="fa-solid fa-trash-can"></i> Remover Quebras</a>
                        </div>
                    </div>    
                </div>
            </div>
        </div>
    `;

    // Retorna o HTML final
    return cardHTML;
}

/* ********** Função retornar o Card dos botões de Colunas ********** */
function getCardColunas(htmlBotoes) {

    // Início do Card
    let cardHTML = `
        <div class="row visualizar-colunas" style="">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row d-flex justify-content-center align-items-center">
                            <h5 style="color: #00abab;"><i class="fa-regular fa-eye"></i> Visualizar Colunas</h5>
                        </div>
                        <div class="row">
                            <hr style="width: 100%;">
                        </div>
                        <div class="row d-flex justify-content-center align-items-center">
    `;

    // Adiciona os botões
    cardHTML += htmlBotoes;

    // Finaliza o card
    cardHTML += `
                        </div>
                    </div>    
                </div>
            </div>
        </div>
    `;

    // Retorna o HTML final
    return cardHTML;
}

/* ********** Função retornar o Card dos campos de Filtro ********** */
function getCardFiltros(htmlCampos) {

    // Início do Card
    let cardHTML = `
        <div class="row filtrar-registros" style="">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row d-flex justify-content-center align-items-center">
                            <h5 style="color: #00abab;"><i class="fa-regular fa-filter"></i> Filtrar Registros</h5>
                        </div>
                        <div class="row">
                            <hr style="width: 100%;">
                        </div>
    `;

    // Adiciona os botões
    cardHTML += htmlCampos;

    // Finaliza o card
    cardHTML += `
                        <div class="row">
                            <hr style="width: 100%;">
                        </div>
                        <div class="row d-flex justify-content-center align-items-center">
                            <a class="btn btn-nexus btn-limpa-filtro mr-2" href="#"><i class="fa-solid fa-broom-wide"></i> Limpar Filtros</a>
                        </div>
                    </div>    
                </div>
            </div>
        </div>
    `;

    // Retorna o HTML final
    return cardHTML;
}

// Função para configurar os eventos de agrupamento e alternância de colunas da Quebra
function setupActionsQuebra(table, tableWrapperId, tableId, originalOrder, groupColumns) {

    // Esconde o Card de Quebra
    $(`${tableWrapperId} .visualizar-quebras`).hide();

    // Função para alternar a exibição da div "Quebra" e trocar a classe do botão
    $(`${tableWrapperId} button.btn-quebra-datatable`).on('click', function() {
        $(`${tableWrapperId} .visualizar-quebras`).slideToggle();  // Exibe ou oculta com efeito deslizante

        // Alterna a classe do botão
        $(this).toggleClass('btn-nexus btn-outline-nexus');
    });

    // Altera a Ordenação do agrupamento entre ASC/DESC
    /* 22/02/2026 - Removido essa funcionalidade, conflito com o Collapse do grupo
    $(`${tableId} tbody`).on('click', 'tr.group', function () {
        var groupIndex = $(this).attr('class').match(/group-(\d+)/)[1]; // Pega o índice do grupo clicado a partir da classe
        var groupColumn = groupColumns[groupIndex]; // Pega a coluna correspondente ao grupo clicado
        var currentOrder = table.order();

        // Verifica se a coluna atual está sendo ordenada e alterna entre 'asc' e 'desc'
        var found = currentOrder.find(function(order) {
            return order[0] === groupColumn;
        });

        if (found && found[1] === 'asc') {
            table.order([groupColumn, 'desc']).draw();
        } else {
            table.order([groupColumn, 'asc']).draw();
        }
    });
    */

    // Troca a classe do botão Ativado/Desativado
    $(`${tableWrapperId} a.groupCol`).on('click', function() {
        // Alterna a classe do botão
        $(this).toggleClass('btn-nexus btn-outline-nexus');
    });

    // Troca a classe de todos os botões para Desativado (Remove Quebras)
    $(`${tableWrapperId} a.removeGroupCol`).on('click', function() {
        // Remove todas as classes que não sejam btn-nexus
        $('a.groupCol').removeClass('btn-nexus').addClass('btn-outline-nexus');
    });

   // Função para adicionar/remover uma coluna ao agrupamento
    function toggleGroupColumn(columnIndex) {
        var index = groupColumns.indexOf(columnIndex);
        if (index > -1) {
            // Coluna já está agrupada, remove o agrupamento
            groupColumns.splice(index, 1); // Remove a coluna do agrupamento
            table.column(columnIndex).visible(true); // Torna a coluna visível novamente
            // Troca a classe do botão para btn-nexus
            $(`${tableWrapperId} a.toggle-vis[data-column="${columnIndex}"]`).removeClass('btn-outline-nexus').addClass('btn-nexus');
        } else {
            // Coluna não está agrupada, adiciona o agrupamento
            groupColumns.push(columnIndex); // Adiciona a coluna ao agrupamento
            table.column(columnIndex).visible(false); // Esconde a coluna ao agrupar
            // Troca a classe do botão para btn-outline-nexus
            $(`${tableWrapperId} a.toggle-vis[data-column="${columnIndex}"]`).removeClass('btn-nexus').addClass('btn-outline-nexus');
        }

        // Filtra o originalOrder para remover colunas que estão no groupColumns
        var filteredOriginalOrder = originalOrder.filter(function(orderItem) {
            return !groupColumns.includes(orderItem[0]);
        });

        // Define a nova ordem, respeitando a ordem original como secundária
        var order = groupColumns.map(function(col) {
            return [col, 'asc'];
        }).concat(filteredOriginalOrder); // Adiciona a ordem original filtrada como secundária

        // Aplica a nova ordem à tabela e redesenha
        table.order(order).draw();
    }

    // Função para remover todos os agrupamentos
    function removeAllGroups() {
        // Para cada coluna agrupada, torna-a visível novamente e troca a classe do botão
        groupColumns.forEach(function(columnIndex) {
            // Torna a coluna visível
            table.column(columnIndex).visible(true); 

            // Altera a classe do botão correspondente
            $(`${tableWrapperId} a.toggle-vis[data-column="${columnIndex}"]`).removeClass('btn-outline-nexus').addClass('btn-nexus');
        });

        // Limpa o array de colunas agrupadas
        groupColumns.length = 0; // Reseta o array de agrupamento

        // Redefine a ordem para a original (ou a que você deseja) sem agrupamento
        table.order(originalOrder).draw(); // Remove a ordenação e o agrupamento e faz ordenação secundária original
    }

    // Botões para alternar agrupamento dinamicamente
    $(`${tableWrapperId} .groupCol`).on('click', function (e) {
        e.preventDefault();
        var column = $(this).data('column'); // Obtém a coluna do atributo data-column
        toggleGroupColumn(column); // Adiciona ou remove a coluna para agrupamento
    });

    // Botão para remover todos os agrupamentos
    $(`${tableWrapperId} .removeGroupCol`).on('click', function (e) {
        e.preventDefault();
        removeAllGroups(); // Remove todos os agrupamentos
    });
}

// Função para adicionar eventos aos botões de Visualizar Colunas
function setupActionsColuna(table, tableWrapperId) {

    // Esconde Card Colunas
    $(`${tableWrapperId} .visualizar-colunas`).hide();

    // Função para alternar a exibição da div "Visualizar Colunas" e trocar a classe do botão
    $(`${tableWrapperId} button.btn-visualizar-datatable`).on('click', function() {
        $(`${tableWrapperId} .visualizar-colunas`).slideToggle();  // Exibe ou oculta com efeito deslizante

        // Alterna a classe do botão
        $(this).toggleClass('btn-nexus btn-outline-nexus');
    });

    // Adiciona um event listener a cada link de toggle
    document.querySelectorAll(`${tableWrapperId} a.toggle-vis`).forEach((el) => {
        el.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Obtém o índice da coluna a partir do atributo data-column
            let columnIdx = e.target.getAttribute('data-column');
            let column = table.column(columnIdx);
            
            // Alterna a visibilidade da coluna
            let isVisible = column.visible();
            column.visible(!isVisible);
            
            // Altera a classe do botão com base na visibilidade
            if (isVisible) {
                e.target.classList.remove('btn-nexus');  // Remove a classe de botão ativo
                e.target.classList.add('btn-outline-nexus');     // Adiciona a classe de botão inativo
            } else {
                e.target.classList.remove('btn-outline-nexus');   // Remove a classe de botão inativo
                e.target.classList.add('btn-nexus');       // Adiciona a classe de botão ativo
            }
        });
    });
}

/* ***** Função para adicionar eventos aos botões de Visualizar Colunas ***** */
function setupActionsFiltro(table, tableWrapperId) {
    // Inicialmente esconde as divs
    $(`${tableWrapperId} .filtrar-registros`).hide();

    // Função para alternar a exibição da div "Filtrar Registros" e trocar a classe do botão
    $(`${tableWrapperId} button.btn-filtro-datatable`).on('click', function() {
        $(`${tableWrapperId} .filtrar-registros`).slideToggle();  // Exibe ou oculta com efeito deslizante

        // Alterna a classe do botão
        $(this).toggleClass('btn-nexus btn-outline-nexus');
    });

    // Função para limpar os campos de filtro e atualizar o DataTable
    $(`${tableWrapperId} .btn-limpa-filtro`).on('click', function(e) {
        e.preventDefault(); // Evita o comportamento padrão do link

        // Seleciona todos os campos de entrada e select
        const inputs = $(`${tableWrapperId} input[name^="filter"], ${tableWrapperId} select[name^="filter"]`);

        // Limpa o valor de cada campo
        inputs.each(function() {
            if (this.tagName.toLowerCase() === 'select') {
                this.selectedIndex = 0; // Para selects, define a opção vazia
            } else {
                this.value = ''; // Para inputs, limpa o valor
            }
        });

        // Atualiza o DataTable para refletir os campos limpos
        table.search('').columns().search('').draw(); // Limpa todos os filtros
    });
}

/* ***** Eventos dos Detalhes do Registro (Sub Consulta) ***** */
function setupDetailRows(table, tableId, format) {

    // Array com os dados da linha
    const detailRows = [];

    // Event listener para expandir/recolher as linhas filhas de detalhes
    $(tableId).on('click', 'tbody td.icone-row-sub', function (event) {
        let tr = $(this).closest('tr'); // Usa jQuery para selecionar o 'tr'
        let row = table.row(tr);
        let icon = $(this).find('i'); // Buscando o ícone

        if (row.child.isShown()) {
            // Ocultar a linha filha
            tr.removeClass('details');
            row.child.hide();
            icon.removeClass('fa-chevron-down').addClass('fa-chevron-right'); // Ícone de "fechado"

            // Remover do array 'detailRows'
            detailRows.splice(detailRows.indexOf(tr.attr('id')), 1);

        } else {
            // Mostrar a linha filha com o conteúdo da view via AJAX
            format(tr).done(function(response) {
                tr.addClass('details');
                row.child(response).show();
                icon.removeClass('fa-chevron-right').addClass('fa-chevron-down'); // Ícone de "aberto"
            });

            // Adicionar ao array 'detailRows'
            if (detailRows.indexOf(tr.attr('id')) === -1) {
                detailRows.push(tr.attr('id'));
            }
        }
    });

    // Reabrir automaticamente as linhas filhas de uma tabela ao redesenhar (redraw) a tabela que estavam abertas
    $(tableId).on('draw', () => {
        detailRows.forEach((id) => {
            let el = document.querySelector('#' + id + ' td.icone-row-sub');
            if (el) {
                el.dispatchEvent(new Event('click', { bubbles: true }));
            }
        });
    });
}

/* ***** Eventos de totalização do agrupamento de linhas (Quebra) - Aplica a Totalização de cada agrupamento ***** */
function applyHierarchicalTotals(api, groupColumns, totalColumns) {

    if (!groupColumns.length || !totalColumns.length) return;

    const rows = api.rows({ page: 'current' }).nodes();
    const data = api.rows({ page: 'current' }).data().toArray();

    const visibleIndexes = api.columns(':visible').indexes().toArray();

    // Mapeia posição visível das colunas totalizadas
    const totalVisiblePositions = totalColumns.map(col =>
        visibleIndexes.indexOf(col)
    );

    let groups = [];

    function parseValor(valor) {
        valor = $('<div>').html(valor).text();
        return parseFloat(
            valor.replace(/\./g, '').replace(',', '.')
        ) || 0;
    }

    function insertTotal(level, totals, rowIndex) {

        let tds = '';
        const indent = level * 25;

        for (let i = 0; i < visibleIndexes.length; i++) {

            if (totalVisiblePositions.includes(i)) {

                const colOriginal = totalColumns[
                    totalVisiblePositions.indexOf(i)
                ];

                const columnClass = $(api.column(colOriginal).header()).attr('class') || '';

                const valor = totals[colOriginal] || 0;

                tds += `
                    <td class="${columnClass} font-weight-bold">
                        ${valor.toLocaleString('pt-BR', { minimumFractionDigits: 2 })}
                    </td>
                `;
            }
            else if (i === 0) {

                const nome = api.column(groupColumns[level]).header().textContent.trim();

                tds += `
                    <td class="font-weight-bold">
                        <span style="padding-left:${indent}px">
                            Total ${nome}
                        </span>
                    </td>
                `;
            }
            else {
                tds += `<td></td>`;
            }
        }

        $(rows).eq(rowIndex).after(`
            <tr class="subtotal-row subtotal-level-${level}">
                ${tds}
            </tr>
        `);
    }

    data.forEach((row, rowIndex) => {

        groupColumns.forEach((colIndex, level) => {

            const key = row[colIndex];

            if (!groups[level]) {
                groups[level] = {
                    key: key,
                    totals: {},
                    lastRow: rowIndex
                };

                totalColumns.forEach(col => {
                    groups[level].totals[col] = 0;
                });
            }

            if (groups[level].key !== key) {

                for (let i = level; i < groups.length; i++) {
                    if (groups[i]) {
                        insertTotal(i, groups[i].totals, groups[i].lastRow);
                        groups[i] = null;
                    }
                }

                groups[level] = {
                    key: key,
                    totals: {},
                    lastRow: rowIndex
                };

                totalColumns.forEach(col => {
                    groups[level].totals[col] = 0;
                });
            }

            totalColumns.forEach(col => {
                groups[level].totals[col] += parseValor(row[col]);
            });

            groups[level].lastRow = rowIndex;
        });
    });

    for (let i = 0; i < groups.length; i++) {
        if (groups[i]) {
            insertTotal(i, groups[i].totals, groups[i].lastRow);
        }
    }
}

/* ***** Eventos de totalização do agrupamento de linhas (Quebra) - Aplica a linha de Totalização na tabela ***** */
function insertTotalRow(group, level, api, rows, totalVisiblePosition, columnCount, groupLabel) {

    let totalFormatado = group.total.toLocaleString('pt-BR', { minimumFractionDigits: 2 });

    let tds = '';

    for (let i = 0; i < columnCount; i++) {

        if (i === totalVisiblePosition) {
            tds += `<td class="text-right font-weight-bold">${totalFormatado}</td>`;
        }
        else if (i === 0) {
            tds += `<td class="font-weight-bold">Total ${groupLabel}</td>`;
        }
        else {
            tds += `<td></td>`;
        }
    }

    $(rows).eq(group.lastRow).after(`
        <tr class="subtotal-row subtotal-level-${level}">
            ${tds}
        </tr>
    `);
}

/* ***** Eventos de totalização Geral - Aplica a Totalização geral na tabela ***** */
function applyGrandTotal(api, totalColumns) {

    if (!totalColumns.length) return;

    const data = api.rows({ search: 'applied' }).data().toArray();
    const visibleIndexes = api.columns(':visible').indexes().toArray();

    const totalVisiblePositions = totalColumns.map(col =>
        visibleIndexes.indexOf(col)
    );

    function parseValor(valor) {
        valor = $('<div>').html(valor).text();
        return parseFloat(
            valor.replace(/\./g, '').replace(',', '.')
        ) || 0;
    }

    let totals = {};

    totalColumns.forEach(col => totals[col] = 0);

    data.forEach(row => {
        totalColumns.forEach(col => {
            totals[col] += parseValor(row[col]);
        });
    });

    let tds = '';

    for (let i = 0; i < visibleIndexes.length; i++) {

        if (totalVisiblePositions.includes(i)) {

            const colOriginal = totalColumns[
                totalVisiblePositions.indexOf(i)
            ];

            const columnClass = $(api.column(colOriginal).header()).attr('class') || '';

            tds += `
                <td class="${columnClass} font-weight-bold">
                    ${totals[colOriginal].toLocaleString('pt-BR', { minimumFractionDigits: 2 })}
                </td>
            `;
        }
        else if (i === 0) {
            tds += `<td class="font-weight-bold">Total Geral</td>`;
        }
        else {
            tds += `<td></td>`;
        }
    }

    $(api.table().body()).append(`
        <tr class="grand-total-row bg-light">
            ${tds}
        </tr>
    `);
}

/* ***** Eventos de agrupamento (Quebra) - Aplica o collapse/expand de cada linha de agrupamento ***** */
function setupGroupRowToggle(table, tableSelector) {
    // Evento de clique nas linhas de grupo
    $(`${tableSelector} tbody`).on('click', 'tr.group', function () {
        const $groupRow = $(this);
        const level = parseInt($groupRow.data('level'));

        $groupRow.toggleClass('collapsed');

        const isCollapsed = $groupRow.hasClass('collapsed');
        const $icon = $groupRow.find('i');

        // Atualiza o ícone
        if (isCollapsed) {
            $icon.removeClass('fa-chevron-down')
                .addClass('fa-chevron-right');
        } else {
            $icon.removeClass('fa-chevron-right')
                .addClass('fa-chevron-down');
        }

        let next = $groupRow.next();

        while (next.length) {

            if (next.hasClass('group')) {
                let nextLevel = parseInt(next.data('level'));
                if (nextLevel <= level) break;
            }

            next.toggle(!isCollapsed);
            next = next.next();
        }
    });
}