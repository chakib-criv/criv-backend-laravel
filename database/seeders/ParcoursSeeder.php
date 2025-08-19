<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Parcours;

class ParcoursSeeder extends Seeder
{
    public function run()
    {
        Parcours::create(['name' => 'CRIV PE', 'description' => 'Paris Est']);
        Parcours::create(['name' => 'CRIV PN', 'description' => 'Paris Nord']);
        Parcours::create(['name' => 'CRIV PSE', 'description' => 'Paris Sud Est']);
        Parcours::create(['name' => 'CRIV PRG', 'description' => 'Paris Rive Gauche']);
    }
}