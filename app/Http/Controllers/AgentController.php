<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use App\Providers\RouteServiceProvider;

class AgentController extends Controller
{
  public function AgentDashboard()
  {
    return view('agent.agent_dashboard');
  }

  public function AgentLogin()
  {
    return view('agent.agent_login');
  } // End Method 

  public function AgentRegister(Request $request)
  {
    $user = User::create([
      'name' => $request->name,
      'email' => $request->email,
      'phone' => $request->phone,
      'password' => Hash::make($request->password),
      'role' => 'agent',
      'status' => 'inactive',
    ]);

    event(new Registered($user));

    Auth::login($user);

    return redirect(RouteServiceProvider::AGENT);
  } // End Method 

  public function AgentLogout(Request $request)
  {
    Auth::guard('web')->logout();

    $request->session()->invalidate();

    $request->session()->regenerateToken();

    $notification = array(
      'message' => 'ログアウトが成功しました',
      'alert-type' => 'success'
    );

    return redirect('/agent/login')->with($notification);
  } // End Method 

  public function AgentProfile()
  {
    $id = Auth::user()->id;
    $profileData = User::find($id);
    return view('agent.agent_profile_view', compact('profileData'));
  } // End Method 

  public function AgentProfileStore(Request $request)
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
      @unlink(public_path('upload/agent_images/' . $data->photo));
      $filename = date('YmdHi') . $file->getClientOriginalName();
      $file->move(public_path('upload/agent_images'), $filename);
      $data['photo'] = $filename;
    }

    $data->save();

    $notification = array(
      'message' => 'プロフィールの変更が成功しました',
      'alert-type' => 'success'
    );

    return redirect()->back()->with($notification);
  } // End Method 

  public function AgentChangePassword()
  {

    $id = Auth::user()->id;
    $profileData = User::find($id);
    return view('agent.agent_change_password', compact('profileData'));
  } // End Method 


  public function AgentUpdatePassword(Request $request)
  {

    // Validation 
    $request->validate([
      'old_password' => 'required',
      'new_password' => 'required|confirmed'
    ]);

    // Hash::checkでフォームに入力された過去のパスワードと、
    // DBに登録したパスワード情報が一致しているか判定をしている
    if (!Hash::check($request->old_password, auth::user()->password)) {

      $notification = array(
        'message' => 'Old Password Does not Match!',
        'alert-type' => 'error'
      );

      return back()->with($notification);
    }

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
