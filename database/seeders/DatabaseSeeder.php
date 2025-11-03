<?php

namespace Database\Seeders;

use App\Models\Lot;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\MohonTinggalKenderaan;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(50)->create();
        Lot::factory(250)->create();
        MohonTinggalKenderaan::factory(500)->create();
    }
}
