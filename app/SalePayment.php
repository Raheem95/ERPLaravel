<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalePayment extends Model
{
    protected $table = 'sale_payment';

    protected $primaryKey = 'PaymentID';

    protected $fillable = [
        'SaleID',
        'PaidAmount',
        'RestrictionID',
        'AddedBy',
    ];
    public function sale()
    {
        return $this->belongsTo(Sale::class, 'SaleID', 'SaleID');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'AddedBy', 'id');
    }
}
