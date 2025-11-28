<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ProjectType;

class ProjectTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            'Annotation',
            'Lease Abstraction Services',
            'Lease Administration',
            'CAM Reconciliation',
        ];

        foreach ($types as $type) {
            ProjectType::create([
                'name' => $type,
                'status' => 1,
            ]);
        }
    }
}
