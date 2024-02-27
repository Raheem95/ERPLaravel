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

// Route::get('/', function () {
//     return view('auth/login');
// });
Auth::routes();
Route::resource('/', 'HomeController');
Route::resource('home', 'HomeController');
Route::resource('categories', 'CategoryController');
Route::resource('items', 'ItemController');
Route::resource('customers', 'CustomerController');
Route::resource('suppliers', 'SupplierController');
Route::resource('accountmanagment', 'SupplierController');
Route::resource('AccountManagment/AccountTypes', 'AccountTypeController');
Route::resource('AccountManagment/Accounts', 'AccountController');
Route::resource('AccountManagment/Currencies', 'CurrencyController');
Route::resource('AccountManagment/DailyAccountingEntries', 'DailyAccountingEntryController');




