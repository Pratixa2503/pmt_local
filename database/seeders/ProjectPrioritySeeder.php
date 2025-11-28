<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ProjectPriority;

class ProjectPrioritySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $priorities = ['Low', 'Moderate', 'High'];

        foreach ($priorities as $priority) {
            ProjectPriority::create([
                'name' => $priority,
                'status' => 1,
            ]);
        }
    }
}
