<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\IntakeLanguage;

class IntakeLanguageSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            'Dutch',
            'Polish',
            'Swedish',
            'Romanian',
            'Russian',
            'Turkish',
            'Norwegian',
            'Czech',
            'Malay',
            'Indonesian',
            'Ukrainian',
            'Finnish',
            'Slovak',
            'Crotian',   // If you intended "Croatian", feel free to change this
            'Danish',
            'Arabic',
            'Hebrew',
            'Chinese',
            'Japanese',
            'Greek',
            'Mandarin',
            'Vietnamese',
            'Thai',
            'Cantonese',
            'Armenian',
            'Korean',
        ];

        foreach ($items as $name) {
            IntakeLanguage::firstOrCreate(['name' => $name]);
        }
    }
}
