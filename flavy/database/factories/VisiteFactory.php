<?php

namespace Database\Factories;

use App\Models\Compte;
use App\Models\Diagnostique;
use App\Models\Visite;
use Illuminate\Database\Eloquent\Factories\Factory;

class VisiteFactory extends Factory
{
    protected $model = Visite::class;

    public function definition(): array
    {
        return [
            'patient_id' => Compte::factory(),
            'docteur_id' => Compte::factory(),
            'diagnostique_id' => Diagnostique::factory(),
            'date_heure' => now(),
        ];
    }
}
