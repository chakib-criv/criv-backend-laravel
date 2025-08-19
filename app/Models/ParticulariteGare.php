<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParticulariteGare extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'texte',       // Le texte de la particularité
        'gare_id',     // La clé étrangère vers la gare
        'parcours_id', // La clé étrangère vers le parcours
        'user_id',     // La clé étrangère vers l'utilisateur
    ];

    /**
     * Définit la relation avec le modèle Gare.
     */
    public function gare()
    {
        return $this->belongsTo(Gare::class);
    }

    /**
     * Définit la relation avec le modèle Parcours.
     */
    public function parcours()
    {
        return $this->belongsTo(Parcours::class);
    }

    /**
     * Définit la relation avec le modèle User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}