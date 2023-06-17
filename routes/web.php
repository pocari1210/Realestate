<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Backend\PropertyTypeController;
use App\Http\Controllers\Backend\PropertyController;
use App\Http\Controllers\Backend\StateController;
use App\Http\Controllers\Backend\TestimonialController;
use App\Http\Controllers\Backend\BlogCategoryController;
use App\Http\Controllers\Backend\SettingController;
use App\Http\Controllers\Backend\RoleController;
use App\Http\Controllers\Agent\AgentPropertyController;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Http\Controllers\Frontend\IndexController;
use App\Http\Controllers\Frontend\WishlistController;
use App\Http\Controllers\Frontend\CompareController;

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

  // User WishlistAll Route 
  Route::controller(WishlistController::class)->group(function () {

    // お気に入りリストページのルート
    Route::get('/user/wishlist', 'UserWishlist')->name('user.wishlist');

    // お気に入り取得処理のルート
    Route::get('/get-wishlist-property', 'GetWishlistProperty');

    // お気に入り削除処理のルート
    Route::get('/wishlist-remove/{id}', 'WishlistRemove');
  });

  // User Compare All Route 
  Route::controller(CompareController::class)->group(function () {
    // プロパティ比較のルート
    Route::get('/user/compare', 'UserCompare')->name('user.compare');

    // プロパティ比較取得処理のルート
    Route::get('/get-compare-property', 'GetCompareProperty');

    // プロパティ比較削除処理のルート
    Route::get('/compare-remove/{id}', 'CompareRemove');
  });
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

// ★agent権限でログインした場合のルートグループ1★
Route::middleware(['auth', 'role:agent'])->group(function () {

  // agent:dashboard
  Route::get('/agent/dashboard', [AgentController::class, 'AgentDashboard'])
    ->name('agent.dashboard');

  // agent:ログアウト処理のルート
  Route::get('/agent/logout', [AgentController::class, 'AgentLogout'])
    ->name('agent.logout');

  // agent:プロフィール編集のルート
  Route::get('/agent/profile', [AgentController::class, 'AgentProfile'])
    ->name('agent.profile');

  // agent:プロフィール更新のルート
  Route::post('/agent/profile/store', [AgentController::class, 'AgentProfileStore'])
    ->name('agent.profile.store');

  // agent:パスワード変更(編集)のルート
  Route::get('/agent/change/password', [AgentController::class, 'AgentChangePassword'])
    ->name('agent.change.password');

  // agent:パスワード更新のルート
  Route::post('/agent/update/password', [AgentController::class, 'AgentUpdatePassword'])
    ->name('agent.update.password');
}); // End Group Agent Middleware

// ★agent権限でログインした場合のルートグループ2★
Route::middleware(['auth', 'role:agent'])->group(function () {

  // Agent All Property  
  Route::controller(AgentPropertyController::class)->group(function () {

    // Agent:プロパティのトップページのルート
    Route::get('/agent/all/property', 'AgentAllProperty')
      ->name('agent.all.property');

    // Agent:プロパティの追加処理のルート
    Route::get('/agent/add/property', 'AgentAddProperty')
      ->name('agent.add.property');

    // Agent:プロパティの保存処理のルート
    Route::post('/agent/store/property', 'AgentStoreProperty')
      ->name('agent.store.property');

    // Agent:プロパティの編集処理のルート
    Route::get('/agent/edit/property/{id}', 'AgentEditProperty')
      ->name('agent.edit.property');

    // Agent:プロパティの更新処理のルート
    Route::post('/agent/update/property', 'AgentUpdateProperty')
      ->name('agent.update.property');

    // 画像の更新の更新ルート
    Route::post('/agent/update/property/thambnail', 'AgentUpdatePropertyThambnail')
      ->name('agent.update.property.thambnail');

    // 複数画像の更新のルート
    Route::post('/agent/update/property/multiimage', 'AgentUpdatePropertyMultiimage')
      ->name('agent.update.property.multiimage');

    // 複数画像の削除のルート
    Route::get('/agent/property/multiimg/delete/{id}', 'AgentPropertyMultiimgDelete')
      ->name('agent.property.multiimg.delete');

    // 複数画像の追加処理のルート
    Route::post('/agent/store/new/multiimage', 'AgentStoreNewMultiimage')
      ->name('agent.store.new.multiimage');

    // 施設の更新処理のルート
    Route::post('/agent/update/property/facilities', 'AgentUpdatePropertyFacilities')
      ->name('agent.update.property.facilities');

    // Agent:プロパティの詳細のルート
    Route::get('/agent/details/property/{id}', 'AgentDetailsProperty')
      ->name('agent.details.property');

    // Agent:プロパティの削除のルート
    Route::get('/agent/delete/property/{id}', 'AgentDeleteProperty')
      ->name('agent.delete.property');

    // Agent:メッセージのルート
    Route::get('/agent/property/message/', 'AgentPropertyMessage')
      ->name('agent.property.message');

    // Agent:メッセージ詳細のルート
    Route::get('/agent/message/details/{id}', 'AgentMessageDetails')
      ->name('agent.message.details');

    // Agent:内覧予約一覧のルート
    Route::get('/agent/schedule/request/', 'AgentScheduleRequest')
      ->name('agent.schedule.request');

    // Agent:内覧予約詳細のルート
    Route::get('/agent/details/schedule/{id}', 'AgentDetailsSchedule')
      ->name('agent.details.schedule');

    // Agent:内覧予約更新のルート
    Route::post('/agent/update/schedule/', 'AgentUpdateSchedule')
      ->name('agent.update.schedule');
  });

  // Agent:パッケージ購入
  Route::controller(AgentPropertyController::class)->group(function () {

    // Agent:パッケージ購入のトップページのルート
    Route::get('/buy/package', 'BuyPackage')
      ->name('buy.package');

    // Agent:パッケージ購入のルート
    Route::get('/buy/business/plan', 'BuyBusinessPlan')
      ->name('buy.business.plan');

    // Agent:パッケージ保存のルート
    Route::post('/store/business/plan', 'StoreBusinessPlan')
      ->name('store.business.plan');

    // Agent:professionalパッケージ購入のルート
    Route::get('/buy/professional/plan', 'BuyProfessionalPlan')
      ->name('buy.professional.plan');

    // Agent:professionalパッケージ保存のルート
    Route::post('/store/professional/plan', 'StoreProfessionalPlan')
      ->name('store.professional.plan');

    // 販売履歴のトップページのルート
    Route::get('/package/history', 'PackageHistory')
      ->name('package.history');

    // 請求書PDF化処理のルート
    Route::get('/agent/package/invoice/{id}', 'AgentPackageInvoice')
      ->name('agent.package.invoice');
  });
}); // End Group Agent Middleware

// Frontend Property Details All Route 

// Frontend:propertyの詳細のルート
Route::get('/property/details/{id}/{slug}', [IndexController::class, 'PropertyDetails']);

// お気に入り登録のルート
Route::post('/add-to-wishList/{property_id}', [WishlistController::class, 'AddToWishList']);

// プロパティの比較のルート
Route::post('/add-to-compare/{property_id}', [CompareController::class, 'AddToCompare']);

// Send Message from Property Details Page 
Route::post('/property/message', [IndexController::class, 'PropertyMessage'])
  ->name('property.message');

// エージェント詳細のルート
Route::get('/agent/details/{id}', [IndexController::class, 'AgentDetails'])
  ->name('agent.details');

// エージェント詳細からお問い合わせのルート
Route::post('/agent/details/message', [IndexController::class, 'AgentDetailsMessage'])
  ->name('agent.details.message');

// 賃貸リスト表示のルート
Route::get('/rent/property', [IndexController::class, 'RentProperty'])
  ->name('rent.property');

// 購入リストの表示のルート
Route::get('/buy/property', [IndexController::class, 'BuyProperty'])
  ->name('buy.property');

// 賃貸リストと購入リストをcaegryに反映させるルート
Route::get('/property/type/{id}', [IndexController::class, 'PropertyType'])
  ->name('property.type');

// 人気のある地域の詳細のルート
Route::get('/state/details/{id}', [IndexController::class, 'StateDetails'])
  ->name('state.details');

// 購入用物件の検索用ルート
Route::post('/buy/property/search', [IndexController::class, 'BuyPropertySearch'])
  ->name('buy.property.search');

// 賃貸用物件の検索用ルート
Route::post('/rent/property/search', [IndexController::class, 'RentPropertySeach'])
  ->name('rent.property.search');

// 賃貸用物件のルート
Route::post('/all/property/search', [IndexController::class, 'AllPropertySeach'])
  ->name('all.property.search');

//フロントエンド:Blogのルート
Route::get('/blog/details/{slug}', [BlogCategoryController::class, 'BlogDetails']);

// フロントエンド:Blogのカテゴリーのルート
Route::get('/blog/cat/list/{id}', [BlogCategoryController::class, 'BlogCatList']);

// フロントエンド:Blogリストのルート
Route::get('/blog', [BlogCategoryController::class, 'BlogList'])
  ->name('blog.list');

// フロントエンド:Blogのコメントのルート
Route::post('/store/comment', [BlogCategoryController::class, 'StoreComment'])
  ->name('store.comment');

Route::get('/admin/blog/comment', [BlogCategoryController::class, 'AdminBlogComment'])
  ->name('admin.blog.comment');

Route::get('/admin/comment/reply/{id}', [BlogCategoryController::class, 'AdminCommentReply'])
  ->name('admin.comment.reply');

Route::post('/reply/message', [BlogCategoryController::class, 'ReplyMessage'])
  ->name('reply.message');

// 内覧予約のルート
Route::post('/store/schedule', [IndexController::class, 'StoreSchedule'])
  ->name('store.schedule');

// agentのroleでログインしていなかった場合、login画面に遷移
Route::get('/agent/login', [AgentController::class, 'AgentLogin'])
  ->name('agent.login')
  ->middleware(RedirectIfAuthenticated::class);

Route::post('/agent/register', [AgentController::class, 'AgentRegister'])->name('agent.register');

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

    // admin:メッセージのルート
    Route::get('/admin/property/message/', 'AdminPropertyMessage')
      ->name('admin.property.message');
  });

  // Agent All Route from admin 
  Route::controller(AdminController::class)->group(function () {

    // Agent:admin画面に登録情報表記するルート
    Route::get('/all/agent', 'AllAgent')->name('all.agent');

    // Agent:agentを追加するルート
    Route::get('/add/agent', 'AddAgent')
      ->name('add.agent');

    // Agent:agentを保存するルート
    Route::post('/store/agent', 'StoreAgent')
      ->name('store.agent');

    // Agent:agentを編集するルート
    Route::get('/edit/agent/{id}', 'EditAgent')
      ->name('edit.agent');

    // Agent:agentを更新するルート
    Route::post('/update/agent', 'UpdateAgent')
      ->name('update.agent');

    // Agent:agentを削除するルート
    Route::get('/delete/agent/{id}', 'DeleteAgent')
      ->name('delete.agent');

    // Agent:active/inactiveを変更するルート
    Route::get('/changeStatus', 'changeStatus');
  });

  // State  All Route 
  Route::controller(StateController::class)->group(function () {

    Route::get('/all/state', 'AllState')
      ->name('all.state');

    Route::get('/add/state', 'AddState')
      ->name('add.state');

    Route::post('/store/state', 'StoreState')
      ->name('store.state');

    Route::get('/edit/state/{id}', 'EditState')
      ->name('edit.state');

    Route::post('/update/state', 'UpdateState')
      ->name('update.state');

    Route::get('/delete/state/{id}', 'DeleteState')
      ->name('delete.state');
  });

  Route::controller(TestimonialController::class)->group(function () {

    // スタッフの声一覧のルート
    Route::get('/all/testimonials', 'AllTestimonials')
      ->name('all.testimonials');

    // スタッフの声の追加のルート
    Route::get('/add/testimonials', 'AddTestimonials')
      ->name('add.testimonials');

    // スタッフの声の保存のルート
    Route::post('/store/testimonials', 'StoreTestimonials')
      ->name('store.testimonials');

    // スタッフの声の編集のルート
    Route::get('/edit/testimonials/{id}', 'EditTestimonials')
      ->name('edit.testimonials');

    // スタッフの声の更新のルート
    Route::post('/update/testimonials', 'UpdateTestimonials')
      ->name('update.testimonials');

    // スタッフの声の削除のルート
    Route::get('/delete/testimonials/{id}', 'DeleteTestimonials')
      ->name('delete.testimonials');
  });

  Route::controller(BlogCategoryController::class)->group(function () {

    // ブログ一覧のルート
    Route::get('/all/blog/category', 'AllBlogCategory')
      ->name('all.blog.category');

    // ブログ保存のルート
    Route::post('/store/blog/category', 'StoreBlogCategory')
      ->name('store.blog.category');

    // ブログ編集のルート
    Route::get('/blog/category/{id}', 'EditBlogCategory');

    // ブログ更新のルート
    Route::post('/update/blog/category', 'UpdateBlogCategory')
      ->name('update.blog.category');

    // ブログ削除のルート
    Route::get('/delete/blog/category/{id}', 'DeleteBlogCategory')
      ->name('delete.blog.category');
  });

  Route::controller(BlogCategoryController::class)->group(function () {

    Route::get('/all/post', 'AllPost')
      ->name('all.post');

    // ブログ投稿:新規作成のルート
    Route::get('/add/post', 'AddPost')
      ->name('add.post');

    // ブログ投稿:保存処理のルート
    Route::post('/store/post', 'StorePost')
      ->name('store.post');

    // ブログ投稿:編集処理のルート
    Route::get('/edit/post/{id}', 'EditPost')
      ->name('edit.post');

    // ブログ投稿:更新処理のルート
    Route::post('/update/post', 'UpdatePost')
      ->name('update.post');

    // ブログ投稿:削除処理のルート
    Route::get('/delete/post/{id}', 'DeletePost')
      ->name('delete.post');
  });

  // メール関連のルート
  Route::controller(SettingController::class)->group(function () {

    Route::get('/smtp/setting', 'SmtpSetting')
      ->name('smtp.setting');

    Route::post('/update/smpt/setting', 'UpdateSmtpSetting')
      ->name('update.smpt.setting');
  });

  // Site Setting  All Route 
  Route::controller(SettingController::class)->group(function () {

    Route::get('/site/setting', 'SiteSetting')
      ->name('site.setting');

    Route::post('/update/site/setting', 'UpdateSiteSetting')
      ->name('update.site.setting');
  });

  Route::controller(RoleController::class)->group(function () {

    Route::get('/all/permission', 'AllPermission')
      ->name('all.permission');

    // permission:新規作成のルート
    Route::get('/add/permission', 'AddPermission')
      ->name('add.permission');

    // permission:保存のルート
    Route::post('/store/permission', 'StorePermission')
      ->name('store.permission');

    // permission:編集のルート
    Route::get('/edit/permission/{id}', 'EditPermission')
      ->name('edit.permission');

    // permission:更新のルート
    Route::post('/update/permission', 'UpdatePermission')
      ->name('update.permission');

    // permission:削除のルート
    Route::get('/delete/permission/{id}', 'DeletePermission')
      ->name('delete.permission');
  });

  Route::controller(RoleController::class)->group(function () {

    // role:一覧のルート
    Route::get('/all/roles', 'AllRoles')
      ->name('all.roles');

    // role:新規作成のルート
    Route::get('/add/roles', 'AddRoles')
      ->name('add.roles');

    // role:保存のルート
    Route::post('/store/roles', 'StoreRoles')
      ->name('store.roles');

    // role:編集のルート
    Route::get('/edit/roles/{id}', 'EditRoles')
      ->name('edit.roles');

    // role:更新のルート
    Route::post('/update/roles', 'UpdateRoles')
      ->name('update.roles');

    // role:削除のルート
    Route::get('/delete/roles/{id}', 'DeleteRoles')
      ->name('delete.roles');

    Route::get('/add/roles/permission', 'AddRolesPermission')
      ->name('add.roles.permission');

    // ROLE & PERMISSION:保存処理
    Route::post('/role/permission/store', 'RolePermissionStore')
      ->name('role.permission.store');

    // ROLE & PERMISSION:一覧表示
    Route::get('/all/roles/permission', 'AllRolesPermission')
      ->name('all.roles.permission');

    // ROLE & PERMISSION:編集
    Route::get('/admin/edit/roles/{id}', 'AdminEditRoles')
      ->name('admin.edit.roles');

    // ROLE & PERMISSION:更新処理
    Route::post('/admin/roles/update/{id}', 'AdminRolesUpdate')
      ->name('admin.roles.update');

    // ROLE & PERMISSION:削除処理
    Route::get('/admin/delete/roles/{id}', 'AdminDeleteRoles')
      ->name('admin.delete.roles');
  });
  // Admin User All Route 
  Route::controller(AdminController::class)->group(function () {

    // MultiAdmin:一覧
    Route::get('/all/admin', 'AllAdmin')
      ->name('all.admin');

    // MultiAdmin:新規作成
    Route::get('/add/admin', 'AddAdmin')
      ->name('add.admin');

    // MultiAdmin:保存処理
    Route::post('/store/admin', 'StoreAdmin')
      ->name('store.admin');

    // MultiAdmin:編集処理
    Route::get('/edit/admin/{id}', 'EditAdmin')
      ->name('edit.admin');

    // MultiAdmin:更新処理
    Route::post('/update/admin/{id}', 'UpdateAdmin')
      ->name('update.admin');
  });
}); // End Group Admin Middleware
