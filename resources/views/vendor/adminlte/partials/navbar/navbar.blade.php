<nav class="main-header navbar
    {{ config('adminlte.classes_topnav_nav', 'navbar-expand') }}
    {{ config('adminlte.classes_topnav', 'navbar-white navbar-light') }}">

    {{-- Navbar left links --}}
    <ul class="navbar-nav">
        {{-- Left sidebar toggler link --}}
        @include('adminlte::partials.navbar.menu-item-left-sidebar-toggler')

        {{-- Configured left links --}}
        @each('adminlte::partials.navbar.menu-item', $adminlte->menu('navbar-left'), 'item')

        <!-- Se voltar com a ideia do campo select no navbar é aqui que monta
        <li class="nav-item">
            <form class="mt-1">
                <div class="form-group" style="margin-bottom: 0 !important;">
                    <div class="input-group input-group-sm">
                        <select id="empresa" name="empresa" class="form-control mb-0">
                            <option value="E00001">E00001 - FusionTech Matriz </option>
                            <option value="E00001">E00001 - FusionTech Loja 2 </option>
                        </select>
                    </div>
                </div>
            </form>
        </li>
        -->

        {{-- Custom left links --}}
        @yield('content_top_nav_left')
    </ul>

    {{-- Navbar right links --}}
    <ul class="navbar-nav ml-auto">
        {{-- Custom right links --}}
        @yield('content_top_nav_right')

        {{-- Configured right links --}}
        @each('adminlte::partials.navbar.menu-item', $adminlte->menu('navbar-right'), 'item')

        <!-- Aqui Removemos a opção de Logout original do projeto e caso voltar a usar remover comentátio -->
        {{-- User menu link --}}
        {{--
        @if(Auth::user())
            @if(config('adminlte.usermenu_enabled'))
                @include('adminlte::partials.navbar.menu-item-dropdown-user-menu')
            @else
                @include('adminlte::partials.navbar.menu-item-logout-link')
            @endif
        @endif
        --}}

        {{-- Right sidebar toggler link --}}
        @if(config('adminlte.right_sidebar'))
            @include('adminlte::partials.navbar.menu-item-right-sidebar-toggler')
        @endif
    </ul>

</nav>
