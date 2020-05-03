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

/*Route::get('/', function () {
    return view('welcome');
});*/

Route::get('/', 'ChartController@chart')->name('home');
// Route::get('/import', 'ImportController@import');
// Route::get('/wms', 'ChartController@chart')->name('wms');



Route::get('/import','ImportController@index')->name('import');
Route::post('/import','ImportController@showUploadFile');