<?php

namespace App\Http\Controllers;

use App\Algorithms\TriageQueue;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Routing\Controller;

class PatientController extends Controller
{
    public function index()
    {
        return view('patient');
    }

    public function store()
    {
        $patient = auth()->user()->compte;
        abort_if(!$patient, 403);

        TriageQueue::enqueue(auth()->user(), 0, "");

        return redirect()->route('patient')->with('success', 'Vous avez été ajouté à la file d\'attente');
    }

    public function destroy()
    {
        $patient = auth()->user()->compte;
        abort_if(!$patient, 403);
        TriageQueue::dequeue(auth()->user());
        return redirect()->route('patient')->with('success', 'Vous avez été retiré de la file d\'attente');
    }

    public function liste()
    {
        $patients = User::whereHas('compte')->with(['compte.visites'])->get()->map(function ($user)
        {
            $compte = $user->compte;$derniereVisite = $compte?->visites->sortByDesc('date_heure')->first();
                return [
                    'id' => $user->id,
                    'email' => $user->email,
                    'prenom' => $compte->prenom ?? 'Prénom inconnu',
                    'nom' => $compte->nom ?? 'Nom inconnu',
                    'initiales' => strtoupper(substr($compte->prenom ?? $user->name ?? 'P', 0, 1) . substr($compte->nom ?? '', 0, 1)),
                    'date_naissance' => $compte?->date_naissance ? Carbon::parse($compte->date_naissance)->format('d/m/Y') : 'Non renseignée',
                    'visites_count' => $compte?->visites->count() ?? 0,
                    'derniere_visite' => $derniereVisite?->date_heure ? Carbon::parse($derniereVisite->date_heure)->format('d/m/Y H:i') : 'Aucune',
                ];
            });

        return view('patients.liste', compact('patients'));
    }

    public function show($id)
    {
        $patient = User::whereHas('compte')
            ->with(['compte.visites.docteur', 'compte.visites.diagnostique'])
            ->findOrFail($id);
        $compte = $patient->compte;

        $visites = $compte?->visites->sortByDesc('date_heure')->map(function ($visite) {
                return [
                    'id' => $visite->id,
                    'date_visite' => $visite->date_heure ? \Carbon\Carbon::parse($visite->date_heure)->format('d/m/Y à H:i') : 'Date inconnue',
                    'medecin' => ($visite->docteur?->prenom ?? 'Non assigné') . ' ' . ($visite->docteur?->nom ?? ''),
                    'diagnostic' => ($visite->diagnostique?->description && $visite->diagnostique->description !== 'À remplir') ? $visite->diagnostique->description : 'Non renseigné',
                    'commentaire' => $visite->diagnostique?->commentaire,
                ];
            });
        $patientData = [
            'prenom' => $compte->prenom ?? 'Prénom inconnu',
            'nom' => $compte->nom ?? 'Nom inconnu',
            'email' => $patient->email,
            'initiales' => strtoupper(substr($compte->prenom ?? $patient->name ?? 'P', 0, 1) . substr($compte->nom ?? '', 0, 1)),
            'date_naissance' => $compte?->date_naissance ? \Carbon\Carbon::parse($compte->date_naissance)->format('d/m/Y') : 'Non renseignée',
            'created_at' => $patient->created_at ? $patient->created_at->format('d/m/Y H:i') : 'Non disponible',
        ];

        return view('patients.show', [
            'patient' => $patientData,
            'visites' => $visites,
        ]);
    }
}
