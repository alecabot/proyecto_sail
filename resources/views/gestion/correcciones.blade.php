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
                        <div class="card-header">Gestión de Correcciones</div>
                        <div class="card-body">

                                <h2 class="mt-3 mb-3">Añadir nueva Corrección</h2>

                                <form class="row" method="post" action="{{route('gestion.correcciones.crear')}}">
                                    @csrf
                                    <div class="col-auto w-75">
                                        <label for="nuevaCorreccion">Descripción de la Corrección a añadir:</label>
                                        <input type="text" class="form-control mt-2" id="nuevaCorreccion" name="nuevaCorreccion" placeholder="Descripción corrección">
                                    </div>
                                    <div class="col-auto align-self-end botones-crear">
                                        <button type="submit" class="btn btn-secondary" id="generate">Añadir</button>
                                        <button type="reset" class="btn btn-danger text-white" id="reset">Limpiar</button>
                                    </div>
                                </form>

                            <br>

                            <h2 class="mt-2 mb-4">Listado de Correcciones</h2>
                            <div class="table-responsive-md table-div">
                                <table class="table table-hover table-striped table-bordered" id="tabla-correcciones">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th scope="col" style="width: 8%" class="text-center">#</th>
                                            <th scope="col" class="text-center">Descripción</th>
                                            <th scope="col" style="width: 17%" class="text-center">Opciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($correcciones as $correccion)
                                    <form class="row" method="patch" action="/gestion/correccionesaplicadas/editar/{{$correccion->id}}">
                                        @csrf
                                        <tr class="align-middle">
                                            <th class="text-center">{{$correccion->id}}</th>
                                            <td><input type="text" class="form-control" id="cambioCorreccion" name="cambioCorreccion" value="{{$correccion->descripcion}}"></td>
                                            <td class="text-center">
                                            <button type="submit" class="btn btn-primary" id="generate">Editar</button>
                                    </form>
                                    <a class="btn btn-warning text-dark segundo-boton" href="/gestion/correccionesaplicadas/habilitar/{{$correccion->id}}"">Deshabilitar</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="h-10 grid grid-cols-1 gap-4 content-between">
                                {{ $correcciones->links('vendor.pagination.bootstrap-5') }}
                            </div>
                            <div class="mt-4 mb-3">
                                <a href="{{route('gestion.correcciones.deshabilitadas')}}" class="btn btn-warning text-dark">Ver deshabilitadas</a>
                            </div>


                        </div>
                    </div>
    </div>


@endsection
@push('scripts')

    <script type="text/javascript">

    </script>

    <script type="module">




    </script>

    <!-- Bootstrap Select CSS -->


@endpush


