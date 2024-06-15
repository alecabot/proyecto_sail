<?php

namespace App\Http\Controllers;

use App\Mail\CorreoPuntosParte;
use App\Models\Alumno;
use App\Models\AlumnoParte;
use App\Models\Correo;
use App\Models\Parte;
use App\Models\ParteConductanegativa;
use App\Models\ParteCorreccionsaplicada;
use App\Models\ParteIncidencia;
use App\Models\Profesor;
use App\Models\Unidad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;

class ApiController extends Controller
{
    public function obtenerCorreos(Request $request) {
        $validator = Validator::make($request->all(), [
            'dni' => ['required', 'string', 'size:9', 'regex:/^[0-9]{8}[TRWAGMYFPDXBNJZSQVHLCKE]$/i'],
        ],[
            'dni.required' => 'El DNI es obligatorio. <br><br>',
            'dni.string' => 'El DNI debe ser una cadena de texto. <br><br>',
            'dni.size' => 'El DNI debe tener una longitud de 9. <br><br>',
            'dni.regex' => 'El DNI tiene un formato incorrecto. <br><br>',
        ]);

        // Se devuelve la información de los errores si ha fallado
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $dniAlumno = $request->dni;

        $existeDNI = Alumno::select('*')->where('dni','=',$dniAlumno)->get();
        if (count($existeDNI) == 0) {
            return response()->json([
                'success' => false,
                'errors' => ["- DNI no registrado. <br><br>"]
            ], 422);
        }

        $listaCorreos = Correo::select("id","correo","tipo")->where("alumno_dni","=",$dniAlumno)->get();
        return $listaCorreos;
    }

    public function crearAlumno(Request $request) {
        $validator = Validator::make($request->all(), [
            'dniCrear' => ['required', 'string', 'size:9', 'regex:/^[0-9]{8}[TRWAGMYFPDXBNJZSQVHLCKE]$/i'],
            'nombreCrear' => ['required', 'string', 'min:6', 'max:80'],
            'puntosCrear' => ['required', 'numeric', 'between:0,50'],
            'idUnidadCrear' => ['required', 'numeric', 'between:1,5000'],
        ],[
            'dniCrear.required' => '- El DNI es obligatorio. <br><br>',
            'dniCrear.string' => '- El DNI debe ser una cadena de texto. <br><br>',
            'dniCrear.size' => '- El DNI debe tener una longitud de 9. <br><br>',
            'dniCrear.regex' => '- El DNI tiene un formato incorrecto. <br><br>',
            'nombreCrear.required' => '- El nombre del alumno es obligatorio. <br><br>',
            'nombreCrear.string' => '- El nombre debe ser una cadena de texto. <br><br>',
            'nombreCrear.min' => '- El nombre debe tener 10 o más caracteres, se deben incluir apellidos. <br><br>',
            'nombreCrear.max' => '- El nombre es demasiado largo, no debe superar los 80 caracteres. <br><br>',
            'puntosCrear.required' => '- Los puntos son obligatorios. <br><br>',
            'puntosCrear.numeric' => '- El campo de puntos debe tener un valor numérico. <br><br>',
            'puntosCrear.between' => '- El valor de los puntos no puede ser inferior a 0 ni mayor a 50. <br><br>',
            'idUnidadCrear.required' => '- El id de la unidad es obligatorio, seleccione una unidad antes de crear. <br><br>',
            'idUnidadCrear.numeric' => '- El identificador de la unidad debe tener un valor numérico. <br><br>',
            'idUnidadCrear.between' => '- El id de la unidad debe tener un valor válido. <br><br>',
        ]);

        // Se devuelve la información de los errores si ha fallado
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Asignamos los datos recibidos por Request a variables
        $dniAlumnoCrear = $request->dniCrear;
        if (!($this->validarDNI($dniAlumnoCrear))) {
            return response()->json([
                'success' => false,
                'errors' => ["- DNI con formato incorrecto. <br><br>"]
            ], 422);
        }

        $listaCorreosAniadir = $request->correosAniadirCrear;
        $nombreCrear = $request->nombreCrear;
        $puntosCrear = $request->puntosCrear;
        $idUnidadCrear = $request->idUnidadCrear;
        $alumnoCrear = new Alumno();
        
        $existeDNI = Alumno::select('*')->where('dni','=',$dniAlumnoCrear)->get();
        if (count($existeDNI) > 0) {
            return response()->json([
                'success' => false,
                'errors' => ["- No creado, DNI ya registrado. <br><br>"]
            ], 422);
        }
        
        // Tras comprobar el dni asignamos el resto de valores antes de guardar
        $alumnoCrear->dni = $dniAlumnoCrear;
        $alumnoCrear->nombre = $nombreCrear;
        $alumnoCrear->puntos = $puntosCrear;
        $alumnoCrear->id_unidad = $idUnidadCrear;
        $alumnoCrear->save();

        // Validamos los correos enviados antes de añadirlo, formateando el string recibido como array
        if ($listaCorreosAniadir != null) {
            for ($i = 0; $i < count($listaCorreosAniadir); $i++) {
                if (!(filter_var($listaCorreosAniadir[$i][0], FILTER_VALIDATE_EMAIL)) 
                && ($listaCorreosAniadir[$i][1] != "personal" || $listaCorreosAniadir[$i][1] != "tutor")) {
                    return response()->json([
                        'success' => false,
                        'errors' => ["- Datos editados, pero algunos de los correos no se han añadido por formato incorrecto. <br><br>"]
                    ], 200);
                }
                $nuevoCorreo = new Correo();
                $nuevoCorreo->correo = $listaCorreosAniadir[$i][0];
                $nuevoCorreo->tipo = $listaCorreosAniadir[$i][1];
                $nuevoCorreo->alumno_dni = $dniAlumnoCrear;
                $nuevoCorreo->save();
            }
        }

        return response()->json([
            'success' => true,
            'user' => $alumnoCrear
        ], 200);
    }

    public function editarAlumno(Request $request) {
        $validator = Validator::make($request->all(), [
            'dniOriginal' => ['required', 'string', 'size:9', 'regex:/^[0-9]{8}[TRWAGMYFPDXBNJZSQVHLCKE]$/i'],
            'dniEditar' => ['required', 'string', 'size:9', 'regex:/^[0-9]{8}[TRWAGMYFPDXBNJZSQVHLCKE]$/i'],
            'nombreEditar' => ['required', 'string', 'min:6', 'max:80'],
            'puntosEditar' => ['required', 'numeric', 'between:0,50'],
        ],[
            'dniOriginal.required' => '- El DNI es obligatorio. <br><br>',
            'dniOriginal.string' => '- El DNI debe ser una cadena de texto. <br><br>',
            'dniOriginal.size' => '- El DNI debe tener una longitud de 9. <br><br>',
            'dniOriginal.regex' => '- El DNI tiene un formato incorrecto. <br><br>',
            'dniEditar.required' => '- El DNI es obligatorio. <br><br>',
            'dniEditar.string' => '- El DNI debe ser una cadena de texto. <br><br>',
            'dniEditar.size' => '- El DNI debe tener una longitud de 9. <br><br>',
            'dniEditar.regex' => '- El DNI tiene un formato incorrecto. <br><br>',
            'nombreEditar.required' => '- El nombre del alumno es obligatorio. <br><br>',
            'nombreEditar.string' => '- El nombre debe ser una cadena de texto. <br><br>',
            'nombreEditar.min' => '- El nombre debe tener 10 o más caracteres, se deben incluir apellidos. <br><br>',
            'nombreEditar.max' => '- El nombre es demasiado largo, no debe superar los 80 caracteres. <br><br>',
            'puntosEditar.required' => '- Los puntos son obligatorios. <br><br>',
            'puntosEditar.numeric' => '- El campo de puntos debe tener un valor numérico. <br><br>',
            'puntosEditar.between' => '- El valor de los puntos no puede ser inferior a 0 ni mayor a 50. <br><br>',
        ]);

        // Se devuelve la información de los errores si ha fallado
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Asignamos los datos recibidos por Request a variables
        $dniAlumnoOriginal = $request->dniOriginal;
        $listaCorreosAniadir = $request->correosAniadir;
        $listaCorreosEliminar = json_decode($request->correosEliminar);
        $dniEditar = $request->dniEditar;
        $nombreEditar = $request->nombreEditar;
        $puntosEditar = $request->puntosEditar;
        $alumnoEditar = Alumno::find($dniAlumnoOriginal);
        if (!($this->validarDNI($dniEditar))) {
            return response()->json([
                'success' => false,
                'errors' => ["- DNI con formato incorrecto. <br><br>"]
            ], 422);
        }

        // Revisar formato de correos añadidos 

        if ($dniEditar != $dniAlumnoOriginal) {
            $existeDNI = Alumno::select('*')->where('dni','=',$dniEditar)->get();
            if (count($existeDNI) > 0) {
                return response()->json([
                    'success' => false,
                    'errors' => ["- No creado, DNI ya registrado. <br><br>"]
                ], 422);
            } else {
                $alumnoEditar->dni = $dniEditar;
            }    
        }
        // Tras comprobar el dni asignamos el resto de valores antes de guardar
        $alumnoEditar->nombre = $nombreEditar;
        $alumnoEditar->puntos = $puntosEditar;
        $alumnoEditar->save();
        // Validamos los ids de los correos recibidos para eliminar y procedemos a eliminarlos
        if ($listaCorreosEliminar != null) {
            for ($i = 0; $i < count($listaCorreosEliminar); $i++) {
                if (!(is_numeric($listaCorreosEliminar[$i])) && $listaCorreosEliminar[$i] != null) {
                    return response()->json([
                        'success' => true,
                        'errors' => ["- Datos editados, ha habido un error al eliminar correos, por lo tanto, tampoco se han podido añadir. <br><br>"]
                    ], 200);
                }
                $correoEliminar = Correo::find($listaCorreosEliminar[$i]);
                $correoEliminar->delete();
            }
        }

        // Validamos los correos enviados antes de añadirlo, formateando el string recibido como array
        if ($listaCorreosAniadir != null) {
            for ($i = 0; $i < count($listaCorreosAniadir); $i++) {
                if (!(filter_var($listaCorreosAniadir[$i][0], FILTER_VALIDATE_EMAIL)) 
                && ($listaCorreosAniadir[$i][1] != "personal" || $listaCorreosAniadir[$i][1] != "tutor")) {
                    return response()->json([
                        'success' => true,
                        'errors' => ["- Datos editados, pero algunos de los correos no se han añadido por formato incorrecto. <br><br>"]
                    ], 200);
                }
                $nuevoCorreo = new Correo();
                $nuevoCorreo->correo = $listaCorreosAniadir[$i][0];
                $nuevoCorreo->tipo = $listaCorreosAniadir[$i][1];
                $nuevoCorreo->alumno_dni = $dniEditar;
                $nuevoCorreo->save();
            }
        }
        $alumnoEditar = Alumno::find($dniAlumnoOriginal);
        if ($puntosEditar == 0) {
            foreach ($alumnoEditar->correos as $correo) {
                Mail::to($correo->correo)->queue(new CorreoPuntosParte($alumnoEditar));
                }
            // Correo jefatura
            Mail::to('sergioggbb02@gmail.com')->queue(new CorreoPuntosParte($alumnoEditar));
        }

        return response()->json([
            'success' => true,
        ], 200);
    }

    public function eliminarAlumno(Request $request) {
        $validator = Validator::make($request->all(), [
            'dniEliminar' => ['required', 'string', 'size:9', 'regex:/^[0-9]{8}[TRWAGMYFPDXBNJZSQVHLCKE]$/i'],
        ],[
            'dniEliminar.required' => '- El DNI es obligatorio. <br><br>',
            'dniEliminar.string' => '- El DNI debe ser una cadena de texto. <br><br>',
            'dniEliminar.size' => '- El DNI debe tener una longitud de 9. <br><br>',
            'dniEliminar.regex' => '- El DNI tiene un formato incorrecto. <br><br>',
        ]);

        // Se devuelve la información de los errores si ha fallado
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $dniAlumno = $request->dniEliminar;
        // Nos aseguramos de eliminar las referencias antes de eliminar el alumno
        $correos = Correo::where('alumno_dni','=', $dniAlumno)->delete();
        $partes = AlumnoParte::select('*')->where('alumno_dni','=', $dniAlumno)->get();
        $partesEliminados = AlumnoParte::where('alumno_dni','=', $dniAlumno)->delete();
        $alumno = Alumno::where('dni','=', $dniAlumno)->delete();
        // Debemos asegurarnos de que los partes no sean colectivos para que no se eliminen
        if (count($partes) > 0) {
            foreach ($partes as $parte) {
                // Por cada parte comprobamos que queden relaciones en la tabla intermedia
                $parteId = $parte->parte_id;
                $comprobarNoMasAlumnos = AlumnoParte::select('*')->where('parte_id','=',$parteId)->get();
                // Si no quedan es que el parte no tiene más alumnos asociados a él, por lo que podemos eliminar
                // todas sus referencias, y a él mismo
                if (count($comprobarNoMasAlumnos) == 0) {
                    ParteConductanegativa::where('parte_id','=',$parteId)->delete();
                    Parte::where('id','=',$parteId)->delete();
                }
            }
        }
        return response()->json([
            'success' => true,
        ], 200);
    }

    public function obtenerTutores(Request $request) {
        $validator = Validator::make($request->all(), [
            'idCurso' => ['required', 'numeric', 'between:1,5000'],
        ],[
            'idCurso' => 'Problema al obtener los datos del curso. <br>',
        ]);

        // Se devuelve la información de los errores si ha fallado
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        $idCurso = $request->idCurso;

        $unidadProfesor = Unidad::select('unidades.nombre AS cursoNombre','profesors.nombre AS tutorNombre')->leftjoin('profesors','unidades.tutor_dni','=','profesors.dni')->where("id_curso","=",$idCurso)->get();

        $cursosTutores = [];

        for ($i = 0; $i < count($unidadProfesor); $i++) {
            if ($unidadProfesor[$i]->tutorNombre == null) {
                $cursosTutores[$i] = [$unidadProfesor[$i]->cursoNombre, "Sin tutor"];
            } else {
                $cursosTutores[$i] = [$unidadProfesor[$i]->cursoNombre, $unidadProfesor[$i]->tutorNombre];
            }
        }

        return response()->json([
            'success' => true,
            'cursosTutores' => $cursosTutores
        ], 200);
    }

    public function asignarTutor(Request $request) {
        $validator = Validator::make($request->all(), [
            'dniTutor' => ['required', 'string', 'size:9', 'regex:/^[0-9]{8}[TRWAGMYFPDXBNJZSQVHLCKE]$/i'],
            'idUnidad' => ['required', 'numeric', 'between:1,5000'],
        ],[
            'dniTutor' => '- Problema al obtener los datos del profesor. <br>',
            'idUnidad' => '- Problema al obtener los datos de la unidad. <br>',
        ]);
        
        // Se devuelve la información de los errores si ha fallado
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Asignamos los datos recibidos por Request a variables
        $dniTutor = $request->dniTutor;
        $idUnidad = $request->idUnidad;
        if (!($this->validarDNI($dniTutor))) {
            return response()->json([
                'success' => false,
                'errors' => ["- DNI con formato incorrecto. <br><br>"]
            ], 422);
        }

        
        $unidadComprobar = Unidad::select('*')->where('id','=',$idUnidad)->get();
        $profesorComprobar = Profesor::select('*')->where('dni','=',$dniTutor)->get();
        if (count($unidadComprobar) == 0 || count($profesorComprobar) == 0) {
            return response()->json([
                'success' => false,
                'errors' => ["- Problema al obtener los datos de los campos seleccionados, inténtelo de nuevo. <br>"]
            ], 422);
        }

        $unidadAsignar = $unidadComprobar[0];
        $unidadAsignar->tutor_dni = $dniTutor;
        $unidadAsignar->save();

        return response()->json([
            'success' => true,
        ], 200);
    }

    // Función importada de https://robertostory.com/blog/12/validacion-de-dni-nie-espanol-php
    public function validarDNI($value)
    {
        $pattern = "/^[XYZ]?\d{5,8}[A-Z]$/";
        $dni = strtoupper($value);
        if(preg_match($pattern, $dni))
        {
            $number = substr($dni, 0, -1);
            $number = str_replace('X', 0, $number);
            $number = str_replace('Y', 1, $number);
            $number = str_replace('Z', 2, $number);
            $dni = substr($dni, -1, 1);
            $start = $number % 23;
            $letter = 'TRWAGMYFPDXBNJZSQVHLCKET';
            $letter = substr('TRWAGMYFPDXBNJZSQVHLCKET', $start, 1);
            if($letter != $dni)
            {
              return false;
            } else {
              return true;
            }
        }else{
            return false;
        }
    }

}
