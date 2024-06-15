<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Models;
use App\Models\Incidencia;
use Hamcrest\Type\IsNumeric;
use Illuminate\Http\Request;

use function PHPUnit\Framework\assertIsNumeric;

class IncidenciasController extends Controller
{
    public function index() {
        $incidencias = Incidencia::select("*")->where('habilitado','=',true);
        $tandaIncidencias = $incidencias->paginate(5);
        return view('gestion.incidencias', ["incidencias" => $tandaIncidencias]);
    }

    public function deshabilitadas() {
        $incidencias = Incidencia::select("*")->where('habilitado','=',false);
        $tandaIncidencias = $incidencias->paginate(5);
        return view('gestion.deshabilitados.incidencias', ["incidencias" => $tandaIncidencias]);
    }

    public function crear(Request $request) {
        $request->validate([
            'nuevaIncidencia' => ['required', 'string', 'min:3', 'max:80'],
        ], [
            'nuevaIncidencia.requires' => 'La descripción de la incidencia es obligatoria. ',
            'nuevaIncidencia.string' => 'La descripción de la incidencia debe ser una cadena. ',
            'nuevaIncidencia.min' => 'La descripción de la incidencia debe tener al menos 3 caracteres. ',
            'nuevaIncidencia.max' => 'La descripción de la incidencia debe tener menos de 80 caracteres. ',
        ]);
        $incidencia = Incidencia::create([
            'descripcion' => $request['nuevaIncidencia'],
            'habilitado' => true,
        ]);
        return back()->with('success', 'Incidencia creada con éxito');
    }

    public function editar(Request $request, $id) {
        $request->validate([
            'cambioIncidencia' => ['required', 'string', 'min:3', 'max:80'],
        ], [
            'cambioIncidencia.requires' => 'La descripción de la incidencia es obligatoria. ',
            'cambioIncidencia.string' => 'La descripción de la incidencia debe ser una cadena. ',
            'cambioIncidencia.min' => 'La descripción de la incidencia debe tener al menos 3 caracteres. ',
            'cambioIncidencia.max' => 'La descripción de la incidencia debe tener menos de 80 caracteres. ',
        ]);
        $nuevaDescripcion = $request->cambioIncidencia;
        if ($id > 0 && $id < 1000) {
            if (Incidencia::find($id) != null) {
                $incidenciaEditar = Incidencia::find($id);
                $incidenciaEditar->descripcion = $nuevaDescripcion;
                $incidenciaEditar->save();
                return back()->with('success', 'Incidencia editada con éxito');
            }
        }
        return back()->with('success', 'Error al editar la incidencia');
    }

    public function eliminar($id) {
        if ($id > 0 && $id < 1000) {
            if (Incidencia::find($id) != null) {
                Incidencia::destroy($id);
                return redirect('/gestion/incidencias')->with('success', 'La incidencia se ha eliminado con éxito');
            }
            return redirect('/gestion/incidencias')->with('success', 'La incidencia a eliminar no existe');
        }
        return redirect('/gestion/incidencias')->with('success', 'Error al eliminar la incidencia');
    }

    public function habilitar(Request $request) {
        $request->validate([
            'id' => ['numeric', 'min:1', 'max:500'],
        ],[
            'id' => 'El identificador no es un valor numérico correcto. ',
        ]);
        $id = $request->id;
        if (count(Incidencia::select('*')->where('id','=',$id)->get()) == 0) return back()->with('success', 'La incidencia no existe');
        $incidencia = Incidencia::select('*')->where('id','=',$id)->get()[0];
        Incidencia::where('id','=',$id)->update(['habilitado' =>  !($incidencia->habilitado)]);

        if ($incidencia->habilitado) return back()->with('success', 'Incidencia deshabilitada');
        else return back()->with('success', 'Incidencia habilitada');
    }

}
