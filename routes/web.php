<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AgentController;

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

Route::get('/', function () {
  return view('welcome');
});

Route::get('/dashboard', function () {
  return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
  Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
  Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
  Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

// ★admin権限でログインした場合のルートグループ★
Route::middleware(['auth', 'roles:admin'])->group(function () {

  // adminのdashboard
  Route::get('/admin/dashboard', [AdminController::class, 'AdminDashboard'])
    ->name('admin.dashboard');
}); // End Group Admin Middleware


// ★agent権限でログインした場合のルートグループ★
Route::middleware(['auth', 'roles:agent'])->group(function () {

  // agentのdashboard
  Route::get('/agent/dashboard', [AgentController::class, 'AgentDashboard'])
    ->name('agent.dashboard');
}); // End Group Agent Middleware