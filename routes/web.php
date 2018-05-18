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

Route::get('/', ["uses" => "HomeController@Index"]);

Route::post('/enqueue', ["uses" => "HomeController@EnqueueResource"]);

Route::get('/resource/{id}', ["uses" => "HomeController@GetResource"]);

Route::get('/resource/{id}/retry', ["uses" => "HomeController@RetryDownload"])
    ->name("retry");

Route::get('/resource/{id}/download', ["uses" => "HomeController@DownloadResource"])
    ->name("download");

Route::get('/resource/{id}/delete', ["uses" => "HomeController@DeleteResource"])
    ->name("delete");