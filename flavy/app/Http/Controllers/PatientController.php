<?php

namespace App\Http\Controllers;

use App\Algorithms\TriageQueue;
use Illuminate\Routing\Controller;

class PatientController extends Controller
{
    public function index() {
        return view('patient');
    }

    public function store() {
        $patient = auth()->user()->compte;
        abort_if(!$patient, 403);

        TriageQueue::enqueue(auth()->user(), 0, "");

        return redirect()
            ->route('patient')
            ->with('success', 'Vous avez été ajouté à la file d\'attente');
    }

    public function destroy() {
        $patient = auth()->user()->compte;
        abort_if(!$patient, 403);

        TriageQueue::dequeue(auth()->user());

        return redirect()
            ->route('patient')
            ->with('success', 'Vous avez été retiré de la file d\'attente');
    }
    public function liste()
    {
        $patients = \App\Models\User::whereHas('compte')
            ->with('compte')
            ->get();

        return view('patients.liste', compact('patients'));
    }
    public function show($id)
    {
        $patient = \App\Models\User::whereHas('compte')
            ->with('compte')
            ->findOrFail($id);

        return view('patients.show', compact('patient'));
    }
}
