<?php

namespace Database\Factories;

use App\Models\Alumno;
use App\Models\Correo;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class CorreoFactory extends Factory
{
    protected $model = Correo::class;

    public function definition(): array
    {
        return [
            'correo' => $this->faker->unique()->safeEmail,
            'alumno_dni' => Alumno::inRandomOrder()->first()->dni,
            'tipo' => $this->faker->randomElement(['personal', 'tutor']),
        ];
    }
}
