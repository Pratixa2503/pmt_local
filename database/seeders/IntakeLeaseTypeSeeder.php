<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\IntakeLeaseType;

class IntakeLeaseTypeSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            'Lease',
            'Sublease',
            'Owned',
            'Ground Lease',
        ];

        foreach ($items as $name) {
            IntakeLeaseType::firstOrCreate(['name' => $name]);
        }
    }
}
