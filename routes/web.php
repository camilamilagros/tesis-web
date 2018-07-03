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

Route::get('/translation', 'TranslationController@create');
Route::post('/translation', 'TranslationController@store');

Route::get('/document', 'DocumentController@index');
Route::get('/document/{document}', 'DocumentController@show');

Route::get('/sentence/{sentence}/translation', 'SentenceController@createTranslation');
Route::post('/sentence/{sentence}/translation', 'SentenceController@storeTranslation');

