<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GareParcoursStatus extends Model
{
    use HasFactory;

    /**
     * Le nom de la table, si ce n'est pas le pluriel standard du nom du modèle.
     * @var string
     */
    protected $table = 'gare_parcours_status';

    /**
     * Les attributs qui peuvent être assignés en masse.
     * CET AJOUT EST LA CORRECTION
     * @var array<int, string>
     */
    protected $fillable = [
        'parcours_id',
        'gare_id',
        'is_active',
    ];
}