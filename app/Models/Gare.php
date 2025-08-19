<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gare extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',          // Permet d'assigner le champ 'name'
        'ligne_rer_id',  // Permet d'assigner le champ 'ligne_rer_id' (la clé étrangère)
    ];

    /**
     * Définit la relation avec la LigneRer.
     */
    public function ligneRer()
    {
        return $this->belongsTo(LigneRer::class);
    }
}