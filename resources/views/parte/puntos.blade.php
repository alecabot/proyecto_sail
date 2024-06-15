<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><strong> Aviso Importante</strong></div>
                <div class="card-body">
                    <p class="card-title">El alumno {{$alumno->nombre}} se ha quedado sin puntos en su carnet.</p>
                    <p>Un saludo</p>
                    <p> Este mensaje es una notificación. No responda directamente a este correo. Pulse en el asunto para acceder al correo de la plataforma</p>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
