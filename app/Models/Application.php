<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Application extends Model
{
    use HasFactory;

    protected $table = 'applications';

    protected $fillable = [
        'categorie_id',
        'nom',
        'slug',
        'description',
        'lien',
        'icone',
        'actif',
        'ordre',
    ];

    protected $casts = [
        'actif'        => 'boolean',
        'categorie_id' => 'integer',
        'ordre'        => 'integer',
    ];

    /* ── Boot : slug automatique ── */
    protected static function booted(): void
    {
        static::creating(function (self $model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->nom);
            }
        });

        static::updating(function (self $model) {
            if ($model->isDirty('nom') && empty($model->slug)) {
                $model->slug = Str::slug($model->nom);
            }
        });

        // Supprime le fichier icône du storage quand l'app est effacée
        static::deleting(function (self $model) {
            if ($model->icone && Storage::disk('public')->exists($model->icone)) {
                Storage::disk('public')->delete($model->icone);
            }
        });
    }

    /* ── Relations ── */

    public function category(): BelongsTo
    {
        return $this->belongsTo(Categorie::class, 'categorie_id');
    }

    /* ── Scopes ── */

    public function scopeActives($query)
    {
        return $query->where('actif', true);
    }

    public function scopeOrdonnees($query)
    {
        return $query->orderBy('ordre')->orderBy('nom');
    }

    public function scopeParCategorie($query, int $categorieId)
    {
        return $query->where('categorie_id', $categorieId);
    }

    /* ── Accesseurs ── */

    /**
     * URL publique de l'icône (ou null si aucune).
     */
    public function getIconeUrlAttribute(): ?string
    {
        if ($this->icone && Storage::disk('public')->exists($this->icone)) {
            return Storage::url($this->icone);
        }

        return null;
    }

    /**
     * Initiale du nom pour l'affichage par défaut.
     */
    public function getInitialeAttribute(): string
    {
        return Str::upper(Str::substr($this->nom, 0, 1));
    }
}