<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyType extends Model
{
  use HasFactory;

  // property_types_tableテーブルの
  // すべてのフィールドが入力可能になる
  protected $guarded = [];
}
