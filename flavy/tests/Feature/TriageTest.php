<?php

namespace Tests\Feature;

use App\Models\Compte;
use App\Models\Triage;
use App\Models\Infirmier_Connecte;
use App\Models\Medecin_Connecte;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class TriageTest extends TestCase
{
    use RefreshDatabase;

    private function createInfirmiers(int $count = 2)
    {
        return Compte::factory($count)->create()->map(function ($compte) {
            return Infirmier_Connecte::create([
                'compte_id' => $compte->id
            ]);
        });
    }

    private function createMedecins(int $count = 2)
    {
        return Compte::factory($count)->create()->map(function ($compte) {
            return Medecin_Connecte::create([
                'compte_id' => $compte->id
            ]);
        });
    }

    /** @test */
    public function it_can_enqueue_patient_with_infirmier()
    {
        $this->createInfirmiers(1);

        $patient = Compte::factory()->create();

        Triage::enqueue($patient, 0);

        $this->assertDatabaseCount('triages', 1);
    }

    /** @test */
    public function it_can_enqueue_patient_with_medecin()
    {
        $this->createMedecins(1);

        $patient = Compte::factory()->create();

        Triage::enqueue($patient, 1);

        $this->assertDatabaseCount('triages', 1);
    }

    /** @test */
    public function it_assigns_least_busy_infirmier()
    {
        $infirmiers = $this->createInfirmiers(2);

        $p1 = Compte::factory()->create();
        $p2 = Compte::factory()->create();

        Triage::enqueue($p1, 0);
        Triage::enqueue($p2, 0);

        $triages = Triage::all();

        $this->assertCount(2, $triages);
        $this->assertNotNull($triages[0]->infirmier_connecte_id);
        $this->assertNotNull($triages[1]->infirmier_connecte_id);
    }

    /** @test */
    public function it_orders_by_priority()
    {
        $this->createInfirmiers(1);

        $low = Compte::factory()->create();
        $high = Compte::factory()->create();

        Triage::enqueue($low, 1);
        Triage::enqueue($high, 0);

        $first = Triage::orderBy('priority')->first();

        $this->assertEquals($high->id, $first->compte_id);
    }

    /** @test */
    public function it_can_dequeue_patient()
    {
        $this->createInfirmiers(1);

        $patient = Compte::factory()->create();

        Triage::enqueue($patient, 0);
        Triage::dequeue($patient);

        $this->assertDatabaseCount('triages', 0);
    }

    /** @test */
    public function it_can_peek_patient_for_infirmier()
    {
        $infCompte = Compte::factory()->create();

        Infirmier_Connecte::create([
            'compte_id' => $infCompte->id
        ]);

        $patient = Compte::factory()->create();

        Triage::enqueue($patient, 0);

        $result = Triage::peek(0, $infCompte);

        $this->assertEquals($patient->id, $result->id);
    }

    /** @test */
    public function it_rebalances_when_new_infirmier_logs_in()
    {
        $inf1 = Compte::factory()->create();
        Infirmier_Connecte::create(['compte_id' => $inf1->id]);

        $patients = Compte::factory(6)->create();

        foreach ($patients as $p) {
            Triage::enqueue($p, 0);
        }

        // add second infirmier → should rebalance
        $inf2 = Compte::factory()->create();
        (new Infirmier_Connecte())->login($inf2);

        $inf3 = Compte::factory()->create();
        (new Infirmier_Connecte())->login($inf3);

        $counts = Infirmier_Connecte::withCount('triages')->pluck('triages_count');

        $this->assertTrue($counts->max() - $counts->min() <= 1);
    }

    /** @test */
    public function it_rebalances_when_infirmier_logs_out()
    {
        $infs = $this->createInfirmiers(3);

        $patients = Compte::factory(6)->create();

        foreach ($patients as $p) {
            Triage::enqueue($p, 0);
        }

        // logout one infirmier
        (new Infirmier_Connecte())->logout($infs[0]->compte);

        $remaining = Infirmier_Connecte::first();

        $this->assertEquals(3, $remaining->triages()->count());
    }

    /** @test */
    public function peek_throws_exception_if_out_of_bounds()
    {
        $infCompte = Compte::factory()->create();

        Infirmier_Connecte::create([
            'compte_id' => $infCompte->id
        ]);

        $this->expectException(\OutOfBoundsException::class);

        Triage::peek(0, $infCompte);
    }

    /** @test */
    public function enqueue_fails_if_no_practitioner()
    {
        Log::shouldReceive('error')
            ->once()
            ->withArgs(function ($message, $context) {
                return $message === 'Triage enqueue failed'
                    && isset($context['error'])
                    && str_contains($context['error'], 'No practitioner available');
            });

        $patient = Compte::factory()->create();

        Triage::enqueue($patient, 0);
    }
}
