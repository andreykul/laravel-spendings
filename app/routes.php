<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::group(array('before' => 'auth'), function(){
	Route::get('/', array('uses' => "AccountsController@index", 'as' => 'home'));
	Route::post('accounts/{accounts}/share', array('uses' => "AccountsController@share", 'as' => 'accounts.share'));
	Route::resource('accounts', 'AccountsController', array('only' => array('index','show','store','destroy')));
	Route::get('accounts/{accounts}/transactions/{transactions}/notes', array('uses' => "TransactionsController@getNotes", 'as' => 'accounts.transactions.notes'));
	Route::post('accounts/{accounts}/transactions/{transactions}/notes', array('uses' => "TransactionsController@postNotes", 'as' => 'accounts.transactions.notes'));
	Route::resource('accounts.transactions', 'TransactionsController', array('only' => array('index','store','destroy')));

});
Route::get('login', array('uses' => 'UsersController@getLogin', 'as' => 'login'));
Route::post('login', array('uses' => 'UsersController@postLogin', 'as' => 'login'));
Route::get('logout', array('uses' => 'UsersController@getLogout', 'as' => 'logout'));
Route::get('register', array('uses' => 'UsersController@getRegister', 'as' => 'register'));
Route::post('register', array('uses' => 'UsersController@postRegister', 'as' => 'register'));
