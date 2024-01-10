<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {User::create([
        'name'=>'admin',
        'phone'=>'0978945613',
        'password'=>Hash::make('09789456123'),
        'role'=>'admin',
        //'remember_token'=>Hash::make('0000'),

    ]);
        User::create([
            'name'=>'sara',
            'phone'=>'0969866714',
            'password'=>Hash::make('0969866714'),
            'role'=>'admin',
            //'remember_token'=>Hash::make('0000'),

        ]);
        User::create([
            'name'=>'sana',
            'phone'=>'0987654321',
            'password'=>Hash::make('0987654321'),
            'role'=>'user',
            //'remember_token'=>Hash::make('0000'),

        ]);
    }
}
