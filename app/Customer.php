<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{

    protected $table = 'customers';

    public $primaryKey = 'CustomerID';
    protected $fillable = [
        'CustomerName',
        'CustomerPhone',
        'CustomerAdress',
        'AccountID',
        'isSupplier',
        'AddedBy'
    ];
}
