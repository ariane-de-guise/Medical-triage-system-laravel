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

class MedecinController extends Controller
{
    public function index()
    {
        return view('medecin.index');
    }

    public function store()
    {
        $medecin = auth()->user()->compte;
        abort_if(!$medecin, 403);

        try {
            TimeCard::login(auth()->user(), Roles::Medecin);
        }
        catch (Exception $e) {
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
        }
        catch (Exception $e) {
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

        $visite = $compte->visites()->latest('date_heure')->first();

        abort_if(!$visite, 404);

        $visite->diagnostique->update([
            'description' => request('diagnostique'),
            'date_heure' => now(),
        ]);

        try {
            TriageQueue::dequeue($compte->user);
        }
        catch (Exception $e) {
            return redirect()->with('error', 'Erreur lors de la sortie de la file : ' . $e->getMessage());
        }

        return redirect()
            ->route('medecin')
            ->with('success', 'Vous avez terminée cette consultation.');
    }
}
