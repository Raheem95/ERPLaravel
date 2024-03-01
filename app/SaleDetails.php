<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SaleDetails extends Model
{
    protected $table = 'sale_details';

    protected $primaryKey = 'SaleDetailsID';

    protected $fillable =
        [
            'SaleID',
            'ItemID',
            'ItemQTY',
            'ItemPrice',
            'Transfare',
        ];




    public function sale()
    {
        return $this->belongsTo(Sale::class, 'SaleID', 'SaleID');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'ItemID', 'ItemID');
    }
}
