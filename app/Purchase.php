<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $table = 'purchase';

    protected $primaryKey = 'PurchaseID';

    protected $fillable = [
        'PurchaseNumber',
        'SupplierID',
        'SupplierName',
        'AccountID',
        'TotalPurchase',
        'PaidAmount',
        'PurchaseStatus',
        'StockID',
        'RestrictionID',
        'Transfer',
        'CurrencyID',
        'AddedBy',
    ];


    public function purchase_details()
    {
        return $this->hasMany(PurchaseDetails::class, 'PurchaseID', 'PurchaseID');
    }
    public function purchase_payment()
    {
        return $this->hasMany(PurchasePayment::class, 'PurchaseID', 'PurchaseID');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'SupplierID', 'SupplierID');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'AddedBy', 'id');
    }

}
