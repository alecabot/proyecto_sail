<?php

namespace Database\Factories;

use App\Models\Alumno;
use App\Models\AlumnoPartes;
use App\Models\Parte;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AlumnoPartes>
 */
class AlumnoPartesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        do {
            $parte_id = Parte::inRandomOrder()->first()->id;
            $alumno_dni = Alumno::inRandomOrder()->first()->dni;
        } while ((AlumnoPartes::where('parte_id', $parte_id)->where('alumno_dni', $alumno_dni)->exists()));

        return [
            'parte_id' => $parte_id,
            'alumno_dni' => $alumno_dni,
        ];
    }
}
