<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PurchaseDetails extends Model
{
    protected $table = 'purchase_details';

    protected $primaryKey = 'PurchaseDetailsID';

    protected $fillable =
        [
            'PurchaseID',
            'ItemID',
            'ItemQTY',
            'ItemPrice',
            'Transfare',
        ];




    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'PurchaseID', 'PurchaseID');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'ItemID', 'ItemID');
    }
}
