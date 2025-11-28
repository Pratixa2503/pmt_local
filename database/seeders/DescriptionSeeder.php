<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Description;

class DescriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = ['Full Scope Abstraction', 'Limited Scope Abstraction', 'Only Clauses Abstraction'];
        foreach ($items as $item) {
            Description::create([
                'name' => $item,
                'status' => true,
                'created_by' => 1,
                'updated_by' => 1,
            ]);
        }
    }
}
