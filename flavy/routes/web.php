<?php

use Illuminate\Support\Facades\Route;

// Page d'accueil
Route::get('/', function () {
    return view('welcome');
});

/** require __DIR__.'/settings.php';
require __DIR__.'/auth.php'; */

// Routes protégées par Jetstream / Sanctum
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    // Dashboard général
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Pages par rôle
    Route::get('/admin', function () {
        return view('admin');
    })->middleware('role:admin');

    Route::get('/medecin', function () {
        return view('medecin');
    })->middleware('role:medecin');

    Route::get('/infirmier', function () {
        return view('infirmier');
    })->middleware('role:infirmier');

    Route::get('/patient', function () {
        return view('patient');
    })->middleware('role:patient');

    // Exemple route multi-rôles
    Route::get('/patients', function () {
        return 'Liste des patients';
    })->middleware('role:medecin|infirmier');
});
