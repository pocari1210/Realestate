<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BlogCategoryTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    DB::table('blog_categories')->insert([

      [
        'id' => 1,
        'category_name' => '新築物件',
        'category_slug' => '新築物件',
      ],

      [
        'id' => 2,
        'category_name' => '中古物件',
        'category_slug' => '中古物件',
      ],

      [
        'id' => 3,
        'category_name' => '商業施設',
        'category_slug' => '商業施設',
      ],

      [
        'id' => 4,
        'category_name' => 'オフィスビル',
        'category_slug' => 'オフィスビル',
      ],

      [
        'id' => 5,
        'category_name' => 'タワーマンション',
        'category_slug' => 'タワーマンション',
      ],

    ]);
  }
}
