<?php

use Illuminate\Support\Facades\Route;

Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

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
