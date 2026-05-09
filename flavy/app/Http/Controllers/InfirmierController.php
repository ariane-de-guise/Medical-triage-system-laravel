<?php

namespace App\Http\Controllers;

use App\Algorithms\AlgorithmePriorite;
use App\Algorithms\Roles;
use App\Algorithms\TimeCard;
use App\Algorithms\TriageQueue;
use App\Models\Compte;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class InfirmierController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $compte = $user->compte;

        abort_if(!$compte, 403);

        $estInfirmierActif = TimeCard::loggedin($user, Roles::Infirmier);
        $estMedecinActif = TimeCard::loggedin($user, Roles::Medecin);

        $patients = [];

        if ($estInfirmierActif) {
            $nombrePatients = TriageQueue::queueCount($user);

            for ($i = 0; $i < $nombrePatients; $i++) {
                $patients[] = TriageQueue::peek($i, $user);
            }
        }

        return view('infirmier.index', compact(
            'estInfirmierActif',
            'estMedecinActif',
            'patients'
        ));
    }

    public function store()
    {
        $infirmier = auth()->user()->compte;
        abort_if(!$infirmier, 403);

        if (TimeCard::loggedin(auth()->user(), Roles::Medecin)) {
            return redirect()
                ->route('infirmier')
                ->with('error', 'Vous êtes déjà en service comme médecin. Vous devez terminer les consultations avant de vous connecter comme infirmier.');
        }

        if (TimeCard::loggedin(auth()->user(), Roles::Infirmier)) {
            return redirect()
                ->route('infirmier')
                ->with('success', 'Vous êtes déjà en service comme infirmier.');
        }

        try {
            TimeCard::login(auth()->user(), Roles::Infirmier);
        } catch (Exception $e) {
            return redirect()
                ->route('infirmier')
                ->with('error', 'Erreur lors de la connexion : ' . $e->getMessage());
        }

        return redirect()
            ->route('infirmier')
            ->with('success', 'Vous êtes prêt à faire le triage.');
    }

    public function destroy()
    {
        $infirmier = auth()->user()->compte;
        abort_if(!$infirmier, 403);

        try {
            TimeCard::logout(auth()->user(), Roles::Infirmier);
        } catch (Exception $e) {
            return redirect()
                ->route('infirmier')
                ->with('error', 'Erreur lors de la déconnexion : ' . $e->getMessage());
        }

        return redirect()
            ->route('infirmier')
            ->with('success', 'Vous avez terminé le triage.');
    }

    public function show(Request $request, Compte $compte)
    {
        $infirmier = auth()->user()->compte;
        abort_if(!$infirmier, 403);

        $symptomes = $request->symptomes;
        $priorite = null;

        if ($symptomes) {
            $priorite = AlgorithmePriorite::calculer($symptomes);
            $symptomes = implode(', ', $symptomes);
        }

        return view('infirmier.consultation', compact('compte', 'priorite', 'symptomes'));
    }

    public function update(Request $request, Compte $compte)
    {
        $infirmier = auth()->user()->compte;
        abort_if(!$infirmier, 403);

        $request->validate([
            'commentaire' => 'required|string',
            'priorite' => 'required|integer',
            'symptomes' => 'required|string',
        ]);

        try {
            TriageQueue::dequeue($compte->user);

            TriageQueue::enqueue(
                $compte->user,
                $request->priorite,
                $request->commentaire . ' --- Symptômes : ' . $request->symptomes
            );
        } catch (Exception $e) {
            return redirect()
                ->route('infirmier')
                ->with('error', 'Erreur lors de l’entrée dans la file : ' . $e->getMessage());
        }

        return redirect()
            ->route('infirmier')
            ->with('success', 'Vous avez terminé cette consultation.');
    }
}
