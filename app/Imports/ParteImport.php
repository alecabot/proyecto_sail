<?php

namespace App\Imports;

use App\Models\Alumno;
use App\Models\AnioAcademico;
use App\Models\Conductanegativa;
use App\Models\Correccionaplicada;
use App\Models\Incidencia;
use App\Models\Parte;
use App\Models\Profesor;
use App\Models\Tramohorario;
use App\Models\Unidad;
use App\Models\Curso;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ParteImport implements ToModel, WithHeadingRow
{

    public function model(array $row)
    {
        // Comprobar si la fila está vacía
        if (!array_filter($row)) {
            return null;
        }

        // Validar los datos
        $validator = Validator::make($row, [
            'Profesor' => 'required|in:' . implode(',', Profesor::all()->pluck('nombre')->toArray()),
            'colesctivo' => 'required|in:Si,No',
            'TramoHorario' => 'required|in:' . implode(',', Tramohorario::all()->pluck('nombre')->toArray()),
            'Alumno' => 'required|in:' . implode(',', Alumno::all()->pluck('nombre')->toArray()),
            'Puntos' => 'required|numeric',
            'Fecha' => 'required|date',
            'Incidencia' => 'required|in:' . implode(',', Incidencia::all()->pluck('descripcion')->toArray()),
            'ConductasNegativa' => 'required|in:' . implode(',', Conductanegativa::all()->pluck('descripcion')->toArray()),
            'CorrecionesAplicadas' => 'required|in:' . implode(',', Correccionaplicada::all()->pluck('descripcion')->toArray()),
        ]);

        if ($validator->fails()) {
            // Almacena los errores de validación en la sesión
            session()->flash('validation_errors', $validator->errors()->all());
            return null;
        }


        $fechaInput = $row['Fecha'];


        $fecha = Carbon::parse($fechaInput)->format('Y-m-d H:i');

        $profesor_dni = Profesor::where('nombre', $row['Profesor'])->first();
        $tramohorario = Tramohorario::where('nombre', $row['TramoHorario'])->first();
        $incidencia = Incidencia::where('descripcion', $row['Incidencia'])->first();
        $correcionesAplicadas = Correccionaplicada::where('descripcion', $row['CorreccionAplicada'])->first();



        // Crear el alumno
        Parte::updateOrCreate([
                'id' => $row['parte_id'],
                'profesor_dni' => $profesor_dni,
                'tramo_horario_id' => $tramohorario,
                'colectivo' => $row['colectivo'],
                'correccionaplicadas_id' => $correcionesAplicadas,
                'incidencia_id' => $incidencia,
                'created_at' => $fecha,
                'puntos_penalizados' => $row['Puntos'],
                'descripcion_detallada' => $row['CorreccionAplicada'],
            ]
        );
    }
}
