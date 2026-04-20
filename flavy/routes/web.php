<?php

use App\Algorithms\Roles;
use App\Algorithms\TimeCard;
use App\Algorithms\TriageQueue;
use App\Models\Compte;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

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
        $user = auth()->user();
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

        Route::post('/medecin/login', function () {
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
        })->name('medecin.login');

        Route::post('/medecin/logout', function () {
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
        })->name('medecin.logout');

        Route::post('/medecin/{compte}', function (Compte $compte) {
            $medecin = auth()->user()->compte;
            abort_if(!$medecin, 403);

            return view('consultation', compact('compte'));
        })->name('medecin.show');

        Route::patch('/medecin/{compte}/next', function (Compte $compte) {
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
        })->name('medecin.next');
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

            TriageQueue::enqueue(auth()->user(), 0, "");

            return redirect()
                ->route('patient')
                ->with('success', 'Vous avez été ajouté à la file d\'attente');
        })->name('patient.enqueue');

        Route::post('/patient/dequeue', function () {
            $patient = auth()->user()->compte;
            abort_if(!$patient, 403);

            TriageQueue::dequeue(auth()->user());

            return redirect()
                ->route('patient')
                ->with('success', 'Vous avez été retiré de la file d\'attente');
        })->name('patient.dequeue');
    });
});
