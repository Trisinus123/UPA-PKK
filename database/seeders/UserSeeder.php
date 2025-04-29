<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::updateOrCreate(
            ['email' => 'admin@jobportal.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('tris12233'),
                'role' => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'perusahaan@jobportal.com'],
            [
                'name' => 'Perusahaan ABC',
                'password' => Hash::make('tris12233'),
                'role' => 'perusahaan',
            ]
        );

        User::updateOrCreate(
            ['email' => 'mahasiswa@jobportal.com'],
            [
                'name' => 'Mahasiswa',
                'password' => Hash::make('tris12233'),
                'role' => 'mahasiswa',
            ]
        );
        
        User::updateOrCreate(
            ['email' => 'tris@g.com'],
            [
                'name' => 'Trus 2',
                'password' => Hash::make('tris12233'),
                'role' => 'mahasiswa',
            ]
        );
        User::updateOrCreate(
            ['email' => 'p@g.com'],
            [
                'name' => 'PT ABC',
                'password' => Hash::make('tris12233'),
                'role' => 'perusahaan',
            ]
        );
    }
}