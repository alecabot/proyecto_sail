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
    <div id="alertPlaceholder"></div>
    @if (session('success'))
        <div class="alert alert-success align-center text-center h3">
                {{ session('success') }}
        </div>
    @endif
        <div class="card mb-4 card-general">
            <div class="card-header">Gestión de tutores</div>
            <div class="card-body">
                    <h2 class="mt-3 mb-3">Asigne un Tutor a su Unidad</h2>
                    <div class="col-12 mb-1">
                            <label for="inputAnoAcademico" class="d-block">Año Academico</label>
                            <div class="col-6">
                                <div class="form-group">
                                    <select id="inputAnoAcademico" class="selectpicker form-control">
                                        <option value="Seleccione una opcion">Seleccione una opcion</option>
                                        @foreach($anioAcademicos as $anio)
                                            <option value="{{$anio->id}}">{{$anio->anio_academico}}</option>
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
                        <div class="row mt-4">
                            <div class="col-md-6 mb-3">
                                <label for="inputUnidad" class="form-label">Unidad:</label>
                                <div class="form-group">
                                    <select id="inputUnidad" data-live-search="true" class="selectpicker form-control">

                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="inputProfesor" class="form-label">Profesor:</label>
                                <select data-live-search="true" data-selected-text-format="count" name="inputProfesor" id="inputProfesor"
                                        class="selectpicker form-control">
                                    <option value="">Seleccione una opción</option>
                                    @foreach($profesores as $profesor)
                                        <option value="{{ $profesor->dni }}">{{ $profesor->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        <div class="mt-3 mb-3 align-middle justify-content-center text-center">
                            <button style="pointer-events: auto !important;" data-toggle="tooltip" data-placement="right" 
                            title="Seleccione un profesor y una unidad" class="btn btn-success text-white align-middle btn-lg" id="botonAsignar" disabled>Asignar</button>
                        </div>
                    </div>
                    <br>
                    <div class="table-responsive table-div">
                        <table class="table table-hover table-striped table-bordered">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col" class="text-center">Unidades</th>
                                    <th scope="col" class="text-center">Tutores</th>
                                </tr>
                            </thead>
                            <tbody id="tbodyTutores">
                            </tbody>
                        </table>
                    </div>
            </div>
        </div>
    </div>
                
@endsection
@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.min.js"></script>
    <link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', (event) => {
            $('[data-toggle="tooltip"]').tooltip();   
        });
    </script>

    <script type="module">
        document.addEventListener('DOMContentLoaded', (event) => {
            $('[data-toggle="tooltip"]').tooltip();   
        });
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
                $('#botonAsignar').attr('disabled','');
            //$('div.dataTables_filter input').prop('disabled', true);
            } else {
                $('#botonAsignar').attr('disabled','');
                handleSelectChange($(this), $('#inputCurso'), "/cursos");
            }

        });

        $('#inputCurso').change(function () {
            console.log($(this).val());
            if ($(this).val() === '0' || $(this).val() === '') {
                $('#inputUnidad').empty().selectpicker('refresh');
                $('#botonAsignar').attr('disabled','');
                //$('div.dataTables_filter input').prop('disabled', true);

            } else {

                $('#inputUnidad').empty().selectpicker('refresh');
                //$('div.dataTables_filter input').prop('disabled', true);
                $('#botonAsignar').attr('disabled','');
                cargarAjaxTutores();
                handleSelectChange($(this), $('#inputUnidad'), "/unidades");
            }

        });

        $('#inputUnidad').change(function () {
            let inputProfesor = $('#inputProfesor');
            if ($(this).val() == '0' || $(this).val() == '' || $(this).val() == null || inputProfesor.val() == '0' || inputProfesor.val() == '' || inputProfesor.val() == null) {

                $('#botonAsignar').attr('disabled','');

            } else {

                $('#botonAsignar').removeAttr('disabled');
                cargarAjaxTutores();
            }

        });

        $('#inputProfesor').change(function () {
            let inputUnidad = $('#inputUnidad');
            if ($(this).val() == '0' || $(this).val() == '' || $(this).val() == null || inputUnidad.val() == '0' || inputUnidad.val() == '' || inputUnidad.val() == null) {

                $('#botonAsignar').attr('disabled','');

            } else {

                $('#botonAsignar').removeAttr('disabled');
                cargarAjaxTutores();
            }

        });

        $('#botonAsignar').on('click', function () {
            let tokenAuth = "Bearer <?= Session::get('TokenApi') ?>";
            $.ajaxSetup({
                headers: {
                    'Authorization': tokenAuth
                }
            });
            let datosForm = {
                "dniTutor" : $('#inputProfesor').val(),
                "idUnidad" : $('#inputUnidad').val(),
            }
            $.ajax({
                url: "{{route('gestion.asignarTutor')}}",
                type: 'POST',
                data: datosForm,
                dataType: 'json',
                success: function(datos) {
                    cargarAjaxTutores();
                    var alertHtml = '<div class="alert alert-success alert-dismissible fade show" role="alert" style="position: fixed; top: 5%; left: 50%; width: 60%; transform: translateX(-50%) translateY(-50%); z-index: 9999;">' +
                        ' <svg class="icon me-2"> <use xlink:href="{{asset('vendors/@coreui/icons/svg/free.svg#cil-check-circle')}} "></use></svg>' +
                        " El tutor ha sido asignado correctamente " +
                        '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
                        '</div>';

                    // Añadir la alerta al DOM
                    $('#alertPlaceholder').html(alertHtml);

                    // Cerrar la alerta después de 3 segundos (3000 milisegundos)
                    setTimeout(function () {
                        $('.alert').alert('close');
                    }, 3000);
                },
                error: function( error) {
                    if (error.status === 422) { // 422 significa que la validación falló
                        var errors = error.responseJSON.errors;
                        var errorHtml = '<div id="errorMessages" class="alert alert-danger"><ul>';

                        Object.values(errors).forEach(function (error) {
                            errorHtml += '<li>' + error + '</li>';
                        });

                        errorHtml += '</ul></div>';

                        // Añadir la alerta al DOM
                        $('#alertPlaceholder').html(errorHtml);

                        // Cerrar la alerta después de 10 segundos
                        setTimeout(function () {
                            $('.alert').alert('close');
                        }, 10000);

                    }
                }
            });
            
        });

        function cargarAjaxTutores() {
            let tokenAuth = "Bearer <?= Session::get('TokenApi') ?>";
            $.ajaxSetup({
                headers: {
                    'Authorization': tokenAuth
                }
            });
            let datosForm = {
                "idCurso" : $('#inputCurso').val(),
            }
            $.ajax({
                url: "{{route('gestion.obtenerTutores')}}",
                type: 'GET',
                data: datosForm,
                dataType: 'json',
                success: function(datos) {
                    let tablaTutores = $('#tbodyTutores');
                    tablaTutores.html('');
                    let datosTutores = datos.cursosTutores;
                    let aniadirTabla = "";
                    for (let i = 0; i < datosTutores.length; i++) {
                        aniadirTabla += '<tr>'
                        + '<td class="text-center">' + datosTutores[i][0] + '</td>'
                        + '<td class="text-center">' + datosTutores[i][1] + '</td>'
                        + '</tr>';
                    }
                    tablaTutores.html(aniadirTabla);
                },
                error: function( error) {
                    if (error.status === 422) { // 422 significa que la validación falló
                        var errors = error.responseJSON.errors;
                        var errorHtml = '<div id="errorMessages" class="alert alert-danger"><ul>';

                        Object.values(errors).forEach(function (error) {
                            errorHtml += '<li>' + error + '</li>';
                        });

                        errorHtml += '</ul></div>';

                        // Añadir la alerta al DOM
                        $('#alertPlaceholder').html(errorHtml);

                        // Cerrar la alerta después de 10 segundos
                        setTimeout(function () {
                            $('.alert').alert('close');
                        }, 10000);

                    }
                }
            });
        }

    </script>

    <!-- Bootstrap Select CSS -->


@endpush


