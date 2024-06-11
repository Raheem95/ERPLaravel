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
Route::middleware('auth')->group(
    function () {
        Route::resource('/', 'HomeController');
        Route::resource('home', 'HomeController');
        Route::resource('categories', 'CategoryController');
        Route::resource('items', 'ItemController');
        Route::resource('customers', 'CustomerController');
        Route::resource('suppliers', 'SupplierController');

        // Account Routs
        Route::resource('AccountManagment/AccountTypes', 'AccountTypeController');
        Route::resource('AccountManagment/Accounts', 'AccountController');
        Route::get('get_account/{CurrencyID}/{AccountType}', 'AccountController@getAccount');
        Route::resource('AccountManagment/Currencies', 'CurrencyController');
        Route::resource('AccountManagment/DailyAccountingEntries', 'DailyAccountingEntryController');
        Route::resource('AccountManagment/Purchase', 'AccountPurchaseController');
        Route::resource('AccountManagment/Sale', 'AccountSaleController');
        Route::resource('AccountManagment/Expenses', 'ExpenseController');
        Route::resource('AccountManagment/Loans', 'LoanController');
        Route::post('/AccountManagment/Loan/Payment', 'LoanController@Payment');
        Route::get('/AccountManagment/Loans/GetPayments/{LoanID}', 'LoanController@GetPayments');
        Route::post('/AccountManagment/Loans/DeletePayment', 'LoanController@DeletePayment');
        Route::resource('/AccountManagment/Salaries', 'SalaryController');
        Route::get('/Salary/GetNonPaidEmployees/{MonthID}', 'SalaryController@GetNonPaidEmployees');
        Route::get('/Salary/GetEmployeeSalaryDetails/{MonthID}/{EmployeeID}', 'SalaryController@GetEmployeeSalaryDetails');
        Route::resource('/AccountManagment/CreditorsDebtors', 'CreditorsDebtorController');

        //Employees
        Route::resource('Employees', 'EmployeeController');
        Route::post('/Employees/Search', 'EmployeeController@Search');


        // purchase routs
        Route::resource('purchases', 'PurchaseController');
        Route::post('pay_purchase', 'PurchaseController@AddPayment')->name('pay_purchase');
        Route::get('get_purchase_payment_details/{PurchaseID}', 'PurchaseController@payment_details');
        Route::post('delete_purchase_payment', 'PurchaseController@DeletePayment')->name('delete_purchase_payment');
        Route::post('purchase_transfare', 'PurchaseController@Transfare')->name('purchase_transfare');

        //sales routs
        Route::resource('sales', 'SaleController');
        Route::post('pay_sale', 'SaleController@AddPayment')->name('pay_sale');
        Route::get('get_sale_payment_details/{salesID}', 'SaleController@payment_details');
        Route::post('delete_sale_payment', 'SaleController@DeletePayment')->name('delete_sale_payment');
        Route::post('transfare_sales_payment', 'SaleController@Transfare')->name('transfare_sale_payment');
        Route::post('get_item_details', 'SaleController@GetItemDetails')->name('get_item_details');

        // Stock Routs

        Route::resource('Stocks/StockManagment', 'StockController');
        Route::resource('Stocks/Purchases', 'StockPurchaseController');
        Route::post('stock_purchase_transfare', 'StockPurchaseController@Transfare')->name('stock_purchase_transfare');
        Route::resource('Stocks/Sales', 'StockSaleController');
        Route::post('stock_sale_transfare', 'StockSaleController@Transfare')->name('stock_sale_transfare');
        Route::resource('Stocks/Transfare', 'StockTransfareController');
        Route::post('stock_transfare', 'StockTransfareController@Transfare')->name('stock_transfare');
    }
);
