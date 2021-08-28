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

Route::get('/', 'App\Http\Controllers\PagesController@simulador')->middleware('auth');
Route::POST('/simulador', 'App\Http\Controllers\PagesController@insert_simulador')->middleware('auth')->name('insert_simulador_route');

Route::get('/receitas', 'App\Http\Controllers\PagesController@receitas')->middleware('auth')->name('receitas_route');
Route::POST('/receitas', 'App\Http\Controllers\PagesController@insert_receitas')->middleware('auth')->name('insert_receitas_route');
Route::delete('/receitas', 'App\Http\Controllers\PagesController@delete_receitas')->middleware('auth')->name('delete_receitas_route');
Route::POST('/', 'App\Http\Controllers\PagesController@insert_formulas')->middleware('auth')->name('insert_formulas_route');
Route::delete('/', 'App\Http\Controllers\PagesController@delete_formulas')->middleware('auth')->name('delete_formulas_route');

Route::get('/produtos', 'App\Http\Controllers\PagesController@produtos')->middleware('auth');
Route::POST('/produtos', 'App\Http\Controllers\PagesController@insert_produtos')->middleware('auth')->name('insert_produtos_route');
Route::delete('/produtos', 'App\Http\Controllers\PagesController@delete_produtos')->middleware('auth')->name('delete_produtos_route');
Route::put('/produtos', 'App\Http\Controllers\PagesController@update_produtos')->middleware('auth')->name('update_produtos_route');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
