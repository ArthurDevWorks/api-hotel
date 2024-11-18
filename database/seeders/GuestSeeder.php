<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Guest;
use Illuminate\Database\Seeder;
use App\database\factories;

class GuestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Guest::factory()
        ->hasAddresses(5)
        ->hasPhones(1)
        ->count(10)->create();
    }
}
