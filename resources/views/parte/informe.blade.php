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
            <div class="card-header">Informe Incidencias</div>
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
                    <div class="col-12 mb-3">
                        <label for="Unidad" class="d-block">Unidad</label>
                        <div class="col-12">
                            <div class="form-group">
                                <select id="Unidad" data-live-search="true" class="selectpicker form-control">

                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 mb-2">
                        <button type="button" id="exportButton" class="btn btn-success text-white">Exportar</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

@endsection



@push('scripts')



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

        $('#exportButton').click(function() {
            // Obtén los valores seleccionados
            var selectedAnoAcademico = $('#AnoAcademico').val();
            var selectedCurso = $('#Curso').val();
            var selectedUnidad = $('#Unidad').val();

            // Realiza una llamada AJAX para exportar los datos
            $.ajax({
                url: '/informeExcel', // Reemplaza esto con la URL de tu servidor
                method: 'POST',
                data: {
                    anoAcademico: selectedAnoAcademico,
                    curso: selectedCurso,
                    unidad: selectedUnidad
                },
                xhrFields:{
                    responseType: 'blob'
                },
                success: function(data) {
                    var url = window.URL || window.webkitURL;
                    // Obtén la fecha actual
                    var date = new Date();

// Formatea la fecha en el formato 'YYYYMMDD'
                    var formattedDate = date.getFullYear() + '-' + ('0' + (date.getMonth() + 1)).slice(-2) + '-' + ('0' + date.getDate()).slice(-2);
                    console.log(formattedDate);
                    var objectUrl = url.createObjectURL(data);
                    var invisibleLink = document.createElement('a');
                    invisibleLink.href = objectUrl;
                    invisibleLink.download = 'incidencias_' + formattedDate + '.xlsx';
                    document.body.appendChild(invisibleLink);
                    invisibleLink.click();
                    document.body.removeChild(invisibleLink);
                }
            });
        });

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



    </script>






    <script type="text/javascript">





        document.addEventListener('DOMContentLoaded', (event) => {


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




    </script>


@endpush


