<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    DB::table('users')->insert([

      //  Admin(管理者)            
      [
        'name' => 'Admin',
        'username' => 'admin',
        'email' => 'admin@gmail.com',
        'password' => Hash::make('password123'),
        'role' => 'admin',
        'status' => 'active',
      ],

      //  Agent(エージェント)
      [
        'name' => 'Agent',
        'username' => 'agent',
        'email' => 'agent@gmail.com',
        'password' => Hash::make('password123'),
        'role' => 'agent',
        'status' => 'active',
      ],

      //  User(ユーザー)
      [
        'name' => 'User',
        'username' => 'user',
        'email' => 'user@gmail.com',
        'password' => Hash::make('password123'),
        'role' => 'user',
        'status' => 'active',
      ],

    ]);
  }
}
