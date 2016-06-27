<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', array('as' => 'cardinal.getIndex', 'uses' => 'CardinalController@getIndex'));
Route::get('logout', array('as' => 'cardinal.getLogout', 'uses' => 'CardinalController@getLogout'));
Route::get('opac', array('as' => 'cardinal.getOpac', 'uses' => 'CardinalController@getOpac'));
Route::get('my_reservations', array('as' => 'cardinal.getReservations', 'uses' => 'CardinalController@getReservations'));
Route::get('account_information', array('as' => 'cardinal.getAccountInformation', 'uses' => 'CardinalController@getAccountInformation'));
Route::get('login', array('as' => 'cardinal.getForgotPassword', 'uses' => 'CardinalController@getForgotPassword'));
Route::get('dashboard', array('as' => 'dashboard.getIndex', 'uses' => 'DashboardController@getIndex'));

Route::post('opac/reserve', array('as' => 'cardinal.postReserve', 'uses' => 'CardinalController@postReserve'));
Route::post('opac/cancel_reservation', array('as' => 'cardinal.postCancelReservation', 'uses' => 'CardinalController@postCancelReservation'));
Route::post('data/{key}', array('as' => 'cardinal.postRequestData', 'uses' => 'CardinalController@postRequestData'));
Route::post('login', array('as' => 'cardinal.postLogin', 'uses' => 'CardinalController@postLogin'));