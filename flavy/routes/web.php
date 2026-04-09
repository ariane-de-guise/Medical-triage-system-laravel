<?php

use App\Models\Triage;
use Illuminate\Support\Facades\Route;

// Page d'accueil
Route::get('/', function () {
    return view('welcome');
});

// ⚠️ IMPORTANT : activer l’auth
//require __DIR__.'/auth.php';
//require __DIR__.'/settings.php';

// Routes protégées (Jetstream + Sanctum)
use App\Http\Controllers\Auth\RegisteredUserController;

Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
Route::post('/register', [RegisteredUserController::class, 'store']);
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        $user = \App\Models\User::find(auth()->user()->id);
        return view('dashboard', compact('user'));
    })->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | ADMIN
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin', function () {
            return view('admin');
        })->name('admin');
    });

    /*
    |--------------------------------------------------------------------------
    | MEDECIN
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:medecin'])->group(function () {
        Route::get('/medecin', function () {
            return view('medecin');
        })->name('medecin');
    });

    /*
    |--------------------------------------------------------------------------
    | INFIRMIER
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:infirmier'])->group(function () {
        Route::get('/infirmier', function () {
            return view('infirmier');
        })->name('infirmier');
    });

    /*
    |--------------------------------------------------------------------------
    | PATIENT
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:patient'])->group(function () {
        Route::get('/patient', function () {
            return view('patient');
        })->name('patient');

        Route::post('/patient/enqueue', function () {
            $patient = auth()->user()->compte;
            abort_if(!$patient, 403);

            Triage::enqueue($patient, 0);

            return redirect()
                ->route('patient')
                ->with('success', 'Vous avez été ajouté à la file d\'attente');
        })->name('patient.enqueue');

        Route::post('/patient/dequeue', function () {
            $patient = auth()->user()->compte;
            abort_if(!$patient, 403);

            Triage::dequeue($patient);

            return redirect()
                ->route('patient')
                ->with('success', 'Vous avez été retiré de la file d\'attente');
        })->name('patient.dequeue');
    });

    /*
    |--------------------------------------------------------------------------
    | MULTI-ROLES
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:medecin|infirmier'])->group(function () {
        Route::get('/patients', function () {
            return 'Liste des patients';
        })->name('patients.list');
    });

});
