<?php

namespace Database\Factories;

use App\Models\Diagnostique;
use Illuminate\Database\Eloquent\Factories\Factory;

class DiagnostiqueFactory extends Factory
{
    protected $model = Diagnostique::class;

    public function definition(): array
    {
        return [
            'commentaire' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'date_heure' => now(),
            'priorite' => fake()->numberBetween(1, 5),
        ];
    }

    public function forPatient(int $compteId): self
    {
        return $this->state([
            'patient_id' => $compteId,
        ]);
    }
}
