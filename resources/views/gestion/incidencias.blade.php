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
                        <div class="card-header">Gestión de Incidencias</div>
                        <div class="card-body">

                                <h2 class="mt-3 mb-3">Añadir nueva Incidencia</h2>

                                <form class="row" method="post" action="{{route('gestion.incidencias.crear')}}">
                                    @csrf
                                    <div class="col-auto w-75">
                                        <label for="nuevaIncidencia">Descripción de la incidencia a añadir:</label>
                                        <input type="text" class="form-control mt-2" id="nuevaIncidencia" name="nuevaIncidencia" placeholder="Descripción incidencia">
                                    </div>
                                    <div class="col-auto align-self-end botones-crear">
                                        <button type="submit" class="btn btn-secondary" id="generate">Añadir</button>
                                        <button type="reset" class="btn btn-danger text-white" id="reset">Limpiar</button>
                                    </div>
                                </form>

                            <br>

                            <h2 class="mt-2 mb-4">Listado de Incidencias</h2>
                            <div class="table-responsive-md table-div">
                                <table class="table table-hover table-striped table-bordered" id="tabla-incidencias">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th scope="col" style="width: 8%" class="text-center">#</th>
                                            <th scope="col" class="text-center">Descripción</th>
                                            <th scope="col" style="width: 17%" class="text-center">Opciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($incidencias as $incidencia)
                                    <form class="row" method="patch" action="/gestion/incidencias/editar/{{$incidencia->id}}">
                                        @csrf 
                                        <tr class="align-middle">
                                            <th class="text-center">{{$incidencia->id}}</th>
                                            <td><input type="text" class="form-control" id="cambioIncidencia" name="cambioIncidencia" value="{{$incidencia->descripcion}}"></td>
                                            <td class="text-center">
                                            <button type="submit" class="btn btn-primary" id="generate">Editar</button>
                                    </form>
                                    <a class="btn btn-warning text-dark sm-mt-2 segundo-boton" href="/gestion/incidencias/habilitar/{{$incidencia->id}}">Deshabilitar</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="h-10 grid grid-cols-1 gap-4 content-between">
                                {{ $incidencias->links('vendor.pagination.bootstrap-5') }}
                            </div>
                            <div class="mt-4 mb-3">
                                <a href="{{route('gestion.incidencias.deshabilitadas')}}" class="btn btn-warning text-dark">Ver deshabilitadas</a>
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


