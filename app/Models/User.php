<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /* ── Champs assignables en masse ── */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /* ── Champs masqués (API / sérialisation) ── */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /* ── Casts ── */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',   // hashage automatique Laravel 10+
        ];
    }

    /* ────────────────────────────────────────
     |  Helpers de rôle
     * ──────────────────────────────────────── */

    /**
     * Vérifie si l'utilisateur est administrateur.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Vérifie si l'utilisateur est un utilisateur standard.
     */
    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    /* ── Scopes ── */

    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }

    public function scopeUsers($query)
    {
        return $query->where('role', 'user');
    }
}