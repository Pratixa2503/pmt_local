<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CustomerApprovalStatus;

class CustomerApprovalStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = ['Pending', 'Approved', 'Unapproved'];

        foreach ($statuses as $status) {
            CustomerApprovalStatus::create([
                'name' => $status,
                'status' => 1,
            ]);
        }
    }
}
