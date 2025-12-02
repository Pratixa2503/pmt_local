<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\InvoiceFormat;

class InvoiceFormatSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            'Property-wise',
            'Tenant-wise',
            'Monthly Completion',
            'One-time project',
            'Property Manager',
        ];

        foreach ($items as $name) {
            InvoiceFormat::firstOrCreate(['name' => $name]);
        }
    }
}
