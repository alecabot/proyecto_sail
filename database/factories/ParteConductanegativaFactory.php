<?php

namespace Database\Factories;

use App\Models\Conductanegativa;
use App\Models\Parte;
use App\Models\ParteConductanegativa;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ParteConductanegativaFactory extends Factory
{
    protected $model = ParteConductanegativa::class;

    public function definition(): array
    {

        do {
            $parte_id = Parte::inRandomOrder()->first()->id;
            $conducta_negativa_id = Conductanegativa::inRandomOrder()->first()->id;
        } while ((ParteConductanegativa::where('parte_id', $parte_id)->where('conductanegativas_id', $conducta_negativa_id)->exists()));

        return [
            'parte_id' => $parte_id,
            'conductanegativas_id' => $conducta_negativa_id,
        ];
    }
}
