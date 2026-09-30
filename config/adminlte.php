<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Title
    |--------------------------------------------------------------------------
    |
    | Here you can change the default title of your admin panel.
    |
    | For detailed instructions you can look the title section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'title' => 'Nexus ERP Cloud',
    'title_prefix' => '',
    'title_postfix' => '',

    /*
    |--------------------------------------------------------------------------
    | Favicon
    |--------------------------------------------------------------------------
    |
    | Here you can activate the favicon.
    |
    | For detailed instructions you can look the favicon section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'use_ico_only' => false,
    'use_full_favicon' => false,

    /*
    |--------------------------------------------------------------------------
    | Google Fonts
    |--------------------------------------------------------------------------
    |
    | Here you can allow or not the use of external google fonts. Disabling the
    | google fonts may be useful if your admin panel internet access is
    | restricted somehow.
    |
    | For detailed instructions you can look the google fonts section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'google_fonts' => [
        'allowed' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Logo
    |--------------------------------------------------------------------------
    |
    | Here you can change the logo of your admin panel.
    |
    | For detailed instructions you can look the logo section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'logo' => 'Nexus',
    'logo_img' => 'img/sistema/icone_nexus.png',
    //'logo_img_class' => 'brand-image img-circle elevation-3 bg-white',
    'logo_img_class' => 'brand-image',
    'logo_img_xl' => null,
    'logo_img_xl_class' => 'brand-image-xl',
    'logo_img_alt' => 'Empresa Logo',

    /*
    |--------------------------------------------------------------------------
    | Authentication Logo
    |--------------------------------------------------------------------------
    |
    | Here you can setup an alternative logo to use on your login and register
    | screens. When disabled, the admin panel logo will be used instead.
    |
    | For detailed instructions you can look the auth logo section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'auth_logo' => [
        'enabled' => true,
        'img' => [
            'path' => 'img/sistema/logo_nexus_c.png',
            'alt' => 'Nexus',
            'class' => 'img-fluid mt-3',
            'width' => 500,
            'height' => 500,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Preloader Animation
    |--------------------------------------------------------------------------
    |
    | Here you can change the preloader animation configuration. Currently, two
    | modes are supported: 'fullscreen' for a fullscreen preloader animation
    | and 'cwrapper' to attach the preloader animation into the content-wrapper
    | element and avoid overlapping it with the sidebars and the top navbar.
    |
    | For detailed instructions you can look the preloader section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'preloader' => [
        'enabled' => true,
        'mode' => 'fullscreen',
        'img' => [
            'path' => 'img/sistema/icone_nexus.png',
            'alt' => 'Nexus',
            'effect' => 'animation__shake',
            'width' => 100,
            'height' => 100,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Menu
    |--------------------------------------------------------------------------
    |
    | Here you can activate and change the user menu.
    |
    | For detailed instructions you can look the user menu section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'usermenu_enabled' => true,
    'usermenu_header' => true,
    'usermenu_header_class' => 'user-bg-nexus bg-gradient',
    'usermenu_image' => false,
    'usermenu_desc' => false,
    'usermenu_profile_url' => false,

    /*
    |--------------------------------------------------------------------------
    | Layout
    |--------------------------------------------------------------------------
    |
    | Here we change the layout of your admin panel.
    |
    | For detailed instructions you can look the layout section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'layout_topnav' => null,
    'layout_boxed' => null,
    'layout_fixed_sidebar' => true,
    'layout_fixed_navbar' => true,
    'layout_fixed_footer' => true,
    'layout_dark_mode' => null,

    /*
    |--------------------------------------------------------------------------
    | Authentication Views Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the authentication views.
    |
    | For detailed instructions you can look the auth classes section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'classes_auth_card' => '',
    'classes_auth_header' => 'card-outline-nexus',
    'classes_auth_body' => '',
    'classes_auth_footer' => '',
    'classes_auth_icon' => '',
    'classes_auth_btn' => 'btn-nexus',

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the admin panel.
    |
    | For detailed instructions you can look the admin panel classes here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'classes_body' => '',
    'classes_brand' => '',
    'classes_brand_text' => 'font-weight-bold text-capitalize',
    'classes_content_wrapper' => '',
    'classes_content_header' => '',
    'classes_content' => '',
    'classes_sidebar' => 'sidebar-dark-olive sidebar-nexus elevation-4',
    'classes_sidebar_nav' => '',
    'classes_topnav' => 'navbar-white navbar-light',
    'classes_topnav_nav' => 'navbar-expand',
    'classes_topnav_container' => 'container',

    /*
    |--------------------------------------------------------------------------
    | Sidebar
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar of the admin panel.
    |
    | For detailed instructions you can look the sidebar section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'sidebar_mini' => 'lg',
    'sidebar_collapse' => false,
    'sidebar_collapse_auto_size' => false,
    'sidebar_collapse_remember' => true,
    'sidebar_collapse_remember_no_transition' => true,
    'sidebar_scrollbar_theme' => 'os-theme-light',
    'sidebar_scrollbar_auto_hide' => 'l',
    'sidebar_nav_accordion' => true,
    'sidebar_nav_animation_speed' => 300,

    /*
    |--------------------------------------------------------------------------
    | Control Sidebar (Right Sidebar)
    |--------------------------------------------------------------------------
    |
    | Here we can modify the right sidebar aka control sidebar of the admin panel.
    |
    | For detailed instructions you can look the right sidebar section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'right_sidebar' => true,
    'right_sidebar_icon' => 'bi bi-grid-fill',
    'right_sidebar_theme' => 'nexus',
    'right_sidebar_slide' => true,
    'right_sidebar_push' => false,
    'right_sidebar_scrollbar_theme' => 'os-theme-light',
    'right_sidebar_scrollbar_auto_hide' => 'l',

    /*
    |--------------------------------------------------------------------------
    | URLs
    |--------------------------------------------------------------------------
    |
    | Here we can modify the url settings of the admin panel.
    |
    | For detailed instructions you can look the urls section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'use_route_url' => false,
    'dashboard_url' => 'home',
    'logout_url' => 'logout',
    'login_url' => 'login',
    'register_url' => 'register',
    'password_reset_url' => 'password/reset',
    'password_email_url' => 'password/email',
    'profile_url' => false,

    /*
    |--------------------------------------------------------------------------
    | Laravel Mix
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Laravel Mix option for the admin panel.
    |
    | For detailed instructions you can look the laravel mix section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Other-Configuration
    |
    */

    'enabled_laravel_mix' => false,
    'laravel_mix_css_path' => 'css/app.css',
    'laravel_mix_js_path' => 'js/app.js',

    /*
    |--------------------------------------------------------------------------
    | Menu Items
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar/top navigation of the admin panel.
    |
    | For detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Menu-Configuration
    |
    */

    'menu' => [
        
        /*
        |--------------------------------------------------------------------------------
        | Navbar 
        |--------------------------------------------------------------------------------
        |
        | Itens relacionados ao Navbar (Barra superior de Menu)
        |
        */
        [
            'text' => '',
            'icon' => 'fa-solid fa-house-chimney',
            'url' => 'home',
            'topnav' => true,
        ],
        [
            'text' => '',
            'icon' => 'fa-solid fa-phone',
            'url' => 'contato',
            'topnav' => true,
        ],
        // Caso voltar com a ideia de campo select no Navbar aqui é a label -> Campo: vendor/adminlte/partials/navbar/navbar.blade.php
        /*[
            'text' => 'Loja',
            'url' => '#',
            'topnav' => true,
            'active' => false,
        ],*/
        // Nesse momento não usamos o Dark Mode (mal formatado)
        /*[
            'type'           => 'darkmode-widget',
            'topnav_right'   => true, // Or "topnav => true" to place on the left.
             'icon_enabled'   => 'fas fa-moon',
             'icon_disabled'  => 'fas fa-sun',
             'color_enabled'  => 'grey',
             'color_disabled' => 'yellow'
        ],*/
        [
            'type'         => 'navbar-search',
            'text'         => 'Pesquisar',
            'topnav_right' => true,
        ],
        [
            'type' => 'navbar-notification',
            'id' => 'my-notification',                // An ID attribute (required).
            'icon' => 'fas fa-bell',                  // A font awesome icon (required).
            //'icon_color' => 'warning',                // The initial icon color (optional).
            //'label' => 0,                             // The initial label for the badge (optional).
            //'label_color' => 'danger',                // The initial badge color (optional).
            'url' => 'notifications/show',            // The url to access all notifications/elements (required).
            'topnav_right' => true,                   // Or "topnav => true" to place on the left (required).
            'dropdown_mode' => true,                  // Enables the dropdown mode (optional).
            'dropdown_flabel' => 'All notifications', // The label for the dropdown footer link (optional).
            'update_cfg' => [
                //'url' => 'notifications/get',         // The url to periodically fetch new data (optional).
                'period' => 30,                       // The update period for get new data (in seconds, optional).
            ],
        ],
        [
            'type'         => 'fullscreen-widget',
            'topnav_right' => true,
        ],

        /*
        |--------------------------------------------------------------------------------
        | Sidebar 
        |--------------------------------------------------------------------------------
        |
        | Itens relacionados ao Sidebar (Barra vertical esquerda do Menu Administrador)
        |
        */
        // Nesse momento não usamos aqui a pesquisa
        /*[
            'type' => 'sidebar-menu-search',
            'text' => 'Pesquisar',
        ],*/      

        /* ****************************** Itens de Menu da Navegação ****************************** */
        [
            'header' => 'Navegação',
        ],
        [
            'text' => 'Dashboard',
            'url'  => 'home',
            'icon' => 'nav-icon bi-icon bi bi-speedometer2',
            'submenu' => [
                [
                    'text' => 'Página Inicial',
                    'url'  => 'home',
                    'icon' => 'nav-icon nav-icon-chevron bi bi-chevron-right',
                    'active' => [
                        'home'
                    ],
                ],
                [
                    'text' => 'Analítico',
                    'url'  => '',
                    'icon' => 'nav-icon nav-icon-chevron bi bi-chevron-right',
                    'active' => [
                    ],
                ],
                [
                    'text' => 'Vendas',
                    'url'  => '',
                    'icon' => 'nav-icon nav-icon-chevron bi bi-chevron-right',
                    'active' => [
                    ],
                ],
            ],
        ],

        /* ****************************** Itens de Menu dos Parâmetros ****************************** */
        [
            'header' => 'Parâmetros',
            'can' => 'is_parameter'
        ],

        /* ****************************** Itens de Menu dos Parâmetros do Sistema ****************************** */
        [
            'text' => 'Parâmetros do Sistema',
            'icon' => 'nav-icon bi-icon bi bi-gear',
            'can'  => 'is_master',
            'submenu' => [
                [
                    'text' => 'Módulos do Sistema',
                    'url'  => '/parametros/sistema/modulosSistema',
                    'icon' => 'nav-icon nav-icon-chevron bi bi-chevron-right',
                    'active' => [
                        '/parametros/sistema/modulosSistema*'
                    ],
                ],
                [
                    'text' => 'Áreas',
                    'url'  => '/parametros/sistema/areasSistema',
                    'icon' => 'nav-icon nav-icon-chevron bi bi-chevron-right',
                    'active' => [
                        '/parametros/sistema/areasSistema*'
                    ],
                ],
                [
                    'text' => 'Grupos e Serviços NFS-e',
                    'url'  => '/parametros/sistema/homeParametrosSistemaServicos',
                    'icon' => 'nav-icon nav-icon-chevron bi bi-chevron-right',
                    'active' => [
                        '/parametros/sistema/gruposServicosSistema*',
                        '/parametros/sistema/servicosSistema*'
                    ],
                ],
            ],
        ],

        /* ****************************** Itens de Menu dos Parâmetros Gerais ****************************** */
        [
            'text' => 'Parâmetros Gerais',
            'icon' => 'nav-icon bi-icon bi bi-building-gear',
            'can'  => 'is_parameter',
            'submenu' => [
                [
                    'text' => 'Gerencial',
                    'icon' => 'nav-icon bi bi-list',
                    'submenu' => [
                        [
                            'text' => 'Geral da Empresa',
                            'url'  => '/parametros/gerencial/geralEmpresa',
                            'icon' => 'nav-icon nav-icon-chevron bi bi-chevron-right',
                            'active' => [
                                '/parametros/gerencial/geralEmpresa*',
                            ],
                        ],
                        [
                            'text' => 'Setores',
                            'url'  => '/parametros/gerencial/setorEmpresa',
                            'icon' => 'nav-icon nav-icon-chevron bi bi-chevron-right',
                            'active' => [
                                '/parametros/gerencial/setorEmpresa*',
                            ],
                        ],
                        [
                            'text' => 'Motivos de Cancelamento',
                            'url'  => '/parametros/gerencial/motivoCancelamento',
                            'icon' => 'nav-icon nav-icon-chevron bi bi-chevron-right',
                            'active' => [
                                '/parametros/gerencial/motivoCancelamento*',
                            ],
                        ],
                        [
                            'text' => 'Motivos de Suspensão',
                            'url'  => '/parametros/gerencial/motivoSuspensao',
                            'icon' => 'nav-icon nav-icon-chevron bi bi-chevron-right',
                            'active' => [
                                '/parametros/gerencial/motivoSuspensao*',
                            ],
                        ],
                    ],
                ],
                [
                    'text' => 'Faturamento',
                    'icon' => 'nav-icon bi bi-list',
                    'can'  => 'is_par_faturamento',
                    'submenu' => [
                        [
                            'text' => 'Emissão de NFS-e',
                            'url'  => '/parametros/faturamento/nfs/homeParametroFatNfs',
                            'icon' => 'nav-icon nav-icon-chevron bi bi-chevron-right',
                            'active' => [
                                '/parametros/faturamento/nfs/emissaoNFSe*',
                                '/parametros/faturamento/nfs/conexaoNFSe*',
                                '/parametros/faturamento/nfs/provedorNFS*',
                            ],
                        ],
                        [
                            'text' => 'Geral da Empresa',
                            'url'  => '/parametros/faturamento/faturamentoGeral',
                            'icon' => 'nav-icon nav-icon-chevron bi bi-chevron-right',
                            'active' => [
                                '/parametros/faturamento/faturamentoGeral*',
                            ],
                        ],
                    ],
                ],
                [
                    'text' => 'Financeiro',
                    'icon' => 'nav-icon bi bi-list',
                    'can'  => 'is_par_faturamento',//aki ainda trem que fazer o parametro financeiro
                    'submenu' => [
                        [
                            'text' => 'Cartão Corporativo',
                            'url'  => '/parametros/financeiro/financeiroCorporativo',
                            'icon' => 'nav-icon nav-icon-chevron bi bi-chevron-right',
                            'active' => [
                                '/parametros/financeiro/financeiroCorporativo*'
                            ],
                        ],
                    ],
                ],
                [
                    'text' => 'Serviços',
                    'icon' => 'nav-icon bi bi-list',
                    'can' => 'is_par_servico',
                    'submenu' => [
                        [
                            'text' => 'Geral da Empresa',
                            'url'  => '/parametros/servico/geralServico',
                            'icon' => 'nav-icon nav-icon-chevron bi bi-chevron-right',
                            'active' => [
                                '/parametros/servico/geralServico*',
                            ],
                        ],
                        [
                            'text' => 'Categorias de Atendimento',
                            'url'  => '/parametros/servico/categAtendimento',
                            'icon' => 'nav-icon nav-icon-chevron bi bi-chevron-right',
                            'can' => 'is_mod_servico',
                            'active' => [
                                '/parametros/servico/categAtendimento*',
                            ],
                        ],
                        [
                            'text' => 'Etapas de Atendimento',
                            'url'  => '/parametros/servico/etapaAtendimento',
                            'icon' => 'nav-icon nav-icon-chevron bi bi-chevron-right',
                            'can' => 'is_mod_servico',
                            'active' => [
                                '/parametros/servico/etapaAtendimento*',
                            ],
                        ],
                        [
                            'text' => 'Tarefas Mão de Obra',
                            'url'  => '/parametros/servico/servicoTMO',
                            'icon' => 'nav-icon nav-icon-chevron bi bi-chevron-right',
                            'can' => 'is_mod_servico',
                            'active' => [
                                '/parametros/servico/servicoTMO*',
                            ],
                        ],
                        [
                            'text' => 'Tipos de Serviço',
                            'url'  => '/parametros/servico/tipoServico',
                            'icon' => 'nav-icon nav-icon-chevron bi bi-chevron-right',
                            'can' => 'is_mod_servico',
                            'active' => [
                                '/parametros/servico/tipoServico*',
                            ],
                        ],
                    ],
                ],
            ],
        ],

        /* ****************************** Itens de Menu dos Cadastros ****************************** */
        [
            'header' => 'Cadastros',
            'can'  => 'is_register',
        ],

        [
            'text' => 'Cliente',
            'url'  => '/cadastros/cliente/cadastroCliente',
            'icon' => 'nav-icon fa-icon fa-light fa-user-tie',
            'can'  => 'is_register',
            'active' => [
                '/cadastros/cliente/cadastroCliente*'
            ],
        ],
        [
            'text' => 'Empresa',
            'url'  => '/cadastros/empresa/cadastroEmpresa',
            'icon' => 'nav-icon bi-icon bi bi-buildings',
            'can'  => 'is_register',
            'active' => [
                '/cadastros/empresa/cadastroEmpresa*'
            ],
        ],
        [
            'text' => 'Prestador',
            'url'  => '/cadastros/prestador/cadastroPrestador',
            'icon' => 'nav-icon fa-icon fa-light fa-user-helmet-safety',
            'can'  => 'is_register_prestador',
            'active' => [
                '/cadastros/prestador/cadastroPrestador*'
            ],
        ],
        [
            'text' => 'Razão',
            'url'  => 'cadastros/financeiro/cadastroRazao',
            'icon' => 'nav-icon bi-icon bi bi-bank',
            'can'  => 'is_register',
            'active' => [
                '/cadastros/financeiro/cadastroRazao*'
            ],
        ],
        [
            'text' => 'Adm. Cartão',
            'url'  => '/cadastros/cartao/cadastroCartao',
            'icon' => 'nav-icon bi-icon bi bi-credit-card-2-back',
            'can'  => 'is_register',
            'active' => [
                '/cadastros/cartao/cadastroCartao*'
            ],
        ],
        [
            'text' => 'Usuario',
            'url'  => '/cadastros/usuario/cadastroUsuario',
            'icon' => 'nav-icon fa-icon fa-light fa-user-shield',
            'can'  => 'is_register',
            'active' => [
                '/cadastros/usuario/cadastroUsuario*'
            ],
        ],

        /* ****************************** Itens de Menu dos Módulos de Serviços ****************************** */
        [
            'header' => 'Serviços',
            'can' => 'is_acessa_mod_servico',
        ],

        /* ****************************** Itens de Menu do Módulo de Serviço ****************************** */
        [
            'text' => 'Lançamento de OS',
            'icon' => 'nav-icon bi-icon bi bi-file-earmark-text',
            'can' => 'is_lancamento_os',
            'submenu' => [
                [
                    'text' => 'Emissão de OS',
                    'url'  => 'lancamentos/servico/homeEmissaoOS',
                    'icon' => 'nav-icon nav-icon-chevron bi bi-chevron-right',
                    'active' => [
                        '/lancamentos/servico/controleAberturaOS*',
                    ],
                ],
                [
                    'text' => 'Situação de OS',
                    'url'  => '/lancamentos/servico/homeSituacaoOS',
                    'icon' => 'nav-icon nav-icon-chevron bi bi-chevron-right',
                    'active' => [
                        '/lancamentos/servico/consultaSituacaoOS*',
                        '/lancamentos/servico/consultaSituacaoOS/painelAberturaOS*',
                        '/lancamentos/servico/painelAberturaOS*'
                    ],
                ],
                [
                    'text' => 'Orçamentos',
                    'url'  => '/lancamentos/servico/homeOrcamentoOS',
                    'icon' => 'nav-icon nav-icon-chevron bi bi-chevron-right',
                    'active' => [
                        '/lancamentos/servico/consultaOrcamentoOS*'
                    ],
                ],
            ],
        ],

        /* ****************************** Itens de Menu do Módulo de Controle de Produção ****************************** */
        [
            
            'text' => 'Controle de Produção',
            'icon' => 'nav-icon bi-icon bi bi-boxes',
            'can' => 'is_controle_producao',
            'submenu' => [
                [
                    'text' => 'Painel de Operação',
                    'url'  => '/lancamentos/producao/homePainelOperador',
                    'icon' => 'nav-icon nav-icon-chevron bi bi-chevron-right',
                    'active' => [
                        '/lancamentos/producao/consultaPainelOperacao*'
                    ],
                ],
                [
                    'text' => 'Painel de Produção',
                    'url'  => '/lancamentos/producao/homePainelProducao',
                    'icon' => 'nav-icon nav-icon-chevron bi bi-chevron-right',
                    'active' => [
                        '/lancamentos/producao/painelProducao*'
                    ],
                ],
                [
                    'text' => 'Painel de Agendamento',
                    'url'  => '/lancamentos/producao/homeAgendamentoPrestador',
                    'icon' => 'nav-icon nav-icon-chevron bi bi-chevron-right',
                    'active' => [
                        '/lancamentos/producao/calendarioAgendamentoPrestador*'
                    ],
                ],
            ],
        ],

        /* ****************************** Itens de Menu dos Módulos de Emissão de NF ****************************** */
        [
            'header' => 'Faturamento',
            'can'  => 'is_acessa_mod_nf',
        ],

        /* ****************************** Itens de Menu do Módulo de Emissão Simplificada de NFS-e ****************************** */
        [
            'text' => 'Emissão Simplificada',
            'icon' => 'nav-icon bi-icon bi bi-ticket',
            'can'  => 'is_emissao_simp_nf',
            'submenu' => [
                [
                    'text' => 'Emissão de NFS-e',
                    'url'  => '/faturamento/notas/simplificada/homeEmissaoSimplificadaNFS',
                    'icon' => 'nav-icon nav-icon-chevron bi bi-chevron-right',
                    'active' => [
                        '/faturamento/notas/simplificada/formularioEmissaoSimplificadaNFS*'
                    ],
                ],
                [
                    'text' => 'Reemissão de NFS-e',
                    'url'  => 'faturamento/notas/simplificada/controleReemissaoSimpNF',
                    'icon' => 'nav-icon nav-icon-chevron bi bi-chevron-right',
                    'active' => [
                        '/faturamento/notas/simplificada/consultaReemissaoSimpNF*',
                        '/faturamento/notas/fat_cnt001_GeracaoNF/REEMISSAO_SIMP*'
                    ],
                ],
            ],
        ],

        /* ****************************** Itens de Menu do Módulo de Faturamento de NF ****************************** */
        [
            'text' => 'Faturamento de Notas',
            'icon' => 'nav-icon bi-icon bi bi-receipt',
            'can'  => 'is_emissao_nf',
            'submenu' => [
                [
                    'text' => 'Emissão de NFS-e',
                    'url'  => 'faturamento/notas/fat_cnt001_EmissaoNF',
                    'icon' => 'nav-icon nav-icon-chevron bi bi-chevron-right',
                    'active' => [
                        '/faturamento/notas/fat_cns001*',
                        '/faturamento/notas/fat_pnl001*',
                        '/faturamento/notas/fat_cnt001_GeracaoNF/EMISSAO/*'
                    ],
                ],
                [
                    'text' => 'Reemissão de NFS-e',
                    'url'  => 'faturamento/notas/controleReemissaoNF',
                    'icon' => 'nav-icon nav-icon-chevron bi bi-chevron-right',
                    'active' => [
                        '/faturamento/notas/fat_cns001_ReemissaoNF*',
                        '/faturamento/notas/fat_cnt001_GeracaoNF/REEMISSAO/*'
                    ],
                ],
            ],
        ],

        /* ****************************** Itens de Menu dos Eventos de NF ****************************** */
        [
            'text' => 'Eventos de NF-e/NFS-e',
            'icon' => 'nav-icon bi-icon bi bi-cloud-arrow-up',
            'submenu' => [
                [
                    'text' => 'Cancelamento de NF-e/NFS-e',
                    'url'  => '',
                    'icon' => 'nav-icon nav-icon-chevron bi bi-chevron-right',
                ],
                [
                    'text' => 'Inutilização de NF-e/NFS-e',
                    'url'  => '',
                    'icon' => 'nav-icon nav-icon-chevron bi bi-chevron-right',
                ],
                [
                    'text' => 'Carta de Correção',
                    'url'  => '',
                    'icon' => 'nav-icon nav-icon-chevron bi bi-chevron-right',
                ],
            ],
        ],

        /* ****************************** Header do Módulo Financeiro ****************************** */
        [
            'header' => 'Financeiro',
            'can'  => '',
        ],

        /* ****************************** Itens de Menu do Módulo Financeiro - Pagamentos ****************************** */
        [
            'text' => 'Pagamentos',
            'icon' => 'nav-icon bi-icon bi bi-wallet2',
            'can'  => 'is_emissao_simp_nf',
            'submenu' => [
                [
                    'text' => 'Conta Corrente',
                    'url'  => '',
                    'icon' => 'nav-icon nav-icon-chevron bi bi-chevron-right',
                    'submenu' => [
                        [
                            'text' => 'Individual em Dinheiro',
                            'url'  => 'financeiro/pagamento/fin_cnt005_HomePCC_InDin',
                            'icon' => 'nav-icon nav-icon-chevron bi bi-chevron-right',
                            'active' => [
                                '/financeiro/pagamento/fin_cns005_ContasPagamento*',
                                '/financeiro/pagamento/fin_frm005_FormularioPagDin*'
                            ],
                        ],
                        [
                            'text' => 'Lote',
                            'route'  => ['home.homePCCLote', ['appOrigem' => 'LOTE']],
                            'icon' => 'nav-icon nav-icon-chevron bi bi-chevron-right',
                            'active' => [
                                '/financeiro/pagamento/fin_cns006_ContasPagamento/LOTE*',
                                '/financeiro/pagamento/fin_pnl006_PainelLote/LOTE*'
                            ],
                        ],
                        [
                            'text' => 'Consulta de Lotes',
                            'url'  => '/financeiro/pagamento/fin_cnt008_HomeLoteConsulta',
                            'icon' => 'nav-icon nav-icon-chevron bi bi-chevron-right',
                            'active' => [
                                '/financeiro/pagamento/fin_cns008_ConsultaLote*',
                            ],
                        ],
                        [
                            'text' => 'Adiantamento de Fornecedor',
                            'icon' => 'nav-icon bi bi-list',
                            'submenu' => [
                                [
                                    'text' => 'Inclusão',
                                    'url'  => '/financeiro/pagamento/fin_frm007_FormularioInclusaoAF',
                                    'icon' => 'nav-icon nav-icon-chevron bi bi-chevron-right',
                                ],
                                [
                                    'text' => 'Pagamento',
                                    'route'  => ['home.homePCCLote', ['appOrigem' => 'PAG_AF']],
                                    'icon' => 'nav-icon nav-icon-chevron bi bi-chevron-right',
                                    'active' => [
                                        '/financeiro/pagamento/fin_cns006_ContasPagamento/PAG_AF*',
                                        '/financeiro/pagamento/fin_pnl006_PainelLote/PAG_AF*'
                                    ],
                                ]
                            ],
                        ],
                    ],
                ],
                [
                    'text' => 'Pequenas Despesas',
                    'url'  => 'financeiro/pagamento/fin_cnt004_HomePPD',
                    'icon' => 'nav-icon nav-icon-chevron bi bi-chevron-right',
                    'active' => [
                        '/financeiro/Pagamento/fin_frm004_FormularioPPD*',
                    ],
                ],
            ],
        ],

        /* ****************************** Itens de Menu do Módulo Financeiro - Recebimentos ****************************** */
        [
            'text' => 'Recebimentos',
            'icon' => 'nav-icon bi-icon bi bi-cash-stack',
            'can'  => 'is_emissao_simp_nf',
            'submenu' => [
                [
                    'text' => 'Contas Correntes',
                    'url'  => 'financeiro/recebimento/fin_cnt001_HomeCCT',
                    'icon' => 'nav-icon nav-icon-chevron bi bi-chevron-right',
                    'active' => [
                        'financeiro/recebimento/fin_pnl001_PainelCCT*'
                    ],
                ],
                [
                    'text' => 'Duplicatas',
                    'url'  => 'financeiro/recebimento/duplicata/fin_cnt002_HomeDUP',
                    'icon' => 'nav-icon nav-icon-chevron bi bi-chevron-right',
                    'active' => [
                        'financeiro/recebimento/duplicata/*',
                        'financeiro/recebimento/duplicata/abertas/fin_cns002_DupAberta*'
                    ],
                ],
                [
                    'text' => 'Outros',
                    'url'  => 'financeiro/recebimento/outros/fin_cnt003_HomeOUT',
                    'icon' => 'nav-icon nav-icon-chevron bi bi-chevron-right',
                    'active' => [
                        '/financeiro/recebimento/outros/*'
                    ],
                ],
            ],
        ],

        /* ****************************** Itens de Menu do Módulo Financeiro - Transferências ****************************** */
        [
            'text' => 'Transferências',
            'icon' => 'nav-icon bi-icon bi bi-arrow-left-right',
            'can'  => 'is_emissao_simp_nf',
            'submenu' => [
                [
                    'text' => 'Consulta',
                    'url'  => 'financeiro/transferencia/fin_cnt011_HomeConsultaTransferencias',
                    'icon' => 'nav-icon nav-icon-chevron bi bi-chevron-right',
                    'active' => [
                        '/financeiro/transferencia/fin_cns011_RelatorioTransferencias*'
                    ],
                ],
                [
                    'text' => 'Reforço de Caixa',
                    'url'  => 'financeiro/transferencia/fin_cnt009_HomeReforcoCaixa',
                    'icon' => 'nav-icon nav-icon-chevron bi bi-chevron-right',
                    'active' => [
                        '/financeiro/transferencia/fin_pnl009_PainelReforcoCaixa*'
                    ],
                ],
                [
                    'text' => 'Trasferência de Caixa',
                    'url'  => '/financeiro/transferencia/fin_cnt010_HomeTransferenciaCaixa',
                    'icon' => 'nav-icon nav-icon-chevron bi bi-chevron-right',
                    'active' => [
                        '/financeiro/transferencia/fin_pnl010_PainelTransferenciaCaixa*'
                    ],
                ],
            ],
        ],

        /* ****************************** Itens de Menu do Módulo Financeiro - Saldos Financeiros (BA,TE,CX) ****************************** */
        [
            'text' => 'Saldos',
            'url'  => '',
            'icon' => 'nav-icon bi-icon bi bi-clipboard-data',
            'can'  => 'is_emissao_simp_nf',
            'active' => [
                ''
            ],
        ],

        /* ****************************** Itens de Menu do Módulo Financeiro - Manutenção de contas correntes ****************************** */
        [
            'text' => 'Manutenção de Contas',
            'icon' => 'nav-icon bi-icon bi bi-house-gear',
            'can'  => 'is_emissao_simp_nf',
            'submenu' => [
                [
                    'text' => 'Conta Corrente',
                    'url'  => '',
                    'icon' => 'nav-icon nav-icon-chevron bi bi-chevron-right',
                ],
                [
                    'text' => 'Duplicatas',
                    'url'  => '',
                    'icon' => 'nav-icon nav-icon-chevron bi bi-chevron-right',
                ],
            ],
        ],

        /* ****************************** Itens de Menu do Módulo Financeiro - Transferências ****************************** */
        [
            'text' => 'Gerênciamento de Cartões',
            'icon' => 'nav-icon bi-icon bi bi-credit-card-2-front',
            'can'  => 'is_emissao_simp_nf',
            'submenu' => [
                [
                    'text' => 'Baixa',
                    'url'  => '',
                    'icon' => 'nav-icon nav-icon-chevron bi bi-chevron-right',
                ],
                [
                    'text' => 'Consulta',
                    'url'  => '',
                    'icon' => 'nav-icon nav-icon-chevron bi bi-chevron-right',
                ],
                [
                    'text' => 'Manutenção',
                    'url'  => '',
                    'icon' => 'nav-icon nav-icon-chevron bi bi-chevron-right',
                ],
                [
                    'text' => 'Remessa',
                    'url'  => '',
                    'icon' => 'nav-icon nav-icon-chevron bi bi-chevron-right',
                ],
            ],
        ],

        /* ****************************** Itens de Menu do Módulo Financeiro - Arquivo de Cobrança Bancária ****************************** */
        [
            'text' => 'Arquivo de Cobrança',
            'icon' => 'nav-icon bi-icon bi bi-filetype-txt',
            'can'  => 'is_emissao_simp_nf',
            'submenu' => [
                [
                    'text' => 'Arquivos de Retorno',
                    'url'  => '',
                    'icon' => 'nav-icon nav-icon-chevron bi bi-chevron-right',
                ],
                [
                    'text' => 'Consultar Borderôs',
                    'url'  => '',
                    'icon' => 'nav-icon nav-icon-chevron bi bi-chevron-right',
                ],
                [
                    'text' => 'Gerar Borderô',
                    'url'  => '',
                    'icon' => 'nav-icon nav-icon-chevron bi bi-chevron-right',
                ],
                [
                    'text' => 'Importar Retorno',
                    'url'  => '',
                    'icon' => 'nav-icon nav-icon-chevron bi bi-chevron-right',
                ],
            ],
        ],

        /* ****************************** Itens de Menu do Módulo Financeiro - Lançamento de Gastos ****************************** */
        [
            'text' => 'Lançamento de Gastos',
            'icon' => 'nav-icon bi-icon bi bi-cash-coin',
            'can'  => 'is_emissao_simp_nf',
            'submenu' => [
                [
                    'text' => 'Gastos Diversos',
                    'url'  => '',
                    'icon' => 'nav-icon nav-icon-chevron bi bi-chevron-right',
                ],
            ],
        ],

        /* ****************************** Itens de Menu do Módulo Financeiro - Conciliação Bancária Manual ****************************** */
        [
            'text' => 'Conciliação Bancária',
            'url'  => '',
            'icon' => 'nav-icon bi-icon bi bi-journal-bookmark',
            'can'  => 'is_emissao_simp_nf',
        ],

        /* ****************************** Itens de Menu do Módulo Financeiro - Operações Financeiras ****************************** */
        [
            'text' => 'Operações Financeira',
            'icon' => 'nav-icon bi-icon bi-gear-wide-connected',
            'can'  => 'is_emissao_simp_nf',
            'submenu' => [
                [
                    'text' => 'Consulta',
                    'url'  => '',
                    'icon' => 'nav-icon nav-icon-chevron bi bi-chevron-right',
                ],
                [
                    'text' => 'Cancelamento',
                    'url'  => '',
                    'icon' => 'nav-icon nav-icon-chevron bi bi-chevron-right',
                ],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Menu Filters
    |--------------------------------------------------------------------------
    |
    | Here we can modify the menu filters of the admin panel.
    |
    | For detailed instructions you can look the menu filters section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Menu-Configuration
    |
    */

    'filters' => [
        JeroenNoten\LaravelAdminLte\Menu\Filters\GateFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\HrefFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\SearchFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ActiveFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ClassesFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\LangFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\DataFilter::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Plugins Initialization
    |--------------------------------------------------------------------------
    |
    | Here we can modify the plugins used inside the admin panel.
    |
    | For detailed instructions you can look the plugins section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Plugins-Configuration
    |
    */

    'plugins' => [
        'Inputmask' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/inputmask/inputmask.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/inputmask/jquery.inputmask.js',
                ],
            ],
        ],
        'DateRangePicker' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/moment/moment.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/daterangepicker/daterangepicker.js',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'vendor/daterangepicker/daterangepicker.css',
                ],
            ],
        ],
        'Select2' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/select2/js/select2.full.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'vendor/select2/css/select2.min.css',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'vendor/select2-bootstrap4-theme/select2-bootstrap4.min.css',
                ],
            ],
        ],
        'BootstrapSelect' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/bootstrap-select/dist/js/bootstrap-select.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'vendor/bootstrap-select/dist/css/bootstrap-select.min.css',
                ],
            ],
        ],
        'Datatables' => [
            'active' => false,
            'files' => [
                /*
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => '//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css',
                ],*/
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/datatables/js/jquery.dataTables.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/datatables/js/dataTables.bootstrap4.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'vendor/datatables/css/dataTables.bootstrap4.min.css',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'vendor/datatables-nexus/css/nexus.style.datatable.css',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/datatables-nexus/js/nexus.settings.datatable.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/datatables-nexus/js/dataTable.lang.pt-BR.js',
                ],
            ],
        ],
        'DatatablesPlugins' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/datatables-plugins/buttons/js/dataTables.buttons.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/datatables-plugins/buttons/js/buttons.bootstrap4.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/datatables-plugins/buttons/js/buttons.html5.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/datatables-plugins/buttons/js/buttons.print.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/datatables-plugins/jszip/jszip.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/datatables-plugins/pdfmake/pdfmake.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/datatables-plugins/pdfmake/vfs_fonts.js',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'vendor/datatables-plugins/buttons/css/buttons.bootstrap4.min.css',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/datatables-plugins/buttons/js/buttons.flash.min.js',
                ],
            ],
        ],
        'Chartjs' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.bundle.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '../../vendor/chart.js/Chart.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '../../vendor/chart.js/Chart.min.css',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '../../vendor/chart.js/Chart.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '../../vendor/chart.js/Chart.css',
                ],
            ],
        ],
        'Sweetalert2' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '../../vendor/sweetalert2/sweetalert2.all.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => '../../vendor/sweetalert2/sweetalert2.min.css',
                ],
            ],
        ],
        'Pace' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/themes/blue/pace-theme-center-radar.min.css',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/pace.min.js',
                ],
            ],
        ],
        'jqueryValidation' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/jquery-validation/jquery.validate.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/jquery-validation/additional-methods.min.js',
                ],
            ],
        ],
        'toastr' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/toastr/toastr.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'vendor/toastr/toastr.min.css',
                ],
            ],
        ],
        'BootstrapSwitch' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/bootstrap-switch/js/bootstrap-switch.min.js',
                ],
            ],
        ],
        'icheckBootstrap' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'vendor/icheck-bootstrap/icheck-bootstrap.css',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'vendor/icheck-bootstrap/icheck-bootstrap.min.css',
                ],
            ],
        ],
        'Fullcalendar' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'vendor/fullcalendar/main.css',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/fullcalendar/main.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/fullcalendar/locales/pt-br.js',
                ],
            ],
        ],
        'Moment' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/moment/moment.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/moment/locale/pt-br.js',
                ],
            ],
        ],
        'Jquery-ui' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/jquery-ui/jquery-ui.min.js',
                ],
            ],
        ],
        'Summernote' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/summernote/summernote-bs4.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'vendor/summernote/summernote-bs4.min.css',
                ],
            ],
        ],
        'BsCustomFileInput' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/bs-custom-file-input/bs-custom-file-input.min.js',
                ],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | IFrame
    |--------------------------------------------------------------------------
    |
    | Here we change the IFrame mode configuration. Note these changes will
    | only apply to the view that extends and enable the IFrame mode.
    |
    | For detailed instructions you can look the iframe mode section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/IFrame-Mode-Configuration
    |
    */

    'iframe' => [
        'default_tab' => [
            'url' => 'homePrincipal',
            'title' => 'Página Inicial',
        ],
        'buttons' => [
            'close' => true,
            'close_all' => true,
            'close_all_other' => true,
            'scroll_left' => true,
            'scroll_right' => true,
            'fullscreen' => true,
        ],
        'options' => [
            'loading_screen' => 1000,
            'auto_show_new_tab' => true,
            'use_navbar_items' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Livewire
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Livewire support.
    |
    | For detailed instructions you can look the livewire here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Other-Configuration
    |
    */

    'livewire' => false,
];
