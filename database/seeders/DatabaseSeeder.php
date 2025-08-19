<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserSeeder::class,
            ParcoursSeeder::class,
            LigneRerSeeder::class,
            GareSeeder::class,
            // Ajoutez d'autres seeders ici si nécessaire
        ]);
    }
}
