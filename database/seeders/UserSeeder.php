<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'first_name' => "Admin",
            'last_name' => 'Briskstar',
            'email' => "admin@briskstar.com",
            'contact_no' => '1234567890',
            'password' => Hash::make("Brisk@123"),
        ]);
        $user->assignRole('super admin');
    }
}