<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\QueryStatus;

class QueryStatusSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Open', 'Closed'] as $name) {
            QueryStatus::firstOrCreate(['name' => $name]);
        }
    }
}
