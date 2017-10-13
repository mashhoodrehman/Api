<?php

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

Route::get('reset_password/{token}', ['as' => 'password.reset', function($token)
{
    // implement your reset password route here!
}]);

Route::get('/', function () {
    return view('welcome');
});

Route::resource('user','UserController');

Route::get('usr','UserController@userget');
Route::post('usr/up/{id}','UserController@userUpdate')->name('user.val.update');
Route::get('user/del/{id}','UserController@del');
Route::get('promotionlist/','PromotionController@promotionlist');
Route::get('promotion/','PromotionController@create');
Route::post('addpromotion/','PromotionController@addpromotion');

Route::post('user/get/yajra','UserTableController');
Route::post('adduser','UserController@store');
Route::get('/','UserController@login');
Route::post('/adminLogin','UserController@checkLogin');

