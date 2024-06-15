<!DOCTYPE html>
<html>
<head>
    <title>Notificación de Parte para Jefatura</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            width: 80%;
            margin: auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        h2, h3 {
            color: #444;
        }
        p {
            line-height: 1.6;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Notificación de Parte</h2>
    <p>Estimado miembro de la jefatura,</p>
    @if($eliminado)
        <p>Le informamos se ha eliminado un parte. A continuación, encontrará los detalles:</p>
    @elseif($actualizado)
        <p>Le informamos se ha modificado un parte. A continuación, encontrará los detalles:</p>
    @else
    <p>Le informamos que se ha emitido un parte. A continuación, encontrará los detalles:</p>
    @endif
    <h3>Detalles del Parte:</h3>
    <p>Curso: {{ $parte->alumnos->first()->unidad->curso->nombre }}</p>
    <p>Unidad: {{ $parte->alumnos->first()->unidad->nombre }}</p>
    <p>Profesor: {{ $parte->profesors()->first()->nombre  }}</p>
    <p>Tipo de parte: {{ $parte->colectivo == 'Si' ? 'Colectivo' : 'Individual' }}</p>
    <p>Puntos de penalizacion: {{$parte->puntos_penalizados}}</p>
    <p>Fecha del Parte: {{ $parte->created_at }}</p>
    <h3>Alumnos Implicados:</h3>
    @if($parte->colectivo == 'Si')
        @foreach($parte->alumnos as $alumno)
            <ul>
                <li>{{ $alumno->nombre }}</li>
            </ul>
        @endforeach
    @else
        <p>Nombre: {{ $parte->alumnos()->first()->nombre }}</p>
    @endif
    <h3>Tipo De Parte:</h3>
    <p>{{ $parte->colectivo == 'Si' ? 'Colectivo' : 'Individual' }}</p>
    <div class="incidencias">
        <h3>Incidencia:</h3>
        <ul>
            <li>{{ $parte->incidencias->first()->descripcion }}</li>
        </ul>
    </div>
    <div class="conductas-negativas">
        <h3>Conductas Negativas:</h3>
        <ul>
            @foreach($parte->conductanegativas as $conducta)
                <li>{{ $conducta->descripcion }}</li>
            @endforeach
        </ul>
    </div>
    <div class="correcciones-aplicadas">
        <h3>Corrección Aplicada:</h3>
        <ul>
            <li>{{ $parte->correccionesaplicadas->first()->descripcion }}</li>
        </ul>
    </div>

    <h3>Descripcion detallada del Parte:</h3>
    {!! $parte->descripcion_detallada !!}
    @if(!empty($imagePaths))
        @foreach($imagePaths as $imagePath)
            <img src="{{ $message->embed($imagePath) }}" alt="Image">
        @endforeach
    @endif

    <p>Un saludo</p>
    <p> Este mensaje es una notificación. No responda directamente a este correo. Pulse en el asunto para acceder al correo de la plataforma</p>
</div>
</body>
</html>
