<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LigneRer;

class LigneRerSeeder extends Seeder
{
    public function run()
    {
        $lignes = [
            ['name' => 'A', 'color' => '#CE0037'],
            ['name' => 'B', 'color' => '#003DA5'],
            ['name' => 'C', 'color' => '#F7D117'],
            ['name' => 'D', 'color' => '#00A94F'],
            ['name' => 'E', 'color' => '#A0006D'],
            ['name' => 'H', 'color' => '#8D5E2A'],
            ['name' => 'J', 'color' => '#007D5E'],
            ['name' => 'K', 'color' => '#7AC143'],
            ['name' => 'L', 'color' => '#E3007E'],
            ['name' => 'N', 'color' => '#00838F'],
            ['name' => 'P', 'color' => '#FFD200'],
            ['name' => 'R', 'color' => '#FF8ED4'],
            ['name' => 'U', 'color' => '#822432'],
            // Note : La ligne V n'existe plus en tant que telle, elle a été intégrée à la ligne C. Je l'omets pour l'instant.
        ];

        foreach ($lignes as $ligne) {
            LigneRer::create($ligne);
        }
    }
}