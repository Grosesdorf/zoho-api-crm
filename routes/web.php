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

Route::get('/oauth2/callback', 'AuthZohoClient@getAccessToken');
Route::get('/oauth2/get-grant-token', 'AuthZohoClient@getGrantToken');
Route::get('/get-answer', 'AnswerController@index');
Route::get('/add-deal', 'DealController@add');
Route::get('/add-task', 'TaskController@add');
