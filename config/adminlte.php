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

    'title' => 'Sistema Nexus',
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
    'logo_img_class' => 'brand-image img-circle elevation-3 bg-white',
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
    | Here you can change the preloader animation configuration.
    |
    | For detailed instructions you can look the preloader section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'preloader' => [
        'enabled' => true,
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

    'classes_auth_card' => 'card-outline card-navy',
    'classes_auth_header' => '',
    'classes_auth_body' => '',
    'classes_auth_footer' => '',
    'classes_auth_icon' => '',
    'classes_auth_btn' => 'btn-flat btn-info',

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

    'right_sidebar' => false,
    'right_sidebar_icon' => 'fas fa-cogs',
    'right_sidebar_theme' => 'dark',
    'right_sidebar_slide' => true,
    'right_sidebar_push' => true,
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
        
        // Navbar items:
        [
            'text' => 'Home',
            'url' => 'home',
            'topnav' => true,
        ],
        [
            'text' => 'Contato',
            'url' => 'contato',
            'topnav' => true,
        ],
        [
            'type'         => 'navbar-search',
            'text'         => 'Pesquisar',
            'topnav_right' => true,
        ],
        [
            'type'         => 'fullscreen-widget',
            'topnav_right' => true,
        ],
        /*
        [
            'type'           => 'darkmode-widget',
            'topnav_right'   => true, // Or "topnav => true" to place on the left.
             'icon_enabled'   => 'fas fa-moon',
             'icon_disabled'  => 'fas fa-sun',
             'color_enabled'  => 'grey',
             'color_disabled' => 'yellow'
        ],
        */
        // Sidebar items:
        /*[
            'type' => 'sidebar-menu-search',
            'text' => 'Pesquisar',
        ],*/
        [
            'text' => 'Página Inicial',
            'url'  => 'home',
            'icon' => 'nav-icon fas fa-home',
        ],
        [
            'header' => 'Área de Parâmetros',
            'can' => 'is_parameter'
        ],
        [
            'text' => 'Parâmetros do Sistema',
            'icon' => 'nav-icon fa-solid fa-gears',
            'can'  => 'is_master',
            'submenu' => [
                [
                    'text' => 'Módulos do Sistema',
                    'url'  => '/parametros/sistema/homeParametrosSistemaModulos',
                    'icon' => 'nav-icon fa-regular fa-circle',
                ],
                [
                    'text' => 'Áreas',
                    'url'  => '/parametros/sistema/homeParametrosSistemaAreas',
                    'icon' => 'nav-icon fa-regular fa-circle',
                ],
                [
                    'text' => 'Grupos e Serviços NFS-e',
                    'url'  => '/parametros/sistema/homeParametrosSistemaServicos',
                    'icon' => 'nav-icon fa-regular fa-circle',
                ],
            ],
        ],

        [
            'text' => 'Parâmetros Gerais',
            'icon' => 'nav-icon fa-solid fa-gear',
            'can'  => 'is_parameter',
            'submenu' => [
                [
                    'text' => 'Gerencial',
                    'icon' => 'nav-icon fa-solid fa-list',
                    'submenu' => [
                        [
                            'text' => 'Geral da Empresa',
                            'url'  => '/parametros/gerencial/homeParametrosGerencialEmpresa',
                            'icon' => 'nav-icon fa-regular fa-circle',
                        ],
                        [
                            'text' => 'Setores',
                            'url'  => '/parametros/servico/homeParametrosServicoSetor',
                            'icon' => 'nav-icon fa-regular fa-circle',
                        ],
                        [
                            'text' => 'Motivos de Cancelamento',
                            'url'  => '/parametros/sistema/homeParametrosSistemaMotivosCancelamento',
                            'icon' => 'nav-icon fa-regular fa-circle',
                        ],
                        [
                            'text' => 'Motivos de Suspensão',
                            'url'  => '/parametros/sistema/homeParametrosSistemaMotivosSuspensao',
                            'icon' => 'nav-icon fa-regular fa-circle',
                        ],
                    ],
                ],
                [
                    'text' => 'Faturamento',
                    'icon' => 'nav-icon fa-solid fa-list',
                    'can'  => 'is_par_faturamento',
                    'submenu' => [
                        /*[
                            'text' => 'Emissão de NF-e',
                            'url'  => '',
                            'icon' => 'nav-icon fa-regular fa-circle',
                        ],*/
                        [
                            'text' => 'Emissão de NFS-e',
                            'url'  => '/parametros/faturamento/nfs/homeParametroFatNfs',
                            'icon' => 'nav-icon fa-regular fa-circle',
                        ],
                        [
                            'text' => 'Geral da Empresa',
                            'url'  => '/parametros/faturamento/homeParametrosFatEmpresa',
                            'icon' => 'nav-icon fa-regular fa-circle',
                        ],
                    ],
                ],
                [
                    'text' => 'Serviços',
                    'icon' => 'nav-icon fa-solid fa-list',
                    'can' => 'is_par_servico',
                    'submenu' => [
                        [
                            'text' => 'Geral da Empresa',
                            'url'  => '/parametros/servico/homeParametrosServicoEmpresa',
                            'icon' => 'nav-icon fa-regular fa-circle',
                        ],
                        [
                            'text' => 'Categorias de Atendimento',
                            'url'  => '/parametros/servico/homeLancamentosServicoCategoria',
                            'icon' => 'nav-icon fa-regular fa-circle',
                            'can' => 'is_mod_servico',
                        ],
                        [
                            'text' => 'Etapas de Atendimento',
                            'url'  => '/parametros/servico/homeLancamentosServicoEtapas',
                            'icon' => 'nav-icon fa-regular fa-circle',
                            'can' => 'is_mod_servico',
                        ],
                        [
                            'text' => 'Tarefas Mão de Obra',
                            'url'  => '/parametros/servico/homeParametrosServicoTMO',
                            'icon' => 'nav-icon fa-regular fa-circle',
                            'can' => 'is_mod_servico',
                        ],
                        [
                            'text' => 'Tipos de Serviço',
                            'url'  => '/parametros/servico/homeLancamentosServicoTipo',
                            'icon' => 'nav-icon fa-regular fa-circle',
                            'can' => 'is_mod_servico',
                        ],
                    ],
                ],
            ],
        ],

        [
            'header' => 'Área de Cadastros',
            'can'  => 'is_register',
        ],

        [
            'text' => 'Cadastros',
            'icon' => 'nav-icon fa-solid fa-file-circle-plus',
            'can'  => 'is_register',
            'submenu' => [
                [
                    'text' => 'Empresa',
                    'url'  => '/cadastros/empresa/homeEmpresa',
                    'icon' => 'nav-icon fa-regular fa-circle',
                ],
                [
                    'text' => 'Usuario',
                    'url'  => '/cadastros/usuario/homeUsuarios',
                    'icon' => 'nav-icon fa-regular fa-circle',
                ],
                [
                    'text' => 'Prestadores',
                    'url'  => '/cadastros/prestador/homePrestadores',
                    'icon' => 'nav-icon fa-regular fa-circle',
                ],
                [
                    'text' => 'Cliente',
                    'url'  => 'cadastros/cliente/homeClientes',
                    'icon' => 'nav-icon fa-regular fa-circle',
                ],
                /*[
                    'text' => 'Banco',
                    'url'  => 'bancos',
                    'icon' => 'nav-icon fa-solid fa-building-columns',
                ],
                [
                    'text' => 'Produto',
                    'url'  => 'products',
                    'icon' => 'nav-icon fa-solid fa-cart-plus',
                ],*/
            ],
        ],

        [
            'header' => 'Área de Serviços',
            'can' => 'is_emite_os',
        ],

        [
            'text' => 'Serviços',
            'icon' => 'nav-icon fa-solid fa-file-invoice',
            'can' => 'is_emite_os',
            'submenu' => [
                [
                    'text' => 'Lançamento de OS',
                    'icon' => 'nav-icon fa-solid fa-list',
                    'submenu' => [
                        [
                            'text' => 'Emissão de OS',
                            'url'  => 'lancamentos/servico/homeEmissaoOS',
                            'icon' => 'nav-icon fa-regular fa-circle',
                        ],
                        [
                            'text' => 'Situação de OS',
                            'url'  => '/lancamentos/servico/homeSituacaoOS',
                            'icon' => 'nav-icon fa-regular fa-circle',
                        ],
                        [
                            'text' => 'Orçamentos',
                            'url'  => '/lancamentos/servico/homeOrcamentoOS',
                            'icon' => 'nav-icon fa-regular fa-circle',
                        ],
                    ],
                ],
                [
                    
                    'text' => 'Controle de Produção',
                    'icon' => 'nav-icon fa-solid fa-list',
                    'submenu' => [
                        [
                            'text' => 'Painel de Operação',//Painel de operação de serviços alocados ao prestador para inicio/finalizaçao da tmo
                            'url'  => '/lancamentos/producao/homePainelOperador',
                            'icon' => 'nav-icon fa-regular fa-circle',
                        ],
                        [
                            'text' => 'Painel de Produção',//Painel de monitor de acompanhamento de produção da oficina diario
                            'url'  => '/lancamentos/producao/homePainelProducao',
                            'icon' => 'nav-icon fa-regular fa-circle',
                        ],
                        [
                            'text' => 'Painel de Agendamento',//Painel de agendamento de TMO
                            'url'  => '/lancamentos/producao/homeAgendamentoPrestador',
                            'icon' => 'nav-icon fa-regular fa-circle',
                        ],
                    ],
                ],
            ],
        ],

        [
            'header' => 'Área de Faturamento',
            'can'  => 'is_emite_nf',
        ],

        [
            'text' => 'Faturamento de Notas',
            'icon' => 'nav-icon fa-solid fa-file-invoice-dollar',
            'can'  => 'is_emite_nf',
            'submenu' => [
                [
                    'text' => 'Emissão Simplificada',
                    'icon' => 'nav-icon fa-solid fa-list',
                    'can'  => 'is_mod_nfs_simp',
                    'submenu' => [
                        [
                            'text' => 'Emissão de NFS-e',
                            'url'  => '/faturamento/notas/simplificada/homeEmissaoSimplificadaNFS',
                            'icon' => 'nav-icon fa-regular fa-circle',
                        ],
                        [
                            'text' => 'Reemissão de NFS-e',
                            'url'  => 'faturamento/notas/simplificada/controleReemissaoSimpNF',
                            'icon' => 'nav-icon fa-regular fa-circle',
                        ],
                    ],
                ],
                [
                    'text' => 'Lançamento de Notas',
                    'icon' => 'nav-icon fa-solid fa-list',
                    'can'  => 'is_mod_nfs',
                    'submenu' => [
                        [
                            'text' => 'Emissão de NFS-e',
                            'url'  => 'faturamento/notas/controleEmissaoNF',
                            'icon' => 'nav-icon fa-regular fa-circle',
                        ],
                        [
                            'text' => 'Reemissão de NFS-e',
                            'url'  => 'faturamento/notas/controleReemissaoNF',
                            'icon' => 'nav-icon fa-regular fa-circle',
                        ],
                    ],
                ],
                /*
                [
                    'text' => 'Eventos de NF-e/NFS-e',
                    'icon' => 'nav-icon fa-solid fa-list',
                    'submenu' => [
                        [
                            'text' => 'Cancelamento de NF-e/NFS-e',
                            'url'  => '',
                            'icon' => 'nav-icon fa-regular fa-circle',
                        ],
                        [
                            'text' => 'Inutilização de NF-e/NFS-e',
                            'url'  => '',
                            'icon' => 'nav-icon fa-regular fa-circle',
                        ],
                        [
                            'text' => 'Carta de Correção',
                            'url'  => '',
                            'icon' => 'nav-icon fa-regular fa-circle',
                        ],
                    ],
                ],
                */
            ],
        ],

        /*
        [
            'text' => 'profile',
            'url'  => 'admin/settings',
            'icon' => 'nav-icon fas fa-fw fa-user',
        ],
        [
            'text' => 'change_password',
            'url'  => 'admin/settings',
            'icon' => 'nav-icon fas fa-fw fa-lock',
        ],
        [
            'text'    => 'multilevel',
            'icon'    => 'fas fa-fw fa-share',
            'submenu' => [
                [
                    'text' => 'level_one',
                    'url'  => '#',
                ],
                [
                    'text'    => 'level_one',
                    'url'     => '#',
                    'submenu' => [
                        [
                            'text' => 'level_two',
                            'url'  => '#',
                        ],
                        [
                            'text'    => 'level_two',
                            'url'     => '#',
                            'submenu' => [
                                [
                                    'text' => 'level_three',
                                    'url'  => '#',
                                ],
                                [
                                    'text' => 'level_three',
                                    'url'  => '#',
                                ],
                            ],
                        ],
                    ],
                ],
                [
                    'text' => 'level_one',
                    'url'  => '#',
                ],
            ],
        ],
        ['header' => 'labels'],
        [
            'text'       => 'important',
            'icon_color' => 'red',
            'url'        => '#',
        ],
        [
            'text'       => 'warning',
            'icon_color' => 'yellow',
            'url'        => '#',
        ],
        [
            'text'       => 'information',
            'icon_color' => 'cyan',
            'url'        => '#',
        ],
        */
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
        'Datatables' => [
            'active' => true,
            'files' => [
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
                ],
            ],
        ],
        'DatatablesPlugins' => [
            'active' => true,
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
