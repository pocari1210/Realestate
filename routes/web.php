<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Backend\PropertyTypeController;
use App\Http\Controllers\Backend\PropertyController;
use App\Http\Middleware\RedirectIfAuthenticated;

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

// エージェントログインページのルート
Route::get('/agent/login', [AgentController::class, 'AgentLogin'])
  ->name('agent.login')
  ->middleware(RedirectIfAuthenticated::class);

// エージェント登録ページのルート
Route::post('/agent/register', [AgentController::class, 'AgentRegister'])
  ->name('agent.register');

// adminのログインルート
Route::get('/admin/login', [AdminController::class, 'AdminLogin'])
  ->name('admin.login')
  ->middleware(RedirectIfAuthenticated::class);

/// Admin Group Middleware 
Route::middleware(['auth', 'role:admin'])->group(function () {

  // Property Type All Route 
  Route::controller(PropertyTypeController::class)->group(function () {

    // Type:トップページのルート
    Route::get('/all/type', 'AllType')
      ->name('all.type');

    // Type:新規作成のルート
    Route::get('/add/type', 'AddType')
      ->name('add.type');

    // Type:保存処理のルート
    Route::post('/store/type', 'StoreType')
      ->name('store.type');

    // Type:編集処理のルート
    Route::get('/edit/type/{id}', 'EditType')
      ->name('edit.type');

    // Type:更新処理のルート
    Route::post('/update/type', 'UpdateType')
      ->name('update.type');

    // Type:削除処理のルート
    Route::get('/delete/type/{id}', 'DeleteType')
      ->name('delete.type');
  });

  // Amenities Type All Route 
  Route::controller(PropertyTypeController::class)->group(function () {

    // Amenitie:トップページのルート
    Route::get('/all/amenitie', 'AllAmenitie')
      ->name('all.amenitie');

    // Amenitie:新規作成のルート
    Route::get('/add/amenitie', 'AddAmenitie')
      ->name('add.amenitie');

    // Amenitie:保存処理のルート
    Route::post('/store/amenitie', 'StoreAmenitie')
      ->name('store.amenitie');

    // Amenitie:編集処理のルート
    Route::get('/edit/amenitie/{id}', 'EditAmenitie')
      ->name('edit.amenitie');

    // Amenitie:更新処理のルート
    Route::post('/update/amenitie', 'UpdateAmenitie')
      ->name('update.amenitie');

    // Amenitie:削除処理のルート
    Route::get('/delete/amenitie/{id}', 'DeleteAmenitie')
      ->name('delete.amenitie');
  });

  // Property All Route 
  Route::controller(PropertyController::class)->group(function () {

    // property:トップページのルート
    Route::get('/all/property', 'AllProperty')
      ->name('all.property');

    // property:新規作成のルート
    Route::get('/add/property', 'AddProperty')
      ->name('add.property');

    // property:保存処理のルート
    Route::post('/store/property', 'StoreProperty')
      ->name('store.property');

    // property:編集処理のルート
    Route::get('/edit/property/{id}', 'EditProperty')
      ->name('edit.property');

    // property:更新処理のルート
    Route::post('/update/property', 'UpdateProperty')
      ->name('update.property');

    // property:画像更新のルート 
    Route::post('/update/property/thambnail', 'UpdatePropertyThambnail')
      ->name('update.property.thambnail');

    // property:複数画像更新のルート
    Route::post('/update/property/multiimage', 'UpdatePropertyMultiimage')
      ->name('update.property.multiimage');

    // property:画像削除のルート
    Route::get('/property/multiimg/delete/{id}', 'PropertyMultiImageDelete')
      ->name('property.multiimg.delete');

    // property:複数画像登録のルート
    Route::post('/store/new/multiimage', 'StoreNewMultiimage')
      ->name('store.new.multiimage');

    // Facilities:更新処理のルート
    Route::post('/update/property/facilities', 'UpdatePropertyFacilities')
      ->name('update.property.facilities');

    // property:削除処理のルート
    Route::get('/delete/property/{id}', 'DeleteProperty')
      ->name('delete.property');

    // property:詳細ページのルート
    Route::get('/details/property/{id}', 'DetailsProperty')
      ->name('details.property');

    // property:非アクティブのルート
    Route::post('/inactive/property', 'InactiveProperty')
      ->name('inactive.property');

    // property:アクティブのルート
    Route::post('/active/property', 'ActiveProperty')
      ->name('active.property');
  });
}); // End Group Admin Middleware
