@extends('layouts.app')

@section('content')
<div class="container">
            <div class="card mb-4 card-general">
                <div class="card-header text-center">{{ __('La sesión se ha iniciado con éxito') }}</div>

                <div class="card-body text-center">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('Bienvenido al Sistema Gestor de Partes del IES San Sebastián') }}
                    <br><br>
                    <img src="{{ asset('/img/LogotipoApp.png') }}" width="30%">
                </div>
            </div>
</div>
@endsection
