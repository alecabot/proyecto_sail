<?php

use Illuminate\Support\Facades\Session;
?>
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
        <div class="card-header">Gestión de Profesores y Alumnos</div>
            <!-- Card body alumnos -->
            <div class="card-body"> 
                <input id="switchProfeAlumno" onchange="cambiarDiv();" type="checkbox" data-on="Alumnos" data-off="Profesores" checked data-toggle="toggle" data-onstyle="primary" data-offstyle="secondary" tabindex="1" aria-checked="true" 
                aria-label="Cambiar entre Alumnos y Profesores">          
                <div id="divAlumno">
                    <h2 class="mt-2 mb-4">Introduzca los datos generales</h2>
                    <form class="row">
                    @csrf
                        <div class="col-12 mb-1">
                            <label for="inputAnoAcademico" class="d-block">Año Academico</label>
                            <div class="col-6">
                                <div class="form-group">
                                    <select id="inputAnoAcademico" class="selectpicker form-control">
                                        <option value="Seleccione una opcion">Seleccione una opcion</option>
                                        @foreach($anoAcademico as $ano)
                                            <option value="{{$ano->id}}">{{$ano->anio_academico}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mb-1">
                            <label for="inputCurso" class="d-block">Curso</label>
                            <div class="col-6" >
                                <div class="form-group">
                                    <select id="inputCurso" data-live-search="true" class="selectpicker form-control">

                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mb-2">
                            <label for="inputUnidad" class="d-block">Unidad</label>
                            <div class="col-6">
                                <div class="form-group">
                                    <select id="inputUnidad" data-live-search="true" class="selectpicker form-control">

                                    </select>
                                </div>
                            </div>
                        </div>
                       <div class="col-12 mt-4 align-self-end">
                            <button style="pointer-events: auto !important;" data-toggle="tooltip" data-placement="right" 
                            title="Seleccione una unidad para añadir al alumno" type="button" class="btn btn-secondary" id="mostrarCrearAlumnoModal" disabled>Añadir</button>
                       </div>
                    </form>
                <br>
                <h2 class="mt-2 mb-4">Listado de Alumnos</h2>
                <div class="">
                    {{$dataTable->table(['class'=>'w-100 mb-2' ])}}
                </div>
            </div>
            <div id="divProfesor">
                <h2 class="mt-3 mb-3">Añadir nuevo profesor/a</h2>

                <form class="row" method="post" action="{{route('gestion.profesoralumno.profesor.crear')}}">
                    @csrf
                    <div class="col-auto w-75">
                        <label for="nuevaProfesor">Campos del nuevo profesor/a:</label>
                        <div class="row">
                            <input type="text" class="form-control xl-2 col-2 col-lg mt-3" id="dniProfesor" name="dniProfesor" placeholder="DNI" style="margin-right: 2% !important; margin-left: 2% !important;">
                            <input type="text" class="form-control xl-2 col-2 col-lg mt-3" id="nombreProfesor" name="nombreProfesor" placeholder="Nombre" style="margin-right: 2% !important; margin-left: 2% !important;">
                            <input type="text" class="form-control xl-2 col-2 col-lg mt-3" id="telefonoProfesor" name="telefonoProfesor" placeholder="Teléfono" style="margin-right: 2% !important; margin-left: 2% !important;">
                            <input type="email" class="form-control xl-2 col-2 col-lg mt-3" id="emailProfesor" name="emailProfesor" placeholder="Correo" style="margin-right: 2% !important; margin-left: 2% !important;">
                        </div>
                    </div>
                    <div class="col-auto align-self-end botones-crear">
                        <button type="submit" class="btn btn-secondary" id="generate">Añadir</button>
                        <button type="reset" class="btn btn-danger text-white" id="reset">Limpiar</button>
                    </div>
                </form>

                <br>

                <h2 class="mt-2 mb-4">Listado de Profesores/as</h2>
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
                    <form class="row" method="patch" action="{{route('gestion.profesoralumno.profesor.editar')}}">
                        @csrf 
                        <input type="text" class="form-control d-none" id="editarProfesorDniOriginal" name="editarProfesorDniOriginal" value="{{$profesor->dni}}">
                        <tr class="align-middle">
                            <td><input type="text" class="form-control" id="editarProfesorDni" name="editarProfesorDni" value="{{$profesor->dni}}"></td>
                            <td><input type="text" class="form-control" id="editarProfesorNombre" name="editarProfesorNombre" value="{{$profesor->nombre}}"></td>
                            <td><input type="text" class="form-control" id="editarProfesorTelefono" name="editarProfesorTelefono" value="{{$profesor->telefono}}"></td>
                            <td><input type="text" class="form-control" id="editarProfesorCorreo" name="editarProfesorCorreo" value="{{$profesor->correo}}"></td>
                            <td class="text-center">
                            <button type="submit" class="btn btn-primary" id="generate">Editar</button>
                    </form>
                            <a class="btn btn-warning text-dark sm-mt-2 segundo-boton-profesores" href="/gestion/profesoralumno/habilitar/{{$profesor->dni}}">Deshabilitar</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                    </table>
                </div>
                
                <div class="h-10 grid grid-cols-1 gap-4 content-between">
                {{ $profesores->links('vendor.pagination.bootstrap-5') }}
                </div>
                <div class="mt-2 mb-3">
                    <a href="{{route('gestion.profesoralumno.profesor.deshabilitados')}}" class="btn btn-warning text-dark">Ver deshabilitad@s</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal crear alumno -->
    <!-- Modal para editar el alumno -->
    <div class="modal fade" id="modalCrearAlumno" tabindex="-1" aria-labelledby="modalAlumnoLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
            <form id="formCrearAlumno" method="post" action="">
            @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAlumnoLabel">Formulario</h5>
                </div>
                <div class="modal-body">
                        <div class="form-group">
                            <label for="dniCrear">DNI:</label>
                            <input type="text" class="form-control mt-2" id="dniCrear" name="dniCrear">
                        </div>
                        <div class="form-group mt-3">
                            <label for="nombreCrear">Nombre:</label>
                            <input type="text" class="form-control mt-2" id="nombreCrear" name="nombreCrear">
                        </div>
                        <div class="form-group mt-3">
                            <label for="puntosCrear">Puntos:</label>
                            <input type="number" class="form-control mt-2" id="puntosCrear" name="puntosCrear">
                        </div>
                        <div class="form-group mt-3">
                            <label for="email">Correo Electrónico:</label>
                            <div id="email-alumnos" class="row mt-2">
                                <div class="col-12 col-lg-8">
                                    <input type="email" class="form-control col" name="email" id="inputNuevoCorreoCrear">
                                </div>
                                <div class="col-12 mt-2 mt-lg-0 col-lg-4">
                                    <select class="form-control selectpicker col" id="selectTipoCorreo" name="tipo_correo">
                                        <option value="personal">Personal</option>
                                        <option value="tutor">Tutor</option>
                                    </select>
                                </div>
                            </div>
                            <button type="button" class="btn btn-secondary mt-2" id="aniadirCorreoAlumnoCrear">Añadir correo</button>
                        </div>
                        <div class="form-group mt-3 d-none" id="divGeneralCorreosCrear">
                        <label for="emailRegistrados">Lista de correos registrados:</label>
                            <div id="divEmailRegistradosCrear" class="row mt-2"></div>
                        </div>
                    
                </div>
                <div class="modal-footer d-flex justify-content-between">
                <div>
                    <button type="button" class="btn btn-danger text-white" data-dismiss="modal" id="botonCerrarModalCrear">Cerrar</button>
                    <button type="submit" class="btn btn-secondary" >Añadir</button> 
                </div>
            </form>
            </div>
            </div>
        </div>
    </div>
    <!-- Modal para editar el alumno -->
    <div class="modal fade" id="modalAlumno" tabindex="-1" aria-labelledby="modalAlumnoLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
            <form id="formEditarAlumno" method="patch" action="/gestion/profesoralumno/alumno/editar">
            @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAlumnoLabel">Formulario</h5>
                </div>
                <div class="modal-body">
                        <div class="form-group">
                            <label for="dniEditar">DNI:</label>
                            <input type="text" class="form-control mt-2" id="dniEditar" name="dniEditar">
                        </div>
                        <div class="form-group mt-3">
                            <label for="nombreEditar">Nombre:</label>
                            <input type="text" class="form-control mt-2" id="nombreEditar" name="nombreEditar">
                        </div>
                        <div class="form-group mt-3">
                            <label for="puntosEditar">Puntos:</label>
                            <input type="number" class="form-control mt-2" id="puntosEditar" name="puntosEditar">
                        </div>
                        <div class="form-group mt-3">
                            <label for="email">Correo Electrónico:</label>
                            <div id="email-alumnos" class="row mt-2">
                                <div class="col-12 col-lg-8">
                                    <input type="email" class="form-control col" name="email" id="inputNuevoCorreo">
                                </div>
                                <div class="col-12 mt-2 mt-lg-0 col-lg-4">
                                    <select class="form-control selectpicker col" id="selectTipoCorreo" name="tipo_correo">
                                        <option value="personal">Personal</option>
                                        <option value="tutor">Tutor</option>
                                    </select>
                                </div>
                            </div>
                            <button type="button" class="btn btn-secondary mt-2" id="aniadirCorreoAlumno">Añadir correo</button>
                        </div>
                        <div class="form-group mt-3 d-none" id="divGeneralCorreos">
                        <label for="emailRegistrados">Lista de correos registrados:</label>
                            <div id="divEmailRegistrados" class="row mt-2"></div>
                        </div>
                    
                </div>
                <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-danger text-white" data-dismiss="modal" id="botonEliminarModal">Eliminar</button>
                <div>
                    <button type="button" class="btn btn-danger text-white" data-dismiss="modal" id="botonCerrarModal">Cerrar</button>
                    <button type="submit" class="btn btn-secondary" >Guardar</button> 
                </div>
            </form>
            </div>
            </div>
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
                        <p class="align-middle"">Eliminará completamente los datos del alumno y sus partes</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="cancelarConfirmar" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <form id="formularioEliminarAlumno" action="">
                        @csrf
                            <button type="submit" name="dniEliminar" id="cancelarEliminar" class="btn btn-danger text-white" value="">Confirmar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalExito" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="mensajeModalExito"></h5>
                </div>
                <div class="modal-footer">
                    <button type="button" id="botonCerrarModalExito" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalErrorCrearAlumno" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">La creación del alumno ha resultado en los siguientes errores</h5>
                </div>
                <div class="modal-body">
                    <p id="mensajeErrorCrearAlumno"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" id="botonCerrarModalErrorCrearAlumno" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalErrorEditarAlumno" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">La edición del alumno ha resultado en los siguientes errores</h5>
                </div>
                <div class="modal-body">
                    <p id="mensajeErrorEditarAlumno"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" id="botonCerrarModalErrorEditarAlumno" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalErrorCorreo" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Error con el formato del correo</h5>
                </div>
                <div class="modal-footer">
                    <button type="button" id="botonCerrarModalError" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalErrorCorreoCrear" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Error con el formato del correo</h5>
                </div>
                <div class="modal-footer">
                    <button type="button" id="botonCerrarModalErrorCrear" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
                </div>
            </div>
        </div>
</div>

    @if (isset($paginaProfesor))
    <div id="divPagina" display="none"></div>
    @endif
@endsection
@push('scripts')

{{ $dataTable->scripts() }}
<link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script type="text/javascript">

    $(document).ready(function() { 
        // Para controlar si existe página, se crea o no un div oculto con un condicional de su valor
        const divPagina = document.getElementById('divPagina');
        const divProfesor = document.getElementById('divProfesor');
        const divAlumno = document.getElementById('divAlumno');
        const switchProfeAlumno = document.getElementById('switchProfeAlumno');
        if (divPagina != null) {
            divAlumno.style.display = 'none';
            divProfesor.style.display = 'block';
            switchProfeAlumno.checked = true;

        } else {
            divAlumno.style.display = 'block';
            divProfesor.style.display = 'none';
        }
        // https://stackoverflow.com/questions/45617292/bootstrap-toggle-buttons-not-accessible
        var $toggle = $('input:checkbox');
        var $toggleDiv = $toggle.parent();
        $toggleDiv.attr('tabindex','0');                // allow focus
        $toggleDiv.unbind('keypress').keypress((e) => { // have enter trigger toggle
            if (e.which===13) {
                e.preventDefault();
                cambiarDivTab();
            }
        })
    });

    function cambiarDiv() {
        $('#alumnos-table').DataTable().ajax.reload();
        const divProfesor = document.getElementById('divProfesor');
        const divAlumno = document.getElementById('divAlumno');
        const checked = document.getElementById('switchProfeAlumno').checked;

        if (checked) {
            divAlumno.style.display = 'block';
            divProfesor.style.display = 'none';
        } else {
            divAlumno.style.display = 'none';
            divProfesor.style.display = 'block';
        }
    }

    function cambiarDivTab() {
        table.DataTable().ajax.reload();
        const divProfesor = document.getElementById('divProfesor');
        const divAlumno = document.getElementById('divAlumno');
        const checked = document.getElementById('switchProfeAlumno').checked;
        const switchToggleProfeAlumno = document.getElementsByClassName('toggle')[0];
        if (checked) {
            divAlumno.style.display = 'block';
            divProfesor.style.display = 'none';
            switchProfeAlumno.checked = false;
            switchToggleProfeAlumno.classList.add("off");
        } else {
            divAlumno.style.display = 'none';
            divProfesor.style.display = 'block';
            switchProfeAlumno.checked = true;
            switchToggleProfeAlumno.classList.remove("off");
        }
    }


</script>

    <script type="module">
    
    const table = $('#alumnos-table');
    // Variables que se usarán para el formulario de alumno
    var dniPulsado = "";
    var correosAlumnoAniadir = [];
    var correosAlumnoEliminar = [];
    $(document).ready(function() {
        // https://stackoverflow.com/questions/45617292/bootstrap-toggle-buttons-not-accessible
        var $toggle = $('input:checkbox');
        var $toggleDiv = $toggle.parent();
        $toggleDiv.attr('tabindex','0');                // allow focus
        $toggleDiv.unbind('keypress').keypress((e) => { // have enter trigger toggle
            if (e.which===13) {
                e.preventDefault();
                cambiarDivTab();
            }
        })
        // Para controlar si existe página, se crea o no un div oculto con un condicional de su valor
        const divPagina = document.getElementById('divPagina');
        const divProfesor = document.getElementById('divProfesor');
        const divAlumno = document.getElementById('divAlumno');
        const switchProfeAlumno = document.getElementById('switchProfeAlumno');
        const switchToggleProfeAlumno = document.getElementsByClassName('toggle')[0];
        if (divPagina != null) {
            divAlumno.style.display = 'none';
            divProfesor.style.display = 'block';
            switchToggleProfeAlumno.classList.add("off");
            switchProfeAlumno.checked = false;
        } else {
            divAlumno.style.display = 'block';
            divProfesor.style.display = 'none';
        }        
        $('#formCrearAlumno').on('submit', function(event) {
            event.preventDefault();
            let correosAniadirCrear = null;
            if (correosAlumnoAniadir.length > 0) correosAniadirCrear = correosAlumnoAniadir;
            let dniCrear = $('#dniCrear').val();
            let nombreCrear = $('#nombreCrear').val();
            let puntosCrear = $('#puntosCrear').val();
            // idUnidadCrear ya establecido
            let datosForm = {
                    "dniCrear" : dniCrear,
                    "nombreCrear" : nombreCrear,
                    "puntosCrear" : puntosCrear,
                    "idUnidadCrear" : idUnidadCrear,
                    "correosAniadirCrear" : correosAniadirCrear,
                }
                let tokenAuth = "Bearer <?= Session::get('TokenApi') ?>";
                $.ajaxSetup({
                    headers: {
                        'Authorization': tokenAuth
                    }
                });
                $.ajax({
                    url: "{{route('gestion.crearAlumno')}}",
                    type: 'POST',
                    data: datosForm,
                    dataType: 'json',
                    success: function(datos) {
                        $("#modalCrearAlumno").modal("hide");
                        $("#mensajeModalExito").html("Alumno añadido correctamente");
                        $("#modalExito").modal("show");
                        table.DataTable().ajax.reload();
                    },
                    error: function(error) {
                        let errores = error.responseJSON.errors;
                        let cadenaErrores = "";
                        for (let errorActual in errores) {
                            if (errores.hasOwnProperty(errorActual)) {
                                cadenaErrores += errores[errorActual]
                            }
                        }
                        $("#modalCrearAlumno").modal("hide");
                        $('#mensajeErrorCrearAlumno').html(cadenaErrores);
                        $("#modalErrorCrearAlumno").modal("show");
                    }
                });
        });
        $('#formEditarAlumno').on('submit', function(event) {
            event.preventDefault();
            let correosAniadir = null;
            let correosEliminar = null;
            if (correosAlumnoAniadir.length > 0) correosAniadir = correosAlumnoAniadir;
            if (correosAlumnoEliminar.length > 0) correosEliminar = JSON.stringify(correosAlumnoEliminar);
            let dniOriginal = dniPulsado;
            let dniEditar = $('#dniEditar').val();
            let nombreEditar = $('#nombreEditar').val();
            let puntosEditar = $('#puntosEditar').val();
            let datosForm = {
                    "dniOriginal" : dniOriginal,
                    "dniEditar" : dniEditar,
                    "nombreEditar" : nombreEditar,
                    "puntosEditar" : puntosEditar,
                    "correosAniadir" : correosAniadir,
                    "correosEliminar" : correosEliminar,
                }
                let tokenAuth = "Bearer <?= Session::get('TokenApi') ?>";
                $.ajaxSetup({
                    headers: {
                        'Authorization': tokenAuth
                    }
                });
                $.ajax({
                    url: "{{route('gestion.editarAlumno')}}",
                    type: 'PATCH',
                    data: datosForm,
                    dataType: 'json',
                    success: function(datos) {
                        $("#modalAlumno").modal("hide");
                        $("#mensajeModalExito").html("Alumno editado correctamente");
                        $("#modalExito").modal("show");
                        table.DataTable().ajax.reload();
                    },
                    error: function(error) {
                        let errores = error.responseJSON.errors;
                        let cadenaErrores = "";
                        for (let errorActual in errores) {
                            if (errores.hasOwnProperty(errorActual)) {
                                cadenaErrores += errores[errorActual]
                            }
                        }
                        $("#modalAlumno").modal("hide");
                        $('#mensajeErrorEditarAlumno').html(cadenaErrores);
                        $("#modalErrorEditarAlumno").modal("show");
                    }
                });

        });
        $('#formularioEliminarAlumno').on('submit', function(event) {
            event.preventDefault();
            // Solo necesita dniEliminar, que es dniPulsado
            let datosForm = {
                    "dniEliminar" : dniPulsado,
                }
                let tokenAuth = "Bearer <?= Session::get('TokenApi') ?>";
                $.ajaxSetup({
                    headers: {
                        'Authorization': tokenAuth
                    }
                });
                $.ajax({
                    url: "{{route('gestion.eliminarAlumno')}}",
                    type: 'DELETE',
                    data: datosForm,
                    dataType: 'json',
                    success: function(datos) {
                        $("#modalConfirmar").modal("hide");
                        table.DataTable().ajax.reload();
                    },
                    error: function(error) {
                        // Si hay algún no se cerrar
                    }
                });
        });
        $('[data-toggle="tooltip"]').tooltip();   
    });

    function cambiarDiv() {
        table.DataTable().ajax.reload();
        const divProfesor = document.getElementById('divProfesor');
        const divAlumno = document.getElementById('divAlumno');
        const checked = document.getElementById('switchProfeAlumno').checked;
        
        if (checked) {
            divAlumno.style.display = 'block';
            divProfesor.style.display = 'none';
        } else {
            divAlumno.style.display = 'none';
            divProfesor.style.display = 'block';
        }
    }

    function cambiarDivTab() {
        table.DataTable().ajax.reload();
        const divProfesor = document.getElementById('divProfesor');
        const divAlumno = document.getElementById('divAlumno');
        const checked = document.getElementById('switchProfeAlumno').checked;
        const switchToggleProfeAlumno = document.getElementsByClassName('toggle')[0];
        if (checked) {
            divAlumno.style.display = 'block';
            divProfesor.style.display = 'none';
            switchProfeAlumno.checked = false;
            switchToggleProfeAlumno.classList.remove("off");
        } else {
            divAlumno.style.display = 'none';
            divProfesor.style.display = 'block';
            switchProfeAlumno.checked = true;
            switchToggleProfeAlumno.classList.add("off");
        }
    }

            
    function handleSelectChange(inputSelect, outputSelect, url) {                
        let selectedId = inputSelect.val();               
        let options = '';
            $.ajax({
                url: url,
                method: 'GET',
                data: {selectedId: selectedId},
                success: function (data) {

                    if (!$.isEmptyObject(data)) {
                        options = '<option value="">Seleccione una opcion</option>';
                    }

                    $.each(data, function (key, value) {
                        options += '<option value="' + key + '">' + value + '</option>';
                    });
                    outputSelect.empty().append(options).selectpicker('refresh');
                }
            });
        }

        $('#inputAnoAcademico').change(function () {
            if ($(this).val() === 'Seleccione una opcion') {
                $('#inputCurso').empty().selectpicker('refresh');
                $('#inputUnidad').empty().selectpicker('refresh');
                $('#mostrarCrearAlumnoModal').attr("disabled", '');
            //$('div.dataTables_filter input').prop('disabled', true);
            } else {
                $('#mostrarCrearAlumnoModal').attr("disabled", '');
                 handleSelectChange($(this), $('#inputCurso'), "/cursos");
            }

        });

        $('#inputCurso').change(function () {
            console.log($(this).val());
            if ($(this).val() === '0' || $(this).val() === '') {
                $('#mostrarCrearAlumnoModal').attr("disabled", '');
                $('#inputUnidad').empty().selectpicker('refresh');

                //$('div.dataTables_filter input').prop('disabled', true);

            } else {

                $('#inputUnidad').empty().selectpicker('refresh');
                $('#mostrarCrearAlumnoModal').attr("disabled", '');
                //$('div.dataTables_filter input').prop('disabled', true);

                handleSelectChange($(this), $('#inputUnidad'), "/unidades");
            }

        });

        table.on('preXhr.dt', function (e, settings, data) {

            data.unidad = $('#inputUnidad').val();

        });

        const hamBurger = document.querySelector(".toggle-btn");


        $('.toggle-btn').on('click', function () {
            table.DataTable.adjust();
            document.querySelector("#sidebar").classList.toggle("expand");
            document.querySelector(".main").classList.toggle("expand"); // Añadido

        });
        $('#inputUnidad').change(function () {
            if ($(this).val() === 1 || $(this).val() === '') {
                //$('div.dataTables_filter input').prop('disabled', true);
                $('#mostrarCrearAlumnoModal').attr("disabled", '');
            } else {
                //$('div.dataTables_filter input').prop('disabled', false);
                $('#mostrarCrearAlumnoModal').removeAttr("disabled");
            }
            table.DataTable().ajax.reload();
            return false;
        });

        $('#reset').on('click', function () {

            $('#start_date').val('')
            $('#end_date').val('')
            $('.selectpicker').selectpicker('deselectAll');
            table.DataTable().ajax.reload();
            return false;
        });
        
        $('#alumnos-table').on('click', 'td', function() {
            // Reiniciamos los arrays
            correosAlumnoAniadir = [];
            correosAlumnoEliminar = [];
            $('#inputNuevoCorreo').val('');
            let data = $('#alumnos-table').DataTable().row(this).data();
            let dni = data.dni;
            dniPulsado = dni;
            let nombre = data.nombre;
            let puntos = data.puntos;
            let correos = data.Correos;
            if (correos == null) {
                $("#divEmailRegistrados").html("");
                $("#divGeneralCorreos").removeClass("d-block");
                $("#divGeneralCorreos").addClass("d-none");
                $("#dniEditar").val(dni);
                $("#nombreEditar").val(nombre);
                $("#puntosEditar").val(puntos);
                $("#modalAlumno").modal("show");
            } else {
                let correosBienFormato = [];
                let datosForm = {
                    "dni" : dni
                }
                let tokenAuth = "Bearer <?= Session::get('TokenApi') ?>";
                $.ajaxSetup({
                    headers: {
                        'Authorization': tokenAuth
                    }
                });
                $.ajax({
                    url: "{{route('gestion.obtenerCorreos')}}",
                    type: 'GET',
                    data: datosForm,
                    dataType: 'json',
                    success: function(datos) {
                        $("#divEmailRegistrados").html("");
                        for (let i = 0; i < datos.length; i++) {
                            correosBienFormato[i] = datos[i];
                            const $ElementoCorreo = `
                                <div class="row mb-2">
                                    <input type="number" value="" name="codCorreo" class="form-control d-none">
                                    <div class="col-8 col-md-10">
                                        <input  type="text" value="` + correosBienFormato[i]['correo'] + ` (` + correosBienFormato[i]['tipo'] + `)` 
                                        +`" name="emailRegistrado" class="form-control" disabled>
                                    </div>
                                    <div class="col-2">
                                        <button type="button" value="` + correosBienFormato[i]['id'] + `" class="btn btn-danger text-white eliminar-correo-alumno">Borrar</button>
                                    </div>
                                </div>
                            `;
                            $('#divEmailRegistrados').append($ElementoCorreo);
                        }
                        $('#divEmailRegistrados').on('click', '.eliminar-correo-alumno', function() {
                            let codigoEliminar = $(this).val();
                            if (!(correosAlumnoEliminar.includes(codigoEliminar))) correosAlumnoEliminar.push(codigoEliminar);
                            $(this).closest('.row').remove();
                        });
                        $("#divGeneralCorreos").removeClass("d-none");
                        $("#divGeneralCorreos").addClass("d-block");
                        // Asignación de valores a los input
                        $("#dniEditar").val(dni);
                        $("#nombreEditar").val(nombre);
                        $("#puntosEditar").val(puntos);
                        $("#modalAlumno").modal("show");
                    },
                    error: function( error) {
                        
                    }
                });
            }
        });

        $(document).on('click', '.remove-email', function() {
            $(this).closest('.email-group').remove();
        });
        
        $('#botonCerrarModal').on('click', function() {
            $("#modalAlumno").modal("hide");
            $("#modalCrearAlumno").modal("hide");
        });

        $('#botonCerrarModalExito').on('click', function() {
            $("#modalExito").modal("hide");
            $("#modalAlumno").modal("hide");
            $("#modalCrearAlumno").modal("hide");
        });

        $('#botonCerrarModalCrear').on('click', function() {
            $("#modalAlumno").modal("hide");
            $("#modalCrearAlumno").modal("hide");
        });
        
        $('#botonEliminarModal').on('click', function() {
            $("#modalAlumno").modal("hide");
            $("#modalConfirmar").modal("show");
            $("#cancelarEliminar").val(dniPulsado);
        });

        $('#cancelarConfirmar').on('click', function() {
            $("#modalAlumno").modal("show");
            $("#modalConfirmar").modal("hide");
        });

        $('#cerrarModal').on('click', function() {
            $("#modalAlumno").modal("show");
            $("#modalConfirmar").modal("hide");
        });

        let idUnidadCrear = null;
        $('#mostrarCrearAlumnoModal').on('click', function() {
            correosAlumnoAniadir = [];
            $('#dniCrear').val('');
            $('#nombreCrear').val('');
            $('#puntosCrear').val('');
            $("#divEmailRegistradosCrear").html("");
            idUnidadCrear = $('#inputUnidad').val();
            $("#modalCrearAlumno").modal("show");
        });

        $('#aniadirCorreoAlumnoCrear').on('click', function() {
            let correoNuevo = $("#inputNuevoCorreoCrear").val().toLowerCase();
            let tipoCorreoNuevo = $("#selectTipoCorreo").val();
            if (validateEmail(correoNuevo)) {
                let datosCorreo = [correoNuevo, tipoCorreoNuevo];
                correosAlumnoAniadir.push(datosCorreo);
                $('#correosAniadirCrear').val(correosAlumnoAniadir);
                const $ElementoCorreo = `
                    <div class="row mb-2">
                        <input type="number" value="" name="codCorreo" class="form-control d-none">
                        <div class="col-8 col-md-10">
                            <input  type="text" value="` + correoNuevo + ` (` + tipoCorreoNuevo + `)` 
                            +`" name="emailRegistrado" class="form-control" disabled>
                        </div>
                        <div class="col-2">
                            
                        </div>
                    </div>
                `;
                $('#divEmailRegistradosCrear').append($ElementoCorreo);
                $("#inputNuevoCorreoCrear").val('');
                $("#divGeneralCorreosCrear").removeClass("d-none");
                $("#divGeneralCorreosCrear").addClass("d-block");
            } else {
                $("#modalCrearAlumno").modal("hide");
                $("#modalErrorCorreoCrear").modal("show");
            }
            
        });

        $('#botonCerrarModalErrorCrearAlumno').on('click', function() {
            $("#modalErrorCrearAlumno").modal("hide");
            $("#modalCrearAlumno").modal("show");
        });

        $('#botonCerrarModalErrorEditarAlumno').on('click', function() {
            $("#modalErrorEditarAlumno").modal("hide");
            $("#modalAlumno").modal("show");
        });

        $('#botonCerrarModalErrorCrear').on('click', function() {
            $("#modalErrorCorreoCrear").modal("hide");
            $("#modalCrearAlumno").modal("show");
        });

        $('#aniadirCorreoAlumno').on('click', function() {
            let correoNuevo = $("#inputNuevoCorreo").val().toLowerCase();
            let tipoCorreoNuevo = $("#selectTipoCorreo").val();
            if (validateEmail(correoNuevo)) {
                let datosCorreo = [correoNuevo, tipoCorreoNuevo];
                correosAlumnoAniadir.push(datosCorreo);
                const $ElementoCorreo = `
                    <div class="row mb-2">
                        <input type="number" value="" name="codCorreo" class="form-control d-none">
                        <div class="col-8 col-md-10">
                            <input  type="text" value="` + correoNuevo + ` (` + tipoCorreoNuevo + `)` 
                            +`" name="emailRegistrado" class="form-control" disabled>
                        </div>
                        <div class="col-2">
                            
                        </div>
                    </div>
                `;
                $('#divEmailRegistrados').append($ElementoCorreo);
                $("#inputNuevoCorreo").val('');
                $("#divGeneralCorreos").removeClass("d-none");
                $("#divGeneralCorreos").addClass("d-block");
            } else {
                $("#modalAlumno").modal("hide");
                $("#modalErrorCorreo").modal("show");
            }
            
        });

        $('#botonCerrarModalError').on('click', function() {
            $("#modalErrorCorreo").modal("hide");
            $("#modalAlumno").modal("show");
        });

        // Función importada de https://mailtrap.io/blog/javascript-email-validation/
        function validateEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }
    </script>


@endpush


