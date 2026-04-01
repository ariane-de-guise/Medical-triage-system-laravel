<?php

namespace Database\Seeders;

use App\Models\Adresse;
use App\Models\Compte;
use App\Models\Diagnostique;
use App\Models\Telephone;
use App\Models\Visite;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1️⃣ Crée les rôles
        $roles = ['admin', 'medecin', 'infirmier', 'patient'];
        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        // 2️⃣ Crée des comptes avec utilisateurs associés
        $comptes = Compte::factory(20)->withUser()->create();

        // Assigne un rôle aléatoire à chaque user
        foreach ($comptes as $compte) {
            $compte->user->assignRole($roles[array_rand($roles)]);
        }

        // 3️⃣ Crée les adresses et les associe
        $adresses = Adresse::factory(30)->create();
        foreach ($comptes as $compte) {
            $compte->adresses()->attach(
                $adresses->random(2)->pluck('id'),
                ['type' => 'home']
            );
        }

        // 4️⃣ Crée les téléphones et les associe
        $telephones = Telephone::factory(30)->create();
        foreach ($comptes as $compte) {
            $compte->telephones()->attach(
                $telephones->random(2)->pluck('id')
            );
        }

        // 5️⃣ Crée les diagnostiques pour les patients
        $patients = $comptes->filter(fn($c) => $c->user->hasRole('patient'));
        $diagnostiques = [];
        foreach (range(1, 30) as $i) {
            $diag = Diagnostique::factory()
                ->for($patients->random(), 'patient') // relation belongsTo patient
                ->create();
            $diagnostiques[] = $diag;
        }

        // 6️⃣ Crée les visites avec patients, médecins et diagnostiques
        $doctors = $comptes->filter(fn($c) => $c->user->hasRole('medecin'));
        foreach (range(1, 30) as $i) {
            Visite::create([
                'patient_id' => $patients->random()->id,
                'docteur_id' => $doctors->random()->id,
                'diagnostique_id' => $diagnostiques[array_rand($diagnostiques)]->id,
                'date_heure' => now(),
            ]);
        }
    }
}
