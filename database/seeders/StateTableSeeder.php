<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StateTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    DB::table('states')->insert([

      [
        'state_name' => '東京',
      ],

      [
        'state_name' => '埼玉',
      ],

      [
        'state_name' => '千葉',
      ],

      [
        'state_name' => '神奈川',
      ],

    ]);
  }
}
