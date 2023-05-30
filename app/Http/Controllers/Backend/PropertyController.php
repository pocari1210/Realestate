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
use Intervention\Image\Facades\Image;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Carbon\Carbon;

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

  public function StoreProperty(Request $request)
  {

    $amen = $request->amenities_id;

    // $amenを一つにまとめる
    $amenites = implode(",", $amen);
    // dd($amen);

    // フォームからきた画像を受け取る
    $image = $request->file('property_thambnail');

    // uniqidを10進数にし、文字列連携で、
    // $imageの拡張子(png)を取得しファイル名作成
    $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();

    Image::make($image)->resize(370, 250)
      ->save('upload/property/thambnail/' . $name_gen);

    // 保存処理をするため、変数($save_url)作成
    $save_url = 'upload/property/thambnail/' . $name_gen;

    $pcode = IdGenerator::generate([
      'table' => 'properties',
      'field' => 'property_code',
      'length' => 5,
      'prefix' => 'PC' // PC:property codeの略
    ]);

    // insertGetIdで追加したID情報を取得
    $property_id = Property::insertGetId([

      'ptype_id' => $request->ptype_id,
      'amenities_id' => $amenites,
      'property_name' => $request->property_name,

      // strtolowerで大文字を小文字に変換
      // フォームから来たproperty_nameに空白があった場合、
      // -(ハイフン)に置換する
      'property_slug' => strtolower(str_replace(' ', '-', $request->property_name)),

      'property_code' => $pcode,
      'property_status' => $request->property_status,

      'lowest_price' => $request->lowest_price,
      'max_price' => $request->max_price,
      'short_descp' => $request->short_descp,
      'long_descp' => $request->long_descp,
      'bedrooms' => $request->bedrooms,
      'bathrooms' => $request->bathrooms,
      'garage' => $request->garage,
      'garage_size' => $request->garage_size,

      'property_size' => $request->property_size,
      'property_video' => $request->property_video,
      'address' => $request->address,
      'city' => $request->city,
      'state' => $request->state,
      'postal_code' => $request->postal_code,

      'neighborhood' => $request->neighborhood,
      'latitude' => $request->latitude,
      'longitude' => $request->longitude,
      'featured' => $request->featured,
      'hot' => $request->hot,
      'agent_id' => $request->agent_id,
      'status' => 1,
      'property_thambnail' => $save_url,

      // Carbonで現在の時間を取得
      'created_at' => Carbon::now(),
    ]);
  } // End Method 

}
