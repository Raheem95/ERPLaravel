<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PurchasePayment extends Model
{
    protected $table = 'purchase_payment';

    protected $primaryKey = 'PaymentID';

    protected $fillable = [
        'PurchaseID',
        'PaidAmount',
        'RestrictionID',
        'AddedBy',
    ];
    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'PurchaseID', 'PurchaseID');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'AddedBy', 'id');
    }
}
