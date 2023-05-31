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
}
