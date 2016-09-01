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

Route::get('/', ['as' => 'cardinal.getIndex', 'uses' => 'CardinalController@getIndex']);
Route::get('logout', ['as' => 'cardinal.getLogout', 'uses' => 'CardinalController@getLogout']);
Route::get('opac', ['as' => 'cardinal.getOpac', 'uses' => 'CardinalController@getOpac']);
Route::get('my_reservations', ['as' => 'cardinal.getReservations', 'uses' => 'CardinalController@getReservations']);
Route::get('borrowed_books', ['as' => 'cardinal.getBorrowedBooks', 'uses' => 'CardinalController@getBorrowedBooks']);
Route::get('account_information/{username?}', ['as' => 'cardinal.getAccountInformation', 'uses' => 'CardinalController@getAccountInformation']);
Route::get('login', ['as' => 'cardinal.getForgotPassword', 'uses' => 'CardinalController@getForgotPassword']);
Route::get('dashboard', ['as' => 'dashboard.getIndex', 'uses' => 'DashboardController@getIndex']);
Route::get('loan_books', ['as' => 'dashboard.getLoanBooks', 'uses' => 'DashboardController@getLoanBooks']);
Route::get('reserved_books', ['as' => 'dashboard.getReservedBooks', 'uses' => 'DashboardController@getReservedBooks']);
Route::get('receive_books', ['as' => 'dashboard.getReceiveBooks', 'uses' => 'DashboardController@getReceiveBooks']);
Route::get('manage_records/{what}', ['as' => 'dashboard.getManageRecords', 'uses' => 'DashboardController@getManageRecords']);
Route::get('manage_records/barcodes/{id}', ['as' => 'dashboard.getBarcodes', 'uses' => 'DashboardController@getBarcodes']);
Route::get('system_settings', ['as' => 'dashboard.getSystemSettings', 'uses' => 'DashboardController@getSystemSettings']);
Route::get('add_record/{what}', ['as' => 'dashboard.getAddRecord', 'uses' => 'DashboardController@getAddRecord']);
Route::get('edit_record/{what}/{id}', ['as' => 'dashboard.getEditRecord', 'uses' => 'DashboardController@getEditRecord']);
Route::get('delete_record/{what}/{id}', ['as' => 'dashboard.getDeleteRecord', 'uses' => 'DashboardController@getDeleteRecord']);
Route::get('recover_book/{id}', ['as' => 'dashboard.getRecoverBook', 'uses' => 'DashboardController@getRecoverBook']);
Route::get('force_change_password/{what}/{id}', ['as' => 'dashboard.getChangePassword', 'uses' => 'DashboardController@getChangePassword']);

Route::post('opac/search', ['as' => 'cardinal.postSearchOpac', 'uses' => 'CardinalController@postSearchOpac']);
Route::post('opac/reserve', ['as' => 'cardinal.postReserve', 'uses' => 'CardinalController@postReserve']);
Route::post('opac/cancel_reservation', ['as' => 'cardinal.postCancelReservation', 'uses' => 'CardinalController@postCancelReservation']);
Route::post('login', ['as' => 'cardinal.postLogin', 'uses' => 'CardinalController@postLogin']);
Route::post('loan_books', ['as' => 'dashboard.postLoanBooks', 'uses' => 'DashboardController@postLoanBooks']);
Route::post('reserved_books', ['as' => 'dashboard.postReservedBooks', 'uses' => 'DashboardController@postReservedBooks']);
Route::post('receive_books', ['as' => 'dashboard.postReceiveBooks', 'uses' => 'DashboardController@postReceiveBooks']);
Route::post('add_record/{what}', ['as' => 'dashboard.postAddRecord', 'uses' => 'DashboardController@postAddRecord']);
Route::post('generate_barcode', ['as' => 'dashboard.postAddBarcode', 'uses' => 'DashboardController@postAddBarcode']);
Route::post('edit_record/{what}/{id}', ['as' => 'dashboard.postEditRecord', 'uses' => 'DashboardController@postEditRecord']);
Route::post('generate_report/{what}', ['as' => 'dashboard.postGenerateReport', 'uses' => 'DashboardController@postGenerateReport']);
Route::post('system_settings/save', ['as' => 'dashboard.postSystemSettings', 'uses' => 'DashboardController@postSystemSettings']);
Route::post('force_change_password/{what}/{id}', ['as' => 'dashboard.postChangePassword', 'uses' => 'DashboardController@postChangePassword']);

Route::post('data/initialize', ['as' => 'data.initialize', 'uses' => 'DataController@initialize']);
Route::post('data/{key}', ['as' => 'data.postRequestData', 'uses' => 'DataController@postRequestData']);
