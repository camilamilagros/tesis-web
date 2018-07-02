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

Route::get('/', function () {
    return view('welcome');
});


Route::get('/sentences', 'SentenceController@index');
Route::get('/sentences/translate', 'SentenceController@toTranslate');
Route::post('/sentences/translate', 'SentenceController@translate');
