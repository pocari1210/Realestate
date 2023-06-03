<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Property;
use App\Models\MultiImage;
use App\Models\Facility;
use App\Models\Amenities;
use App\Models\PropertyType;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class WishlistController extends Controller
{
  public function AddToWishList(Request $request, $property_id)
  {
    // ユーザーがログインをしていた場合の処理
    if (Auth::check()) {

      // カラム名'user_id'(第1引数)をログインしているIDを指定(第2引数)
      $exists = Wishlist::where('user_id', Auth::id())
        ->where('property_id', $property_id)->first();

      // $existsでproperty_idがない場合の処理
      // 同じアカウントがお気に入りを二度押せないようにする
      if (!$exists) {
        Wishlist::insert([
          'user_id' => Auth::id(),
          'property_id' => $property_id,
          'created_at' => Carbon::now()
        ]);

        // ★addToWishListメソッドのメッセージ★

        // テーブルにデータを挿入した時のメッセージ(成功)
        // success: function(data)が実行される
        return response()->json(['success' => 'Successfully Added On Your Wishlist']);
      } else {
        // 既にお気に入りに登録していた場合のメッセージ(エラー)
        return response()->json(['error' => 'This Property Has Already in your WishList']);
      }
    } else {

      // ログインをしていない場合のメッセージ(エラー)
      return response()->json(['error' => 'At First Login Your Account']);
    }
  } // End Method 

  public function UserWishlist()
  {

    $id = Auth::user()->id;
    $userData = User::find($id);

    return view(
      'frontend.dashboard.wishlist',
      compact('userData')
    );
  } // End Method 

  public function GetWishlistProperty()
  {

    // withメソッドでリレーション先のPropertyを取得
    // ※Wishlist.phpで作成したメソッドがwithの中に入る
    $wishlist = Wishlist::with('property')->where('user_id', Auth::id())
      ->latest()->get();

    // wishlistの件数を取得している
    $wishQty = wishlist::count();

    return response()->json([
      'wishlist' => $wishlist,
      'wishQty' => $wishQty
    ]);
  } // End Method 

  public function WishlistRemove($id)
  {
    Wishlist::where('user_id', Auth::id())
      ->where('id', $id)->delete();

    return response()->json([
      'success' => 'Successfully Property Remove'
    ]);
  } // End Method 
}
