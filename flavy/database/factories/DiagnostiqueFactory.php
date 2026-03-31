<?php

namespace Database\Factories;

use App\Models\Diagnostique;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DiagnostiqueFactory extends Factory
{
    protected $model = Diagnostique::class;

    public function definition(): array
    {
        return [
            'patient_id' => User::factory(),
            'commentaire' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'date_heure' => now(),
            'priorite' => fake()->numberBetween(1, 5),
        ];
    }
}
