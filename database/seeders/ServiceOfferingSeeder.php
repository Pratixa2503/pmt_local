<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ServiceOffering;

class ServiceOfferingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            'Abstraction',
            'Abstraction - Limited Scope',
            'Abstraction - D&D'
        ];
        foreach ($statuses as $name) {
            ServiceOffering::create([
                'name' => $name,
                'status' => true,
            ]);
        }
    }
}
