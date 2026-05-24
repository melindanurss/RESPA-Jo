<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['username' => 'melinda'],
            [
                'nama' => 'Melinda Nur Sahira',
                'username' => 'melinda',
                'email' => 'melindanursahira13@gmail.com',
                'password' => Hash::make('melinda123'),
                'role' => 'admin',
            ]
        );
        User::updateOrCreate(
            ['username' => 'mifta'], // cek apakah sudah ada
            [
                'nama' => 'Mifta Rahayu',
                'username' => 'mifta',
                'email' => 'miftarahayu14@gmail.com',
                'password' => Hash::make('mifta123'),
                'role' => 'admin',
            ]
        );
        User::updateOrCreate(
            ['username' => 'widya'], // cek apakah sudah ada
            [
                'nama' => 'Widyawati',
                'username' => 'widya',
                'email' => 'widya.wti353@gmail.com',
                'password' => Hash::make('widya123'),
                'role' => 'admin',
            ]
        );
        User::updateOrCreate(
            ['username' => 'salsa'], // cek apakah sudah ada
            [
                'nama' => 'Salsabilla Agustin',
                'username' => 'salsa',
                'email' => 'agustinnnsalsabilla@gmail.com',
                'password' => Hash::make('salsa123'),
                'role' => 'admin',
            ]
        );
        User::updateOrCreate(
            ['username' => 'ngainul'], // cek apakah sudah ada
            [
                'nama' => 'Ngainul Faldiana',
                'username' => 'ngainul',
                'email' => 'ngainulfaldiana26@gmail.com',
                'password' => Hash::make('ngainul123'),
                'role' => 'admin',
            ]
        );
        User::updateOrCreate(
            ['username' => 'nanda'], // cek apakah sudah ada
            [
                'nama' => 'Nanda Siska Setyo Putri',
                'username' => 'nanda',
                'email' => 'nandasiskasp12@gmail.com',
                'password' => Hash::make('nanda123'),
                'role' => 'admin',
            ]
        );
        User::updateOrCreate(
            ['username' => 'rizka'], // cek apakah sudah ada
            [
                'nama' => 'Rizka Itsmatul Jannah',
                'username' => 'rizka',
                'email' => 'rizkaitsmatuljannah@gmail.com',
                'password' => Hash::make('rizka123'),
                'role' => 'admin',
            ]
        );
        User::updateOrCreate(
            ['username' => 'gita'], // cek apakah sudah ada
            [
                'nama' => 'Bernagita Wahyu Setyabudi',
                'username' => 'gita',
                'email' => 'bernasetyabudi@gmail.com',
                'password' => Hash::make('gita123'),
                'role' => 'admin',
            ]
        );
    }
}
