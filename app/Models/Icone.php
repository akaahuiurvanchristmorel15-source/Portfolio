<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Icone extends Model
{
    use HasFactory;

    protected $table = 'icones';

    protected $fillable = [
        'nom',
        'fichier',
        'extension',
        'taille',
    ];

    protected $casts = [
        'taille' => 'integer',
    ];

    /* ── Boot ── */
    protected static function booted(): void
    {
        // Nettoie le fichier physique lors de la suppression du modèle
        static::deleting(function (self $model) {
            if (Storage::disk('public')->exists($model->fichier)) {
                Storage::disk('public')->delete($model->fichier);
            }
        });
    }

    /* ── Accesseurs ── */

    public function getUrlAttribute(): string
    {
        return Storage::url($this->fichier);
    }

    /**
     * Taille humainement lisible (ex: "24 Ko").
     */
    public function getTailleHumaineAttribute(): string
    {
        if (!$this->taille) {
            return '—';
        }

        return match (true) {
            $this->taille >= 1_048_576 => round($this->taille / 1_048_576, 1) . ' Mo',
            $this->taille >= 1_024     => round($this->taille / 1_024)        . ' Ko',
            default                    => $this->taille . ' o',
        };
    }
}