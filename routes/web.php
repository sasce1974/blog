<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'PostController@index')->name('post');


/*Route::get('/', function () {
    return view('welcome');
});*/

Auth::routes(['verify'=>true]);

Route::get('/dashboard', 'HomeController@index')
    ->name('dashboard')
    ->middleware(['verified', 'auth']);

Route::resource('role', 'RoleController');

// Post routes
Route::get('/post/search', 'PostController@search')->name('post.search');
Route::resource('post', 'PostController');
Route::get('/post/category/{id}', 'PostController@indexByCategory')
    ->name('post.category');
Route::patch('/post/{id}/approve', 'PostController@approve')
    ->name('post.approve')
    ->middleware(['auth', 'admin']);
Route::patch('/post/{id}/disapprove', 'PostController@disapprove')
    ->name('post.disapprove')
    ->middleware(['auth', 'admin']);
Route::delete('/post/{post}/photo', 'PostController@deletePhoto')
    ->name('post.photo.destroy')
    ->middleware('auth');


//User routes

Route::post('/user', 'UserController@store')->name('user.store');
Route::get('/user/{user}', 'UserController@show')->name('user.show');
Route::get('/user/{user}/edit', 'UserController@edit')->name('user.edit');
Route::put('user/{user}', 'UserController@update')->name('user.update');
Route::delete('user/{user}', 'UserController@destroy')->name('user.destroy');
Route::put('/user/{user}/photo', 'UserController@uploadPhoto')
    ->name('user.photo.store');
Route::delete('/user/{user}/photo', 'UserController@deletePhoto')
    ->name('user.photo.destroy');


//Category routes
Route::post('/category/store', 'CategoryController@store')
    ->name('category.store')
    ->middleware('auth', 'admin');

Route::delete('/category/{id}', 'CategoryController@destroy')
    ->name('category.destroy');

//Comment routes
Route::resource('post.comment', 'CommentController');
Route::post('/comment/{post}/reply', 'CommentController@storeReply')
    ->name('comment.reply');
Route::patch('/comment/{id}/approve', 'CommentController@approve')
    ->name('comment.approve')
    ->middleware(['auth', 'admin']);
Route::patch('/comment/{id}/disapprove', 'CommentController@disapprove')
    ->name('comment.disapprove')
    ->middleware(['auth', 'admin']);


//TESTS

