<?php

use Illuminate\Support\Facades\Route;


use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\PostController;


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

// Home

Route::get('/', function () {
    // If the user is authenticated, redirect to the 'main' page
    // If not authenticated, redirect to the 'welcome' page
    return auth()->check() ? redirect('/main') : redirect('/welcome');
})->name('home');

Route::get('/welcome', function () {
    return view('pages.welcome');
})->name('welcome')->middleware('guest');

Route::get('/main', function () {
    return view('pages.main');
})->name('main')->middleware('auth');


Route::get('/user_news', function () {
    return view('pages.user_news');
})->name('user_news')->middleware('auth');

// User
Route::controller(UserController::class)->group(function () {
    Route::get('/user/{id}/edit', 'update')->name('profile.update');
    Route::get('/user/{id}/delete', 'delete')->name('profile.delete');
    Route::get('/profile/{id}', 'show')->name('profile');
})->middleware('auth');


// Create Post
Route::get('/create_post', function () {
    return view('pages.create_post');
})->name('create_post')->middleware('auth');

// Posts
Route::controller(PostController::class)->group(function () {
    Route::get('/welcome/top', 'listTop')->name('posts');
    Route::get('/welcome/recent', 'listRecent')->name('posts');
    Route::get('/posts/{id}', 'show');
});

// Admin
Route::controller(AdminController::class)->group(function () {
    Route::get('/admin_dashboard', 'index')->name('admin_dashboard');
})->middleware('admin');

// Authentication
Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'authenticate');
    Route::get('/logout', 'logout')->name('logout');
});

Route::controller(RegisterController::class)->group(function () {
    Route::get('/register', 'showRegistrationForm')->name('register');
    Route::post('/register', 'register');
});
?>