<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Backend\PropertyTypeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//   return view('welcome');
// });

// ★User Frontend★
Route::get('/', [UserController::class, 'Index']);

Route::get('/dashboard', function () {
  return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

  // プロフィールの編集ページのルート
  Route::get('/user/profile', [UserController::class, 'UserProfile'])
    ->name('user.profile');

  // プロフィールの保存処理のルート
  Route::post('/user/profile/store', [UserController::class, 'UserProfileStore'])
    ->name('user.profile.store');

  // プロフィールからのログアウトのルート
  Route::get('/user/logout', [UserController::class, 'UserLogout'])
    ->name('user.logout');

  // パスワード変更ページのルート
  Route::get('/user/change/password', [UserController::class, 'UserChangePassword'])
    ->name('user.change.password');

  // パスワード更新のルート
  Route::post('/user/password/update', [UserController::class, 'UserPasswordUpdate'])
    ->name('user.password.update');
});

require __DIR__ . '/auth.php';

// ★admin権限でログインした場合のルートグループ★
Route::middleware(['auth', 'role:admin'])->group(function () {

  // adminのdashboard
  Route::get('/admin/dashboard', [AdminController::class, 'AdminDashboard'])
    ->name('admin.dashboard');

  // dashboardからログアウトするルート
  Route::get('/admin/logout', [AdminController::class, 'AdminLogout'])
    ->name('admin.logout');

  // プロフィールのルート
  Route::get('/admin/profile', [AdminController::class, 'AdminProfile'])
    ->name('admin.profile');

  // プロフィール更新(保存)のルート
  Route::post('/admin/profile/store', [AdminController::class, 'AdminProfileStore'])
    ->name('admin.profile.store');

  // パスワード変更のルート
  Route::get('/admin/change/password', [AdminController::class, 'AdminChangePassword'])
    ->name('admin.change.password');

  // パスワードの更新処理のルート
  Route::post('/admin/update/password', [AdminController::class, 'AdminUpdatePassword'])
    ->name('admin.update.password');
}); // End Group Admin Middleware

// ★agent権限でログインした場合のルートグループ★
Route::middleware(['auth', 'role:agent'])->group(function () {

  // agentのdashboard
  Route::get('/agent/dashboard', [AgentController::class, 'AgentDashboard'])
    ->name('agent.dashboard');
}); // End Group Agent Middleware


// adminのログインルート
Route::get('/admin/login', [AdminController::class, 'AdminLogin'])
  ->name('admin.login');

/// Admin Group Middleware 
Route::middleware(['auth', 'role:admin'])->group(function () {

  // Property Type All Route 
  Route::controller(PropertyTypeController::class)->group(function () {

    Route::get('/all/type', 'AllType')
      ->name('all.type');

    // Type:新規作成のルート
    Route::get('/add/type', 'AddType')
      ->name('add.type');

    // Type:保存処理のルート
    Route::post('/store/type', 'StoreType')
      ->name('store.type');
  });
}); // End Group Admin Middleware
