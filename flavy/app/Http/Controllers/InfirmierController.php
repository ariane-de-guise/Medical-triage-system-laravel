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
    public function index() {
        return view('infirmier.index');
    }

    public function store() {
        $infirmier = auth()->user()->compte;
        abort_if(!$infirmier, 403);

        $personnelActif = $infirmier->personnel;

        if ($personnelActif && $personnelActif->role === Roles::Medecin) {
            return redirect()
                ->route('infirmier')
                ->with('error', 'Vous êtes déjà en service comme médecin. Vous devez terminer les consultations avant de vous connecter comme infirmier.');
        }

        if ($personnelActif && $personnelActif->role === Roles::Infirmier) {
            return redirect()
                ->route('infirmier')
                ->with('success', 'Vous êtes déjà en service comme infirmier.');
        }

        try {
            TimeCard::login(auth()->user(), Roles::Infirmier);
        }
        catch (Exception $e) {
            return redirect()
                ->route('infirmier')
                ->with('error', 'Erreur lors de la connexion : ' . $e->getMessage());
        }

        return redirect()
            ->route('infirmier')
            ->with('success', 'Vous êtes prêt à prendre des consultations.');
    }

    public function destroy() {
        $infirmier = auth()->user()->compte;
        abort_if(!$infirmier, 403);

        try {
            TimeCard::logout(auth()->user(), Roles::Infirmier);
        }
        catch (Exception $e) {
            return redirect()
                ->route('infirmier')
                ->with('error', 'Erreur lors de la déconnexion : ' . $e->getMessage());
        }

        return redirect()
            ->route('infirmier')
            ->with('success', 'Vous avez terminé de prendre des consultations.');
    }

    public function show(Request $request, Compte $compte) {
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

    public function update(Request $request, Compte $compte) {
        $infirmier = auth()->user()->compte;
        abort_if(!$infirmier, 403);

        $request->validate([
            'commentaire' => 'required|string',
            'priorite' => 'required|integer',
            'symptomes' => 'required|string'
        ]);

        $commentaire = $request->commentaire;
        $priorite = $request->priorite;
        $symptomes = $request->symptomes;

        try {
            TriageQueue::dequeue($compte->user);
            TriageQueue::enqueue($compte->user, $priorite, ($commentaire . ' --- Symptômes : ' . $symptomes));
        }
        catch (Exception $e) {
            return redirect()->with('error', 'Erreur lors de l\'entrée de la file : ' . $e->getMessage());
        }

        return redirect()
            ->route('infirmier')
            ->with('success', 'Vous avez terminée cette consultation.');
    }
}
