<?php

use Illuminate\Support\Facades\Route;

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
//     return view('welcome');
// });

Route::get('/', [App\Http\Controllers\LandingPage\HomeController::class, 'index'])->name('home');

Route::get('admin/login', [App\Http\Controllers\Auth\AdminController::class, 'showLoginForm'])->name('admin.login');
Route::post('admin/login', [App\Http\Controllers\Auth\AdminController::class, 'login'])->name('admin.login.submit');
Route::get('admin/logout/', [App\Http\Controllers\Auth\AdminController::class, 'logout'])->name('admin.logout');

Route::group(['middleware' => 'auth:admin'], function(){
    @include('admin.php');
});

// Auth::routes();
Auth::routes(['register' => false, 'login' => false]);

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
