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
Route::get('account_information', array('as' => 'cardinal.getAccountInformation', 'uses' => 'CardinalController@getAccountInformation'));
Route::get('login', array('as' => 'cardinal.getForgotPassword', 'uses' => 'CardinalController@getForgotPassword'));
Route::get('dashboard', array('as' => 'dashboard.getIndex', 'uses' => 'DashboardController@getIndex'));

Route::post('login', array('as' => 'cardinal.postLogin', 'uses' => 'CardinalController@postLogin'));