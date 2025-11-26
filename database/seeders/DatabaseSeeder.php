<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UkmCategory;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create Admin User
        User::create([
            'name' => 'Administrator',
            'email' => 'eka@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'admin',
            'nim' => 'ADMIN001',
            'phone' => '081234567890',
            'fakultas' => 'Teknik',
            'jurusan' => 'Informatika',
            'angkatan' => '2024',
        ]);

        // Create Sample Categories
        $categories = [
            ['name' => 'Olahraga', 'description' => 'UKM bidang olahraga'],
            ['name' => 'Seni & Budaya', 'description' => 'UKM bidang seni dan budaya'],
            ['name' => 'Akademik', 'description' => 'UKM bidang akademik dan keilmuan'],
            ['name' => 'Teknologi', 'description' => 'UKM bidang teknologi dan IT'],
            ['name' => 'Sosial & Kemanusiaan', 'description' => 'UKM bidang sosial'],
            ['name' => 'Lainnya', 'description' => 'UKM kategori lainnya'],
        ];

        foreach ($categories as $category) {
            UkmCategory::create($category);
        }

        // Create sample user
        User::create([
            'name' => 'John Doe',
            'email' => 'azam@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'user',
            'nim' => '202401001',
            'phone' => '081234567891',
            'fakultas' => 'Ilmu Komputer',
            'jurusan' => 'Sistem Informasi',
            'angkatan' => '2024',
        ]);
    }
}