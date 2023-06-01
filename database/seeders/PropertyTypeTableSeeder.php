<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PropertyTypeTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    DB::table('property_types')->insert([

      [
        'type_name' => 'マンション',
        'type_icon' => 'icon-1',
      ],

      [
        'type_name' => 'アパート',
        'type_icon' => 'icon-1',
      ],
      [
        'type_name' => 'ビル',
        'type_icon' => 'icon-1',
      ],
      [
        'type_name' => 'モール',
        'type_icon' => 'icon-1',
      ],
      [
        'type_name' => 'ビル',
        'type_icon' => 'icon-1',
      ],
    ]);
  }
}
