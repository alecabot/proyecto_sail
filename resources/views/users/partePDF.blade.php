<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Parte PDF</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 0;
            margin: 0;

        }
        .container {
            width: 80%;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
            border-radius: 5px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            font-size: 2em;
            color: #333;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .parte-info {
            border: 1px solid #ddd;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .parte-info h2 {
            font-size: 1.5em;
            color: #333;
            margin-bottom: 10px;
            text-transform: uppercase;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }
        .parte-info p {
            font-size: 1em;
            color: #666;
            margin-bottom: 10px;
            line-height: 1.6;
        }
        .parte-info .alumnos, .conductas-negativas, .correcciones-aplicadas, .incidencias {
            margin-top: 20px;
        }
        .parte-info .alumnos h3, .conductas-negativas h3, .correcciones-aplicadas h3, .incidencias h3 {
            font-size: 1.2em;
            color: #333;
            margin-bottom: 10px;
            text-transform: uppercase;
        }
        .parte-info .alumnos ul, .conductas-negativas ul, .correcciones-aplicadas ul, .incidencias ul {
            list-style-type: none;
            padding: 0;
        }
        .parte-info .alumnos ul li, .conductas-negativas ul li, .correcciones-aplicadas ul li, .incidencias ul li {
            margin-bottom: 5px;
            padding: 5px;
            background-color: #f9f9f9;
            border-radius: 3px;
        }
        .descripcion-detallada img {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>Información del Parte</h1>
    </div>
    <div class="parte-info">
        <h2>Detalles del Parte</h2>
        <p><strong>Fecha y hora:</strong> {{$parte->created_at}}</p>
        <p><strong>Profesor:</strong> {{$parte->profesors()->first()->nombre}}</p>
        <p><strong>Tramo Horario:</strong> {{$parte->tramoHorario()->first()->nombre}}</p>
        <p><strong>Puntos Penalizados:</strong> {{$parte->puntos_penalizados}}</p>
        <p><strong>Curso:</strong> {{ $parte->alumnos->first()->unidad->curso->nombre }}</p>
        <p><strong>Unidad:</strong> {{ $parte->alumnos->first()->unidad->nombre }}</p>
        <div class="alumnos">
            <h3>Alumnos Implicados:</h3>
            <ul>
                @foreach($parte->alumnos as $alumno)
                    <li>{{ $alumno->nombre }}</li>
                @endforeach
            </ul>
        </div>
        <div class="conductas-negativas">
            <h3>Conductas Negativas:</h3>
            <ul>
                @foreach($parte->conductanegativas as $conducta)
                    <li>{{ $conducta->descripcion }} ({{ $conducta->tipo }})</li>
                @endforeach
            </ul>
        </div>
        <div class="correcciones-aplicadas">
            <h3>Corrección Aplicada:</h3>
            <ul>
                <li>{{ $parte->correccionesaplicadas()->first()->descripcion }}</li>
            </ul>
        </div>
        <div class="incidencias">
            <h3>Incidencia:</h3>
            <ul>
                <li>{{ $parte->incidencias()->first()->descripcion }}</li>
            </ul>
        </div>
        <p><strong>Descripción Detallada:</strong></p>
        <div class="descripcion-detallada">
            {!! $parte->descripcion_detallada !!}
        </div>
    </div>
</div>
</body>
</html>
