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
                        <div class="card-header">Gestión de Profesores deshabilitad@s</div>
                        <div class="card-body">

                            <h2 class="mt-2 mb-4">Listado de de Profesores deshabilitad@s</h2>
                            @if (count($profesores) == 0)
                                <br>
                                <h4 class="text-center">Actualmente no hay Profesores deshabilitad@s</h4>
                                <br>
                            @else
                                <div class="table-responsive-xl table-div">
                                    <table class="table table-hover table-striped table-bordered" id="tabla-profesores">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th class="text-center">DNI</th>
                                                <th class="text-center">Nombre</th>
                                                <th class="text-center">Teléfono</th>
                                                <th class="text-center">Correo</th>
                                                <th class="text-center">Opciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($profesores as $profesor)
                                            <tr class="align-middle">
                                                <td><input type="text" class="form-control" id="editarProfesorDni" name="editarProfesorDni" value="{{$profesor->dni}}" disabled></td>
                                                <td><input type="text" class="form-control" id="editarProfesorNombre" name="editarProfesorNombre" value="{{$profesor->nombre}}" disabled></td>
                                                <td><input type="text" class="form-control" id="editarProfesorTelefono" name="editarProfesorTelefono" value="{{$profesor->telefono}}" disabled></td>
                                                <td><input type="text" class="form-control" id="editarProfesorCorreo" name="editarProfesorCorreo" value="{{$profesor->correo}}" disabled></td>
                                                <td class="text-center">
                                                <a class="btn btn-warning text-dark sm-mt-2" href="/gestion/profesoralumno/habilitar/{{$profesor->dni}}">Habilitar</a>
                                                    <button id="mostrarModalEliminar" class="btn btn-danger text-white segundo-boton mostrarModalEliminar" value="{{$profesor->dni}}">Eliminar</button>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="h-10 grid grid-cols-1 gap-4 content-between">
                                    {{ $profesores->links('vendor.pagination.bootstrap-5') }}
                                </div>
                            @endif
                            <div class="mt-2 mb-3">
                                <a href="{{route('gestion.profesoralumno')}}" class="btn btn-warning text-dark">Ver habilitad@s</a>
                            </div>


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
@endsection
@push('scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script type="text/javascript">

    </script>

    <script type="module">

        $('.mostrarModalEliminar').on('click', function() {
            let dniActual = $(this).val();
            let urlEliminar = "/gestion/profesoralumno/profesor/eliminar/" + dniActual;
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


