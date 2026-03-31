<?php

namespace Database\Seeders;

use App\Models\Adresse;
use App\Models\Compte;
use App\Models\Diagnostique;
use App\Models\Telephone;
use App\Models\User;
use App\Models\Visite;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create comptes (with users automatically)
        $comptes = Compte::factory(20)->create();

        // Split doctors and patients
        $patients = $comptes->where('type', 0);
        $doctors = $comptes->where('type', 1);

        // Adresses
        $adresses = Adresse::factory(30)->create();

        foreach ($comptes as $compte) {
            $compte->adresses()->attach(
                $adresses->random(2)->pluck('id'),
                ['type' => 'home']
            );
        }

        // Diagnostiques
        $diagnostiques = Diagnostique::factory(30)->make()->each(function ($diag) use ($patients) {
            $diag->patient_id = $patients->random()->user_id;
            $diag->save();
        });

        // Visites
        foreach (range(1, 30) as $i) {
            Visite::create([
                'patient_id' => $patients->random()->id,
                'docteur_id' => $doctors->random()->id,
                'diagnostique_id' => $diagnostiques->random()->id,
                'date_heure' => now(),
            ]);
        }

        $telephones = Telephone::factory(30)->create();

        foreach ($comptes as $compte) {
            $compte->telephones()->attach(
                $telephones->random(2)->pluck('id')
            );
        }
    }
}
