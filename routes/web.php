<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', 'AuthController@index');
Route::get('/register', 'AuthController@showRegisterPage');
Route::post('/login', 'AuthController@login');
Route::delete('/logout', 'AuthController@logout');
Route::get('/forgot-password', 'AuthController@showForgotPasswordPage');
Route::get('/reset-password', 'AuthController@showResetPasswordpage');


Route::get('dashboard', 'DashboardController@index');
Route::get('profile', 'UserController@showProfile');
Route::get('users', 'UserController@showUsers');
