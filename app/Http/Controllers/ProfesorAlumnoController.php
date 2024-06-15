<?php

namespace App\Http\Controllers;

use App\DataTables\AlumnoDataTable;
use App\Models\Alumno;
use App\Models\AlumnoParte;
use App\Models\AnioAcademico;
use App\Models\Correo;
use App\Models\Parte;
use App\Models\ParteConductanegativa;
use App\Models\ParteCorreccionsaplicada;
use App\Models\ParteIncidencia;
use App\Models\Profesor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ProfesorAlumnoController extends Controller
{
    public function index(AlumnoDataTable $dataTable, Request $request) {
        $request->validate([
            'page' => ['numeric', 'min:1', 'max:100'],
        ],[
            'page' => 'Error de paginación, vuelva a cargar la página. ',
        ]);
        $this->renovarSesion();
        $anoAcademico = AnioAcademico::all();
        $profesores = Profesor::select("*")->where('habilitado','=',true)->orderBy('nombre');
        $tandaProfesores = $profesores->paginate(5);
        $paginaActual = $request->page;
        if ($paginaActual != null) {
            return $dataTable->render('gestion.profesoralumno', ['anoAcademico' => $anoAcademico, "profesores" => $tandaProfesores, "paginaProfesor" => $paginaActual]);
        } else return $dataTable->render('gestion.profesoralumno', ['anoAcademico' => $anoAcademico, "profesores" => $tandaProfesores]);
    }

    public function deshabilitados() {
        $profesores = Profesor::select("*")->where('habilitado','=',false)->orderBy('nombre');
        $tandaProfesores = $profesores->paginate(5);
        $this->renovarSesion();
        return view('gestion.deshabilitados.profesoralumno', ["profesores" => $tandaProfesores]);
    }

    public function crearProfesor(Request $request) {
        $request->validate([
            'dniProfesor' => ['required', 'string', 'size:9', 'regex:/^[0-9]{8}[TRWAGMYFPDXBNJZSQVHLCKE]$/i'],
            'nombreProfesor' => ['required', 'string', 'min:10', 'max:80'],
            'telefonoProfesor' => ['required', 'numeric', 'between:000000001,999999999'],
            'emailProfesor' => ['required', 'email:filter'],
        ],[
            'dniProfesor.required' => 'El DNI es obligatorio. ',
            'dniProfesor.string' => 'El DNI debe ser una cadena de texto. ',
            'dniProfesor.size' => 'El DNI debe tener una longitud de 9. ',
            'dniProfesor.regex' => 'El DNI tiene un formato incorrecto. ',
            'nombreProfesor.required' => 'El nombre del profesor es obligatorio. ',
            'nombreProfesor.string' => 'El nombre debe ser una cadena de texto. ',
            'nombreProfesor.min' => 'El nombre debe tener 10 o más caracteres, se deben incluir apellidos. ',
            'nombreProfesor.max' => 'El nombre es demasiado largo, no debe superar los 80 caracteres. ',
            'telefonoProfesor.required' => 'El teléfono es obligatorio. ',
            'telefonoProfesor.numeric' => 'El teléfono debe ser numérico (9 dígitos). ',
            'telefonoProfesor.between' => 'El teléfono debe ser una serie de 9 dígitos consecutivos. ',
            'emailProfesor.required' => 'El correo es obligatorio. ',
            'emailProfesor.email' => 'El formato del correo no es correcto. ',
        ]);
        $dniNuevoProfesor = $request['dniProfesor'];
        if (!($this->validarDNI($dniNuevoProfesor))) {
            return back()->with('success', 'DNI con formato incorrecto');
        }
        $existeDNI = Profesor::select('*')->where('dni','=',$dniNuevoProfesor)->get();
        if (count($existeDNI) > 0) {
                return back()->with('success', 'No creado, DNI ya existente');
        }
        $profesor = Profesor::create([
            'dni' => $dniNuevoProfesor,
            'nombre' => $request['nombreProfesor'],
            'telefono' => $request['telefonoProfesor'],
            'correo' => $request['emailProfesor'],
            'habilitado' => true,
        ]);
        $this->renovarSesion();
        return back()->with('success', 'Profesor creado con éxito');
    }

    public function editarProfesor(Request $request) {
        $request->validate([
            'editarProfesorDniOriginal' => ['required', 'string', 'size:9', 'regex:/^[0-9]{8}[TRWAGMYFPDXBNJZSQVHLCKE]$/i'],
            'editarProfesorDni' => ['required', 'string', 'size:9', 'regex:/^[0-9]{8}[TRWAGMYFPDXBNJZSQVHLCKE]$/i'],
            'editarProfesorNombre' => ['required', 'string', 'min:6', 'max:80'],
            'editarProfesorTelefono' => ['required', 'numeric', 'between:000000001,999999999'],
            'editarProfesorCorreo' => ['required', 'email:filter'],
        ],[
            'editarProfesorDniOriginal.required' => 'El DNI es obligatorio. ',
            'editarProfesorDniOriginal.string' => 'El DNI debe ser una cadena de texto. ',
            'editarProfesorDniOriginal.size' => 'El DNI debe tener una longitud de 9. ',
            'editarProfesorDniOriginal.regex' => 'El DNI tiene un formato incorrecto. ',
            'editarProfesorDni.required' => 'El DNI es obligatorio. ',
            'editarProfesorDni.string' => 'El DNI debe ser una cadena de texto. ',
            'editarProfesorDni.size' => 'El DNI debe tener una longitud de 9. ',
            'editarProfesorDni.regex' => 'El DNI tiene un formato incorrecto. ',
            'editarProfesorNombre.required' => 'El nombre del profesor es obligatorio. ',
            'editarProfesorNombre.string' => 'El nombre debe ser una cadena de texto. ',
            'editarProfesorNombre.min' => 'El nombre debe tener 10 o más caracteres, se deben incluir apellidos. ',
            'editarProfesorNombre.max' => 'El nombre es demasiado largo, no debe superar los 80 caracteres. ',
            'editarProfesorTelefono.required' => 'El teléfono es obligatorio. ',
            'editarProfesorTelefono.numeric' => 'El teléfono debe ser numérico (9 dígitos). ',
            'editarProfesorTelefono.between' => 'El teléfono debe ser una serie de 9 dígitos consecutivos. ',
            'editarProfesorCorreo.required' => 'El correo es obligatorio. ',
            'editarProfesorCorreo.email' => 'El formato del correo no es correcto. ',
        ]);
        $dniEditar = $request->editarProfesorDni;
        if (!($this->validarDNI($dniEditar))) {
            return back()->with('success', 'DNI con formato incorrecto');
        }
        $dniProfesorOriginal = $request->editarProfesorDniOriginal;
        if (count(Profesor::select('*')->where('dni','=',$dniProfesorOriginal)->get()) == 0) {
            return back()->with('success', 'Error al obtener DNI, pruebe a refrescar la página');
        }
        $profesorEditar = Profesor::select('*')->where('dni','=',$dniProfesorOriginal)->get()[0];
        if ($dniEditar != $dniProfesorOriginal) {
            $existeDNI = Profesor::select('*')->where('dni','=',$dniEditar)->get();
            if (count($existeDNI) > 0) {
                return back()->with('success', 'No editado, DNI ya registrado');
            } else {
                $profesorEditar->dni = $dniEditar;
            }    
        }
        $profesorEditar->nombre = $request->editarProfesorNombre;
        $profesorEditar->telefono = $request->editarProfesorTelefono;
        $profesorEditar->correo = $request->editarProfesorCorreo;
        $profesorEditar->save();
        $this->renovarSesion();
        return back()->with('success', 'Profesor editado con éxito');
    }

    public function eliminarProfesor($dni) {
        if (!($this->validarDNI($dni))) {
            return back()->with('success', 'DNI con formato incorrecto');
        }
        if (count(Profesor::where('dni','=', $dni)->get()) == 0) {
            return back()->with('success', 'Error al eliminar profesor');
        }
        Profesor::where('dni','=', $dni)->delete();
        $this->renovarSesion();
        return back()->with('success', 'Profesor eliminado');
    }

    public function reinciarPuntos() {
        // Update general mediante Eloquent para todos los puntos
        Alumno::query()->where('puntos','<',12)->update([
            'puntos' => 12,
        ]);
        return back()->with('success', 'Los puntos de todos los alumnos inferiores a 12 se han restaurado');
    }

    public function habilitar($dni) {
        if (!($this->validarDNI($dni))) return back()->with('success', 'DNI incorrecto');
        $dniProfesor = $dni;
        if (count(Profesor::select('*')->where('dni','=',$dniProfesor)->get()) == 0) return back()->with('success', 'El profesor no existe');
        $profesor = Profesor::select('*')->where('dni','=',$dniProfesor)->get()[0];
        Profesor::where('dni','=',$dniProfesor)->update(['habilitado' =>  !($profesor->habilitado)]);
        $this->renovarSesion();
        if ($profesor->habilitado) return back()->with('success', 'Profesor deshabilitado');
        else return back()->with('success', 'Profesor habilitado');
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

    public function renovarSesion() {
        $usuarioActual = Auth::user();
        $rol = $usuarioActual->getRoleNames()[0];
        if ($rol == "jefatura" && Session::get('TokenApi') == "") {
            $tokenPre = $usuarioActual->currentAccessToken();
            if ($tokenPre) $tokenPre->delete();
            $tokenAuth = $usuarioActual->createToken('ApiToken')->plainTextToken;
            Session::put("TokenApi", $tokenAuth);
        }
    }

}
