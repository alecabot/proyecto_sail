<!doctype html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="manifest" crossorigin="use-credentials" href="{{ route('laravelpwa.manifest') }}" />
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <link rel="stylesheet" href="{{asset('css/coreuicss.css')}}">
    <link rel="stylesheet" href="{{asset('css/simplebar.css')}}">
    <link rel="stylesheet" href="{{asset('css/simplebar2.css')}}">

    <script src="{{asset('js/simplebar2.js')}}"></script>
    <script src="{{asset('js/coreui.js')}}"></script>
    <script src="{{asset('js/color-mode.js')}}"></script>
    <script src="{{asset('js/ckeditor.js')}}"></script>


    <link href="{{asset('css/style.css')}}" rel="stylesheet">

    <link
        href="https://fonts.googleapis.com/css2?family=Material+Icons"
        rel="stylesheet"
    />

    <script src="{{asset('js/jquery.js')}}"></script>

    <link rel="stylesheet" href="{{asset('js/bootstrap-select.css')}}">

    <script defer src="{{asset('js/bootstrap-select.js')}}"></script>

    <script defer src="{{asset('js/bootstrap-select@es_ES.js')}}"></script>

    <title>Gestión de Partes IES SS</title>
    <link rel="shortcut icon" type="image/png" href="{{ asset('/img/LogotipoApp.png') }}">
    <link rel="shortcut icon" sizes="192x192" href="{{ asset('/img/LogotipoApp.png') }}">
    @laravelPWA
</head>
<body>
    <div style="display: none !important;">
        <a href="#contenido" aria-label="Saltar al contenido">Saltar al contenido</a>
    </div>
<div class="sidebar sidebar-fixed border-end" id="sidebar">
    <div class="sidebar-header border-bottom">
        <div class="sidebar-brand">
            <img class="sidebar-brand-full" width="110" height="32" alt="CoreUI Logo" src="{{asset('img/logo1.png')}}">
            <img class="sidebar-brand-narrow" width="32" height="32" alt="CoreUI Logo" src="{{asset('img/logo2.png')}}">
        </div>
        <button class="btn-close d-lg-none" type="button" aria-label="Close"
                onclick="coreui.Sidebar.getInstance(document.querySelector(&quot;#sidebar&quot;)).toggle()"></button>
    </div>
    <ul class="sidebar-nav">
        <li class="nav-title" data-coreui-i18n="theme">Alumnado</li>
        <li class="nav-item"><a class="nav-link {{ request()->routeIs('users.index') ? 'active' : '' }}" href="{{route('users.index')}}">
                <svg class="nav-icon">
                    <use xlink:href="{{asset('/vendors/@coreui/icons/svg/free.svg#cil-spreadsheet')}}"></use>
                </svg>
                <span data-coreui-i18n="dashboard">Partes</span></a></li>



        <li class="nav-item"><a class="nav-link {{ request()->routeIs('parte.resumen') ? 'active' : '' }}" href="{{route('parte.resumen')}}">
                <svg class="nav-icon">
                    <use xlink:href="{{asset('/vendors/@coreui/icons/svg/free.svg#cil-balance-scale')}}"></use>
                </svg>
                <span data-coreui-i18n="dashboard">Resumen Incidencias</span></a></li>
        <li class="nav-item"><a class="nav-link {{ request()->routeIs('parte.informe') ? 'active' : '' }}" href="{{route('parte.informe')}}">
                <svg class="nav-icon">
                    <use xlink:href="{{asset('/vendors/@coreui/icons/svg/free.svg#cil-file')}}"></use>
                </svg>
                <span data-coreui-i18n="dashboard">Informe Incidencias</span></a></li>
        <!-- Gestión para jefatura -->
        @role('jefatura')
            <li class="nav-title" data-coreui-i18n="theme">Gestión</li>
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('gestion.incidencias') ? 'active' : '' }}" href="{{route('gestion.incidencias')}}">
                    <svg class="nav-icon">
                        <use xlink:href="{{asset('/vendors/@coreui/icons/svg/free.svg#cil-sad')}}"></use>
                    </svg>
                    <span data-coreui-i18n="dashboard">Incidencias</span></a></li>
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('gestion.negativas') ? 'active' : '' }}" href="{{route('gestion.negativas')}}">
                    <svg class="nav-icon">
                        <use xlink:href="{{asset('/vendors/@coreui/icons/svg/free.svg#cil-book')}}"></use>
                    </svg>
                    <span data-coreui-i18n="dashboard">Conductas Negativas</span></a></li>
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('gestion.correcciones') ? 'active' : '' }}" href="{{route('gestion.correcciones')}}">
                    <svg class="nav-icon">
                        <use xlink:href="{{asset('/vendors/@coreui/icons/svg/free.svg#cil-pin')}}"></use>
                    </svg>
                    <span data-coreui-i18n="dashboard">Correcciones</span></a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('gestion.profesoralumno') ? 'active' : '' }}" href="{{route('gestion.profesoralumno')}}">
                    <svg class="nav-icon">
                        <use xlink:href="{{asset('/vendors/@coreui/icons/svg/free.svg#cil-school')}}"></use>
                    </svg>
                    <span data-coreui-i18n="dashboard">Alumnos / Profesores</span></a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('gestion.tutores') ? 'active' : '' }}" href="{{route('gestion.tutores')}}">
                    <svg class="nav-icon">
                        <use xlink:href="{{asset('/vendors/@coreui/icons/svg/free.svg#cil-user')}}"></use>
                    </svg>
                    <span data-coreui-i18n="dashboard">Tutores</span></a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('gestion.puntos') ? 'active' : '' }}" href="{{route('gestion.puntos')}}">
                    <svg class="nav-icon">
                        <use xlink:href="{{asset('/vendors/@coreui/icons/svg/free.svg#cil-bell-exclamation')}}"></use>
                    </svg>
                    <span data-coreui-i18n="dashboard">Reiniciar puntos</span></a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('users.import') ? 'active' : '' }}" href="{{route('users.import')}}">
                            <svg class="nav-icon">
                                <use xlink:href="{{asset('/vendors/@coreui/icons/svg/free.svg#cil-bookmark')}}"></use>
                            </svg>
                            <span data-coreui-i18n="dashboard">Importaciones</span></a></li>
        @endrole
    </ul>
    <div class="sidebar-footer border-top d-none d-md-flex">
        <button class="sidebar-toggler" type="button" data-coreui-toggle="unfoldable"></button>
    </div>
</div>



<div class="wrapper d-flex flex-column min-vh-100">
    <header class="header header-sticky p-0 mb-4">
        <div class="container-fluid px-4 border-bottom">
            <button id="buttone" class="header-toggler" type="button"
                    onclick="coreui.Sidebar.getInstance(document.querySelector('#sidebar')).toggle()"
                    style="margin-inline-start: -14px">
                <svg class="icon icon-lg">
                    <use xlink:href="{{asset('vendors/@coreui/icons/svg/free.svg#cil-menu')}} "></use>
                </svg>
            </button>
            <img width="52" height="52" alt="Logotipo App" src="{{asset('img/LogotipoApp.png')}}">
            @role('jefatura')
            <h2 class="h2 mb-0 ms-3">Jefatura</h2>
            @elserole('profesor')
            <h2 class="h2 mb-0 ms-3">Profesorado</h2>
            @endrole
            <ul class="header-nav d-none d-md-flex ms-auto">

            </ul>
            <ul class="header-nav ms-auto ms-md-0">


                <li class="nav-item dropdown">
                    <button class="btn btn-link nav-link" type="button" aria-expanded="false"
                            data-coreui-toggle="dropdown">
                        <svg class="icon icon-lg theme-icon-active">
                            <use xlink:href="{{asset('vendors/@coreui/icons/svg/free.svg#cil-contrast')}} "></use>
                        </svg>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" style="--cui-dropdown-min-width: 8rem;">
                        <li>
                            <button class="dropdown-item d-flex align-items-center" type="button"
                                    data-coreui-theme-value="light">
                                <svg class="icon icon-lg me-3">
                                    <use xlink:href="{{asset('vendors/@coreui/icons/svg/free.svg#cil-sun')}}"></use>
                                </svg>
                                <span data-coreui-i18n="light">Claro</span>
                            </button>
                        </li>
                        <li>
                            <button class="dropdown-item d-flex align-items-center" type="button"
                                    data-coreui-theme-value="dark">
                                <svg class="icon icon-lg me-3">
                                    <use xlink:href="{{asset('vendors/@coreui/icons/svg/free.svg#cil-moon')}}"></use>
                                </svg>
                                <span data-coreui-i18n="dark">Oscuro</span>
                            </button>
                        </li>
                        <li>
                            <button class="dropdown-item d-flex align-items-center active" type="button"
                                    data-coreui-theme-value="auto">
                                <svg class="icon icon-lg me-3">
                                    <use xlink:href="{{asset('vendors/@coreui/icons/svg/free.svg#cil-contrast')}} "></use>
                                </svg>
                                Automatico
                            </button>
                        </li>
                    </ul>
                </li>
                <li class="nav-item py-1">
                    <div class="vr h-100 mx-2 text-body text-opacity-75"></div>
                </li>
                <li class="nav-item dropdown"><a class="nav-link py-0" data-coreui-toggle="dropdown" href="#"
                                                 role="button" aria-haspopup="true" aria-expanded="false">
                        <div class="avatar avatar-md"><img class="avatar-img" src="{{asset('img/user.png')}}"
                                                           alt="user@email.com"></div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end pt-0">
                        <div class="dropdown-header bg-body-tertiary text-body-secondary fw-semibold rounded-top mb-2"
                             data-coreui-i18n="account">Cuenta
                        </div>
                        <a class="dropdown-item" href="{{ route('logout') }}"  onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <svg class="icon me-2">
                                <use xlink:href="{{asset('vendors/@coreui/icons/svg/free.svg#cil-account-logout')}}"></use>
                            </svg>
                            <span data-coreui-i18n="logout">Cerrar Session</span>
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </li>
            </ul>
        </div>
    </header>
    <div class="body flex-grow-1">
        <div class="container-lg px-4" id="contenido">
            @yield('content')
        </div>
    </div>
</div>



@stack('scripts')
<script type="text/javascript">
    $(document).ready(function() {
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.attributeName === 'data-coreui-theme') {
                    if ($('html').attr('data-coreui-theme') === 'dark') {
                        $('.sidebar-brand-full').attr('src', '{{asset("img/logo_dark.png")}}');
                    } else {
                        $('.sidebar-brand-full').attr('src', '{{asset("img/logo1.png")}}');
                    }
                }
            });
        });

        observer.observe(document.documentElement, { attributes: true });

        if ($('html').attr('data-coreui-theme') === 'dark') {
            $('.sidebar-brand-full').attr('src', '{{asset("img/logo_dark.png")}}');
        } else {
            $('.sidebar-brand-full').attr('src', '{{asset("img/logo1.png")}}');
        }
    });
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $('.bootstrap-select').each(function () {
            $(this).find('button:first').removeClass().addClass('form-select text-start').css('padding-left', '9px');
        });

        const observer = new MutationObserver(function (mutations) {
            mutations.forEach(function (mutation) {
                if (mutation.attributeName === 'data-coreui-theme') {
                    if ($('html').attr('data-coreui-theme') === 'dark') {
                        $('.bootstrap-select button:not(:first)').removeClass('btn-light').addClass('btn-dark');
                    } else {
                        $('.bootstrap-select button:not(:first)').removeClass('btn-dark').addClass('btn-light');
                    }
                }
            });
        });

        observer.observe(document.documentElement, {attributes: true});

        if ($('html').attr('data-coreui-theme') === 'dark') {
            $('.bootstrap-select button:not(:first)').removeClass('btn-light').addClass('btn-dark');
        } else {
            $('.bootstrap-select button:not(:first)').removeClass('btn-dark').addClass('btn-light');
        }
    });
    $('#buttone').on('click', function () {

        if ($(window).width() >= 992) {
            $('.dataTables_scrollHeadInner').attr('style', 'width: 100% !important;');
        }

    });




</script>
</body>
</html>





