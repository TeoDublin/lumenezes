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

Route::get('/', 'App\Http\Controllers\PagesController@simulador');
Route::POST('/simulador', 'App\Http\Controllers\PagesController@insert_simulador')->name('insert_simulador_route');

Route::get('/receitas', 'App\Http\Controllers\PagesController@receitas')->name('receitas_route');
Route::POST('/receitas', 'App\Http\Controllers\PagesController@insert_receitas')->name('insert_receitas_route');
Route::delete('/receitas', 'App\Http\Controllers\PagesController@delete_receitas')->name('delete_receitas_route');
Route::POST('/', 'App\Http\Controllers\PagesController@insert_formulas')->name('insert_formulas_route');
Route::delete('/', 'App\Http\Controllers\PagesController@delete_formulas')->name('delete_formulas_route');

Route::get('/produtos', 'App\Http\Controllers\PagesController@produtos');
Route::POST('/produtos', 'App\Http\Controllers\PagesController@insert_produtos')->name('insert_produtos_route');
Route::delete('/produtos', 'App\Http\Controllers\PagesController@delete_produtos')->name('delete_produtos_route');
Route::put('/produtos', 'App\Http\Controllers\PagesController@update_produtos')->name('update_produtos_route');
