<?php

namespace Database\Factories;

use App\Models\Alumno;
use App\Models\Correccionaplicada;
use App\Models\Incidencia;
use App\Models\Parte;
use App\Models\Profesor;
use App\Models\Tramohorario;
use Illuminate\Database\Eloquent\Factories\Factory;

class ParteFactory extends Factory
{
    protected $model = Parte::class;

    public function definition(): array
    {
        $correccionaplicadas_id = Correccionaplicada::inRandomOrder()->first()->id;
        $incidencia_id = Incidencia::inRandomOrder()->first()->id;

        return [
            'colectivo' => $this->faker->randomElement(['Si', 'No']),
            'profesor_dni' => Profesor::InRandomOrder()->first()->dni,
            'tramo_horario_id' => Tramohorario::InRandomOrder()->first()->id,
            'puntos_penalizados' => $this->faker->numberBetween(0, 3),
            'correccionaplicadas_id' => $correccionaplicadas_id,
            'incidencia_id' => $incidencia_id,
        ];
    }
}
