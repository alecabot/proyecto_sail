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
                        <div class="card-header">Gestión de Correcciones deshabilitadas</div>
                        <div class="card-body">
                            <br>
                            <h2 class="mt-2 mb-4">Listado de Correcciones deshabilitadas</h2>
                            @if (count($correcciones) == 0)
                                <br>
                                <h4 class="text-center">Actualmente no hay Correcciones deshabilitadas</h4>
                                <br>
                            @else
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
                                            <tr class="align-middle">
                                                <th class="text-center">{{$correccion->id}}</th>
                                                <td><input type="text" class="form-control" id="cambioCorreccion" name="cambioCorreccion" value="{{$correccion->descripcion}}" disabled></td>
                                                <td class="text-center">
                                                <a class="btn btn-warning text-dark" href="/gestion/correccionesaplicadas/habilitar/{{$correccion->id}}"">Habilitar</a>
                                                <button id="mostrarModalEliminar" class="btn btn-danger text-white segundo-boton mostrarModalEliminar" value="{{$correccion->id}}">Eliminar</button>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="h-10 grid grid-cols-1 gap-4 content-between">
                                    {{ $correcciones->links('vendor.pagination.bootstrap-5') }}
                                </div>
                            @endif
                            <div class="mt-4 mb-3">
                                <a href="{{route('gestion.correcciones')}}" class="btn btn-warning text-dark">Ver habilitadas</a>
                            </div>

                            <div class="modal fade" id="modalConfirmar" style="display: none;" tabindex="-1">

                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">¿Está usted completamente segur@?</h5>
                                            <button type="button" id="cerrarModal" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body align-middle">
                                            <p class="align-middle"">Esta operación afectará a los partes ya creados, eliminando las referencias...</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" id="cancelarConfirmar" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                            <a  id="confirmarEliminar" class="btn btn-danger text-white" value="">Confirmar</a>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
    </div>


@endsection
@push('scripts')

    <script type="text/javascript">

    </script>

    <script type="module">

        
        $('.mostrarModalEliminar').on('click', function() {
            let idActual = $(this).val();
            let urlEliminar = "/gestion/correccionesaplicadas/eliminar/" + idActual;
            $("#confirmarEliminar").attr('href', urlEliminar);
            $("#modalConfirmar").modal("show");
        });


        $('#cerrarModal').on('click', function() {
            $("#modalConfirmar").modal("hide");
        });

        $('#cancelarConfirmar').on('click', function() {
            $("#modalConfirmar").modal("hide");
        });


    </script>

    <!-- Bootstrap Select CSS -->


@endpush


