<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ProjectDeliveryFrequency;

class ProjectDeliveryFrequencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $frequencies = ['One-time', 'Monthly', 'Weekly'];

        foreach ($frequencies as $frequency) {
            ProjectDeliveryFrequency::create([
                'name' => $frequency,
                'status' => 1,
            ]);
        }
    }
}
