<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\UnitOfMeasurement;

class UnitOfMeasurementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $units = [
            'FTE',
            'Unit Based',
            'Hourly Rate',
            'Contingency Percentage',
            'Retainer Model',
            'Project Based'
        ];

        foreach ($units as $unit) {
            UnitOfMeasurement::create([
                'name' => $unit,
                'status' => 1,
                'created_by' => 1, // replace with auth user if needed
                'updated_by' => 1,
            ]);
        }
    }
}
