<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Categorie extends Model
{
    use HasFactory;

    protected $table = 'categories';

    protected $fillable = [
        'nom',
        'slug',
        'description',
        'ordre',
    ];

    protected $casts = [
        'ordre' => 'integer',
    ];

    /* ── Boot : génération automatique du slug ── */
    protected static function booted(): void
    {
        static::creating(function (self $model) {

            $slug = Str::slug($model->nom);
            $originalSlug = $slug;
            $count = 1;

            while (self::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $count++;
            }

            $model->slug = $slug;
        });
    }

    /* ── Relations ── */

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class, 'categorie_id');
    }

    /* ── Scopes ── */

    public function scopeOrdonnees($query)
    {
        return $query->orderBy('ordre')->orderBy('nom');
    }

    /* ── Accesseurs ── */

    public function getNombreApplicationsAttribute(): int
    {
        return $this->applications()->where('actif', true)->count();
    }
}