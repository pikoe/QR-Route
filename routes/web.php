<?php

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
use App\Http\Controllers\ClientsController;
use App\Http\Controllers\PointsController;
use App\Http\Controllers\RoutesController;

Auth::routes(['register' => false]);

Route::get('/', [ClientsController::class, 'search'])->name('clients.search');

Route::get('start', [PointsController::class, 'startCode'])->name('points.start');
Route::post('check', [PointsController::class, 'checkCode'])->name('points.check');
Route::post('update', [PointsController::class, 'updateLocation'])->name('points.update');


Route::name('admin.')->middleware('auth')->group(function () {
    Route::any('admin/routes', [RoutesController::class, 'admin'])->name('routes');
    Route::any('admin/clients', [ClientsController::class, 'admin'])->name('clients');

	Route::get('admin/codes', function () {
		return view('codes');
	});
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
