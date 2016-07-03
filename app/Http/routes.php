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
Route::get('borrowed_books', array('as' => 'cardinal.getBorrowedBooks', 'uses' => 'CardinalController@getBorrowedBooks'));
Route::get('account_information', array('as' => 'cardinal.getAccountInformation', 'uses' => 'CardinalController@getAccountInformation'));
Route::get('login', array('as' => 'cardinal.getForgotPassword', 'uses' => 'CardinalController@getForgotPassword'));
Route::get('dashboard', array('as' => 'dashboard.getIndex', 'uses' => 'DashboardController@getIndex'));
Route::get('loan_books', array('as' => 'dashboard.getLoanBooks', 'uses' => 'DashboardController@getLoanBooks'));
Route::get('reserved_books', array('as' => 'dashboard.getReservedBooks', 'uses' => 'DashboardController@getReservedBooks'));
Route::get('receive_books', array('as' => 'dashboard.getReceiveBooks', 'uses' => 'DashboardController@getReceiveBooks'));
Route::get('manage_records/{what}', array('as' => 'dashboard.getManageRecords', 'uses' => 'DashboardController@getManageRecords'));
Route::get('manage_records/barcodes/{id}', array('as' => 'dashboard.getBarcodes', 'uses' => 'DashboardController@getBarcodes'));
Route::get('add_record/{what}', array('as' => 'dashboard.getAddRecord', 'uses' => 'DashboardController@getAddRecord'));
Route::get('edit_record/{what}/{id}', array('as' => 'dashboard.getEditRecord', 'uses' => 'DashboardController@getEditRecord'));

Route::post('opac/reserve', array('as' => 'cardinal.postReserve', 'uses' => 'CardinalController@postReserve'));
Route::post('opac/cancel_reservation', array('as' => 'cardinal.postCancelReservation', 'uses' => 'CardinalController@postCancelReservation'));
Route::post('login', array('as' => 'cardinal.postLogin', 'uses' => 'CardinalController@postLogin'));
Route::post('add_record/{what}', array('as' => 'dashboard.postAddRecord', 'uses' => 'DashboardController@postAddRecord'));
Route::post('add_record/barcodes', array('as' => 'dashboard.postAddBarcode', 'uses' => 'DashboardController@postAddBarcode'));
Route::post('edit_record/{what}/{id}', array('as' => 'dashboard.postEditRecord', 'uses' => 'DashboardController@postEditRecord'));

Route::post('data/{key}', array('as' => 'data.postRequestData', 'uses' => 'DataController@postRequestData'));