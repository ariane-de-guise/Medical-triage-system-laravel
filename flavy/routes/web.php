<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InfirmierController;
use App\Http\Controllers\MedecinController;
use App\Http\Controllers\PatientController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\RegisteredUserController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
Route::post('/register', [RegisteredUserController::class, 'store']);
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified',])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | ADMIN
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin', [AdminController::class, 'index'])->name('admin');
        Route::get('/admin/roles', [AdminController::class, 'edit'])->name('admin.roles');
        Route::post('/admin/roles/add', [UserController::class, 'addRole'])->name('admin.roles.add');
        Route::post('/admin/roles/remove', [UserController::class, 'removeRole'])->name('admin.roles.remove');
    });

    /*
    |--------------------------------------------------------------------------
    | MEDECIN
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:medecin'])->group(function () {
        Route::get('/medecin', [MedecinController::class, 'index'])->name('medecin');
        Route::post('/medecin/login', [MedecinController::class, 'store'])->name('medecin.login');
        Route::delete('/medecin/logout', [MedecinController::class, 'destroy'])->name('medecin.logout');
        Route::get('/medecin/{compte}', [MedecinController::class, 'show'])->name('medecin.show');
        Route::patch('/medecin/{compte}/next', [MedecinController::class, 'update'])->name('medecin.next');
    });

    /*
    |--------------------------------------------------------------------------
    | INFIRMIER
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:infirmier'])->group(function () {
        Route::get('/infirmier', [InfirmierController::class, 'index'])->name('infirmier');
        Route::post('/infirmier/login', [InfirmierController::class, 'store'])->name('infirmier.login');
        Route::delete('/infirmier/logout', [InfirmierController::class, 'destroy'])->name('infirmier.logout');
        Route::get('/infirmier/{compte}', [InfirmierController::class, 'show'])->name('infirmier.show');
        Route::post('/infirmier/{compte}/next', [InfirmierController::class, 'update'])->name('infirmier.next');
    });

    /*
    |--------------------------------------------------------------------------
    | PATIENT
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:patient'])->group(function () {
        Route::get('/patient', [PatientController::class, 'index'])->name('patient');
        Route::post('/patient/enqueue', [PatientController::class, 'store'])->name('patient.enqueue');
        Route::post('/patient/dequeue', [PatientController::class, 'destroy'])->name('patient.dequeue');
    });

    Route::middleware(['role:admin|medecin'])->group(function () {
        Route::get('/patients', [PatientController::class, 'liste'])->name('patients.liste');
        Route::get('/patients/{id}', [PatientController::class, 'show'])->name('patients.show');
    });
});
