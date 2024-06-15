<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <!-- Option 1: CoreUI for Bootstrap CSS -->
    {{--    <link href="https://cdn.jsdelivr.net/npm/@coreui/coreui@5.0.0/dist/css/coreui.min.css" rel="stylesheet" integrity="sha384-KGkYqG3gD435LMZAC/8byquZiD5665LheNozmHAqp8vrOKBaBL/bFUO5Er5tMRNi" crossorigin="anonymous">--}}
    {{--    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />--}}
    <!-- Option 2: CoreUI PRO for Bootstrap CSS -->
    <link rel="stylesheet" href="/css/coreuicss.css">
    <link rel="stylesheet" href="/css/simplebar.css">
    <link rel="stylesheet" href="/css/simplebar2.css">
    <title>Gestión de Partes IES SS</title>
    <link rel="shortcut icon" type="image/png" href="{{ asset('/img/LogotipoApp.png') }}">
    <link rel="shortcut icon" sizes="192x192" href="{{ asset('/img/LogotipoApp.png') }}">

    <script src="/js/simplebar2.js"></script>
    <script src="/js/coreui.js"></script>
    <script src="/js/color-mode.js"></script>
    {{--    <script src="https://cdn.jsdelivr.net/npm/@coreui/icons@3.0.1/dist/cjs/index.min.js"></script>--}}
    {{--    <link href="https://cdn.jsdelivr.net/npm/@coreui/icons@3.0.1/css/all.min.css" rel="stylesheet">--}}
    <link href="{{asset('css/style.css')}}" rel="stylesheet">


    {{--    <!-- Latest compiled and minified CSS -->--}}
    {{--    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">--}}

    {{--    <!-- Latest compiled and minified JavaScript -->--}}
    {{--    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>--}}

    {{--    <!-- (Optional) Latest compiled and minified JavaScript translation files -->--}}
    {{--    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/i18n/defaults-*.min.js"></script>--}}


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.full.min.js"></script>
    
</head>
<body>

<div class="min-vh-100 d-flex flex-row align-items-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-10 col-lg-8">
                <div class="card-group d-block d-md-flex row">
                    <div class="card col-md-7 p-4 mb-0">
                        <div class="card-body">

                            @yield('content')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{--    <div class="body flex-grow-1">--}}
{{--        <div class="container-lg px-4">--}}
{{--        @yield('content')--}}
{{--          </div>--}}
{{--    </div>--}}


<!-- Optional JavaScript; choose one of the two! -->

<!-- Option 1: CoreUI for Bootstrap Bundle with Popper -->
<!-- <script src="https://cdn.jsdelivr.net/npm/@coreui/coreui@5.0.0/dist/js/coreui.bundle.min.js" integrity="sha384-Wl6mLWl+C0OgKzQqXtoEXuTkm2RwXManFDTNyMRxPR5zh3DFIUIDhq7Fp1JCFm1V" crossorigin="anonymous"></script> -->

<!-- Option 2: CoreUI PRO for Bootstrap Bundle with Popper -->

@stack('scripts')
<!-- Option 3: Separate Popper and CoreUI/CoreUI PRO  for Bootstrap JS -->
<!--
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@coreui/coreui@5.0.0/dist/js/coreui.min.js" integrity="sha384-0pR6+CQ3+YSoPvs/vb4AgvfnbU03AdP4/HK6hH/8RxK3DI7/8zuRD/8R9ERU3p31" crossorigin="anonymous"></script>
or
<script src="https://cdn.jsdelivr.net/npm/@coreui/coreui-pro@5.0.0/dist/js/coreui.min.js" integrity="sha384-P55oEtbpJcbKNSX0lgAPDYscDoCN5JqAnSPTimkcSTygCzQ6W8l60nRD09Vzfzx6" crossorigin="anonymous"></script>
-->

</body>
</html>


{{--<!doctype html>--}}
{{--<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">--}}
{{--<head>--}}
{{--    <meta charset="utf-8">--}}
{{--    <meta name="viewport" content="width=device-width, initial-scale=1">--}}

{{--    <!-- CSRF Token -->--}}
{{--    <meta name="csrf-token" content="{{ csrf_token() }}">--}}
        

{{--    <!-- Fonts -->--}}

{{--    <link rel="stylesheet" href="{{asset('css/custom2.css')}}">--}}


{{--    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />--}}


<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
{{--<script src="js/multiselect.js"></script>--}}

{{--    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>--}}
{{--    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.full.min.js"></script>--}}
{{--    <script src="{{asset('js/es.js')}}"></script>--}}

{{--    <!--google fonts -->--}}


{{--    <!-- Scripts -->--}}
{{--    @vite(['resources/sass/app.scss', 'resources/js/app.js'])--}}
{{--</head>--}}
{{--<body>--}}

{{--<div id="app">--}}
{{--    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">--}}
{{--        <div class="container">--}}
{{--            <a class="navbar-brand" href="{{ url('/') }}">--}}
{{--                {{ config('app.name', 'Laravel') }}--}}
{{--            </a>--}}
{{--            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">--}}
{{--                <span class="navbar-toggler-icon"></span>--}}
{{--            </button>--}}

{{--            <div class="collapse navbar-collapse" id="navbarSupportedContent">--}}
{{--                <!-- Left Side Of Navbar -->--}}
{{--                <ul class="navbar-nav me-auto">--}}

{{--                </ul>--}}

{{--                <!-- Right Side Of Navbar -->--}}
{{--                <ul class="navbar-nav ms-auto">--}}
{{--                    <!-- Authentication Links -->--}}
{{--                    @guest--}}
{{--                        @if (Route::has('login'))--}}
{{--                            <li class="nav-item">--}}
{{--                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>--}}
{{--                            </li>--}}
{{--                        @endif--}}

{{--                        @if (Route::has('register'))--}}
{{--                            <li class="nav-item">--}}
{{--                                <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>--}}
{{--                            </li>--}}
{{--                        @endif--}}
{{--                    @else--}}
{{--                        <li class="nav-item dropdown">--}}
{{--                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>--}}
{{--                                {{ Auth::user()->name }}--}}
{{--                            </a>--}}

{{--                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">--}}
{{--                                <a class="dropdown-item" href="{{ route('logout') }}"--}}
{{--                                   onclick="event.preventDefault();--}}
{{--                                                     document.getElementById('logout-form').submit();">--}}
{{--                                    {{ __('Logout') }}--}}
{{--                                </a>--}}

{{--                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">--}}
{{--                                    @csrf--}}
{{--                                </form>--}}
{{--                            </div>--}}
{{--                        </li>--}}
{{--                    @endguest--}}
{{--                </ul>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </nav>--}}

{{--    <main class="py-4">--}}
{{--        @yield('content')--}}
{{--    </main>--}}
{{--</div>--}}
{{--@stack('scripts')--}}


{{--</body>--}}
{{--</html>--}}
