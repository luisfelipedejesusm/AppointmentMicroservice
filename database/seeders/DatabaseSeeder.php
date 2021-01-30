<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\appointment_seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            appointment_seeder::class
        ]);
    }
}
