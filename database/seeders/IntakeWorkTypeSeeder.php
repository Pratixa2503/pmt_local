<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\IntakeWorkType;

class IntakeWorkTypeSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            'Full Abstraction',
            'Partial Abstraction',
            'Limited Scope',
            'Dates & Dollars',
            'Validation',
            'Clauses',
            'Migration',
            'Incorporations / Modified Abstract',
            'Recovery Setup',
            'Property Setup',
            'Lease Setup',
            'Translation',
            'Multi lingual Abstract',
        ];

        foreach ($items as $name) {
            IntakeWorkType::firstOrCreate(['name' => $name]);
        }
    }
}
