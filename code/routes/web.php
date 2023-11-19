<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CardController;
use App\Http\Controllers\ItemController;

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
@guest
Route::get('/', function () {
    return view('pages.welcome');
})->name('welcome');
@endguest

@auth
Route::get('/', function () {
    return view('pages.main');
})->name('main');
@endauth

Route::get('/user_news', function () {
    return view('pages.user_news');
})->name('user_news');

Route::get('/profile', function () {
    return view('pages.profile');
})->name('profile');

// Create Post
Route::get('/create_post', function () {
    return view('pages.create_post');
})->name('create_post');


// Cards
Route::controller(CardController::class)->group(function () {
    Route::get('/cards', 'list')->name('cards');
    Route::get('/cards/{id}', 'show');
});

// Posts
Route::controller(PostController::class)->group(function () {
    Route::get('/welcome/top', 'listTop')->name('posts');
    Route::get('/welcome/recent', 'listRecent')->name('posts');
    Route::get('/posts/{id}', 'show');
});

// API
Route::controller(CardController::class)->group(function () {
    Route::put('/api/cards', 'create');
    Route::delete('/api/cards/{card_id}', 'delete');
});

Route::controller(ItemController::class)->group(function () {
    Route::put('/api/cards/{card_id}', 'create');
    Route::post('/api/item/{id}', 'update');
    Route::delete('/api/item/{id}', 'delete');
});

// Comment
Route::post('/post/{postId}/comment', 'CommentController@store');

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