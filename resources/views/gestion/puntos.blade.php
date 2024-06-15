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
        <div class="alert alert-success align-center text-center h3">
                {{ session('success') }}
        </div>
    @endif
        <div class="card mb-4 card-general">
            <div class="card-header">Reinicio de puntos del alumnado</div>
            <div class="card-body">
                    <h2 class="mt-3 mb-3">¿Desea reiniciar todos los puntos del alumnado?</h2>
                    <h5 class="mt-3 mb-3">Es recomendable realizar la operación únicamente al inicio de cada trimestre.</h5>                               
                    <div class="mt-5 mb-3 align-middle justify-content-center text-center">
                        <button data-bs-toggle="modal" data-bs-target="#modal" class="btn btn-danger text-white align-middle btn-lg" id="generate">Reinicar puntos</button>
                    </div>
                <br>
            </div>
        </div>
        <!-- Modal de Bootstrap -->
        <div class="modal fade" id="modal" style="display: none;" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">¿Está usted completamente segur@?</h5>
                    <button type="button" id="cerrarModal" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body align-middle">
                    <p class="align-middle"">¡Los puntos no podrán revertirse a su estado anterior!</p>
                </div>
                <div class="modal-footer">
                    <button type="button" id="cancelar" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <form id="formulario-puntos" method="get" action="{{route('gestion.reinciarpuntos')}}">
                    @csrf
                        <button type="submit" id="confirmar" class="btn btn-danger text-white">Confirmar</button>
                    </form>
                </div>
                </div>
            </div>
        </div>
    </div>
                
@endsection
@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.min.js"></script>

    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', (event) => {
            let modal = document.getElementById("modal");
            let formulario = document.getElementById("formulario-puntos");
            let confirmar = document.getElementById("confirmar");
            let cancelar = document.getElementById("cancelar");
            let cerrarModal = document.getElementById("cerrarModal");

            modal.style.display = "none";

            // Al pulsar cancelar se oculta el modal
            cancelar.addEventListener('click', function() {
                modal.style.display = "none";
            });

            // Al pulsar la X se oculta el modal
            cerrarModal.addEventListener('click', function() {
                modal.style.display = "none";
            });


            // Al pulsar fuera se oculta el modal
            window.addEventListener('click', function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            });
        });
    </script>

    <script type="module">

        


    </script>

    <!-- Bootstrap Select CSS -->


@endpush


