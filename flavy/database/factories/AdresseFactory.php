<?php

namespace Database\Factories;

use App\Models\Adresse;
use Illuminate\Database\Eloquent\Factories\Factory;

class AdresseFactory extends Factory
{
    protected $model = Adresse::class;

    public function definition()
    {
        $provinces = ['AB', 'BC', 'MB', 'NB', 'NL', 'NS', 'ON', 'PE', 'QC', 'SK', 'YT', 'NU', 'NT'];
        $postal_code = fake()->randomLetter() . fake()->randomDigit() . fake()->randomLetter() . ' ' . fake()->randomDigit() . fake()->randomLetter() . fake()->randomDigit();
        return [
            'code_postal' => $this->getRandomPostalCode(),
            'numero' => fake()->numberBetween(1, 9999),
            'province' => fake()->randomElement($provinces),
            'rue' => fake()->streetName(),
            'ville' => fake()->city(),
            'type' => fake()->randomElement(['home', 'work']),
        ];
    }

    private function getRandomPostalCode(): string
    {
        $letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $digits = '0123456789';
        $postal_code = '';
        for ($i = 0; $i < 3; $i++) {
            $postal_code .= $letters[rand(0, strlen($letters) - 1)];
            $postal_code .= $digits[rand(0, strlen($digits) - 1)];
        }
        return substr($postal_code, 0, 3) . ' ' . substr($postal_code, 3);
    }
}
