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

    'title' => 'AdminLTE 3',
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

    'logo' => '<b>Admin</b>LTE',
    'logo_img' => 'vendor/adminlte/dist/img/AdminLTELogo.png',
    'logo_img_class' => 'brand-image img-circle elevation-3',
    'logo_img_xl' => null,
    'logo_img_xl_class' => 'brand-image-xs',
    'logo_img_alt' => 'Admin Logo',

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
        'enabled' => false,
        'img' => [
            'path' => 'vendor/adminlte/dist/img/AdminLTELogo.png',
            'alt' => 'Auth Logo',
            'class' => '',
            'width' => 50,
            'height' => 50,
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
            'path' => 'vendor/adminlte/dist/img/AdminLTELogo.png',
            'alt' => 'AdminLTE Preloader Image',
            'effect' => 'animation__shake',
            'width' => 60,
            'height' => 60,
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
    'usermenu_header' => false,
    'usermenu_header_class' => 'bg-primary',
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
    'layout_fixed_sidebar' => null,
    'layout_fixed_navbar' => null,
    'layout_fixed_footer' => null,
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

    'classes_auth_card' => 'card-outline card-primary',
    'classes_auth_header' => '',
    'classes_auth_body' => '',
    'classes_auth_footer' => '',
    'classes_auth_icon' => '',
    'classes_auth_btn' => 'btn-flat btn-primary',

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
    'classes_brand_text' => '',
    'classes_content_wrapper' => '',
    'classes_content_header' => '',
    'classes_content' => '',
    'classes_sidebar' => 'sidebar-dark-primary elevation-4',
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
    'sidebar_collapse_remember' => false,
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
            'type'         => 'navbar-search',
            'text'         => 'search',
            'topnav_right' => true,
        ],
        [
            'type'         => 'fullscreen-widget',
            'topnav_right' => true,
        ],
        [
            'type'           => 'darkmode-widget',
            'topnav_right'   => true, // Or "topnav => true" to place on the left.
             'icon_enabled'   => 'fas fa-moon',
             'icon_disabled'  => 'fas fa-sun',
             'color_enabled'  => 'grey',
             'color_disabled' => 'yellow'
        ],

        // Sidebar items:
        [
            'type' => 'sidebar-menu-search',
            'text' => 'search',
        ],
        [
            'text' => 'blog',
            'url'  => 'admin/blog',
            'can'  => 'manage-blog',
        ],
        [
            'text' => 'Página Inicial',
            'url'  => 'homePrincipal',
            'icon' => 'fas fa-home',
        ],

        /*[
            'text'        => 'pages',
            'url'         => 'admin/pages',
            'icon'        => 'far fa-fw fa-file',
            'label'       => 4,
            'label_color' => 'success',
        ],*/

        [
            'header' => 'Área de Parâmetros',
            'can' => 'is_parameter'
        ],

        [
            'text' => 'Parâmetros do Sistema',
            'icon' => 'fa-solid fa-gears',
            'can'  => 'is_master',
            'submenu' => [
                [
                    'text' => 'Módulos do Sistema',
                    'url'  => '/parametros/sistema/homeParametrosSistemaModulos',
                    'icon' => '',
                ],
                [
                    'text' => 'Áreas',
                    'url'  => '/parametros/sistema/homeParametrosSistemaAreas',
                    'icon' => '',
                ],
                [
                    'text' => 'Serviços e Grupos da NFS-e',
                    'url'  => '/parametros/sistema/homeParametrosSistemaServicos',
                    'icon' => '',
                ],
            ],
        ],

        [
            'text' => 'Parâmetros Gerais',
            'icon' => 'fa-solid fa-gear',
            'can'  => 'is_parameter',
            'submenu' => [
                [
                    'text' => 'Faturamento',
                    'icon' => 'fa-solid fa-list',
                    'submenu' => [
                        /*[
                            'text' => 'Emissão de NF-e',
                            'url'  => '',
                            'icon' => '',
                        ],*/
                        [
                            'text' => 'Emissão de NFS-e',
                            'url'  => '/parametros/faturamento/nfs/homeParametroFatNfs',
                            'icon' => '',
                        ],
                        [
                            'text' => 'Parâmetros Empresas',
                            'url'  => '',
                            'icon' => '',
                        ],
                    ],
                ],
                [
                    'text' => 'Lançamento de Serviços',
                    'icon' => 'fa-solid fa-list',
                    'submenu' => [
                        [
                            'text' => 'Categorias de Atendimento',
                            'url'  => '/parametros/servico/homeLancamentosServicoCategoria',
                            'icon' => '',
                        ],
                        [
                            'text' => 'Etapas de Atendimento',
                            'url'  => '/parametros/servico/homeLancamentosServicoEtapas',
                            'icon' => '',
                        ],
                        [
                            'text' => 'Setor',
                            'url'  => '/parametros/servico/homeParametrosServicoSetor',
                            'icon' => '',
                        ],
                        [
                            'text' => 'Tarefas Mão de Obra',
                            'url'  => '/parametros/servico/homeParametrosServicoTMO',
                            'icon' => '',
                        ],
                        [
                            'text' => 'Tipos de Serviço',
                            'url'  => '/parametros/servico/homeLancamentosServicoTipo',
                            'icon' => '',
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
            'icon' => 'fa-solid fa-file-circle-plus',
            'can'  => 'is_register',
            'submenu' => [
                [
                    'text' => 'Empresa',
                    'url'  => '/cadastros/empresa/homeEmpresa',
                    'icon' => '',
                ],
                [
                    'text' => 'Usuario',
                    'url'  => '/cadastros/usuario/homeUsuarios',
                    'icon' => '',
                ],
                [
                    'text' => 'Prestadores',
                    'url'  => '/cadastros/prestador/homePrestadores',
                    'icon' => '',
                ],
                [
                    'text' => 'Cliente',
                    'url'  => 'cadastros/cliente/homeClientes',
                    'icon' => '',
                ],
                /*[
                    'text' => 'Banco',
                    'url'  => 'bancos',
                    'icon' => 'fa-solid fa-building-columns',
                ],
                [
                    'text' => 'Produto',
                    'url'  => 'products',
                    'icon' => 'fa-solid fa-cart-plus',
                ],*/
            ],
        ],

        ['header' => 'Área de Lançamentos'],

        [
            'text' => 'Lançamentos',
            'icon' => 'fa-solid fa-file-pen',
            'submenu' => [
                [
                    'text' => 'Serviços',
                    'icon' => 'fa-solid fa-list',
                    'submenu' => [
                        [
                            'text' => 'Emissão de OS',
                            'url'  => 'lancamentos/servico/homeEmissaoOS',
                            'icon' => '',
                        ],
                        [
                            'text' => 'Situação de OS',
                            'url'  => '/lancamentos/servico/homeSituacaoOS',
                            'icon' => '',
                        ],
                    ],
                ],
            ],
        ],

        ['header' => 'Área de Faturamento'],

        [
            'text' => 'Faturamento de Notas',
            'icon' => 'fa-solid fa-file-invoice-dollar',
            'submenu' => [
                [
                    'text' => 'Lançamento de Notas',
                    'icon' => 'fa-solid fa-list',
                    'submenu' => [
                        [
                            'text' => 'Consulta Geral',
                            'url'  => 'fiscal',
                            'icon' => '',
                        ],
                        [
                            'text' => 'Emissão de NF-e/NFS-e',
                            'url'  => 'faturamento/notas/controleEmissaoNF',
                            'icon' => '',
                        ],
                        [
                            'text' => 'Reemissão de NF-e/NFS-e',
                            'url'  => 'faturamento/notas/controleReemissaoNF',
                            'icon' => '',
                        ],
                    ],
                ],
                [
                    'text' => 'Eventos de NF-e/NFS-e',
                    'icon' => 'fa-solid fa-list',
                    'submenu' => [
                        [
                            'text' => 'Cancelamento de NF-e/NFS-e',
                            'url'  => '',
                            'icon' => '',
                        ],
                        [
                            'text' => 'Inutilização de NF-e/NFS-e',
                            'url'  => '',
                            'icon' => '',
                        ],
                        [
                            'text' => 'Carta de Correção',
                            'url'  => '',
                            'icon' => '',
                        ],
                    ],
                ],
            ],
        ],

        /*['header' => 'Sistema Financeiro'],

        [
            'text'    => 'Financeiro',
            'icon'    => 'fa-solid fa-hand-holding-dollar',
            'submenu' => [
                [
                    'text' => 'Bancos',
                    'url'  => 'bancos',
                    'icon' => 'fa-solid fa-vault',
                ],
                [
                    'text' => 'Contas a Pagar',
                    'url'  => 'pagar',
                    'icon' => 'fa-solid fa-money-bill-trend-up fa-flip-vertical',
                ],
                [
                    'text' => 'Contas a Receber',
                    'url'  => 'receber',
                    'icon' => 'fa-solid fa-money-bill-trend-up',
                ],
            ],
        ],*/

        /*
        [
            'text' => 'profile',
            'url'  => 'admin/settings',
            'icon' => 'fas fa-fw fa-user',
        ],
        [
            'text' => 'change_password',
            'url'  => 'admin/settings',
            'icon' => 'fas fa-fw fa-lock',
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
