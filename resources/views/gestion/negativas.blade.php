@extends('layouts.app')

@section('content')
    @if (isset($errors) && $errors->any())
        <div class="alert alert-danger" role="alert">
            @foreach ($errors->all() as $error)
                {{$error}}
            @endforeach
        </div>
    @endif

    <div class="container">

    @if (session('success'))
        <div class="alert alert-success align-middle text-center h3">
                {{ session('success') }}
        </div>
    @endif
                    <div class="card mb-4 card-general">
                        <div class="card-header">Gestión de Conductas Negativas</div>
                        <div class="card-body">

                                <h2 class="mt-3 mb-3">Añadir nueva Conducta Negativa</h2>

                                <form class="row" method="post" action="{{route('gestion.negativas.crear')}}">
                                    @csrf
                                    <div class="col-auto w-75">
                                        <label for="nuevaConducta">Descripción de la conducta negativa a añadir:</label>
                                        <div class="row">
                                            <input type="text" class="form-control mt-2 col-2 col-md" id="nuevaConducta" name="nuevaConducta" placeholder="Descripción conducta negativa" style="margin-right: 2% !important; margin-left: 2% !important;">
                                            <select class="form-select mt-2 col-2 col-md ms-1" id="nuevaConductaTipo" name="nuevaConductaTipo">
                                                <option value="Contraria" selected>Contraria</option>
                                                <option value="Grave">Grave</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-auto align-self-end botones-crear">
                                        <button type="submit" class="btn btn-secondary" id="generate">Añadir</button>
                                        <button type="reset" class="btn btn-danger text-white" id="reset">Limpiar</button>
                                    </div>
                                </form>

                            <br>

                            <h2 class="mt-2 mb-4">Listado de Conductas Negativas</h2>
                            <div class="table-responsive-xl table-div">
                                <table class="table table-hover table-striped table-bordered" id="tabla-conductas">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th scope="col" style="width: 8%" class="text-center">#</th>
                                            <th scope="col" class="text-center">Descripción</th>
                                            <th scope="col" class="text-center" style="width: 12%">Tipo</th>
                                            <th scope="col" style="width: 17%" class="text-center">Opciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($conductas as $conducta)
                                    <form class="row" method="patch" action="/gestion/conductasnegativas/editar/{{$conducta->id}}">
                                        @csrf 
                                        <tr class="align-middle">
                                            <th class="text-center">{{$conducta->id}}</th>
                                            <td><input type="text" class="form-control" id="cambioConducta" name="cambioConducta" value="{{$conducta->descripcion}}"></td>
                                            <td>
                                                <select class="form-select col" id="cambioConductaTipo" name="cambioConductaTipo">
                                                    @if ($conducta->tipo == "Contraria")
                                                        <option value="Contraria" selected>Contraria</option>
                                                        <option value="Grave">Grave</option>
                                                    @else
                                                        <option value="Contraria">Contraria</option>
                                                        <option value="Grave" selected>Grave</option>
                                                    @endif

                                                </select>
                                            </td>
                                            <td class="text-center">
                                            <button type="submit" class="btn btn-primary" id="generate">Editar</button>
                                    </form>
                                            <a class="btn btn-warning text-dark sm-mt-2 segundo-boton-conductas" href="/gestion/conductasnegativas/habilitar/{{$conducta->id}}">Deshabilitar</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="h-10 grid grid-cols-1 gap-4 content-between">
                                {{ $conductas->links('vendor.pagination.bootstrap-5') }}
                            </div>
                            <div class="mt-2 mb-3">
                                <a href="{{route('gestion.negativas.deshabilitadas')}}" class="btn btn-warning text-dark">Ver deshabilitadas</a>
                            </div>


                        </div>
                    </div>
                </div>
@endsection
@push('scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script type="text/javascript">

    </script>

    <script type="module">

        


    </script>

    <!-- Bootstrap Select CSS -->


@endpush


