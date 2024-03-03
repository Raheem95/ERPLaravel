<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StockTransfareDetails extends Model
{
     protected $table = 'stock_transfare_details';

    protected $primaryKey = 'TransfareDetailsID';

    protected $fillable = [
        'TransfareID',
        'ItemID',
        'ItemQTY',
    ];
    public function transfare()
    {
        return $this->belongsTo(StockTransfare::class, 'TransfareID', 'TransfareID');
    }
    public function item()
    {
        return $this->belongsTo(Item::class, 'ItemID', 'ItemID');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'AddedBy', 'id');
    }
}
