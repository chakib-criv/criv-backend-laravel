<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Parcours extends Model
{
    use HasFactory;

    /**
     * Les attributs qui peuvent être assignés en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'is_active',
    ];

    /**
     * Les attributs qui doivent être convertis en types natifs.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Définit la relation Many-to-Many avec LigneRer.
     * CORRECTION DE LA SYNTAXE DE LA RELATION
     */
    public function lignes_rer(): BelongsToMany
    {
        return $this->belongsToMany(LigneRer::class, 'ligne_rer_parcours');
    }

    /**
     * Utilisateurs autorisés pour ce parcours (table pivot: parcours_user)
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'parcours_user');
    }
}