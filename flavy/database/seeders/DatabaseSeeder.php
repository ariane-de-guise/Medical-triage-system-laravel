<?php

namespace Database\Seeders;

use App\Models\Adresse;
use App\Models\Compte;
use App\Models\Diagnostique;
use App\Models\Infirmier_Connecte;
use App\Models\Medecin_Connecte;
use App\Models\Telephone;
use App\Models\Triage;
use App\Models\Visite;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $roles = ['admin', 'medecin', 'infirmier', 'patient'];

        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        $comptes = Compte::factory(20)->withUser()->create();

        $admin = $comptes->shift();

        $nurses = $comptes->splice(0, 4);
        $doctors = $comptes->splice(0, 4);
        $patients = $comptes;

        $admin->user->assignRole('admin');

        foreach ($nurses as $compte) {
            $compte->user->assignRole('infirmier');
        }

        foreach ($doctors as $compte) {
            $compte->user->assignRole('medecin');
        }

        foreach ($patients as $compte) {
            $compte->user->assignRole('patient');
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

        $diagnostiques = [];
        foreach (range(1, 30) as $i) {
            $diag = Diagnostique::factory()
                ->for($patients->random(), 'patient') // relation belongsTo patient
                ->create();
            $diagnostiques[] = $diag;
        }

        foreach (range(1, 30) as $i) {
            Visite::create([
                'patient_id' => $patients->random()->id,
                'docteur_id' => $doctors->random()->id,
                'diagnostique_id' => $diagnostiques[array_rand($diagnostiques)]->id,
                'date_heure' => now(),
            ]);
        }

        $connectedNurses = collect();
        foreach ($nurses->take(2) as $nurse) {
            $connectedNurses->push(
                Infirmier_Connecte::create([
                    'compte_id' => $nurse->id
                ])
            );
        }

        $connectedDoctors = collect();
        foreach ($doctors->take(3) as $doctor) {
            $connectedDoctors->push(
                Medecin_Connecte::create([
                    'compte_id' => $doctor->id
                ])
            );
        }

        foreach ($patients as $patient) {
            $priority = rand(0, 4);
            Triage::enqueue($patient, $priority);
        }
    }
}
