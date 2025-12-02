<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\IntakeStatus;

class IntakeStatusSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = [
            'Delivered',
            'Do not Abstract',
            'On-Hold',
            'Duplicate',
        ];

        foreach ($statuses as $status) {
            IntakeStatus::firstOrCreate(['name' => $status]);
        }
    }
}
