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

Route::resource('post', 'PostController');
Route::get('/post/category/{id}', 'PostController@indexByCategory')
    ->name('post.category');
Route::patch('/post/{id}/approve', 'PostController@approve')
    ->name('post.approve')
    ->middleware(['auth', 'admin']);
Route::patch('/post/{id}/disapprove', 'PostController@disapprove')
    ->name('post.disapprove')
    ->middleware(['auth', 'admin']);


//User routes
Route::get('/user', 'UserController@index');
Route::post('/user', 'UserController@store')->name('user.store');
Route::get('/user/{user}', 'UserController@show')->name('user.show');
Route::get('/user/{user}/edit', 'UserController@edit')->name('user.edit');
Route::put('user/{user}', 'UserController@update')->name('user.update');
Route::delete('user/{user}', 'UserController@destroy')->name('user.destroy');

//Category routes
Route::post('/category/store', 'CategoryController@store')
    ->name('category.store')
    ->middleware('auth', 'admin');

Route::delete('/category/{id}', 'CategoryController@destroy')
    ->name('category.destroy');
