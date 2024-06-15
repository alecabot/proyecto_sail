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
                        <div class="card-header">Gestión de Conductas Negativas deshabilitadas</div>
                        <div class="card-body">

                            <h2 class="mt-2 mb-4">Listado de Conductas Negativas deshabilitadas</h2>
                            @if (count($conductas) == 0)
                                <br>
                                <h4 class="text-center">Actualmente no hay Conductas Negativas deshabilitadas</h4>
                                <br>
                            @else
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
                                                <tr class="align-middle">
                                                    <th class="text-center">{{$conducta->id}}</th>
                                                    <td><input type="text" class="form-control" id="cambioConducta" name="cambioConducta" value="{{$conducta->descripcion}}" disabled></td>
                                                    <td>
                                                        <select class="form-select col" id="cambioConductaTipo" name="cambioConductaTipo" disabled>
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
                                                    <a class="btn btn-warning text-dark sm-mt-2" href="/gestion/conductasnegativas/habilitar/{{$conducta->id}}">Habilitar</a>
                                                    <button id="mostrarModalEliminar" class="btn btn-danger text-white segundo-boton mostrarModalEliminar" value="{{$conducta->id}}">Eliminar</button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="h-10 grid grid-cols-1 gap-4 content-between">
                                    {{ $conductas->links('vendor.pagination.bootstrap-5') }}
                                </div>
                            @endif
                            <div class="mt-2 mb-3">
                                <a href="{{route('gestion.negativas')}}" class="btn btn-warning text-dark">Ver habilitadas</a>
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
            let idActual = $(this).val();
            let urlEliminar = "/gestion/conductasnegativas/eliminar/" + idActual;
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


