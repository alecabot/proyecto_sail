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
        <div class="card card-general">
            <div class="card-header">Incidencias Alumnos</div>
            <div class="card-body">

                <form class="row">
                    <div class="col-12 mb-1">
                        <label for="AnoAcademico" class="d-block">Año Academico</label>
                        <div class="col-12">
                            <div class="form-group">
                                <select id="AnoAcademico" class="selectpicker form-control">
                                    <option value="Seleccione una opcion">Seleccione una opcion</option>
                                    @foreach($anoAcademico as $ano)
                                        <option value="{{$ano->id}}">{{$ano->anio_academico}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 mb-1">
                        <label for="Curso" class="d-block">Curso</label>
                        <div class="col-12">
                            <div class="form-group">
                                <select id="Curso" data-live-search="true" class="selectpicker form-control">

                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 mb-2">
                        <label for="Unidad" class="d-block">Unidad</label>
                        <div class="col-12">
                            <div class="form-group">
                                <select id="Unidad" data-live-search="true" class="selectpicker form-control">

                                </select>
                            </div>
                        </div>
                    </div>
                    {{--                    <div class="col-12 align-self-end">--}}
                    {{--                        <button type="button" class="btn btn-primary" id="generate">Buscar</button>--}}
                    {{--                        <button type="reset" class="btn btn-danger" id="reset">Limpiar</button>--}}
                    {{--                    </div>--}}
                </form>
                <br>
                <div class="">
                    {{$dataTable->table(['class'=>'w-100 ' ])}}
                </div>
            </div>
        </div>
    </div>







    <div class="modal fade" data-id="" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Confirmar Eliminación</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body text-center">
                    <svg class="icon me-2" style="width: 100px; height: 100px;color: red">
                        <use xlink:href="{{asset('vendors/@coreui/icons/svg/free.svg#cil-trash')}} ">
                        </use>
                    </svg>
                    <p>¿Estás seguro de que quieres eliminar este Parte?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" id="deleteParte" class="btn btn-danger">Eliminar</button>
                </div>
            </div>
        </div>
    </div>





    <!-- Modal de Creacion -->
    <div class="modal fade modal-xl" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Crear nuevo parte</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="modalForm">
                        @csrf

                            <input type="hidden" name="id" id="hiddenId" value="">


                        {{--                        <input type="hidden" name="Curso" id="hiddenCurso">--}}
                        {{--                        <input type="hidden" name="Unidad" id="hiddenUnidad">--}}
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="Fecha" class="form-label">Fecha:</label>
                                <input name="Fecha" type="datetime-local" id="Fecha" class="form-control">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="Profesor" class="form-label">Profesor:</label>
                                <select data-live-search="true" data-selected-text-format="count" name="Profesor" id="Profesor"
                                        class="selectpicker form-control">
                                    <option value="">Seleccione una opción</option>
                                    @foreach($profesores as $profesor)
                                        <option value="{{ $profesor->dni }}">{{ $profesor->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('Profesor')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                            <div class="col-md-6 mb-3">
                                <label for="TramoHorario" class="form-label">Tramo Horario:</label>
                                <select name="TramoHorario" data-selected-text-format="count" id="TramoHorario" data-live-search="true"
                                        class="selectpicker form-control">
                                    <option value="">Seleccione una opción</option>
                                    @foreach($tramos as $tramo)
                                        <option value="{{ $tramo->id }}">{{ $tramo->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('TramoHorario')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                            <div class="col-md-6 mb-3">
                                <label for="Alumno" class="form-label">Alumno Implicados:</label>
                                <select name="Alumno[]" data-selected-text-format="count" id="Alumno" data-live-search="true" multiple
                                        class="selectpicker form-control">
                                    <!-- Options will be dynamically populated -->
                                </select>
                            </div>
                            @error('Alumno')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                            <div class="col-md-6 mb-3">


                                <label for="Incidencia" class="form-label">Incidencia:</label>
                                <select name="Incidencia" data-selected-text-format="count" id="Incidencia" data-live-search="true"
                                        class="selectpicker form-control">
                                    <option value="">Seleccione una opción</option>
                                    @foreach($incidencias as $incidencia)
                                        <option value="{{ $incidencia->id }}">{{ $incidencia->descripcion }}</option>
                                    @endforeach
                                </select>
                            </div>


                            @error('Incidencia')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                            <div class="col-md-6 mb-3">
                                <label for="ConductasNegativa" class="form-label">Conductas Negativas:</label>
                                <select multiple data-actions-box="true" data-selected-text-format="count" name="ConductasNegativa[]"
                                        id="ConductasNegativa" data-live-search="true"
                                        class="selectpicker form-control">

                                    @foreach($conductasNegativas as $conductaNegativa)
                                        <option
                                            value="{{ $conductaNegativa->id }}">{{ $conductaNegativa->descripcion }} ({{ $conductaNegativa->tipo }})</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('ConductasNegativa')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                            <div class="col-md-6 mb-3">
                                <label for="CorrecionesAplicadas" class="form-label">Correciones Aplicadas:</label>
                                <select name="CorrecionesAplicadas" id="CorrecionesAplicadas"
                                        data-live-search="true" data-selected-text-format="count" class="selectpicker form-control">
                                    <option value="">Seleccione una opción</option>
                                    @foreach($correcionesAplicadas as $correcionesAplicada)
                                        <option
                                            value="{{ $correcionesAplicada->id }}">{{ $correcionesAplicada->descripcion }} </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('CorrecionesAplicadas')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                            <div class="col-md-6 mb-3">
                                <label for="Puntos" class="form-label">Puntos de Penalizacion:</label>
                                <input id="Puntos" type="number" name="Puntos" class="form-control" value="">
                            </div>
                            @error('Puntos')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                            <div class="col-md-12 mb-3">
                                <label for="DescripcionDetallada" class="form-label">Descripcion Detallada:</label>
                                <textarea name="DescripcionDetallada" class="form-control DescripcionDetallada"
                                          id="DescripcionDetallada"></textarea>
                            </div>
                            @error('DescripcionDetallada')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" id="saveChangesButton" class="btn btn-primary">Guardar cambios</button>
                </div>
            </div>
        </div>
    </div>

@endsection



@push('scripts')

    {{ $dataTable->scripts() }}

    <script type="text/javascript">


        $(document).ready(function () {
            // Deshabilita el campo de búsqueda global de DataTables

            $('div.dt-buttons').prop('disabled', true);
        });


    </script>



    <script type="module">


        $(document).ready(function () {
            // Deshabilita el campo de búsqueda global de DataTables
            $('#users-table_filter input').prop('disabled', true);
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })
        });
        // Deshabilita el campo de búsqueda global de DataTables


        const table = $('#users-table');


        function handleSelectChange(inputSelect, outputSelect, url) {
            let selectedId = inputSelect.val();
            let options = '';

            $.ajax({
                url: url, // Reemplaza esto con la URL de tu servidor
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

        $('#AnoAcademico').change(function () {

            if ($(this).val() === 'Seleccione una opcion') {
                $('#Curso').empty().selectpicker('refresh');
                $('#Unidad').empty().selectpicker('refresh');

                $('div.dataTables_filter input').prop('disabled', true);
                $('#btnCrearParte').prop('disabled', true);

            } else {
                handleSelectChange($(this), $('#Curso'), "/cursos");
            }

        });

        $('#Curso').change(function () {
            // console.log($(this).val());
            if ($(this).val() === '0' || $(this).val() === '') {

                $('#Unidad').empty().selectpicker('refresh');

                $('div.dataTables_filter input').prop('disabled', true);
                $('#btnCrearParte').prop('disabled', true);

            } else {

                $('#Unidad').empty().selectpicker('refresh');

                $('div.dataTables_filter input').prop('disabled', true);
                $('#btnCrearParte').prop('disabled', true);

                handleSelectChange($(this), $('#Unidad'), "/unidades");
            }

        });

        // Añade aquí más eventos change para otros elementos select
        // Por ejemplo:
        // $('#otroInputSelect').change(function() {
        //     handleSelectChange($(this), $('#otroOutputSelect'));
        // });


        table.on('preXhr.dt', function (e, settings, data) {

            data.unidad = $('#Unidad').val();
            // console.log(data.clase);


        });

        table.on('init.dt', function (e, settings, data) {

            $('div.dataTables_filter input').prop('disabled', true);
            $('#btnCrearParte').prop('disabled', true);
            // console.log(data.clase);


        });


        document.addEventListener('DOMContentLoaded', function () {
            // Inicializa el modal de Bootstrap



            var exampleModalEl = document.getElementById('exampleModal');
            var modalInstance = new bootstrap.Modal(exampleModalEl);
            $('#saveChangesButton').on('click', function () {

                var id = $('#hiddenId').val();
                console.log(id);
                var url, method;
                let operacionParte = "";
                if (id) {
                    // Estás editando un registro existente
                    url = '/updateParte/' + id; // Asegúrate de que esta URL es correcta
                    method = 'POST';
                    operacionParte = 'Parte editado correctamente.';
                } else {
                    // Estás creando un nuevo registro
                    url = '/createParte'; // Asegúrate de que esta URL es correcta
                    method = 'POST';
                    operacionParte = 'Parte creado correctamente.';
                }
                $('#DescripcionDetallada').val(editorInstance.getData());
                var formData = $('#modalForm').serialize();

                // Enviar los datos al servidor usando AJAX
                $.ajax({
                    url: url, // Cambia esto por la URL de tu servidor
                    type: method,
                    data: formData,
                    success: function (response) {


                        // Cerrar el modal
                        modalInstance.hide();
                        table.DataTable().ajax.reload();
                        var alertHtml = '<div class="alert alert-success alert-dismissible fade show" role="alert" style="position: fixed; top: 5%; left: 50%; width: 60%; transform: translateX(-50%) translateY(-50%); z-index: 9999;">' +
                            ' <svg class="icon me-2"> <use xlink:href="{{asset('vendors/@coreui/icons/svg/free.svg#cil-check-circle')}} "></use></svg>' +
                            operacionParte +
                            '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
                            '</div>';

                        // Añadir la alerta al DOM
                        $('#alertPlaceholder').html(alertHtml);

                        // Cerrar la alerta después de 3 segundos (3000 milisegundos)
                        setTimeout(function () {
                            $('.alert').alert('close');
                        }, 3000);
                        // Puedes agregar más acciones aquí según sea necesario
                    },
                    error: function (xhr, status, error) {
                        // Manejar errores
                        // Cerrar el modal
                        // modalInstance.hide();


                        if (xhr.status === 422) { // 422 significa que la validación falló
                            var errors = xhr.responseJSON.errors;
                            var errorHtml = '<div id="errorMessages" class="alert alert-danger"><ul>';

                            Object.values(errors).forEach(function (error) {
                                errorHtml += '<li>' + error + '</li>';
                            });

                            errorHtml += '</ul></div>';

                            // Elimina los errores anteriores antes de añadir los nuevos errores
                            $('#errorMessages').remove();

                            $('#exampleModal .modal-body').prepend(errorHtml);
                        }


                        // Puedes agregar más acciones aquí según sea necesario
                    }
                });
            });

            var deleteModalEl = document.getElementById('deleteModal');
            var modalDeleteInstance = new bootstrap.Modal(deleteModalEl);
            $('#deleteParte').off('click').on('click', function () {
                var id = $('#deleteModal').data('id');

                $.ajax({
                    url: '/deleteParte/' + id,
                    method: 'GET',
                    success: function () {
                        modalDeleteInstance.hide();
                        table.DataTable().ajax.reload();
                        var alertHtml = '<div class="alert alert-success alert-dismissible fade show" role="alert" style="position: fixed; top: 5%; left: 50%; width: 60%; transform: translateX(-50%) translateY(-50%); z-index: 9999;">' +
                            ' <svg class="icon me-2"> <use xlink:href="{{asset('vendors/@coreui/icons/svg/free.svg#cil-check-circle')}} "></use></svg>' +
                            'Parte eliminado correctamente.' +
                            '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
                            '</div>';

                        // Añadir la alerta al DOM
                        $('#alertPlaceholder').html(alertHtml);

                        setTimeout(function () {
                            $('.alert').alert('close');
                        }, 3000);
                    }
                });
            });
        });


        const hamBurger = document.querySelector(".toggle-btn");


        $('.toggle-btn').on('click', function () {
            table.DataTable.adjust();
            document.querySelector("#sidebar").classList.toggle("expand");
            document.querySelector(".main").classList.toggle("expand"); // Añadido

        });
        $('#Unidad').change(function () {
            if ($(this).val() === 1 || $(this).val() === '') {
                $('div.dataTables_filter input').prop('disabled', true);
                $('#btnCrearParte').prop('disabled', true);
            } else {
                $('div.dataTables_filter input').prop('disabled', false);
                $('#btnCrearParte').prop('disabled', false);
            }


            table.DataTable().ajax.reload();
            return false;
        });

        $('#reset').on('click', function () {

            // table.on('preXhr.dt', function (e, settings, data) {
            //     // data.start_date = '';
            //     // data.end_date = '';
            //     // data.clase = '';
            //
            //
            // });
            $('#start_date').val('')
            $('#end_date').val('')
            $('.selectpicker').selectpicker('deselectAll');
            table.DataTable().ajax.reload();
            return false;
        });

        // $('#clase').on('change', function () {
        //     table.DataTable().ajax.reload();
        // });


    </script>






    <script type="text/javascript">

        function getFecha() {
            let fechaActual = new Date();

// Formatea la fecha y la hora en el formato correcto (yyyy-mm-ddThh:mm)
            let dia = String(fechaActual.getDate()).padStart(2, '0');
            let mes = String(fechaActual.getMonth() + 1).padStart(2, '0'); // Los meses en JavaScript comienzan desde 0
            let ano = fechaActual.getFullYear();
            let hora = String(fechaActual.getHours()).padStart(2, '0');
            let minuto = String(fechaActual.getMinutes()).padStart(2, '0');
            return ano + '-' + mes + '-' + dia + 'T' + hora + ':' + minuto;
        }


        var editorInstance;

        ClassicEditor
            .create(document.querySelector('#DescripcionDetallada'), {
                language: 'es',
                extraPlugins: [MyCustomUploadAdapterPlugin],


            })
            .then(editor => {
                editorInstance = editor;
            })
            .catch(error => {
                console.error(error);
            });
        class MyUploadAdapter {
            constructor(loader) {
                this.loader = loader;
            }

            upload() {
                return this.loader.file
                    .then(file => new Promise((resolve, reject) => {
                        const data = new FormData();
                        data.append('upload', file);

                        axios.post('/upload', data)
                            .then(response => {
                                resolve({default: response.data.url});
                            })
                            .catch(error => {
                                reject('Upload failed');
                                console.error(error);
                            });
                    }));
            }

            abort() {
            }
        }

        function MyCustomUploadAdapterPlugin(editor) {
            editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
                return new MyUploadAdapter(loader);
            };
        }


        document.addEventListener('DOMContentLoaded', (event) => {


// Establece la fecha y la hora formateadas en el campo de entrada de fecha




            $('#exampleModal').on('hidden.bs.modal', function (e) {
                // Limpiar los campos select
                $('#exampleModal select').val(null).selectpicker('refresh');

                // Limpiar los campos input y textarea, excepto el campo CSRF
                $('#exampleModal input:not([name="_token"]):not(#Fecha)').val('');

                $('#errorMessages').remove();

                document.getElementById('Fecha').value = getFecha();
                if (editorInstance) {
                    // Limpiar el campo DescripcionDetallada manejado por CKEditor
                    editorInstance.setData('');
                }

            });







            function handleSelectChange(inputSelect, outputSelect, url, addSelection = false) {
                let selectedId = inputSelect.val();
                let options = '';

                $.ajax({
                    url: url, // Reemplaza esto con la URL de tu servidor
                    method: 'GET',
                    data: {selectedId: selectedId},
                    success: function (data) {

                        if (!$.isEmptyObject(data) && addSelection) {
                            options = '<option value="">Seleccione una opcion</option>';
                        }

                        $.each(data, function (key, value) {
                            options += '<option value="' + key + '">' + value + '</option>';

                        });
                        outputSelect.empty().append(options).selectpicker('refresh');
                    }
                });
            }


            $('#Curso').change(function () {
                // console.log($(this).val());

                handleSelectChange($(this), $('#Unidad'), "/unidades", true);


            });

            $('#Unidad').change(function () {
                // console.log($(this).val());

                handleSelectChange($(this), $('#Alumno'), "/alumnos");


            });


        });
        function crearParte() {
            document.getElementById('Fecha').value = getFecha();

            $.ajax({
                url: '/getProfesores',
                method: 'GET',
                success: function (response) {

                    $('#Profesor').empty();

                    $.each(response.profesoresAll, function(index, option) {
                        $('#Profesor').append(new Option(option.nombre, option.dni));
                    });

                    $('#Profesor').selectpicker('refresh');
                    $('#Profesor').selectpicker('val', '')

                },
                error: function (xhr) {
                    console.log(xhr.responseText);
                }
            });
        }


            // // Obtener los valores seleccionados de curso y unidad
            // var selectedCurso = $('#Curso').val();
            // var selectedUnidad = $('#Unidad').val();
            //
            // // Realiza una llamada AJAX para obtener los valores de curso y unidad
            // $.ajax({
            //     url: '/get-course-unit',
            //     method: 'GET',
            //     data: {
            //         curso: selectedCurso,
            //         unidad: selectedUnidad
            //     },
            //     success: function (response) {
            //         // Establece los valores obtenidos en los campos ocultos
            //         $('#hiddenCurso').val(response.curso);
            //         $('#hiddenUnidad').val(response.unidad);
            //     },
            //     error: function (xhr) {
            //         console.log(xhr.responseText);
            //     }
            // });


        function editarParte($id) {

            $.ajax({
                url: '/getParte/' + $id,
                method: 'GET',
                // data: {
                //     curso: selectedCurso,
                //     unidad: selectedUnidad
                // },
                success: function (response) {
                    // Establece los valores obtenidos en los campos ocultos
                    $('#hiddenId').val(response.id);
                    $('#Fecha').val(response.fecha);
                    console.log(response.profesor);

                    $('#Profesor').empty();

                    $.each(response.profesorAll, function(index, option) {
                        $('#Profesor').append(new Option(option.nombre, option.dni));
                    });
                    $('#Profesor').selectpicker('refresh');

                    $('#Profesor').selectpicker('val', response.profesor)
                    $('#TramoHorario').selectpicker('val', response.tramoHorario.toString());
                    var dniAlumnos = response.alumnos.map(function (alumno) {
                        return alumno.alumno_dni;
                    });
                    $('#Alumno').selectpicker('val', dniAlumnos)

                    $('#Incidencia').selectpicker('val', response.incidencia.toString())

                    var conductasNegativas = response.conductasNegativas.map(function (conducta) {
                        return conducta.conductanegativas_id.toString();
                    });
                    $('#ConductasNegativa').selectpicker('val', conductasNegativas);
                    $('#CorrecionesAplicadas').selectpicker('val', response.correcionesAplicadas.toString());

                    $('#Puntos').val(response.puntos);

                    if(response.descripcionDetallada !== null) {
                        editorInstance.setData(response.descripcionDetallada);
                    } else {
                        editorInstance.setData('');
                    }
                },
                error: function (xhr) {
                    console.log(xhr.responseText);
                }
            });
        }

        // Cuando se hace clic en el enlace de eliminación
        function eliminarParte($id) {
            // Obtén el ID del enlace
            var id = $id;
            console.log(id);

            // Establece el ID en el atributo de datos del modal
            $('#deleteModal').data('id', id);
        }



        // Cuando se hace clic en el botón de eliminación en el modal



    </script>









    <!-- Bootstrap Select CSS -->

@endpush


