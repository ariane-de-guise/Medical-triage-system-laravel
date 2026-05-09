<?php

namespace App\Http\Controllers;

use App\Algorithms\Roles;
use App\Algorithms\TimeCard;
use App\Algorithms\TriageQueue;
use App\Models\Compte;
use Exception;
use Illuminate\Routing\Controller;

class MedecinController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $compte = $user->compte;

        abort_if(!$compte, 403);

        $estMedecinActif = TimeCard::loggedin($user, Roles::Medecin);
        $estInfirmierActif = TimeCard::loggedin($user, Roles::Infirmier);

        $patients = [];

        if ($estMedecinActif) {

            $nombrePatients = TriageQueue::queueCount($user);

            for ($i = 0; $i < $nombrePatients; $i++) {

                $comptePatient = TriageQueue::peek($i, $user);

                $derniereVisite = $comptePatient->visites()
                    ->with('diagnostique')
                    ->latest('date_heure')
                    ->first();

                $patients[] = [
                    'index' => $i,

                    'compte' => $comptePatient,

                    'priorite' => $comptePatient->triage?->priority,

                    'date_visite' => $derniereVisite?->date_heure,

                    'commentaire' => $derniereVisite?->diagnostique?->commentaire,

                    'attente' => $derniereVisite?->date_heure
                        ? now()->diffInMinutes($derniereVisite->date_heure, true)
                        : null,
                ];
            }
        }

        return view('medecin.index', compact(
            'estMedecinActif',
            'estInfirmierActif',
            'patients'
        ));
    }

    public function store()
    {
        $medecin = auth()->user()->compte;
        abort_if(!$medecin, 403);

        if (TimeCard::loggedin(auth()->user(), Roles::Infirmier)) {
            return redirect()
                ->route('medecin')
                ->with('error', 'Vous êtes déjà en service comme infirmier. Vous devez terminer le triage avant de vous connecter comme médecin.');
        }

        if (TimeCard::loggedin(auth()->user(), Roles::Medecin)) {
            return redirect()
                ->route('medecin')
                ->with('success', 'Vous êtes déjà en service comme médecin.');
        }

        try {
            TimeCard::login(auth()->user(), Roles::Medecin);
        } catch (Exception $e) {
            return redirect()
                ->route('medecin')
                ->with('error', 'Erreur lors de la connexion : ' . $e->getMessage());
        }

        return redirect()
            ->route('medecin')
            ->with('success', 'Vous êtes prêt à prendre des consultations.');
    }

    public function destroy()
    {
        $medecin = auth()->user()->compte;
        abort_if(!$medecin, 403);

        try {
            TimeCard::logout(auth()->user(), Roles::Medecin);
        } catch (Exception $e) {
            return redirect()
                ->route('medecin')
                ->with('error', 'Erreur lors de la déconnexion : ' . $e->getMessage());
        }

        return redirect()
            ->route('medecin')
            ->with('success', 'Vous avez terminé de prendre des consultations.');
    }

    public function show(Compte $compte)
    {
        $medecin = auth()->user()->compte;
        abort_if(!$medecin, 403);

        return view('medecin.consultation', compact('compte'));
    }

    public function update(Compte $compte)
    {
        $medecin = auth()->user()->compte;
        abort_if(!$medecin, 403);

        request()->validate([
            'diagnostique' => 'required|string|min:2',
        ]);

        $visite = $compte->visites()
            ->with('diagnostique')
            ->latest('date_heure')
            ->first();

        abort_if(!$visite || !$visite->diagnostique, 404);

        $visite->diagnostique->update([
            'description' => request('diagnostique'),
            'date_heure' => now(),
        ]);

        try {
            TriageQueue::dequeue($compte->user);
        } catch (Exception $e) {
            return redirect()
                ->route('medecin')
                ->with('error', 'Erreur lors de la sortie de la file : ' . $e->getMessage());
        }

        return redirect()
            ->route('medecin')
            ->with('success', 'Vous avez terminé cette consultation.');
    }
}
