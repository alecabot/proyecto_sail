<?php

namespace Database\Factories;

use App\Models\Profesor;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProfesorFactory extends Factory
{
    protected $model = Profesor::class;

    public function definition(): array
    {
        $prefijo = rand(10000000, 99999999);
        $posibleLetraCadena = "TRWAGMYFPDXBNJZSQVHLCKE";
        $letraDniIndex = $prefijo % 23;
        $dni = $prefijo . ($posibleLetraCadena[$letraDniIndex]);
        return [
            'dni' => $dni,
            'nombre' => $this->faker->name(),
            'telefono' => $this->faker->numerify('#########'),
            'correo' => $this->faker->email(),
            'habilitado' => $this->faker->boolean(),
        ];
    }
}
