<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ProjectStatus;

class ProjectStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $statuses = [
            'Draft',
            'Active',
            'On Hold',
            'Completed',
            'Cancelled',
        ];

        foreach ($statuses as $name) {
            ProjectStatus::create([
                'name' => $name,
                'status' => 1,
            ]);
        }
    }
}
