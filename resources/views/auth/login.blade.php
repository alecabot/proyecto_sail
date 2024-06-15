@extends('layouts.auth')

@section('content')
    <div class="row justify-content: center;">

        <div class="col-5 col-md-3 text-center text-xs-start d-none d-xs-block d-md-none">
            <img src="{{ asset('/img/LogoSanSebastian.png') }}" height="60%" style="margin-top: 20px;"/>
        </div>
        <div class="col-12 col-xs-5 col-md-3 text-center text-xs-start">
            <img src="{{ asset('/img/LogotipoApp.png') }}" width="130" height="130" />
        </div>
        <div class="col-12 col-md-6 text-center">
            <h1>Sistema de Gestión de Partes</h1>
            <h2>IES San Sebastián</h2>
        </div>
        <div class="col-12 col-md-3 d-none d-md-block text-md-end">
            <img src="{{ asset('/img/LogoSanSebastian.png') }}" height="60%" style="margin-top: 20px;" id="fotoLogin"/>
        </div>

    </div>
    <h1 class="mb-4 mt-md-4 mt-4">Login de usuario</h1>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="input-group mb-3"><span class="input-group-text">
                                <svg class="icon">
                        <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-user"></use>
                      </svg></span>


            <input id="email" type="text" class="form-control @error('email') is-invalid @enderror" name="email"
                   value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Usuario">

            @error('email')
            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
            @enderror

        </div>


        <div class="input-group mb-3">
        <span class="input-group-text">
                      <svg class="icon">
                        <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-lock-locked"></use>
                      </svg></span>

            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                   name="password" required autocomplete="current-password" placeholder="Contraseña">

            @error('password')
            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
            @enderror

        </div>


        <div class="row mb-3">
            <div class="col-md-6">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember"
                           id="remember" {{ old('remember') ? 'checked' : '' }}>

                    <label class="form-check-label" for="remember">
                        {{ __('Recuérdame') }}
                    </label>
                </div>
            </div>
        </div>

        <div class="row">

                <div class="col-12 col-md-6">
                    <button class="btn btn-primary px-4" type="submit">{{ __('Login') }}</button>
                </div>

            <div class="col-6 text-end d-none">
                @if (Route::has('password.request'))
                    <a class="btn btn-link" href="{{ route('password.request') }}">
                        {{ __('¿Has olvidado la contraseña?') }}
                    </a>
                @endif
            </div>

        </div>
    </form>























@endsection
