<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SiteSettingsTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    DB::table('site_settings')->insert([

      [
        'id' => 1,
        'support_phone' => '0957-47-5477',
        'company_address' => '長崎県諫早市永昌町1-2',
        'email' => 'test@gmail.com',
        'copyright' => 'おうちの管理',
      ],
    ]);
  }
}
