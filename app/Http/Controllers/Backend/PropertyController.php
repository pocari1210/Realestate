<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Property;
use App\Models\MultiImage;
use App\Models\Facility;
use App\Models\Amenities;
use App\Models\PropertyType;
use App\Models\User;

class PropertyController extends Controller
{
  public function AllProperty()
  {
    $property = Property::latest()->get();

    return view(
      'backend.property.all_property',
      compact('property')
    );
  } // End Method 

  public function AddProperty()
  {

    // PropertyTypeの最新のデータを取得
    $propertytype = PropertyType::latest()->get();

    // Amenitiesの最新のデータを取得
    $amenities = Amenities::latest()->get();

    // Userのステータスがactiveで権限がagentの最新情報を取得
    $activeAgent = User::where('status', 'active')
      ->where('role', 'agent')
      ->latest()->get();

    // dd($propertytype, $amenities, $activeAgent);

    return view(
      'backend.property.add_property',
      compact('propertytype', 'amenities', 'activeAgent')
    );
  } // End Method 
}
