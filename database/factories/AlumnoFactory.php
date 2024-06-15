<?php

namespace Database\Factories;

use App\Models\Alumno;
use App\Models\Unidad;
use Illuminate\Database\Eloquent\Factories\Factory;

class AlumnoFactory extends Factory
{
    protected $model = Alumno::class;

    public function definition(): array
    {
        $prefijo = rand(10000000, 99999999);
        $posibleLetraCadena = "TRWAGMYFPDXBNJZSQVHLCKE";
        $letraDniIndex = $prefijo % 23;
        $dni = $prefijo . ($posibleLetraCadena[$letraDniIndex]);
        return [
            'id_unidad' => Unidad::InRandomOrder()->first()->id,
            'dni' => $dni,
            'nombre' => $this->faker->name(),
            'puntos' => $this->faker->numberBetween(0, 12),
        ];
    }
}
