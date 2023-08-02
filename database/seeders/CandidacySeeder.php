<?php

namespace Database\Seeders;

use App\Models\Candidacy;
use Illuminate\Database\Seeder;

class CandidacySeeder extends Seeder
{
    public function run(): void
    {
        Candidacy::factory(50)
            ->create();
    }
}
