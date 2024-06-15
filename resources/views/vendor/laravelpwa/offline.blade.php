@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header ">Sin conexión a Internet</div>
                    <div class="card-body">
                        <p>Por favor, verifica tu conexión y vuelve a intentarlo.</p>
                        <a href="{{ route('home') }}" class="btn btn-primary">Regresar al inicio</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
