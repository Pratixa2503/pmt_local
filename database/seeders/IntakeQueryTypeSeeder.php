<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\IntakeQueryType;

class IntakeQueryTypeSeeder extends Seeder
{
    public function run(): void
    {
        $queries = [
            'CD Contingent',
            'Pages missing',
            'Unexecuted documents',
            'Term Expired',
        ];

        foreach ($queries as $query) {
            IntakeQueryType::firstOrCreate(['name' => $query]);
        }
    }
}
