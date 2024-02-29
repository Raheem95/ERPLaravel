<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $table = 'suppliers';

    public $primaryKey = 'SupplierID';
    protected $fillable = [
        'SupplierName',
        'SupplierPhone',
        'SupplierAdress',
        'AccountID',
        'isCustomer',
        'AddedBy'
    ];

    public function account()
    {
        return $this->belongsTo(Account::class, 'AccountID', 'AccountID');
    }
    public function purchase()
    {
        return $this->hasMany(Purchase::class, 'SupplierID', 'SupplierID');
    }
}
