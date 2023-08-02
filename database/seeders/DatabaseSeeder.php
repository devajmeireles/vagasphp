<?php

namespace Database\Seeders;

use App\Models\{Job, User};
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(JobSeeder::class);
    }
}
