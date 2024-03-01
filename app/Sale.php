<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $table = 'sale';

    protected $primaryKey = 'SaleID';

    protected $fillable = [
        'SaleNumber',
        'CustomerID',
        'CustomerName',
        'AccountID',
        'TotalSale',
        'PaidAmount',
        'SaleStatus',
        'StockID',
        'RestrictionID',
        'Transfer',
        'CurrencyID',
        'AddedBy',
    ];


    public function sale_details()
    {
        return $this->hasMany(SaleDetails::class, 'SaleID', 'SaleID');
    }
    public function sale_payment()
    {
        return $this->hasMany(SalePayment::class, 'SaleID', 'SaleID');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'CustomerID', 'CustomerID');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'AddedBy', 'id');
    }
}
