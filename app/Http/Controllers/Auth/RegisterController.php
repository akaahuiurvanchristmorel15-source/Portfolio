<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class RegisterController extends Controller
{
    /* ── GET /register ── */
    public function create(): View
    {
        return view('auth.register');
    }

    /* ── POST /register ── */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers(),
            ],
        ], [
            'name.required'      => 'Le nom est obligatoire.',
            'email.required'     => 'L\'adresse email est obligatoire.',
            'email.email'        => 'L\'adresse email est invalide.',
            'email.unique'       => 'Cette adresse email est déjà utilisée.',
            'password.required'  => 'Le mot de passe est obligatoire.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
            'password.min'       => 'Le mot de passe doit contenir au moins 8 caractères.',
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => 'user', // rôle par défaut
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('services.applications')
            ->with('success', 'Bienvenue, ' . $user->name . ' !');
    }
}