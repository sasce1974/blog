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

Route::get('/', 'PostController@index');


/*Route::get('/', function () {
    return view('welcome');
});*/

Auth::routes(['verify'=>true]);

Route::get('/home', 'HomeController@index')->name('home')->middleware('verified');

Route::resource('role', 'RoleController');

Route::resource('post', 'PostController')->middleware(['auth']);

Route::get('/user', 'UserController@index');
Route::get('/user/{user}', 'UserController@show')->name('user.show');
Route::get('/user/{user}/edit', 'UserController@edit')->name('user.edit');
Route::put('user/{user}/update', 'UserController@update')->name('user.update');
Route::delete('user/{user}/delete', 'UserController@destroy')->name('user.destroy');
