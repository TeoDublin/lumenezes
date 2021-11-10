<?php

use Illuminate\Support\Facades\Route;

Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/', 'App\Http\Controllers\PagesController@vendas')->middleware('auth');

//Vendas
Route::get('/vendas', 'App\Http\Controllers\PagesController@vendas')->middleware('auth')->name('vendas_route');
Route::POST('/vendas', 'App\Http\Controllers\PagesController@insert_vendas')->middleware('auth')->name('insert_vendas_route');
Route::delete('/vendas', 'App\Http\Controllers\PagesController@delete_vendas')->middleware('auth')->name('delete_vendas_route');

//Simulador
Route::get('/simulador', 'App\Http\Controllers\PagesController@simulador')->middleware('auth')->name('simulador_route');
Route::POST('/simulador', 'App\Http\Controllers\PagesController@simulador_add')->middleware('auth')->name('simulador_add_route');
Route::delete('/simulador', 'App\Http\Controllers\PagesController@delete_simulador')->middleware('auth')->name('delete_simulador_route');
Route::POST('/simulador_add', 'App\Http\Controllers\PagesController@insert_simulador')->middleware('auth')->name('insert_simulador_route');
Route::POST('/simulador_detalhes', 'App\Http\Controllers\PagesController@detalhe_simulador')->middleware('auth')->name('simulador_detalhe_route');
Route::put('/simulador_detalhes', 'App\Http\Controllers\PagesController@update_simulador')->middleware('auth')->name('simulador_update_route');

//Simulador_bolos
Route::get('/simulador_bolos', 'App\Http\Controllers\PagesController@simulador_bolos')->middleware('auth')->name('simulador_bolos_route');
Route::POST('/simulador_bolos', 'App\Http\Controllers\PagesController@simulador_bolos_add')->middleware('auth')->name('simulador_bolos_add_route');
Route::delete('/simulador_bolos', 'App\Http\Controllers\PagesController@delete_simulador_bolos')->middleware('auth')->name('delete_simulador_bolos_route');
Route::POST('/simulador_add_bolos', 'App\Http\Controllers\PagesController@insert_simulador_bolos')->middleware('auth')->name('insert_simulador_bolos_route');
Route::POST('/simulador_detalhes_bolos', 'App\Http\Controllers\PagesController@detalhe_simulador_bolos')->middleware('auth')->name('simulador_detalhe_bolos_route');
Route::put('/simulador_detalhes_bolos', 'App\Http\Controllers\PagesController@update_simulador_bolos')->middleware('auth')->name('simulador_update_bolos_route');

//Receitas
Route::get('/receitas', 'App\Http\Controllers\PagesController@receitas')->middleware('auth')->name('receitas_route');
Route::get('/receitas_bolos', 'App\Http\Controllers\PagesController@receitas_bolos')->middleware('auth')->name('receitas_bolos_route');
Route::get('/receitas_massas', 'App\Http\Controllers\PagesController@receitas_massas')->middleware('auth')->name('receitas_massas_route');
Route::get('/receitas_recheios', 'App\Http\Controllers\PagesController@receitas_recheios')->middleware('auth')->name('receitas_recheios_route');
Route::get('/receitas_coberturas', 'App\Http\Controllers\PagesController@receitas_coberturas')->middleware('auth')->name('receitas_coberturas_route');

Route::POST('/receitas', 'App\Http\Controllers\PagesController@vendas')->middleware('auth')->name('insert_receitas_route');
Route::delete('/receitas', 'App\Http\Controllers\PagesController@delete_receitas')->middleware('auth')->name('delete_receitas_route');

Route::POST('/receitas_bolos', 'App\Http\Controllers\PagesController@insert_receitas_bolos')->middleware('auth')->name('insert_bolos_route');
Route::delete('/receitas_bolos', 'App\Http\Controllers\PagesController@delete_receitas_bolos')->middleware('auth')->name('delete_bolos_route');
Route::POST('/bolo_formulas', 'App\Http\Controllers\PagesController@insert_bolo_formulas')->middleware('auth')->name('insert_bolo_formulas_route');
Route::delete('/bolo_formulas', 'App\Http\Controllers\PagesController@delete_bolo_formulas')->middleware('auth')->name('delete_bolo_formulas_route');

Route::POST('/formulas', 'App\Http\Controllers\PagesController@insert_formulas')->middleware('auth')->name('insert_formulas_route');
Route::delete('/formulas', 'App\Http\Controllers\PagesController@delete_formulas')->middleware('auth')->name('delete_formulas_route');

//Produtos
Route::get('/produtos', 'App\Http\Controllers\PagesController@produtos')->middleware('auth');
Route::POST('/produtos', 'App\Http\Controllers\PagesController@insert_produtos')->middleware('auth')->name('insert_produtos_route');
Route::delete('/produtos', 'App\Http\Controllers\PagesController@delete_produtos')->middleware('auth')->name('delete_produtos_route');
Route::put('/produtos', 'App\Http\Controllers\PagesController@update_produtos')->middleware('auth')->name('update_produtos_route');
