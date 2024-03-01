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
Route::resource('stocks', 'StockController');
Route::resource('accountmanagment', 'SupplierController');
Route::resource('AccountManagment/AccountTypes', 'AccountTypeController');
Route::resource('AccountManagment/Accounts', 'AccountController');
Route::resource('AccountManagment/Currencies', 'CurrencyController');
Route::resource('AccountManagment/DailyAccountingEntries', 'DailyAccountingEntryController');
Route::get('get_account/{CurrencyID}/{AccountType}', 'AccountController@getAccount');

// purchase routs
Route::resource('purchases', 'PurchaseController');
Route::post('pay_purchase', 'PurchaseController@AddPayment')->name('pay_purchase');
Route::get('get_purchase_payment_details/{PurchaseID}', 'PurchaseController@payment_details');
Route::post('delete_purchase_payment', 'PurchaseController@DeletePayment')->name('delete_purchase_payment');
Route::post('transfare_purchase_payment', 'PurchaseController@Transfare')->name('transfare_purchase_payment');

//sales routs
Route::resource('sales', 'SaleController');
Route::post('pay_sales', 'SaleController@AddPayment')->name('pay_sales');
Route::get('get_sales_payment_details/{salesID}', 'SaleController@payment_details');
Route::post('delete_sales_payment', 'SaleController@DeletePayment')->name('delete_sales_payment');
Route::post('transfare_sales_payment', 'SaleController@Transfare')->name('transfare_sales_payment');






