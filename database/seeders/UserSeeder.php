<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name' => 'eliphas',
            'email' => 'eliphas.sn@gmail.com',
            'password' => Hash::make('123456789'),
            // Дополнительные поля, если они присутствуют в вашей модели User
        ]);

    }
}
