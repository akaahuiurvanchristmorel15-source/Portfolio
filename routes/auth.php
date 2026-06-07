<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Routes d'authentification — à ajouter dans routes/web.php
|--------------------------------------------------------------------------
*/

/* ── Groupe GUEST : accès uniquement si NON connecté ── */
Route::middleware('guest')->group(function () {

    // Inscription
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);

    // Connexion
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
});

/* ── Déconnexion : accès uniquement si connecté ── */
Route::post('/logout', [LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Tableau des routes
|--------------------------------------------------------------------------
|
| GET  /login      login           Page de connexion
| POST /login      —               Traitement de la connexion
| GET  /register   register        Page d'inscription
| POST /register   —               Traitement de l'inscription
| POST /logout     logout          Déconnexion
|
*/