<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Conductanegativa;
use Illuminate\Http\Request;

class ConductasNegativasController extends Controller
{
    public function index() {
        $conductas = Conductanegativa::select("*")->where('habilitado','=',true);
        $tandaConductas = $conductas->paginate(5);
        return view('gestion.negativas', ["conductas" => $tandaConductas]);
    }

    public function deshabilitadas() {
        $conductas = Conductanegativa::select("*")->where('habilitado','=',false);
        $tandaConductas = $conductas->paginate(5);
        return view('gestion.deshabilitados.negativas', ["conductas" => $tandaConductas]);
    }

    public function crear(Request $request) {
        $request->validate([
            'nuevaConducta' => ['required', 'string', 'min:3', 'max:80'],
            'nuevaConductaTipo' => ['required', 'string', 'in:Contraria,Grave'],
        ], [
            'nuevaConducta.requires' => 'La descripción de la conducta es obligatoria. ',
            'nuevaConducta.string' => 'La descripción de la conducta debe ser una cadena. ',
            'nuevaConducta.min' => 'La descripción de la conducta debe tener al menos 3 caracteres. ',
            'nuevaConducta.max' => 'La descripción de la conducta debe tener menos de 80 caracteres. ',
            'nuevaConductaTipo.requires' => 'El tipo de la conducta es obligatorio. ',
            'nuevaConductaTipo.string' => 'El tipo de la conducta debe ser una cadena. ',
            'nuevaConductaTipo.in' => 'El tipo de la conducta debe ser Contraria o Grave. ',
        ]);
        $conducta = Conductanegativa::create([
            'descripcion' => $request['nuevaConducta'],
            'tipo' => $request['nuevaConductaTipo'],
            'habilitado' => true,
        ]);
        return back()->with('success', 'Conducta Negativa creada con éxito');
    }

    public function editar(Request $request, $id) {
        $request->validate([
            'cambioConducta' => ['required', 'string', 'min:3', 'max:80'],
            'cambioConductaTipo' => ['required', 'string', 'in:Contraria,Grave'],
        ], [
            'cambioConducta.requires' => 'La descripción de la conducta es obligatoria. ',
            'cambioConducta.string' => 'La descripción de la conducta debe ser una cadena. ',
            'cambioConducta.min' => 'La descripción de la conducta debe tener al menos 3 caracteres. ',
            'cambioConducta.max' => 'La descripción de la conducta debe tener menos de 80 caracteres. ',
            'cambioConductaTipo.requires' => 'El tipo de la conducta es obligatorio. ',
            'cambioConductaTipo.string' => 'El tipo de la conducta debe ser una cadena. ',
            'cambioConductaTipo.in' => 'El tipo de la conducta debe ser Contraria o Grave. ',
        ]);
        $nuevaDescripcion = $request->cambioConducta;
        $nuevaDescripcionTipo = $request->cambioConductaTipo;
        if ($id > 0 && $id < 1000) {
            if (Conductanegativa::find($id) != null) {
                $conductaEditar = Conductanegativa::find($id);
                $conductaEditar->descripcion = $nuevaDescripcion;
                $conductaEditar->tipo = $nuevaDescripcionTipo;
                $conductaEditar->save();
                return back()->with('success', 'Conducta negativa editada con éxito');
            }
        }
        return back()->with('success', 'Error al editar la conducta');
    }

    public function eliminar($id) {
        if ($id > 0 && $id < 1000) {
            if (Conductanegativa::find($id) != null) {
                Conductanegativa::destroy($id);
                return redirect('/gestion/conductasnegativas')->with('success', 'La conducta negativa se ha eliminado con éxito');
            }
            return redirect('/gestion/conductasnegativas')->with('success', 'La conducta negativa a eliminar no existe');
        }
        return redirect('/gestion/conductasnegativas')->with('success', 'Error al eliminar la conducta negativa');
    }

    public function habilitar(Request $request) {
        $request->validate([
            'id' => ['numeric', 'min:1', 'max:500'],
        ],[
            'id' => 'El identificador no es un valor numérico correcto. ',
        ]);
        $id = $request->id;
        if (count(Conductanegativa::select('*')->where('id','=',$id)->get()) == 0) return back()->with('success', 'La conducta no existe');
        $conducta = Conductanegativa::select('*')->where('id','=',$id)->get()[0];
        Conductanegativa::where('id','=',$id)->update(['habilitado' =>  !($conducta->habilitado)]);

        if ($conducta->habilitado) return back()->with('success', 'Conducta Negativa deshabilitada');
        else return back()->with('success', 'Conducta Negativa habilitada');
    }

}
