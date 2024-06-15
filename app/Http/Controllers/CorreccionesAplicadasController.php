<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Correccionaplicada;
use Illuminate\Http\Request;

class CorreccionesAplicadasController extends Controller
{
    public function index() {
        $correcciones = Correccionaplicada::select("*")->where('habilitado','=',true);
        $tandaCorrecciones = $correcciones->paginate(5);
        return view('gestion.correcciones', ["correcciones" => $tandaCorrecciones]);
    }

    public function deshabilitadas() {
        $correcciones = Correccionaplicada::select("*")->where('habilitado','=',false);
        $tandaCorrecciones = $correcciones->paginate(5);
        return view('gestion.deshabilitados.correcciones', ["correcciones" => $tandaCorrecciones]);
    }

    public function crear(Request $request) {
        $request->validate([
            'nuevaCorreccion' => ['required', 'string', 'min:3', 'max:80'],
        ], [
            'nuevaCorreccion.requires' => 'La descripción de la corección es obligatoria. ',
            'nuevaCorreccion.string' => 'La descripción de la corección debe ser una cadena. ',
            'nuevaCorreccion.min' => 'La descripción de la corección debe tener al menos 3 caracteres. ',
            'nuevaCorreccion.max' => 'La descripción de la corección debe tener menos de 80 caracteres. ',
        ]);
        $correccion = Correccionaplicada::create([
            'descripcion' => $request['nuevaCorreccion'],
            'habilitado' => true,
        ]);
        return back()->with('success', 'Corrección creada con éxito');
    }

    public function editar(Request $request, $id) {
        $request->validate([
            'cambioCorreccion' => ['required', 'string', 'min:3', 'max:80'],
        ], [
            'cambioCorreccion.requires' => 'La descripción de la corección es obligatoria. ',
            'cambioCorreccion.string' => 'La descripción de la corección debe ser una cadena. ',
            'cambioCorreccion.min' => 'La descripción de la corección debe tener al menos 3 caracteres. ',
            'cambioCorreccion.max' => 'La descripción de la corección debe tener menos de 80 caracteres. ',
        ]);
        $nuevaDescripcion = $request->cambioCorreccion;
        if ($id > 0 && $id < 1000) {
            if (Correccionaplicada::find($id) != null) {
                $correccionEditar = Correccionaplicada::find($id);
                $correccionEditar->descripcion = $nuevaDescripcion;
                $correccionEditar->save();
                return back()->with('success', 'Corrección editada con éxito');
            }
        }
        return back()->with('success', 'Error al editar la corrección');
    }

    public function eliminar($id) {
        if ($id > 0 && $id < 1000) {
            if (Correccionaplicada::find($id) != null) {
                Correccionaplicada::destroy($id);
                return redirect('/gestion/correccionesaplicadas')->with('success', 'La correción se ha eliminado con éxito');
            }
            return redirect('/gestion/correccionesaplicadas')->with('success', 'La correción a eliminar no existe');
        }
        return redirect('/gestion/correccionesaplicadas')->with('success', 'Error al eliminar la correción');
    }

    public function habilitar(Request $request) {
        $request->validate([
            'id' => ['numeric', 'min:1', 'max:500'],
        ],[
            'id' => 'El identificador no es un valor numérico correcto. ',
        ]);
        $id = $request->id;
        if (count(Correccionaplicada::select('*')->where('id','=',$id)->get()) == 0) return back()->with('success', 'La corrección no existe');
        $correccion = Correccionaplicada::select('*')->where('id','=',$id)->get()[0];
        Correccionaplicada::where('id','=',$id)->update(['habilitado' =>  !($correccion->habilitado)]);
        if ($correccion->habilitado) return back()->with('success', 'Corrección deshabilitada');
        else return back()->with('success', 'Corrección habilitada');
    }
}
