@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-general">
                <div class="card-header">Importar</div>

                <div class="card-body">
                    @if (isset($errors) && $errors->any())
                        <div class="alert alert-danger" role="alert">
                            @foreach ($errors->all() as $error)
                                {{$error}}
                            @endforeach
                        </div>
                    @endif

                    <form action="{{route('users.import')}}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <input type="file" name="import_file" />

                        <button class="btn btn-primary" type="submit">Importar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
