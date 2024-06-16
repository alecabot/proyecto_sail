<?php

namespace App\Imports;

use App\Models\Alumno;
use App\Models\AnioAcademico;
use App\Models\Correo;
use App\Models\Unidad;
use App\Models\Curso;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AlumnosImport implements ToModel, WithHeadingRow
{
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
    public function model(array $row)
    {
        // Comprobar si la fila está vacía
        if (!array_filter($row)) {
            return null;
        }

        // Validar los datos
        $validator = Validator::make($row, [
            'dni' => ['required', 'unique:alumnos,dni', function ($attribute, $value, $fail) {
                if (!$this->validarDNI($value)) {
                    $fail($attribute.' is invalid.');
                }
            }],
            'nombre' => 'required|string',
            'nombre_curso' => 'required|string',
            'nombre_unidad' => 'required|string',
            'correo' => ['required', function ($attribute, $value, $fail) {
                $correos = explode(',', $value);
                foreach ($correos as $correo) {
                    $correoValidator = Validator::make(['correo' => $correo], [
                        'correo' => 'email',
                    ]);

                    if ($correoValidator->fails()) {
                        $fail($attribute.'debe contener direcciones de correo electrónico válidas, con una coma y un espacio.');
                    }
                }
            }],
        ]);

        if ($validator->fails()) {
            // Almacena los errores de validación en la sesión
            session()->flash('validation_errors', $validator->errors()->all());
            return null;
        }

        // Crear o encontrar el curso
        $year = date('Y');
        $nextYear = date('Y', strtotime('+1 year'));

        $anoacademico = AnioAcademico::firstOrCreate(['anio_academico' => $year . '-' . $nextYear]);

        $curso = Curso::firstOrCreate(['nombre' => $row['nombre_curso'], 'id_anio_academico' => $anoacademico->id]);

        // Crear o encontrar la unidad
        $unidad = Unidad::firstOrCreate(['nombre' => $row['nombre_unidad'], 'id_curso' => $curso->id]);

        $correos = explode(',', $row['correo']);
        // Crear el alumno
        $alumnoNuevo = Alumno::create([
            'dni' => $row['dni'],
            'nombre' => $row['nombre'],
            'correo' => $row['correo'],
            'puntos' => 12,
            'id_unidad' => $unidad->id,
        ]);

        for ($i = 0; $i < count($correos); $i++) {
            if ($i == 0) {
                Correo::create([
                    'alumno_dni' => $row['dni'],
                    'correo' => $correos[$i],
                    'tipo' => 'personal',
                ]);
            } else {
                Correo::create([
                    'alumno_dni' => $row['dni'],
                    'correo' => $correos[$i],
                    'tipo' => 'tutor',
                ]);
            }

        }

        return $alumnoNuevo;
    }
}
