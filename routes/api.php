<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:api');

Route::group(['namespace' => 'Api'], function () {

	Route::post('/register', 'AuthController@register');
	Route::post('/login', 'AuthController@login');
	Route::post('/forgot-password', 'AuthController@sendForgotPasswordCodeEmail');
	Route::post('/reset-password', 'AuthController@resetPassword');
	
	Route::get('/migrate', 'FolderController@migrate');

	Route::group(['middleware' => 'customAuth'], function () {
		Route::delete('/logout', 'AuthController@logout');
		
		Route::get('/folder/shared', 'FolderController@getSharedFolders');
		Route::put('/folder/{folderId}/share/{shareWithUserId}', 'FolderController@shareFolder');
		Route::delete('/folder/{folderId}/share/{shareWithUserId}', 'FolderController@revokeShareFolderRule');

		Route::resource('/folder', 'FolderController');

		Route::put('/folder/{folderId}/link/{linkId}/folder', 'LinkController@changeLinkFolder');
		Route::resource('/folder/{folderId}/link', 'LinkController');

		Route::put('/user/password/{userId}', 'AuthController@changePassword');
		Route::resource('user', 'UserController');

		Route::get('family/all', 'FamilyController@getAllFamilies');
		Route::post('family/invite', 'FamilyController@inviteToFamily');
		Route::resource('family', 'FamilyController');
		
		Route::resource('recurringIncome', 'RecurringIncomeController');
		Route::resource('income', 'IncomeController');

		Route::resource('incomeType', 'IncomeTypeController');
		Route::resource('incomeFrequency', 'IncomeFrequencyController');

		Route::resource('expenditureType', 'ExpenditureTypeController');
		Route::resource('expenditureFrequency', 'ExpenditureFrequencyController');

		Route::resource('expenditure', 'ExpenditureController');
	});

});
