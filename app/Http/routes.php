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

Auth::routes(['register' => false]);

Route::get('/', function () {
    return view('search');
});

Route::post('check', ['as' => 'points.check', 'uses' => 'PointsController@checkCode']);


Route::name('admin.')->middleware('auth')->group(function () {
    Route::any('admin/routes', ['as' => 'admin.routes', 'uses' => 'RoutesController@admin']);

	Route::get('admin/codes', function () {
		return view('codes');
	});
});


