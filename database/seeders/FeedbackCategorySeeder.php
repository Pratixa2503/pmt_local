<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FeedbackCategory;

class FeedbackCategorySeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Critical', 'Non-Critical'] as $name) {
            FeedbackCategory::firstOrCreate(['name' => $name]);
        }
    }
}
