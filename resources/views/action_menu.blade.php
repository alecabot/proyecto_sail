{{--<style>--}}
{{--    .dropbone {--}}
{{--        position: fixed;--}}
{{--        z-index: 100;--}}
{{--    }--}}

{{--</style>--}}

{{--<div class="align-middle" >--}}
{{--    <button class="btn " type="button" data-bs-toggle="dropdown" aria-expanded="false">--}}
{{--        <svg class="icon " style="width: 20px; height: 20px;">--}}
{{--            <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-options"></use>--}}
{{--        </svg>--}}
{{--    </button>--}}
{{--    <div  class="dropdown dropbone" id="actionMenu">--}}

{{--        <div>--}}
{{--            <ul class="dropdown-menu">--}}
{{--                <li><a class="dropdown-item" href="#">{{$id}}</a></li>--}}
{{--                <li><a class="dropdown-item" href="#">Action</a></li>--}}
{{--                <li><a class="dropdown-item" href="#">Another action</a></li>--}}
{{--                <li><a class="dropdown-item" href="#">Something else here</a></li>--}}
{{--            </ul>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}
{{--<script>--}}

{{--        var dropdown = document.getElementById('actionMenu');--}}
{{--        dropdown.addEventListener('show.bs.dropdown', function() {--}}
{{--            // Cuando el menú desplegable se abre, aumenta el z-index--}}
{{--            alert("dfd")--}}
{{--            this.style.zIndex = '10000';--}}
{{--        });--}}
{{--        dropdown.addEventListener('hide.bs.dropdown', function() {--}}
{{--            // Cuando el menú desplegable se cierra, restablece el z-index--}}
{{--            alert("df")--}}
{{--            this.style.zIndex = '';--}}
{{--        });--}}
{{--  --}}
{{--</script>--}}

<div class="d-flex flex-row align-items-center gap-1">
    <a href="#editEmployeeModal" class="edit" data-bs-target="#exampleModal" data-bs-toggle="modal"
       onclick="editarParte({{$id}})"><i data-bs-toggle="tooltip" data-bs-title="editar"
                                         class="material-icons text-info">&#xE254;</i></a>
    <a href="#" onclick="eliminarParte({{$id}})" class="delete" data-bs-target="#deleteModal" data-bs-toggle="modal"><i
            data-bs-toggle="tooltip" data-bs-title="Eliminar" class="material-icons text-danger">&#xE872;</i></a>
    <a href="#downloadEmployeeData" data-id="{{ $id }}" class="downloadLink download"><i data-bs-toggle="tooltip"
                                                                                         data-bs-title="Descargar"
                                                                                         class="material-icons text-success">&#xE2C4;</i></a>

</div>


<script>
    $(document).ready(function () {
        // Deshabilita el campo de búsqueda global de DataTables

        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })


        $('.downloadLink').off('click').on('click', function (e) {
            e.preventDefault();

            var id = $(this).data('id');

            $.ajax({
                url: '/descargarPartePDF/' + id,
                method: 'GET',
                xhrFields: {
                    responseType: 'blob'
                },
                success: function (data) {
                    var a = document.createElement('a');
                    var url = window.URL.createObjectURL(data);
                    a.href = url;
                    a.download = 'parte.pdf';
                    document.body.append(a);
                    a.click();
                    a.remove();
                    window.URL.revokeObjectURL(url);
                }
            });
        });



            // Inicializa el modal de Bootstrap



    });

</script>
