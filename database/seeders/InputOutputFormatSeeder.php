<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\InputOutputFormat;

class InputOutputFormatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $formats = [
            ['name' => 'PDF', 'status' => 1],
            ['name' => 'Excel', 'status' => 1],
            ['name' => 'CSV', 'status' => 1],
        ];

        foreach ($formats as $format) {
            InputOutputFormat::create($format);
        }
    }
}
