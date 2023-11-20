<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\CardController;
use App\Http\Controllers\ItemController;
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

//Home
Route::get('/', function () {
    return redirect('/main');
})->name('home');
Route::redirect('/main', '/main/top'); // Redirect /main to /main/top

Route::get('/user_news', function () {
    return view('pages.user_news');
})->name('user_news')->middleware('auth');

Route::get('/search_results', function () {
    return view('pages.search_results');
})->name('search_results');


// User
Route::controller(UserController::class)->group(function () {
    Route::get('/user/{id}/edit', 'update')->name('profile.update');
    Route::get('/user/{id}/delete', 'delete')->name('profile.delete');
    Route::get('/profile/{id}', 'show')->name('profile');
})->middleware('auth');

Route::get('/profile', function () {
    $user = Auth::user();
    return view('pages.profile', compact('user'));
})->name('profile_page');

// Create Post
Route::get('/create_post', function () {
    return view('pages.create_post');
})->name('create_post')->middleware('auth');

// Posts
Route::group(['prefix' => 'main'], function () {
    Route::get('/top', [PostController::class, 'listTop'])->name('posts.top');
    Route::get('/recent', [PostController::class, 'listRecent'])->name('posts.recent');
    Route::post('/search', [PostController::class, 'search'])->name('posts.search');
});

// Individual Post Actions
Route::prefix('posts')->group(function () {
    Route::get('/{id}', [PostController::class, 'show'])->name('posts.show');
    Route::post('/create_post', [PostController::class, 'create'])->name('posts.create');
    Route::delete('/{id}', [PostController::class, 'delete'])->name('posts.delete');
    Route::patch('/{id}', [PostController::class, 'update'])->name('posts.update');
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


Route::controller(UserController::class)->group(function () {
    Route::put('/user/{user}', [UserController::class, 'update'])->name('user.update');
    Route::post('/change-password', [UserController::class, 'change_password'])->name('change.password');
});

?>