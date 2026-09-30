/*
|--------------------------------------------------------------------------------
| Dropdown Datatables
|--------------------------------------------------------------------------------
|
| Alterações do dropdown do datatable.
|
*/

/* ***** Impede o dropdown de fechar ao clicar dentro do menu dentro da div dataTables_filter ***** */
$(document).ready(function() {
    $('.dataTables_filter .dropdown-menu').on('click', function(event) {
        event.stopPropagation();
    });
});
