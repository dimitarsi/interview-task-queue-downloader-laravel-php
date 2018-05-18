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

Route::get('/', ["uses" => "HomeController@index"])
    ->name("index");

Route::post('/resource', ["uses" => "HomeController@createResource"])
    ->name("create");

Route::get('/resource/{id}', ["uses" => "HomeController@getResource"]);

Route::get('/resource/{id}/retry', ["uses" => "HomeController@retryDownload"])
    ->name("retry");

Route::get('/resource/{id}/download', ["uses" => "HomeController@downloadResource"])
    ->name("download");

Route::get('/resource/{id}/delete', ["uses" => "HomeController@deleteResource"])
    ->name("delete");