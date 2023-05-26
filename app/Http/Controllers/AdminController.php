<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
  public function AdminDashboard()
  {
    return view('admin.index');
  }

  public function AdminLogout(Request $request): RedirectResponse
  {
    Auth::guard('web')->logout();

    $request->session()->invalidate();

    $request->session()->regenerateToken();

    return redirect('/admin/login');
  }

  public function AdminLogin()
  {
    return view('admin.admin_login');
  }

  public function AdminProfile()
  {
    $id = Auth::user()->id;
    $profileData = User::find($id);

    return view(
      'admin.admin_profile_view',
      compact('profileData')
    );
  }

  public function AdminProfileStore(Request $request)
  {

    $id = Auth::user()->id;
    $data = User::find($id);
    $data->username = $request->username;
    $data->name = $request->name;
    $data->email = $request->email;
    $data->phone = $request->phone;
    $data->address = $request->address;

    if ($request->file('photo')) {
      $file = $request->file('photo');
      @unlink(public_path('upload/admin_images/' . $data->photo));
      $filename = date('YmdHi') . $file->getClientOriginalName();
      $file->move(public_path('upload/admin_images'), $filename);
      $data['photo'] = $filename;
    }

    $data->save();

    // return redirect()->back();

    $notification = array(
      'message' => 'プロフィールを変更しました',
      'alert-type' => 'success'
    );

    return redirect()->back()->with($notification);
  } // End Method

  public function AdminChangePassword()
  {
    $id = Auth::user()->id;
    $profileData = User::find($id);

    return view(
      'admin.admin_change_password',
      compact('profileData')
    );
  } // End Method 

  public function AdminUpdatePassword(Request $request)
  {

    // Validation 
    $request->validate([
      'old_password' => 'required',
      'new_password' => 'required|confirmed'

    ]);

    /// Match The Old Password

    // Hash::checkでフォームに入力された過去のパスワードと、
    // DBに登録したパスワード情報が一致しているか判定をしている
    if (!Hash::check($request->old_password, auth::user()->password)) {

      $notification = array(
        'message' => '古いパスワード情報が誤っています',
        'alert-type' => 'error'
      );

      return back()->with($notification);
    }

    /// Update The New Password 

    // ログインしているアカウントのパスワード情報を
    // ハッシュ化し、更新している
    User::whereId(auth()->user()->id)->update([
      'password' => Hash::make($request->new_password)

    ]);

    $notification = array(
      'message' => 'パスワードの変更に成功しました',
      'alert-type' => 'success'
    );

    return back()->with($notification);
  } // End Method 
}
