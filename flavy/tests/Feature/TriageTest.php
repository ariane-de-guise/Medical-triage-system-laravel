<?php

namespace Tests\Feature;

use App\Models\Compte;
use App\Models\Triage;
use App\Models\Infirmier_Connecte;
use App\Models\Medecin_Connecte;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use OutOfBoundsException;
use Tests\TestCase;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertNotNull;

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
        Infirmier_Connecte::login($inf2);

        $inf3 = Compte::factory()->create();
        Infirmier_Connecte::login($inf3);

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

    public function relation_medecin_compte(): void
    {
        $meds = $this->createMedecins(3);

        assertNotNull($meds[0]->compte);
    }

    /** @test */
    public function peek_throws_exception_if_out_of_bounds()
    {
        $infCompte = Compte::factory()->create();

        Infirmier_Connecte::create([
            'compte_id' => $infCompte->id
        ]);

        $this->expectException(OutOfBoundsException::class);

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

    public function test_enqueued_returns_true_if_patient_is_in_queue()
    {
        $compte = Compte::factory()->create();

        Triage::create([
            'compte_id' => $compte->id,
            'priority' => 1,
            'position' => 0,
        ]);

        $this->assertTrue(Triage::enqueued($compte));
    }

    public function test_enqueued_returns_false_if_patient_not_in_queue()
    {
        $compte = Compte::factory()->create();

        $this->assertFalse(Triage::enqueued($compte));
    }

    public function test_queue_position_returns_correct_index()
    {
        $compte1 = Compte::factory()->create();
        $compte2 = Compte::factory()->create();
        $compte3 = Compte::factory()->create();

        $inf1 = Compte::factory()->create();
        $infirmier = Infirmier_Connecte::create(['compte_id' => $inf1->id]);

        Triage::create([
            'compte_id' => $compte1->id,
            'priority' => 1,
            'position' => 0,
            'infirmier_connecte_id' => $infirmier->id,
        ]);

        Triage::create([
            'compte_id' => $compte2->id,
            'priority' => 1,
            'position' => 1,
            'infirmier_connecte_id' => $infirmier->id,
        ]);

        Triage::create([
            'compte_id' => $compte3->id,
            'priority' => 1,
            'position' => 2,
            'infirmier_connecte_id' => $infirmier->id,
        ]);

        $this->assertEquals(2, Triage::queuePosition($compte2));
    }

    public function test_queue_position_returns_minus_one_if_not_found()
    {
        $compte = Compte::factory()->create();

        $this->assertEquals(-1, Triage::queuePosition($compte));
    }

    public function test_enqueued_true_and_false()
    {
        $compte = Compte::factory()->create();

        $this->assertFalse(Triage::enqueued($compte));

        Triage::create([
            'compte_id' => $compte->id,
            'priority' => 1,
            'position' => 0,
        ]);

        $this->assertTrue(Triage::enqueued($compte));
    }

    public function test_queue_position_with_infirmier_and_medecin()
    {
        $compte = Compte::factory()->create();

        $inf1 = Compte::factory()->create();
        $infirmier = Infirmier_Connecte::create(['compte_id' => $inf1->id]);

        $med = Compte::factory()->create();
        $medecin = Medecin_Connecte::create(['compte_id' => $med->id]);

        // infirmier branch
        Triage::create([
            'compte_id' => $compte->id,
            'priority' => 1,
            'position' => 0,
            'infirmier_connecte_id' => $infirmier->id,
        ]);

        $this->assertEquals(1, Triage::queuePosition($compte));

        // medecin branch
        Triage::create([
            'compte_id' => $compte->id,
            'priority' => 1,
            'position' => 0,
            'medecin_connecte_id' => $medecin->id,
        ]);

        $this->assertNotEquals(-1, Triage::queuePosition($compte));
    }

    public function test_queue_position_returns_minus_one()
    {
        $compte = Compte::factory()->create();

        $this->assertEquals(-1, Triage::queuePosition($compte));
    }

    public function test_queue_position_respects_priority_and_position()
    {
        $compte1 = Compte::factory()->create();
        $compte2 = Compte::factory()->create();

        $inf1 = Compte::factory()->create();
        $inf = Infirmier_Connecte::create(['compte_id' => $inf1->id]);

        Triage::create([
            'compte_id' => $compte2->id,
            'priority' => 2,
            'position' => 0,
            'infirmier_connecte_id' => $inf->id,
        ]);

        Triage::create([
            'compte_id' => $compte1->id,
            'priority' => 1,
            'position' => 0,
            'infirmier_connecte_id' => $inf->id,
        ]);

        // compte1 should be first because lower priority value
        $this->assertEquals(1, Triage::queuePosition($compte1));
    }

    public function test_triage_relations()
    {
        $compte = Compte::factory()->create();
        $compte2 = Compte::factory()->create();

        $inf1 = Compte::factory()->create();
        $inf = Infirmier_Connecte::create(['compte_id' => $inf1->id]);

        $med1 = Compte::factory()->create();
        $med = Medecin_Connecte::create(['compte_id' => $med1->id]);

        $triage = Triage::create([
            'compte_id' => $compte->id,
            'infirmier_connecte_id' => $inf->id,
            'priority' => 1,
            'position' => 0,
        ])->fresh();

        $triage2 = Triage::create([
            'compte_id' => $compte2->id,
            'medecin_connecte_id' => $med->id,
            'priority' => 1,
            'position' => 0,
        ])->fresh();

        $this->assertInstanceOf(Compte::class, $triage->compte);
        $this->assertInstanceOf(Infirmier_Connecte::class, $triage->infirmier);

        $this->assertInstanceOf(Medecin_Connecte::class, $triage2->medecin);
    }

    public function test_triage_medecin_relation()
    {
        $compte = Compte::factory()->create();

        $medCompte = Compte::factory()->create();
        $med = Medecin_Connecte::create(['compte_id' => $medCompte->id]);

        $triage = Triage::create([
            'compte_id' => $compte->id,
            'medecin_connecte_id' => $med->id,
            'priority' => 1,
            'position' => 0,
        ])->fresh();

        $this->assertInstanceOf(Medecin_Connecte::class, $triage->medecin);
    }

    public function test_infirmier_has_triages()
    {
        $inf1 = Compte::factory()->create();
        $inf = Infirmier_Connecte::create(['compte_id' => $inf1->id]);

        Triage::create([
            'compte_id' => Compte::factory()->create()->id,
            'infirmier_connecte_id' => $inf->id,
            'priority' => 1,
            'position' => 0,
        ]);

        $this->assertCount(1, $inf->triages);
    }

    public function test_enqueue_assigns_infirmier_and_medecin()
    {
        $compte = Compte::factory()->create();

        $inf1 = Compte::factory()->create();
        $inf = Infirmier_Connecte::create(['compte_id' => $inf1->id]);
        $med1 = Compte::factory()->create();
        $med = Medecin_Connecte::create(['compte_id' => $med1->id]);

        // priority 0 → infirmier
        Triage::enqueue($compte, 0);

        $this->assertDatabaseHas('triages', [
            'compte_id' => $compte->id,
            'infirmier_connecte_id' => $inf->id,
        ]);

        // priority > 0 → medecin
        $compte2 = Compte::factory()->create();

        Triage::enqueue($compte2, 1);

        $this->assertDatabaseHas('triages', [
            'compte_id' => $compte2->id,
            'medecin_connecte_id' => $med->id,
        ]);
    }

    public function test_enqueue_logs_error_if_no_practitioner()
    {
        Log::spy();

        $compte = Compte::factory()->create();

        // no infirmier or medecin in DB
        Triage::enqueue($compte, 0);

        Log::shouldHaveReceived('error');
    }

    public function test_dequeue_removes_patient()
    {
        $compte = Compte::factory()->create();

        Triage::create([
            'compte_id' => $compte->id,
            'priority' => 1,
            'position' => 0,
        ]);

        Triage::dequeue($compte);

        $this->assertDatabaseMissing('triages', [
            'compte_id' => $compte->id,
        ]);
    }

    public function test_dequeue_throws_if_not_found()
    {
        $this->expectException(ModelNotFoundException::class);

        $compte = Compte::factory()->create();

        Triage::dequeue($compte);
    }

    public function test_peek_returns_correct_patient_for_infirmier()
    {
        $compte = Compte::factory()->create();
        $infCompte = Compte::factory()->create();

        $inf = Infirmier_Connecte::create(['compte_id' => $infCompte->id]);

        Triage::create([
            'compte_id' => $compte->id,
            'infirmier_connecte_id' => $inf->id,
            'priority' => 1,
            'position' => 0,
        ]);

        $result = Triage::peek(0, $infCompte);

        $this->assertEquals($compte->id, $result->id);
    }

    public function test_peek_throws_if_out_of_bounds()
    {
        $this->expectException(OutOfBoundsException::class);

        $compte = Compte::factory()->create();

        Triage::peek(0, $compte);
    }

    public function test_rebalance_assigns_patients_evenly()
    {
        $inf12 = Compte::factory()->create();
        $inf2 = Infirmier_Connecte::create(['compte_id' => $inf12->id]);

        $inf11 = Compte::factory()->create();
        $inf1 = Infirmier_Connecte::create(['compte_id' => $inf11->id]);

        foreach (range(1, 4) as $i) {
            Triage::create([
                'compte_id' => Compte::factory()->create()->id,
                'priority' => 1,
                'position' => $i,
                'infirmier_connecte_id' => $inf1->id,
            ]);
        }

        Triage::rebalance(true);

        $counts = Infirmier_Connecte::withCount('triages')->pluck('triages_count');

        $this->assertTrue($counts->max() - $counts->min() <= 1);
    }

    public function test_medecin_login_and_logout()
    {
        $compte = Compte::factory()->create();

        Medecin_Connecte::login($compte);

        $this->assertDatabaseHas('medecins_connectes', [
            'compte_id' => $compte->id,
        ]);

        Medecin_Connecte::logout($compte);

        $this->assertDatabaseMissing('medecins_connectes', [
            'compte_id' => $compte->id,
        ]);
    }

    public function test_infirmier_queue_count()
    {
        $inf11 = Compte::factory()->create();
        $inf1 = Infirmier_Connecte::create(['compte_id' => $inf11->id]);

        Triage::create([
            'compte_id' => Compte::factory()->create()->id,
            'infirmier_connecte_id' => $inf1->id,
            'priority' => 0,
            'position' => 0,
        ]);

        $this->assertEquals(1, $inf1->queueCount($inf11));
    }

    public function test_infirmier_login_and_logout()
    {
        $compte = Compte::factory()->create();

        Infirmier_Connecte::login($compte);

        $this->assertDatabaseHas('infirmiers_connectes', [
            'compte_id' => $compte->id,
        ]);

        Infirmier_Connecte::logout($compte);

        $this->assertDatabaseMissing('infirmiers_connectes', [
            'compte_id' => $compte->id,
        ]);
    }

    public function test_dequeue_does_nothing_if_not_found()
    {
        $this->expectException(ModelNotFoundException::class);
        $compte = Compte::factory()->create();

        // Should not crash
        Triage::dequeue($compte);
    }

    public function test_triage_count_returns_number_of_triages()
    {
        $inf11 = Compte::factory()->create();
        $inf1 = Infirmier_Connecte::create(['compte_id' => $inf11->id]);

        foreach (range(1, 3) as $i) {
            Triage::create([
                'compte_id' => Compte::factory()->create()->id,
                'priority' => 1,
                'position' => $i,
                'infirmier_connecte_id' => $inf1->id,
            ]);
        }

        $this->assertEquals(3, Triage::count());
    }

    public function test_peek_for_medecin()
    {
        $compte = Compte::factory()->create();

        $med1 = Compte::factory()->create();
        $med = Medecin_Connecte::create(['compte_id' => $med1->id]);

        Triage::create([
            'compte_id' => $compte->id,
            'medecin_connecte_id' => $med->id,
            'priority' => 1,
            'position' => 0,
        ]);

        $result = Triage::peek(0, $med1);

        $this->assertEquals($compte->id, $result->id);
    }

    public function test_medecin_queue_count()
    {
        $medCompte = Compte::factory()->create();
        $med = Medecin_Connecte::create(['compte_id' => $medCompte->id]);

        Triage::create([
            'compte_id' => Compte::factory()->create()->id,
            'medecin_connecte_id' => $med->id,
            'priority' => 1,
            'position' => 0,
        ]);

        $this->assertEquals(1, Medecin_Connecte::queueCount($medCompte));
    }

    public function test_medecin_logout_logs_error_on_failure()
    {
        Log::spy();

        $compte = Compte::factory()->create();

        // No medecin exists → will throw inside transaction
        Medecin_Connecte::logout($compte);

        Log::shouldHaveReceived('error');
    }

    public function test_infirmier_logout_logs_error_on_failure()
    {
        Log::spy();

        $compte = Compte::factory()->create();

        Infirmier_Connecte::logout($compte);

        Log::shouldHaveReceived('error');
    }

    public function test_queue_position_returns_minus_one_if_not_in_list()
    {
        $compte = Compte::factory()->create();

        $infCompte = Compte::factory()->create();
        $inf = Infirmier_Connecte::create(['compte_id' => $infCompte->id]);

        // patient exists but not in same queue group
        Triage::create([
            'compte_id' => $compte->id,
            'priority' => 1,
            'position' => 0,
            'infirmier_connecte_id' => null,
            'medecin_connecte_id' => null,
        ]);

        $this->assertEquals(-1, Triage::queuePosition($compte));
    }

    public function test_medecin_login_logs_error_on_exception()
    {
        Log::spy();

        // Force failure: null compte_id (should break DB constraint)
        $compte = new Compte(); // NOT saved → id = null

        Medecin_Connecte::login($compte);

        Log::shouldHaveReceived('error');
    }

    public function test_infirmier_login_logs_error_on_exception()
    {
        Log::spy();

        // Force failure: null compte_id (should break DB constraint)
        $compte = new Compte(); // NOT saved → id = null

        Infirmier_Connecte::login($compte);

        Log::shouldHaveReceived('error');
    }
}
