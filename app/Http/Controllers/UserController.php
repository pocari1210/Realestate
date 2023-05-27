<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
  public function Index()
  {
    //return view('frontend.frontend_dashboard');
    return view('frontend.index');
  } // End Method

  public function UserProfile()
  {
    $id = Auth::user()->id;
    $userData = User::find($id);

    return view(
      'frontend.dashboard.edit_profile',
      compact('userData')
    );
  } // End Method 

  public function UserProfileStore(Request $request)
  {

    // ログインしているユーザーのid取得
    $id = Auth::user()->id;

    // Userモデルの$idを1件取得
    $data = User::find($id);
    $data->username = $request->username;
    $data->name = $request->name;
    $data->email = $request->email;
    $data->phone = $request->phone;
    $data->address = $request->address;

    // formから画像の登録があった場合の処理
    if ($request->file('photo')) {
      $file = $request->file('photo');
      @unlink(public_path('upload/user_images/' . $data->photo));
      $filename = date('YmdHi') . $file->getClientOriginalName();
      $file->move(public_path('upload/user_images'), $filename);
      $data['photo'] = $filename;
    }

    $data->save();

    $notification = array(
      'message' => 'プロフィールの更新が成功しました',
      'alert-type' => 'success'
    );

    return redirect()->back()->with($notification);
  }
}
