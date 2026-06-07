<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ServiceController;

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ApplicationController;
use App\Http\Controllers\Admin\CategorieController;
use App\Http\Controllers\Admin\IconeController;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

/*
|--------------------------------------------------------------------------
| ROUTES PUBLIQUES
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/services', [ServiceController::class, 'index'])
    ->name('services.index');

Route::get('/services/applications', [ServiceController::class, 'index'])
    ->name('services.applications');


/*
|--------------------------------------------------------------------------
| AUTHENTIFICATION
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {

    // REGISTER
    Route::get('/register', [RegisterController::class, 'create'])
        ->name('register');

    Route::post('/register', [RegisterController::class, 'store']);

    // LOGIN
    Route::get('/login', [LoginController::class, 'create'])
        ->name('login');

    Route::post('/login', [LoginController::class, 'store']);
});

// LOGOUT
Route::post('/logout', [LoginController::class, 'destroy'])
    ->middleware('auth') // middleware personnalisé pour rediriger les invités vers la page d'accueil
    ->name('logout');


/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'admin'])
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | DASHBOARD
        |--------------------------------------------------------------------------
        */

        Route::get('/', [DashboardController::class, 'index'])
            ->name('dashboard');


        /*
        |--------------------------------------------------------------------------
        | APPLICATIONS
        |--------------------------------------------------------------------------
        */

        Route::resource('applications', ApplicationController::class)
            ->except(['show']);

        // Mise à jour du lien uniquement
        Route::patch(
            'applications/{application}/lien',
            [ApplicationController::class, 'updateLien']
        )->name('applications.lien');


        /*
        |--------------------------------------------------------------------------
        | CATEGORIES
        |--------------------------------------------------------------------------
        */

        Route::resource('categories', CategorieController::class)
            ->except(['show']);


        /*
        |--------------------------------------------------------------------------
        | ICONES
        |--------------------------------------------------------------------------
        */

        Route::get(
            'icones',
            [IconeController::class, 'index']
        )->name('icones.index');

        Route::post(
            'icones',
            [IconeController::class, 'store']
        )->name('icones.store');

        Route::delete(
            'icones/{icone}',
            [IconeController::class, 'destroy']
        )->name('icones.destroy');

        Route::put(
            'icones/{icone}/rename',
            [IconeController::class, 'rename']
        )->name('icones.rename');


        /*
        |--------------------------------------------------------------------------
        | LIENS
        |--------------------------------------------------------------------------
        */

        Route::get('liens', function () {

            $applications = \App\Models\Application::with('categorie')
                ->latest()
                ->get();

            return view('admin.liens.index', compact('applications'));

        })->name('liens.index');

    });