<?php

namespace Database\Factories;

use App\Models\Compte;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompteFactory extends Factory
{
    protected $model = Compte::class;

    public function definition(): array
    {
        return [
            'nom' => fake()->lastName(),
            'prenom' => fake()->firstName(),
            'date_naissance' => fake()->date(),
        ];
    }

    /**
     * Crée un user associé avant la création du compte
     */
    public function withUser(): self
    {
        return $this->state(function (array $attributes) {
            $user = User::factory()->create([
                'email' => strtolower(fake()->lastName()) . '.' . strtolower(fake()->firstName()) . '@example.com',
                'password' => bcrypt('password'),
            ]);

            return [
                'user_id' => $user->id,
            ];
        });
    }
}
