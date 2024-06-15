<?php

namespace App\Http\Controllers;

use App\Models\AnioAcademico;
use App\Models\Curso;
use App\Models\Profesor;
use App\Models\Unidad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
class TutoresController extends Controller
{
    public function index()
    {
        $anioAcademicos = AnioAcademico::all();
        $cursos = Curso::all();
        $unidades = Unidad::all();
        $profesores = Profesor::all()->where('habilitado','=',true);
        $this->renovarSesion();
        return view('gestion.tutores', ["anioAcademicos" => $anioAcademicos, "cursos" => $cursos,
        "unidades" => $unidades, "profesores" => $profesores]);
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
