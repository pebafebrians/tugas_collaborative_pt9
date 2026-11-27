<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

public function run(): void {
    User::create(['name' => 'Super Admin', 'email' => 'super@kopdes.com', 'password' => Hash::make('password'), 'role' => 'super_admin']);
    User::create(['name' => 'Admin Gudang', 'email' => 'admin@kopdes.com', 'password' => Hash::make('password'), 'role' => 'admin']);
    User::create(['name' => 'Petugas', 'email' => 'petugas@kopdes.com', 'password' => Hash::make('password'), 'role' => 'production']);
}

}
