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
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ImageController;


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


Route::get('/user_news', function () {
    return view('pages.user_news');
})->name('user_news')->middleware('auth');


// User
Route::controller(UserController::class)->group(function () {
    Route::put('/user/{user}', [UserController::class, 'update'])->name('user.update');
    Route::post('/change-password', [UserController::class, 'change_password'])->name('change.password');
    Route::get('/profile/{id}', [UserController::class, 'show'])->name('profile_page');
    Route::post('/profile/{id}/delete', [UserController::class, 'delete'])->name('profile_delete');
    Route::get('/search', [UserController::class, 'search'])->name('user.search');
    Route::post('/main', [PostController::class, 'applyFilter'])->name('filter.posts.apply');

});

// ImageUser
Route::controller(ImageController::class)->group(function () {
    Route::post('/profile/{id}/image', [ImageController::class, 'create'])->name('image.new');
})->middleware('auth');

// Create Post
Route::get('/create_post', function () {
    return view('pages.create_post');
})->name('create_post')->middleware('auth');

// Main Page Routes
Route::group(['prefix' => 'main'], function () {
    Route::get('/', [PostController::class, 'listTop'])->name('posts.top');
    Route::get('/search', [PostController::class, 'search'])->name('posts.search');
});


//user news
Route::get('/user_news', [PostController::class, 'userNews'])->name('user_news'); 


// Individual Post Actions
Route::prefix('posts')->group(function () {
    Route::get('/{id}', [PostController::class, 'show'])->name('posts.show');
    Route::post('/create_post', [PostController::class, 'create'])->name('posts.create');
    Route::post('/{id}/delete', [PostController::class, 'delete'])->name('posts.delete');
    Route::patch('/{id}/update', [PostController::class, 'update'])->name('posts.update');
});

// Comments
Route::post('/posts/{id}/comments', [CommentController::class, 'create'])->name('comments.create');
Route::get('/comments/{id}/edit', [CommentController::class, 'edit'])->name('comments.edit');
Route::post('/comments/{id}/update', [CommentController::class, 'updateComment'])->name('comments.update');
Route::delete('/comments/{id}/delete', [CommentController::class, 'delete'])->name('comments.delete');

// Admin
Route::controller(AdminController::class)->group(function () {
    Route::get('/admin_dashboard', 'index')->name('admin_dashboard');
    Route::post('/admin_dashboard', 'create')->name('admin.register');
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